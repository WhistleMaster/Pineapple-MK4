<?php

require("/pineapple/components/infusions/sitesurvey/handler.php");
require("/pineapple/components/infusions/sitesurvey/functions.php");

global $directory, $rel_dir;

require($directory."includes/vars.php");

if (isset($_GET['int'])) $interface = $_GET['int'];
if (isset($_GET['mon'])) $monitorInterface = $_GET['mon'];

if (isset($_GET['history']))
{
	$log_list = array_reverse(glob($directory."includes/log/*"));

	if(count($log_list) == 0)
		echo "<em>No log history...</em>";
	
	for($i=0;$i<count($log_list);$i++)
	{
		if(basename($log_list[$i]) != "tmp")
		{
			$info = explode("_", basename($log_list[$i]));
			echo date('Y-m-d H-i-s', $info[1])." [";
			echo "<a href=\"javascript:sitesurvey_load_file('".basename($log_list[$i])."');\">view</a> | ";
			echo "<a href=\"javascript:location.href='".$rel_dir."includes/log/".basename($log_list[$i])."'\">download</a> | ";
			echo "<a href=\"javascript:sitesurvey_delete_file('log','".basename($log_list[$i])."');\">delete</a>]<br />";
		}
	}
}

if (isset($_GET['captures']))
{
	$captures_list = array_reverse(glob($directory."includes/captures/*.cap"));

	if(count($captures_list) == 0)
		echo "<em>No capture history...</em>";

	for($i=0;$i<count($captures_list);$i++)
	{
		$info = explode("_", basename($captures_list[$i]));
		
		$BSSID = exec("awk -F, '/BSSID/ {i=1; next} i {print $1}' ".$directory."includes/captures/".basename($captures_list[$i],".cap").".csv | head -1");
		$ESSID = exec("awk -F, '/BSSID/ {i=1; next} i {print $14}' ".$directory."includes/captures/".basename($captures_list[$i],".cap").".csv | head -1");
		$IVS = exec("awk -F, '/BSSID/ {i=1; next} i {print $11}' ".$directory."includes/captures/".basename($captures_list[$i],".cap").".csv | head -1");
		
		echo date('Y-m-d H-i-s', $info[1])." [".$BSSID." - ".$ESSID."] #IVS ".$IVS." ";
		echo "| ".dataSize($directory."includes/captures/".basename($captures_list[$i],".cap").".*")." ";
		echo "| <a href=\"javascript:sitesurvey_delete_file('cap','".basename($captures_list[$i],".cap")."');\">delete</a><br/>";

		for($j=0;$j<count($output_types);$j++)
		{
			$file = basename($captures_list[$i],".cap").".".$output_types[$j];
			
			$tags = array("FILENAME" => $directory."includes/captures/".$file);
			$custom_command = addslashes(replace_tags($tags, $custom_commands[1]));
			
			echo $output_types[$j]." ";
			echo "[<a href=\"javascript:location.href='".$rel_dir."includes/captures/".$file."'\">load</a> - ";
			echo "<a href=\"javascript:execute_custom_script('".base64_encode($custom_command)."');\">exec</a>] ";
		}
		echo "<br /><br />";
	}
}

if (isset($_GET['deauthtarget']))
{
	if(isset($_GET['deauthtargetClient']) && $_GET['deauthtargetClient'] != "")
	{
		exec("aireplay-ng -0 ".$_GET['deauthtimes']." --ignore-negative-one -D -c $_GET[deauthtargetClient] -a $_GET[deauthtarget] ".$monitorInterface." &");
	}
	else
	{
		exec("aireplay-ng -0 ".$_GET['deauthtimes']." --ignore-negative-one -D -a $_GET[deauthtarget] ".$monitorInterface." &");
	}
}

if (isset($_GET['ap']))
{
	$time = time();
	$full_cmd = "airodump-ng -c ".$_GET['channel']." --bssid ".$_GET['ap']." -w ".$directory."includes/captures/capture_".$time." ".$monitorInterface." &> /dev/null";

	shell_exec("echo \"#!/bin/sh\necho \"".$_GET['ap']."\" > ".$directory."includes/captures/lock\n".$full_cmd." &\" > ".$directory."includes/captures.sh && chmod +x ".$directory."includes/captures.sh &");
	exec("echo ".$directory."includes/captures.sh | at now");
}

if (isset($_GET['cancel']))
{
	exec("killall airodump-ng && rm -rf ".$directory."includes/captures/lock &");
}

?>