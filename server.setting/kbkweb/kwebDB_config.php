<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

$db_host='localhost';
$db_user='root';
$db_pwd='k46s13u13';
$db_name='kbkweb';

$link = mysqli_connect($db_host, $db_user, $db_pwd, $db_name);
if (!$link)
{
   echo "MySQL connection error: ";
   echo mysqli_connect_error();
   exit();
}


mysqli_set_charset($link,"utf8");
?>
