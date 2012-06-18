<?php
header('content-type:text/html;charset=utf-8');
$t_start = microtime(true);
?>
<style type="text/css">
  .R1
  {
    background-color:#CCCCCC;
  }
  .pit
  {
    border: #CCCCFF solid 3px;
  }
  .driving
  {
    font-size:11px;
    font-weight: bold;
  }
  .new
  {
    background-color:#DDF;
  }
  .gain
  {
    background-color:#BFB;
  }
  .loss
  {
    background-color:#FBB;
  }
  table
  {
    font-family: "Arial";
    font-size: 12px;
    margin-right:50px;
  }
  .driver
  {
    font-size: 8px;
  }
  ul{
    margin-bottom:0px; 
  }
  .P1
  {
    background-color:#FFCCCC;
  }
  .P2
  {
    background-color:#CCFFCC;
  }
  .PRO
  {
    background-color:#CCCCFF;
  }
  .AM
  {
    background-color:#FFFFCC;
  }
  .R2
  {
    background-color: #CFC;
  }
  .R4
  {
    background-color: #FCC;
  }
  .R3
  {
    background-color: #FFC;
  }
  ul
  {
    margin-left:0px;
    padding-left:0px;
    list-style: none;
  }
  .c
  {
    text-align: center;
  }
  td
  {
    padding-left:2px;
    padding-right:2px;
  }
  table
  {
    float:left;
  }
</style>

<?php

include ("include.php");

$user = $_GET["user"];
if(!$user)
{
  $user = $_SERVER["REMOTE_ADDR"];
}

echo "<title>CuttlefishTech Le Mans Stats - for $user</title>";

$countries = json_decode($jnationality,true);
$entry = json_decode($jengages,true);
$cars = json_decode($jvehicles,true);
$teams = json_decode($jteams,true);
$drivers = json_decode($jdrivers,true);
$marques = json_decode($jmarques,true);
$classes = json_decode($jclasses,true);

//echo "<pre>";
//print_r($json[0]);
//print_r($classes);
//print_r($countries[1]);
//print_r($teams[1]);
//print_r($cars[$entry[$json[1][1][7]]["voiture"]]);
//print_r($entry[$json[1][1][7]]);
//print_r($json[1][53]);
//print_r($drivers[22]);
//print_r($marques);
//echo "</pre>";

$result = dbquery("SELECT * from laps where num='997' order by timestamp desc limit 2;");
$ts1 = mysql_fetch_array($result);
$ts2 = mysql_fetch_array($result);

$tsnew = $ts1["timestamp"]; 
$tsold = $ts2["timestamp"];

if ($user)
{
  $result = dbquery("SELECT * from users where user='$user'");
  $row = mysql_fetch_array($result);
  $ut = $row["timestamp"];
  if($row)
  {
    $ut = $row["timestamp"];
    $result = dbquery("SELECT * from laps where num='997' and timestamp='$ut' limit 1;");
    $tsu = mysql_fetch_array($result);
    $tsold = $tsu["timestamp"];
    if ($tsold == $tsnew)
      $tsold = $tsold = $ts2["timestamp"];
  }
}

echo "<div>Most recent timestamp: $tsnew, old timestamp " . $tsold . "</div>";

?>
<table border="1" cellspacing ="0" cellpadding ="0">
  <tr>
    <td>Pos</td>
    <td>Num</td>
    <td>Team</td>
    <td>Driver</td>
    <td>Laps</td>
    <td>Diff</td>
    <td>Best</td>
    <td>Last</td>
    <td>Pits </td>
  </tr>
    
<?

$new_result = dbquery("SELECT * FROM laps WHERE timestamp='$tsnew' order by pos asc");
$old_result = dbquery("SELECT * FROM laps WHERE timestamp='$tsold' order by pos asc");

while($new_row = mysql_fetch_array($new_result))
  $newArray[$new_row["pos"]] = $new_row;
while($old_row = mysql_fetch_array($old_result))
  $oldArray[$old_row["num"]] = $old_row;

