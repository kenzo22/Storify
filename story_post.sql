CREATE TABLE IF NOT EXISTS story_posts (
  ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  post_author bigint(20) unsigned NOT NULL DEFAULT '0',
  post_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  post_date_gmt datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  post_title varchar(120) NOT NULL,
  post_summary text NOT NULL,
  post_pic_url varchar(200) NOT NULL,
  post_content text NOT NULL,
  post_status varchar(20) NOT NULL DEFAULT 'draft',
  post_modified datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  post_modified_gmt datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `post_title` (`post_title`),
  KEY `status_date` (`post_status`,`post_date`,`ID`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;