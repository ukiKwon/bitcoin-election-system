<?php
function sendTophp($list_can, $fPhp_path) {
  $can_key=array_keys($list_can);

  echo "<html>\n";
  echo "<head>\n";
  echo "</head>\n";
  echo "<body onload='document.form1.submit();'>\n";
  echo "<form name='form1' method='post' action='".$fPhp_path."'>\n";
  for($i=0; $i<count($list_can); $i++)
  {
    	$value = addslashes($list_can[$can_key[$i]]);
    	echo "<input type='hidden' name='{$can_key[$i]}' value='$value'>\n";
  }
  echo "</form>\n";
  echo "</body>\n";
  echo "</html>";
}
?>
