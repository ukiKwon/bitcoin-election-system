<?php

function zeroFilter($arr_can)
{
  global $arr_can;
  foreach ($arr_can as $key => $val) {
      if($val == '')
      {
          unset($arr_can[$key]);
      }
  }
}
function setListcanStr($arr_can, $str_cans)
{
    global $arr_can, $str_cans;
    if(!count($arr_can))
    {
      echo "The candidate list is empty now"."</br>";
    } else
    {
      for($i=0; $i<count($arr_can); ++$i) {
        $str_cans.=($arr_can[$i]." ");
      }
      echo "Candidates are { ".$str_cans." }</br>";
    }
}
function sizeOfpost($arr_can)
{
    global $arr_can;
    $count=0;
    $chk='true';
    $i=0;
    while($chk == 'true')
    {
        if(!empty($_POST[$i]))
        {
          $count++;
          array_push($arr_can, $_POST[$i]);

        } else if($count == 0)
        {
          zeroFilter($arr_can);
          $count=count($arr_can);
        } else{;}
        $chk = isset($_POST[$i++]);
    }
    return "$count";
}
?>
