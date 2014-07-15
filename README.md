dvac
====

For DVAC

INSTALLATION
============

1) mysql -u root -p < initdb.sql

2) mkdir $DVACDIR/case/uploads

3) chmod a+w $DVACDIR/case/uploads

4) Edit /etc/php5/apache2/php.ini and set
    upload_max_filesize = 64M
    post_max_size = 64M
