<?php
$U_addr=$_POST['u_addr'];
#echo "U_addr = $U_addr";
system("./voter.sh $U_addr");
?>
