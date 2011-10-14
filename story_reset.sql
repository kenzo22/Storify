CREATE TABLE IF NOT EXISTS story_reset (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT primary key,
  username varchar(60) NOT NULL DEFAULT '',
  email varchar(100) NOT NULL DEFAULT '',
  reset_code char(8) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;