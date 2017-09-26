<?php
header('Content-Type: application/json');

$name=array("문재인", "박근혜");
/*
$name=explode(" ", $_POST['candidate']);
var_dump($name);
*/
$Final=array();

for($i=0; $i<count($name); $i++)
{
		$candidate=array();
		$sum =0;
		$temp = $name[$i];
		$candidate["name"]=$name[$i];
		##########start getaddr
		$cXaddr=exec("/var/www/html/KBK_election/system.op/getadd.sh $temp 1");
		echo $cXaddr;
		if(strcmp($cXaddr, 0))
		{
				$var=exec("/var/www/html/KBK_election/system.op/getbalance.sh $cXaddr");
				$candidate["sum"]=(int)($var);
				$Final['data'][]=array($candidate["name"],$candidate["sum"]);
		}
		else {
				echo "There's no address like that";
		}
		//echo $output;
}
	//$output=json_encode($Final);
	//var_dump($output);

$output=json_encode($Final, JSON_UNESCAPED_UNICODE);
echo $output;
?>
