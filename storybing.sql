-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 11 月 14 日 22:14
-- 服务器版本: 5.5.8
-- PHP 版本: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `storybing`
--

-- --------------------------------------------------------

--
-- 表的结构 `story_follow`
--

CREATE TABLE IF NOT EXISTS `story_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `follow_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `story_follow`
--


-- --------------------------------------------------------

--
-- 表的结构 `story_icode`
--

CREATE TABLE IF NOT EXISTS `story_icode` (
  `ic_index` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ic_code` char(6) NOT NULL,
  `ic_email` varchar(50) DEFAULT NULL,
  `ic_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ic_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `story_icode`
--


-- --------------------------------------------------------

--
-- 表的结构 `story_pageview`
--

CREATE TABLE IF NOT EXISTS `story_pageview` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `story_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `domain_name` varchar(60) NOT NULL DEFAULT '',
  `refer_url` varchar(200) NOT NULL DEFAULT '',
  `view_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `story_pageview`
--


-- --------------------------------------------------------

--
-- 表的结构 `story_posts`
--

CREATE TABLE IF NOT EXISTS `story_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `embed_name` char(12) NOT NULL,
  `post_title` varchar(120) NOT NULL,
  `post_summary` text NOT NULL,
  `post_pic_url` varchar(200) NOT NULL,
  `post_content` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'draft',
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_digg_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_title` (`post_title`),
  KEY `status_date` (`post_status`,`post_date`,`ID`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `story_posts`
--


-- --------------------------------------------------------

--
-- 表的结构 `story_publictoken`
--

CREATE TABLE IF NOT EXISTS `story_publictoken` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `weibo_access_token` varchar(100) NOT NULL DEFAULT '',
  `weibo_access_token_secret` varchar(100) NOT NULL DEFAULT '',
  `tweibo_access_token` varchar(100) NOT NULL DEFAULT '',
  `tweibo_access_token_secret` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `story_publictoken`
--

INSERT INTO `story_publictoken` (`id`, `weibo_access_token`, `weibo_access_token_secret`, `tweibo_access_token`, `tweibo_access_token_secret`) VALUES
(1, '3dded3c1a69e0e24609b04c3bc07d3ee', '4815f86a2f8dcbbca4a307535b1a82d8', '1fce15f8b9d3449ea9a031adf9138f95', '2a4a03d0dac0951f06d3e7b5b30a1ea0');

-- --------------------------------------------------------

--
-- 表的结构 `story_reset`
--

CREATE TABLE IF NOT EXISTS `story_reset` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `reset_code` char(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `story_reset`
--


-- --------------------------------------------------------

--
-- 表的结构 `story_tag`
--

CREATE TABLE IF NOT EXISTS `story_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `story_tag`
--


-- --------------------------------------------------------

--
-- 表的结构 `story_tag_story`
--

CREATE TABLE IF NOT EXISTS `story_tag_story` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `story_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `story_tag_story`
--


-- --------------------------------------------------------

--
-- 表的结构 `story_user`
--

CREATE TABLE IF NOT EXISTS `story_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '',
  `passwd` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `photo` varchar(255) DEFAULT NULL,
  `intro` varchar(255) NOT NULL DEFAULT '',
  `weibo_user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `weibo_access_token` varchar(100) NOT NULL DEFAULT '',
  `weibo_access_token_secret` varchar(100) NOT NULL DEFAULT '',
  `tweibo_user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `tweibo_access_token` varchar(100) NOT NULL DEFAULT '',
  `tweibo_access_token_secret` varchar(100) NOT NULL DEFAULT '',
  `douban_user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `douban_access_token` varchar(100) NOT NULL DEFAULT '',
  `douban_access_token_secret` varchar(100) NOT NULL DEFAULT '',
  `yupoo_token` varchar(100) NOT NULL DEFAULT '',
  `registered_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activate` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_name_key` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `story_user`
--

INSERT INTO `story_user` (`id`, `username`, `passwd`, `email`, `photo`, `intro`, `weibo_user_id`, `weibo_access_token`, `weibo_access_token_secret`, `tweibo_user_id`, `tweibo_access_token`, `tweibo_access_token_secret`, `douban_user_id`, `douban_access_token`, `douban_access_token_secret`, `yupoo_token`, `registered_time`, `activate`) VALUES
(1, '张辛欣', '7c4a8d09ca3762af61e59520943dc26494f8941b', '11473124@qq.com', NULL, '', 0, '', '', 0, '', '', 0, '', '', '', '0000-00-00 00:00:00', 0),
(2, '金奂', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'crazyscar@gmail.com', NULL, '', 0, '', '', 0, '', '', 0, '', '', '', '0000-00-00 00:00:00', 0),
(3, '口立方', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'support@koulifang.com', NULL, '', 0, '', '', 0, '', '', 0, '', '', '', '0000-00-00 00:00:00', 0),
(4, '源源', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'yuan0320@gmail.com', NULL, '', 0, '', '', 0, '', '', 0, '', '', '', '0000-00-00 00:00:00', 0);
