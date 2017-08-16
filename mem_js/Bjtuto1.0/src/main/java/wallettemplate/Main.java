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

import com.google.common.util.concurrent.*;
import javafx.scene.input.*;

import org.bitcoinj.core.Block;
import org.bitcoinj.core.NetworkParameters;
import org.bitcoinj.kits.WalletAppKit;
import org.bitcoinj.params.*;
import org.bitcoinj.utils.BriefLogFormatter;
import org.bitcoinj.utils.Threading;
import org.bitcoinj.wallet.DeterministicSeed;
import javafx.application.Application;
import javafx.application.Platform;
import javafx.fxml.FXMLLoader;
import javafx.scene.Node;
import javafx.scene.Scene;
import javafx.scene.layout.Pane;
import javafx.scene.layout.StackPane;
import javafx.stage.Stage;
import wallettemplate.controls.NotificationBarPane;
import wallettemplate.utils.GuiUtils;
import wallettemplate.utils.TextFieldValidator;

import javax.annotation.Nullable;
import java.io.File;
import java.io.IOException;
import java.math.BigInteger;
import java.net.URL;

import static com.google.common.base.Preconditions.checkState;
import static wallettemplate.utils.GuiUtils.*;

public class Main extends Application {
    public static NetworkParameters params = TestNet3Params.get();//네트워크 파라미터 받아오기 (testnet3 받음 --> 주소에 영향)
    public static final String APP_NAME = "simple";
    private static final String WALLET_FILE_NAME = APP_NAME.replaceAll("[^a-zA-Z0-9.-]", "_") + "-"
            + params.getPaymentProtocolId();

    public static WalletAppKit bitcoin;
    public static Main instance;

    private StackPane uiStack; // 컨트롤을 겹쳐서 배치하는 레이아웃
    private Pane mainUI; //PANE 컨테이너를 말함 --> layout 작성시 컨트롤을 쉽게 배치할 수 있도록 도와줌
    public MainController controller; //컨트롤러
    public NotificationBarPane notificationBar;//직접 만든거인듯
    public Stage mainWindow;

    @Override
    public void start(Stage mainWindow) throws Exception {
        try {
            realStart(mainWindow);
        } catch (Throwable e) {
            GuiUtils.crashAlert(e);
            throw e;
        }
    }

