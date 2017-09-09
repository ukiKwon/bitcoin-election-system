<?php
if(isset($_POST['message']))
  echo $_POST['message'];
else {
  echo "NO MESSAGE IS DELIVERED"."</br></br>";
}

?>
<?php
include("./db/locWeb_config.php");

$list_can = array();
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
        }
        $chk = isset($_POST[$i++]);
    }
    #print_r($list_can);
    return "$count";
}
$szPost = sizeOfpost();
$today = date("Y-m-d H:i:s");
echo $today."</br></br>";
echo " Now The number of candidates registered is ".$szPost."</br>";

?>
<html>
<head>
  <script src="./lib/jquery-3.2.1.min.js"></script>
  <h1>This is a page for a manager</h1>
</head>
<body>
	<ul>
    <form action="<?php $_PHP_SELF ?>">
	  synchronize kbkdb-kwebdb <input type="submit" class="button" name="syncdb" value="syncdb"/></br>
	  confirm candidate <input type="submit" class="button" name="concan" value="concan"/></br>
    generate candidate address <input type="submit" class="button" name="gen" value="gen"/></br>
  </form>
  </ul>
  <script>
  $(document).ready(function(){
      $('.button').click(function(){
          var clickBtnValue = $(this).val();
          var candiValue = <?php echo json_encode($list_can) ?>;
          var ajaxurl = 'manModule.php';
          data = [
                  {'action': clickBtnValue},
                  {'candidate': candiValue}
                  ];

          $.post(ajaxurl, data, function (response) {
              // Response div goes here.
              alert("action performed successfully");
          });
      });

  });
  </script>
<script language="javascript">
    function open_win_editar() {
        window.open ("the php file exectued here!!!", "Editar notícia", "location=1, status=1, scrollbars=1, width=800, height=455");
     }
</script>
</body>
</html>
