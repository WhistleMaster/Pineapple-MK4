<?php

$cmd="";
if (isset($_POST['update']))
{
	$cmd = "opkg update > /dev/null && opkg list | wc -l"; 
	$output = shell_exec($cmd);
	echo trim($output);	
}

if (isset($_POST['action']) && isset($_POST['package']))
{	
	$action = $_POST['action'];
	$package = $_POST['package'];
	
	if($action == "info")
		$cmd = "opkg info ".$package;
	else if($action == "remove")
		$cmd = "opkg remove ".$package;
	else if($action == "install_usb")
		$cmd = "opkg install --dest usb ".$package;
	else if($action == "install")
		$cmd = "opkg install ".$package;
	else if($action == "force")
		$cmd = "opkg --force-reinstall install ".$package;

	if($cmd != "")
	{
		echo "<pre>";
		$output = shell_exec($cmd);
		echo trim($output);	
		echo "</pre>";
	}
}

if (isset($_POST['show_actions_popup']) && isset($_POST['package']))
{
	$package = $_POST['package'];
	$installed = exec("opkg list-installed | grep ".$package) != "" ? 1 : 0;
	
	echo "Status: ";
	
	if($installed) echo "<font color=\"lime\"><strong>installed</strong></font>";
	else echo "<font color=\"red\"><strong>not installed</strong></font>";
	
	echo "<br/>";
	
	echo "Actions: ";
			
	if($installed)
		echo '<a href="javascript:opkgmanager_perf_action(\''.$package.'\', \'remove\');">Remove</a> | <a href="javascript:opkgmanager_perf_action(\''.$package.'\', \'force\');">Re-install</a>';
	else
		echo 'Install [<a href="javascript:opkgmanager_perf_action(\''.$package.'\', \'install\');">Internal</a>] [<a href="javascript:opkgmanager_perf_action(\''.$package.'\', \'install_usb\');">USB</a>]';	
	
	echo "<br/>";
	
	$cmd = "opkg info ".$package;
	$output = shell_exec($cmd);
	echo "<pre>";
	echo trim($output);
	echo "</pre>";
}

?>