    private void realStart(Stage mainWindow) throws IOException {
        this.mainWindow = mainWindow;
        instance = this;  
        // Show the crash dialog for any exceptions that we don't handle and that hit the main loop.
        // 우리가 처리하지 않고 메인 루프와 충돌하는 예외에 대해서 대화상자를 보여줌
        GuiUtils.handleCrashesOnThisThread();

        if (System.getProperty("os.name").toLowerCase().contains("mac")) {//"mac"스타일과 일치 시키는 함수 굳이 필요해 보이지 않음
            // We could match the Mac Aqua style here, except that (a) Modena doesn't look that bad, and (b)
            // the date picker widget is kinda broken in AquaFx and I can't be bothered fixing it.
            // AquaFx.style();
        }

        // Load the GUI. The MainController class will be automagically created and wired up.
        //GUI 로드, maincontroller 클래스는 자동으로 생성되어 유선된다
        URL location = getClass().getResource("main.fxml");
        FXMLLoader loader = new FXMLLoader(location);
        mainUI = loader.load();
        controller = loader.getController();
        // Configure the window with a StackPane so we can overlay things on top of the main UI, and a
        // NotificationBarPane so we can slide messages and progress bars in from the bottom.Note that
        // stackPane으로 창을 구성하여 주 UI위에 물건을 오버레이하고 NotificationBarpane을 배치하여 메시지와 진행 막대를 아래에서부터 밀어낼 수 잇다.
        // ordering of the construction and connection matters here, otherwise we get (harmless) CSS error
        // spew to the logs.
        //이 아이들 사용이유 알아야 할듯
        notificationBar = new NotificationBarPane(mainUI);
        mainWindow.setTitle(APP_NAME);//프로그램 이름
        uiStack = new StackPane();
        Scene scene = new Scene(uiStack);
        TextFieldValidator.configureScene(scene);   // Add CSS that we need.
        scene.getStylesheets().add(getClass().getResource("wallet.css").toString());
        uiStack.getChildren().add(notificationBar);
        mainWindow.setScene(scene);

        // Make log output concise. log출력을 간결하게 만든다?
        BriefLogFormatter.init();
        // Tell bitcoinj to run event handlers on the JavaFX UI thread. This keeps things simple and means
        // bitcoinj에게 jvavFX Ui 스레드에서 이벤트 처리기를 실행하도록 지시. 이것은 간단하게 유지되며
        // we cannot forget to switch threads when adding event handlers. Unfortunately, the DownloadListener
        // 이벤트 처리기를 추가 할 때 스레드 전환을 잊지 못하게 된다. 불행하게도 우리가 앱 킷에 제공하는 DownloadListener는 현재 예외이며 라이브러리 스레드에서 실행된다.
        // we give to the app kit is currently an exception and runs on a library thread. It'll get fixed in
        // a future version.
        Threading.USER_THREAD = Platform::runLater;
        // Create the app kit. It won't do any heavyweight initialization until after we start it.
        setupWalletKit(null);

        if (bitcoin.isChainFileLocked()) {//이미 실행된 상태에서 또 실행하는 경우
            informationalAlert("Already running", "This application is already running and cannot be started twice.");
            Platform.exit();
            return;
        }
        mainWindow.show();

        WalletSetPasswordController.estimateKeyDerivationTimeMsec();//

        bitcoin.addListener(new Service.Listener() {
            @Override
            public void failed(Service.State from, Throwable failure) {
                GuiUtils.crashAlert(failure);
            }
        }, Platform::runLater);
        bitcoin.startAsync();

        scene.getAccelerators().put(KeyCombination.valueOf("Shortcut+F"), () -> bitcoin.peerGroup().getDownloadPeer().close());
    }

    public void setupWalletKit(@Nullable DeterministicSeed seed) {//널 값 넣어줬는디요?
        // If seed is non-null it means we are restoring from backup.
    	// 시드가 널값이 아닌 경우는 백업으로 부터 복원 중이라는 이야기이다
        bitcoin = new WalletAppKit(params, new File("."), WALLET_FILE_NAME) {
            @Override
            protected void onSetupCompleted() {//이 메서드는 모든 객체가 초기화 된 후 피어 그룹 또는 블록 체인 다운로드가 시작되기전에 백그라운드 스레드에서 호출, 여기서 개체 구성을 조정.
                // Don't make the user wait for confirmations for now, as the intention is they're sending it
            	// 유저가 확인을 기다리게 하지 마십시요, 의도대로 그들은 그것을 보내고 잇다???
                // their own money!
                bitcoin.wallet().allowSpendingUnconfirmedTransactions();//unconfirmedTransaction으로 하면 굳이 이 트랜잭션이 블록에 오르고 뒤에 더 붙어서 사용가능한게 될 때까지 안기다린다.
                Platform.runLater(controller::onBitcoinSetup);
            }
        };
        // Now configure and start the appkit. This will take a second or two - we could show a temporary splash screen
        // 이제 앱킷을 구성하고 시작한다, 이 작업은 1-2초 정도 소요, 임시스플래시 화면을 보여줄 수 있다.
        // or progress widget to keep the user engaged whilst we initialise, but we don't.
        // 또는  초기화하는 동안 사용자가 계속 참여하도록 위젯을 진행한다.
        if (params == RegTestParams.get()) {
            bitcoin.connectToLocalHost();   // You should run a regtest mode bitcoind locally.
            //regtest모드 비트코인d는 로컬에서 진행되어야한다.
        }
        bitcoin.setDownloadListener(controller.progressBarUpdater())
               .setBlockingStartup(false)
               .setUserAgent(APP_NAME, "1.0");
        if (seed != null)
            bitcoin.restoreWalletFromSeed(seed);
    }

