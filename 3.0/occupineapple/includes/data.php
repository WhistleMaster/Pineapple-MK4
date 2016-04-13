<?php

require("/pineapple/components/infusions/occupineapple/handler.php");

global $directory;

require($directory."includes/vars.php");

if(isset($_GET['log']))
{
	if($is_mdk3_running)
	{
		if(file_exists($directory."includes/log")) echo file_get_contents($directory."includes/log");
	}
	else
	{
		echo "Occupineapple is not running...";
	}
}

?>