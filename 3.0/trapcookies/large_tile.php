<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/trapcookies/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/trapcookies/includes/js/infusion.js'></script>

<style>@import url('/components/infusions/trapcookies/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ trapcookies_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="trapcookies" class="refresh_text"></span></div>
<div class=sidePanelContent>
<?php
if($is_ngrep_installed)
{
	echo "ngrep";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";

	if ($is_ngrep_running) 
	{
		echo "trapcookies <span id=\"trapcookies_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"trapcookies_link\" href=\"javascript:trapcookies_toggle('stop');\"><strong>Stop</strong></a><br />";
	}
	else
	{ 
		echo "trapcookies <span id=\"trapcookies_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"trapcookies_link\" href=\"javascript:trapcookies_toggle('start');\"><strong>Start</strong></a><br />"; 
	}
	
	if ($is_dnsspoof_running) 
	{
		echo "dnsspoof <span id=\"dnsspoof_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"dnsspoof_link\" href=\"javascript:trapcookies_dnsspoof_toggle('stop');\"><strong>Stop</strong></a><br />";
	}
	else
	{ 
		echo "dnsspoof <span id=\"dnsspoof_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"dnsspoof_link\" href=\"javascript:trapcookies_dnsspoof_toggle('start');\"><strong>Start</strong></a><br />"; 
	}
	
	if($is_landing_installed)
	{
		echo "Landing Page <span id=\"landing_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
		echo " | <a id=\"landing_link\" href=\"javascript:trapcookies_landing_toggle('uninstall');\"><strong>Uninstall</strong></a><br />";
	}
	else
	{
		echo "Landing Page <span id=\"landing_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
		echo " | <a id=\"landing_link\" href=\"javascript:trapcookies_landing_toggle('install');\"><strong>Install</strong></a><br />";
	} 

	if ($is_ngrep_onboot) 
	{
		echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:trapcookies_boot_toggle('disable');\"><strong>Disable</strong></a><br />";
	}
	else 
	{ 
		echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:trapcookies_boot_toggle('enable');\"><strong>Enable</strong></a><br />"; 
	}
}
else
{
	echo "ngrep";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";

	echo "Install to <a id=\"install_int\" href=\"javascript:trapcookies_install('internal');\">Internal Storage</a> or <a id=\"install_usb\" href=\"javascript:trapcookies_install('usb');\">USB Storage</a>";
	
	exit();	
}
?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="History_link" href="#History">History</a></li>
		<li><a id="Hosts_link" href="#Hosts">Hosts</a></li>
		<li><a id="Landing_link" href="#Landing">Landing</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:trapcookies_refresh();">Refresh</a>]<br /><br />
	<textarea class="trapcookies" id='trapcookies_output' name='trapcookies_output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:trapcookies_refresh_history();">Refresh</a>]<br />
	<div id="content_history"></div>
</div>

<div id="Hosts">
	[<a href="javascript:trapcookies_update_conf($('#hosts').val(), 'hosts');">Save</a>]<br /><br />
	<?php
		echo "<textarea class='trapcookies' id='hosts' name='hosts' cols='85' rows='29'>"; echo file_get_contents($hosts_path); echo "</textarea>";
	?>
</div>

<div id="Landing">
	[<a href="javascript:trapcookies_update_conf($('#landing').val(), 'landing');">Save</a>]<br /><br />
	<?php
		echo "<textarea class='trapcookies' id='landing' name='landing' cols='85' rows='29'>"; echo file_get_contents($landing_path); echo "</textarea>";
	?>
</div>

</div>
<br />
Auto-refresh <select class="trapcookies" id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="trapcookies_auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a>