<meta charset="utf-8">
<?php
# SET DB
if(!strcmp($_SERVER['SERVER_NAME'], "localhost"))
{   //If this is localhost
    include("./db/locAs_config.php");
}
else
{   //Or another
    include("./db/asdb_config.php");
}
//else{;}
include_once ("./server_util.php");
include_once ("./mod_sendTophp.php");

//echo $_POST['u_name'];
if(isset($_POST['u_name']))
{
      $u_name=$_POST['u_name'];
      $sql = "SELECT regisid FROM kdb WHERE name = '$u_name'";
      $result = mysqli_query($link_kas, $sql);
      if($result)   # Valid name -> NOT PASSED YET
      {
            $u_reg=isset($_POST['u_reg']) ? $_POST['u_reg'] : '';
            $row = mysqli_fetch_array($result);
            if(strcmp($row['regisid'], $u_reg))
            {
                  $login=4001;
                  //loginHanlderMsg($login);

            }   # Failed due to wrong register id
            else
            {     # Registered
                  # step1.Get the list of candidates
                  $list_can = array();
                  $sql_can = "SELECT name FROM kdb where candidate='1'";
                  $res_can = mysqli_query($link_kas, $sql_can);
                  if($res_can)
                  {     #Candidate is found
                        while($row_can = mysqli_fetch_array($res_can))
                        {
                            array_push($list_can, $row_can['name']);
                        }
                        mysqli_free_result($res_can);
                  }
                  else  #Candidates doesn't exist in kdb
                  {
                  			$login=1001;
                  			#loginHanlderMsg($login);
            		  }
                  # step2.Check Manager
                  $sql_man = "SELECT manager FROM kdb where regisid='$u_reg'"; # TO DO : may be this will be problemed.
                  $res_man = mysqli_query($link_kas, $sql_man);
                  if($res_man)
                  {     # Valid manager name
                        $row_man = mysqli_fetch_array($res_man);
                        if(strcmp($row_man['manager'], 0))
                        {       # This is a Manager
                                $login=1100;
		                            #loginHanlderMsg($login);
                    						sendTophp($list_can,'./manager.php');
                    						//echo("<script>location.replace('./manager.php');</script>");
                        }
                        else
                        {     # Not a manager, but voter
                                $login=1401;
				                        #loginHanlderMsg($login);
                                $vcode=getvcode($link_kas, $u_reg);
				$cans_str=implode(",", $list_can);
				echo $cans_str;
                                sendTophp($list_can,'./voter.php');

                                //echo("<script>location.replace('./voter.php');</script>");
                        }
                  }
                  else
                  {     #Managers doesn't exist in kdb.
                        $login=1401;
	                      #loginHanlderMsg($login);
                  }
            }
	    }
      else # Failed due to unregistered name
      {   $login=4000;
		      #loginHanlderMsg($login);
          echo mysqli_errno($link_kas);
      }
}
else
{
    echo "Type your ID"."</br>";
}  # No post data of 'name'
mysqli_close($link_kas);
?>
<?php
$vApp=strpos($_SERVER['HTTP_USER_AGENT'], "Java");
if($vApp === false) {
?>
    <html>
	<head><h1>KWEB</h1></head>
       <body>
          <form action="<?php $_PHP_SELF ?>" method="POST">
            id: <input type = "text" name = "u_name" />
            reg: <input type = "text" name = "u_reg" />
            <input type = "submit" />
          </form>
       </body>
    </html>
<?php
} else
{
global $login,$vcode, $cans_str;
	echo "\n";
	echo $login;
	echo "\n";
	echo $vcode;
	echo "\n";
	echo $cans_str;
}
?>
