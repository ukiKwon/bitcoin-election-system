<?php
$list_can = array();
$listCan_str="";

if(isset($_POST['candidate']))
{
  //echo $_POST['candidate'];
  $list_can=explode(' ',$_POST['candidate']);
} else {
  echo "NO MESSAGE IS DELIVERED"."</br></br>";
}

?>
<?php
include("./db/locWeb_config.php");

global $list_can;
global $listCan_str;
function setListcanStr()
{
    global $list_can, $listCan_str;
    if(!count($list_can))
      echo "The candidate list is empty now"."</br>";
    for($i=0; $i<count($list_can); ++$i) {
      $listCan_str.=($list_can[$i]." ");
    }
    echo "Candidates are { ".$listCan_str." }</br>";
}
function sizeOfpost()
{
    global $list_can;
    $count=0;
    $chk='true';
    $i=0;
    while($chk == 'true')
    {
        if(!empty($_POST[$i]))
        {
          $count++;
          array_push($list_can, $_POST[$i]);

        } else if($count == 0)
        {
          $count=count($list_can)-1;
        } else{;}
        $chk = isset($_POST[$i++]);
    }
    return "$count";
}
# View Date info
$today = date("Y-m-d H:i:s");
echo $today."</br></br>";
# View Candidate info
$szPost = sizeOfpost();
# echo candidate list
setListcanStr();
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
	  synchronize kbkdb-kwebdb <input type="submit" id="syncdb" name="syncdb" value="syncdb"/></br>
	  confirm candidate <input type="submit" id="concan" name="concan" value="concan"/></br>
    generate candidate address <input type="submit" id="concan" name="concan" value="genaddr"/></br>
    <input type="hidden" id="candidate" name="candidate" value="<?php echo $listCan_str; ?>"/></br>
  </form>
  </ul>

</body>
</html>
