<?php

require("occupineapple_vars.php");

if(isset($_GET['log']))
{
	if($is_mdk3_running)
	{
		if(file_exists($module_path."log")) echo file_get_contents($module_path."log");
	}
	else
	{
		echo "Occupineapple is not running...";
	}
}

?>