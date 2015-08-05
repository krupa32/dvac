#!/bin/bash

SITEDIR=/var/www/dvac

echo "Creating site directory"
mkdir $SITEDIR

echo "Copying source files"
cp -R ../* $SITEDIR/

echo "Moving to site directory"
cd $SITEDIR

echo "Initializing database"
mysql -u root -p < initdb.sql

echo "Setting up uploads and tmp directories"
chmod a+w "$SITEDIR/case/uploads"
chmod a+w "$SITEDIR/case/tmp"

echo "Setting up cron jobs"
crontab $SITEDIR/backup/backup.crontab

echo "Updating apache files"
cp install/php.ini /etc/php5/apache2/
cp install/001-dvac.conf /etc/apache2/sites-available/
ln -sf /etc/apache2/sites-available/001-dvac.conf /etc/apache2/sites-enabled/000-default.conf

echo "Restarting apache"
service apache2 restart

echo "Finished installation"

