<?php

require("/pineapple/components/infusions/logcheck/handler.php");

global $directory;

require($directory."includes/vars.php");

if($is_logcheck_running)
{
	exec("grep -hv -e ^# ".$match_path." -e ^$ > ".$directory."includes/rules/match.tmp");
	exec("grep -hv -e ^# ".$ignore_path." -e ^$ > ".$directory."includes/rules/ignore.tmp");

	exec("cat ".$directory."includes/events | grep -Ef ".$directory."includes/rules/match.tmp | grep -vEf ".$directory."includes/rules/ignore.tmp" , $output);

	if(empty($output)) echo "No Filtered logs\n"; else echo "Filtered logs\n";
	foreach($output as $outputline) echo ("$outputline\n");
}
else
{
	echo "Logcheck is not running...";
}
?>