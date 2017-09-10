<?php
function sendVCode($_link, $_regisid)
{
  /* vcode_str : return value */
  $vcode_str="";
  /* load the specified data from kdb matching with the actual user */
  $sql_vt = "SELECT region, sex, age FROM kdb WHERE regisid = '$_regisid'";
  $res_vt = mysqli_query($_link, $sql_vt);
  if($res_vt)
  {
      $row_vt = mysqli_fetch_array($res_vt);

      /* search the region code from regcode matching with $row['region'] */
      $target_reg=$row_vt['region'];
      $sql_vc = "SELECT rcode FROM regcode WHERE region = '$target_reg'";
      $res_vc = mysqli_query($_link, $sql_vc);

      $row_vc = mysqli_fetch_array($res_vc);
      $vcode_str.=$row_vc['rcode'];
      $vcode_str.=$row_vt['sex'];
      $vcode_str.=$row_vt['age'];

      mysqli_free_result($res_vt);
      mysqli_free_result($res_vc);
  }
  echo "$vcode_str\n";
  //echo "<script>console.log($vcode_str);</script>";
}

 ?>
