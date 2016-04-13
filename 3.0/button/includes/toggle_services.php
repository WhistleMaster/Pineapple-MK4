<?php

$op = $argv[1];

if (!$op) {
	return;
}

if($op == "-dnsspoof")
{
	$isdnsspoofup = exec("ps -all | grep [d]nsspoof | grep -v php");
	if ($isdnsspoofup == "")
	{
		exec('echo "dnsspoof -i br-lan -f /etc/pineapple/spoofhost > /dev/null 2>/tmp/dnsspoof.log" | at now');
		exec ("logger \"SCRIPT: Start DNS Spoof\"");
	} 
	else 
	{ 
		exec('killall dnsspoof');
		exec ("logger \"SCRIPT: Stop DNS Spoof\"");
	}
}
else if($op == "-karma")
{
	if ( exec("hostapd_cli -p /var/run/hostapd-phy0 karma_get_state | tail -1") == "ENABLED" )
		$iskarmaup = true;
		
	if (!$iskarmaup) 
	{		
		exec("/etc/init.d/karma start");
		
		exec ("logger \"SCRIPT: Start Karma\"");
	}
	else
	{
		exec("/etc/init.d/karma stop");
		
		exec ("logger \"SCRIPT: Stop Karma\"");
	}
}
else if($op == "-s")
{
	exec('pgrep autossh', $pids);
	if(empty($pids)) return $isssh = false;
	else return $isssh = true;
	
	if($isssh)
	{
		exec('/etc/init.d/autossh start');
		exec ("logger \"SCRIPT: Start AutoSSH\"");
	}
	else
	{
		exec('/etc/init.d/autossh stop');
		exec ("logger \"SCRIPT: Stop AutoSSH\"");	
	}
}

?>
