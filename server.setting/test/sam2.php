<?php
$list_can = array("jahu", "love", "me");
$listCan_str = implode(' ', $list_can);

if(isset($_POST['post_msg']))
{
  $resp=$_POST['post_msg'];
  echo "result :".$resp."</br>";
} else {
  echo "no responcse from res.php"."</br>";
}
 ?>
<html>
<head>
   <script src="./lib/jquery-3.2.1.min.js"></script>
   <script src="./main.js" type="text/javascript"></script>
   <h1>sam2</h1>
</head>

  <body>
    <form method="POST" id="post_form">
        <input type="text" id="post_msg" name="post_msg"/>
        <input type="submit" id="post_submit" value="입력"/>
    </form>



  </body>
</html>
