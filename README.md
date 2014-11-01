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

5) To install auto backup,
    sudo su
    crontab $DVACDIR/backup/backup.crontab

6) To install sms service
    a) sudo chmod 666 /dev/ttyS0
    b) minicom -s
        Select serial port setup and set device as /dev/ttyS0, baud 115200,
        no flow control.
    c) In minicom type,
        A                      [this starts communication]
        AT<enter>              [check for OK]
        AT+CPIN=nnnn<enter>    [this sets the SIM pin to 'nnnn']
    d) After all this, the green LED in the GSM board should blink with
       ON and 3 seconds OFF.
