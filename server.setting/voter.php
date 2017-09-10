
/* split vcode */

/* INSERT INTO KWEBDB */
#$sql_vote= "INSERT INTO kwebdb (kaddr, vote_date, region, sex, age)"
#$sql_man = "SELECT manager FROM kdb where name='$u_name'"; # TO DO : may be this will be problemed.
#$res_man = mysqli_query($link,$sql_man);
#if($res_man)
#?>
<?php
	$sample=array('vcode'=>"0090m24",'kaddr'=>"1234567890123456789011234");
	$sendTothis=json_encode($sample);
?>
<?php
  include ("./db/locWeb_config.php");
	include ("./server_util.php");

	define('VCODE', [
		"LENGTH_REGION"=> 4,
		"LENGTH_SEX"=> 1,
		"LENGTH_AGE"=> 1
	])
	define("LENGTH_REGION", 4, "true");
	define("LENGTH_SEX", 1, "true");
	define("LENGTH_AGE", 1,"true");

	# RECEIVE JSON OBJ FROM THE VOTER APP
	$json_vcode = json_decode(file_get_contents('php://input'));
	$_vcode=$json_vcode->vcode;//var_dump($json_vcode->vcode);
	$_kaddr=$json_vcode->kaddr;//var_dump($json_vcode->kaddr);
	$_rcode=substr($_vcode, 0, VCODE['LENGTH_REGION']);
	if(!$_rcode)
			echo "<script>console.log("substring is failed!");</script>";
	# SUBSTRING VCDOE

	# INDEXING
	// post data
	$array_can=array();
	$array_CAddr=array();
	$array_sz=0;

	// Read Post parameter
	# View Date info
	$today = date("Y-m-d H:i:s");
	echo $today."</br></br>";
	# View Candidate info
	$array_sz = sizeOfpost($array_can);
	echo " Now The number of candidates registered is ".$array_sz."</br>";

	$index=$_rcode; /* region code */

	for($i=0; $i< $array_sz; $i++)
	{
	  $name=$array_can[$i];
	  $arr_addr=system("./getadd.sh $name"); /* getaddress by account */
	  $trimmed=str_replace("\"","",$arr_addr);
	  $trimmed=str_replace(" ","",$trimmed);
	  $trimmed=trim($trimmed,"[]");
	  $res=explode(",",$trimmed);
	  echo $res[$index]."</br>";
		$array_CAddr.array_push($res[index]);
	}
?>
<html>
<head><h1>This is a page for a voter</h1></head>
<body>
	<h4>SEE A ELECTION</h4>
	<input name="��ǥ��Ȳ" type="submit" action='<?php $_PHPSELF ?>' value="count of votes"/>
</body>

</html>
