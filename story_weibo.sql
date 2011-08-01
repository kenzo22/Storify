CREATE TABLE IF NOT EXISTS story_weibo (
  weibo_ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  weibo_permanent_ID bigint(20) unsigned NOT NULL DEFAULT '0',
  weibo_post_ID bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`weibo_ID`),
  KEY `weibo_post_ID` (`weibo_post_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
