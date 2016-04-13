<?php

global $directory, $rel_dir;

$interfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"wlan*\" | grep -v \"mon*\" | awk '{print $1}'"))));
$monitorInterfaces = array_reverse(explode("\n", trim(shell_exec("iwconfig 2> /dev/null | grep \"mon*\" | awk '{print $1}'"))));

$is_airodump_running = exec("ps auxww | grep airodump-ng | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_capture_running = file_exists($directory."includes/captures/lock") != "" ? 1 : 0;
$is_custom_running = exec("ps auxww | grep custom.sh | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;

if(!$is_airodump_running && $is_capture_running) exec("rm -rf ".$directory."includes/captures/lock &");

$custom_commands = explode("\n", trim(file_get_contents($directory."includes/infusion.conf")));

$timeAP = 30;

$dumpPath="/tmp/mk";

$output_types = array("cap", "csv", "kismet.csv", "kismet.netxml");

?>