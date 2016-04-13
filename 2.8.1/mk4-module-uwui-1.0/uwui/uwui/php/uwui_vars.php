<?php
	if (is_dir("/etc/config")) {
		$system="pineapple";
		$sudo=""; 
		putenv('LD_LIBRARY_PATH='.getenv('LD_LIBRARY_PATH').':/usb/lib:/usb/usr/lib');   
		putenv('PATH='.getenv('PATH').':/usb/usr/bin:/usb/usr/sbin');
	} else {
		$system="intel";
		$sudo="sudo ";
	}
	$demo=false;
	$debug=false;
?>
