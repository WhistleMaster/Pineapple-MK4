#!/bin/sh

MYPATH="$(dirname $0)/"
LOG=${MYPATH}log

echo -e "Stopping Occupineapple..." >> ${LOG}

killall -9 start_mdk3.sh
killall -9 mdk3
rm ${LOG}
