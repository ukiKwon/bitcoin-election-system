<meta charset="utf-8">
<?php
//ISSUE : echo $_SERVER['HTTP_USER_AGENT']; -> distinguish app and web
if(!strcmp($_SERVER['SERVER_NAME'], "localhost"))
{
		include_once ("./db/locAs_config.php");
} else
{
		include_once ("./db/asdb_config.php");
}
include_once ("./server_util.php");
include_once ("./voter_util.php");

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
		     		 echo "</br>\n"."decoding failed\n";
		      		 echo json_last_error_msg() . PHP_EOL;
		  }
			# JSONtoArray
	  	$_kaddr=$data_array["kaddr"];
	  	$_vcode=$data_array["vcode"];
			$_kcans=$data_array["cname"];
			// split vcode
			$_rcode=substr($_vcode, 0, VC['LEN_REG']);
 			#echo "</br>\n"."_vcode:".$_vcode."</br>\n";echo "_kaddr:".$_kaddr."</br>\n";echo "_rcode:".$_rcode."</br>\n";

			# DB checking && get 'index', region index
			$index=getIndexRegion($link_kas, $_rcode);
			#echo "</br>\n"."rcode ------->>index :".$index."</br>\n";

			# If Voter App use this
			$arr_can=explode(",", $_kcans);

			# INDEXING
			indexingCAddr($arr_can, $arr_caddr, $index);

			# JSON_DATA for VOTER
			$candidateJson=setJsonfrom($arr_can, $arr_caddr);
}
else
{ 	# No Json data
	  // setting test data
	  $json_string='{ "sam": [
								{ "kaddr": "mifXPsikc7A6pVCDLzgfdba7FS1GcvY6Qc" },
	          		{ "vcode": "0001m55"}
	      				]}';
		# DECODE JSON
	  #$data_object=json_decode($json_string);// parse to php object
	  $data_array=json_decode($json_string, true);// parse to php array
		#var_dump($data_array);
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

		# JSON_DATA for VOTER
		$candidateJson=setJsonfrom($arr_can, $arr_caddr);
}
# SAVE VOTER INFO INTO WEBDB
$_ballot=setVoterInfo($link_kweb, $_kaddr, $_vcode);

# SEND BALLOT(VOTE)
_sendBallot($_ballot, $_kaddr);

?>
<?php
$vApp=strpos($_SERVER['HTTP_USER_AGENT'], "Java");

if($vApp === false)
{		#Device == web
?>
		<html>
			<head><h1>This is a page for a voter</h1></head>
			<body>
				<h4>SEE A ELECTION</h4>
				<input name="showRate" type="submit" action='<?php $_PHPSELF ?>' value="k_rate"/>
				<input name="logout" type="submit" action="./logout.php" value="logout"/>
			</body>
		</html>
<?php
} else
{		#Device == App
		global $candidateJson;
		echo "\n";
		echo $candidateJson;
}
?>
