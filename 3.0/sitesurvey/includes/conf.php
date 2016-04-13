<?php

require("/pineapple/components/infusions/sitesurvey/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['get_conf']))
{
	$configArray = explode("\n", trim(file_get_contents($directory."includes/infusion.conf")));
	
	echo "<form id='sitesurvey_form_conf'>";
	echo "<input class='sitesurvey' type='hidden' name='set_conf'/>";
	echo "Command executed on selected AP [Variables: %%SSID%%, %%BSSID%%, %%CHANNEL%%]<br />";
	echo '<input class="sitesurvey" type="text" id="command_AP" name="commands[]" value="'.$configArray[0].'" size="115"><br /><br />';
	echo "Command executed on selected capture [Variables: %%FILENAME%%]<br />";
	echo '<input class="sitesurvey" type="text" id="command_File" name="commands[]" value="'.$configArray[1].'" size="115">';
	echo "</form>";
}

if (isset($_POST['set_conf']))
{
	if (isset($_POST['commands']))
	{
		$configArray = $_POST['commands'];
		
		$commands = "";
		foreach($configArray as $conf)
		{
			$commands .= stripslashes($conf)."\n";
		}
		
		exec("echo \"".$commands."\" > ".$directory."includes/infusion.conf");
	}
}
?>