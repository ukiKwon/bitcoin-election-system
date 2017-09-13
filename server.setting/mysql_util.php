<?php
function mysqliMsg($e)
{
  switch($e)
  {
    case 1 :
      echo "<script> console.log('>> This voter can vote.'); </script>\n";
      break;
    case 1062 :
      echo "<script> console.log('>> This voter is Already voted'); </script>\n";
      break;
    default :
      break;
  }
}
?>
