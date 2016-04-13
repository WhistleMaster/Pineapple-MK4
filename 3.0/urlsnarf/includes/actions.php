<?php

require("/pineapple/components/infusions/urlsnarf/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['urlsnarf']))
{
	if($is_urlsnarf_installed)
	{
		if (isset($_GET['start']))
		{
		
			if (isset($_GET['int'])) $interface_run = $_GET['int']; else $interface_run = 'br-lan';
		
			exec("echo \"".$interface_run."\" > ".$directory."includes/infusion.run");
		
			$time = time();
			$full_cmd = "urlsnarf -i ".$interface_run." > ".$directory."includes/log/output_".$time.".log";

			shell_exec("echo \"#!/bin/sh\n".$full_cmd."\" > ".$directory."includes/urlsnarf.sh && chmod +x ".$directory."includes/urlsnarf.sh &");
			exec("echo ".$directory."includes/urlsnarf.sh | at now");
		}
		if (isset($_GET['stop']))
		{
			exec("kill `ps -ax | grep urlsnarf | grep -v -e grep | grep -v -e php | awk {'print $1'}`");
		
			exec("echo \"\" > ".$directory."includes/infusion.run");
		}
	}
	else
	{
		echo "urlsnarf is not installed...";
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
		{
			$log_date = date ("F d Y H:i:s", filemtime($directory."includes/log/".$_GET['file']));
			echo "urlsnarf ".$_GET['file']." [".$log_date."]\n";
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
				exec("sed -i '/urlsnarf\/includes\/autostart.sh/d' /etc/rc.local");
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