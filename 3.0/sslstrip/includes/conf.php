<?php

require("/pineapple/components/infusions/sslstrip/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['get_conf']))
{
	$configArray = explode("\n", trim(file_get_contents($directory."includes/infusion.conf")));
	
	echo "<form id='sslstrip_form_conf'>";
	echo "Command executed on selected capture [Variables: %%FILENAME%%]<br />";
	echo '<input class="sslstrip" type="text" id="command_File" name="commands[]" value="'.$configArray[0].'" size="115">';
	echo "</form>";
}

if (isset($_POST['set_conf']))
{
	if (isset($_POST['commands']))
	{
		$commands = stripslashes(base64_decode($_POST['commands']));
		
		$filename = $directory."includes/infusion.conf";
		
		$newdata = $commands;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w+');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
		
		echo '<font color="lime"><strong>saved</strong></font>';
	}
}
?>
