<?php
$U_addr=$_POST['u_addr'];
#echo "U_addr = $U_addr";
system("../system.op/voter.sh $U_addr");
?>
