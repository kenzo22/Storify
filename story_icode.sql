create table if not exists story_icode(
ic_index int(10) unsigned not null auto_increment primary key,
ic_code char(6) not null,
ic_email varchar(50) default null,
ic_time int(10) unsigned not null
);