CREATE TABLE IF NOT EXISTS story_weibo (
  weibo_ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  weibo_post_ID bigint(20) unsigned NOT NULL DEFAULT '0',
  weibo_author tinytext NOT NULL,
  weibo_profile_image_url varchar(200) NOT NULL DEFAULT '',
  weibo_date datetime NOT NULL DEFAULT '0000-00-00 00:00',
  weibo_date_gmt datetime NOT NULL DEFAULT '0000-00-00 00:00',
  weibo_content text NOT NULL,
  weibo_type varchar(20) NOT NULL DEFAULT '',
  weibo_author_id bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`weibo_ID`),
  KEY `weibo_post_ID` (`weibo_post_ID`),
  KEY `weibo_date_gmt` (`weibo_date_gmt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
