<?php
include ("./server_util.php");
include ("./db/locWeb_config.php");

define ("LEN_REG", 4);
define ("LEN_SEX", 1);
define ("LEN_AGE", 1);
/* another way
define ("VC", [
	"LEN_REG"=>4,
	"LEN_SEX"=>1,
	"LEN_AGE"=>1,
])
*/
$arr_can=array();
$str_cans="";

//print_r($_POST);
# DATE && CANDIDATE LIST
//date
$today = date("Y-m-d H:i:s");
echo $today."</br></br>";
//candidate
$sz_can = sizeOfpost($arr_can);
setListcanStr($arr_can, $str_cans);
echo " Now The number of candidates registered is ".$sz_can."</br>";
header('Content-Type:application/json');
if(isset($_POST['k_json']))
{
    # RECEIVE JSON OBJ : FROM VOTERS
    #	RECEIVE JSON VALUE
    # voter code : region(4 byte) + sex(1 bite) + age(1 byte) .=.31 bite
    # kbk address : 34 bite
    #
    $res=json_decode(stripslashes($_POST['k_json']), true));
    if($res)
    {
        echo $res['vcode']."</br>\n";
        echo $res['kaddr']."</br>\n";
        // split vcode
        $_rcode=substr($_vcode, 0, (int)LEN_REG);

        echo "_vcode:".$_vcode."</br>";
        echo "_kaddr:".$_kaddr."</br>";
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
      echo "</br>"."decoding failed";
    }
}
else
{
  echo "</br>"."No json data";
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
	<input name="개표현황" type="submit" action='<?php $_PHPSELF ?>' value="count of votes"/>
</body>

</html>
