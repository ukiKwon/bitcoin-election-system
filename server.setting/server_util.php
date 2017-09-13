<?php
date_default_timezone_set('Asia/Seoul');
function webHeader($arr_can, $str_cans)
{
    global $arr_can, $str_cans;
    //date
    $today = date("Y-m-d H:i:s");
    echo $today."</br></br>";
    //candidate
    $sz_can = sizeOfpost($arr_can);
    setListcanStr($arr_can, $str_cans);
    echo " Now The number of candidates registered is ".$sz_can."</br>";
}
function loginHanlderMsg($_ecode)
{
  /* NOTICE : echo value
    4 bit echo means "boolean" about keyword
    x         x         x         x
    |         |         |         |
    voter   manager     candidate fail count

  ex) 1010 : voter and candidate
      4001 : right name but wrong regisid
      4000 : not korean
      1100 : voter and manager
      1401 : voter but not manager

  - but checking this user is candidate will not chekcing.because it has no mean.
  */
  switch($_ecode)
  {
    case 4001:
      echo "<script>console.log('# Failed due to wrong register id');</script>";
      break;
    case 1100:
      echo "<script>console.log('# This is a Manager');</script>";
      break;
    case 1001:
      echo "<script>console.log('#Candidates doesn't exist in kdb');</script>";
      break;
    case 1401:
      echo "<script>console.log('# Not a manager, but voter');</script>";
      break;
    case 4000:
      echo "<script>console.log('# Failed due to unregistered named');</script>";
      break;
    defalut :
      echo "<script>console.log('# Somthing wrong : Login parameter');</script>";
      break;
  }
}
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
