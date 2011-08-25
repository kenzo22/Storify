create table if not exists story_follow(
id int unsigned not null auto_increment primary key,
user_id int unsigned default null,
follow_id int unsigned default null
);
