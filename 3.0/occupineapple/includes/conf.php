<?php

require("/pineapple/components/infusions/occupineapple/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['get_conf']))
{	
	echo "<form id='occupineapple_form_conf'>";
	echo "<input class='occupineapple' type='hidden' name='set_conf'/>";
	echo '<table id="occupineapple" class="grid">';
	echo "<tr><td>Speed (e.g. 20)</td>";
	echo '<td><input class="occupineapple" type="text" id="speed" name="speed" value="'.$speed_conf.'" size="5"> (Leave empty for default)</td></tr>';
	echo "<tr><td>Channel (e.g. 10)</td>";
	echo '<td><input class="occupineapple" type="text" id="channel" name="channel" value="'.$channel_conf.'" size="5"> (Leave empty for default)</td></tr>';
	echo "<tr><td>Options</td>";
	echo '<td><input class="occupineapple" type="text" id="options" name="options" value="'.$options_conf.'" size="15"></td></tr>';
	echo '</table>';
	echo "</form>";
	
	echo "<pre>
      -d
         Show station as Ad-Hoc
      -w
         Set WEP bit (Generates encrypted networks)
      -g
         Show station as 54 Mbit
      -t
         Show station using WPA TKIP encryption
      -a
         Show station using WPA AES encryption
      -m
         Use valid accesspoint MAC from OUI database
      -h
         Hop to channel where AP is spoofed
         This makes the test more effective against some devices/drivers
         But it reduces packet rate due to channel hopping.</pre>";
}

if (isset($_POST['set_conf']))
{
	$filename = $directory."includes/infusion.conf";
		
	$new_speed = $_POST['speed'];
	$new_channel = $_POST['channel'];
	$new_options = $_POST['options'];

	$newdata = "speed=".$new_speed."\n"."channel=".$new_channel."\n"."interface=".$interface_conf."\n"."monitor=".$monitor_conf."\n"."list=".$list_conf."\n"."options=".$new_options;
	$newdata = ereg_replace(13,  "", $newdata);
	$fw = fopen($filename, 'w');
	$fb = fwrite($fw,stripslashes($newdata));
	fclose($fw);
	
	echo '<font color="lime"><strong>saved</strong></font>';
}

?>