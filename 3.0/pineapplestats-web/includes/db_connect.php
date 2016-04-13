<?php

$serverpath = str_replace('\\','/',dirname($_SERVER['SCRIPT_FILENAME']));
define("CONFIGFILE",$serverpath.'/includes/pineapplestats.cfg');

eval(file_get_contents(CONFIGFILE));

define("HOST", $conf['mysql_host']);
define("USER", $conf['mysql_user']); 
define("PASSWORD", $conf['mysql_pass']); 
define("DATABASE", $conf['mysql_database']);

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);

?>