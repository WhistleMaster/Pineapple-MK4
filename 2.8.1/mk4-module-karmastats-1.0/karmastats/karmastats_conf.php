<?php

require("karmastats_vars.php");

if (isset($_GET['get_conf']))
{	
	echo "<form id='form_conf'>";
	echo "<input type='hidden' name='set_conf'/>";
	echo '<table class="grid">';
	echo "<tr><td>Remote Server</td>";
	echo '<td><input placeholder="e.g. https://www.remoteserver.com/karmastats/"  type="text" id="server" name="server" value="'.$server.'" size="25"> [<a href="javascript:testRemote($(\'#server\').val())">Test</a>] <span id="testRemote"></span></td></tr>';
	echo "<tr><td>Pineapple Name</td>";
	echo '<td><input type="text" id="pineName" name="pineName" value="'.$pineName.'" size="25"></td></tr>';
	echo "<tr><td>Pineapple Latitude</td>";
	echo '<td><input type="text" id="pineLatitude" name="pineLatitude" value="'.$pineLatitude.'" size="25"> [<a href="javascript:getPosition()">Get Position</a>]</td></tr>';
	echo "<tr><td>Pineapple Longitude</td>";
	echo '<td><input type="text" id="pineLongitude" name="pineLongitude" value="'.$pineLongitude.'" size="25"></td></tr>';
	echo "<tr><td>Watchdog Frequency</td>";
	echo '<td><input type="text" id="watchdog_time" name="watchdog_time" value="'.$watchdog_time.'" size="25"></td></tr>';
	echo "<tr><td>Karma Stats Token</td>";
	echo '<td><input type="text" id="token" name="token" value="'.$token.'" size="25"></td></tr>';
	echo "<tr><td>Hash MAC Addresses</td>";
	if($hashMAC)
		echo '<td><input type="checkbox" checked="yes" name="hashMAC" value="hashMAC"/></td></tr>';
	else
		echo '<td><input type="checkbox" name="hashMAC" value="hashMAC"/></td></tr>';
	echo '</table>';
	echo "</form>";
	
	echo "<em><strong>Note:</strong> Cron and Watchdog have to be restarted after configuration changes.</em>";
}

if (isset($_POST['set_conf']))
{
	$new_server = $_POST['server'];
	$new_pineName = $_POST['pineName'];
	$new_watchdog_time = $_POST['watchdog_time'];
	$new_pineLatitude = $_POST['pineLatitude'];
	$new_pineLongitude = $_POST['pineLongitude'];
	
	if(isset($_POST['hashMAC'])) $new_hashMAC = 1; else $new_hashMAC = 0;

	$newdata = "server=".$new_server."\n"."pineName=".$new_pineName."\n"."watchdogFreq=".$new_watchdog_time."\n"."pineLatitude=".$new_pineLatitude."\n"."pineLongitude=".$new_pineLongitude."\n"."token=".$token."\n"."hashMAC=".$new_hashMAC;
	$newdata = ereg_replace(13,  "", $newdata);
	$fw = fopen($karmastats_conf_path, 'w');
	$fb = fwrite($fw,stripslashes($newdata));
	fclose($fw);
}

?>
