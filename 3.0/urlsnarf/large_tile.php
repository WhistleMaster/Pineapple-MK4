<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/urlsnarf/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/urlsnarf/includes/js/jquery.base64.min.js'></script>
<script type='text/javascript' src='/components/infusions/urlsnarf/includes/js/infusion.js'></script>

<style>@import url('/components/infusions/urlsnarf/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ urlsnarf_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="urlsnarf" class="refresh_text"></span></div>
<div class=sidePanelContent>
<?php
if($is_urlsnarf_installed)
{
	echo "urlsnarf";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";
}
else
{
	echo "urlsnarf";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br />";
}

if ($is_urlsnarf_running) 
{
	echo "urlsnarf <span id=\"urlsnarf_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
	echo " | <a id=\"urlsnarf_link\" href=\"javascript:urlsnarf_toggle('stop');\"><strong>Stop</strong></a> ";
}
else
{ 
	echo "urlsnarf <span id=\"urlsnarf_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
	echo " | <a id=\"urlsnarf_link\" href=\"javascript:urlsnarf_toggle('start');\"><strong>Start</strong></a> "; 
}

if($is_urlsnarf_running)
	echo '<select class="urlsnarf" disabled="disabled" id="interface" name="interface">';
else
	echo '<select class="urlsnarf" id="interface" name="interface">';

for($i=0;$i<count($interfaces);$i++)
{
	if($current_interface == $interfaces[$i])
		echo '<option selected value="'.$interfaces[$i].'">'.$interfaces[$i].'</option>';
	else
		echo '<option value="'.$interfaces[$i].'">'.$interfaces[$i].'</option>';
}
echo '</select><br /><br />';

if ($is_urlsnarf_onboot) 
{
	echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
	echo " | <a id=\"boot_link\" href=\"javascript:urlsnarf_boot_toggle('disable');\"><strong>Disable</strong></a><br />";
}
else 
{ 
	echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
	echo " | <a id=\"boot_link\" href=\"javascript:urlsnarf_boot_toggle('enable');\"><strong>Enable</strong></a><br />"; 
}
?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="History_link" href="#History">History</a></li>
		<li><a id="Custom_link" href="#Custom">Custom</a></li>
		<li><a id="Configuration_link" href="#Conf">Configuration</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:urlsnarf_refresh();">Refresh</a>] Filter <input class="urlsnarf" type="text" id="filter" name="filter" value="" size="90"> <em>Piped commands used to filter output (e.g. grep, awk)</em><br /><br />
	<textarea class="urlsnarf" id='urlsnarf_output' name='urlsnarf_output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:urlsnarf_refresh_history();">Refresh</a>]<br />
	<div id="content_history"></div>
</div>

<div id="Custom">
	[<a id="refresh" href="javascript:urlsnarf_refresh_custom();">Refresh</a>]<br />
	<div id="content_custom"></div>
</div>

<div id="Conf">
	[<a id="config" href="javascript:urlsnarf_set_config();">Save</a>]<br />
	<div id="content_conf"></div>
</div>

</div>
<br />
Auto-refresh <select class="urlsnarf" id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="urlsnarf_auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a>