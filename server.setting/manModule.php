<?php
include_once("./mod_sendTophp.php");
include_once("./server_util.php");

$action=isset($_POST['action'])? $_POST['action'] : "NO ACTION";
$value=isset($_POST['candidate'])? $_POST['candidate'] : '';

function permitb()
{
    $message=">> The permitb function is called.";
    $ret=exec("../system.op/permitblock.sh");
    manModulemsg($ret, $message);
    retBashMsg($_SERVER['SCRIPT_FILENAME'], $ret);
    exit;
}
function showtx()
{
    $message=">> The showtx function is called.";
    manModulemsg($val, $message);
    exit;
}
function genaddr($value)
{
    $message=">> The generating addr of candidates is operated.";
    echo "<script>console.log('candidateList :$value');</script>";
    $ret=exec("../system.op/getaddressbycandi.sh $value");
    manModulemsg($value, $message);
    retBashMsg($_SERVER['SCRIPT_FILENAME'], $ret);
    exit;
}
echo "</br><h1>Manager Workspace</h1>";
if(isset($_POST['action']))
{
  //echo "<script>console.log('>> action : $action');</script>";
  switch($action)
  {
    case "permitb" :
      permitb();
      break;
    case "showtx" :
      showtx();
      break;
    case "genaddr":
      genaddr($value);
      break;
    default:
      break;
  }
}
?>
