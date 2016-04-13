#!/bin/sh

# Custom script
cat /tmp/dhcp.leases; echo -e '\n'; cat /proc/net/arp; echo -e '\n'; grep KARMA: /tmp/karma.log |awk '!x[$0]++ || ($3 == "Successful") || ($3 == "Checking")'| sed -e 's/\(CTRL_IFACE \)\|\(IEEE802_11 \)//' | sed -n '1!G;h;$p'