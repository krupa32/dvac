#!/bin/bash

# Configuration variables
SITEDIR=/home/krupa/work/dvac
SYNCDIR=/home/krupa/dvacbackup
BACKUPDIR=/tmp
MYSQLPASS=fossil27
RETAIN=7

BACKUPNAME=`date +%Y-%m-%d-%M`

if [ ! -d $SYNCDIR ]
then
	mkdir -p $SYNCDIR
fi

if [ ! -d $BACKUPDIR ]
then
	mkdir -p $BACKUPDIR
fi

# Take backup
mysqldump -u root -p$MYSQLPASS --databases dvac --add-drop-database > $SYNCDIR/db.sql
rsync -ru $SITEDIR/case/uploads $SYNCDIR
zip -r $BACKUPDIR/$BACKUPNAME.zip $SYNCDIR/*

# remove older backups (if any)
NUMBACKUPS=`ls -t1 $BACKUPDIR/*.zip | wc -l`
#echo "numbackup=$NUMBACKUPS retain=$RETAIN" >> $BACKUPDIR/log.txt
if [ $NUMBACKUPS -gt $RETAIN ]
then
	OLDBACKUPS=`expr $NUMBACKUPS - $RETAIN`
	#echo "oldbackups=$OLDBACKUPS" >> $BACKUPDIR/log.txt
	for i in `ls -t1 $BACKUPDIR/*.zip | head -n $OLDBACKUPS`
	do
		rm $i
	done
fi
