#!/bin/bash

MYPATH="$(dirname $0)/"
LOG=${MYPATH}log

rm ${LOG}

BRINT=br-interceptor
SWINT=eth1
COMPINT=eth0

BRIP=169.254.66.66

echo -e "Starting Interceptor..." > ${LOG}

echo -e "Bridge: ${BRINT}" >> ${LOG}

# Remove eth0 from pineapple br-lan
brctl delif br-lan eth0 >> ${LOG}

#build the bridge
brctl addbr ${BRINT} >> ${LOG}
brctl addif ${BRINT} ${COMPINT} >> ${LOG}
brctl addif ${BRINT} ${SWINT} >> ${LOG}

#bring up both sides of the bridge
ifconfig ${COMPINT} 0.0.0.0 up promisc >> ${LOG}
ifconfig ${SWINT} 0.0.0.0 up promisc >> ${LOG}

# bring up the bridge with the non-routable IP
ifconfig ${BRINT} ${BRIP} up promisc >> ${LOG}

echo -e "Interceptor is running..." >> ${LOG}