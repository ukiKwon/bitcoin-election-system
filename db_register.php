<?php
include("db_config.php");

session_start();

$u_name = isset($_POST['u_name']) ? $_POST['u_name'] : '';
$u_reg = isset($_POST['u_reg']) ? $_POST['u_reg'] : '';
$u_sex = isset($_POST['u_sex']) ? $_POST['u_sex'] : '';
$u_age = isset($_POST['u_age']) ? $_POST['u_age'] : '';
$u_region = isset($_POST['u_region']) ? $_POST['u_region'] : '';
$u_date = date("Y-m-d h:i:s");

if ($u_name !="" and $u_reg !="" and $u_sex !="" and $u_age !="" and $u_region !=""){

    $sql = "insert into kdb(name, regisid, sex, age,region, vote_date, vote_chk) values('$u_name','$u_reg','$u_sex','$u_age','$u_region','$u_date',DEFAULT(vote_chk))";
    $result = mysqli_query($link,$sql);

    if($result){
       echo "SQL query process success";
    }
    else{
       echo "SQL query processing error occured: ";
       echo mysqli_error($link);
    }

} else {
    echo "type data";
}


mysqli_close($link);
?>

<?php

$kbk = strpos($_SERVER['HTTP_USER_AGENT'], "Kbk");

if (!$kbk){
?>

<html>
   <body>
      <form action="<?php $_PHP_SELF ?>" method="POST">
        name: <input type = "text" name = "u_name" />
        registerID: <input type = "text" name = "u_reg" />
        sex: <input type = "text" name = "u_sex"/>
        age: <input type = "text" name = "u_age" />
        region: <input type = "text" name = "u_region" />
        <input type = "submit" />
      </form>

   </body>
</html>
<?php
}
?>
~                                                        
