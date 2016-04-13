#!/bin/sh

MYPATH="$(dirname $0)/"
LOG=${MYPATH}log

BRINT=br-interceptor

echo -e "Stopping Interceptor..." > ${LOG}

ifconfig ${BRINT} down >> ${LOG}
brctl delbr ${BRINT} >> ${LOG}

# Bring back eth0 to pineapple br-lan
brctl addif br-lan eth0 >> ${LOG}

rm ${LOG}
