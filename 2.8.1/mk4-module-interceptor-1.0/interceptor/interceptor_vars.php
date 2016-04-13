<?php

putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');

$module_name = "Interceptor";
$module_path = exec("pwd")."/";
$module_version = "1.0";

$installed = file_exists($module_path."installed") ? 1 : 0;

$is_interceptor_running = file_exists($module_path."interceptor.run") ? 1 : 0;
$is_8021X = file_exists($module_path."interceptor.run") && exec("cat ".$module_path."interceptor.run") == "8021X" ? 1 : 0;
$is_interceptor_installed = exec("uci get network.interceptor") == "interface" ? 1 : 0;
$is_interceptor_onboot = exec("cat /etc/rc.local | grep start_interceptor.sh") != "" ? 1 : 0;
$is_8021X_onboot = exec("cat /etc/rc.local | grep start_interceptor_8021X.sh") != "" ? 1 : 0;

?>