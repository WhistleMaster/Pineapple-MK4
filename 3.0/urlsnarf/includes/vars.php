<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

global $directory, $rel_dir;

$is_urlsnarf_installed = exec("which urlsnarf") != "" ? 1 : 0;
$is_urlsnarf_running = exec("ps auxww | grep urlsnarf | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_urlsnarf_onboot = exec("cat /etc/rc.local | grep urlsnarf/includes/autostart.sh") != "" ? 1 : 0;

$interfaces = explode("\n", trim(shell_exec("cat /proc/net/dev | tail -n +3 | cut -f1 -d: | sed 's/ //g'")));
$current_interface = trim(file_get_contents($directory."includes/infusion.run"));

$custom_commands = explode("\n", trim(file_get_contents($directory."includes/infusion.conf")));

$is_executable = exec("if [ -x ".$directory."includes/autostart.sh ]; then echo '1'; fi") != "" ? 1 : 0;
if(!$is_executable) exec("chmod +x ".$directory."includes/autostart.sh");

?>