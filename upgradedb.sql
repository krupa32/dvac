use dvac;

alter table users modify reporting_to int;

create table reminders(id int primary key auto_increment, creator int, case_id int, remind_on int, comment text);
