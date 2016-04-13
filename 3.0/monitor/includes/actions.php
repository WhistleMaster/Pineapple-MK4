<?php

require("/pineapple/components/infusions/monitor/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['daemon'])) 
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'enable': 
				exec("echo \"\n".$cron_time." ".$cron_task."\" >> /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"); 
			break;
			
			case 'disable': 
				exec("sed -i '/graphs-vnstat.sh/d' /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"); 
			break;
		}
	}
}

if (isset($_GET['usb'])) 
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'enable': 
				exec("mkdir -p /usb/var/lib/ && mv /var/lib/vnstat /usb/var/lib/ && ln -s /usb/var/lib/vnstat /var/lib/vnstat &"); 
			break;
			
			case 'disable': 
				exec("rm -rf /var/lib/vnstat && mv /usb/var/lib/vnstat /var/lib/ &"); 
			break;
		}
	}
}

if (isset($_GET['reset']))
{
	exec("rm -rf /var/lib/vnstat/* && rm -rf ".$directory."includes/vnstat/* &");
	
	for($i=0;$i<count($interfaces);$i++)
	{
		if(!file_exists("/var/lib/vnstat/".$interfaces[$i]))
			exec("vnstat -u -i ".$interfaces[$i]);
	}
}

if (isset($_GET['force']))
{
	exec("echo ".$cron_task." | at now");
}

?>