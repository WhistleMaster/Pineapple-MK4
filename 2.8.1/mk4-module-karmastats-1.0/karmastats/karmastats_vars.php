<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "Karma Stats";
$module_path = exec("pwd")."/";
$module_version = "1.0";

$installed = file_exists($module_path."installed") ? 1 : 0;
$install_error = file_exists($module_path."install_error") ? 1 : 0;

$pineNumbers = exec("pineNumbers");
$pineMAC = exec("ifconfig | grep wlan0 | awk '{print $5}'");
$pineDateTime = date('Y-m-d H:i:s');

$is_watchdog_installed = exec("cat /etc/crontabs/root | grep karmastats_watchdog.sh") != "" ? 1 : 0;
$watchdog_update = exec("logread | grep karmastats_watchdog.sh | tail -1 | awk '{print $1\" \"$2\" \"$3;}'");

$is_daemon_running = exec("ps auxww | grep {karmastats_repo} | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_daemon_onboot = exec("cat /etc/rc.local | grep karmastats_report.sh") != "" ? 1 : 0;

$karmastats_conf_path = $module_path."karmastats.conf";

$karmastats_conf = parse_ini_file($karmastats_conf_path);
$server = $karmastats_conf['server'];
$pineName = $karmastats_conf['pineName'];
$watchdog_time = $karmastats_conf['watchdogFreq'];
$pineLatitude = $karmastats_conf['pineLatitude'];
$pineLongitude = $karmastats_conf['pineLongitude'];
$token = $karmastats_conf['token'];
$hashMAC = $karmastats_conf['hashMAC'];

if($token == "")
{
	$token = md5(uniqid(rand(), true));
	
	$newdata = "server=".$server."\n"."pineName=".$pineName."\n"."watchdogFreq=".$watchdog_time."\n"."pineLatitude=".$pineLatitude."\n"."pineLongitude=".$pineLongitude."\n"."token=".$token."\n"."hashMAC=".$hashMAC;
	$newdata = ereg_replace(13,  "", $newdata);
	$fw = fopen($karmastats_conf_path, 'w');
	$fb = fwrite($fw,stripslashes($newdata));
	fclose($fw);
}

$watchdog_task = $module_path."karmastats_watchdog.sh";

function remoteFileExists($url)
{
	if (@fopen($url,"r"))  
	    return TRUE;  
	else   
	    return FALSE;
}    

?>