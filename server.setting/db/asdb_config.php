<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

$db_host='localhost';
$db_user='root';
$db_pwd='kbkweb';
$db_name='kbkdb';

$link_kas = mysqli_connect($db_host, $db_user, $db_pwd, $db_name);
if (!$link_kas)
{
   echo "MySQL connection error: ";
   echo mysqli_connect_error();
   exit();
}


mysqli_set_charset($link_kas,"utf8");
?>
