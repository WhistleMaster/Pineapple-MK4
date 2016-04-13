<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

global $directory, $rel_dir;

$is_sslstrip_installed = exec("which sslstrip") != "" ? 1 : 0;
$is_sslstrip_running = exec("ps auxww | grep sslstrip | grep -v -e grep | grep -v -e php") != "" ? 1 : 0;
$is_sslstrip_onboot = exec("cat /etc/rc.local | grep sslstrip/includes/autostart.sh") != "" ? 1 : 0;
$is_verbose = exec("ps auxww | grep \"sslstrip -a\" | grep -v -e grep | grep -v -e php");
$is_rule_http_installed = exec("iptables -t nat --line-numbers -n -L | grep 80 | grep 10000") != "" ? 1 : 0;
$is_rule_https_installed = exec("iptables -t nat --line-numbers -n -L | grep 443 | grep 10000") != "" ? 1 : 0;

if(!file_exists("/usr/lib/python2.7") && file_exists("/usb/usr/lib/python2.7/"))
{
	exec("ln -s /usb/usr/lib/python2.7/ /usr/lib/python2.7");
}

if(!file_exists("/usr/share/sslstrip") && file_exists("/usb/usr/share/sslstrip/"))
{
	exec("ln -s /usb/usr/share/sslstrip/ /usr/share/sslstrip");
}

if(!file_exists("/usb/usr/lib/python2.7/site-packages/zope/__init__.py"))
{
	exec("touch /usb/usr/lib/python2.7/site-packages/zope/__init__.py");
}

$is_executable = exec("if [ -x ".$directory."includes/autostart.sh ]; then echo '1'; fi") != "" ? 1 : 0;
if(!$is_executable) exec("chmod +x ".$directory."includes/autostart.sh");

$custom_commands = explode("\n", trim(file_get_contents($directory."includes/infusion.conf")));

?>