    private Node stopClickPane = new Pane();

    //<T> --> 제너릭 타입
    public class OverlayUI<T> {
        public Node ui;
        public T controller;

        public OverlayUI(Node ui, T controller) {
            this.ui = ui;
            this.controller = controller;
        }

        public void show() {
            checkGuiThread();
            if (currentOverlay == null) {
                uiStack.getChildren().add(stopClickPane);
                uiStack.getChildren().add(ui);
                blurOut(mainUI);//위치 확인하고
                //darken(mainUI);
                fadeIn(ui);
                zoomIn(ui);
            } else {
                // Do a quick transition between the current overlay and the next.
            	// 현재 오버레이와 다음 오버레이 사이를 빠르게 전환합니다.
                // Bug here: we don't pay attention to changes in outsideClickDismisses.
            	// 버그가 약간 있다?
                explodeOut(currentOverlay.ui);
                fadeOutAndRemove(uiStack, currentOverlay.ui);
                uiStack.getChildren().add(ui);
                ui.setOpacity(0.0);
                fadeIn(ui, 100);
                zoomIn(ui, 100);
            }
            currentOverlay = this;
        }

        public void outsideClickDismisses() {
            stopClickPane.setOnMouseClicked((ev) -> done());
        }

        public void done() {
            checkGuiThread();
            if (ui == null) return;  // In the middle of being dismissed and got an extra click.
            //추가 클릭이 발생했다?
            explodeOut(ui);
            fadeOutAndRemove(uiStack, ui, stopClickPane);
            blurIn(mainUI);
            //undark(mainUI);
            this.ui = null;
            this.controller = null;
            currentOverlay = null;
        }
    }

    @Nullable//null값 사용가능함을 보여줌
    private OverlayUI currentOverlay;

    public <T> OverlayUI<T> overlayUI(Node node, T controller) {
        checkGuiThread();
        OverlayUI<T> pair = new OverlayUI<T>(node, controller);
        // Auto-magically set the overlayUI member, if it's there.
        // overlay ui 멤버가 있으면 자동으로 설정
        try {
            controller.getClass().getField("overlayUI").set(controller, pair);
        } catch (IllegalAccessException | NoSuchFieldException ignored) {
        }
        pair.show();
        return pair;
    }

    /** Loads the FXML file with the given name, blurs out the main UI and puts this one on top. */
    // 지정된 이름으로 FXML 파일을 로드하고, 기본 UI를 불러 아웃하여 맨 위에 배치한다?
    public <T> OverlayUI<T> overlayUI(String name) {
        try {
            checkGuiThread();
            // Load the UI from disk.
            URL location = GuiUtils.getResource(name);
            FXMLLoader loader = new FXMLLoader(location);
            Pane ui = loader.load();
            T controller = loader.getController();
            OverlayUI<T> pair = new OverlayUI<T>(ui, controller);
            // Auto-magically set the overlayUI member, if it's there.
            try {
                if (controller != null)
                    controller.getClass().getField("overlayUI").set(controller, pair);
            } catch (IllegalAccessException | NoSuchFieldException ignored) {
                ignored.printStackTrace();
            }
            pair.show();
            return pair;
        } catch (IOException e) {
            throw new RuntimeException(e);  // Can't happen.
        }
    }

    @Override
    public void stop() throws Exception {
        bitcoin.stopAsync();
        bitcoin.awaitTerminated();
        // Forcibly terminate the JVM because Orchid likes to spew non-daemon threads everywhere.
        // Orchid가 비 데몬 스레드를 어디에나 뿌리고 싶어하기 때문에 강제로 JVM을 종료해야한다.
        Runtime.getRuntime().exit(0);
    }

    public static void main(String[] args) {
        launch(args);
    }
}
