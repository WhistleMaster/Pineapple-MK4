<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$_wpsx_values = array("Do Nothing", "Toggle Karma", "Toggle DNS Spoof", "Toggle AutoSSH", "Reboot", "Run Custom Script 1", "Run Custom Script 2", "Run Custom Script 3", "Run Custom Script 4", "Run Default Script");
$_wpsx_time = array("0-2 Seconds", "4-6 Seconds", "8-10 Seconds", "12+ Seconds");
$_wpsx_min = array(0,4,8,12);
$_wpsx_max = array(2,6,10,30);

$karma_handler = "sh /pineapple/components/infusions/button/includes/scripts/flash.sh 1 && php /pineapple/components/infusions/button/includes/toggle_services.php -karma";
$dnsspoof_handler = "sh /pineapple/components/infusions/button/includes/scripts/flash.sh 1 && php /pineapple/components/infusions/button/includes/toggle_services.php -dnsspoof";
$ssh_handler = "sh /pineapple/components/infusions/button/includes/scripts/flash.sh 1 && php /pineapple/components/infusions/button/includes/toggle_services.php -s";
$reboot_handler = "sh /pineapple/components/infusions/button/includes/scripts/flash.sh 2 && reboot";
$script1_handler = "sh /pineapple/components/infusions/button/includes/scripts/flash.sh 3 && sh /pineapple/components/infusions/button/includes/scripts/wpsScript1.sh";
$script2_handler = "sh /pineapple/components/infusions/button/includes/scripts/flash.sh 4 && sh /pineapple/components/infusions/button/includes/scripts/wpsScript2.sh";
$script3_handler = "sh /pineapple/components/infusions/button/includes/scripts/flash.sh 5 && sh /pineapple/components/infusions/button/includes/scripts/wpsScript3.sh";
$script4_handler = "sh /pineapple/components/infusions/button/includes/scripts/flash.sh 6 && sh /pineapple/components/infusions/button/includes/scripts/wpsScript4.sh";
$default_handler = "sh /etc/pineapple/wpsScript.sh";
$no_handler = "logger No action";

for($i=0;$i<4;$i++)
{
	$is_executable = exec("if [ -x ".$directory."includes/scripts/wpsScript".($i+1).".sh ]; then echo '1'; fi") != "" ? 1 : 0;
	if(!$is_executable) exec("chmod +x ".$directory."includes/scripts/wpsScript".($i+1).".sh");
}

$is_executable = exec("if [ -x ".$directory."includes/scripts/flash.sh ]; then echo '1'; fi") != "" ? 1 : 0;
if(!$is_executable) exec("chmod +x ".$directory."includes/scripts/flash.sh");

?>