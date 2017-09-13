<?php
# SET DB
if(!strcmp($_SERVER['SERVER_NAME'], "localhost"))
{   //If this is localhost
    include("./db/locAs_config.php");
}
else
{   //Or another
    include("asdb_config.php");
}
include("./sendVcode.php");
include("./mod_sendTophp.php");

/* NOTICE : echo value
  4 bit echo means "boolean" about keyword
  x         x         x         x
  |         |         |         |
  voter   manager     candidate fail count

ex) 1010 : voter and candidate
    4001 : right name but wrong regisid
    4000 : not korean
    1100 : voter and manager
    1401 : voter but not manager

- but checking this user is candidate will not chekcing.because it has no mean.
*/
echo "<h1>KWEB</h1>";
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
                  echo "4001\n";
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
                            echo "</br>".$row_can['name']."</br>";
                            array_push($list_can, $row_can['name']);
                        }
                        mysqli_free_result($res_can);
                  }
                  else  #Candidates doesn't exist in kdb
                  {echo "NO CANDI";}
                  # step2.Check Manager
                  $sql_man = "SELECT manager FROM kdb where regisid='$u_reg'"; # TO DO : may be this will be problemed.
                  $res_man = mysqli_query($link_kas, $sql_man);
                  if($res_man)
                  {     # Valid manager name
                        $row_man = mysqli_fetch_array($res_man);
                        if(strcmp($row_man['manager'], 0))
                        {       # This is a Manager
                    						sendTophp($list_can,'./manager.php');
                    						echo "1100\n";
                    						//echo("<script>location.replace('./manager.php');</script>");
                        }
                        else
                        {     # Not a manager, but voter
                                echo "1401\n";
                                sendVCode($link_kas, $u_reg);
                                sendTophp($list_can,'./voter.php');
                                //echo("<script>location.replace('./voter.php');</script>");
                        }
                  }
                  else
                  {     #Managers doesn't exist in kdb.
                        echo "1401\n";
                  }
            }
	    }
      else # Failed due to unregistered name
      {   echo "4000\n";
          echo "<script>console.log('Can not find NAME');</script>";
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

$vApp=strpos($_SERVER['HTTP_USER_AGENT'], "bit");

if(!$vApp) {
?>
    <html>
       <body>
          <form action="<?php $_PHP_SELF ?>" method="POST">
            id: <input type = "text" name = "u_name" />
            reg: <input type = "text" name = "u_reg" />
            <input type = "submit" />
          </form>
       </body>
    </html>
<?php
}
?>
