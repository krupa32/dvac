#!/bin/bash

# Backup procedure is as follows:
# - All code and database is compressed and backed up under 
#   BACKUPDIR/BACKUPNAME.tgz
# - All uploads (files) are backed up under BACKUPDIR/uploads/ dir
# - Last 7 versions of code are retained. Older ones deleted.
# - Only latest version of uploads/ dir are retained.

# Configuration variables
SITEDIR=/var/www/dvac
SYNCDIR=/root/highcourtbackup
BACKUPDIR=/mnt/usb/highcourtbackup
UPLOADBACKUPDIR=$BACKUPDIR/uploads
MYSQLPASS=123456
RETAIN=7

BACKUPNAME=`date +%Y-%m-%d`

# Create required sync and backup directories
if [ ! -d $SYNCDIR ]
then
	mkdir -p $SYNCDIR
fi

if [ ! -d $BACKUPDIR ]
then
	mkdir -p $BACKUPDIR
fi

if [ ! -d $UPLOADBACKUPDIR ]
then
	mkdir -p $UPLOADBACKUPDIR
fi

# Remove everything from tmp dir
rm -f $SITEDIR/case/tmp/*

# Sync the uploads
rsync -u $SITEDIR/case/uploads/* $UPLOADBACKUPDIR/

# Take backup of code and database
mysqldump -u root -p$MYSQLPASS --databases dvac --add-drop-database > $SYNCDIR/db.sql
rsync -ru --exclude="*/uploads/*" --exclude="*/tmp/*" $SITEDIR/* $SYNCDIR
tar -czf $BACKUPDIR/$BACKUPNAME.tgz $SYNCDIR/*

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
