<?php

global $directory, $rel_dir;

$interfaces = explode("\n", trim(shell_exec("ifconfig | grep  'encap:Ethernet'  | cut -d' ' -f1")));

?>