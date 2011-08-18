CREATE TABLE IF NOT EXISTS story_publictoken (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT primary key,
  weibo_access_token varchar(100) NOT NULL DEFAULT '',
  weibo_access_token_secret varchar(100) NOT NULL DEFAULT '',
  tweibo_access_token varchar(100) NOT NULL DEFAULT '',
  tweibo_access_token_secret varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `story_publictoken` (`id`, `weibo_access_token`, `weibo_access_token_secret`, `tweibo_access_token`, `tweibo_access_token_secret`) VALUES 
(1, '3dded3c1a69e0e24609b04c3bc07d3ee', '4815f86a2f8dcbbca4a307535b1a82d8','1fce15f8b9d3449ea9a031adf9138f95', '2a4a03d0dac0951f06d3e7b5b30a1ea0');

