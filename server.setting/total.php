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
	$temp = $name[$i];
	$Candidata["name"]=$name[$i];
	$var=exec("./gettotal.sh $temp");
	$Candidata["sum"]=(int)($var);
	$Final['data'][]=array($Candidata["name"],$Candidata["sum"]);
}

$output=json_encode($Final);

#var_dump($output);
echo $output;
?>
