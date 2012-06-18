<script type="text/javascript" src="jquery-1.5.1.min.js" ></script>
<script type="text/javascript" src="jquery-ui-1.8.12.custom.min.js" ></script>

<button id="update" name="Update" >Update</button>
  <div id="updates">
    
    
  </div>
<script type="text/javascript">

function update(){
  $("#updates").prepend("<div />");
  $("#updates div:first").load("lemans2012_dbupdate.php");
}

$("#update").click(update);

setInterval("update()",10000);

</script>
