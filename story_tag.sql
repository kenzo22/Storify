CREATE TABLE IF NOT EXISTS story_tag (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT primary key,
  name varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `story_tag` (`id`, `name`) VALUES 
(1, '新闻'),
(2, '微博'),
(3, '娱乐'),
(4, '动车事故'),
(5, '创业');
