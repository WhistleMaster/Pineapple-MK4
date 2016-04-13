<?php

require("karmastats_vars.php");

if(isset($_GET['log']))
{
	if(file_exists($module_path."log")) 
	{
		echo file_get_contents($module_path."log");
	}
	else
	{
		echo "No log...";
	}
}

if(isset($_GET['clean']))
{
	if(file_exists($module_path."log")) 
	{
		exec("rm -rf ".$module_path."log");
	}
}

?>