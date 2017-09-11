

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
# RECEIVE JSON OBJ : FROM VOTERS
/* 	RECEIVE JSON VALUE
	* voter code : region(4 byte) + sex(1 bite) + age(1 byte) .=.31 bite
	* kbk address : 34 bite
*/
print_r($_POST);

$recvJson=json_decode(file_get_contents("php://input"));
/* split vcode */
var_dump($recvJson);
$_vcode=$recvJson['vcode'];
$_kaddr=$recvJson['kaddr'];
$_rcode=substr($_vcode, 0, LEN_REG);

echo "_vcode:".$_vcode."</br>";
echo "_kaddr:".$_kaddr."</br>";
echo "_rcode:".$_rcode."</br>";

# RECV POST DATA : CANDIDATE INFO
$arr_can=array();
$sz_can = sizeOfpost($arr_can);

print_r($arr_can);
# INDEXING
for($i=0; $i< $sz_can; $i++)
{
  $name=$arr_can[$i];
  $var=system("../system.op/getadd.sh $name"); /* getaddress by account */
  $trimmed=str_replace("\"","",$var);
  $trimmed=str_replace(" ","",$trimmed);
  $trimmed=trim($trimmed,"[]");
  $arr_caddr=explode(",",$trimmed);
  echo $arr_caddr[$_rcode]."</br>";
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
