CREATE TABLE IF NOT EXISTS story_tag_story (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT primary key,
  tag_id bigint(20) unsigned NOT NULL DEFAULT 0,
  story_id bigint(20) unsigned NOT NULL DEFAULT 0
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `story_tag_story` (`id`, `tag_id`, `story_id`) VALUES 
(1, 1, 4),
(2, 1, 5),
(3, 2, 5),
(4, 3, 5),
(5, 4, 5),
(6, 4, 6),
(7, 5, 6),
(8, 5, 7);
