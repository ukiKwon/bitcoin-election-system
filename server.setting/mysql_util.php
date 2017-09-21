<?php
include_once ("./server_util.php");
function mysqliMsg($e)
{
  switch($e)
  {
    case 1 :
      consoleMsg(">> This voter can vote.");
      break;
    case 1062 :
      consoleMsg(">> This voter is Already voted");
      break;
    default :
      break;
  }
}
?>
