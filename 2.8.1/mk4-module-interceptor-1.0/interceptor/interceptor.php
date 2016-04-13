<?php

require("interceptor_vars.php");

?>
<html>
<head>
<title>Pineapple Control Center - <?php echo $module_name." [v".$module_version."]"; ?></title>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.idTabs.min.js"></script>

<script type="text/javascript" src="js/interceptor.js"></script>
<link rel="stylesheet" type="text/css" href="css/interceptor.css" />
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
	if ($is_interceptor_installed) 
	{
		echo "Interceptor <span id=\"interceptor2_status\"><font color=\"lime\"><strong>installed</strong></font></span>";
		echo " | <a id=\"interceptor2_link\" href=\"javascript:interceptor_toggle('uninstall');\"><strong>Uninstall</strong></a><br /><br />";
	}
	else 
	{ 
		echo "Interceptor <span id=\"interceptor2_status\"><font color=\"red\"><strong>not installed</strong></font></span>";
		echo " | <a id=\"interceptor2_link\" href=\"javascript:interceptor_toggle('install');\"><strong>Install</strong></a><br /><br />"; 
	}
	
	if ($is_interceptor_running) 
	{
		echo "Interceptor <span id=\"interceptor_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"interceptor_link\" href=\"javascript:interceptor_toggle('stop');\"><strong>Stop</strong></a> ";
		
		if($is_8021X)
			echo '<input type="checkbox" checked="checked" id="8021X" name="8021X" value="8021X" /> 802.1X<br /><br />';
		else
			echo '<input type="checkbox" id="8021X" name="8021X" value="8021X" /> 802.1X<br /><br />';
	}
	else
	{ 
		echo "Interceptor <span id=\"interceptor_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"interceptor_link\" href=\"javascript:interceptor_toggle('start');\"><strong>Start</strong></a> "; 
		
		if($is_8021X)
			echo '<input type="checkbox" checked="checked" id="8021X" name="8021X" value="8021X" /> 802.1X<br /><br />';
		else
			echo '<input type="checkbox" id="8021X" name="8021X" value="8021X" /> 802.1X<br /><br />';
	}
	
	if ($is_interceptor_onboot || $is_8021X_onboot)
	{
		echo "Autostart <span id=\"boot_status\"><font color=\"lime\"><strong>enabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('disable');\"><strong>Disable</strong></a> ";
		
		if($is_8021X_onboot)
			echo '<input type="checkbox" checked="checked" disabled id="8021X_onboot" name="8021X_onboot" value="8021X" /> 802.1X<br />';
		else
			echo '<input type="checkbox" id="8021X_onboot" disabled name="8021X_onboot" value="8021X" /> 802.1X<br />';
	}
	else 
	{ 
		echo "Autostart <span id=\"boot_status\"><font color=\"red\"><strong>disabled</strong></font></span>";
		echo " | <a id=\"boot_link\" href=\"javascript:boot_toggle('enable');\"><strong>Enable</strong></a> "; 
		
		if($is_8021X_onboot)
			echo '<input type="checkbox" checked="checked" id="8021X_onboot" name="8021X_onboot" value="8021X" /> 802.1X<br />';
		else
			echo '<input type="checkbox" id="8021X_onboot" name="8021X_onboot" value="8021X" /> 802.1X<br />';
	}
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
		<li><a id="Help_link" href="#Help">Help</a></li>
	</ul>

<div id="Output">
	[<a id="refresh" href="javascript:refresh();">Refresh</a>]<br /><br />
	<textarea id='output' name='output' cols='85' rows='29'></textarea>
</div>

<div id="Help">
	<div>
		<strong>Usage:</strong>	<br><br>
		1. Install Interceptor through the Pineapple Control Center module page. <br> A new SSID <strong>Interceptor</strong> with preshared key <strong>Int3rc3pt0r</strong> will be created.<br><br>
		2. Connect to the <strong>Interceptor</strong> SSID.<br><br>
		3. Start Interceptor through the Pineapple Control Center module page on <strong>http://172.15.42.1:1471</strong>.<br><br>
		4. Connect trough SSH to the Pineapple <strong>ssh root@172.15.42.1</strong>.<br>
		<br>
		<strong>Example:</strong>	<br><br>
		1. Remotly capture traffic <strong>ssh root@172.15.42.1 /usb/usr/sbin/tcpdump -i eth1 -w - 'port !22' > capture.pcap</strong><br>
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
