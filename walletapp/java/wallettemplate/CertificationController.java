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
import sun.rmi.runtime.Log;

import org.json.simple.parser.ParseException;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.util.Scanner;

import static wallettemplate.utils.GuiUtils.*;

public class CertificationController {//본인 인증
    private static final Logger log = LoggerFactory.getLogger(CertificationController.class);
    public Button closeButton;
    public Main.OverlayUI overlayUI;
    public TextField Fname, Fbirth;
    public String Faddr;
    public String[] Candi;

    // Note: NOT called by FXMLLoader!
    public void initialize() throws Exception{
    	Faddr=Main.bitcoin.wallet().currentReceiveAddress().toString();
    }
    
    @FXML
    public void setaccessClicked(ActionEvent event) throws IOException {
    		 startAs();
    }
	public void closeClicked(ActionEvent event) {//close버튼
        overlayUI.done();
    }
	
	
	//본인 인증 실질 .. 따로 빼야하는데..
	public void startAs() throws IOException{
		URL url = new URL("http://13.124.112.35/KBK_election/server.setting/index.php");//인증서버 주소
		HttpURLConnection conn = (HttpURLConnection) url.openConnection();
		 conn.setRequestMethod("POST");
		 conn.setDoOutput(true);
		  
		 try (OutputStream out = conn.getOutputStream()) {//Post로 이름,주민번호 Post
	            out.write(("u_name="+URLEncoder.encode(Fname.getText(),"UTF-8")).getBytes());//u_name = 태그
	            out.write("&".getBytes()); 
	            out.write(("u_reg=" + URLEncoder.encode(Fbirth.getText(),"UTF-8")).getBytes());//u_reg = 태그 같이 날려야함
	        }
		InputStream is = conn.getInputStream();
		Scanner scan = new Scanner(is,"UTF-8");
		
		
 		//페이지에 뜨는 내용 중 원하는 부분만 따와서 사용(성공여부+Vcode(지역코드,나이,성별)+후보자이름)
		int line = 1;
		String res = null;
		while(scan.hasNext()) {
			String str3 = scan.nextLine();
			if(line == 12) {
			System.out.println("str1 :" + str3);
			res = str3;
			res.substring(0,1);
			str3 = scan.nextLine();
			System.out.println("str2 :" +str3);
			Main.Vcode = str3;
			str3 = scan.nextLine();
			System.out.println("str3 :" +str3);
			Main.Cname = str3;
			Candi = str3.split(",");
			break;
			}
			line++;
			}
		scan.close();
		//후보자 이름 세팅 Test 필요
		for(int i=0; i<Candi.length; i++) {
			Main.Clist.add(Candi[i]);
		}
		//성공여부에 따른 동작
		if(res != "0") {
			informationalAlert("등록된 사용자 입니다","성공",0);
			try {
				//투표권 요청, 지역에 따른 후보자 주소 요청
				Voter.start();
				//Json Parser때문에 예외처리
			} catch (ParseException e) {
				e.printStackTrace();
			}
		    overlayUI.done();
		    }
		else{//앞이 0 or 4면 실패
		    informationalAlert("등록된 사용자가 아닙니다","실패",0);
		    return;
		    }
		conn.disconnect(); 
		} 
	}