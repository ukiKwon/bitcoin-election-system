<?php

$can_arr = array();
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'syncdb':
            syncdb();
            break;
        case 'concan':
            concan();
            break;
        case 'gen':
            gen();
            break;
    }
}
if (isset($_POST['candidate'])) {
   array_push($can_arr, $_POST['candidate']);
}

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
function gen() {
    $message="The gen function is called.";
    #echo "The gen function is called.";
    system("/var/www/html/KBK_election/system.op/getaddressbycandi.sh '$can_arr'");
    exit;
}
?>
