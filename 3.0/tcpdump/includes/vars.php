<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

global $directory, $rel_dir;

$is_tcpdump_installed = exec("which tcpdump") != "" ? 1 : 0;
$is_tcpdump_running = exec("ps auxww | grep tcpdump | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_dump_running = file_exists($directory."includes/dumps/tmp") != "" ? 1 : 0;

if(!$is_tcpdump_running && $is_dump_running) exec("rm -rf ".$directory."includes/dumps/tmp &");

$interfacesArray = explode("\n", trim(shell_exec("cat /proc/net/dev | tail -n +3 | cut -f1 -d: | sed 's/ //g'")));

$tcpdump_run = parse_ini_file($directory."includes/infusion.run");
$int_run = $tcpdump_run['int'];
$cmd_run = $tcpdump_run['cmd'];

$interfaces = array();
for($i=0;$i<count($interfacesArray);$i++)
{
	$interfaces[$interfacesArray[$i]] = "-i ".$interfacesArray[$i];
}

$resolve = array(
				"Don't resolve hostnames" => "-n",  
				"Don't resolve hostnames or port names" => "-nn"
				);

$options = array(
				"Don't print domain name qualification of host names" => "-N", 
				"Show the packet's contents in both hex and ASCII" => "-X",
				"Print absolute sequence numbers" => "-S",
				"Get the ethernet header as well" => "-e",
				"Show less protocol information" => "-q",
				"Monitor mode" => "-I",
				 );
				
$verbose = array(
				"Verbose" => "-v",  
				"Very verbose" => "-vv",  
				"Very very verbose" => "-vvv"
				 );

$timestamp = array(
				"Don't print a timestamp on each dump line" => "-t",  
				"Print an unformatted timestamp on each dump line" => "-tt",  
				"Print a delta (micro-second resolution) between current and previous line on each dump line" => "-ttt",  
				"Print a timestamp in default format proceeded by date on each dump line" => "-tttt",
				"Print a delta (micro-second resolution) between current and first line on each dump line" => "-ttttt"
				 );

?>