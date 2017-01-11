drop table reminders;
drop table tasks;
drop table user_categories;
drop table categories;
drop table users;
drop table time_zones;

create table users(
	user_id bigint not null auto_increment,
	user_name varchar(255) not null,
	user_password varchar(255) not null,
	user_email varchar(255) not null,
	user_phone varchar(10),
	user_carrier varchar(255),
	user_account_active boolean not null,
	user_account_created_date datetime not null,
	user_time_zone_id tinyint not null,
    primary key(user_id)
);

create table categories(
	category_id bigint not null auto_increment,
	category_name varchar(255),
    primary key(category_id)
);


/* VALID DATETIME FORMAT: 'YYYY-MM-DD HH:MM:SS' */
create table tasks(
	task_id bigint not null auto_increment,
    task_name varchar(255) not null,
    created_date datetime not null,
    due_date datetime,
    completed_date datetime,
    category_id bigint,
    user_id bigint not null,
	task_active tinyint,
    primary key(task_id),
    foreign key(user_id) references users(user_id),
    foreign key(category_id) references categories(category_id)
);

create table reminders(
	reminder_id bigint not null auto_increment,
	task_id bigint not null,
    reminder_date datetime not null,
	reminder_input int not null,
	reminder_select varchar(255) not null,
    primary key(reminder_id),
	foreign key(task_id) references tasks(task_id)
);

create table user_categories(
	user_id bigint not null,
    category_id bigint,
    foreign key(user_id) references users(user_id),
    foreign key(category_id) references categories(category_id)
);

create table time_zones (
	time_zone_id tinyint auto_increment,
	time_zone_location varchar(255),
	time_zone_value varchar(255),
	primary key(time_zone_id)
);

create table friend (
	friendship_id bigint not null auto_increment,
	user_one_id bigint not null,
	user_two_id bigint not null,
	primary key(friendship_id)
);

create table invite (
	invite_id bigint not null auto_increment,
	from_id bigint not null,
	to_id bigint not null,
	primary key(invite_id)
);

create table shared_task(
	task_id bigint not null,
	from_id bigint not null,
	to_id bigint not null
);

insert into categories(category_name)
Values ("Personal");

insert into categories(category_name)
Values ("Business");

insert into categories(category_name)
Values ("School");

insert into categories(category_name)
Values ("Medical");

insert into categories(category_name)
Values ("Work");

insert into time_zones(time_zone_location, time_zone_value)
Values ("Eastern", "America/New_York");

insert into time_zones(time_zone_location, time_zone_value)
Values ("Central", "America/Chicago");

insert into time_zones(time_zone_location, time_zone_value)
Values ("Mountain", "America/Denver");

insert into time_zones(time_zone_location, time_zone_value)
Values ("Mountain no DST", "America/Phoenix");

insert into time_zones(time_zone_location, time_zone_value)
Values ("Pacific", "America/Los_Angeles");

insert into time_zones(time_zone_location, time_zone_value)
Values ("Alaska", "America/Anchorage");

insert into time_zones(time_zone_location, time_zone_value)
Values ("Hawaii", "America/Adak");

insert into time_zones(time_zone_location, time_zone_value)
Values ("Hawaii no DST", "Pacific/Honolulu");


SELECT DISTINCT r.reminder_date, time_zones.time_zone_value, users.user_id
FROM reminders r
JOIN tasks t ON reminders.task_id = tasks.task_id
WHERE reminders.reminder_date>='2017-01-05 21:32:00' AND reminders.reminder_date<='2017-01-05 23:32:00'
		AND reminders.task_id = tasks.task_id AND tasks.user_id = users.user_id;
