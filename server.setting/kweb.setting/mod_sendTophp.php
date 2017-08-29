<?php
function sendTophp($list_can) {
  $can_key=array_keys($list_can);

  echo "<html>\n";
  echo "<head>\n";echo"mod_sendTophp.php\n";
  echo "</head>\n";
  echo "<body onload='document.form1.submit();'>\n";
  echo "<form name='form1' method='post' action='./manager.php'>\n";
  for($i=0; $i<count($list_can); $i++) 
  {
	$value = addslashes($list_can[$can_key[$i]]);
	echo "<input type='hidden' name='{$can_key[$i]}' value='$value'>\n";
	var_dump($value);
  }
  echo "</form>\n";
  echo "</body>\n";
  echo "</html>";
}
?>
