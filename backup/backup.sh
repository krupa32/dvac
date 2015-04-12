#!/bin/bash

# Configuration variables
SITEDIR=/var/www/dvac
SYNCDIR=/root/highcourtbackup
BACKUPDIR=/mnt/usb/highcourtbackup
MYSQLPASS=123456
RETAIN=7

BACKUPNAME=`date +%Y-%m-%d`

if [ ! -d $SYNCDIR ]
then
	mkdir -p $SYNCDIR
fi

if [ ! -d $BACKUPDIR ]
then
	mkdir -p $BACKUPDIR
fi

# Remove everything from tmp dir
rm -f $SITEDIR/case/tmp/*

# Take backup
mysqldump -u root -p$MYSQLPASS --databases dvac --add-drop-database > $SYNCDIR/db.sql
rsync -ru $SITEDIR/* $SYNCDIR
tar -czf $BACKUPDIR/$BACKUPNAME.tgz $SYNCDIR/*
#zip -r $BACKUPDIR/$BACKUPNAME.zip $SYNCDIR/*

# remove older backups (if any)
NUMBACKUPS=`ls -t1 $BACKUPDIR/* | wc -l`
#echo "numbackup=$NUMBACKUPS retain=$RETAIN" >> $BACKUPDIR/log.txt
if [ $NUMBACKUPS -gt $RETAIN ]
then
	OLDBACKUPS=`expr $NUMBACKUPS - $RETAIN`
	#echo "oldbackups=$OLDBACKUPS" >> $BACKUPDIR/log.txt
	for i in `ls -tr1 $BACKUPDIR/* | head -n $OLDBACKUPS`
	do
		rm $i
	done
fi
