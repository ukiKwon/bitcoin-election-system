<?php
include ("./server_util.php");
include("./db/locAs_config.php");
//include ("./db/locWeb_config.php");

/*
define ("LEN_REG", 4);
define ("LEN_SEX", 1);
define ("LEN_AGE", 1);
*/
# another way
define ('VC', [
	'LEN_REG'=>4,
  'LEN_SEX'=>1,
	'LEN_AGE'=>1,
]);
$arr_can=array();
$str_cans="";
$arr_caddr=array();
//print_r($_POST);
# DATE && CANDIDATE LIST
//date
$today = date("Y-m-d H:i:s");
echo $today."</br></br>";
//candidate
$sz_can = sizeOfpost($arr_can);
setListcanStr($arr_can, $str_cans);
echo " Now The number of candidates registered is ".$sz_can."</br>";


if(isset($_POST['k_json']))
{
    # RECEIVE JSON OBJ : FROM VOTERS
    #	RECEIVE JSON VALUE
    # voter code : region(4 byte) + sex(1 bite) + age(1 byte) .=.31 bite
    # kbk address : 34 bite
    $res=json_decode(stripslashes($_POST['k_json'], true));
    if($res)
    {
        echo $res['k_json'][0]['vcode']."</br>\n";
        echo $res['k_json'][1]['kaddr']."</br>\n";
        // split vcode
        $_rcode=$res['k_json'][0]['vcode'];
        $_rcode=substr($_vcode, 0, (int)LEN_REG);

        echo "_vcode:".$res['k_json'][0]['vcode']."</br>";
        echo "_kaddr:".$res['k_json'][1]['kaddr']."</br>";
        echo "_rcode:".$_rcode."</br>";

        # INDEXING
        for($i=0; $i< $sz_can; $i++)
        {
          $name=$arr_can[$i];
          $var=system("../system.op/getadd.sh $name"); // getaddress by account
          $trimmed=str_replace("\"","",$var);
          $trimmed=str_replace(" ","",$trimmed);
          $trimmed=trim($trimmed,"[]");
          $arr_caddr=explode(",",$trimmed);
          echo $arr_caddr[$_rcode]."</br>";
        }
    }
    else
    {
      echo "</br>"."decoding failed\n";
      if (json_last_error() > 0)
      {
          echo json_last_error_msg() . PHP_EOL;
      }
    }
}
else
{
  echo ">> No json data\n"."</br>";
  //echo "step1.Testing start\n"."</br>";
  # setting test data
  $json_string='{ "sam": [
          { "vcode": "0010m56"},
          { "kaddr": "123456789012345678901234" }
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
	//echo "</br>".">> step2 start"."</br>";
  $_vcode=$data_array["sam"][0]["vcode"];
  $_kaddr=$data_array["sam"][1]["kaddr"];
	# SPLIT vcode
  $_rcode=substr($_vcode, 0, VC['LEN_REG']);
  //echo "_vcode:".$_vcode."</br>";echo "_kaddr:".$_kaddr."</br>";echo "_rcode:".$_rcode."</br>";

	# DB checking && get 'index', region index
  $sql_as = "SELECT rcode FROM regcode order by rcode ASC";
  $res_as = mysqli_query($link_kas, $sql_as);
  $index=0;
  if($res_as)
  {		# Valid name -> NOT PASSED YET
      while($row_as=mysqli_fetch_array($res_as))
      {
        if(!strcmp($row_as[0], $_rcode))
        {
          break;
        }
        ++$index;
      }
      //echo "KEY :".$index;echo "</br>";
  }
  # INDEXING
  echo "</br>";
	indexingAddr($arr_can, $arr_caddr, $index);

	# JSON_DATAtoVOTER
	$retTovoter=array();
	for($i=0; $i<count($arr_can); ++$i)
	{
		$retTovoter["$arr_can[$i]"] = $arr_caddr[$i];
	}
	//var_dump($retTovoter);
	$jsonTovoter=json_encode($retTovoter);	//to json
	$jsonTovoter=json_encode($retTovoter, JSON_UNESCAPED_UNICODE);	//solution : hangul error
	echo $jsonTovoter;
	//var_dump($jsonTovoter);
}
# INTO KWEBDB
/* INSERT INTO KWEBDB */
#$sql_vote= "INSERT INTO kwebdb (kaddr, vote_date, region, sex, age)"
#$sql_man = "SELECT manager FROM kdb where name='$u_name'"; # TO DO : may be this will be problemed.
#$res_man = mysqli_query($link,$sql_man);
#if($res_man)
#
?>

<html>
<head><h1>This is a page for a voter</h1></head>
<body>
	<h4>SEE A ELECTION</h4>
	<input name="showRate" type="submit" action='<?php $_PHPSELF ?>' value="k_rate"/>
</body>

</html>
