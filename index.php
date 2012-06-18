<?
$user = $_GET["u"];
?>

<style type="text/css">
  #control
  {
    position:absolute;
    top:0px;
    right:0px;
  }
</style>

<script type="text/javascript" src="jquery-1.5.1.min.js" ></script>
<div id="control"><input type="checkbox" id="auto-update"/>Auto-update every 30s. Next update in <span id="countdown">0</span></div>

<script type="text/javascript">
  var id, id2;
  var count;
  $("#auto-update").click(function(){
    if (this.checked)
      {
        id = setInterval(function(){
          <? if ($user) { ?>
          $("#target").load("lemans2012_cached.php?user=<?= $user ?>");
          <? } else { ?>
          $("#target").load("lemans2012_cached.php");
          <? } ?>
          count = 30;
        },30000);
        count = 30;
        $("#countdown").html("30");
        id2 = setInterval(function(){
          $("#countdown").html(count);
          count--;
        },1000);
        
      }
      else
      {
        clearInterval(id);
        clearInterval(id2);
        count = 0;
        $("#countdown").html("0");
      }
  });


$(document).ready(function(){
  <? if ($user) { ?>
            $("#target").load("lemans2012_cached.php?user=<?= $user ?>");
  <? } else { ?>
            $("#target").load("lemans2012_cached.php");
  <? } ?>
});
</script>
<div id="target">Loading...</div>