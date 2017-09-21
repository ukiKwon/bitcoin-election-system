<?php
header('Content-Type: application/json');
#Read Post parameter
#$index=$_POST['index'];
#$C_name=$_POST['C_name'];
#$C_num=$_POST['C_num'];
#$name=$_POST['$i'];
$name=array("js","kk");
$max=count($name);

$Final = array();
for($i=0; $i<$max; $i++){ 
	$Candidata= array();
	$sum =0;
	$temp = $name[$i];
	$Candidata["name"]=$name[$i];
	##########start getaddr
	$var=exec("./getadd.sh $temp");
	$trimmed=str_replace("\"","",$var);
	$trimmed=str_replace(" ","",$trimmed);
	$trimmed=trim($trimmed,"[]");
	$res=explode(",",$trimmed);#finish getaddr*;
	$var=exec("./getbalance.sh $res[2]");
	$Candidata["sum"]=(int)($var);
	$Final['data'][]=array($Candidata["name"],$Candidata["sum"]);
}
$output=json_encode($Final);

#var_dump($output);
echo $output;
?>
