<?php
include_once("./mod_sendTophp.php");

$action=isset($_POST['action'])? $_POST['action'] : "NO ACTION";
$value=isset($_POST['candidate'])? $_POST['candidate'] : '';

function manModulemsg($value, $message)
{
    echo "<script>console.log('$message');</script>";
    if(count($value))
    {
        echo "<script>console.log('>> Operating success');</script>";
    } else {
        echo "<script>console.log('>> Operating fail');</script>";
    }
}
function permitb()
{
    $message=">> The permitb function is called.";
    $val=exec("../system.op/permitblock.sh");
    manModulemsg($val, $message);
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
    manModulemsg($value, $message);
    echo "<script>console.log('candidateList :$value');</script>";
    $ret=system("../system.op/getaddressbycandi.ver1.4.sh $value");
    echo $ret;
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
