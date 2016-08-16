#!/bin/bash
if [ $# -eq 1 ] ; then
	if [ "`date +'%Y%m%d' -d $1 2> /dev/null`" == $1 ]; then
	    echo "$1 is ok"
		from_date=$1
	else
	    echo "$1 is ng"
		from_date=`date '+%Y%m%d'`
	fi
else
	echo "argument is not"
	from_date=`date '+%Y%m%d'`
fi

echo $from_date
