<?php
include("./db/locWeb_config.php");

function sizeOfpost()
{
    $count=0;
    $chk="true";
    $i=0;
    while($chk==="true")
    {
        if(!empty($_POST[$i]))
        $count++;
        $chk=isset($_POST[$i++]);
    }
    return "$count";
}
$szPost = sizeOfpost();
$today = date("Y-m-d H:i:s");
echo $today."</br></br>";
echo " Now The number of candidates registered is ".($szPost + 1)."</br>";
;
?>
<?php
  system("")
?>
<html>
<head><h1>This is a page for a manager</h1></head>
<body>
	<ul>
	  synchronize kbkdb-kwebdb <input name="sync" type="button" action="#"/></br>
	  confirm candidate <input name="confirm" type="button" action="#"/></br>
    generate candidate address <input name="gen" type="button" action="#"/></br>
  </ul>
</body>
</html>
