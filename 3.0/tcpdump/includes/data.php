<?php

require("/pineapple/components/infusions/tcpdump/handler.php");
require("/pineapple/components/infusions/tcpdump/functions.php");

global $directory, $rel_dir;

require($directory."includes/vars.php");

if (isset($_GET['history']))
{
	$dumps_list = array_reverse(glob($directory."includes/dumps/*.pcap"));
	
	if(count($dumps_list) == 0)
		echo "<em>No dump history...</em>";
	
	for($i=0;$i<count($dumps_list);$i++)
	{
		if(basename($dumps_list[$i]) != "capture.log")
		{
			$info = explode("_", basename($dumps_list[$i]));
			echo date('Y-m-d H-i-s', $info[1])." - ";
			echo dataSize($directory."includes/dumps/".basename($dumps_list[$i]))." [";
			echo "<a href=\"javascript:javascript:location.href='".$rel_dir."includes/dumps/".basename($dumps_list[$i])."'\">download</a> | ";
			echo "<a href=\"javascript:tcpdump_delete_file('".basename($dumps_list[$i])."');\">delete</a>]<br />";
		}
	}
}

?>