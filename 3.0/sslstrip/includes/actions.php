<?php

require("/pineapple/components/infusions/sslstrip/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['sslstrip']))
{
	if($is_sslstrip_installed)
	{
		if (isset($_GET['start']))
		{
			if (isset($_GET['verbose'])) $verbose = 1; else $verbose = 0;
		
			exec("iptables -t nat -A PREROUTING -p tcp --destination-port 80 -j REDIRECT --to-ports 10000");
			//exec("iptables -t nat -A PREROUTING -p tcp --destination-port 443 -j REDIRECT --to-ports 10000");
		
			$time = time();
		
			if($verbose)
				$full_cmd = "sslstrip -a -k -f -w ".$directory."includes/log/output_".$time.".log 2>&1";
			else
				$full_cmd = "sslstrip -k -f -w ".$directory."includes/log/output_".$time.".log 2>&1";

			shell_exec("echo \"#!/bin/sh\n".$full_cmd." &\" > ".$directory."includes/sslstrip.sh && chmod +x ".$directory."includes/sslstrip.sh &");
			exec("echo ".$directory."includes/sslstrip.sh | at now");
		}
	
		if (isset($_GET['stop']))
		{
			$rule_http_number = exec("iptables -t nat --line-numbers -n -L | grep 80 | grep 10000 | awk {'print $1'}") != "" ? 1 : 0;
			exec("iptables -t nat -D PREROUTING ".$rule_http_number);
			$rule_https_number = exec("iptables -t nat --line-numbers -n -L | grep 443 | grep 10000 | awk {'print $1'}") != "" ? 1 : 0;
			exec("iptables -t nat -D PREROUTING ".$rule_https_number);
			
			exec("kill `ps -ax | grep sslstrip | grep -v -e grep | grep -v -e php | awk {'print $1'}`");
		}
	}
	else
	{
		echo "sslstrip is not installed...";
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
		{
			$log_date = date ("F d Y H:i:s", filemtime($directory."includes/log/".$_GET['file']));
			echo "sslstrip ".$_GET['file']." [".$log_date."]\n";
			echo file_get_contents($directory."includes/log/".$_GET['file']);
		}
		else if (isset($_GET['custom']))
		{
			$log_date = date ("F d Y H:i:s", filemtime($directory."includes/custom/".$_GET['file']));
			echo "Custom script ".$_GET['file']." [".$log_date."]\n";
			echo file_get_contents($directory."includes/custom/".$_GET['file']);
		}
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
			exec("rm -rf ".$directory."includes/log/".$_GET['file']."*");
		else if (isset($_GET['custom']))
			exec("rm -rf ".$directory."includes/custom/".$_GET['file']."*");
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
				exec("opkg update && opkg install sslstrip --dest usb"); 
			break;
			
			case 'internal': 
				exec("opkg update && opkg install sslstrip"); 
			break;
		}
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
				exec("echo ".$directory."includes/autostart.sh >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable': 
				exec("sed -i '/sslstrip\/includes\/autostart.sh/d' /etc/rc.local");
			break;
		}
	}	
}

if (isset($_GET['execute']))
{
	if (isset($_GET['cmd']))
	{	
		$time = time(); $cmd = stripslashes(base64_decode($_GET['cmd']));
		$full_cmd = "(".$cmd.") &> ".$directory."includes/custom/output_".$time.".log &";
		
		$filename = $directory."includes/custom.sh";
		
		$newdata = "#!/bin/sh\n".$full_cmd;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w+');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
		
		shell_exec("chmod +x ".$directory."includes/custom.sh &");
		exec("echo ".$directory."includes/custom.sh | at now");
	}
}

?>