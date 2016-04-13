<?php

$cmd="";
if (isset($_POST['update']))
{
	$cmd = "opkg update > /dev/null && opkg list | wc -l"; 
}

if (isset($_POST['cache_status']))
{
	if(file_exists("/tmp/opkg_list_all_html"))
	{
		$date_cache = date("M d Y", filemtime("/tmp/opkg_list_all_html"));
		echo 'yes ['.$date_cache.']';
	}
	else
	{	
		echo 'no';
	}
}

if (isset($_POST['update_cache']))
{
	shell_exec('rm -rf /tmp/opkg_list_all_html');
			
	$odd=0;
			
	shell_exec ("opkg list-installed > /tmp/opkg_list_installed");
	shell_exec("opkg list | awk '{ print $1\"|\"$3 }' > /tmp/opkg_list_all");
			
	$contents = file_get_contents("/tmp/opkg_list_installed");
			
	$file_handle = fopen("/tmp/opkg_list_all", "r"); $data = ""; $filename = "/tmp/opkg_list_all_html";
	while (!feof($file_handle))
	{
		$line = fgets($file_handle);
		if($line != "")
		{
			$line = explode("|", $line);

			$pattern = "/^.*$line[0].*\$/m";
			if(exec("cat /tmp/opkg_list_installed | grep ".$line[0]) != "") $installed = 0; else $installed = 1;

			if($odd % 2) echo '<tr class="odd">'; else echo '<tr class="even">';

			$data .= '<td>'.$line[0].'</td>';
			$data .= '<td>'.$line[1].'</td>';

			$data .= '<td>';
			if($installed)
				$data .= '<a href="javascript:perf_action(\''.$line[0].'\', \'install\');">Install</a> [<a href="javascript:perf_action(\''.$line[0].'\', \'install_usb\');">USB</a>] | <a href="javascript:perf_action(\''.$line[0].'\', \'info\');">Info</a>';
			else
				$data .= '<a href="javascript:perf_action(\''.$line[0].'\', \'remove\');">Remove</a> | <a href="javascript:perf_action(\''.$line[0].'\', \'info\');">Info</a> | <a href="javascript:perf_action(\''.$line[0].'\', \'force\');">Re-install</a>';
					
			$data .= '</td>';

			$data .= '</tr>';

			$odd += 1;
		}
	}
	fclose($file_handle);
			
	$fw = fopen($filename, 'w');
	$fb = fwrite($fw,stripslashes($data));
	fclose($fw);
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
}

if($cmd != "")
{
	$output = shell_exec($cmd);
	echo trim($output);	
}

?>