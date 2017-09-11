<?php
#If this is a localhost,then activate below line
include("./db/locAs_config.php");
include("./sendVcode.php");
##else
#include("asdb_config.php");
##done
include("./mod_sendTophp.php");
include ("./curl-post.php");
//session_start();

echo "<h1>KWEB</h1>";
if(isset($_POST['u_name']))
{
      $u_name=$_POST['u_name'];//isset($_POST['u_name']) ? $_POST['u_name'] : '';
      $sql = "SELECT regisid FROM kdb WHERE name = '$u_name'";
      $result = mysqli_query($link,$sql);

      if($result)
      {
            $u_reg=isset($_POST['u_reg']) ? $_POST['u_reg'] : '';
            $row = mysqli_fetch_array($result);

            if(is_null($row['regisid']))
            {
                    echo "Can not find NAME"."</br>";
            }
            else
            {
                    if(strcmp($row['regisid'], $u_reg))
                    {     #Not registerd
                          echo "0\n";
                    }
                    else
                    { 	  # Registered
                          # Is Manager check
                          $sql_man = "SELECT manager FROM kdb where name='$u_name'"; # TO DO : may be this will be problemed.
                          $res_man = mysqli_query($link, $sql_man);
                          if($res_man)
                          {
                                $row_man = mysqli_fetch_array($res_man);
                                $list_can=array();
                                if(strcmp($row_man['manager'], 0))
                                {     # Yes Manager
                                        $sql_can = "SELECT name FROM kdb where candidate='1'";
                                        $res_can = mysqli_query($link, $sql_can);
                                        if($res_can)
                                        {     #candidate is here
                                              while($row_can = mysqli_fetch_array($res_can))
                                              {
                                                  #printf("\"candidate\" : \"%s\"</br>", $row_can['name']);
                    								              array_push($list_can, $row_can['name']);
                                              }
                                  						#print_r(array_keys($list_can));
                                  						sendTophp($list_can,'./manager.php');
                                  						//print_r($list_can);
                                              mysqli_free_result($res_can);
                                  						echo "1101\n"."</br>";
                                  						#echo("<script>location.replace('./manager.php');</script>");
                                        }
                                        else
                                        {     #Candidates doesn't exist in kdb
                                              echo "4402\n"."</br>";
                                        }
                                }
                                else
                                {     # Not a manager, but voter
                                        header('Content-Type:application/json');
                                        echo  "1401\n"."</br>";

                                        print_r($list_can);
                                        sendVCode($link, $u_reg);
                                        sendTophp($list_can,'./voter.php');
                                        /* test */
                                        //$sampledata=array("vcode"=> "1234567",
                                          //                "kaddr"=> "12345678901234567890123456789012");
                                        //$_jsdata=json_encode($sampledata);
                                        //print("$_jsdata\n");
                                        //$v_url="./voter.php";
                                        //$ret= Curl($v_url, $sampledata);
                                        //var_dump($ret);
                                        /* /test */
		                                    //echo("<script>location.replace('./voter.php');</script>");
                                }
                          }
                          else
                          { #Managers doesn't exist in kdb.
                                echo "4401\n"."</br>";
                          }
                    }
     		    }
      }
      else
      {   echo "4441\n"."</br>";
          echo mysqli_errno($link);}
      }
else
{
    echo "Type your ID"."</br>";
}
    mysqli_close($link);
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
