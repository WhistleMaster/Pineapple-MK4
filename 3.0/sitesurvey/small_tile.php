<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");
require($directory."includes/iwlist_parser.php");

?>

<script type='text/javascript' src='/components/infusions/sitesurvey/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/sitesurvey/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ sitesurvey_init_small(); });
</script>

<?php

echo '<select class="sitesurvey" id="sitesurvey_interfaces" name="sitesurvey_interfaces">';
foreach($interfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
echo '</select>&nbsp;';
echo '[<a id="refresh" href="javascript:sitesurvey_refresh_available_ap();">Refresh</a>]&nbsp;<span id="sitesurvey_small" class="refresh_text"></span><br/><br/>';

echo '<div id="sitesurvey_list_ap"></div><br />';
	
?>

Auto-refresh <select class="sitesurvey" id="sitesurvey_small_auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="sitesurvey_small_auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a> 