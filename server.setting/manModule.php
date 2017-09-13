<?php
function syncdb() {
    $message=">> The syncdb function is called.";
    echo $message;
    exit;
}

function concan() {
    $message=">> The concan function is called.";
    echo $message;
    exit;
}
function genaddr() {
    global $value;
    $message=">> The genaddr function is called.";
    echo $message."</br>";
    #echo "The gen function is called.";
    echo "candidateList : ".$value."</br>";
    //exec("../system.op/getaddressbycandi.ver1.4.sh $value");
    //system("../system.op/getaddressbycandi.ver1.4.sh $value");
    //system("../system.op/genaddr.sh $value");
    exit;
}

$value=isset($_POST['candidate'])? $_POST['candidate'] : "NO RETURN";
$action=isset($_POST['action'])? $_POST['action'] : "NO ACTION";

echo "</br><h1>Parameter checking below</h1>";
echo "candidate are :".$value."</br>";
echo "action is : ".$action."</br></br>";

if(isset($_POST['action']))
{
   echo "<script>console.log('>> The button of man is activated');</script>";
  switch($_POST['action'])
  {
    case "syncdb" :
      syncdb();
      break;
    case "concan" :
      concan();
      break;
    case "genaddr":
      genaddr();
      break;
    default:
      break;
  }
}
?>
