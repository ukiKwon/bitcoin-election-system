<?php
include("webdb_config.php");

function sizeOfpost() {
   $count=0;
   $chk="true";
   $i=0;
   while($chk==="true") {
	if(!empty($_POST[$i]))
	   $count++;
	$chk=isset($_POST[$i++]);	
   }
   return "$count";
}
$res=sizeOfpost();
echo $_POST[1];
echo "The number of candidate is".$res."</br>";

?>
<html>
<head><h1>This is a page for a manager</h1></head>
<body>
	<ul>
	  synchronize kbkdb-kwebdb <input type='button' action='#'/></br>
	  confirm candidate <input type='button' action='#'/>
	</ul>
</body>
</html>
