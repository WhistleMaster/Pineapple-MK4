<?php

require("/pineapple/components/infusions/pineapplestats/handler.php");
require("/pineapple/components/infusions/pineapplestats/functions.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['daemon']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'start':
				exec("echo ".$directory."includes/pineapplestats_report.sh | at now");
			break;
			
			case 'stop': 
				exec("kill `ps -ax | grep {pineapplestats_} | grep -v -e grep | grep -v -e php | awk {'print $1'}`");
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
				exec("echo \"".$directory."includes/pineapplestats_report.sh &\" >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable': 
				exec("sed -i '/pineapplestats_report.sh/d' /etc/rc.local");
			break;
		}
	}	
}

if (isset($_GET['autossh']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'start':
				exec('/etc/init.d/autossh start');
			break;
			
			case 'stop': 
				exec('/etc/init.d/autossh stop');
			break;
		}
	}	
}

if (isset($_GET['watchdog']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'enable':
				exec("echo \"\n".$watchdog_time." ".$watchdog_task."\" >> /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"); 
			break;
			
			case 'disable':
				exec("sed -i '/pineapplestats_watchdog.sh/d' /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"); 
			break;
		}
	}
}

if (isset($_GET['reboot']))
{
	if (isset($_GET['action']))
	{
		$action = $_GET['action'];
		
		switch($action)
		{
			case 'enable':
				exec("echo \"\n".$reboot_time." ".$reboot_task."\" >> /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"); 
			break;
			
			case 'disable':
				exec("sed -i '/pineapplestats_reboot.sh/d' /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"); 
			break;
		}
	}
}

if (isset($_GET['addToken']))
{
	exec("echo \"sh ".$directory."includes/pineapplestats_token.sh\" | at now");
	
	echo '<font color="lime"><strong>done</strong></font>';
}

if(isset($_GET['test_Remote']))
{
	if(isset($_GET['server'])) $remote_server = $_GET['server'];
	
	if (remoteFileExists($remote_server.'ip.php')) 
	{
	    if(remoteFileExists($remote_server.'upload.php'))
		{
		    if(remoteFileExists($remote_server.'watchdog.php')) 
			{ 
				echo '<font color="lime"><strong>OK</strong></font>'; 
			}
			else
			{
				echo '<font color="red"><strong>file watchdog.php not found!</strong></font>'; 
			}
		}
		else
		{
			echo '<font color="red"><strong>file upload.php not found!</strong></font>'; 
		}
	}
	else 
	{
		echo '<font color="red"><strong>file ip.php not found!</strong></font>'; 
	}
}

if (isset($_GET['install_dep']))
{
	exec("echo \"<?php echo 'working'; ?>\" > ".$directory."includes/status.php");
	exec("echo \"sh ".$directory."includes/install.sh\" | at now");
}
?>