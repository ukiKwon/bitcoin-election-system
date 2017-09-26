<?php
header('Content-Type: application/json');

/*
$name=explode(" ", $_POST['candidate']);
var_dump($name);
*/
$name=array("문재인", "박근혜");
$Final=array();
for($i=0; $i<$max; $i++)
{
			$Candidata= array();
			$temp = $name[$i];
			$candidate["name"]=$name[$i];
			$var=exec("/var/www/html/KBK_election/system.op/gettotal.sh $temp");
			$candidate["sum"]=(int)($var);
			$Final['data'][]=array($candidate["name"],$Candidata["sum"]);
}
$output=json_encode($Final, JSON_UNESCAPED_UNICODE);

#var_dump($output);
echo $output;
?>
