-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 11 月 15 日 21:13
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `story_follow`
--

INSERT INTO `story_follow` (`id`, `user_id`, `follow_id`) VALUES
(1, 4, 1),
(2, 1, 3),
(3, 1, 2);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `story_pageview`
--

INSERT INTO `story_pageview` (`id`, `story_id`, `domain_name`, `refer_url`, `view_count`) VALUES
(1, 2, 'koulifang.com', '', 18),
(2, 3, 'koulifang.com', '', 6),
(3, 4, 'koulifang.com', '', 18),
(4, 5, 'koulifang.com', '', 3);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `story_posts`
--

INSERT INTO `story_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `embed_name`, `post_title`, `post_summary`, `post_pic_url`, `post_content`, `post_status`, `post_modified`, `post_modified_gmt`, `post_digg_count`) VALUES
(2, 1, '2011-11-14 22:56:52', '2011-11-14 22:56:52', 'GAEzhzlSzTWu', '北京空气质量', '让我们聊聊最近网上热议的帝都空气质量问题', 'http://tp4.sinaimg.cn/2050142347/180/1301288435/0', '{"content":[{"id":0,"type":"comment","content":"今天绿色-北京公布的数据"},{"id":1,"type":"weibo","content":{"id":"3379671511177537","nic":"绿色-北京","uid":"2050142347"}},{"id":2,"type":"video","content":{"title":"北京地区雾霾天气持续 空气质量再引关注 111101 正午30分 - 视频 - 优酷视频 - 在线观看","src":"http://player.youku.com/player.php/sid/XMzE4MjI1MDg4/v.swf","url":"http://v.youku.com/v_show/id_XMzE4MjI1MDg4.html"}},{"id":3,"type":"comment","content":"公布PM2.5我们还要等多久？"},{"id":4,"type":"weibo","content":{"id":"3378550859655770","nic":"头条新闻","uid":"1618051664"}},{"id":5,"type":"weibo","content":{"id":"3378579229305511","nic":"南都周刊","uid":"1641532820"}},{"id":6,"type":"video","content":{"title":"美国大使馆惊爆北京空气质量是重度污染 - 视频 - 优酷视频 - 在线观看","src":"http://player.youku.com/player.php/sid/XMjI5OTc1NDIw/v.swf","url":"http://v.youku.com/v_show/id_XMjI5OTc1NDIw.html"}},{"id":7,"type":"weibo","content":{"id":"3376476386751281","nic":"陈志武","uid":"1222713954"}},{"id":8,"type":"comment","content":"至少我觉得应该拥有这个知情权，我至少要知道在一个我天天生活的地方哪天出去是比较安全的吧。。。"}]}', 'Published', '2011-11-14 22:56:52', '2011-11-14 22:56:52', 1),
(3, 1, '2011-11-14 23:07:08', '2011-11-14 23:07:08', 'CsTFtsrRnZal', '西安一座大厦门店发生爆炸', '', 'http://tp1.sinaimg.cn/1641532820/180/5608624466/1', '{"content":[{"id":0,"type":"weibo","content":{"id":"3379644959815495","nic":"南都周刊","uid":"1641532820"}},{"id":1,"type":"video","content":{"title":"实拍西安高新区嘉天国际大厦一层爆炸现场","src":"http://player.youku.com/player.php/Type/Folder/Fid/16631516/Ob/1/Pt/0/sid/XMzIyMjkyMTg0/v.swf","url":"http://v.youku.com/v_playlist/f16631516o1p0.html"}},{"id":2,"type":"video","content":{"title":"[拍客]实拍记者医院采访西安爆炸案伤者 - 视频 - 优酷视频 - 在线观看","src":"http://player.youku.com/player.php/sid/XMzIyNDE0MDE2/v.swf","url":"http://v.youku.com/v_show/id_XMzIyNDE0MDE2.html"}}]}', 'Published', '2011-11-14 23:07:08', '2011-11-14 23:07:08', 5),
(4, 3, '2011-11-14 23:51:47', '2011-11-14 23:51:47', 'fQP5d53N4jAz', '口立方-用户帮助-FAQ', '在这里我们希望提供一些额外的信息帮助大家了解和使用口立方，大家有什么问题或者建议欢迎通过主页右侧的意见反馈联系我们，我们的成长不能没有你们的参与 ：）', 'http://tp1.sinaimg.cn/2329577672/180/5616253127/1', '{"content":[{"id":0,"type":"comment","content":"这是我们新开通的官方微博，欢迎大家通过微博关注我们和我们互动 ：）"},{"id":1,"type":"weibo","content":{"id":"3379778277509461","nic":"口立方","uid":"2329577672"}},{"id":2,"type":"comment","content":"<b>口立方可以用来做什么？</b>"},{"id":3,"type":"comment","content":"在口立方你可以快速地寻找与某一主题相关的微博，图片，视频等信息，简单操作你就能将这些信息组合起来创建一个完整丰富的“故事”。"},{"id":4,"type":"comment","content":"故事的主题可以是新闻、热点事件报道，生活旅游，时尚周边，书评影评，还有更多你能想到的。将故事发布出去，让更多的人阅读分享你的作品。"},{"id":5,"type":"comment","content":"<b>口立方发布的故事有什么特殊的？为什么要用口立方创建发布故事？</b>"},{"id":6,"type":"comment","content":"1. 口立方发布的故事可以包含微博，图片，视频，评论，内容样式很丰富。<br>2. 口立方的故事可以将相关的各类信息组合成一个有机的整体，整体内的信息可以有内在的逻辑联系和顺序，这显然比杂乱无章的碎片化信息更有传播价值。<br>3. 你的故事可以挑选引用别人的优秀内容，同时也能加入自己的评论和注解。<br>4. 发布故事以后的嵌入和分享功能可以让你的作品传播更迅速，影响更深远。<br>5. 在口立方创建发布故事轻松快速。<br>"},{"id":7,"type":"comment","content":"<b>为什么取名口立方？</b><br>"},{"id":8,"type":"comment","content":"这个名字来源于一位好朋友的创意，我们觉得非常适合这个网站。“立方”汇聚了我们钟爱的各种社会媒体信息，我们希望通过口立方创建的故事能够口口相传，达到最大化传播和影响的效果。"},{"id":9,"type":"comment","content":"ps: 口立方从字面上也可以引申成为很多张嘴， 我们认为web2.0时代每个人都可以成为信息的提供者，每个人都应该有话语权；我们信奉“维基经济学”，我们相信众包的力量，我们认定大家的参与可以让信息更加透明，传播更加迅速，影响更加深远。<br>"},{"id":10,"type":"comment","content":"<b>为什么选择这个logo?</b>"},{"id":11,"type":"comment","content":"最主要的几个原因，简单，好看，识别性高，与网站名字和主题都很契合，选择蓝色是因为这是最能激发创造力的颜色。"},{"id":12,"type":"comment","content":"<b>为什么只提供新浪，腾讯，豆瓣，优酷，又拍这几个信息源，还会增加更多的信息源吗？</b><br>"},{"id":13,"type":"comment","content":"会的，这个已经在我们的开发计划内。现在提供的这几个都是在相关领域最有人气的网站，我们会在以后添加更多的信息源，欢迎大家提供建设性的意见。初步透露一下我们会添加对更多视频网站的支持，添加对RSS的支持，还有更多就留待我们讨论以后再透露啦"},{"id":14,"type":"comment","content":"<b>如何用口立方创建故事？</b>"},{"id":15,"type":"comment","content":"进入创建故事页面，左栏是用来搜寻信息的，点击最左侧的网站图标就能使用相应的信息源。右栏是用来排放信息的，只需要从左栏简单的拖动信息到右栏就可以了，可以拖放到不同的位置来指定信息的排放次序。"},{"id":16,"type":"comment","content":"右栏的“T”图标是用来添加评论和注解的，这篇故事的大部分内容都是点击“T”图标添加的注解。每个故事的标题是必须的，我们建议给故事添加简短的描述和\\n合适的标签；另外还可以通过右栏左上角图片下面的左右箭头来给故事选择一张图片。编辑完毕以后，点击页面右上方的发布按钮就可以了，当然保存草稿和预览也\\n是可以的。"},{"id":17,"type":"comment","content":"<b>为什么有时候搜寻信息的时候要花费比较长的时间？</b>"},{"id":18,"type":"comment","content":"我们使用的是第三方网站提供的API，API的调用时间和第三方网站的服务器状态以及你的网络情况有关，不过通常情况下都是比较迅速的。如果你刷新信息过于频繁，有可能达到第三方网站设定的调用频率上限，这时候可能需要你等一段时间才能继续正常使用该信息源。"},{"id":19,"type":"comment","content":"<b>用口立方创建故事使用了第三方网站的信息源，引用别人的内容违反版权吗？</b>"},{"id":20,"type":"comment","content":"口立方通过第三方网站发布的API或其他公共渠道搜寻信息，我们严格遵守这些网站的API和内容使用条例。你在故事中引用到的内容都会留有原作者的相关信息，以微博为例，引用到的微博会留有原发布者的名称，头像，相关链接等。我们鼓励故事作者发布故事以后知会你引用到的内容源，这可以通过口立方内置的“一键知会”功能完成。"},{"id":21,"type":"comment","content":"<b>发布了一个故事以后我可以做什么？</b>"},{"id":22,"type":"comment","content":"1. 复制嵌入代码粘贴到你的个人博客或网站中，这样通过你的个人博客或网站也能看到这个故事了。<br>2. \\n知会故事中引用到的信息源，比如引用到的微博作者，告诉他们你引用了他们的内容以示感谢。<br>3. \\n将这个故事分享到其他社交媒体网站，让更多的人看到你的故事。<br>上面三个功能都是口立方内置的功能，在你发布了一个故事以后，页面上部就能找到这三个功能，当然你也可以重新编辑已经发布的故事，点击编辑图标就可以了。"},{"id":23,"type":"comment","content":"<b>口立方会存储微博，图片，视频内容吗？</b><br>"},{"id":24,"type":"comment","content":"不会，我们只会存储相关的链接和必要的信息以备口立方能通过API再次拿到这些信息。"}]}', 'Published', '2011-11-14 23:51:47', '2011-11-14 23:51:47', 1),
(5, 2, '2011-11-15 09:45:30', '2011-11-15 09:45:30', 'mOcYdkaKuyau', '源代码影评', '不错的片子，看得出导演还是精心构思过的，结尾也很出人意料，推荐观看', 'http://pic.yupoo.com/mattchun/BdTkBxLt/square', '{"content":[{"id":0,"type":"photo","content":{"id":"81882074","title":"源代码/Source Code","author":"mattchun","nic":"马特陈","url":"http://pic.yupoo.com/mattchun/BdTkBxLt/small"}},{"id":1,"type":"comment","content":"高清预告片"},{"id":2,"type":"video","content":{"title":"源代码","src":"http://player.youku.com/player.php/sid/XMzA5OTg1NDI0/v.swf","url":"http://v.youku.com/v_show/id_XMzA5OTg1NDI0.html"}},{"id":3,"type":"comment","content":"源代码男主角采访"},{"id":4,"type":"video","content":{"title":"源代码","src":"http://player.youku.com/player.php/sid/XMjk4NTE4MzQ0/v.swf","url":"http://v.youku.com/v_show/id_XMjk4NTE4MzQ0.html"}},{"id":5,"type":"comment","content":"豆瓣平均得分8.4， 还是蛮高的<br>"},{"id":6,"type":"comment","content":"下面来看看豆瓣网友提供的影评，有些会有剧透，慎看！"},{"id":7,"type":"douban","content":{"item_type":"movieReviews","item_id":"5009619"}},{"id":8,"type":"douban","content":{"item_type":"movieReviews","item_id":"4986759"}},{"id":9,"type":"douban","content":{"item_type":"movieReviews","item_id":"4916498"}}]}', 'Published', '2011-11-15 09:45:30', '2011-11-15 09:45:30', 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `story_reset`
--

INSERT INTO `story_reset` (`id`, `username`, `email`, `reset_code`) VALUES
(1, '张辛欣', '11473124@qq.com', 'ji8yulhl'),
(2, '金奂', 'crazyscar@gmail.com', 'ieu9kl9a'),
(3, '口立方', 'support@koulifang.com', 'erinmc76'),
(4, '源源', 'yuan0320@gmail.com', 'n67chus0');

-- --------------------------------------------------------

--
-- 表的结构 `story_tag`
--

CREATE TABLE IF NOT EXISTS `story_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `story_tag`
--

