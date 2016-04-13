<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

global $directory, $rel_dir;

$interfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"wlan*\" | grep -v \"mon*\" | awk '{print $1}'"))));
$monitorInterfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"mon*\" | awk '{print $1}'"))));

$is_mdk3_installed = exec("which mdk3") != "" ? 1 : 0;
$is_mdk3_running = exec("ps auxww | grep mdk3 | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;

$is_mdk3_onboot = exec("cat /etc/rc.local | grep start_mdk3.sh") != "" ? 1 : 0;

$occupineapple_run = trim(file_get_contents($directory."includes/infusion.run"));

$occupineapple_conf = parse_ini_file($directory."includes/infusion.conf");
$speed_conf = $occupineapple_conf['speed'];
$channel_conf = $occupineapple_conf['channel'];
$interface_conf = $occupineapple_conf['interface'];
$monitor_conf = $occupineapple_conf['monitor'];
$list_conf = $occupineapple_conf['list'];
$options_conf = $occupineapple_conf['options'];

$is_executable = exec("if [ -x ".$directory."includes/start_mdk3.sh ]; then echo '1'; fi") != "" ? 1 : 0;
if(!$is_executable) exec("chmod +x ".$directory."includes/start_mdk3.sh");

$is_executable = exec("if [ -x ".$directory."includes/stop_mdk3.sh ]; then echo '1'; fi") != "" ? 1 : 0;
if(!$is_executable) exec("chmod +x ".$directory."includes/stop_mdk3.sh");

?>
