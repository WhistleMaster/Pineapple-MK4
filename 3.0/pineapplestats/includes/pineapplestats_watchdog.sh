#!/bin/bash

LD_LIBRARY_PATH=$LD_LIBRARY_PATH:/usb/lib:/usb/usr/lib
PATH=$PATH:/usb/usr/bin:/usb/usr/sbin

MYPATH="$(dirname $0)/"
LOG=${MYPATH}log

MYSERVER=`cat ${MYPATH}infusion.conf | grep "server" | awk -F = '{print $2}'`

MYTIMESTAMP=`date +%s`

MYPINEID=`pineNumbers`
MYPINENAME=`cat ${MYPATH}infusion.conf | grep "pineName" | awk -F = '{print $2}'`
# MYPINEMAC=`ifconfig | grep wlan0 | awk '{print $5}'`
MYPINEMAC=`uci get wireless.radio0.macaddr`
MYPINELATITUDE=`cat ${MYPATH}infusion.conf | grep "pineLatitude" | awk -F = '{print $2}'`
MYPINELONGITUDE=`cat ${MYPATH}infusion.conf | grep "pineLongitude" | awk -F = '{print $2}'`

MYCONNECTIVITYCHECK=`cat ${MYPATH}infusion.conf | grep "connectivityCheck" | awk -F = '{print $2}'`

MYTOKEN=`cat ${MYPATH}infusion.conf | grep "token" | awk -F = '{print $2}'`

MYPINEIP=`curl -s ${MYSERVER}ip.php`

echo -e "==================================" >> ${LOG}
echo -e "WATCHDOG..." >> ${LOG}

MYDATE=`date -d @${MYTIMESTAMP} +"%y-%m-%d %k-%M-%S"`

ping -q -c 1 -W 10 8.8.8.8 >/dev/null
rc=$?
if [[ $rc -ne 0 ]]; then
	echo -e "No internet connection... Please check your network connectivity..."  >> ${LOG}
else
	echo -e "Timestamp: ${MYDATE}" >> ${LOG}
	echo -e "Pineapple IP: ${MYPINEIP}" >> ${LOG}

	curl --data "token=${MYTOKEN}&Pineapple_Number=${MYPINEID}&Pineapple_Name=${MYPINENAME}&Pineapple_MAC=${MYPINEMAC}&Pineapple_Latitude=${MYPINELATITUDE}&Pineapple_Longitude=${MYPINELONGITUDE}&Pineapple_IP=${MYPINEIP}&Data_Timestamp=${MYDATE}" ${MYSERVER}watchdog.php
fi

# Monitor Deamon status
if pidof pineapplestats_report.sh > /dev/null; then
	echo -e "pineapplestats_report.sh is RUNNING." >> ${LOG}
else
	echo -e "pineapplestats_report.sh is NOT RUNNING. Starting pineapplestats_report.sh..." >> ${LOG}
	echo ${MYPATH}pineapplestats_report.sh | at now > /dev/null 2>&1
fi

# Monitor AutoSSH status
if pidof autossh > /dev/null; then
	echo -e "autossh is RUNNING." >> ${LOG}
else
	echo -e "autossh is NOT RUNNING. Starting autossh..." >> ${LOG}
	/etc/init.d/autossh start
fi

# Monitor Internet status
if [[ ${MYCONNECTIVITYCHECK} -ne 0 ]]; then
	ping -q -c 1 -W 10 8.8.8.8 >/dev/null
	rc=$?
	if [[ $rc -ne 0 ]]; then
		echo -e "No internet connection... Waiting 5 minutes..."  >> ${LOG}
		sleep 300
	
		ping -q -c 1 -W 10 8.8.8.8 >/dev/null
		rc=$?
		if [[ $rc -ne 0 ]]; then
			echo -e "Still no internet connection... Rebooting..."  >> ${LOG}
			reboot
		else
			echo -e "Internet connection is UP."  >> ${LOG}
		fi
	else
		echo -e "Internet connection is UP."  >> ${LOG}
	fi
fi

# Monitor log size
# q = threshold in bytes
q=1310720
w=`ls -la ${LOG} | awk '{print $5}'`
if [ $w -ge $q ]; then
	echo -e "Log truncated to prevent memory loss.\n" > ${LOG}
fi

echo -e "==================================\n" >> ${LOG}