INSERT INTO `story_tag` (`id`, `name`) VALUES
(1, '北京'),
(2, '空气质量'),
(3, '西安'),
(4, '爆炸'),
(5, '口立方'),
(6, '常见问题'),
(7, '源代码');

-- --------------------------------------------------------

--
-- 表的结构 `story_tag_story`
--

CREATE TABLE IF NOT EXISTS `story_tag_story` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `story_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `story_tag_story`
--

INSERT INTO `story_tag_story` (`id`, `tag_id`, `story_id`) VALUES
(1, 1, 2),
(2, 2, 2),
(3, 3, 3),
(4, 4, 3),
(5, 5, 4),
(6, 6, 4),
(7, 7, 5);

-- --------------------------------------------------------

--
-- 表的结构 `story_user`
--

CREATE TABLE IF NOT EXISTS `story_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '',
  `passwd` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `personal_domain` varchar(60) NOT NULL DEFAULT '',
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

INSERT INTO `story_user` (`id`, `username`, `passwd`, `email`, `personal_domain`, `photo`, `intro`, `weibo_user_id`, `weibo_access_token`, `weibo_access_token_secret`, `tweibo_user_id`, `tweibo_access_token`, `tweibo_access_token_secret`, `douban_user_id`, `douban_access_token`, `douban_access_token_secret`, `yupoo_token`, `registered_time`, `activate`) VALUES
(1, '张辛欣', '7c4a8d09ca3762af61e59520943dc26494f8941b', '11473124@qq.com', '', 'http://tp2.sinaimg.cn/1743548661/50/1275119822/1', '', 1743548661, '3dded3c1a69e0e24609b04c3bc07d3ee', '4815f86a2f8dcbbca4a307535b1a82d8', 0, '1fce15f8b9d3449ea9a031adf9138f95', '2a4a03d0dac0951f06d3e7b5b30a1ea0', 0, '703ee8accc61bfbb50bcba0665253f31', 'fe112285794b5ed2', 'e44dc7cf407d6cd0b2276590ffd6f975', '0000-00-00 00:00:00', 1),
(2, '金奂', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'crazyscar@gmail.com', '', NULL, '', 0, '', '', 0, '', '', 0, '', '', '', '0000-00-00 00:00:00', 1),
(3, '口立方', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'support@koulifang.com', '', 'http://tp1.sinaimg.cn/2329577672/50/5616253127/1', '', 2329577672, '9a0db78eaffe82ee099f17c8937f29cf', '0175d039c755cc3b128c134f30b9af3c', 0, '', '', 0, '', '', '', '0000-00-00 00:00:00', 1),
(4, '源源', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'yuan0320@gmail.com', '', 'http://tp4.sinaimg.cn/2008327691/50/5612607308/0', '', 2008327691, '02feef188f733ac59b5a16f9200033f7', '35789d02288c6b2617ad2e0f2accfc0b', 0, '', '', 0, '', '', '', '0000-00-00 00:00:00', 1);
