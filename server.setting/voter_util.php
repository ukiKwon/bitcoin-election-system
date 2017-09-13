<?php
if(!strcmp($_SERVER['SERVER_NAME'], "localhost"))
{
	include_once ("./db/locWeb_config.php");
} else
{
	include_once ("./db/webdb_config.php");
}
include_once("./mysql_util.php");
# SPIT INFO
//vcode : This is about the voter info
define ('VC', [
	'LEN_REG'=>4,
  'LEN_SEX'=>1,
	'LEN_AGE'=>3,
]);
# COMMENT
// give a ballot to the voter
define("GBTV", "give Ballot");
// count the index about the region from the asdb table
function getIndexRegion($link_kas, $_rcode)
{
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
    return $index;
}
// get the address of candidate by using the voter's region info
function indexingCAddr($arr_can, $arr_caddr, $index)
{
    global $arr_caddr;
    for($i=0; $i< count($arr_can); $i++)
    {
      $name=$arr_can[$i];
      $var=exec("../system.op/getadd.sh $name $index"); // getaddress by account
      array_push($arr_caddr, $var);
    }
    // check the address of candidates
    if(!count($arr_caddr))
    { // fail
      echo "\nsytem is not ready";echo "</br>";
    }
    else
    { // success : get the specified address by account along by the voter region
      //print_r($arr_caddr)
      ;
    }
}
//commit to the webdb with the voter info
function setVoterInfo($link_kweb, $_kaddr, $_vcode)
{
		//data
		$_rcode=substr($_vcode, 0, VC['LEN_REG']);
		$_ksex=substr($_vcode, VC['LEN_REG'], VC['LEN_SEX']);
		$_kage=substr($_vcode, VC['LEN_REG'] + VC['LEN_SEX']);
		$_ktoday = date("Y-m-d H:i:s");

		//into KWEBDB
		$sql_vote= "INSERT INTO voter values ('$_kaddr', '$_ktoday','$_rcode','$_ksex','$_kage')";
		$res_vote = mysqli_query($link_kweb, $sql_vote);
		if($res_vote)
		{
				mysqliMsg($res_vote);
		}
		else {
				$errno=mysqli_errno($link_kweb);
				mysqliMsg($errno);
		}
}
 ?>
