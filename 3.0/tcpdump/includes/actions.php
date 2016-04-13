<?php

require("/pineapple/components/infusions/tcpdump/handler.php");

global $directory, $rel_dir;

require($directory."includes/vars.php");

if (isset($_GET['scan']))
{
	if (isset($_GET['cmd']))
	{
		$time = time();
		$full_cmd = stripslashes($_GET['cmd']) . " -w ".$directory."includes/dumps/dump_".$time.".pcap 2> ".$directory."includes/dumps/capture.log";
		
		shell_exec("echo \"#!/bin/sh\n".$full_cmd." && echo -e \\\"int\ncmd=\\\" > ".$directory."includes/infusion.run \" > ".$directory."includes/tcpdump.sh && chmod +x ".$directory."includes/tcpdump.sh &");
		exec("echo ".$directory."includes/tcpdump.sh | at now");
		
		if (isset($_GET['int'])) $new_int_run = $_GET['int'];
		$new_cmd_run = $_GET['cmd'];
				
		$filename = $directory."includes/infusion.run";

		$newdata = "int=".$new_int_run."\n"."cmd=".$new_cmd_run;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
	}
}

if (isset($_GET['cancel']))
{
	exec("echo -e \"int=\ncmd=\" > ".$directory."includes/infusion.run");
	exec("killall tcpdump &");
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		echo file_get_contents($directory."includes/dumps/".$_GET['file']);
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		exec("rm -rf ".$directory."includes/dumps/".$_GET['file']);
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
				exec("opkg update && opkg install tcpdump --dest usb"); 
			break;
			
			case 'internal': 
				exec("opkg update && opkg install tcpdump"); 
			break;
		}
	}
}

?>