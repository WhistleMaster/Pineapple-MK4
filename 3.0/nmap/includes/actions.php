<?php

require("/pineapple/components/infusions/nmap/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['scan']))
{
	if (isset($_GET['cmd']))
	{
		$time = time();
		$full_cmd = stripslashes($_GET['cmd']) . " -oN ".$directory."includes/scans/tmp 2>&1";
		
		shell_exec("echo \"#!/bin/sh\n".$full_cmd." && mv ".$directory."includes/scans/tmp ".$directory."includes/scans/scan_".$time." && echo -e \\\"target=\nprofile=\ncmd=\\\" > ".$directory."includes/infusion.run \" > ".$directory."includes/nmap.sh && chmod +x ".$directory."includes/nmap.sh &");
		exec("echo ".$directory."includes/nmap.sh | at now");
		
		if (isset($_GET['profile'])) $new_profile_run = $_GET['profile'];
		if (isset($_GET['target'])) $new_target_run = $_GET['target'];
		$new_cmd_run = $_GET['cmd'];
				
		$filename = $directory."includes/infusion.run";

		$newdata = "target=".$new_target_run."\n"."profile=".$new_profile_run."\n"."cmd=".$new_cmd_run;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
	}
}

if (isset($_GET['cancel']))
{
	exec("echo -e \"target=\nprofile=\ncmd=\" > ".$directory."includes/infusion.run");
	exec("killall nmap &");
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		echo file_get_contents($directory."includes/scans/".$_GET['file']);
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		exec("rm -rf ".$directory."includes/scans/".$_GET['file']);
	}
}

if (isset($_GET['install'])) 
{
	if (isset($_GET['where']))
	{
		$where = $_GET['where'];
		
		switch($where)
		{
			case 'usb': 
				exec("opkg update && opkg install nmap --dest usb"); 
			break;
			
			case 'internal': 
				exec("opkg update && opkg install nmap"); 
			break;
		}
	}
}

?>