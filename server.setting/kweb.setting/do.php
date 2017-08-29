<?php

function sizeOfpost() {

$count=0;
$i=0;
$chk="true";
while($chk==="true"){
	var_dump($_POST['0']);
	if(!empty($_POST[$i]))
		$count++;
	$chk=isset($_POST[$i++]);
}
return $count;
}
$result=sizeOfpost();
echo $result;
?>
