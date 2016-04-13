<?php

require("/pineapple/components/infusions/dnsspoof/handler.php");

global $directory, $rel_dir;

require($directory."includes/vars.php");

if (isset($_GET['dnsspoof']))
{
	if (isset($_GET['start']))
	{		
		$time = time();
		$full_cmd = "dnsspoof -i br-lan -f ".$hosts_path." > /dev/null 2> ".$directory."includes/log/output_".$time.".log";

		shell_exec("echo \"#!/bin/sh\n".$full_cmd."\" > ".$directory."includes/dnsspoof.sh && chmod +x ".$directory."includes/dnsspoof.sh &");
		exec("echo ".$directory."includes/dnsspoof.sh | at now");
	}
	if (isset($_GET['stop']))
	{
		exec("kill `ps -ax | grep dnsspoof | grep -v -e grep | grep -v -e php | awk {'print $1'}`");
	}
}

if (isset($_GET['load']))
{
	if (isset($_GET['file']))
	{
		$log_date = date ("F d Y H:i:s", filemtime($directory."includes/log/".$_GET['file']));
		echo "dnsspoof ".$_GET['file']." [".$log_date."]\n";

		exec("cat ".$directory."includes/log/".$_GET['file']);
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		if (isset($_GET['log']))
			exec("rm -rf ".$directory."includes/log/".$_GET['file']."*");
	}
}

if (isset($_GET['fake']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'install':
				exec("cp ".$directory."includes/fake/ncsi.txt /www/ && mkdir -p /www/library/test/ && cp ".$directory."includes/fake/success.html /www/library/test/"); 
			break;
			
			case 'uninstall':
				exec("rm -rf /www/ncsi.txt && rm -rf /www/library "); 
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
				exec("sed -i '/dnsspoof\/includes\/autostart.sh/d' /etc/rc.local");
			break;
		}
	}	
}

?>