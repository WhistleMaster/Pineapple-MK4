#!/bin/bash

LD_LIBRARY_PATH=$LD_LIBRARY_PATH:/usb/lib:/usb/usr/lib
PATH=$PATH:/usb/usr/bin:/usb/usr/sbin

MYPATH="$(dirname $0)/"
LOG=${MYPATH}log

MYSERVER=`cat ${MYPATH}karmastats.conf | grep "server" | awk -F = '{print $2}'`

MYTIMESTAMP=`date +%s`

MYPINEID=`pineNumbers`
MYPINENAME=`cat ${MYPATH}karmastats.conf | grep "pineName" | awk -F = '{print $2}'`
MYPINEMAC=`ifconfig | grep wlan0 | awk '{print $5}'`
MYPINELATITUDE=`cat ${MYPATH}karmastats.conf | grep "pineLatitude" | awk -F = '{print $2}'`
MYPINELONGITUDE=`cat ${MYPATH}karmastats.conf | grep "pineLongitude" | awk -F = '{print $2}'`

MYTOKEN=`cat ${MYPATH}karmastats.conf | grep "token" | awk -F = '{print $2}'`

MYPINEIP=`curl -s ${MYSERVER}ip.php`

echo -e "==================================" >> ${LOG}
echo -e "WATCHDOG..." >> ${LOG}

MYDATE=`date -d @${MYTIMESTAMP} +"%y-%m-%d %k-%M-%S"`

ping -c 3 8.8.8.8 >/dev/null
rc=$?
if [[ $rc -ne 0 ]]; then
	echo -e "No internet connection... Please check your network connectivity..."
else
	echo -e "Timestamp: ${MYDATE}" >> ${LOG}
	echo -e "Pineapple IP: ${MYPINEIP}" >> ${LOG}

	curl --data "token=${MYTOKEN}&Pineapple_Number=${MYPINEID}&Pineapple_Name=${MYPINENAME}&Pineapple_MAC=${MYPINEMAC}&Pineapple_Latitude=${MYPINELATITUDE}&Pineapple_Longitude=${MYPINELONGITUDE}&Pineapple_IP=${MYPINEIP}&Data_Timestamp=${MYDATE}" ${MYSERVER}watchdog.php
fi

# Monitor log size
# q = threshold in bytes
q=1310720
w=`ls -la ${LOG} | awk '{print $5}'`
if [ $w -ge $q ]; then
	echo -e "Log truncated to prevent memory loss.\n" > ${LOG}
fi

# Monitor Deamon status
ps auxww | grep {karmastats_repo} | grep -v -e grep | grep -v -e php
case "$?" in
   0)
		# It is running in this case so we do nothing.
		echo -e "karmastats_report.sh is RUNNING." >> ${LOG}
   ;;
   1)
		echo -e "karmastats_report.sh is NOT RUNNING. Starting karmastats_report.sh..." >> ${LOG}
		echo ${MYPATH}karmastats_report.sh | at now > /dev/null 2>&1
   ;;
esac

echo -e "==================================\n" >> ${LOG}