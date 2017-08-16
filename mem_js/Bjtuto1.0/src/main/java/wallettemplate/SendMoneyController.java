/*
 * Copyright by the original author or authors
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

//중요!!! transaction 나오심다
package wallettemplate;

import javafx.scene.layout.HBox;

import org.bitcoinj.core.*;
import org.bitcoinj.wallet.SendRequest;
import org.bitcoinj.wallet.Wallet;

import com.google.common.util.concurrent.FutureCallback;
import com.google.common.util.concurrent.Futures;
import com.sun.javafx.collections.MappingChange.Map;

import javafx.beans.property.SimpleStringProperty;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.ComboBox;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import org.spongycastle.crypto.params.KeyParameter;
import wallettemplate.controls.BitcoinAddressValidator;
import wallettemplate.utils.TextFieldValidator;
import wallettemplate.utils.WTUtils;

import static com.google.common.base.Preconditions.checkState;
import static wallettemplate.utils.GuiUtils.*;

import java.net.URL;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map.Entry;
import java.util.ResourceBundle;

import javax.annotation.Nullable;

public class SendMoneyController implements Initializable {
    public Button sendBtn;
    public Button cancelBtn;
    public TextField address;
    public Label titleLabel;//넌 어디에?
    public TextField amountEdit;
    public Label btcLabel;

    public Main.OverlayUI overlayUI;

    private Wallet.SendResult sendResult;
    private KeyParameter aesKey;
    //////////////////////////////////////////////////////////////////////////////
    //hashmap
    public HashMap<String,String> Hmap = new HashMap<String,String>();
    @FXML
    private ComboBox<String> Caddress;
    private ObservableList<String> list = FXCollections.observableArrayList();
    
    // Called by FXMLLoader
    public void initialize() {//maincontroller에서 불러줌
        Coin balance = Main.bitcoin.wallet().getBalance();//getbalance = AVAILABLE찾음
        checkState(!balance.isZero());
        //new BitcoinAddressValidator(Main.params, address, sendBtn);//요고 유효성 검사
        new TextFieldValidator(amountEdit, text ->
                !WTUtils.didThrow(() -> checkState(Coin.parseCoin(text).compareTo(balance) <= 0)));
        amountEdit.setText(balance.toPlainString());
        hinit();
    }
    
    public void cancel(ActionEvent event) {
        overlayUI.done();
    }

    public void send(ActionEvent event) {//중요!!
        // Address exception cannot happen as we validated it beforehand.
    	// 주소 유효성 검사는 사전에 유효성을 검사 했으므로 발생할 수 없습니다.
    	//int j = Integer.parseInt(amountEdit.getText());
    	//for(int i=0;i<j;i++) {
        try {
            Coin amount = Coin.parseCoin(amountEdit.getText());
            Address destination = Address.fromBase58(Main.params, Hmap.get(Caddress.getValue()));
            SendRequest req;
            if (amount.equals(Main.bitcoin.wallet().getBalance()))
            	//sendRequest.class에 있음 
                req = SendRequest.emptyWallet(destination);
            else
                req = SendRequest.to(destination, amount);
            req.aesKey = aesKey;
            sendResult = Main.bitcoin.wallet().sendCoins(req);
            Futures.addCallback(sendResult.broadcastComplete, new FutureCallback<Transaction>() {
                @Override
                public void onSuccess(@Nullable Transaction result) {
                    checkGuiThread();
                    //overlayUI.done();
                    //informationalAlert("성공","성공");
                }

                @Override
                public void onFailure(Throwable t) {
                    // We died trying to empty the wallet.
                    crashAlert(t);
                }
            });
            sendResult.tx.getConfidence().addEventListener((tx, reason) -> {
                if (reason == TransactionConfidence.Listener.ChangeReason.SEEN_PEERS)
                    updateTitleForBroadcast();
            });
            sendBtn.setDisable(true);
            address.setDisable(true);
            ((HBox)amountEdit.getParent()).getChildren().remove(amountEdit);
            ((HBox)btcLabel.getParent()).getChildren().remove(btcLabel);
            updateTitleForBroadcast();
        }catch (InsufficientMoneyException e) {
            informationalAlert("Could not empty the wallet",
                    "You may have too little money left in the wallet to make a transaction.");
            overlayUI.done();
        } catch (ECKey.KeyIsEncryptedException e) {
            askForPasswordAndRetry();
        }
    }

    private void askForPasswordAndRetry() {
        Main.OverlayUI<WalletPasswordController> pwd = Main.instance.overlayUI("wallet_password.fxml");
        final String addressStr = address.getText();
        final String amountStr = amountEdit.getText();
        pwd.controller.aesKeyProperty().addListener((observable, old, cur) -> {
            // We only get here if the user found the right password. If they don't or they cancel, we end up back on
            // the main UI screen. By now the send money screen is history so we must recreate it.
            checkGuiThread();
            Main.OverlayUI<SendMoneyController> screen = Main.instance.overlayUI("send_money.fxml");
            screen.controller.aesKey = cur;
            screen.controller.address.setText(addressStr);
            screen.controller.amountEdit.setText(amountStr);
            screen.controller.send(null);
        });
    }

    private void updateTitleForBroadcast() {
        final int peers = sendResult.tx.getConfidence().numBroadcastPeers();
        titleLabel.setText(String.format("Broadcasting ... seen by %d peers", peers));
    }

	@Override
	public void initialize(URL arg0, ResourceBundle arg1) {
		Coin balance = Main.bitcoin.wallet().getBalance();//getbalance = AVAILABLE찾음
        checkState(!balance.isZero());
        hinit();
        Caddress.setItems(list);
        new BitcoinAddressValidator(Main.params, Caddress,Hmap, sendBtn);//요고 유효성 검사
        //new TextFieldValidator(amountEdit, text ->
        //      !WTUtils.didThrow(() -> checkState(Coin.parseCoin(text).compareTo(balance) <= 0)));
        //amountEdit.setText(balance.toPlainString());
	}
	
	public void hinit() {//
		Hmap.put("후보자 선택", "0");
		Hmap.put("1번","1111");
		Hmap.put("2번","mxXRAqb1HzKHavU58L1WNgTtEh1acbNc7B");
		Hmap.put("3번","3333");
		list.add("1번");
		list.add("2번");
	}
}
