CREATE TABLE IF NOT EXISTS story_user (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT primary key,
  username varchar(60) NOT NULL DEFAULT '',
  passwd varchar(64) NOT NULL DEFAULT '',
  email varchar(100) NOT NULL DEFAULT '',
  weibo_user_id bigint(20) unsigned NOT NULL DEFAULT 0,
  weibo_access_token varchar(100) NOT NULL DEFAULT '',
  weibo_access_token_secret varchar(100) NOT NULL DEFAULT '',
  tweibo_user_id bigint(20) unsigned NOT NULL DEFAULT 0,
  tweibo_access_token varchar(100) NOT NULL DEFAULT '',
  tweibo_access_token_secret varchar(100) NOT NULL DEFAULT '',
  yupoo_token varchar(100) NOT NULL DEFAULT '',
  registered_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  activate int(1) not null default 0,
  KEY `user_name_key` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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