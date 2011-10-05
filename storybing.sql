-- MySQL dump 10.13  Distrib 5.5.15, for Linux (i686)
--
-- Host: localhost    Database: storybing
-- ------------------------------------------------------
-- Server version	5.5.15-log

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_follow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `follow_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_icode` (
  `ic_index` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ic_code` char(6) NOT NULL,
  `ic_email` varchar(50) DEFAULT NULL,
  `ic_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ic_index`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
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
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_posts`
--

LOCK TABLES `story_posts` WRITE;
/*!40000 ALTER TABLE `story_posts` DISABLE KEYS */;
INSERT INTO `story_posts` VALUES (58,8,'2011-10-05 20:25:09','2011-10-05 20:25:09','test8.story1','test8.story1','http://tp2.sinaimg.cn/1639356433/180/1282876307/1','{\"content\":[{\"id\":0,\"type\":\"weibo\",\"content\":{\"id\":\"3365232119358307\",\"nic\":\"宅男宅女爱冷笑话\",\"uid\":\"1639356433\"}},{\"id\":1,\"type\":\"weibo\",\"content\":{\"id\":\"3365232063989233\",\"nic\":\"头条新闻\",\"uid\":\"1618051664\"}}]}','Published','2011-10-05 20:25:09','2011-10-05 20:25:09',3),(59,8,'2011-10-05 20:33:22','2011-10-05 20:33:22','test8.story2','test8.story2','http://app.qlogo.cn/mbloghead/5e7ba5c4dab5d55dacca/180','{\"content\":[{\"id\":0,\"type\":\"tweibo\",\"content\":{\"id\":\"71072019693447\",\"nic\":\"腾讯薇薇\",\"name\":\"t\"}},{\"id\":1,\"type\":\"tweibo\",\"content\":{\"id\":\"44085002483588\",\"nic\":\"微博精灵\",\"name\":\"QQGenius\"}}]}','Published','2011-10-05 20:33:22','2011-10-05 20:33:22',0),(60,7,'2011-10-05 20:45:29','2011-10-05 20:45:29','test7.story1','test7.story1','http://app.qlogo.cn/mbloghead/c39e95b85b1b6bcd6f84/180','{\"content\":[{\"id\":0,\"type\":\"tweibo\",\"content\":{\"id\":\"668060163072\",\"nic\":\"微博精灵\",\"name\":\"QQGenius\"}},{\"id\":1,\"type\":\"tweibo\",\"content\":{\"id\":\"5174085651397\",\"nic\":\"微博精灵\",\"name\":\"QQGenius\"}}]}','Published','2011-10-05 20:45:29','2011-10-05 20:45:29',0),(61,6,'2011-10-05 23:03:18','2011-10-05 23:03:18','test6.story1.sina','test6.story1','http://tp2.sinaimg.cn/1195403385/180/5608638792/1','{\"content\":[{\"id\":0,\"type\":\"weibo\",\"content\":{\"id\":\"3365272415196471\",\"nic\":\"方舟子\",\"uid\":\"1195403385\"}},{\"id\":1,\"type\":\"weibo\",\"content\":{\"id\":\"3365272016677780\",\"nic\":\"全球经典音乐\",\"uid\":\"1920061532\"}}]}','Published','2011-10-05 23:03:18','2011-10-05 23:03:18',0);
/*!40000 ALTER TABLE `story_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_publictoken`
--

DROP TABLE IF EXISTS `story_publictoken`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_publictoken` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `weibo_access_token` varchar(100) NOT NULL DEFAULT '',
  `weibo_access_token_secret` varchar(100) NOT NULL DEFAULT '',
  `tweibo_access_token` varchar(100) NOT NULL DEFAULT '',
  `tweibo_access_token_secret` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_tag`
--

LOCK TABLES `story_tag` WRITE;
/*!40000 ALTER TABLE `story_tag` DISABLE KEYS */;
INSERT INTO `story_tag` VALUES (116,'宅'),(117,'冷笑话'),(118,'动画'),(119,'微博'),(120,'新闻'),(121,'微薄'),(122,'故事'),(123,'恭喜'),(124,'诺贝尔'),(125,'怀念');
/*!40000 ALTER TABLE `story_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_tag_story`
--

DROP TABLE IF EXISTS `story_tag_story`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_tag_story` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `story_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_tag_story`
--

LOCK TABLES `story_tag_story` WRITE;
/*!40000 ALTER TABLE `story_tag_story` DISABLE KEYS */;
INSERT INTO `story_tag_story` VALUES (124,116,58),(125,117,58),(126,118,58),(127,118,59),(128,119,59),(129,120,59),(130,121,60),(131,122,60),(132,123,60),(133,122,61),(134,124,61),(135,125,61);
/*!40000 ALTER TABLE `story_tag_story` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_user`
--

DROP TABLE IF EXISTS `story_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `story_user` (
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_user`
--

LOCK TABLES `story_user` WRITE;
/*!40000 ALTER TABLE `story_user` DISABLE KEYS */;
INSERT INTO `story_user` VALUES (1,'张辛欣','e10adc3949ba59abbe56e057f20f883e','xinxinzhang22@gmail.com',NULL,'',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',0),(2,'源源','e10adc3949ba59abbe56e057f20f883e','yuan0320@gmail.com',NULL,'',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',0),(3,'test3','e10adc3949ba59abbe56e057f20f883e','test3@gmail.com',NULL,'',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',0),(4,'test4','e10adc3949ba59abbe56e057f20f883e','test4@gmail.com',NULL,'',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',0),(5,'test5','e10adc3949ba59abbe56e057f20f883e','test5@gmail.com',NULL,'',0,'','',0,'','',0,'','','','0000-00-00 00:00:00',0),(6,'test6','e10adc3949ba59abbe56e057f20f883e','test6@gmail.com','http://tp2.sinaimg.cn/1734674501/50/5609749063/1','info for test6',1734674501,'843fb71b921f8cea5db7385ee2e9a23c','fbb92674da03e9bf6a70a98efa3b2f0f',0,'','',0,'','','','0000-00-00 00:00:00',1),(7,'test7','e10adc3949ba59abbe56e057f20f883e','test7@gmail.com','/img/user/dNZDRI7.jpg','info for test7',0,'','',0,'820fbe9bc1bf4a5cb25b0806b0a01a73','cea1a48f54748b202bf9de6914fd11f8',0,'','','','0000-00-00 00:00:00',1),(8,'test8','e10adc3949ba59abbe56e057f20f883e','test8@gmail.com','/img/user/yqCEXP8.jpg','info for test8',1734674501,'843fb71b921f8cea5db7385ee2e9a23c','fbb92674da03e9bf6a70a98efa3b2f0f',0,'820fbe9bc1bf4a5cb25b0806b0a01a73','cea1a48f54748b202bf9de6914fd11f8',0,'','','','0000-00-00 00:00:00',1);
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

-- Dump completed on 2011-10-06  0:18:05
