<?php
/* sendVCode : To send a string to the voter in response to the request */
function sendVCode($_link, $_regisid)
{
  //vcode_str : return value
  $vcode_str="";
  //step1.Load the specified data from tabled 'kdb' matching with this voter.
  $sql_vt = "SELECT region, sex, age FROM kdb WHERE regisid = '$_regisid'";
  $res_vt = mysqli_query($_link, $sql_vt);
  if($res_vt)
  {   //step2.voter data is found
      $row_vt = mysqli_fetch_array($res_vt);
      //Search the region code from regcode matching with $row['region']
      $target_reg=$row_vt['region'];
      //step3.Get the region code from table regcode
      $sql_vc = "SELECT rcode FROM regcode WHERE region = '$target_reg'";
      $res_vc = mysqli_query($_link, $sql_vc);
      if(res_vc)
      {   //step4.region code is found
          $row_vc = mysqli_fetch_array($res_vc);
          //step5.concatenation
          $vcode_str.=$row_vc['rcode'];
          $vcode_str.=$row_vt['sex'];
          $vcode_str.=$row_vt['age'];
      }
      mysqli_free_result($res_vt);
      mysqli_free_result($res_vc);
  }
  echo "$vcode_str\n";
}

 ?>
