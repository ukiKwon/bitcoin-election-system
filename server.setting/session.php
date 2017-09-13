<?php
if(!strcmp($_SERVER['SERVER_NAME'], "localhost"))
{   //If this is localhost
    include("./db/locAs_config.php");
}
else
{   //Or another
    include("asdb_config.php");
}
   session_start();

   $user_check = $_SESSION['login_user'];
   $ses_sql = mysqli_query($db,"select name from kdb where username = '$user_check' ");

   $row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

   $login_session = $row['name'];

   if(!isset($_SESSION['login_user'])){
      header("location:./index.php");
   }
?>
