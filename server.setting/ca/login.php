<?php
include("db_config.php");

session_start();

if($_POST['u_name']) {

        $u_name=isset($_POST['u_name']) ? $_POST['u_name'] : '';
        $sql = "SELECT regisid FROM kdb WHERE name = '$u_name'";

        $result = mysqli_query($link,$sql);

        if($result) {
                $u_reg=isset($_POST['u_reg']) ? $_POST['u_reg'] : '';

                $row = mysqli_fetch_array($result);
                if(is_null($row['regisid'])) {
                        echo "Can not find NAME";
                }
                else {
                        if(strcmp($row['regisid'], $u_reg)) {
                                //Not registered
                                echo 0;
                        }
                        else {
                                // registered
                                echo 1;
                                // hashID  or base64
                                $sql2 = "SELECT name, regisid, sex, age, region, login_date from kdb WHERE regisid ='$u_reg'";
                                $result2 = mysqli_query($link, $sql2);
                                $row = mysqli_fetch_array($result2);
                                $str = $row['name'].$row['regisid'].$row['sex'].$row['age'].$row['region'].$row['login_date'];

                                #echo "</br> str : $str";       
                                $hid = hash('sha256', $str);
                                echo "$hid";
                                /* base64_encode_decode_test */
                                #$ebase64id = base64_encode($str);
                                #$dbaseid = base64_decode($ebase64id);
                                #echo "</br> $dbase64id";
                                #echo "</br> base64_en :$ebase64id";
                        }
                }
        }
        else {
                echo mysqli_errno($link);
        }
}
else {
        echo "Type your ID";
}
?>
<html>
   <body>
      <form action="<?php $_PHP_SELF ?>" method="POST">
         name: <input type = "text" name = "u_name" />
         regisID: <input type = "text" name = "u_reg" />
         <input type = "submit" />
      </form>

   </body>
</html>
