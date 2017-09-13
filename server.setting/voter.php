<?php
//ISSUE : echo $_SERVER['HTTP_USER_AGENT']; -> distinguish app and web
if(!strcmp($_SERVER['SERVER_NAME'], "localhost"))
{	include_once ("./db/locAs_config.php");
} else
{
	include_once ("./db/asdb_config.php");
}
include_once ("./server_util.php");
include_once ("./voter_util.php");
include ("./session.php");
# VCODE SPLIT POINT
$arr_can=array();
$str_cans="";
$arr_caddr=array();

# DATE && CANDIDATE LIST
webHeader($arr_can, $str_cans);

# processing post data
if(isset($_POST['k_json']))
{			# RECEIVE JSON OBJ : FROM VOTERS
			#
			# voter code : region(4 byte) + sex(1 bite) + age(1 byte) .=.31 bite
			# kbk address : 34 bite
			//$data_object=json_decode($_POST['k_json']);// parse to php object
			$data_array=json_decode($_POST['k_json'], true);// parse to php array
			if (json_last_error() > 0)
			{		// handle error
		      echo "</br>"."decoding failed\n";
		      echo json_last_error_msg() . PHP_EOL;
		  }
			# JSONtoArray
		  $_vcode=$data_array["k_json"][0]["kaddr"];
		  $_kaddr=$data_array["k_json"][1]["vcode"];
      // split vcode
    	$_rcode=substr($_vcode, 0, VC['LEN_REG']);

      echo "_vcode:".$_vcode."</br>";echo "_kaddr:".$_kaddr."</br>";echo "_rcode:".$_rcode."</br>";

			# DB checking && get 'index', region index
			$index=getIndexRegion($link_kas, $_rcode);
			# INDEXING
			indexingCAddr($arr_can, $arr_caddr, $index);

			# JSON_DATAtoVOTER
			$retTovoter=array();
			for($i=0; $i<count($arr_can); ++$i)
			{
					$retTovoter["$arr_can[$i]"] = $arr_caddr[$i];
			}
			$jsonTovoter=json_encode($retTovoter);	//to json
			$jsonTovoter=json_encode($retTovoter, JSON_UNESCAPED_UNICODE);	//solution : hangul error
			echo $jsonTovoter;

		# SEND BALLOT(VOTE)
		$sendBal=exec("../system.op/sendBallot.sh $_kaddr 'GBTV'");
		if(!$sendBal)
			echo ">> Ballit is given to you";

}
else
{ 	# No Json data
	  // setting test data
	  $json_string='{ "sam": [
						{ "kaddr": "mgfXPsikc7A6pVCDLzgfdba7FS1GcvY6Qc" },
	          { "vcode": "0010m56"}
	      ]}';
		# DECODE JSON
	  $data_object=json_decode($json_string);// parse to php object
	  $data_array=json_decode($json_string, true);// parse to php array

	  if (json_last_error() > 0)
		{		// handle error
	      echo "</br>"."decoding failed\n";
	      echo json_last_error_msg() . PHP_EOL;
	  }
		# JSONtoArray
		$_kaddr=$data_array["sam"][0]["kaddr"];
		$_vcode=$data_array["sam"][1]["vcode"];
		# SPLIT vcode
	  $_rcode=substr($_vcode, 0, VC['LEN_REG']);
	  //echo "_vcode:".$_vcode."</br>";echo "_kaddr:".$_kaddr."</br>";echo "_rcode:".$_rcode."</br>";

		# DB checking && get 'index', region index
	  $index=getIndexRegion($link_kas, $_rcode);
	  # INDEXING
		indexingCAddr($arr_can, $arr_caddr, $index);

		# JSON_DATAtoVOTER
		$retTovoter=array();
		for($i=0; $i<count($arr_can); ++$i)
		{
			$retTovoter["$arr_can[$i]"] = $arr_caddr[$i];
		}
		$jsonTovoter=json_encode($retTovoter);	//to json
		$jsonTovoter=json_encode($retTovoter, JSON_UNESCAPED_UNICODE);	//solution : hangul error

}
# SEND BALLOT(VOTE) : #working
/*
$sendBal=exec("../system.op/sendBallot.sh $_kaddr 'GBTV'");
if(!$sendBal)
		echo ">> Ballit is given to you";
else {
		echo ">> You address is wrong\n";
}
*/
# SAVE VOTER INFO INTO WEBDB
setVoterInfo($link_kweb, $_kaddr, $_vcode);
?>
<?php
//echo $_SERVER['HTTP_USER_AGENT'];
$vApp=strpos($_SERVER['HTTP_USER_AGENT'], "Java");

if(!$vApp)
{		#Device == web
?>
		<html>
			<head><h1>This is a page for a voter</h1></head>
			<body>
				<h4>SEE A ELECTION</h4>
				<input name="showRate" type="submit" action='<?php $_PHPSELF ?>' value="k_rate"/>
				<a href = "./logout.php">Sign Out</a>
			</body>
		</html>
<?php
} else
{		#Device == App
		global $jsonTovoter;
		echo $jsonTovoter;
}
?>
