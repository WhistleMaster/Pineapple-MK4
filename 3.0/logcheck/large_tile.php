<?php

global $directory, $rel_dir, $version, $name;
require($directory."includes/vars.php");

?>

<script type='text/javascript' src='/components/infusions/logcheck/includes/js/jquery.idTabs.min.js'></script>
<script type='text/javascript' src='/components/infusions/logcheck/includes/js/infusion.js'></script>

<style>@import url('/components/infusions/logcheck/includes/css/infusion.css')</style>

<script type="text/javascript">
	$(document).ready(function(){ logcheck_init(); });
</script>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $name; ?> - v<?php echo $version; ?>&nbsp;<span id="logcheck" class="refresh_text"></span></div>
<div class=sidePanelContent>
<?php 
if($is_ssmtp_installed)
{
	echo "ssmtp";
	echo "&nbsp;<font color=\"lime\"><strong>installed</strong></font><br />";
	
	if ($is_logcheck_running) 
	{
		echo "Logcheck <span id=\"logcheck_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"logcheck_link\" href=\"javascript:logcheck_toggle('stop');\"><strong>Stop</strong></a><br />";
	}
	else 
	{ 
		echo "Logcheck <span id=\"logcheck_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"logcheck_link\" href=\"javascript:logcheck_toggle('start');\"><strong>Start</strong></a><br />"; 
	}

	if ($is_logcheck_onboot) 
	{
		echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:logcheck_boot_toggle('disable');\"><strong>Disable</strong></a><br />";
	}
	else 
	{ 
		echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:logcheck_boot_toggle('enable');\"><strong>Enable</strong></a><br />"; 
	}

	if($is_daemon_installed)
	{
		echo "cron <span id=\"cron_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
		echo " | <a id=\"cron_link\" href=\"javascript:logcheck_daemon_toggle('disable');\"><strong>Uninstall</strong></a>";
		echo " | <a href=\"javascript:hide_large_tile(); draw_large_tile('configuration', 'system'); selectTabContent('cron');\"><b>Edit</b></a><br />";	
	}
	else
	{
		echo "cron <span id=\"cron_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
		echo " | <a id=\"cron_link\" href=\"javascript:logcheck_daemon_toggle('enable');\"><strong>Install</strong></a>";
		echo " | <a href=\"javascript:hide_large_tile(); draw_large_tile('configuration', 'system'); selectTabContent('cron');\"><b>Edit</b></a><br />";		
	}
	if($daemon_update != "")
		echo "Last cron update: <font color=\"lime\"><strong>".$daemon_update."</strong></font><br />";
	else
		echo "Last cron update: <font color=\"red\"><strong>N/A</strong></font><br />";
}
else
{
	echo "ssmtp";
	echo "&nbsp;<font color=\"red\"><strong>not installed</strong></font><br /><br />";
	
	echo "Install to <a id=\"install_int\" href=\"javascript:logcheck_install('internal');\">Internal Storage</a> or <a id=\"install_usb\" href=\"javascript:logcheck_install('usb');\">USB Storage</a>";
		
	exit();
}
?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="Rules_link" href="#Rules">Rules</a></li>
		<li><a id="Rules_link" href="#Custom">Custom</a></li>
		<li><a id="Email_link" href="#Email">Email</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:logcheck_refresh();">Refresh</a>]<br /><br />
	<textarea class="logcheck" id='logcheck_output' name='logcheck_output' cols='85' rows='29'></textarea>
</div>
	
<div id="Rules">
	<strong>Matching Rules</strong> [<a href="javascript:logcheck_update_conf($('#match').val(), 'match');">Save</a>]<br /><br />
	<textarea class="logcheck" id='match' name='match' cols='85' rows='29'><?php echo file_get_contents($match_path); ?></textarea><br /><br />
	<strong>Ignore Rules</strong> [<a href="javascript:logcheck_update_conf($('#ignore').val(), 'ignore');">Save</a>]<br /><br />
	<textarea class="logcheck" id='ignore' name='ignore' cols='85' rows='29'><?php echo file_get_contents($ignore_path); ?></textarea>
</div>

<div id="Custom">
	<strong>Custom Script</strong> [<a href="javascript:logcheck_update_conf($('#custom').val(), 'custom');">Save</a>]<br /><br />
	<?php
		echo "<textarea class='logcheck' id='custom' name='custom' cols='85' rows='29'>"; if(file_exists($custom_path)) echo file_get_contents($custom_path); echo "</textarea>";
	?>
</div>

<div id="Email">
	<strong>Email Settings</strong> [<a href="javascript:logcheck_update_settings();">Save</a>] [<a href="javascript:logcheck_test_email();">Test</a>]<br /><br />
	<form id='logcheck_form_conf'>
	<input class="logcheck" type='hidden' name='set_conf' value='email'/>
	<table id="logcheck"  class="grid">
	<tr><td>To:</td> <td><input class="logcheck" type="text" id="to" name="to" value="<?php echo $To; ?>" size="50"></td></tr>
	<tr><td>From:</td> <td><input class="logcheck" type="text" id="from" name="from" value="<?php echo $From; ?>" size="50"></td></tr>
	<tr><td>Subject:</td> <td><input class="logcheck" type="text" id="subject" name="subject" value="<?php echo $Subject; ?>" size="50"></td></tr>
	</table>
	</form>
	<br />
	<?php
	if($is_ssmtp_installed)
	{
		echo "<strong>SMTP Configuration</strong> (<a href=\"javascript:logcheck_update_conf($('#smtp').val(), 'smtp');\">Save</a>)<br /><br />";
		echo "<textarea class='logcheck' id='smtp' name='smtp' cols='85' rows='29'>"; if(file_exists($smtp_path)) echo file_get_contents($smtp_path); echo "</textarea>";
	}
	else
	{
		echo "<strong>SMTP Configuration</strong><br /><br />";
		echo "<em>ssmtp not installed...</em>";
	}
	?>
</div>

</div>
<br />
Auto-refresh <select class="logcheck" id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="logcheck_auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a>