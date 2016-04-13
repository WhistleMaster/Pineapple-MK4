<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/sitesurvey/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/sitesurvey/includes/js/infusion.js'></script>
<style>@import url('/components/infusions/sitesurvey/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ sitesurvey_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="sitesurvey" class="refresh_text"></span></div>
<div class=sidePanelContent>
<?php

echo "WLAN interface ";
echo "<span id=\"interfaces_l\">";
echo '<select class="sitesurvey" id="interfaces" name="interfaces">';
foreach($interfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
echo '</select>';
echo "</span>";
echo "&nbsp;| <a id=\"interface\" href=\"javascript:sitesurvey_interface_toggle('start');\"><strong>Start</strong></a> - <a id=\"interface\" href=\"javascript:sitesurvey_interface_toggle('stop');\"><strong>Stop</strong></a> [<a id=\"auto_interface\" href=\"javascript:sitesurvey_auto_toggle();\"><strong>Auto</strong></a>] | <a id=\"monitorInterface\" href=\"javascript:sitesurvey_monitor_toggle('start');\"><strong>Start Monitor</strong></a><br />";

echo "Monitor interface ";
echo "<span id=\"monitorInterface_l\">";
echo '<select class="sitesurvey" id="monitorInterfaces" name="monitorInterfaces">';
echo '<option value="">--</option>'; 
foreach($monitorInterfaces as $value) { echo '<option value="'.$value.'">'.$value.'</option>'; }
echo '</select>';
echo "</span>";
echo "&nbsp;|  <a id=\"monitorInterface\" href=\"javascript:sitesurvey_monitor_toggle('stop');\"><strong>Stop Monitor</strong></a><br />";
?>
</div>
</div>

[<a id="refresh" href="javascript:sitesurvey_refresh(0);">Refresh APs</a>] [<a id="clients" href="javascript:sitesurvey_refresh(1);">Refresh Clients</a>]<br /><br />
<div id="content"></div><br />
Auto-refresh <select class="sitesurvey" id="sitesurvey_auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <select class="sitesurvey" id="sitesurvey_auto_what">
	<option value="0">APs</option>
	<option value="1">All</option>
</select> <a id="sitesurvey_auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a> 

<div id="tabs2" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="Captures_link" href="#Captures">Captures</a></li>
		<li><a id="History_link" href="#History">History</a></li>
		<li><a id="Configuration_link" href="#Conf">Configuration</a></li>
	</ul>
	
<div id="Output">
	<textarea readonly class="sitesurvey" id='sitesurvey_output' name='sitesurvey_output' cols='85' rows='29'></textarea>
</div>

<div id="Captures">
	[<a id="refresh" href="javascript:sitesurvey_refresh_history();">Refresh</a>]<br />
	<div id="content_captures"></div>
</div>

<div id="History">
	[<a id="refresh" href="javascript:sitesurvey_refresh_history();">Refresh</a>]<br />
	<div id="content_history"></div>
</div>

<div id="Conf">
	[<a id="config" href="javascript:sitesurvey_set_config();">Save</a>]<br />
	<div id="content_conf"></div>
</div>

</div>