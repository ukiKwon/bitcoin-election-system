<?php
function zeroFilter($array_can)
{
  global $array_can;
  foreach ($array_can as $key => $val) {
      if($val == '')
      {
          unset($array_can[$key]);
      }
  }
}
function setListcanStr($array_can, $strCandidates)
{
    global $array_can, $strCandidates;
    if(!count($array_can))
    {
      echo "The candidate list is empty now"."</br>";
    } else
    {
      for($i=0; $i<count($array_can); ++$i) {
        $strCandidates.=($array_can[$i]." ");
      }
      echo "Candidates are { ".$strCandidates." }</br>";
    }
}
function sizeOfpost($array_can)
{
    /* TO DO : count($array_can)? */
    global $array_can;
    $count=0;
    $chk='true';
    $i=0;
    while($chk == 'true')
    {
        if(!empty($_POST[$i]))
        {
          $count++;
          array_push($array_can, $_POST[$i]);

        } else if($count == 0)
        {
          zeroFilter($array_can);
          $count=count($array_can);
        } else{;}
        $chk = isset($_POST[$i++]);
    }
    return "$count\n";
}
 ?>
