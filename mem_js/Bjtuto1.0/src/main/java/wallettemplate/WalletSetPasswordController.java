/*
 * Copyright by the original author or authors.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package wallettemplate;

import com.google.protobuf.*;
import javafx.application.*;
import javafx.event.*;
import javafx.fxml.*;
import javafx.scene.control.*;
import javafx.scene.layout.*;
import org.bitcoinj.crypto.*;
import org.bitcoinj.wallet.*;
import org.slf4j.*;
import org.spongycastle.crypto.params.*;
import wallettemplate.utils.*;

import java.time.*;
import java.util.concurrent.*;

import static wallettemplate.utils.GuiUtils.*;

public class WalletSetPasswordController {
    private static final Logger log = LoggerFactory.getLogger(WalletSetPasswordController.class);
    public PasswordField pass1, pass2;//패스워드 입력으로 들어오는 2개 값인듯

    public ProgressIndicator progressMeter;
    public GridPane widgetGrid;//그리드 패널 표처럼 만들어짐 --> (창문처럼)
    public Button closeButton;
    public Label explanationLabel;

    public Main.OverlayUI overlayUI;
    // These params were determined empirically on a top-range (as of 2014) MacBook Pro with native scrypt support,
    // using the scryptenc command line tool from the original scrypt distribution, given a memory limit of 40mb.
    // 이 매개 변수는 메모리 제한이 40MB인 경우 원래의 스킯트 배포에서 scryptenc 명령 줄 도구를 사용하여 네이티브 암호화 지원이 있는
    // 최상위 범위의 MacBook pro에서 경험적으로 결정되었다.. 걍 쓰자..
    public static final Protos.ScryptParameters SCRYPT_PARAMETERS = Protos.ScryptParameters.newBuilder()
            .setP(6)
            .setR(8)
            .setN(32768)
            .setSalt(ByteString.copyFrom(KeyCrypterScrypt.randomSalt()))
            .build();

    public void initialize() {
        progressMeter.setOpacity(0);//opacity 불투명
    }

    public static Duration estimatedKeyDerivationTime = null;

    public static CompletableFuture<Duration> estimateKeyDerivationTimeMsec() {//근데 이거 왜 하는 걸까?
        // This is run in the background after startup. If we haven't recorded it before, do a key derivation to see
        // how long it takes. This helps us produce better progress feedback, as on Windows we don't currently have a
        // native Scrypt impl and the Java version is ~3 times slower, plus it depends a lot on CPU speed.
    	// 시작 후 백그라운드에서 실행된다. 이전에 기록하지 않은 경우 키 유도를 사용하여 소요 시간을 확인, 더 나은 진행 피드백을 생성하는데 도움이 되며, window에서 현재 기본 scrypt impl을 갖고 있지 않으며
    	// 자바 버전이 ~3 배 느리고 CPU속도에 많이 의존핮다.
        CompletableFuture<Duration> future = new CompletableFuture<>();
        new Thread(() -> {//test하는 거랑 비슷한거 같아요
            log.info("Doing background test key derivation");
            KeyCrypterScrypt scrypt = new KeyCrypterScrypt(SCRYPT_PARAMETERS);
            long start = System.currentTimeMillis();
            scrypt.deriveKey("test password");
            long msec = System.currentTimeMillis() - start;
            log.info("Background test key derivation took {}msec", msec);
            Platform.runLater(() -> {
                estimatedKeyDerivationTime = Duration.ofMillis(msec);
                future.complete(estimatedKeyDerivationTime);
            });
        }).start();
        return future;
    }

    @FXML
    public void setPasswordClicked(ActionEvent event) {//이벤트 동작 --> setting에서 지갑 패스워드 설정하는 것
        if (!pass1.getText().equals(pass2.getText())) {
            informationalAlert("Passwords do not match", "Try re-typing your chosen passwords.");
            return;
        }
        String password = pass1.getText();
        // This is kind of arbitrary and we could do much more to help people pick strong passwords.
        if (password.length() < 4) {
            informationalAlert("Password too short", "You need to pick a password at least five characters or longer.");
            return;
        }

        fadeIn(progressMeter);
        fadeOut(widgetGrid);
        fadeOut(explanationLabel);
        fadeOut(closeButton);


        //지정된  Scrypt 매개 변수를 사용한 암호화/ 암호 해독
        KeyCrypterScrypt scrypt = new KeyCrypterScrypt(SCRYPT_PARAMETERS);

        // Deriving the actual key runs on a background thread. 500msec is empirical on my laptop (actual val is more like 333 but we give padding time).
        // 실제 키 파생은 백그라운드 스레드에서 이루어진다. 
        //백그라운드 진행
        KeyDerivationTasks tasks = new KeyDerivationTasks(scrypt, password, estimatedKeyDerivationTime) {
            @Override
            protected final void onFinish(KeyParameter aesKey, int timeTakenMsec) {
                // Write the target time to the wallet so we can make the progress bar work when entering the password.
            	// 지갑에 목표 시간을 입력하라 그러면 진행상황 막대가 작동하도록 가능
                WalletPasswordController.setTargetTime(Duration.ofMillis(timeTakenMsec));
                // The actual encryption part doesn't take very long as most private keys are derived on demand.
                log.info("Key derived, now encrypting");
                Main.bitcoin.wallet().encrypt(scrypt, aesKey);
                log.info("Encryption done");
                //이건 실제로 뜨는 팝업창
                informationalAlert("Wallet encrypted",
                        "You can remove the password at any time from the settings screen.");
                overlayUI.done();//mainui로 복귀
            }
        };
        //Ui에 직접 보여짐
        progressMeter.progressProperty().bind(tasks.progress);
        tasks.start();
    }

    public void closeClicked(ActionEvent event) {//close버튼 따로 있음 ㅋㅋ
        overlayUI.done();
    }
}
