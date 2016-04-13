<?php

require("karmastats_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/karmastats.js"></script>
<link rel="stylesheet" type="text/css" href="css/karmastats.css" />
<link rel="stylesheet" type="text/css" href="css/firmware.css" />

<link rel="icon" href="/favicon.ico" type="image/x-icon"> 
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<script type="text/javascript" charset="utf-8">
	$(document).ready( function () { init(); });	
</script>

<?php if(file_exists("/pineapple/includes/navbar.php")) require('/pineapple/includes/navbar.php'); ?>

<p id="version">v<?php echo $module_version; ?></p>

<div class=sidePanelLeft>
<div class=sidePanelTitle><?php echo $module_name; ?> <span id="refresh_text"></span></div>
<div class=sidePanelContent>
<?php
if($installed)
{
	echo "Pineapple ID <font color=\"lime\"><strong>".$pineNumbers."</strong></font><br/>";
	if($pineName != "")
		echo "Pineapple Name <font color=\"lime\"><strong>".$pineName."</strong></font><br/>";
	else
		echo "Pineapple Name <font color=\"red\"><strong><em>not defined</em></strong></font> [<a href=\"javascript:showTab()\">Configuration</a>]<br/>";
	echo "Pineapple MAC <font color=\"lime\"><strong>".$pineMAC."</strong></font><br/>";
	echo "Pineapple Date and Time <font color=\"lime\"><strong>".$pineDateTime."</strong></font><br/>";
	if($pineLatitude != "" && $pineLatitude != "")
		echo "Pineapple Position <font color=\"lime\"><strong>".$pineLatitude." / ".$pineLatitude."</strong></font><br/><br/>";
	else
		echo "Pineapple Position <font color=\"red\"><strong><em>not defined</em></strong></font> [<a href=\"javascript:showTab()\">Configuration</a>]<br/><br/>";

	if ($is_daemon_running) 
	{
		echo "Daemon <span id=\"daemon_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"daemon_link\" href=\"javascript:daemon_toggle('stop');\"><strong>Stop</strong></a><br />";
	}
	else
	{ 
		echo "Daemon <span id=\"daemon_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"daemon_link\" href=\"javascript:daemon_toggle('start');\"><strong>Start</strong></a><br />"; 
	}
	
	if ($is_daemon_onboot) 
	{
		echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('disable');\"><strong>Disable</strong></a><br /><br />";
	}
	else 
	{ 
		echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('enable');\"><strong>Enable</strong></a><br /><br />"; 
	}

	if($is_watchdog_installed)
	{
		echo "Watchdog <span id=\"watchdog_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
		echo " | <a id=\"watchdog_link\" href=\"javascript:watchdog_toggle('disable');\"><strong>Uninstall</strong></a>";
		echo " | <a href=\"/index.php?jobs\"><b>Edit</b></a><br />";
	}
	else
	{
		echo "Watchdog <span id=\"watchdog_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
		echo " | <a id=\"watchdog_link\" href=\"javascript:watchdog_toggle('enable');\"><strong>Install</strong></a>";
		echo " | <a href=\"/index.php?jobs\"><b>Edit</b></a><br />";
	}
	if($watchdog_update != "")
		echo "Last watchdog update: <font color=\"lime\"><strong>".$watchdog_update."</strong></font><br/>";
	else
		echo "Last watchdog update: <font color=\"red\"><strong>N/A</strong></font><br/>";
}
else if($install_error)
{
	echo "No internet connection...<br /><br />";
		
	echo "Please check your network connectivity...<br /><br />";
		
	echo '[<a href="javascript:reload();">Reload</a>]';
	
	exec("rm -rf ".$module_path."install_error");
				
	exit();
}
else
{
	echo "All required dependencies have to be installed first. This may take a few minutes.<br /><br />";
		
	echo "Please wait, do not leave or refresh this page. Once the install is complete, this page will refresh automatically.<br /><br />";
		
	echo '[<a id="Install" href="javascript:install();">Install</a>]';
				
	exit();
}
?>
</div>
</div>

<div id="tabs" class="tab">
	<ul>
		<li><a id="Output_link" class="selected" href="#Output">Output</a></li>
		<li><a id="Configuration_link" href="#Conf">Configuration</a></li>
		<li><a id="Help_link" href="#Help">Help</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:refresh();">Refresh</a>] [<a id="clean" href="javascript:clean();">Clean log</a>]<br /><br />
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="Conf">
	[<a id="config" href="javascript:set_config();">Save</a>]<br />
	<div id="content_conf"></div>
</div>

<div id="Help">
	<div>
		<strong>Usage:</strong>	<br><br>
		1. Upload server-side <a href="karmastats.tar.gz">files</a> on a remote server.<br><br>
		2. Create DB Structure by using karmastats.sql.<br><br>
		3. Go to your remote server and follow the instructions for the installation.
	</div>
</div>

</div>
<br />
Auto-refresh <select id="auto_time">
	<option value="1000">1 sec</option>
	<option value="5000">5 sec</option>
	<option value="10000">10 sec</option>
	<option value="15000">15 sec</option>
	<option value="20000">20 sec</option>
	<option value="25000">25 sec</option>
	<option value="30000">30 sec</option>
</select> <a id="auto_refresh" href="javascript:void(0);"><font color="red">Off</font></a>

</body>
</html>
