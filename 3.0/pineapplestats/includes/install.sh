#!/bin/sh

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
	
	#libpcap
	opkg upgrade libpcap
	
	# check permissions
	chmod +x ${MODULEPATH}pineapplestats_report.sh
	chmod +x ${MODULEPATH}pineapplestats_token.sh
	chmod +x ${MODULEPATH}pineapplestats_reboot.sh
	chmod +x ${MODULEPATH}pineapplestats_watchdog.sh
	
	# Stealth mode
	uci set wireless.@wifi-iface[0].mode=monitor
	uci set wireless.@wifi-iface[0].hidden=1
	uci delete wireless.@wifi-iface[0].ssid
	uci delete wireless.@wifi-iface[0].network
	uci commit wireless
	wifi
	
	# Generate new SSH Public Key
	rm -rf /etc/dropbear/id_rsa
	dropbearkey -t rsa -f /etc/dropbear/id_rsa
	
	# AutoSSH at boot
	sed -i '/exit 0/d' /etc/rc.local
	echo /etc/init.d/autossh start >> /etc/rc.local
	echo exit 0 >> /etc/rc.local
	
	# Done !
	touch ${MODULEPATH}installed
	echo "done" > ${MODULEPATH}status.php
fi