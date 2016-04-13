#!/bin/sh

i=1
while [ ${i} -le ${1} ]
do
	ledcontrol wps off; sleep 1
	ledcontrol wps on; sleep 1
	i=`expr $i + 1`
done
