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
		$candidate=array();
		$sum =0;
		$temp = iconv("EUC-KR", "UTF-8", $name[$i]);

		$candidate["name"]=$temp;//$name[$i];
		##########start getaddr
		$cXaddr=exec("/var/www/html/KBK_election/system.op/getadd.sh $temp 1");
		//echo $cXaddr."\n";
		if(strcmp($cXaddr, 0))
		{
				$var=exec("/var/www/html/KBK_election/system.op/getbalance.sh $cXaddr");
				//$var=iconv("EUC-KR", "UTF-8", $var);
				//echo $var."\n";
				//echo mb_detect_encoding($var, "EUC-KR, UTF-8, ASCII");

				$candidate["sum"]=(int)($var);
				$Final['data'][]=array($candidate["name"],$candidate["sum"]);
		}
		else {
				echo "\n"."There's no address like that";
		}
		//echo $output;
}
//var_dump($Final);
if (json_last_error() > 0)
{		// handle error
			 echo "</br>\n"."decoding failed\n";
				 echo json_last_error_msg() . PHP_EOL;
}
$output=json_encode($Final, JSON_UNESCAPED_UNICODE);
echo $output;
?>
