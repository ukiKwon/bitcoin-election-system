<?php
header('Content-Type: application/json');

/*
$name=explode(" ", $_POST['candidate']);
var_dump($name);
*/
$name=array("문재인", "박근혜");
$Final=array();
for($i=0; $i<count($name); $i++)
{
		$candidate= array();
		$temp = iconv("EUC-KR", "UTF-8", $name[$i]);
		$candidate["name"]=$temp;
		$var=exec("/var/www/html/KBK_election/system.op/gettotal.sh $temp");
		$candidate["sum"]=(int)($var);
		$Final['data'][]=array($candidate["name"],$candidate["sum"]);
}
if (json_last_error() > 0)
{		// handle error
			 echo "</br>\n"."decoding failed\n";
				 echo json_last_error_msg() . PHP_EOL;
}
#var_dump($output);
$output=json_encode($Final, JSON_UNESCAPED_UNICODE);
echo $output;
?>
