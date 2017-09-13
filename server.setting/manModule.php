<?php
include_once("./mod_sendTophp.php");
function manModulemsg($val, $message)
{
    echo "<script>console.log('$message');</script>";
    if(!$val)
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
function genaddr()
{
    $message=">> The generating addr of candidates is operated.";
    manModulemsg($val, $message);
    echo "<script>console.log('candidateList :$value');</script>";
    //$ret=exec("../system.op/getaddressbycandi.ver1.4.sh $value");
    exit;
}
echo "</br><h1>Manager Workspace</h1>";
if(isset($_POST['action']))
{
  $action=isset($_POST['action'])? $_POST['action'] : "NO ACTION";
  $value=isset($_POST['candidate'])? $_POST['candidate'] : "NO RETURN";
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
      genaddr();
      break;
    default:
      break;
  }
}

?>
