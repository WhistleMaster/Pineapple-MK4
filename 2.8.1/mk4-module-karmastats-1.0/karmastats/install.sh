#!/bin/sh

USBPATH="/usb/"
MODULEPATH="$(dirname $0)/"

ping -c 3 8.8.8.8 >/dev/null
rc=$?
if [[ $rc -ne 0 ]]; then
	touch ${MODULEPATH}install_error

	echo "done" > ${MODULEPATH}status.php
else
	# Update repository
	opkg update 

	# curl 
	opkg install curl

	# tcpdump
	opkg install tcpdump
	
	# check permissions
	chmod +x ${MODULEPATH}karmastats_report.sh
	chmod +x ${MODULEPATH}karmastats_watchdog.sh

	touch ${MODULEPATH}installed

	echo "done" > ${MODULEPATH}status.php
fi