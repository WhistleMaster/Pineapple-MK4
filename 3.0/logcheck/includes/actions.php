<?php

require("/pineapple/components/infusions/logcheck/handler.php");

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
				exec("sed -i '/logcheck_report.sh/d' /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"); 
			break;
		}
	}
}

if (isset($_GET['logcheck']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'start':
				$full_cmd = "logread -f >> ".$directory."includes/events";
		
				shell_exec("echo \"#!/bin/sh\n".$full_cmd." &\" > ".$directory."includes/logcheck.sh && chmod +x ".$directory."includes/logcheck.sh &");
				exec("echo ".$directory."includes/logcheck.sh | at now");
			break;
			
			case 'stop':
				exec("killall logread");
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
				exec("echo ".$directory."includes/logcheck.sh >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable':
				exec("sed -i '/logcheck.sh/d' /etc/rc.local");
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
				exec("opkg update && opkg install ssmtp --dest usb"); 
			break;
			
			case 'internal': 
				exec("opkg update && opkg install ssmtp"); 
			break;
		}
	}
}

if (isset($_GET['test_email']))
{
	$body = "To: ".$To."\n";
	$body .= "From: ".$From."\n";
	$body .= "Subject: ".$Subject."\n";
	$body .= "\n\n";
	$body .= "[Test]\n";
	
	exec("echo -e '".$body."' > ".$directory."includes/mail_test.tmp");
	
	exec("ssmtp -t < ".$directory."includes/mail_test.tmp");
	exec("rm -rf ".$directory."includes/mail_test.tmp");
	
	echo '<font color="lime"><strong>test mail sent</strong></font>';
}

?>