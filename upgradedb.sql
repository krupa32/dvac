use dvac;

-- for r3
-- alter table users modify reporting_to int;
-- create table reminders(id int primary key auto_increment, creator int, case_id int, remind_on int, comment text);

-- for r4
-- alter table users add last_login int;

-- for r8
-- create table regularcases(id int primary key auto_increment, case_num text);
-- alter table cases add regularcase int;
-- alter table attachments add type tinyint;
-- alter table attachments add comment text;

-- for r9
-- create table smsqueue(id int primary key auto_increment, phone varchar(16), sms text);
-- alter table users add phone varchar(16);

-- for r11
-- alter table cases add court tinyint after category;
-- update cases set court=1;
-- update cases set court=2 where case_num like '%(MD)%';
-- alter table cases add tag text;

-- for r13
-- alter table cases add direction tinyint default 0 after status;

-- for r15
-- alter table cases add dvac_status tinyint default 20 after status;
-- update cases set status=10,dvac_status=10 where status=20;

-- for r17
alter table reminders add column dismissed tinyint default 1 after remind_on;
update reminders set dismissed = 1 where remind_on < (UNIX_TIMESTAMP() - 24*3600);
