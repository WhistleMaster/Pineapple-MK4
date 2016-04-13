<?php

require("interceptor_vars.php");

if(isset($_GET['log']))
{
	if($is_interceptor_running)
	{
		if(file_exists($module_path."log")) echo file_get_contents($module_path."log");
	}
	else
	{
		echo "Interceptor is not running...";
	}
}

?>