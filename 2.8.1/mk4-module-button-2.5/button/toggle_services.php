<?php

$op = $argv[1];

if (!$op) {
	return;
}

if($op == "-dnsspoof")
{
	$isdnsspoofup = exec("ps auxww | grep dnsspoof.sh | grep -v -e grep");
	if ($isdnsspoofup == "") 
	{
		exec ("echo '' > /pineapple/logs/dnsspoof.log");
		exec ("echo /pineapple/dnsspoof/dnsspoof.sh | at now");
		
		exec ("logger \"SCRIPT: Start DNS Spoof\"");
	} 
	else 
	{ 
		exec("killall dnsspoof");
		exec ("logger \"SCRIPT: Stop DNS Spoof\"");
	}
}
else if($op == "-karma")
{
	if ( exec("hostapd_cli -p /var/run/hostapd-phy0 karma_get_state | tail -1") == "ENABLED" )
		$iskarmaup = true;
		
	if (!$iskarmaup) 
	{		
		exec ("echo '' > /tmp/karma.log");
		exec ("echo /pineapple/karma/startkarma.sh | at now");
		
		exec ("logger \"SCRIPT: Start Karma\"");
	}
	else
	{
		exec ("echo '' > /tmp/karma.log");
		exec ("hostapd_cli -p /var/run/hostapd-phy0 karma_disable");
		
		exec ("logger \"SCRIPT: Stop Karma\"");
	}
}
else if($op == "-snarf")
{
	$isurlsnarfup = exec("ps auxww | grep urlsnarf.sh | grep -v -e grep");
	if ($isurlsnarfup == "") 
	{
		exec ("echo '' > /pineapple/logs/urlsnarf.log");
		exec ("echo /pineapple/urlsnarf/urlsnarf.sh | at now");
		exec ("echo /pineapple/urlsnarf/update-urlsnarf.sh | at now");
		
		exec ("logger \"SCRIPT: Start URL Snarf\"");
	}
	else
	{
		exec ("echo '' > /pineapple/logs/urlsnarf.log");
		exec ("kill `ps auxww | grep \"urlsnarf.sh\" | grep -v -e grep | awk '{print $1}'`");
		exec ("killall update-urlsnarf.sh");
		exec ("kill `ps auxww | grep \"urlsnarf -i br-lan\" | grep -v -e grep | awk '{print $1}'`");
		
		exec ("logger \"SCRIPT: Stop URL Snarf\"");
	}
}
else if($op == "-s")
{
	$isssh = exec("ps aux | grep [s]sh | grep -v -e ssh.php | grep -v grep");
	if($isssh == "")
	{
		exec ("echo /pineapple/ssh/ssh-connect.sh | at now");
		exec ("logger \"SCRIPT: Connect SSH\"");
	}
	else
	{
		exec ("kill `ps aux | grep -v -e ssh.php | awk '/[s]sh/{print $1}'`");
		exec ("logger \"SCRIPT: Disconnect SSH\"");	
	}
}

?>
