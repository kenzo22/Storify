CREATE TABLE IF NOT EXISTS story_pageview (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT primary key,
  story_id bigint(20) unsigned NOT NULL DEFAULT 0,
  domain_name varchar(60) NOT NULL DEFAULT '',
  refer_url varchar(200) NOT NULL DEFAULT '',
  view_count int unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;