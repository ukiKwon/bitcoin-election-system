<?php
//session_start();
$arr_can = array();
$str_cans="";

if(isset($_POST['candidate']))
{
  //echo $_POST['candidate'];
  $arr_can=explode(' ',$_POST['candidate']);
} else {
  echo "NO MESSAGE IS DELIVERED"."</br></br>";
}

?>
<?php
include("./db/locWeb_config.php");
include ("./server_util.php");

global $arr_can;
global $str_cans;

# View Date && candiates
webHeader($arr_can, $str_cans);
?>
<?php

$vApp=strpos($_SERVER['HTTP_USER_AGENT'], "Java");

if(!$vApp) {
?>
  <html>
    <head>
      <meta charset="utf-8">
      <script src="./lib/jquery-3.2.1.min.js"></script>
      <script src="./manmodule.js" type="text/javascript"></script>
      <h1>This is a page for a manager</h1>
    </head>
    <body>
    	<ul>
        <form method="post" action="manModule.php">
    	  Permit new block <input type="submit" id="permitb" name="action" value="permitb"/></br>
    	  show Transaction List <input type="submit" id="showtx" name="action" value="showtx"/></br>
        generate candidate address <input type="submit" id="genaddr" name="action" value="genaddr"/></br>
        <input type="hidden" id="candidate" name="candidate" value="<?php echo $str_cans; ?>"/></br>
      </form>
      </ul>

    </body>
  </html>
<?php
}
?>
