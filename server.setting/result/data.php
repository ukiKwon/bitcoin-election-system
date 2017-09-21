<?php 

$con = mysqli_connect("localhost","root","autoset","election");
if (!$con) {
  die('Could not connect: ' . mysql_error());
}

$result = mysqli_query($con,"SELECT name, number FROM name");
$rows = array();
//$rows['type'] = 'pie';
//$rows['name'] = 'Revenue';
//$rows['innerSize'] = '50%';
while ($r = mysqli_fetch_array($result)) {
    $rows[0]['data'][] = array($r['name'], $r['number']);    
}
$rslt = array();
array_push($rslt,$rows);
print json_encode($rslt, JSON_NUMERIC_CHECK);
mysqli_close($con);


?> 
  