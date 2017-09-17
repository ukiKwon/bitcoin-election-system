package wallettemplate;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.Scanner;

import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

public class Voter {

	public static void start() throws ParseException {//Vcode+유권자 지갑 주소+후보자 이름을 넘겨 주고 투표권, 후보자 지갑 주소를 받는다
		String Cstr = null;
		
		//VoterInfo/////////////////////////////////////
		JSONObject voterInfo = new JSONObject();
		String str1 = Main.Vcode;
		String str2 = Main.bitcoin.wallet().currentReceiveAddress().toString();
		String str3 = Main.Cname;
		voterInfo.put("vcode", str1);
		voterInfo.put("kaddr", str2);
		voterInfo.put("cname", str3);
		//////////////////////////////////////////
		try {
    		//php사용
    		URL url = new URL("http://13.124.112.35/KBK_election/server.setting/voter.php");
    		HttpURLConnection conn = (HttpURLConnection)url.openConnection();
    		conn.setRequestMethod("POST");//Post방식
    		conn.setDoOutput(true);
    	
    		try(OutputStream out = conn.getOutputStream()){
    			out.write(("k_json="+URLEncoder.encode(voterInfo.toString(),"UTF-8")).getBytes());
    		}
    		InputStream is = conn.getInputStream();
    		Scanner scan = new Scanner(is,"UTF-8");
    	
    		int line = 1;
    		while(scan.hasNext()) {
    			String str4 = scan.nextLine();
    			if(line == 16) {
    				System.out.println("str4 :" + str4);//확인용
    				Cstr = str4;//후보자 지역 주소 들어있는 Json
    				break;
    				}
    			line++;
    		}
    		scan.close();
    	}catch(IOException e) {
    		System.out.println("Error");
    	}
		//Json Parse 시작 Test 필요
		JSONObject candiInfo = new JSONObject();
		JSONParser parser = new JSONParser();
		candiInfo = (JSONObject)parser.parse(Cstr);
		for(int i=0; i<Main.Clist.size(); i++) {
			String s = (String)candiInfo.get(Main.Clist.get(i));
			System.out.println(s);
			Main.CAlist.add(s);
		}
	}
}
