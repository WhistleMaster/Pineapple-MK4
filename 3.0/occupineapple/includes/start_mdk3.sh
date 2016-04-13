#!/bin/sh

MYPATH="$(dirname $0)/"
LOG=${MYPATH}log

killall -9 mdk3
rm ${LOG}

MYMONITOR=`cat ${MYPATH}infusion.conf | grep "monitor" | awk -F = '{print $2}'`
MYINTERFACE=`cat ${MYPATH}infusion.conf | grep "interface" | awk -F = '{print $2}'`

MYLIST=`cat ${MYPATH}infusion.conf | grep "list" | awk -F = '{print $2}'`
SPEED=`cat ${MYPATH}infusion.conf | grep "speed" | awk -F = '{print $2}'`
CHANNEL=`cat ${MYPATH}infusion.conf | grep "channel" | awk -F = '{print $2}'`
MYOPTIONS=`cat ${MYPATH}infusion.conf | grep "options" | awk -F = '{print $2}'`

echo -e "Starting Occupineapple..." > ${LOG}

if [ -z "$MYINTERFACE" ]; then
	MYINTERFACE=`iwconfig 2> /dev/null | grep "Mode:Master" | awk '{print $1}' | head -1`
else
	MYFLAG=`iwconfig 2> /dev/null | awk '{print $1}' | grep ${MYINTERFACE}`
	
	if [ -z "$MYFLAG" ]; then
	    MYINTERFACE=`iwconfig 2> /dev/null | grep "Mode:Master" | awk '{print $1}' | head -1`
	fi
fi

if [ -z "$MYMONITOR" ]; then
	MYMONITOR=`iwconfig 2> /dev/null | grep "Mode:Monitor" | awk '{print $1}' | head -1`
   
	MYFLAG=`iwconfig 2> /dev/null | awk '{print $1}' | grep ${MYMONITOR}`
	
	if [ -z "$MYFLAG" ]; then
	    airmon-ng start ${MYINTERFACE}
	    MYMONITOR=`iwconfig 2> /dev/null | grep "Mode:Monitor" | awk '{print $1}' | head -1`
	fi
else
	MYFLAG=`iwconfig 2> /dev/null | awk '{print $1}' | grep ${MYMONITOR}`
	
	if [ -z "$MYFLAG" ]; then
	    airmon-ng start ${MYINTERFACE}
	    MYMONITOR=`iwconfig 2> /dev/null | grep "Mode:Monitor" | awk '{print $1}' | head -1`
	fi
fi

echo -e "Interface : ${MYINTERFACE}" >> ${LOG}
echo -e "Monitor : ${MYMONITOR}" >> ${LOG}

if [ "$MYLIST" != "--" ] && [ -n "$MYLIST" ]; then
	echo -e "List : ${MYLIST}" >> ${LOG}
	
	MYFLAG=`echo ${MYLIST} | awk '{print match($0,".mlist")}'`;

	if [ ${MYFLAG} -gt 0 ];then
		MYLIST="-v ${MYPATH}lists/${MYLIST}"
	else
		MYLIST="-f ${MYPATH}lists/${MYLIST}"
	fi
else
	echo -e "List : random" >> ${LOG}
	MYLIST=
fi

if [ -n "$SPEED" ]; then
	echo -e "Speed : ${SPEED}" >> ${LOG}
	SPEED="-s ${SPEED}"
else
	echo -e "Speed : default" >> ${LOG}
	SPEED=
fi

if [ -n "$CHANNEL" ]; then
	echo -e "Channel : ${CHANNEL}" >> ${LOG}
	CHANNEL="-c ${CHANNEL}"
else
	echo -e "Channel : default" >> ${LOG}
	CHANNEL=
fi

echo -e "Options : ${MYOPTIONS}" >> ${LOG}

ifconfig ${MYINTERFACE} down
ifconfig ${MYINTERFACE} up

mdk3 ${MYMONITOR} b ${SPEED} ${CHANNEL} ${MYLIST} ${MYOPTIONS} >> ${LOG} &
