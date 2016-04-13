<?php

require("/pineapple/components/infusions/pineapplestats/handler.php");

global $directory;

require($directory."includes/vars.php");

if(isset($_GET['log']))
{
	if(file_exists($directory."includes/log")) 
	{
		echo file_get_contents($directory."includes/log");
	}
	else
	{
		echo "No log...";
	}
}

if(isset($_GET['clean']))
{
	if(file_exists($directory."includes/log")) 
	{
		exec("rm -rf ".$directory."includes/log");
	}
}

?>