foreach($newArray as $pos=>$car)
{
  $num = $entry[$car["num"]]["num"];
  $class = $classes[$entry[$car["num"]]["categorie"]]["nom"];
  $teamname = ucwords(strtolower($teams[$entry[$car["num"]]["team"]]["nom"]));
  $teamcountry = $countries[$teams[$entry[$car["num"]]["team"]]["pays"]]["short"];
  $marquename = ucwords(strtolower($marques[$cars[$entry[$car["num"]]["voiture"]]["marque"]]));
  $carname = ucwords(strtolower($cars[$entry[$car["num"]]["voiture"]]["nom"]));
  $driverid = $car["driver"];
  $driverarray = $entry[$car["num"]]["pilotes"];
  $laps = $car["laps"];
  $diff = $car["diff"];
  $best = $car["best"];
  $last = $car["last"];
  $speed = "N/A";
  $pits = $car["pits"];
  $status = $car["status"];
  $tires = "N/A";
  $row = $oldArray[$car["num"]];
  if ($pos == 29)
  {  ?>
  <!--</table>
  <table border="1" cellspacing ="0" cellpadding ="0">
  <tr>
    <td>Pos</td>
    <td>Num</td>
    <td>Team</td>
    <td>Driver</td>
    <td>Laps</td>
    <td>Diff</td>
    <td>Best</td>
    <td>Last</td>
    <td>Pits </td>
  </tr>-->
  
  <?
  }
  echo "<tr>";
  if($pos > $row["pos"])
    echo "<td class=\"loss c\">";
  else if($pos < $row["pos"])
    echo "<td class=\"gain c\">";
  else
    echo "<td class=\"c\">";
  echo "$pos</td>";
  
  echo "<td class=\"$class c\">$num</td>";
  echo "<td>$teamname ($teamcountry)<br>$marquename $carname</td>";
  echo "<td class=\"driver\"><ul>";
  foreach($driverarray as $id => $driver)
  { 
    if($driver == $driverid)
      echo "<li class=\"driving\">";
    else
      echo "<li class=\"notdriving\">";
    echo ucwords(mb_strtolower($drivers[$driver]["prenom"],'UTF-8')) . " " . ucwords(mb_strtolower($drivers[$driver]["nom"],'UTF-8'));
    echo " " . $countries[$drivers[$driver]["pays"]]["short"];
    echo "</li>";
  }
  echo "</ul></td>";
  
  if ($laps != $row["laps"])
    echo "<td class=\"new c\">";
  else
    echo "<td class=\"c\">";
  echo "$laps</td>";
  /*$diffdiff = diff($diff,$row["diff"]);
  if($diffdiff > 0)
    echo "<td class=\"loss\">";
  else if($diff < $row["diff"])
    echo "<td class=\"gain\">";
  else*/
  
  if ($diff != $row["diff"])
    echo "<td class=\"new\">";
  else
    echo "<td>";
  echo "$diff";
  /*$diffdiff = diff($diff,$row["diff"]);
  if($diffdiff != 0)  
    echo " (" . $row["diff"] .")<br>$diffdiff";   
    */
  echo "</td>";
  
  if($best > $row["best"])
    echo "<td class=\"loss\">";
  else if($best < $row["best"])
    echo "<td class=\"gain\">";
  else
    echo "<td>";
  $bestdiff = diff($row["best"],$best);
  echo "$best";
  if($bestdiff != 0)
    echo"(".$row["best"].")<br>$bestdiff";
  echo "</td>";
  
  if ($last != $row["last"])
    echo "<td class=\"new\">";
  else
    echo "<td>";
  $lastdiff = diff($row["last"],$last);
  if($lastdiff != 0)
    echo "$last (".$row["last"].")<br>$lastdiff";
  else
    echo "$last";
  echo "</td>";
  
  
  if ($pits != $row["pits"])
  {
    echo "<td class=\"R$status c pits\">";
    echo "$pits (".$row["pits"].")</td>";
  }
  else
  {
    echo "<td class=\"R$status c\">";
    echo "$pits</td>";
  }
  echo "</tr>";
  
}
if($user)
    dbquery("REPLACE INTO users (user,timestamp) VALUES ('$user','$tsnew')");

?>
</table>
<? 
$t_end = microtime(true);
?>
<span style="display:none">
<?= "Render time: " . ($t_end - $t_start) ?>
</span>