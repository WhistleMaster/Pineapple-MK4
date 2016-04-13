<?php

require("/pineapple/components/infusions/pineapplestats/handler.php");

global $directory;

require($directory."includes/vars.php");

if (isset($_GET['get_conf']))
{	
	echo "<form id='pineapplestats_form_conf'>";
	echo "<input class='pineapplestats' type='hidden' name='set_conf'/>";
	echo '<table id="pineapplestats" class="grid">';
	echo "<tr><td><strong>Web UI</strong></td>";
	echo "<tr><td>Remote Server</td>";
	echo '<td><input class="pineapplestats" placeholder="e.g. https://www.remoteserver.com/pineapplestats/"  type="text" id="server" name="server" value="'.$server.'" size="25"> [<a href="javascript:pineapplestats_testRemote($(\'#server\').val())">Test</a>] <span id="testRemote"></span></td></tr>';
	echo "<tr><td>Pineapple Stats Token</td>";
	echo '<td><input class="pineapplestats" type="text" id="token" name="token" value="'.$token.'" size="25"> ';
	if($server != "")
		echo '[<a href="javascript:pineapplestats_addToken($(\'#token\').val())">Add Token</a>] <span id="addToken"></span>';
	else
		echo '[<a href="javascript:alert(\'Please set Remote Server and save configuration before adding token.\')">Add Token</a>] <span id="addToken"></span>';
	echo '</td></tr>';
	echo "<tr><td>Hash MAC Addresses</td>";
	if($hashMAC)
		echo '<td><input class="pineapplestats" type="checkbox" checked="yes" name="hashMAC" value="hashMAC"/></td></tr>';
	else
		echo '<td><input class="pineapplestats" type="checkbox" name="hashMAC" value="hashMAC"/></td></tr>';
	
	echo "<tr><td>&nbsp;</td>";
	echo "<tr><td><strong>Pineapple Information</strong></td>";
	
	echo "<tr><td>Pineapple Name</td>";
	echo '<td><input class="pineapplestats" type="text" id="pineName" name="pineName" value="'.$pineName.'" size="25"></td></tr>';
	echo "<tr><td>Pineapple Latitude</td>";
	echo '<td><input class="pineapplestats" type="text" id="pineLatitude" name="pineLatitude" value="'.$pineLatitude.'" size="25"> [<a href="javascript:pineapplestats_getPosition()">Get Position</a>]</td></tr>';
	echo "<tr><td>Pineapple Longitude</td>";
	echo '<td><input class="pineapplestats" type="text" id="pineLongitude" name="pineLongitude" value="'.$pineLongitude.'" size="25"></td></tr>';
	
	echo "<tr><td>&nbsp;</td>";
	echo "<tr><td><strong>Cron</strong></td>";
	
	//echo "<tr><td>Watchdog Frequency</td>";
	//echo '<td><input class="pineapplestats" type="text" id="watchdog_time" name="watchdog_time" value="'.$watchdog_time.'" size="25"></td></tr>';
	echo '<input class="pineapplestats" type="hidden" name="watchdog_time" value="'.$watchdog_time.'"/>';
	echo "<tr><td>Reboot Frequency</td>";
	echo '<td><input class="pineapplestats" type="text" id="reboot_time" name="reboot_time" value="'.$reboot_time.'" size="25"></td></tr>';
	echo "<tr><td>Connectivity check</td>";
	if($connectivityCheck)
		echo '<td><input class="pineapplestats" type="checkbox" checked="yes" name="connectivityCheck" value="connectivityCheck"/></td></tr>';
	else
		echo '<td><input class="pineapplestats" type="checkbox" name="connectivityCheck" value="connectivityCheck"/></td></tr>';
	
	echo "<tr><td>&nbsp;</td>";
	echo "<tr><td><strong>SSH</strong></td>";
	
	echo "<tr><td>Remote Host</td><td><input class='pineapplestats' name='host' type='text' placeholder='".$host."' value='".$host."'></td></tr>";
	echo "<tr><td>Port </td><td><input class='pineapplestats' name='port' type='text' placeholder='".$port."' value='".$port."'></td></tr>";
	echo "<tr><td>Listen Port</td><td><input class='pineapplestats' name='listen' type='text' placeholder='".$listen."' value='".$listen."'></td></tr>";
	
	echo '</table>';
	echo "</form>";
	echo "<br/>";
	echo "<em><strong>Note:</strong> Services have to be restarted after configuration changes.</em>";
}

if (isset($_POST['set_conf']))
{
	$new_server = $_POST['server'];
	$new_pineName = $_POST['pineName'];
	$new_watchdog_time = $_POST['watchdog_time'];
	$new_reboot_time = $_POST['reboot_time'];
	$new_pineLatitude = $_POST['pineLatitude'];
	$new_pineLongitude = $_POST['pineLongitude'];
	
	$host = $_POST['host'];
	$port = $_POST['port'];
	$listen = $_POST['listen'];
	
	exec('uci set autossh.@autossh[0].ssh="-i /etc/dropbear/id_rsa -N -T -R '.$port.':localhost:'.$listen.' '.$host.'"');
	exec('uci commit autossh');
	
	if(isset($_POST['hashMAC'])) $new_hashMAC = 1; else $new_hashMAC = 0;
	if(isset($_POST['connectivityCheck'])) $new_connectivityCheck = 1; else $new_connectivityCheck = 0;

	$newdata = "server=".$new_server."\n"."pineName=".$new_pineName."\n"."watchdogFreq=".$new_watchdog_time."\n"."rebootFreq=".$new_reboot_time."\n"."pineLatitude=".$new_pineLatitude."\n"."pineLongitude=".$new_pineLongitude."\n"."token=".$token."\n"."hashMAC=".$new_hashMAC."\n"."connectivityCheck=".$new_connectivityCheck;
	$newdata = ereg_replace(13,  "", $newdata);
	$fw = fopen($pineapplestats_conf_path, 'w');
	$fb = fwrite($fw,stripslashes($newdata));
	fclose($fw);
	
	echo '<font color="lime"><strong>saved</strong></font>';
}

if (isset($_POST['remote_public_key']))
{
	$filename = "/root/.ssh/known_hosts";
	$newdata = $_POST['newdata'];

	$newdata = ereg_replace(13,  "", $newdata);
	$fw = fopen($filename, 'w');
	$fb = fwrite($fw,stripslashes($newdata));
	fclose($fw);
	
	echo '<font color="lime"><strong>saved</strong></font>';
}

?>
