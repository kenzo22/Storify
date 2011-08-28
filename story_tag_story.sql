CREATE TABLE IF NOT EXISTS story_tag_story (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT primary key,
  tag_id bigint(20) unsigned NOT NULL DEFAULT 0,
  story_id bigint(20) unsigned NOT NULL DEFAULT 0
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

