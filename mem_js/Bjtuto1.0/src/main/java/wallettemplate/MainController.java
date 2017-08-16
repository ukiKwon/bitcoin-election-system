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

import org.bitcoinj.core.listeners.DownloadProgressTracker;
import org.bitcoinj.core.Coin;
import org.bitcoinj.utils.MonetaryFormat;
import javafx.animation.FadeTransition;
import javafx.animation.ParallelTransition;
import javafx.animation.TranslateTransition;
import javafx.event.ActionEvent;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.layout.HBox;
import javafx.util.Duration;
import org.fxmisc.easybind.EasyBind;
import wallettemplate.controls.ClickableBitcoinAddress;
import wallettemplate.controls.NotificationBarPane;
import wallettemplate.utils.BitcoinUIModel;
import wallettemplate.utils.easing.EasingMode;
import wallettemplate.utils.easing.ElasticInterpolator;

import static wallettemplate.Main.bitcoin;

/**
 * Gets created auto-magically by FXMLLoader via reflection. The widget fields are set to the GUI controls they're named
 * after. This class handles all the updates and event handling for the main UI.
 */
// 리플렉션을 통해 FXMLLoader에 의해 자동 생성됩니다. 위젯 필드는 나중에 명명 된 GUI컨트롤로 설정됩니다. 이 클래스는 기본 UI에 대한 모든 업데이트 및 이벤트를 처리합니다
public class MainController {
    public HBox controlsBox;
    public Label balance;
    public Button sendMoneyOutBtn;
    public ClickableBitcoinAddress addressControl; //control에 있음 볼것

    private BitcoinUIModel model = new BitcoinUIModel();//util에 있음 볼것
    private NotificationBarPane.Item syncItem;

    // Called by FXMLLoader.
    public void initialize() {
        addressControl.setOpacity(0.0);
    }

    public void onBitcoinSetup() {
        model.setWallet(bitcoin.wallet());
        //address 보여주는 곳
        //model.addressProperty()에서 입력된 내용이 addressControl에 그대로 입력되는것
        addressControl.addressProperty().bind(model.addressProperty());
        //사용 가능한 코인 보여주는 곳
        balance.textProperty().bind(EasyBind.map(model.balanceProperty(), coin -> MonetaryFormat.BTC.noCode().format(coin).toString()));
        // Don't let the user click send money when the wallet is empty.
        // 지갑이 비어 있을때는 사용자가 send money 할 수 없다.
        //sendMoneyoutbtn을 비활성화 한다 -> model.balanceProperty()가 zero와 같을 때
        sendMoneyOutBtn.disableProperty().bind(model.balanceProperty().isEqualTo(Coin.ZERO));

        showBitcoinSyncMessage();
        model.syncProgressProperty().addListener(x -> {
            if (model.syncProgressProperty().get() >= 1.0) {
                readyToGoAnimation();
                if (syncItem != null) {
                    syncItem.cancel();
                    syncItem = null;
                }
            } else if (syncItem == null) {
                showBitcoinSyncMessage();
            }
        });
    }

    private void showBitcoinSyncMessage() {//시작시에 나오는 바!!
        syncItem = Main.instance.notificationBar.pushItem("Synchronising with the Bitcoin network", model.syncProgressProperty());
    }

    public void sendMoneyOut(ActionEvent event) {//sendmoneyout 버튼
        // Hide this UI and show the send money UI. This UI won't be clickable until the user dismisses send_money.
    	// 이 UI를 숨기고 send_money ui를 띄워라, 이 ui는 send money ui를 끄기 전까지는 다시 클릭할 수 없다
        Main.instance.overlayUI("send_money.fxml");//overlay가 팝업창 처럼 뜨게하는 거인듯
    }

    public void settingsClicked(ActionEvent event) {//setting click 이벤트 처리
        Main.OverlayUI<WalletSettingsController> screen = Main.instance.overlayUI("wallet_settings.fxml");//wallet setting 불러오기
        screen.controller.initialize(null);
    }
    public void accessClicked(ActionEvent event) throws Exception {//access click 이벤트 처리
        Main.OverlayUI<WalletTestController> screen = Main.instance.overlayUI("wallet_test.fxml");//wallet setting 불러오기
        screen.controller.initialize();
    }

    public void restoreFromSeedAnimation() {
        // Buttons slide out ...
    	// 버튼이 사라진다?
        TranslateTransition leave = new TranslateTransition(Duration.millis(1200), controlsBox);
        leave.setByY(80.0);
        leave.play();
    }

    public void readyToGoAnimation() {
        // Buttons slide in and clickable address appears simultaneously.
        TranslateTransition arrive = new TranslateTransition(Duration.millis(1200), controlsBox);
        arrive.setInterpolator(new ElasticInterpolator(EasingMode.EASE_OUT, 1, 2));
        arrive.setToY(0.0);
        FadeTransition reveal = new FadeTransition(Duration.millis(1200), addressControl);
        reveal.setToValue(1.0);
        ParallelTransition group = new ParallelTransition(arrive, reveal);
        group.setDelay(NotificationBarPane.ANIM_OUT_DURATION);
        group.setCycleCount(1);
        group.play();
    }

    public DownloadProgressTracker progressBarUpdater() {
        return model.getDownloadProgressTracker();
    }
}
