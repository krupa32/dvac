use dvac;

-- for r3
-- alter table users modify reporting_to int;
-- create table reminders(id int primary key auto_increment, creator int, case_id int, remind_on int, comment text);

-- for r4
-- alter table users add last_login int;

-- for r8
create table regularcases(id int primary key auto_increment, case_num text);
alter table cases add regularcase int;
alter table attachments add type tinyint;
alter table attachments add comment text;

