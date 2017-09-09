<?php
$value=isset($_POST['candidate'])? $_POST['candidate'] : "NO RETURN";
echo "candidate :".$value."</br>";

function syncdb() {
    $message="The select function is called.";
    #echo "The select function is called.";
    echo "<html>\n";
    echo "<body onload='document.form2.submit();'>\n";
    echo "<form name='form2' method='post' action='./manager.php'>\n";
    echo "<input type='hidden' name='$message' value='$message'>\n";
    echo "</form>\n";
    echo "</body>\n";
    echo "</html>\n";
    exit;
}

function concan() {
    $message="The insert function is called.";
    echo "The insert function is called.";
    echo "<body onload='document.form2.submit();'>\n";
    echo "<form name='form2' method='post' action='./manager.php'>\n";
    echo "<input type='hidden' name='$message' value='$message'>\n";
    echo "</form>\n";
    exit;
}
function genaddr() {
    global $value;
    $message="The gen function is called.";
    echo $message."</br>";
    #echo "The gen function is called.";
    echo "candidateList : ".$value."</br>";
    system("../system.op/genaddr.sh $value");
    exit;
}

 genaddr();
  //echo "value: ".$value;

/*
  echo "<html>\n";
  echo "<head>\n";
  echo "</head>\n";
  echo "<body onload='document.form1.submit();'>\n";
  echo "<form name='form1' method='post' action='manager.php'>\n";
  echo "<input type='hidden' name='result' value='$value'>\n";
  echo "</form>\n";
  echo "</body>\n";
  echo "</html>";
  */
?>
