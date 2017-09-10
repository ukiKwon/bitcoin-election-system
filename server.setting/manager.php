<?php
$array_can = array();
$strCandidates="";

if(isset($_POST['candidate']))
{
  //echo $_POST['candidate'];
  $array_can=explode(' ',$_POST['candidate']);
} else {
  echo "NO MESSAGE IS DELIVERED"."</br></br>";
}

?>
<?php
include("./db/locWeb_config.php");
include ("./server_util.php");

global $array_can;
global $strCandidates;

# View Date info
$today = date("Y-m-d H:i:s");
echo $today."</br></br>";
# View Candidate info
$szPost = sizeOfpost($array_can);
# echo candidate list
setListcanStr($array_can, $strCandidates);
echo " Now The number of candidates registered is ".$szPost."</br>";

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
	  synchronize kbkdb-kwebdb <input type="submit" id="syncdb" name="action" value="syncdb"/></br>
	  confirm candidate <input type="submit" id="concan" name="action" value="concan"/></br>
    generate candidate address <input type="submit" id="concan" name="action" value="genaddr"/></br>
    <input type="hidden" id="candidate" name="candidate" value="<?php echo $strCandidates; ?>"/></br>
  </form>
  </ul>

</body>
</html>
