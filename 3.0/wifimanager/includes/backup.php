<?php

require("/pineapple/components/infusions/wifimanager/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['bck']))
{
	$backup_list = array_reverse(glob($directory."includes/backup/*"));

	if(count($backup_list) == 0)
		echo "<em>No backup history...</em>";

	for($i=0;$i<count($backup_list);$i++)
	{
		$info = explode("_", basename($backup_list[$i]));
		echo date('Y-m-d H-i-s', $info[1])." [";
		echo "<a href=\"javascript:wifimanager_view_bck('".basename($backup_list[$i])."');\">view</a> | ";
		echo "<a href=\"javascript:wifimanager_restore_bck('".basename($backup_list[$i])."');\">restore</a> | ";
		echo "<a href=\"javascript:wifimanager_delete_bck('".basename($backup_list[$i])."');\">delete</a>]<br />";
	}
}

if (isset($_GET['new']))
{
	exec("cp /etc/config/wireless ".$directory."includes/backup/wireless_".(time()).".bck");
	
	echo '<font color="lime"><strong>done</strong></font>';
}

if (isset($_GET['restore']))
{
	if (isset($_GET['file']))
	{
		exec("cp ".$directory."includes/backup/".$_GET['file']." /etc/config/wireless");
		
		echo '<font color="lime"><strong>done</strong></font>';
	}
}

if (isset($_GET['delete']))
{
	if (isset($_GET['file']))
	{
		exec("rm -rf ".$directory."includes/backup/".$_GET['file']."*");
	}
}

if (isset($_GET['view']))
{
	if (isset($_GET['file']))
	{
		$info = explode("_", basename($_GET['file']));
		
		echo "<b>Backup [".date('Y-m-d H-i-s', $info[1])."]<br/><br/>";
		
		echo "<pre>";
		echo file_get_contents($directory."includes/backup/".$_GET['file']);
		echo "</pre>";
	}
}

?>