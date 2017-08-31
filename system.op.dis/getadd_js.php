<?php
#Read Post parameter
$index=$_POST['index'];
$C_name=$_POST['C_name'];
$C_num=$_POST['C_num'];

$R_name=explode(",",$C_name);
for($i=0; $i< $C_num; $i++){
$name=$R_name[$i];
$var=system("./getadd.sh $name");
$trimmed=str_replace("\"","",$var);
$trimmed=str_replace(" ","",$trimmed);
$trimmed=trim($trimmed,"[]");
$res=explode(",",$trimmed);
echo $res[$index];
echo "\n";
}

#start sh for get candidate's bitcoin address
##$var=system("./getadd.sh $C_name");

#trim  result
##$trimmed=str_replace("\"","",$var);
##$trimmed=str_replace(" ","",$trimmed);
##$trimmed=trim($trimmed);
##$trimmed=trim($trimmed,"[]");

#devide
##$res=explode(",",$trimmed);

#get address by local number
##echo $res[$index];
?>
