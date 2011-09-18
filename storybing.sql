-- MySQL dump 10.11
--
-- Host: localhost    Database: storybing
-- ------------------------------------------------------
-- Server version	5.0.77

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `story_follow`
--

DROP TABLE IF EXISTS `story_follow`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `story_follow` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned default NULL,
  `follow_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `story_follow`
--

LOCK TABLES `story_follow` WRITE;
/*!40000 ALTER TABLE `story_follow` DISABLE KEYS */;
INSERT INTO `story_follow` VALUES (12,6,8),(11,7,8),(17,8,6),(14,8,7),(15,6,7),(16,7,6);
/*!40000 ALTER TABLE `story_follow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_icode`
--

DROP TABLE IF EXISTS `story_icode`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `story_icode` (
  `ic_index` int(10) unsigned NOT NULL auto_increment,
  `ic_code` char(6) NOT NULL,
  `ic_email` varchar(50) default NULL,
  `ic_time` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`ic_index`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `story_icode`
--

LOCK TABLES `story_icode` WRITE;
/*!40000 ALTER TABLE `story_icode` DISABLE KEYS */;
INSERT INTO `story_icode` VALUES (1,'6fK0kA','crazyscar@gmail.com',1312654410),(2,'7PPco0',NULL,0),(3,'uxkK3O',NULL,0),(4,'Ve5i31',NULL,0);
/*!40000 ALTER TABLE `story_icode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_posts`
--

DROP TABLE IF EXISTS `story_posts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `story_posts` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `post_author` bigint(20) unsigned NOT NULL default '0',
  `post_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL default '0000-00-00 00:00:00',
  `post_title` varchar(120) NOT NULL,
  `post_summary` text NOT NULL,
  `post_pic_url` varchar(200) NOT NULL,
  `post_content` text NOT NULL,
  `post_status` varchar(20) NOT NULL default 'draft',
  `post_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL default '0000-00-00 00:00:00',
  `post_digg_count` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `post_title` (`post_title`),
  KEY `status_date` (`post_status`,`post_date`,`ID`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `story_posts`
--

LOCK TABLES `story_posts` WRITE;
/*!40000 ALTER TABLE `story_posts` DISABLE KEYS */;
INSERT INTO `story_posts` VALUES (4,8,'2011-08-29 23:47:07','2011-08-29 23:47:07','test8.story1','test8.story1','http://tp2.sinaimg.cn/1764222885/180/5608794508/0','{\"content\":[{\"id\":0,\"type\":\"weibo\",\"content\":\"3351875177824154\"}]}','Published','2011-08-29 23:47:07','2011-08-29 23:47:07',1),(5,6,'2011-08-30 23:36:36','2011-08-30 23:36:36','test6.story2','test6.story2','http://tp2.sinaimg.cn/1256177501/180/5605957551/1','{\"content\":[{\"id\":0,\"type\":\"weibo\",\"content\":\"3352235234756197\"}]}','Published','2011-08-30 23:36:36','2011-08-30 23:36:36',2),(6,6,'2011-08-31 00:00:33','2011-08-31 00:00:33','test6.story1','test6.story1','http://tp1.sinaimg.cn/1857881352/180/1289985124/0','{\"content\":[{\"id\":0,\"type\":\"weibo\",\"content\":\"3352241210211836\"}]}','Published','2011-08-31 00:00:33','2011-08-31 00:00:33',1),(7,7,'2011-08-31 00:06:27','2011-08-31 00:06:27','test7.story1','test7.story1','http://tp2.sinaimg.cn/1290099433/180/1279876935/1','{\"content\":[{\"id\":0,\"type\":\"weibo\",\"content\":\"3352242338524983\"}]}','Published','2011-08-31 00:06:27','2011-08-31 00:06:27',3),(9,7,'2011-09-04 20:34:22','2011-09-04 20:34:22','test7.story2','test7.story2','http://tp2.sinaimg.cn/1734674501/180/5609749063/1','{\"content\":[{\"id\":0,\"type\":\"weibo\",\"content\":\"3353882122559224\"},{\"id\":1,\"type\":\"weibo\",\"content\":\"3354001379635337\"}]}','Published','2011-09-04 20:34:22','2011-09-04 20:34:22',0),(44,8,'2011-09-07 23:30:46','2011-09-07 23:30:46','test8.story2','test8.story2','http://tp4.sinaimg.cn/1191965271/180/1297483984/1','{\"content\":[{\"id\":0,\"type\":\"weibo\",\"content\":\"3355132631116047\"}]}','Published','2011-09-07 23:30:46','2011-09-07 23:30:46',0),(54,8,'2011-09-12 21:23:08','2011-09-12 21:23:08','test8.tweibo.remove','test8.tweibo.remote','http://app.qlogo.cn/mbloghead/63685a47cf21cf90489a/180','{\"content\":[{\"id\":0,\"type\":\"tweibo\",\"content\":\"30630071094244\"},{\"id\":1,\"type\":\"tweibo\",\"content\":\"14161128716654\"}]}','Published','2011-09-12 21:23:08','2011-09-12 21:23:08',0);
/*!40000 ALTER TABLE `story_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_publictoken`
--

DROP TABLE IF EXISTS `story_publictoken`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `story_publictoken` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `weibo_access_token` varchar(100) NOT NULL default '',
  `weibo_access_token_secret` varchar(100) NOT NULL default '',
  `tweibo_access_token` varchar(100) NOT NULL default '',
  `tweibo_access_token_secret` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `story_publictoken`
--

LOCK TABLES `story_publictoken` WRITE;
/*!40000 ALTER TABLE `story_publictoken` DISABLE KEYS */;
INSERT INTO `story_publictoken` VALUES (1,'3dded3c1a69e0e24609b04c3bc07d3ee','4815f86a2f8dcbbca4a307535b1a82d8','1fce15f8b9d3449ea9a031adf9138f95','2a4a03d0dac0951f06d3e7b5b30a1ea0');
/*!40000 ALTER TABLE `story_publictoken` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_tag`
--

DROP TABLE IF EXISTS `story_tag`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `story_tag` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `story_tag`
--

LOCK TABLES `story_tag` WRITE;
/*!40000 ALTER TABLE `story_tag` DISABLE KEYS */;
INSERT INTO `story_tag` VALUES (46,'王小寒'),(48,'关注'),(49,'新闻'),(50,'新浪'),(51,'微博'),(52,'鲁迅'),(53,'南京'),(54,'南方周末'),(80,'我们'),(81,'中国'),(82,'豆瓣'),(102,'remove'),(103,'delete'),(104,'chaitin');
/*!40000 ALTER TABLE `story_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_tag_story`
--

DROP TABLE IF EXISTS `story_tag_story`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `story_tag_story` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `tag_id` bigint(20) unsigned NOT NULL default '0',
  `story_id` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `story_tag_story`
--

LOCK TABLES `story_tag_story` WRITE;
/*!40000 ALTER TABLE `story_tag_story` DISABLE KEYS */;
INSERT INTO `story_tag_story` VALUES (43,46,5),(44,48,6),(45,49,6),(46,50,6),(47,51,6),(49,49,5),(50,53,7),(51,54,7),(52,50,7),(53,49,7),(55,56,9),(78,80,44),(79,81,44),(80,50,44),(81,51,44),(82,82,44),(108,102,54),(109,103,54),(110,104,54);
/*!40000 ALTER TABLE `story_tag_story` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_user`
--

DROP TABLE IF EXISTS `story_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `story_user` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(60) NOT NULL default '',
  `passwd` varchar(64) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `photo` varchar(255) default NULL,
  `intro` varchar(255) NOT NULL default '',
  `weibo_user_id` bigint(20) unsigned NOT NULL default '0',
  `weibo_access_token` varchar(100) NOT NULL default '',
  `weibo_access_token_secret` varchar(100) NOT NULL default '',
  `tweibo_user_id` bigint(20) unsigned NOT NULL default '0',
  `tweibo_access_token` varchar(100) NOT NULL default '',
  `tweibo_access_token_secret` varchar(100) NOT NULL default '',
  `douban_user_id` bigint(20) unsigned NOT NULL default '0',
  `douban_access_token` varchar(100) NOT NULL default '',
  `douban_access_token_secret` varchar(100) NOT NULL default '',
  `yupoo_token` varchar(100) NOT NULL default '',
  `registered_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `activate` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user_name_key` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `story_user`
--

LOCK TABLES `story_user` WRITE;
/*!40000 ALTER TABLE `story_user` DISABLE KEYS */;
INSERT INTO `story_user` VALUES (1,'张辛欣','e10adc3949ba59abbe56e057f20f883e','xinxinzhang22@gmail.com',NULL,'',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',0),(2,'源源','e10adc3949ba59abbe56e057f20f883e','yuan0320@gmail.com',NULL,'',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',0),(3,'test3','e10adc3949ba59abbe56e057f20f883e','test3@gmail.com',NULL,'',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',0),(4,'test4','e10adc3949ba59abbe56e057f20f883e','test4@gmail.com',NULL,'',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',0),(5,'test5','e10adc3949ba59abbe56e057f20f883e','test5@gmail.com',NULL,'',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',0),(6,'test6','e10adc3949ba59abbe56e057f20f883e','test6@gmail.com','http://tp2.sinaimg.cn/1734674501/50/1292853975/1','info for test6',1734674501,'843fb71b921f8cea5db7385ee2e9a23c','fbb92674da03e9bf6a70a98efa3b2f0f',0,'','',0,'','','','0000-00-00 00:00:00',1),(7,'test7','e10adc3949ba59abbe56e057f20f883e','test7@gmail.com','/storify/img/user/dNZDRI7.jpg','info for test7',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',1),(8,'test8','e10adc3949ba59abbe56e057f20f883e','test8@gmail.com','/storify/img/user/yqCEXP8.jpg','info for test8',1734674501,'843fb71b921f8cea5db7385ee2e9a23c','fbb92674da03e9bf6a70a98efa3b2f0f',0,'820fbe9bc1bf4a5cb25b0806b0a01a73','cea1a48f54748b202bf9de6914fd11f8',0,'','','','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `story_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-09-18 11:26:42
