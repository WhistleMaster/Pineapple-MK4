<?php

require("/pineapple/components/infusions/wifimanager/handler.php");

global $directory;

require($directory."includes/vars.php");
require($directory."includes/iwlist_parser.php");

?>

<script type='text/javascript' src='/components/infusions/wifimanager/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/wifimanager/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/wifimanager/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ 
		refresh_available_ap();
	});
	
	function refresh_available_ap() {
		wifimanager_myajaxStart();

		$.ajax({
			type: "GET",
			data: "available_ap&int="+$("#interfaces").val(),
			url: "/components/infusions/wifimanager/includes/interfaces.php",
			success: function(msg){
				$("#list_ap").html(msg);
				wifimanager_myajaxStop('');
				$('#wifimanager-survey-grid tr').click(function() { 
					$("#" + "<?php echo $_GET['w']?>").val($(this).attr("name"));
					close_popup();
					return false;
				});
			}
		});
	}
</script>
	
<?php

echo '<select class="wifimanager" id="interfaces" name="interfaces">';
foreach($wifi_interfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
echo '</select>&nbsp;';
echo '[<a id="refresh" href="javascript:refresh_available_ap();">Refresh</a>] <span id="wifimanager" class="refresh_text"></span><br/><br/>';

echo '<em>Click on row to add AP name to SSID field</em><br/><br/>';

echo '<div id="list_ap"></div>';
	
?>