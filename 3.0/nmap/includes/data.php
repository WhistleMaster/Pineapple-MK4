<?php

require("/pineapple/components/infusions/nmap/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['history']))
{
	$scans_list = array_reverse(glob($directory."includes/scans/*"));

	if(count($scans_list) == 0)
		echo "<em>No scan history...</em>";
	
	for($i=0;$i<count($scans_list);$i++)
	{
		if(basename($scans_list[$i]) != "tmp")
		{
			$info = explode("_", basename($scans_list[$i]));
			echo date('Y-m-d H-i-s', $info[1])." [";
			echo "<a href=\"javascript:nmap_load_file('".basename($scans_list[$i])."');\">view</a> | ";
			echo "<a href=\"javascript:javascript:location.href='".$rel_dir."includes/scans/".basename($scans_list[$i])."'\">download</a> | ";
			echo "<a href=\"javascript:nmap_delete_file('".basename($scans_list[$i])."');\">delete</a>]<br />";
		}
	}
}

if (isset($_GET['control']))
{
	if($is_nmap_running)
	{
		echo '<a id="scan_small" href="javascript:nmap_scan_toggle(\'cancel\');"><font color="red"><strong>Cancel</strong></font></a>';
		echo '<script type="text/javascript" charset="utf-8">$(document).ready( function () { nmap_refresh_output(); });</script>';
	}
	else
	{
		echo '<a id="scan_small" href="javascript:nmap_scan_toggle(\'scan\');"><font color="lime"><strong>Scan</strong></font></a>';
		echo '<script type="text/javascript" charset="utf-8">$(document).ready( function () { clearInterval(nmap_auto_refresh); nmap_refresh_output(); nmap_refresh_history(); });</script>';
	}
}

if (isset($_GET['control_small']))
{
	if($is_nmap_running)
	{
		echo '<a id="scan" href="javascript:nmap_scan_toggle_small(\'cancel\');"><font color="red"><strong>Cancel</strong></font></a>';
		echo '<script type="text/javascript" charset="utf-8">$(document).ready( function () { nmap_refresh_output_small(); });</script>';
	}
	else
	{
		echo '<a id="scan" href="javascript:nmap_scan_toggle_small(\'scan\');"><font color="lime"><strong>Scan</strong></font></a>';
		echo '<script type="text/javascript" charset="utf-8">$(document).ready( function () { clearInterval(nmap_auto_refresh); nmap_refresh_output_small(); });</script>';
	}
}

if (isset($_GET['lastscan']))
{
	if(file_exists($directory."includes/scans/tmp"))
	{	
		echo file_get_contents($directory."includes/scans/tmp");
	}
	else
	{
		$path = $directory."includes/scans";

		$latest_ctime = 0;
		$latest_filename = '';    

		$d = dir($path);
		while (false !== ($entry = $d->read())) {
		  $filepath = "{$path}/{$entry}";
		  if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
		      $latest_ctime = filectime($filepath);
		      $latest_filename = $entry;
		    }
		}
	
		echo file_get_contents($directory."includes/scans/".$latest_filename);
	}
}

?>