-- MySQL dump 10.13  Distrib 5.7.24, for Win64 (x86_64)
--
-- Host: 192.168.10.10    Database: flexcms
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

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
-- Table structure for table `activations`
--

DROP TABLE IF EXISTS `activations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_activations_user_id_idx` (`user_id`),
  CONSTRAINT `fk_activations_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activations`
--

LOCK TABLES `activations` WRITE;
/*!40000 ALTER TABLE `activations` DISABLE KEYS */;
INSERT INTO `activations` VALUES (6,1,'tN8O7WyAbtLjOG1qqxigRtMh12EWMo0i',1,'2015-11-26 19:30:55','2015-11-26 19:30:55','2015-11-26 19:30:55');
/*!40000 ALTER TABLE `activations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `adverts`
--

DROP TABLE IF EXISTS `adverts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adverts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `widget_id` int(11) DEFAULT NULL,
  `categories` varchar(255) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `css_class` varchar(45) DEFAULT NULL,
  `file1` varchar(45) DEFAULT NULL,
  `file2` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `publicidadTipoId_fk_idx` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adverts`
--

LOCK TABLES `adverts` WRITE;
/*!40000 ALTER TABLE `adverts` DISABLE KEYS */;
INSERT INTO `adverts` VALUES (18,'3',NULL,'[\"163\",\"164\",\"165\",\"166\",\"172\"]','Multiple','2015-07-16 11:36:46','2015-08-16 11:36:46',1,'','',NULL),(19,'1',45,'null','Normal','2015-07-16 11:39:25','2015-08-16 11:39:25',1,'','',NULL),(20,'2',68,'null','asd','2015-07-16 11:50:56','2015-08-16 11:50:56',1,'','','');
/*!40000 ALTER TABLE `adverts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar`
--

DROP TABLE IF EXISTS `calendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `enabled` tinyint(1) DEFAULT '1',
  `date` date NOT NULL,
  `temporary` tinyint(1) DEFAULT '1',
  `css_class` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar`
--

LOCK TABLES `calendar` WRITE;
/*!40000 ALTER TABLE `calendar` DISABLE KEYS */;
INSERT INTO `calendar` VALUES (1,1,'2015-04-10',1,NULL),(2,1,'2015-07-24',1,NULL),(4,1,'2015-07-24',1,NULL),(5,1,'1969-12-31',0,'');
/*!40000 ALTER TABLE `calendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_activities`
--

DROP TABLE IF EXISTS `calendar_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` time NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT NULL,
  `temporary` tinyint(1) DEFAULT '1',
  `enabled` tinyint(1) DEFAULT '1',
  `data` mediumtext COMMENT 'temporary field untill I finish translations and dynamic fields',
  PRIMARY KEY (`id`),
  KEY `fk_calendar_id` (`calendar_id`),
  KEY `fk_activities_placeId_idx` (`place_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar_activities`
--

LOCK TABLES `calendar_activities` WRITE;
/*!40000 ALTER TABLE `calendar_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `order` int(10) unsigned DEFAULT NULL,
  `css_class` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `private` tinyint(1) DEFAULT '0',
  `image` varchar(45) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `data` mediumtext,
  `temporary` tinyint(1) DEFAULT '1',
  `popup` tinyint(1) DEFAULT NULL,
  `type` varchar(45) NOT NULL,
  `is_content` tinyint(1) DEFAULT NULL,
  `content_type` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` tinyint(1) DEFAULT NULL,
  `group_visibility` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,0,1,16,NULL,NULL,1,0,NULL,NULL,NULL,1,NULL,'root',NULL,NULL,'2017-04-05 20:25:35','2017-04-27 21:50:24',NULL,NULL),(2,1,1,2,3,2,NULL,1,0,NULL,NULL,'{\"structure\":[{\"class\":\"\",\"columns\":[{\"class\":\"\",\"span\":{\"large\":12,\"medium\":12,\"small\":12},\"offset\":{\"large\":0,\"medium\":0,\"small\":0},\"push\":{\"large\":0,\"medium\":0,\"small\":0},\"pull\":{\"large\":0,\"medium\":0,\"small\":0},\"widgets\":[43]}],\"expanded\":false},{\"class\":\"\",\"columns\":[{\"class\":\"\",\"span\":{\"large\":6,\"medium\":6,\"small\":6},\"offset\":{\"large\":0,\"medium\":0,\"small\":0},\"push\":{\"large\":0,\"medium\":0,\"small\":0},\"pull\":{\"large\":0,\"medium\":0,\"small\":0},\"widgets\":[]},{\"class\":\"\",\"span\":{\"large\":6,\"medium\":6,\"small\":6},\"offset\":{\"large\":0,\"medium\":0,\"small\":0},\"push\":{\"large\":0,\"medium\":0,\"small\":0},\"pull\":{\"large\":0,\"medium\":0,\"small\":0},\"widgets\":[]}],\"expanded\":false}]}',1,0,'page',1,'auth','2017-04-12 18:06:41','2017-04-27 21:46:55',NULL,3),(7,1,1,8,9,3,NULL,1,0,NULL,NULL,'{\"structure\":[{\"class\":\"\",\"columns\":[{\"class\":\"\",\"span\":{\"large\":12,\"medium\":12,\"small\":12},\"offset\":{\"large\":0,\"medium\":0,\"small\":0},\"push\":{\"large\":0,\"medium\":0,\"small\":0},\"pull\":{\"large\":0,\"medium\":0,\"small\":0},\"widgets\":[47]}],\"expanded\":false}]}',1,0,'page',1,'catalog','2017-04-13 16:26:19','2017-04-27 21:46:55',NULL,NULL),(8,1,1,4,5,4,NULL,1,0,NULL,NULL,'{\"structure\":[{\"class\":\"\",\"columns\":[{\"class\":\"\",\"span\":{\"large\":12,\"medium\":12,\"small\":12},\"offset\":{\"large\":0,\"medium\":0,\"small\":0},\"push\":{\"large\":0,\"medium\":0,\"small\":0},\"pull\":{\"large\":0,\"medium\":0,\"small\":0},\"widgets\":[52]}],\"expanded\":false}]}',1,0,'page',1,'calendar','2017-04-13 16:26:31','2017-04-27 21:46:56',NULL,NULL),(9,1,1,10,11,1,NULL,1,0,NULL,NULL,'{\"structure\":[{\"class\":\"\",\"columns\":[{\"class\":\"\",\"span\":{\"large\":8,\"medium\":8,\"small\":6},\"offset\":{\"large\":0,\"medium\":0,\"small\":0},\"push\":{\"large\":0,\"medium\":0,\"small\":0},\"pull\":{\"large\":0,\"medium\":0,\"small\":0},\"widgets\":[54]},{\"class\":\"\",\"span\":{\"large\":4,\"medium\":4,\"small\":6},\"offset\":{\"large\":0,\"medium\":0,\"small\":0},\"push\":{\"large\":0,\"medium\":0,\"small\":0},\"pull\":{\"large\":0,\"medium\":0,\"small\":0},\"widgets\":[]}],\"expanded\":false}]}',1,0,'page',0,NULL,'2017-04-25 22:03:50','2019-12-20 21:16:16',NULL,NULL),(10,1,1,6,7,5,NULL,1,0,NULL,NULL,'{\"structure\":[{\"class\":\"\",\"columns\":[{\"class\":\"\",\"span\":{\"large\":12,\"medium\":12,\"small\":12},\"offset\":{\"large\":0,\"medium\":0,\"small\":0},\"push\":{\"large\":0,\"medium\":0,\"small\":0},\"pull\":{\"large\":0,\"medium\":0,\"small\":0},\"widgets\":[55]}],\"expanded\":false}]}',1,0,'page',1,'faq','2017-04-25 22:09:28','2017-04-27 21:46:56',NULL,NULL),(11,1,1,12,13,6,NULL,1,0,NULL,NULL,'{\"structure\":[{\"class\":\"\",\"columns\":[{\"class\":\"\",\"span\":{\"large\":12,\"medium\":12,\"small\":12},\"offset\":{\"large\":0,\"medium\":0,\"small\":0},\"push\":{\"large\":0,\"medium\":0,\"small\":0},\"pull\":{\"large\":0,\"medium\":0,\"small\":0},\"widgets\":[56]}],\"expanded\":false}]}',1,0,'page',1,'content','2017-04-26 20:27:58','2017-04-27 21:46:56',NULL,3),(15,1,1,14,15,NULL,NULL,1,0,NULL,NULL,'{\"structure\":[{\"class\":\"\",\"columns\":[{\"class\":\"\",\"span\":{\"large\":12,\"medium\":12,\"small\":12},\"offset\":{\"large\":0,\"medium\":0,\"small\":0},\"push\":{\"large\":0,\"medium\":0,\"small\":0},\"pull\":{\"large\":0,\"medium\":0,\"small\":0},\"widgets\":[68]}],\"expanded\":false}]}',1,NULL,'page',0,NULL,'2017-04-27 21:50:24','2019-12-16 12:59:56',NULL,NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  `prevent_update` int(10) DEFAULT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(45) NOT NULL,
  `value` varchar(255) NOT NULL,
  `group` varchar(45) DEFAULT 'general',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,'site_name','FlexCMS','general','2016-07-27 21:44:56'),(2,'index_page_id','23','general','2016-12-27 21:31:04'),(3,'theme','destiny','general','2017-01-20 01:39:40'),(4,'environment','development','general','2017-01-19 23:35:51'),(5,'debug_bar','false','general','2017-01-19 23:31:36'),(9,'indent_html','false','general','2017-01-19 23:35:51'),(10,'automatic_activation','1','auth','2016-07-28 02:45:30'),(11,'login_identity','email','auth','2016-07-27 21:44:56'),(12,'password_min_length','1','auth','2016-07-28 02:45:37'),(13,'password_max_length','180','auth','2016-07-28 02:45:37'),(14,'registered_role','registered','auth','2016-07-28 02:45:30'),(15,'facebook_login','0','auth','2016-07-29 03:55:44'),(16,'facebook_app_id','','auth','2016-07-28 22:53:22'),(17,'facebook_app_secret','','auth','2016-07-28 22:53:22'),(18,'twitter_login','0','auth','2016-07-29 03:55:44'),(19,'twitter_consumer_key','','auth','2016-07-28 22:41:29'),(20,'twitter_consumer_secret','','auth','2016-07-28 22:41:29'),(21,'menu_show_categories','1','catalog','2016-09-15 07:36:17'),(22,'menu_show_products','1','catalog','2016-09-15 07:36:17');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `css_class` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `temporary` tinyint(1) DEFAULT '1',
  `important` tinyint(1) DEFAULT '0',
  `timezone` varchar(45) DEFAULT NULL,
  `publication_start` datetime DEFAULT NULL,
  `publication_end` datetime DEFAULT NULL,
  `module` varchar(45) DEFAULT NULL,
  `data` varchar(45) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paginaId` (`category_id`),
  KEY `publicacionHabilitado` (`enabled`),
  CONSTRAINT `fk_category_id_content` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content`
--

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` VALUES (1,'',2,1,1,0,'America/Bogota',NULL,NULL,NULL,NULL,2,'2017-04-25 01:22:19','2017-04-25 01:24:23',NULL),(3,'',2,1,1,0,'America/Bogota','1970-01-01 00:00:00','1970-01-01 00:00:00',NULL,NULL,1,'2017-04-25 01:22:54','2017-04-25 02:06:31',NULL),(4,'',9,1,1,0,'America/Bogota','2019-09-27 00:00:00','2019-09-27 00:00:00',NULL,NULL,NULL,'2019-12-16 15:46:36','2019-12-20 20:56:47',NULL);
/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `field_data`
--

DROP TABLE IF EXISTS `field_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `field_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `field_id` int(11) NOT NULL,
  `section` varchar(45) NOT NULL,
  `data` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `key_unique_field_data` (`parent_id`,`field_id`,`section`),
  KEY `fk_field_data_field_id_idx` (`field_id`),
  CONSTRAINT `fk_field_data_field_id` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `field_data`
--

LOCK TABLES `field_data` WRITE;
/*!40000 ALTER TABLE `field_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `field_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fields`
--

DROP TABLE IF EXISTS `fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `input_id` int(11) NOT NULL,
  `parent_id` varchar(45) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `css_class` varchar(45) DEFAULT NULL,
  `section` varchar(45) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `label_enabled` tinyint(1) DEFAULT NULL,
  `required` tinyint(1) DEFAULT NULL,
  `validation` varchar(45) DEFAULT NULL,
  `data` tinytext,
  `view_in` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campoId` (`input_id`),
  KEY `inputId_bc_idx` (`input_id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fields`
--

LOCK TABLES `fields` WRITE;
/*!40000 ALTER TABLE `fields` DISABLE KEYS */;
INSERT INTO `fields` VALUES (15,13,'1',2,NULL,'form',NULL,1,1,'integer',NULL,NULL,1,'2017-01-13 21:18:42','2017-04-11 16:21:41',NULL),(16,13,'1',3,NULL,'form',NULL,1,1,'email',NULL,NULL,1,'2017-01-13 22:23:19','2017-04-11 16:21:41',NULL),(17,11,'1',4,NULL,'form',NULL,0,1,NULL,NULL,NULL,1,'2017-01-16 19:04:42','2017-04-11 16:21:41',NULL),(19,13,'11',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-01-17 17:03:58','2017-01-17 17:03:58',NULL),(21,13,'13',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-01-17 17:09:27','2017-01-17 17:09:27',NULL),(23,13,'14',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-01-17 17:09:57','2017-01-17 17:09:57',NULL),(24,13,'14',3,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-01-17 17:09:58','2017-01-17 17:09:58',NULL),(26,13,'15',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-01-17 20:58:47','2017-04-11 21:51:51',NULL),(27,13,'15',3,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-01-17 20:58:47','2017-04-11 21:51:52',NULL),(37,43,'1',1,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-01-18 16:34:22','2017-04-11 16:21:41',NULL),(38,13,'1',5,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 16:21:16','2017-04-11 16:21:16',NULL),(39,13,'1',5,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 16:21:41','2017-04-11 16:21:41',NULL),(41,13,'16',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 18:47:25','2017-04-11 18:47:25',NULL),(43,13,'17',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 18:49:15','2017-04-11 18:49:15',NULL),(44,13,'17',3,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 18:49:15','2017-04-11 18:49:15',NULL),(46,13,'18',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 18:49:57','2017-04-11 18:49:57',NULL),(47,13,'18',3,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 18:49:58','2017-04-11 18:49:58',NULL),(49,13,'21',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 18:58:29','2017-04-11 18:58:29',NULL),(50,13,'21',3,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 18:58:30','2017-04-11 18:58:30',NULL),(51,13,'21',4,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 18:58:30','2017-04-11 18:58:30',NULL),(53,13,'24',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 19:03:23','2017-04-11 19:03:23',NULL),(57,13,'22',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(58,13,'22',3,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(59,13,'22',4,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(60,13,'22',5,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(61,13,'22',6,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(62,13,'22',7,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(63,13,'22',8,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(64,13,'22',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(65,13,'22',3,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(66,13,'22',4,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(67,13,'22',5,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(68,13,'22',6,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(69,13,'22',7,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(70,13,'22',8,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(71,13,'22',9,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(72,13,'22',10,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(73,13,'22',11,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(75,13,'25',2,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:42:57','2017-04-11 21:42:57',NULL),(76,13,'15',1,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:43:25','2017-04-11 21:51:51',NULL),(77,13,'15',4,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:43:25','2017-04-11 21:51:52',NULL),(78,13,'15',5,NULL,'form',NULL,0,0,NULL,NULL,NULL,1,'2017-04-11 21:43:25','2017-04-11 21:51:52',NULL);
/*!40000 ALTER TABLE `fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `section_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `position` int(11) DEFAULT '0',
  `data` json DEFAULT NULL,
  `variants` json DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `type` varchar(10) DEFAULT NULL,
  `mime_type` varchar(45) DEFAULT NULL,
  `file_ext` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `descargaCategoriaId_d` (`parent_id`),
  KEY `descargaCategoriaId_idx` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` VALUES (37,4,1,'500',1,'{\"colors\": {\"textColor\": \"dark\", \"paletteColor\": [[204, 204, 204], [152, 152, 152], [172, 172, 172], [180, 180, 180], [196, 196, 196], [188, 188, 188], [200, 204, 208], [200, 204, 208], [200, 204, 208]], \"dominantColor\": [203, 203, 203]}, \"coords\": {\"cropTop\": 229, \"cropLeft\": 221, \"cropWidth\": 200, \"areaCoords\": {\"h\": 150, \"w\": 200, \"x\": 221, \"y\": 229}, \"canvasSize\": {\"h\": 450, \"w\": 450}, \"cropHeight\": 150, \"cropImageTop\": 254, \"cropImageLeft\": 246, \"cropImageWidth\": 222, \"cropImageHeight\": 167}, \"image_alt\": \"image\"}','{\"_large\": {\"url\": \"assets/images/content/4/500_large.jpg?1576875407\", \"width\": 800, \"height\": 602}, \"_small\": {\"url\": \"assets/images/content/4/500_small.jpg?1576875407\", \"width\": 100, \"height\": 75}, \"_medium\": {\"url\": \"assets/images/content/4/500_medium.jpg?1576875407\", \"width\": 400, \"height\": 301}}',NULL,NULL,1,'image','image/png','png','2019-12-18 13:25:48','2019-12-20 20:56:47',NULL),(55,4,2,'500',2,'{\"colors\": {\"textColor\": \"dark\", \"paletteColor\": [[204, 204, 204], [149, 149, 149], [164, 164, 164], [172, 172, 172], [180, 180, 180], [188, 188, 188], [196, 196, 196], [200, 204, 208], [200, 204, 208]], \"dominantColor\": [203, 203, 203]}, \"coords\": {\"cropTop\": 165, \"cropLeft\": 125, \"cropWidth\": 200, \"areaCoords\": {\"h\": 120, \"w\": 200, \"x\": 125, \"y\": 165}, \"canvasSize\": {\"h\": 450, \"w\": 450}, \"cropHeight\": 120, \"cropImageTop\": 183, \"cropImageLeft\": 139, \"cropImageWidth\": 222, \"cropImageHeight\": 133}, \"image_alt\": \"image\"}','{\"3\": {\"url\": \"assets/images/content/4/5003.jpg?1576875407\", \"width\": 500, \"height\": 500}, \"5\": {\"url\": \"assets/images/content/4/5005.jpg?1576875407\", \"width\": 500, \"height\": 500}}',NULL,NULL,1,'image','image/png','png','2019-12-20 20:56:47','2019-12-20 20:56:47',NULL),(56,4,2,'1000',3,'{\"colors\": {\"textColor\": \"dark\", \"paletteColor\": [[204, 204, 204], [149, 149, 149], [172, 172, 172], [164, 164, 164], [180, 180, 180], [188, 188, 188], [196, 196, 196], [200, 204, 208], [200, 204, 208]], \"dominantColor\": [203, 203, 203]}, \"coords\": {\"cropTop\": 165, \"cropLeft\": 125, \"cropWidth\": 200, \"areaCoords\": {\"h\": 120, \"w\": 200, \"x\": 125, \"y\": 165}, \"canvasSize\": {\"h\": 450, \"w\": 450}, \"cropHeight\": 120, \"cropImageTop\": 367, \"cropImageLeft\": 278, \"cropImageWidth\": 444, \"cropImageHeight\": 267}, \"image_alt\": \"image\"}','{\"3\": {\"url\": \"assets/images/content/4/10003.jpg?1576875408\", \"width\": 500, \"height\": 500}, \"5\": {\"url\": \"assets/images/content/4/10005.jpg?1576875408\", \"width\": 500, \"height\": 500}}',NULL,NULL,1,'image','image/png','png','2019-12-20 20:56:48','2019-12-20 20:56:48',NULL);
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forms`
--

LOCK TABLES `forms` WRITE;
/*!40000 ALTER TABLE `forms` DISABLE KEYS */;
INSERT INTO `forms` VALUES (1,'miguel@dejabu.ec','Contacto','2016-09-18 08:30:57','2017-04-11 21:21:41',NULL),(15,'dsafffffff','asd555555','2017-01-18 01:57:16','2017-04-11 23:46:56',NULL);
/*!40000 ALTER TABLE `forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image_sections`
--

DROP TABLE IF EXISTS `image_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image_sections` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `section` varchar(45) NOT NULL,
  `multiple_upload` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image_sections`
--

LOCK TABLES `image_sections` WRITE;
/*!40000 ALTER TABLE `image_sections` DISABLE KEYS */;
INSERT INTO `image_sections` VALUES (1,'Main','content',0),(2,'Gallery','content',1);
/*!40000 ALTER TABLE `image_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images_config`
--

DROP TABLE IF EXISTS `images_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_section_id` int(11) unsigned NOT NULL,
  `sufix` varchar(45) DEFAULT '_huge',
  `width` smallint(6) DEFAULT '500',
  `height` smallint(6) DEFAULT '300',
  `name` varchar(45) DEFAULT NULL,
  `position` tinyint(3) DEFAULT NULL,
  `crop` tinyint(1) DEFAULT '0',
  `force_jpg` tinyint(1) DEFAULT NULL,
  `optimize_original` tinyint(1) DEFAULT NULL,
  `background_color` varchar(45) DEFAULT NULL,
  `quality` decimal(3,0) DEFAULT NULL,
  `restrict_proportions` tinyint(1) DEFAULT NULL,
  `watermark` tinyint(1) DEFAULT NULL,
  `watermark_file_id` int(11) DEFAULT NULL,
  `watermark_position` varchar(45) DEFAULT NULL,
  `watermark_alpha` decimal(3,0) DEFAULT NULL,
  `watermark_repeat` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_images_config_image_section_id_idx` (`image_section_id`),
  CONSTRAINT `fk_images_config_image_section_id` FOREIGN KEY (`image_section_id`) REFERENCES `image_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images_config`
--

LOCK TABLES `images_config` WRITE;
/*!40000 ALTER TABLE `images_config` DISABLE KEYS */;
INSERT INTO `images_config` VALUES (1,1,'_large',800,600,'Large',1,1,1,1,NULL,80,1,NULL,NULL,NULL,NULL,NULL,'2017-01-27 22:53:20','2019-12-18 13:38:36',NULL),(2,1,'_medium',400,200,'Medium',2,0,0,1,NULL,80,1,NULL,NULL,NULL,NULL,NULL,'2017-01-28 00:12:05','2019-12-20 14:20:43',NULL),(3,2,'3',500,300,'3',4,0,1,1,NULL,80,1,NULL,NULL,NULL,NULL,NULL,'2017-01-28 00:18:04','2017-01-28 03:20:52',NULL),(4,1,'_small',100,60,'Small',5,0,1,1,NULL,80,1,NULL,NULL,NULL,NULL,NULL,'2017-01-28 00:19:01','2019-12-18 13:39:32',NULL),(5,2,'5',NULL,NULL,'5',6,0,1,1,NULL,80,0,NULL,NULL,NULL,NULL,NULL,'2017-01-28 00:20:00','2017-01-28 00:53:35',NULL);
/*!40000 ALTER TABLE `images_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `input_type`
--

DROP TABLE IF EXISTS `input_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `input_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_input_tipo_input1` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `input_type`
--

LOCK TABLES `input_type` WRITE;
/*!40000 ALTER TABLE `input_type` DISABLE KEYS */;
INSERT INTO `input_type` VALUES (1,'input'),(2,'imagen'),(3,'textarea'),(4,'link'),(5,'tabla'),(6,'imagenes'),(7,'archivos'),(8,'mapa'),(9,'checkbox'),(11,'videos'),(12,'select'),(13,'fecha'),(14,'audio');
/*!40000 ALTER TABLE `input_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inputs`
--

DROP TABLE IF EXISTS `inputs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inputs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET latin1 NOT NULL,
  `input_type_id` int(11) NOT NULL,
  `section` varchar(10) CHARACTER SET latin1 NOT NULL COMMENT 'donde se mostrara el input contacto , producto o ambos',
  PRIMARY KEY (`id`),
  KEY `fk_input_contacto_inputs_rel1` (`id`),
  KEY `inputTipoId` (`input_type_id`),
  KEY `inputTipoId_i` (`input_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inputs`
--

LOCK TABLES `inputs` WRITE;
/*!40000 ALTER TABLE `inputs` DISABLE KEYS */;
INSERT INTO `inputs` VALUES (8,'numero',1,'form'),(9,'texto',1,'slider'),(10,'texto multilinea',3,'slider'),(11,'texto multilinea',3,'form'),(12,'texto multilinea',3,'product'),(13,'texto',1,'form'),(14,'texto',1,'product'),(16,'link',1,'product'),(17,'link',1,'form'),(18,'tabla',5,'product'),(20,'archivos',7,'product'),(22,'precio',1,'product'),(23,'checkbox',9,'product'),(24,'checkbox',9,'form'),(25,'texto',1,'user'),(26,'texto multilinea',3,'user'),(27,'texto',1,'mapas'),(28,'texto multilinea',3,'mapas'),(29,'listado',12,'product'),(30,'listado predefinido',12,'product'),(31,'fecha',13,'form'),(32,'fecha',13,'user'),(33,'país',12,'user'),(37,'texto',1,'calendario'),(38,'texto multilinea',3,'calendario'),(40,'imágenes',6,'calendario'),(41,'archivos',7,'calendario'),(42,'tabla',5,'calendario'),(43,'archivo',7,'form'),(44,'nombre',1,'form');
/*!40000 ALTER TABLE `inputs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) CHARACTER SET latin1 NOT NULL,
  `slug` varchar(45) DEFAULT NULL,
  `position` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'Spanish','es','1','2017-04-11 19:48:28','2017-04-12 00:48:28',NULL),(2,'English','en','2','2017-04-11 19:48:28','2017-04-12 00:48:28',NULL);
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `map_locations`
--

DROP TABLE IF EXISTS `map_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `map_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `map_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `coords` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `temporary` tinyint(1) DEFAULT NULL,
  `css_class` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mapaId_idx` (`map_id`),
  KEY `mapaId_mu` (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `map_locations`
--

LOCK TABLES `map_locations` WRITE;
/*!40000 ALTER TABLE `map_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `map_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maps`
--

DROP TABLE IF EXISTS `maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maps` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `temporary` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maps`
--

LOCK TABLES `maps` WRITE;
/*!40000 ALTER TABLE `maps` DISABLE KEYS */;
INSERT INTO `maps` VALUES (1,'World','jpg?1436827680',1,NULL);
/*!40000 ALTER TABLE `maps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persistences`
--

DROP TABLE IF EXISTS `persistences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persistences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_UNIQUE` (`code`),
  KEY `fk_persistences_user_id_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persistences`
--

LOCK TABLES `persistences` WRITE;
/*!40000 ALTER TABLE `persistences` DISABLE KEYS */;
INSERT INTO `persistences` VALUES (1,1,'NYdVZKJf0OBVCuWprvSDHI2GDMoeZWgf','2015-11-26 19:34:30','2015-11-26 19:34:30'),(2,1,'uS5iR0dSTqU7aCUOLpXv3ShfzIZwsPUL','2015-11-26 19:34:59','2015-11-26 19:34:59'),(4,1,'vOnZWNpcCVe9pz48qDu8zcDIbk86rY6E','2015-11-26 20:08:55','2015-11-26 20:08:55'),(5,1,'oDClqoTzIScou8jOfuOCA9fqSfT8BOkT','2015-11-27 23:24:12','2015-11-27 23:24:12'),(6,1,'QJwFRcxnFJeBNY8rGSD8HIwxyFapi5lX','2015-12-01 00:17:54','2015-12-01 00:17:54'),(7,1,'COKlkLPOX1es1UNEJC2dYj2xtvg5sKl4','2015-12-01 20:03:21','2015-12-01 20:03:21'),(8,1,'z3je5UrYxUxM3Zkw9ssaKUMUutMXJDB8','2015-12-02 21:02:12','2015-12-02 21:02:12'),(9,1,'ow1ZKkx1F1eUCD7RRAm7HWZkGAoq8jRm','2015-12-03 03:58:52','2015-12-03 03:58:52'),(10,1,'auKfElvRrg56ZV2Gh22tFagkvyp4smf4','2015-12-03 20:27:04','2015-12-03 20:27:04'),(11,1,'dXLpjFoKjVvHaUNOsp5MpTaslfoUzOy9','2015-12-03 20:27:05','2015-12-03 20:27:05'),(12,1,'Yx73XpVCWhrpwVasBrTeXvHIlcEDpBXb','2015-12-04 19:15:03','2015-12-04 19:15:03'),(13,1,'YB68pVZVceZfeFlApwCJO5XnR5R4bHcV','2015-12-04 23:56:29','2015-12-04 23:56:29'),(14,1,'XYseWk4tDClJXWxg8URerzFs8z6HoRFk','2015-12-08 03:49:13','2015-12-08 03:49:13'),(15,1,'pNqSj0YvV3ILlVEbdZT3NgLZvz54A491','2015-12-08 22:56:34','2015-12-08 22:56:34'),(16,1,'34QosvvXsONh5yUta3pGHDGcNO5MZwxo','2016-05-20 04:05:57','2016-05-20 04:05:57'),(17,1,'BD4LvSr4nwJ3LT3MNvESDODIxPCUZlqE','2016-07-26 21:03:13','2016-07-26 21:03:13'),(18,1,'Em8lD3b4oDVztJVzjv1VklmGa7NVajGC','2016-07-27 22:18:08','2016-07-27 22:18:08'),(20,1,'qrcMLe8W6QTAdxzrH4tncb2DedSVgblf','2016-07-27 22:18:56','2016-07-27 22:18:56'),(21,1,'20FGNyFPPGjHRWANeolaFTDxaNFKA5dX','2016-07-27 22:19:54','2016-07-27 22:19:54'),(22,1,'TdGu3NZqGVBAzMz7eAGbFC5OCUVFCAxK','2016-09-04 00:17:14','2016-09-04 00:17:14'),(23,1,'dubwgrGdDbrp1kIsyZiAinJaOHZk8YGd','2016-11-12 03:10:40','2016-11-12 03:10:40'),(24,1,'EUyhr12QKCqblkEx3PM2749KZb9z6Yb2','2016-11-17 23:40:43','2016-11-17 23:40:43'),(25,1,'qShPBvkYC7K5F7H22B6B50Z2MINeK0z2','2016-11-22 21:59:43','2016-11-22 21:59:43'),(26,1,'cWEocr5lsBIobcnVQY2snKrggSHEF29M','2016-12-16 04:03:09','2016-12-16 04:03:09'),(27,1,'uwAKRHpQskLEA1Boe4deuYBd9IdM9PYH','2016-12-20 20:53:33','2016-12-20 20:53:33'),(28,1,'KVbruOxXw2z49yyPTsrYkW1bwWRMKHdd','2016-12-27 19:26:25','2016-12-27 19:26:25'),(29,1,'upmeNImq5ZUCt5UfUU1OzgcO8xP2isdL','2017-01-04 00:41:46','2017-01-04 00:41:46'),(30,1,'ron3jhFVuXvYbtcNgSKUmAC2oFRtYItx','2017-01-11 00:52:40','2017-01-11 00:52:40'),(31,1,'8TdTegOjH2AhnNVVc8EXWf96kvm1rYVv','2017-01-21 02:18:00','2017-01-21 02:18:00'),(32,1,'JZUtqng2RVFNerBgkOm4U4k9UsT4XINy','2017-01-27 20:56:57','2017-01-27 20:56:57'),(33,1,'8Pa6WV7Dkw5sim1mb4AGqR4BomNczdob','2017-01-27 20:56:59','2017-01-27 20:56:59'),(34,1,'wJSySudVwJvciSgL9M8X8KPpaEjP0FLL','2017-02-01 19:53:38','2017-02-01 19:53:38'),(35,1,'IRlv6chDxBE8LYprdoialXD5oQWiOUpm','2017-02-17 00:33:01','2017-02-17 00:33:01'),(43,1,'YCc9IEjCGfmG46oqZLdxNwweb2vnp5BX','2017-03-21 21:43:21','2017-03-21 21:43:21'),(50,1,'xodJRwSmyc6xBXOAivWe7TxPicSHZ8NV','2017-04-25 19:41:05','2017-04-25 19:41:05'),(51,1,'xQkt7SlF1gEB8m4L9TmAbr1z1WhlE06D','2019-12-09 20:28:05','2019-12-09 20:28:05');
/*!40000 ALTER TABLE `persistences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `predefined_lists`
--

DROP TABLE IF EXISTS `predefined_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `predefined_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `css_class` varchar(45) DEFAULT NULL,
  `position` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productoCampoId_pclp_idx` (`field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `predefined_lists`
--

LOCK TABLES `predefined_lists` WRITE;
/*!40000 ALTER TABLE `predefined_lists` DISABLE KEYS */;
/*!40000 ALTER TABLE `predefined_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `important` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 si es producto del dia 0 si no ',
  `category_id` int(11) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'si,no para mostrar consultas',
  `image` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `position` int(11) DEFAULT '0',
  `temporary` tinyint(1) DEFAULT NULL,
  `stock_quantity` smallint(5) DEFAULT '0',
  `stock_auto_allocate_status` tinyint(1) DEFAULT '1',
  `weight` double DEFAULT NULL,
  `css_class` varchar(45) DEFAULT NULL,
  `visible_to` varchar(45) DEFAULT 'public',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoriaId_idx` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reminders`
--

DROP TABLE IF EXISTS `reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reminders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `fk_reminders_user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reminders`
--

LOCK TABLES `reminders` WRITE;
/*!40000 ALTER TABLE `reminders` DISABLE KEYS */;
/*!40000 ALTER TABLE `reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_sections`
--

DROP TABLE IF EXISTS `role_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_sections` (
  `role_id` int(10) unsigned NOT NULL,
  `section_name` varchar(45) NOT NULL,
  PRIMARY KEY (`role_id`,`section_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_sections`
--

LOCK TABLES `role_sections` WRITE;
/*!40000 ALTER TABLE `role_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_users`
--

DROP TABLE IF EXISTS `role_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_users` (
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `fk_role_users_role_id_idx` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_users`
--

LOCK TABLES `role_users` WRITE;
/*!40000 ALTER TABLE `role_users` DISABLE KEYS */;
INSERT INTO `role_users` VALUES (1,1,'2015-11-27 18:49:32','2015-11-27 18:49:32');
/*!40000 ALTER TABLE `role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `permissions` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'superadmin','Superadmin','{\"admin\":true,\"module.structure\":true,\"module.advert\":true,\"module.auth\":true,\"module.contact\":true,\"module.mailchimp\":true,\"module.map\":true,\"module.slider\":true,\"module.theme\":true,\"module.language\":true,\"module.config\":true}','2015-11-26 15:05:41','2016-07-28 23:08:55'),(2,'admin','Admin','{\"admin\":true,\"module.slider\":true,\"module.language\":true,\"module.config\":true}','2015-11-26 18:24:01','2016-07-28 23:09:12'),(3,'registered','Registered',NULL,'2015-11-26 18:24:18','2015-11-26 23:24:18');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `controller` varchar(45) DEFAULT NULL,
  `position` tinyint(2) DEFAULT NULL,
  `view_menu` tinyint(1) DEFAULT '0',
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'Estructura','structure',1,1,'Crear páginas, editar su estructura, añadir módulos'),(2,'Artículos','article',2,0,NULL),(3,'Preguntas Frecuentes','faq',3,0,NULL),(4,'Enlaces','link',4,0,NULL),(5,'Publicaciones','noticias',5,0,NULL),(6,'Banners','slideshow',6,1,'Banners animados, slideshows'),(7,'Mapas','mapas',7,1,'Mapas y ubicaciones'),(8,'Catálogo','catalog',8,0,NULL),(9,'Galería','gallery',9,0,NULL),(10,'Idiomas','idiomas',10,1,'Editar idiomas para sitios multi-idiomas'),(11,'Contacto','contact',11,1,'Formulario de contáctos, personas de contacto'),(12,'Usuarios','auth/admin/main',12,1,'Usuarios del sistema: administradores, registrados, etc'),(13,'Estadísticas','estadisticas',13,1,'Datos simples del uso del sitio web'),(14,'Configuración','config',19,1,'Tamaños de imagenes, configuracion general'),(15,'Servicios','servicios',14,0,NULL),(16,'Publicidad','publicidad',15,1,'Crear publicidad en varias secciones definidas'),(17,'Carrito de Compras','cart',16,0,NULL),(18,'Diseño','theme',17,1,'Editar como se ve el sitio web'),(19,'Mailing','mailing',18,1,'Enviar mails masivos');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sliders`
--

DROP TABLE IF EXISTS `sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT '800',
  `height` int(11) DEFAULT '600',
  `enabled` tinyint(1) DEFAULT '1',
  `temporary` tinyint(1) DEFAULT '1',
  `config` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sliders`
--

LOCK TABLES `sliders` WRITE;
/*!40000 ALTER TABLE `sliders` DISABLE KEYS */;
INSERT INTO `sliders` VALUES (12,'Swiper','','Swiper',1024,261,1,0,'{\n    \"initialSlide\": 0,\n    \"direction\": \"horizontal\",\n    \"speed\": 300,\n    \"autoplay\": 0,\n    \"autoplayDisableOnInteraction\": true,\n    \"watchSlidesProgress\": false,\n    \"watchVisibility\": false,\n    \"freeMode\": false,\n    \"freeModeMomentum\": true,\n    \"freeModeMomentumRatio\": 1,\n    \"freeModeMomentumBounce\": true,\n    \"freeModeMomentumBounceRatio\": 1,\n    \"effect\": \"coverflow\",\n    \"cube\": {\n        \"slideShadows\": 1,\n        \"shadow\": 1,\n        \"shadowOffset\": 20,\n        \"shadowScale\": 0.94\n    },\n    \"coverflow\": {\n        \"rotate\": 50,\n        \"stretch\": 0,\n        \"depth\": 100,\n        \"modifier\": 1,\n        \"slideShadows\": 1\n    },\n    \"spaceBetween\": 0,\n    \"slidesPerView\": 3,\n    \"slidesPerColumn\": 1,\n    \"slidesPerColumnFill\": \"column\",\n    \"slidesPerGroup\": 1,\n    \"centeredSlides\": false,\n    \"grabCursor\": false,\n    \"touchRatio\": 1,\n    \"touchAngle\": 45,\n    \"simulateTouch\": true,\n    \"shortSwipes\": true,\n    \"longSwipes\": true,\n    \"longSwipesRatio\": 0.5,\n    \"longSwipesMs\": 300,\n    \"followFinger\": true,\n    \"onlyExternal\": false,\n    \"threshold\": 0,\n    \"touchMoveStopPropagation\": true,\n    \"resistance\": true,\n    \"resistanceRatio\": 0.85,\n    \"preventClicks\": true,\n    \"preventClicksPropagation\": true,\n    \"releaseFormElements\": true,\n    \"slideToClickedSlide\": false,\n    \"allowSwipeToPrev\": true,\n    \"allowSwipeToNext\": true,\n    \"noSwiping\": true,\n    \"noSwipingClass\": \"swiper-no-swiping\",\n    \"swipeHandler\": null,\n    \"pagination\": null,\n    \"paginationHide\": true,\n    \"paginationClickable\": false,\n    \"nextButton\": null,\n    \"prevButton\": null,\n    \"scrollbar\": null,\n    \"scrollbarHide\": true,\n    \"keyboardControl\": false,\n    \"mousewheelControl\": false,\n    \"mousewheelForceToAxis\": false,\n    \"hashnav\": false,\n    \"updateOnImagesReady\": true,\n    \"loop\": false,\n    \"loopAdditionalSlides\": 0,\n    \"loopedSlides\": null,\n    \"control\": null,\n    \"controlInverse\": false,\n    \"observer\": false,\n    \"observeParents\": false,\n    \"slideClass\": \"swiper-slide\",\n    \"slideActiveClass\": \"swiper-slide-active\",\n    \"slideVisibleClass\": \"swiper-slide-visible\",\n    \"slideDuplicateClass\": \"swiper-slide-duplicate\",\n    \"slideNextClass\": \"swiper-slide-next\",\n    \"slidePrevClass\": \"swiper-slide-prev\",\n    \"wrapperClass\": \"swiper-wrapper\",\n    \"bulletClass\": \"swiper-pagination-bullet\",\n    \"bulletActiveClass\": \"swiper-pagination-bullet-active\",\n    \"paginationHiddenClass\": \"swiper-pagination-hidden\",\n    \"buttonDisabledClass\": \"swiper-button-disabled\"\n}',NULL,'2016-07-26 19:59:33',NULL),(73,'bxSlider','','bxSlider',200,200,1,0,'{\n    \"mode\": \"horizontal\",\n    \"speed\": 700,\n    \"slideMargin\": 0,\n    \"startSlide\": 0,\n    \"randomStart\": false,\n    \"infiniteLoop\": true,\n    \"hideControlOnEnd\": false,\n    \"easing\": \"linear\",\n    \"captions\": false,\n    \"ticker\": false,\n    \"tickerHover\": false,\n    \"adaptiveHeight\": true,\n    \"adaptiveHeightSpeed\": 500,\n    \"video\": false,\n    \"preloadImages\": \"all\",\n    \"pager\": true,\n    \"pagerType\": \"full\",\n    \"pagerShortSeparator\": \" \\/ \",\n    \"controls\": true,\n    \"nextText\": \"Next\",\n    \"prevText\": \"Prev\",\n    \"autoControls\": false,\n    \"startText\": \"Start\",\n    \"stopText\": \"Stop\",\n    \"auto\": true,\n    \"pause\": 8000,\n    \"autoStart\": true,\n    \"autoDirection\": \"next\",\n    \"autoHover\": false,\n    \"autoDelay\": 0,\n    \"minSlides\": 1,\n    \"maxSlides\": 1,\n    \"moveSlides\": 0,\n    \"slideWidth\": 0\n}',NULL,NULL,NULL),(75,'Stack','','StackGallery',500,500,1,0,'{\n    \"slideshowLayout\": \"horizontalLeft\",\n    \"slideshowDirection\": \"forward\",\n    \"controlsAlignment\": \"rightCenter\",\n    \"fullSize\": 1,\n    \"slideshowDelay\": 3000,\n    \"slideshowOn\": 1,\n    \"useRotation\": 1,\n    \"swipeOn\": 0\n}',NULL,NULL,NULL);
/*!40000 ALTER TABLE `sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `category_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `estadisticaUserIP` (`ip`),
  KEY `estadisticaFecha` (`date`),
  KEY `paginaId_e_idx` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats`
--

LOCK TABLES `stats` WRITE;
/*!40000 ALTER TABLE `stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `throttle`
--

DROP TABLE IF EXISTS `throttle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `throttle`
--

LOCK TABLES `throttle` WRITE;
/*!40000 ALTER TABLE `throttle` DISABLE KEYS */;
INSERT INTO `throttle` VALUES (47,NULL,'global',NULL,'2019-12-09 20:18:15','2019-12-09 20:18:15'),(48,NULL,'ip','192.168.10.1','2019-12-09 20:18:15','2019-12-09 20:18:15'),(49,1,'user',NULL,'2019-12-09 20:18:15','2019-12-09 20:18:15'),(50,NULL,'global',NULL,'2019-12-09 20:26:22','2019-12-09 20:26:22'),(51,NULL,'ip','192.168.10.1','2019-12-09 20:26:22','2019-12-09 20:26:22'),(52,NULL,'global',NULL,'2019-12-09 20:26:31','2019-12-09 20:26:31'),(53,NULL,'ip','192.168.10.1','2019-12-09 20:26:31','2019-12-09 20:26:31'),(54,1,'user',NULL,'2019-12-09 20:26:31','2019-12-09 20:26:31'),(55,NULL,'global',NULL,'2019-12-09 20:27:25','2019-12-09 20:27:25'),(56,NULL,'ip','192.168.10.1','2019-12-09 20:27:25','2019-12-09 20:27:25'),(57,1,'user',NULL,'2019-12-09 20:27:25','2019-12-09 20:27:25');
/*!40000 ALTER TABLE `throttle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `type` varchar(45) DEFAULT NULL COMMENT 'widget, content, field',
  `data` mediumtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_unique_translations` (`parent_id`,`language_id`,`type`),
  KEY `fk_translations_1_idx` (`parent_id`),
  KEY `fk_translations_language_id_idx` (`language_id`),
  CONSTRAINT `fk_translations_language_id` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=305 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations`
--

LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
INSERT INTO `translations` VALUES (79,15,1,'content','{\"name\":\"1\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-11-17 22:04:04','2017-01-19 17:33:23',NULL),(87,23,1,'page','{\"name\":\"Pagina Inicial\",\"menu_name\":\"Index\",\"meta_keywords\":[\"asd\",\"dsa\",\"asdsad\"],\"meta_description\":\"\"}','2016-11-21 14:34:38','2016-11-25 21:54:21',NULL),(111,45,1,'content','{\"name\":\"2\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-12-02 00:06:41','2016-12-02 00:06:41',NULL),(114,48,1,'content','{\"name\":\"dsasss\",\"content\":\"<ul>\\n<li>sadas<\\/li>\\n<li>dsa<\\/li>\\n<li>dsa<\\/li>\\n<li>dsad<\\/li>\\n<\\/ul>\",\"meta_keywords\":[\"asd\",\"das\"],\"meta_description\":\"dsss\",\"meta_title\":\"d\"}','2016-12-02 00:12:06','2017-01-19 17:33:05',NULL),(115,49,1,'content','{\"name\":\"Iure quasi quisquam velit id aut aut quaerat consequatur\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"Maiores sint consequatur consequatur? In provident, eius nisi accusamus pariatur? Amet, quaerat dolor nesciunt.\",\"meta_title\":\"Nulla dolor excepteur iure ex nemo et sit porro sit aliquid aperiam nihil quidem commodo qui\"}','2016-12-02 18:16:05','2016-12-02 18:16:05',NULL),(116,50,1,'content','{\"name\":\"Iure quasi quisquam velit id aut aut quaerat consequatur\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"Maiores sint consequatur consequatur? In provident, eius nisi accusamus pariatur? Amet, quaerat dolor nesciunt.\",\"meta_title\":\"Nulla dolor excepteur iure ex nemo et sit porro sit aliquid aperiam nihil quidem commodo qui\"}','2016-12-02 18:16:19','2016-12-02 18:16:19',NULL),(119,53,1,'content','{\"name\":\"123\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-12-02 18:16:52','2016-12-02 18:16:52',NULL),(120,54,1,'content','{\"name\":\"123\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-12-02 18:16:54','2016-12-02 18:16:54',NULL),(121,55,1,'content','{\"name\":\"123\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-12-02 18:16:55','2016-12-02 18:16:55',NULL),(122,56,1,'content','{\"name\":\"123\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-12-02 18:16:56','2016-12-02 18:16:56',NULL),(123,57,1,'content','{\"name\":\"123\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-12-02 18:16:57','2016-12-02 18:16:57',NULL),(124,58,1,'content','{\"name\":\"123\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-12-02 18:16:58','2016-12-02 18:16:58',NULL),(125,59,1,'content','{\"name\":\"asd\",\"content\":\"<p>dsa<\\/p>\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-12-02 18:16:58','2017-01-19 17:15:04',NULL),(126,60,1,'content','{\"name\":\"1\",\"content\":\"<p>2<\\/p>\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-12-02 18:16:59','2017-01-19 17:18:27',NULL),(127,61,1,'content','{\"name\":\"3\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2016-12-02 18:17:00','2017-01-19 17:20:38',NULL),(133,15,1,'form_field','{\"name\":\"Nombre\",\"placeholder\":\"Nombre\"}','2017-01-13 21:18:42','2017-01-13 22:24:08',NULL),(134,16,1,'form_field','{\"name\":\"Email\",\"placeholder\":\"\"}','2017-01-13 22:23:19','2017-01-13 22:23:19',NULL),(135,17,1,'form_field','{\"name\":\"Mensaje\",\"placeholder\":\"\"}','2017-01-16 19:04:42','2017-01-16 19:04:42',NULL),(137,19,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-01-17 17:03:58','2017-01-17 17:03:58',NULL),(139,21,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-01-17 17:09:27','2017-01-17 17:09:27',NULL),(141,23,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-01-17 17:09:57','2017-01-17 17:09:57',NULL),(142,24,1,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-01-17 17:09:58','2017-01-17 17:09:58',NULL),(144,26,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-01-17 20:58:47','2017-01-17 20:58:47',NULL),(145,27,1,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-01-17 20:58:47','2017-01-17 20:58:47',NULL),(155,37,1,'form_field','{\"name\":\"Archivo\",\"placeholder\":\"\"}','2017-01-18 16:34:22','2017-01-18 16:34:22',NULL),(156,48,1,NULL,'{\"name\":\"dsasss\",\"content\":\"<ul>\\n<li>sadas<\\/li>\\n<li>dsa<\\/li>\\n<li>dsa<\\/li>\\n<li>dsad<\\/li>\\n<\\/ul>\",\"meta_keywords\":[\"asd\",\"das\"],\"meta_description\":\"dsss\",\"meta_title\":\"d\"}','2017-01-18 17:50:15','2017-01-18 21:39:41',NULL),(157,62,1,NULL,'{\"name\":\"1\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-18 22:44:08','2017-01-18 22:44:08',NULL),(158,63,1,NULL,'{\"name\":\"2\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-18 22:45:28','2017-01-18 22:45:28',NULL),(159,23,1,'base','{\"name\":null,\"menu_name\":null,\"meta_keywords\":[],\"meta_description\":\"\"}','2017-01-19 17:11:41','2017-01-19 20:41:10',NULL),(160,62,1,'content','{\"name\":\"2\",\"content\":\"<p>3<\\/p>\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-19 17:35:44','2017-01-19 17:35:44',NULL),(161,63,1,'content','{\"name\":\"6\",\"content\":\"<p>6<\\/p>\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-19 17:40:20','2017-01-19 17:40:20',NULL),(162,64,1,'content','{\"name\":\"7\",\"content\":\"<p>7<\\/p>\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-19 17:41:39','2017-01-19 17:41:39',NULL),(163,65,1,'content','{\"name\":\"8\",\"content\":\"<p>8<\\/p>\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-19 17:42:17','2017-01-19 17:42:17',NULL),(164,23,2,'base','{\"name\":null,\"menu_name\":null,\"meta_keywords\":[]}','2017-01-19 20:41:10','2017-01-19 20:41:10',NULL),(165,65,2,'content','{\"name\":\"Eight\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-27 20:24:29','2017-01-27 20:24:29',NULL),(166,66,1,'content','{\"name\":\"asd\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-27 20:24:54','2017-01-27 20:24:54',NULL),(167,66,2,'content','{\"name\":\"das\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-27 20:24:54','2017-01-27 20:24:54',NULL),(168,67,1,'content','{\"name\":\"fds\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-27 20:26:31','2017-01-27 20:26:31',NULL),(169,67,2,'content','{\"name\":\"ffff\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-27 20:26:31','2017-01-27 20:26:31',NULL),(170,68,1,'content','{\"name\":\"eee\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-27 21:10:52','2017-01-27 21:10:52',NULL),(171,68,2,'content','{\"name\":\"33\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-27 21:10:52','2017-01-27 21:10:52',NULL),(172,69,1,'content','{\"name\":\"1ewe\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-27 21:14:22','2017-01-27 21:14:22',NULL),(173,69,2,'content','{\"name\":\"eeee\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-01-27 21:14:22','2017-01-27 21:14:22',NULL),(174,60,2,'content','{\"name\":\"2\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-03-06 22:37:57','2017-03-06 22:37:57',NULL),(175,38,1,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 16:21:16','2017-04-11 16:21:16',NULL),(176,38,2,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 16:21:16','2017-04-11 16:21:16',NULL),(177,39,1,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 16:21:41','2017-04-11 16:21:41',NULL),(178,39,2,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 16:21:41','2017-04-11 16:21:41',NULL),(181,41,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 18:47:25','2017-04-11 18:47:25',NULL),(182,41,2,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 18:47:25','2017-04-11 18:47:25',NULL),(185,43,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 18:49:15','2017-04-11 18:49:15',NULL),(186,43,2,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 18:49:15','2017-04-11 18:49:15',NULL),(187,44,1,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 18:49:15','2017-04-11 18:49:15',NULL),(188,44,2,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 18:49:15','2017-04-11 18:49:15',NULL),(191,46,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 18:49:58','2017-04-11 18:49:58',NULL),(192,46,2,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 18:49:58','2017-04-11 18:49:58',NULL),(193,47,1,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 18:49:58','2017-04-11 18:49:58',NULL),(194,47,2,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 18:49:58','2017-04-11 18:49:58',NULL),(197,49,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 18:58:30','2017-04-11 18:58:30',NULL),(198,49,2,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 18:58:30','2017-04-11 18:58:30',NULL),(199,50,1,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 18:58:30','2017-04-11 18:58:30',NULL),(200,50,2,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 18:58:30','2017-04-11 18:58:30',NULL),(201,51,1,'form_field','{\"name\":\"campo 4\",\"placeholder\":\"\"}','2017-04-11 18:58:30','2017-04-11 18:58:30',NULL),(202,51,2,'form_field','{\"name\":\"campo 4\",\"placeholder\":\"\"}','2017-04-11 18:58:30','2017-04-11 18:58:30',NULL),(205,53,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 19:03:23','2017-04-11 19:03:23',NULL),(206,53,2,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 19:03:23','2017-04-11 19:03:23',NULL),(213,57,1,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(214,57,2,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(215,58,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(216,58,2,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(217,59,1,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(218,59,2,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(219,60,1,'form_field','{\"name\":\"campo 4\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(220,60,2,'form_field','{\"name\":\"campo 4\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(221,61,1,'form_field','{\"name\":\"campo 5\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(222,61,2,'form_field','{\"name\":\"campo 5\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(223,62,1,'form_field','{\"name\":\"campo 6\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(224,62,2,'form_field','{\"name\":\"campo 6\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(225,63,1,'form_field','{\"name\":\"campo 7\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(226,63,2,'form_field','{\"name\":\"campo 7\",\"placeholder\":\"\"}','2017-04-11 21:42:38','2017-04-11 21:42:38',NULL),(227,64,1,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(228,64,2,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(229,65,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(230,65,2,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(231,66,1,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(232,66,2,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(233,67,1,'form_field','{\"name\":\"campo 4\",\"placeholder\":\"\"}','2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(234,67,2,'form_field','{\"name\":\"campo 4\",\"placeholder\":\"\"}','2017-04-11 21:42:46','2017-04-11 21:42:46',NULL),(235,68,1,'form_field','{\"name\":\"campo 5\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(236,68,2,'form_field','{\"name\":\"campo 5\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(237,69,1,'form_field','{\"name\":\"campo 6\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(238,69,2,'form_field','{\"name\":\"campo 6\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(239,70,1,'form_field','{\"name\":\"campo 7\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(240,70,2,'form_field','{\"name\":\"campo 7\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(241,71,1,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(242,71,2,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(243,72,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(244,72,2,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(245,73,1,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(246,73,2,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 21:42:47','2017-04-11 21:42:47',NULL),(249,75,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 21:42:57','2017-04-11 21:42:57',NULL),(250,75,2,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 21:42:57','2017-04-11 21:42:57',NULL),(251,76,1,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 21:43:25','2017-04-11 21:43:25',NULL),(252,76,2,'form_field','{\"name\":\"campo 1\",\"placeholder\":\"\"}','2017-04-11 21:43:25','2017-04-11 21:43:25',NULL),(253,77,1,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 21:43:25','2017-04-11 21:43:25',NULL),(254,77,2,'form_field','{\"name\":\"campo 2\",\"placeholder\":\"\"}','2017-04-11 21:43:25','2017-04-11 21:43:25',NULL),(255,78,1,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 21:43:25','2017-04-11 21:43:25',NULL),(256,78,2,'form_field','{\"name\":\"campo 3\",\"placeholder\":\"\"}','2017-04-11 21:43:25','2017-04-11 21:43:25',NULL),(265,2,1,'page','{\"name\":\"Usuarios\",\"menu_name\":\"Usuarios\",\"meta_keywords\":[]}','2017-04-12 21:58:27','2017-04-26 20:27:21',NULL),(266,2,2,'page','{\"name\":\"Registered\",\"menu_name\":\"Registered\",\"meta_keywords\":[]}','2017-04-12 21:58:27','2017-04-12 22:16:55',NULL),(267,7,1,'page','{\"name\":\"Catalogo\",\"menu_name\":\"Catalogo\",\"meta_keywords\":[]}','2017-04-13 16:26:20','2017-04-25 21:34:16',NULL),(268,7,2,'page','{\"name\":null,\"menu_name\":null,\"meta_keywords\":[]}','2017-04-13 16:26:20','2017-04-13 16:26:20',NULL),(269,8,1,'page','{\"name\":\"Calendario\",\"menu_name\":\"Calendario\",\"meta_keywords\":[]}','2017-04-13 16:26:32','2017-04-25 21:55:26',NULL),(270,8,2,'page','{\"name\":null,\"menu_name\":null,\"meta_keywords\":[]}','2017-04-13 16:26:32','2017-04-13 16:26:32',NULL),(271,1,1,'content','{\"name\":\"1\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-04-24 20:22:19','2017-04-24 20:22:19',NULL),(272,1,2,'content','{\"name\":\"1\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-04-24 20:22:19','2017-04-24 20:22:19',NULL),(273,2,1,'content','{\"name\":\"2\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-04-24 20:22:44','2017-04-24 20:22:44',NULL),(274,2,2,'content','{\"name\":\"2\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-04-24 20:22:44','2017-04-24 20:22:44',NULL),(275,3,1,'content','{\"name\":\"3s\",\"content\":\"<p>asdasdasas<\\/p>\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-04-24 20:22:54','2017-04-24 21:06:31',NULL),(276,3,2,'content','{\"name\":\"3a\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2017-04-24 20:22:54','2017-04-24 21:04:35',NULL),(277,9,1,'page','{\"name\":\"Contenido\",\"menu_name\":\"Contenido\",\"meta_keywords\":[]}','2017-04-25 22:03:51','2017-04-25 22:03:51',NULL),(278,9,2,'page','{\"name\":null,\"menu_name\":null,\"meta_keywords\":[]}','2017-04-25 22:03:51','2017-04-25 22:03:51',NULL),(279,10,1,'page','{\"name\":\"Preguntas Frecuentes\",\"menu_name\":\"FAQ\",\"meta_keywords\":[]}','2017-04-25 22:09:28','2017-04-25 22:09:28',NULL),(280,10,2,'page','{\"name\":null,\"menu_name\":null,\"meta_keywords\":[]}','2017-04-25 22:09:28','2017-04-25 22:09:28',NULL),(281,11,1,'page','{\"name\":\"Pagina para usuarios Registrados\",\"menu_name\":\"Registrados\",\"meta_keywords\":[]}','2017-04-26 20:27:58','2017-04-26 20:27:58',NULL),(282,11,2,'page','{\"name\":null,\"menu_name\":null,\"meta_keywords\":[]}','2017-04-26 20:27:58','2017-04-26 20:27:58',NULL),(289,15,1,'page','{\"name\":\"Galeria\",\"menu_name\":\"Galeria\",\"meta_keywords\":[]}','2017-04-27 21:50:24','2017-04-27 21:50:24',NULL),(290,15,2,'page','{\"name\":null,\"menu_name\":null,\"meta_keywords\":[]}','2017-04-27 21:50:24','2017-04-27 21:50:24',NULL),(291,4,1,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 15:46:36','2019-12-16 15:46:36',NULL),(292,4,2,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 15:46:36','2019-12-16 15:46:36',NULL),(293,5,1,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 15:54:09','2019-12-16 15:54:09',NULL),(294,5,2,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 15:54:09','2019-12-16 15:54:09',NULL),(295,6,1,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 15:54:32','2019-12-16 15:54:32',NULL),(296,6,2,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 15:54:32','2019-12-16 15:54:32',NULL),(297,7,1,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 15:56:17','2019-12-16 15:56:17',NULL),(298,7,2,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 15:56:17','2019-12-16 15:56:17',NULL),(299,8,1,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 16:01:28','2019-12-16 16:01:28',NULL),(300,8,2,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 16:01:28','2019-12-16 16:01:28',NULL),(301,9,1,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 16:05:35','2019-12-16 16:05:35',NULL),(302,9,2,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 16:05:35','2019-12-16 16:05:35',NULL),(303,10,1,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 16:08:49','2019-12-16 16:08:49',NULL),(304,10,2,'content','{\"name\":\"Test\",\"content\":\"\",\"meta_keywords\":[],\"meta_description\":\"\",\"meta_title\":\"\"}','2019-12-16 16:08:49','2019-12-16 16:08:49',NULL);
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permissions` text,
  `last_login` timestamp NULL DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `image_extension` varchar(45) DEFAULT NULL,
  `image_coord` varchar(255) DEFAULT NULL,
  `temporary` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'miguel@dejabu.ec','$2y$10$PWH1K0k81TJTa.INQpYBruRkcR71WuWyxW.h4sVrigadCgv240bKu',NULL,'2019-12-09 20:28:05','Miguel','Suarez',NULL,NULL,0,'2015-11-25 22:41:58','2019-12-09 20:28:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widgets`
--

DROP TABLE IF EXISTS `widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `view` varchar(45) DEFAULT 'default_view.php',
  `class` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `type` varchar(45) DEFAULT NULL,
  `data` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paginaId_m` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widgets`
--

LOCK TABLES `widgets` WRITE;
/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;
INSERT INTO `widgets` VALUES (43,2,'default_view.php',NULL,1,'Content','{\"content_type\":\"auth\",\"settings\":{\"list_view\":\"\",\"detail_view\":\"\",\"order\":\"manual\",\"pagination\":false,\"quantity\":6}}','2017-04-22 03:31:23','2017-04-26 02:30:59',NULL),(47,7,'default_view.php',NULL,1,'Content','{\"content_type\":\"catalog\"}','2017-04-26 02:38:18','2017-04-26 02:56:19',NULL),(52,8,'default_view.php',NULL,1,'Content','{\"content_type\":\"calendar\"}','2017-04-26 02:55:14','2017-04-26 02:55:20',NULL),(54,9,'default_view.php',NULL,1,'Content','{\"content_type\":\"content\",\"settings\":{\"list_view\":\"\",\"detail_view\":\"\",\"order\":\"manual\",\"pagination\":false,\"quantity\":3}}','2017-04-26 03:03:18','2019-12-16 22:49:23',NULL),(55,10,'default_view.php',NULL,1,'Content','{\"content_type\":\"faq\"}','2017-04-26 03:08:58','2017-04-26 03:09:28',NULL),(56,11,'default_view.php',NULL,1,'Content','{\"content_type\":\"content\"}','2017-04-27 01:27:45','2017-04-27 01:27:58',NULL),(57,0,'default_view.php',NULL,1,'Content',NULL,'2017-04-28 00:55:52','2017-04-28 00:55:52',NULL),(58,0,'default_view.php',NULL,1,'Content',NULL,'2017-04-28 00:56:21','2017-04-28 00:56:21',NULL),(59,0,'default_view.php',NULL,1,'Content',NULL,'2017-04-28 00:59:01','2017-04-28 00:59:01',NULL),(60,0,'default_view.php',NULL,1,'Content',NULL,'2017-04-28 01:17:53','2017-04-28 01:17:53',NULL),(62,12,'default_view.php',NULL,1,'Content',NULL,'2017-04-28 01:29:48','2017-04-28 01:29:48',NULL),(66,13,'default_view.php',NULL,1,'Content','{\"content_type\":\"content\"}','2017-04-28 01:52:10','2017-04-28 02:00:21',NULL),(68,15,'default_view.php',NULL,1,'Content','{\"content_type\":\"file\"}','2017-04-28 02:50:02','2017-04-28 02:50:24',NULL);
/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-12-20 16:18:27
