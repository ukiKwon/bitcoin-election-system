<?php
echo "PHP_AUTH_DIGEST :".$_SERVER['PHP_AUTH_DIGEST'];echo "</br>";
echo "PHP_AUTH_USER :".$_SERVER['PHP_AUTH_USER'];echo "</br>";
echo "SERVER_ADMIN :".$_SERVER['SERVER_ADMIN'];echo "</br>";
echo "REMOTE_HOST :".$_SERVER['REMOTE_HOST'];echo "</br>";
echo "HTTP_USER_AGENT :".$_SERVER['HTTP_USER_AGENT'];echo "</br>";
echo "HTTP_HOST :".$_SERVER['HTTP_HOST'];echo "</br>";
echo "SERVER_NAME :".$_SERVER['SERVER_NAME'];echo "</br>";
echo "SERVER_ADDR :".$_SERVER['SERVER_ADDR'];echo "</br>";
echo "HTTP_HOST :".$_SERVER['HTTP_HOST'];echo "</br>";
echo "</br></br></br>";

$list_can = array();
array_push($list_can, "kwon");
print_r($list_can);
$listCan_str="";
for($i=0; $i<count($list_can); ++$i)
  $listCan_str.=($list_can[$i]." ");
echo "listCan_st :".$listCan_str;
echo "</br></br>";

#exec("/var/www/html/KBK_election/system.op/getaddressbycandi.ver1.3.sh $listCan_str");
#system("/var/www/html/KBK_election/system.op/getaddressbycandi.ver1.3.sh $listCan_str");
exec("../system.op/getaddressbycandi.ver1.4.sh $listCan_str");

?>
