<?php
if(!strcmp($_SERVER['SERVER_NAME'], "localhost"))
{
	include_once ("./db/locWeb_config.php");
} else
{
	include_once ("./db/webdb_config.php");
}
include_once("./mysql_util.php");
include_once("./server_util.php");

# SPIT INFO
//vcode : This is about the voter info
define ('VC', [
	'LEN_REG'=>4,
  	'LEN_SEX'=>1, 'LEN_AGE'=>3, ]);
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
    #echo "</br>\n"."This main algo(indexing)part"."</br>\n";
    #echo "</br>\n"."candidate are ";print_r($arr_can);echo "</br>\n";
    #echo "</br>\n"."region is :".$index."</br>\n";
    for($i=0; $i< count($arr_can); $i++)
    {
      $name=$arr_can[$i];
      $ret=exec("../system.op/getadd.sh $name $index"); // getaddress by account
      retBashMsg($_SERVER['SCRIPT_NAME'], $ret);
      array_push($arr_caddr, $ret);
    }
    // check the address of candidates
    if(!count($arr_caddr))
    { // fail
      consoleMsg(">> system is not ready. Check bitcoind run or candidate address is setting");
    }
    else
    { // success : get the specified address by account along by the voter region
      consoleMsg(">> system is ready");
      #print_r($arr_caddr)
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
		$_ktoday=date("Y-m-d H:i:s");

		//into KWEBDB
		$sql_vote= "INSERT INTO voter values ('$_kaddr', '$_ktoday','$_rcode','$_ksex','$_kage')";
		$res_vote = mysqli_query($link_kweb, $sql_vote);
		if($res_vote === true)
		{
				mysqliMsg($res_vote);
		}
		else {
				$errno=mysqli_errno($link_kweb);
				mysqliMsg($errno);
		}
		mysqli_close($link_kweb);
		return "$res_vote";
}
function sendBallot($_kaddr_)
{
	$sendbal=exec("../system.op/sendBallot.sh $_kaddr_ 'GBTV'");
	echo "sendbal :".$sendbal;
	if($sendbal != 0)
	{
		consoleMsg(">> Ballot is given to you");	
		consoleMsg(", ballotId $sendbal");
	}
	else
	{
		consoleMsg(">> The voter address is not valid");
	}
	return "$sendbal";
}
function _sendBallot($_bool, $_kaddr)
{
	
	if($_bool === true)
	{
		echo "</br>\n"."send!!!";
		sendBallot($_kaddr);
	}
	else
	{
		consoleMsg(">> You've already vote.");
	}
}
 ?>
