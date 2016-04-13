<?php

require("/pineapple/components/infusions/sitesurvey/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['int'])) $interface = $_GET['int'];
if (isset($_GET['mon'])) $monitorInterface = $_GET['mon'];

if (isset($_GET['monitor']))
{
	if (isset($_GET['start'])) 
		exec("airmon-ng start ".$interface." &");	
	if (isset($_GET['stop']))
		exec("airmon-ng stop ".$monitorInterface." &");
	
	echo '<font color="lime"><strong>done</strong></font>';	
}

if (isset($_GET['interface'])) 
{
	if (isset($_GET['start'])) 
		exec("ifconfig ".$interface." up &");
	if (isset($_GET['stop']))
		exec("ifconfig ".$interface." down &");
	
	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_GET['auto']))
{
	$isUP = exec("ifconfig ".$interface." | grep UP | awk '{print $1}'");
	
	if ($isUP == "UP")
		exec("ifconfig ".$interface." down && ifconfig ".$interface." up &");
	else
		exec("ifconfig ".$interface." up && ifconfig ".$interface." down &");

	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
			exec("rm -rf ".$directory."includes/log/".$_GET['file']."*");
		if (isset($_GET['cap']))
			exec("rm -rf ".$directory."includes/captures/".$_GET['file']."*");
	}
}

if (isset($_GET['execute']))
{
	if (isset($_GET['cmd']))
	{	
		$time = time(); $cmd = stripslashes(base64_decode($_GET['cmd']));
		$full_cmd = "(".$cmd.") &> ".$directory."includes/log/output_".$time.".log";
		
		shell_exec("echo \"#!/bin/sh\n".$full_cmd." &\" > ".$directory."custom.sh && chmod +x ".$directory."custom.sh &");
		exec("echo ".$directory."custom.sh | at now");
	}
}

if (isset($_GET['cancel']))
{
	exec("killall custom.sh &");	
}

if (isset($_GET['background_refresh']))
{
	if ($_GET['background_refresh'] == "start")
	{
		$full_cmd = "airodump-ng --write $dumpPath $monitorInterface &> /dev/null &";
		
		shell_exec("rm -rf ".$dumpPath."-01*");
		shell_exec("killall airodump-ng 2> /dev/null");

		shell_exec("echo \"#!/bin/sh\n".$full_cmd."\" > ".$directory."includes/refresh.sh && chmod +x ".$directory."includes/refresh.sh &");
		exec("echo ".$directory."includes/refresh.sh | at now");
	}
	else if ($_GET['background_refresh'] == "stop")
	{
		exec("killall airodump-ng 2> /dev/null");
		shell_exec("rm -rf ".$dumpPath."-01*");
		shell_exec("killall airodump-ng 2> /dev/null");
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		$log_date = date ("F d Y H:i:s", filemtime($directory."includes/log/".$_GET['file']));
		echo "Custom script ".$_GET['file']." [".$log_date."]\n";
		echo file_get_contents($directory."includes/log/".$_GET['file']);
	}
}

?>