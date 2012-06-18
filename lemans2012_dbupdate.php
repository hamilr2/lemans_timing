<?php
header('content-type:text/html;charset=utf-8');
echo "<div>";

include ("include.php");
$t_start = microtime(true);
$contents = do_post_request("http://live.lemans-tv.com/proxy.html?file=1/live/data.js","1/live/data.js",$log,$headers);
$t_request = microtime(true);
//print_r($contents);


$json = json_decode($contents,true);


$remain = $json[0][10];
$timestamp = $json[0][9];
$local = $json[0][0];
if(count($json[1]) == 56)
  echo " - Received timestamp: $timestamp, ".count($json[1]) . " rows of data";
else
{
  echo " - ERROR, invalid data received - Request: " . ($t_request - $t_start) ."</div>";
  die();
}

foreach($json[1] as $pos=>$car)
{
  $fakenum = $car[9];
  $driverid = $car[0];
  $laps = $car[2];
  $diff = $car[4];
  $best = $car[5];
  $last = $car[6];
  $speed = $car[8];
  $pits = $car[7];
  $status = $car[1];
  $tires = $car[10];
  
  $query = "REPLACE INTO laps (num, timestamp, last, best, diff, driver, pits, laps, pos, status) VALUES ('$fakenum','$timestamp','$last','$best','$diff','$driverid','$pits','$laps','$pos','$status')";
  dbquery($query);
}

$t_process = microtime(true);

echo " - Request: " . ($t_request - $t_start) . ", Process: " . ($t_process - $t_request);

?>
</div>