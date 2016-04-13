<?php

require("karmastats_vars.php");

if (isset($_GET['daemon']))
{
	if (isset($_GET['start']))
	{		
		$cmd = "echo ".$module_path."karmastats_report.sh | at now";
	}
	if (isset($_GET['stop']))
	{
		$cmd = "kill `ps -ax | grep {karmastats_repo} | grep -v -e grep | grep -v -e php | awk {'print $1'}`";
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
				exec("echo ".$module_path."karmastats_report.sh & >> /etc/rc.local");
				exec("echo exit 0 >> /etc/rc.local");
			break;
			
			case 'disable': 
				exec("sed -i '/karmastats_report.sh/d' /etc/rc.local");
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
			case 'enable': $cmd = "echo \"\n".$watchdog_time." ".$watchdog_task."\" >> /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"; break;
			case 'disable': $cmd = "sed -i '/karmastats_watchdog.sh/d' /etc/crontabs/root && sed -i '/^$/d' /etc/crontabs/root && /etc/init.d/cron restart"; break;
		}
	}
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
	exec("echo \"<?php echo 'working'; ?>\" > ".$module_path."status.php");
	$cmd = "echo \"sh ".$module_path."install.sh\" | at now";
}

if($cmd != "")
{
	$output = shell_exec($cmd);
	echo trim($output);	
}

?>