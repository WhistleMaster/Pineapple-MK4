#!/bin/sh

USBPATH="/usb/"
MODULEPATH="$(dirname $0)/"

# ebtables
opkg install ${MODULEPATH}dep/kmod-ebtables.ipk
opkg install ${MODULEPATH}dep/kmod-ebtables-ipv4.ipk
opkg install ${MODULEPATH}dep/ebtables.ipk --dest usb

# Update repository
opkg update 

# mii-tool
opkg install mii-tool

touch ${MODULEPATH}installed

echo "done" > ${MODULEPATH}status.php
