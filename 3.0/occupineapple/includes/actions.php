<?php

require("/pineapple/components/infusions/occupineapple/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['int'])) $interface = $_GET['int'];
if (isset($_GET['mon'])) $monitorInterface = $_GET['mon'];
if (isset($_GET['list'])) $list = $_GET['list'];

if (isset($_GET['mdk3']))
{
	if (isset($_GET['start']))
	{
		exec("echo \"".$list."\" > ".$directory."includes/infusion.run");
		
		$filename = $directory."includes/infusion.conf";

		$newdata = "speed=".$speed_conf."\n"."channel=".$channel_conf."\n"."interface=".$interface."\n"."monitor=".$monitorInterface."\n"."list=".$list."\n"."options=".$options_conf;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
		
		exec("echo ".$directory."includes/start_mdk3.sh | at now");
	}
	if (isset($_GET['stop']))
	{
		exec("echo \"\" > ".$directory."includes/infusion.run");
		exec("echo ".$directory."includes/stop_mdk3.sh | at now");
	}
}

if (isset($_GET['monitor']))
{
	if (isset($_GET['start'])) 
		exec("airmon-ng start ".$interface." &");	
	if (isset($_GET['stop']))
		exec("airmon-ng stop ".$monitorInterface." &");
	if (isset($_GET['name']))
		exec("iwconfig 2> /dev/null | grep \"Mode:Monitor\" | awk '{print $1}' | head -1");	

	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_GET['interface']))
{
	if (isset($_GET['start'])) 
		exec("ifconfig ".$interface." up &");
	if (isset($_GET['stop']))
		exec("ifconfig ".$interface." down &");
	if (isset($_GET['name']))
		exec("iwconfig 2> /dev/null | awk '{print $1}' | head -1");
	
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

if (isset($_GET['boot']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'enable':
				exec("sed -i '/exit 0/d' /etc/rc.local"); 
				exec("echo ".$directory."includes/start_mdk3.sh >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable': 
				exec("sed -i '/start_mdk3.sh/d' /etc/rc.local");
			break;
		}
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
				exec("opkg update && opkg install mdk3 --dest usb"); 
			break;
			
			case 'internal': 
				exec("opkg update && opkg install mdk3"); 
			break;
		}
	}
}

?>