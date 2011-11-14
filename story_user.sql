CREATE TABLE IF NOT EXISTS story_user (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT primary key,
  username varchar(60) NOT NULL DEFAULT '',
  passwd varchar(64) NOT NULL DEFAULT '',
  email varchar(100) NOT NULL DEFAULT '',
  photo varchar(255) default NULL,
  intro varchar(255) NOT NULL DEFAULT '',
  weibo_user_id bigint(20) unsigned NOT NULL DEFAULT 0,
  weibo_access_token varchar(100) NOT NULL DEFAULT '',
  weibo_access_token_secret varchar(100) NOT NULL DEFAULT '',
  tweibo_user_id bigint(20) unsigned NOT NULL DEFAULT 0,
  tweibo_access_token varchar(100) NOT NULL DEFAULT '',
  tweibo_access_token_secret varchar(100) NOT NULL DEFAULT '',
  douban_user_id bigint(20) unsigned NOT NULL DEFAULT 0,
  douban_access_token varchar(100) NOT NULL DEFAULT '',
  douban_access_token_secret varchar(100) NOT NULL DEFAULT '',
  yupoo_token varchar(100) NOT NULL DEFAULT '',
  registered_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  activate int(1) not null default 0,
  KEY `user_name_key` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `story_user` (`id`, `username`, `passwd`, `email`) VALUES 
(1, '张辛欣', '7c4a8d09ca3762af61e59520943dc26494f8941b','11473124@qq.com'),
(2, '金奂', '7c4a8d09ca3762af61e59520943dc26494f8941b','crazyscar@gmail.com'),
(3, '口立方', '7c4a8d09ca3762af61e59520943dc26494f8941b','support@koulifang.com'),
(4, '源源', '7c4a8d09ca3762af61e59520943dc26494f8941b','yuan0320@gmail.com');

grant select, insert, update, delete
on storybing.*
to root@localhost identified by 'kenzo22';
