<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/dnsspoof/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/dnsspoof/includes/js/infusion.js'></script>

<style>@import url('/components/infusions/dnsspoof/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ dnsspoof_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="dnsspoof" class="refresh_text"></span></div>
<div class=sidePanelContent>
<?php
if($is_dnsspoof_installed)
{
	echo "dnsspoof";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";
}
else
{
	echo "dnsspoof";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br />";
}

if ($is_dnsspoof_running) 
{
	echo "dnsspoof <span id=\"dnsspoof_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
	echo " | <a id=\"dnsspoof_link\" href=\"javascript:dnsspoof_toggle('stop');\"><strong>Stop</strong></a><br />";
}
else
{ 
	echo "dnsspoof <span id=\"dnsspoof_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
	echo " | <a id=\"dnsspoof_link\" href=\"javascript:dnsspoof_toggle('start');\"><strong>Start</strong></a><br />"; 
}

if ($is_dnsspoof_onboot) 
{
	echo "Autostart <span id=\"dnsspoof_boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
	echo " | <a id=\"dnsspoof_boot_link\" href=\"javascript:dnsspoof_boot_toggle('disable');\"><strong>Disable</strong></a><br />";
}
else 
{ 
	echo "Autostart <span id=\"dnsspoof_boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
	echo " | <a id=\"dnsspoof_boot_link\" href=\"javascript:dnsspoof_boot_toggle('enable');\"><strong>Enable</strong></a><br />"; 
}

if($fake_files_installed)
{
	echo "Fake captive portal files <span id=\"fake_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
	echo " | <a id=\"fake_link\" href=\"javascript:dnsspoof_fake_toggle('uninstall');\"><strong>Uninstall</strong></a><br />";
}
else
{
	echo "Fake captive portal files <span id=\"fake_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
	echo " | <a id=\"fake_link\" href=\"javascript:dnsspoof_fake_toggle('install');\"><strong>Install</strong></a><br />";
} 
?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="History_link" href="#History">History</a></li>
		<li><a id="Hosts_link" href="#Hosts">Hosts</a></li>
		<li><a id="Redirect_link" href="#Redirect">Redirect.php</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:dnsspoof_refresh();">Refresh</a>]<br /><br />
	<textarea class="dnsspoof" id='dnsspoof_output' name='dnsspoof_output' cols='85' rows='29'></textarea>
</div>

<div id="History">
	[<a id="refresh" href="javascript:dnsspoof_refresh_history();">Refresh</a>]<br />
	<div id="dnsspoof_content_history"></div>
</div>

<div id="Hosts">
	[<a href="javascript:dnsspoof_update_conf($('#hosts').val(), 'hosts');">Save</a>]<br /><br />
	<?php
		echo "<textarea class='dnsspoof' id='hosts' name='hosts' cols='85' rows='29'>"; echo file_get_contents($hosts_path); echo "</textarea>";
	?>
</div>

<div id="Redirect">
	[<a href="javascript:dnsspoof_update_conf($('#redirect').val(), 'redirect');">Save</a>]<br /><br />
	<?php
		echo "<textarea class='dnsspoof' id='redirect' name='redirect' cols='85' rows='29'>"; echo file_get_contents($redirect_path); echo "</textarea>";
	?>
</div>

</div>
<br />
Auto-refresh <select class="dnsspoof" id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="dnsspoof_auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a>