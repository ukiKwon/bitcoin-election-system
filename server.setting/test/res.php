<?php
include ("mod_sendTophp.php");

  $post_msg=$_POST['post_msg'];
  $post_can=$_POST['can'];
  if(!isset($_POST['can']))
    $post_msg.="NONONO!!!!";
  else {
    $post_msg.="YESYES!!!!";
  }

  echo "<html>\n";
  echo "<head>\n";
  echo "</head>\n";
  echo "<body onload='document.form1.submit();'>\n";
  echo "<form name='form1' method='post' action='sam2.php'>\n";

  echo "<input type='hidden' name='result' value='$post_msg'>\n";
  echo "</form>\n";
  echo "</body>\n";
  echo "</html>";
?>
