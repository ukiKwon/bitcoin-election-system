<?php
include("caDB_config.php");

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
