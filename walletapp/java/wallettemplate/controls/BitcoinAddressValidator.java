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

package wallettemplate.controls;

import java.util.HashMap;

import org.bitcoinj.core.Address;
import org.bitcoinj.core.AddressFormatException;
import org.bitcoinj.core.NetworkParameters;
import javafx.scene.Node;
import javafx.scene.control.Button;
import javafx.scene.control.ComboBox;
import javafx.scene.control.TextField;
import wallettemplate.utils.TextFieldValidator;

/**
 * Given a text field, some network params and optionally some nodes, will make the text field an angry red colour
 * if the address is invalid for those params, and enable/disable the nodes.
 */
public class BitcoinAddressValidator {
    private NetworkParameters params;
    public Button nodes;

    public BitcoinAddressValidator(NetworkParameters params, ComboBox string, HashMap<String, String> hmap, Button sendBtn) {
        this.params = params;
        this.nodes = sendBtn;
        string.valueProperty().addListener((observableValue,prev,current)->{toggleButtons(hmap.get(current.toString()));});
        toggleButtons(hmap.get(string.getPromptText().toString()));
    }

    private void toggleButtons(String current) {
        boolean valid = testAddr(current);
        nodes.setDisable(!valid);
    }

    private boolean testAddr(String text) {
        try {
            Address.fromBase58(params, text);
            return true;
        } catch (AddressFormatException e) {
            return false;
        }
    }
}
