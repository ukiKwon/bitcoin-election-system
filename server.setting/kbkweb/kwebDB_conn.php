<?php
include("kwebDB_config.php");
session_start();

if($_POST['u_id']) {

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
                                echo 1;
                        }
                        else {
                                echo 0;
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
        id: <input type = "text" name = "u_name" />
        addr: <input type = "text" name = "u_reg" />
        vote: <input type = "text" name = "u_sex"/>
        <input type = "submit" />
      </form>

   </body>
</html>
