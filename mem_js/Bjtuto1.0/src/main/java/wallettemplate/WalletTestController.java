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

import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.scene.control.PasswordField;
import javafx.scene.control.TextField;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

import static wallettemplate.utils.GuiUtils.*;

public class WalletTestController {
    private static final Logger log = LoggerFactory.getLogger(WalletTestController.class);
    public Button closeButton;
    private String teststr = "921109";
    private Integer num;
    public Main.OverlayUI overlayUI;
    public TextField Fname, Fbirth;

    // Note: NOT called by FXMLLoader!
    public void initialize() throws Exception{
    	File f = new File("test.txt");
    	if(f.exists()) {
    		FileReader fis = new FileReader(f);
    		int i;
    		i=fis.read();
    		i= i-48;
    		setNum(i);
    		fis.close();
    	}
    	else {
    		FileWriter stream = new FileWriter("test.txt");
    		stream.write("0");
    		setNum(0);
    		stream.close();
    	}
    }
    
    @FXML
    public void setaccessClicked(ActionEvent event) throws IOException {
    	/*Connection conn = null;
    	try {
    		conn = DriverManager.getConnection("jdbc:mysql://localHost:3306/test-db","testuser","test1234");
    		informationalAlert("DB","접속성공");
    		overlayUI.done();
    	}catch (SQLException sqex) {
    		System.err.println(sqex.getMessage());
    	}finally {
    		try {
    			if(conn != null) {conn.close();}
    		}catch(Exception e) {}
    	}
    }

    public void closeClicked(ActionEvent event) {
        overlayUI.done();
    }*/
    		 test();
    }

	public int getNum() {
		return num;
	}

	public void setNum(int num) {
		this.num = num;
	}
	public void closeClicked(ActionEvent event) {//close버튼 따로 있음 ㅋㅋ
        overlayUI.done();
    }
	public void test() throws IOException{
		URL url = new URL("http://13.124.168.121/db_login.php");
		HttpURLConnection conn = (HttpURLConnection) url.openConnection();
		 conn.setRequestMethod("POST");
		 conn.setDoOutput(true);
		 
		 try (OutputStream out = conn.getOutputStream()) {
	            out.write(("u_name="+URLEncoder.encode(Fname.getText(),"UTF-8")).getBytes());//u_name = 태그
	            out.write("&".getBytes());
	            out.write(("u_reg=" + URLEncoder.encode(Fbirth.getText(),"UTF-8")).getBytes());//u_reg = 태그 같이 날려야함
	        }
		 try (InputStream in = conn.getInputStream();
		            ByteArrayOutputStream out = new ByteArrayOutputStream()) {
		            byte[] buf = new byte[256];//1024 * 8];
		            int length = 0;
		            length = in.read(buf);
		            out.write(buf, 0, length);
		            String result = new String(out.toByteArray());//, "UTF-8");
		            System.out.println(result);
		            String s1 = result.substring(0,1);
		            System.out.println(s1);
		            String s2 = result.substring(1);
		            System.out.println(s2);
		            
		            int i = Integer.parseInt(s1);
		            
		            if(i == 1) 
		            {
		            	informationalAlert("등록된 사용자 입니다","성공");
		            	overlayUI.done();
		            }
		            else{
		            	informationalAlert("등록된 사용자가 아닙니다","실패");
		            }
		            
		            //System.out.println(new String(out.toByteArray(), "UTF-8"));
		        }
		        // 접속 해제
		        conn.disconnect();    
		        }
}