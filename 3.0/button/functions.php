<?php

require("/pineapple/components/infusions/button/includes/vars.php");

if(isset($_GET['button_update_conf']))
{
	echo button_update_conf($_POST['_wpsx'], $_POST['_newdata']);
}

function button_update_conf($_wpsx, $_newdata)
{  	
	global $_wpsx_min, $_wpsx_max, $no_handler, $karma_handler, $dnsspoof_handler, $ssh_handler, $reboot_handler, $script1_handler, $script2_handler, $script3_handler, $script4_handler, $default_handler;
	
	for($i=0;$i<4;$i++)
	{
		if ($_newdata[$i] != "")
			file_put_contents("/pineapple/components/infusions/button/includes/scripts/wpsScript".($i+1).".sh", $_newdata[$i]);
	}
	
	file_put_contents("/etc/pineapple/wpsScript.sh", $_newdata[4]);

	while(exec("uci get system.@button[1]") == "button")
	{
		exec("uci delete system.@button[1]");
	}
	exec("uci commit system");

	for($i=0;$i<4;$i++)
	{
		exec("uci add system button");
		exec("uci set system.@button[".($i+1)."].button=reset");
		exec("uci set system.@button[".($i+1)."].action=released");

		if($_wpsx[$i] == 0) $handler = $no_handler;
		else if($_wpsx[$i] == 1) $handler = $karma_handler;
		else if($_wpsx[$i] == 2) $handler = $dnsspoof_handler;
		else if($_wpsx[$i] == 3) $handler = $ssh_handler;
		else if($_wpsx[$i] == 4) $handler = $reboot_handler;
		else if($_wpsx[$i] == 5) $handler = $script1_handler;
		else if($_wpsx[$i] == 6) $handler = $script2_handler;
		else if($_wpsx[$i] == 7) $handler = $script3_handler;
		else if($_wpsx[$i] == 8) $handler = $script4_handler;
		else if($_wpsx[$i] == 9) $handler = $default_handler;

		exec("uci set system.@button[".($i+1)."].handler='".$handler."'");
		exec("uci set system.@button[".($i+1)."].min=".$_wpsx_min[$i]."");
		exec("uci set system.@button[".($i+1)."].max=".$_wpsx_max[$i]."");
		exec("uci commit system");
	}

	exec("uci commit system");

	return "<font color=lime>done</font><br/>";
}

function file_put_contents($filename, $data) {
  $f = @fopen($filename, 'w');
  if (!$f) {
    return false;
  } else {
    $bytes = fwrite($f, $data);
    fclose($f);
    return $bytes;
  }
}
	
?>