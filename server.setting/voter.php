
#include ("locWeb_config.php");

# SECTOR date_default_timezone_get
#define("REGION", 4, "true");
#define("SEX", 1, "true");
#define("AGE", 1,)


/* RECEIVE POST VALUE*/
# voter code : region(4 byte) + sex(1 bite) + age(1 byte) .=.31 bite
# kbk address : 34 bite
#$kaddr=isset($_POST['vcode'])? $_POST['kaddr'] : '';
#$vcode=isset($_POST['vcode'])? $_POST['vcode'] : '';


#<?php

/* split vcode */

/* INSERT INTO KWEBDB */
#$sql_vote= "INSERT INTO kwebdb (kaddr, vote_date, region, sex, age)"
#$sql_man = "SELECT manager FROM kdb where name='$u_name'"; # TO DO : may be this will be problemed.
#$res_man = mysqli_query($link,$sql_man);
#if($res_man)
#?>

<html>
<head><h1>This is a page for a voter</h1></head>
<body>
	<h4>SEE A ELECTION</h4>
	<input name="개표현황" type="submit" action='<?php $_PHPSELF ?>' value="count of votes"/>
</body>

</html>
