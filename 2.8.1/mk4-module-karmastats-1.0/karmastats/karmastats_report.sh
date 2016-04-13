#!/bin/bash

LD_LIBRARY_PATH=$LD_LIBRARY_PATH:/usb/lib:/usb/usr/lib
PATH=$PATH:/usb/usr/bin:/usb/usr/sbin

MYINTERFACE=wlan0

MYPATH="$(dirname $0)/"
LOG=${MYPATH}log

MYSERVER=`cat ${MYPATH}karmastats.conf | grep "server" | awk -F = '{print $2}'`

MYTIMESTAMP=`date +%s`

MYPINEID=`pineNumbers`
MYPINENAME=`cat ${MYPATH}karmastats.conf | grep "pineName" | awk -F = '{print $2}'`
MYPINEMAC=`ifconfig | grep wlan0 | awk '{print $5}'`
MYPINELATITUDE=`cat ${MYPATH}karmastats.conf | grep "pineLatitude" | awk -F = '{print $2}'`
MYPINELONGITUDE=`cat ${MYPATH}karmastats.conf | grep "pineLongitude" | awk -F = '{print $2}'`

MYHASHMAC=`cat ${MYPATH}karmastats.conf | grep "hashMAC" | awk -F = '{print $2}'`

MYTOKEN=`cat ${MYPATH}karmastats.conf | grep "token" | awk -F = '{print $2}'`

echo -e "==================================" >> ${LOG}
MYDATE=`date -d @${MYTIMESTAMP} +"%y-%m-%d %k-%M-%S"`
echo -e "Timestamp: ${MYDATE}" >> ${LOG}

# Interfaces #####
ifconfig ${MYINTERFACE} down
iwconfig ${MYINTERFACE} mode monitor
ifconfig ${MYINTERFACE} up

echo -e "Interface : ${MYINTERFACE}" >> ${LOG}

################

echo -e "Getting Stations list..." >> ${LOG}

tcpdump -l -i ${MYINTERFACE} -nne -s 256 type mgt subtype probe-resp or subtype probe-req 2> /dev/null | grep -v bad-fcs | while read line; do  
  
_ssid=$(echo ${line} | awk -F'[()]' '{print $2}')

if [ -n "$_ssid" ]; then

	_mac=$(echo $line | awk '{print substr($15, 4)}')
	_signal=$(echo $line | awk '{print $9}' | sed "s/dB//")

	if [ "${_signal}" -le "-100" ]; then
		_quality="0"
	elif [ "${_signal}" -ge "-50" ]; then
		_quality="100"
	else
		_quality=$((2 * (${_signal} + 100)))
	fi

	if [ "${MYHASHMAC}" -eq "1" ]; then
		_mac=$(echo -n ${_mac} | md5sum | awk '{ print $1 }')
	fi
	
	echo -e "=============" >> ${LOG}
	MYTIMESTAMP=`date +%s`
	MYDATE=`date -d @${MYTIMESTAMP} +"%y-%m-%d %k-%M-%S"`
	echo -e "Timestamp: ${MYDATE}" >> ${LOG}

	echo -e "Pineapple ID: ${MYPINEID}" >> ${LOG}
	echo -e "Pineapple Name: ${MYPINENAME}" >> ${LOG}
	echo -e "Pineapple MAC: ${MYPINEMAC}" >> ${LOG}
	echo -e "Pineapple Latitude: ${MYPINELATITUDE}" >> ${LOG}
	echo -e "Pineapple Longitude: ${MYPINELONGITUDE}" >> ${LOG}
	echo -e "Station MAC: ${_mac}" >> ${LOG}
	echo -e "SSID: ${_ssid}" >> ${LOG}
	echo -e "Station Signal: ${_signal}" >> ${LOG}
	echo -e "Station Signal Quality: ${_quality}" >> ${LOG}

	echo -e "Sending Stations data..." >> ${LOG}
	
	ping -c 3 8.8.8.8 >/dev/null
	rc=$?
	if [[ $rc -ne 0 ]]; then
		echo -e "No internet connection... Please check your network connectivity..."
	else
		curl --data "token=${MYTOKEN}&Pineapple_Number=${MYPINEID}&Pineapple_Name=${MYPINENAME}&Pineapple_MAC=${MYPINEMAC}&Pineapple_Latitude=${MYPINELATITUDE}&Pineapple_Longitude=${MYPINELONGITUDE}&Data_Timestamp=${MYDATE}&Station_SSID=${_ssid}&Station_MAC=${_mac}&Station_Signal=${_signal}&Station_Signal_Quality=${_quality}" ${MYSERVER}upload.php
	fi

fi

sleep 5
  
done

echo -e "==================================\n" >> ${LOG}