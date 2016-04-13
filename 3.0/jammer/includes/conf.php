<?php

require("/pineapple/components/infusions/jammer/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_POST['set_conf']))
{	
	if($_POST['set_conf'] == "whitelist")
	{
		$filename = $whitelist_path;
		$newdata = $_POST['newdata'];

		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
	}
	
	if($_POST['set_conf'] == "blacklist")
	{
		$filename = $blacklist_path;
		$newdata = $_POST['newdata'];

		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
	}
	
	if($_POST['set_conf'] == "settings")
	{
		$filename = $settings_path;
		
		$new_packet = $_POST['packet'];
		$new_sleep = $_POST['sleep'];

		$newdata = "packet=".$new_packet."\n"."sleep=".$new_sleep."\n"."interface=".$interface_conf."\n"."monitor=".$monitor_conf;
		$newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w');
		$fb = fwrite($fw,stripslashes($newdata));
		fclose($fw);
	}
	
	echo '<font color="lime"><strong>saved</strong></font>';
}

if (isset($_GET['get_conf']))
{	
	echo "<form id='jammer_form_conf'>";
	echo "<input class='jammer' type='hidden' name='set_conf' value='settings'/>";
	echo '<table id="jammer" class="grid">';
	echo "<tr><td>Number of deauths to send</td>";
	echo '<td><input class="jammer" type="text" id="packet" name="packet" value="'.$packet_conf.'" size="5"> (Leave empty for default. 0 means send them continuously)</td></tr>';
	echo "<tr><td>Sleeping time in seconds</td>";
	echo '<td><input class="jammer" type="text" id="sleep" name="sleep" value="'.$sleep_conf.'" size="5"> (Leave empty for default)</td></tr>';
	echo '</table>';
	echo "</form>";
}

?>