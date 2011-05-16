create table story_User  (
  id int(11) unsigned not null auto_increment primary key,
  username varchar(16) not null,
  passwd char(40) not null,
  email varchar(100) not null,
  activate int(1) not null default 0
);

INSERT INTO `story_User` (`id`, `username`, `passwd`, `email`) VALUES 
(1, '张辛欣', 'e10adc3949ba59abbe56e057f20f883e','xinxinzhang22@gmail.com'),
(2, '源源', 'e10adc3949ba59abbe56e057f20f883e','yuan0320@gmail.com'),
(3, 'test3', 'e10adc3949ba59abbe56e057f20f883e','test3@gmail.com'),
(4, 'test4', 'e10adc3949ba59abbe56e057f20f883e','test4@gmail.com'),
(5, 'test5', 'e10adc3949ba59abbe56e057f20f883e','test5@gmail.com'),
(6, 'test6', 'e10adc3949ba59abbe56e057f20f883e','test6@gmail.com'),
(7, 'test7', 'e10adc3949ba59abbe56e057f20f883e','test7@gmail.com'),
(8, 'test8', 'e10adc3949ba59abbe56e057f20f883e','test8@gmail.com');

grant select, insert, update, delete
on storybing.*
to root@localhost identified by 'kenzo22';