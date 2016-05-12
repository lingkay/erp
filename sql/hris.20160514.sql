-- MySQL dump 10.13  Distrib 5.5.49, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: hris_quadrant
-- ------------------------------------------------------
-- Server version	5.5.49-0ubuntu0.14.04.1

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
-- Table structure for table `auth_tokens`
--

DROP TABLE IF EXISTS `auth_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `token` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_tokens`
--

LOCK TABLES `auth_tokens` WRITE;
/*!40000 ALTER TABLE `auth_tokens` DISABLE KEYS */;
INSERT INTO `auth_tokens` VALUES (1,'2015-10-02','ed8cc2313a138a43dacf526a7593a1a3'),(2,'2015-10-05','845071489894349e7eae6cd4cef1e8b1'),(3,'2015-10-06','42cc993d412c00a375003afb236705f2'),(4,'2015-10-07','0228003a4f2cdac24511e39181c58283'),(5,'2015-10-13','1395a5fd5ae476ef7d0added85e20d1b'),(6,'2015-10-22','702d5c535a9a5b4f5a31cfc53409c9d8'),(7,'2015-10-23','9bcc7f9d0ad56210cad34f7553be3f5a'),(8,'2015-10-26','02786c9de0c190bd3f4e119d64f2eef3'),(9,'2015-10-27','de6aff9a8356e3436fa4c7a5064ce730'),(10,'2015-10-29','3f138919fc2f1e4dcad868baa7957b8b'),(11,'2015-11-03','b4edb498bda0a145b75dcca43f61c8eb'),(12,'2015-11-04','bdbe1d19c43c6d00a23bb38db0fef249'),(13,'2015-11-05','24b6b5a990ce92f1c4b01e24009fc800'),(14,'2015-12-11','0922cfb9370801df23bc875fe50410ab'),(15,'2015-12-14','a24493dc4b2cb94f14c0bba10be55aea'),(16,'2016-01-07','7bfa90194b9dc3b013e42729b358847e'),(17,'2016-05-05','db05a80042d3888fdede5191660f9d11'),(18,'2016-05-10','33e68908dc832109029a7aeda7180b0a'),(19,'2016-05-11','99d807833748575c1d98dd3dc596d9dd');
/*!40000 ALTER TABLE `auth_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `biometrics_attendance`
--

DROP TABLE IF EXISTS `biometrics_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `biometrics_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `checktime` datetime DEFAULT NULL,
  `checktype` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A8AD4CA68C03F15C` (`employee_id`),
  CONSTRAINT `FK_A8AD4CA68C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1451 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `biometrics_attendance`
--

LOCK TABLES `biometrics_attendance` WRITE;
/*!40000 ALTER TABLE `biometrics_attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `biometrics_attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cfg_entry`
--

DROP TABLE IF EXISTS `cfg_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cfg_entry` (
  `id` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cfg_entry`
--

LOCK TABLES `cfg_entry` WRITE;
/*!40000 ALTER TABLE `cfg_entry` DISABLE KEYS */;
INSERT INTO `cfg_entry` VALUES ('cat_color_bg_image',''),('cat_color_header',''),('cat_color_primary',''),('cat_color_secondary',''),('cat_color_tertiary',''),('hris_biometrics_password','bioman'),('hris_biometrics_username','admin'),('hris_com_favicon','34'),('hris_com_favicon_url','/uploads/cc/7e/572bd66a97ecc.png'),('hris_com_info_company_address','11'),('hris_com_info_company_name','Quadrant Alpha Technology Solutions Inc.'),('hris_com_info_email','info@quadrantalpha.com'),('hris_com_info_phone','[8]'),('hris_com_info_website','www.quadrantalpha.com'),('hris_com_logo','33'),('hris_com_logo_url','/uploads/e7/00/572bd666a00e7.png'),('hris_hr_department','2'),('hris_payroll_semimonthly_payroll_pagibig','1'),('hris_payroll_semimonthly_payroll_philhealth','1'),('hris_payroll_semimonthly_payroll_sss','1'),('hris_payroll_semimonthly_sched','{\"cutoff_start1\":\"29\",\"cutoff_end1\":\"13\",\"cutoff_pay1\":\"15\",\"cutoff_start2\":\"14\",\"cutoff_end2\":\"28\",\"cutoff_pay2\":\"30\"}'),('hris_payroll_weekly_sched','{\"cutoff_start\":\"1\",\"cutoff_end\":\"5\",\"cutoff_pay\":\"6\"}'),('hris_request_approver','53'),('hris_vp_operations','');
/*!40000 ALTER TABLE `cfg_entry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_address`
--

DROP TABLE IF EXISTS `cnt_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `city` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `is_primary` tinyint(1) NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DCF4B65AEEFE5067` (`user_create_id`),
  KEY `IDX_DCF4B65A2D5B0234` (`city`),
  KEY `IDX_DCF4B65AA393D2FB` (`state`),
  KEY `IDX_DCF4B65A5373C966` (`country`),
  CONSTRAINT `FK_DCF4B65A2D5B0234` FOREIGN KEY (`city`) REFERENCES `world_location` (`id`),
  CONSTRAINT `FK_DCF4B65A5373C966` FOREIGN KEY (`country`) REFERENCES `world_location` (`id`),
  CONSTRAINT `FK_DCF4B65AA393D2FB` FOREIGN KEY (`state`) REFERENCES `world_location` (`id`),
  CONSTRAINT `FK_DCF4B65AEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_address`
--

LOCK TABLES `cnt_address` WRITE;
/*!40000 ALTER TABLE `cnt_address` DISABLE KEYS */;
INSERT INTO `cnt_address` VALUES (1,NULL,'704','San Miguel Avenue',4973,1144,169,0.0000000,0.0000000,0,'2015-08-07 10:56:51'),(2,NULL,'','',4924,1139,169,0.0000000,0.0000000,0,'2015-08-07 10:58:37'),(3,NULL,'704','San Miguel Avenue',4973,1144,169,0.0000000,0.0000000,0,'2015-08-07 11:00:54'),(4,NULL,'','',1670,241,2,0.0000000,0.0000000,0,'2015-08-07 11:08:54'),(5,NULL,'42','Cilantro Street',4976,1144,169,0.0000000,0.0000000,0,'2015-08-07 13:23:06'),(6,NULL,'Unit 7A Belvedere Tower','15 San Miguel Avenue, Ortigas Center',4973,1144,169,6.0000000,6.0000000,0,'2015-08-07 15:09:16'),(7,1,'Unit 102 Legaspi Suites Building','Salcedo Street',4978,1144,169,28.6667000,77.2167000,1,'2015-08-11 17:12:35'),(8,NULL,'','',4978,1144,169,0.0000000,0.0000000,0,'2015-08-11 17:25:12'),(9,NULL,'','',NULL,NULL,NULL,0.0000000,0.0000000,0,'2015-08-11 17:45:06'),(10,NULL,'Unit 102 Legaspi Suites Building','Salcedo Street',4979,1144,169,10.0000000,10.0000000,0,'2015-08-11 18:33:57'),(11,NULL,'102','178 Legaspi Suites Bldg Salcedo St Legaspi Village',4978,1144,169,11.0000000,11.0000000,0,'2015-08-12 16:29:31'),(12,NULL,'1006','Malagasang 1-F',5013,1147,169,12.0000000,12.0000000,0,'2015-08-13 16:56:07'),(13,NULL,'67','Cambridge St Cubao',4970,1144,169,13.0000000,13.0000000,0,'2015-09-01 14:08:08'),(14,NULL,'55 8th Ave','Baltazar St',4972,1144,169,0.0000000,0.0000000,0,'2015-09-01 15:25:55'),(15,NULL,'42','Cilantro Street, Mahogany Place 3',4976,1144,169,15.0000000,15.0000000,0,'2015-09-01 15:41:30'),(16,NULL,'','',NULL,NULL,NULL,0.0000000,0.0000000,0,'2015-09-01 15:41:49'),(17,NULL,'','',NULL,NULL,NULL,0.0000000,0.0000000,0,'2015-09-02 18:13:28'),(18,NULL,'3 ','Dove Street',4970,1144,169,18.0000000,18.0000000,0,'2015-09-09 15:59:20'),(19,NULL,'','',NULL,NULL,NULL,0.0000000,0.0000000,0,'2015-09-09 16:05:54'),(20,NULL,'Blk 59 Lot 2 Purok 2','Reposar St., Upper Bicutan',4976,1144,169,20.0000000,20.0000000,0,'2015-09-09 16:09:05');
/*!40000 ALTER TABLE `cnt_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_contact_person`
--

DROP TABLE IF EXISTS `cnt_contact_person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_contact_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_contact_person`
--

LOCK TABLES `cnt_contact_person` WRITE;
/*!40000 ALTER TABLE `cnt_contact_person` DISABLE KEYS */;
/*!40000 ALTER TABLE `cnt_contact_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_phone`
--

DROP TABLE IF EXISTS `cnt_phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2114359CEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_2114359CEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_phone`
--

LOCK TABLES `cnt_phone` WRITE;
/*!40000 ALTER TABLE `cnt_phone` DISABLE KEYS */;
INSERT INTO `cnt_phone` VALUES (1,1,'Mobile','09166351305',1,'2015-08-05 14:00:03'),(2,1,'Home','9281111',1,'2015-08-05 14:08:45'),(3,1,'Work','1243333',1,'2015-08-05 14:10:41'),(4,1,'Home','9852222',0,'2015-08-05 14:11:26'),(5,1,'Work','2343321',1,'2015-08-05 14:13:18'),(6,30,'Work','8137616',0,'2015-08-12 14:24:10'),(7,30,'Work','8137616',0,'2015-08-12 14:30:45'),(8,30,'Work','8137616',1,'2015-08-12 16:29:26'),(9,31,'Mobile','09059283081',1,'2015-08-13 16:56:00'),(10,30,'Mobile','09172713400',1,'2015-09-01 14:08:03'),(11,38,'Mobile','09989546125',1,'2015-09-01 15:41:45'),(12,38,'Mobile','09166351305',0,'2015-09-01 15:41:57'),(13,39,'Work','0922-8555186',1,'2015-09-09 15:59:06');
/*!40000 ALTER TABLE `cnt_phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_phone_type`
--

DROP TABLE IF EXISTS `cnt_phone_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_phone_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_phone_type`
--

LOCK TABLES `cnt_phone_type` WRITE;
/*!40000 ALTER TABLE `cnt_phone_type` DISABLE KEYS */;
INSERT INTO `cnt_phone_type` VALUES (1,'Work'),(2,'Mobile'),(3,'Home'),(4,'Fax');
/*!40000 ALTER TABLE `cnt_phone_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_type`
--

DROP TABLE IF EXISTS `cnt_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cnt_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cnt_type`
--

LOCK TABLES `cnt_type` WRITE;
/*!40000 ALTER TABLE `cnt_type` DISABLE KEYS */;
INSERT INTO `cnt_type` VALUES (1,'Individual'),(2,'Company');
/*!40000 ALTER TABLE `cnt_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cominfo_phone`
--

DROP TABLE IF EXISTS `cominfo_phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cominfo_phone` (
  `cominfo_id` int(11) NOT NULL,
  `phone_id` int(11) NOT NULL,
  PRIMARY KEY (`cominfo_id`,`phone_id`),
  KEY `IDX_D057E20AD189329C` (`cominfo_id`),
  KEY `IDX_D057E20A3B7323CB` (`phone_id`),
  CONSTRAINT `FK_D057E20A3B7323CB` FOREIGN KEY (`phone_id`) REFERENCES `cnt_phone` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_D057E20AD189329C` FOREIGN KEY (`cominfo_id`) REFERENCES `comp_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cominfo_phone`
--

LOCK TABLES `cominfo_phone` WRITE;
/*!40000 ALTER TABLE `cominfo_phone` DISABLE KEYS */;
INSERT INTO `cominfo_phone` VALUES (1,8);
/*!40000 ALTER TABLE `cominfo_phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comp_profile`
--

DROP TABLE IF EXISTS `comp_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comp_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A3B2C267EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_A3B2C267EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comp_profile`
--

LOCK TABLES `comp_profile` WRITE;
/*!40000 ALTER TABLE `comp_profile` DISABLE KEYS */;
INSERT INTO `comp_profile` VALUES (1,1,'2015-08-05 13:35:05');
/*!40000 ALTER TABLE `comp_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contactperson_phone`
--

DROP TABLE IF EXISTS `contactperson_phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contactperson_phone` (
  `contactperson_id` int(11) NOT NULL,
  `phone_id` int(11) NOT NULL,
  PRIMARY KEY (`contactperson_id`,`phone_id`),
  KEY `IDX_D8688559959E62B0` (`contactperson_id`),
  KEY `IDX_D86885593B7323CB` (`phone_id`),
  CONSTRAINT `FK_D86885593B7323CB` FOREIGN KEY (`phone_id`) REFERENCES `cnt_phone` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_D8688559959E62B0` FOREIGN KEY (`contactperson_id`) REFERENCES `cnt_contact_person` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contactperson_phone`
--

LOCK TABLES `contactperson_phone` WRITE;
/*!40000 ALTER TABLE `contactperson_phone` DISABLE KEYS */;
/*!40000 ALTER TABLE `contactperson_phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_advance`
--

DROP TABLE IF EXISTS `hr_advance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_advance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `releaser_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `date_filed` datetime NOT NULL,
  `date_released` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `flag_deducted` tinyint(1) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_904E2468C03F15C` (`employee_id`),
  KEY `IDX_904E24671C8FDB3` (`releaser_id`),
  KEY `IDX_904E246EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_904E24671C8FDB3` FOREIGN KEY (`releaser_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_904E2468C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_904E246EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_advance`
--

LOCK TABLES `hr_advance` WRITE;
/*!40000 ALTER TABLE `hr_advance` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_advance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_app`
--

DROP TABLE IF EXISTS `hr_app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `benefit_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `choice` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `app_status` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `offer_data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `background_data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `signing_data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `appeared` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_73362912CCCFBA31` (`upload_id`),
  KEY `IDX_73362912B517B89` (`benefit_id`),
  KEY `IDX_73362912EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_73362912B517B89` FOREIGN KEY (`benefit_id`) REFERENCES `hr_benefit` (`id`),
  CONSTRAINT `FK_73362912CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_73362912EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_app`
--

LOCK TABLES `hr_app` WRITE;
/*!40000 ALTER TABLE `hr_app` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_app_checklist`
--

DROP TABLE IF EXISTS `hr_app_checklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_app_checklist` (
  `application_id` int(11) NOT NULL,
  `checklist_id` int(11) NOT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_received` date DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`application_id`,`checklist_id`),
  UNIQUE KEY `UNIQ_82DE3953CCCFBA31` (`upload_id`),
  KEY `IDX_82DE39533E030ACD` (`application_id`),
  KEY `IDX_82DE3953B16D08A7` (`checklist_id`),
  CONSTRAINT `FK_82DE39533E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_82DE3953B16D08A7` FOREIGN KEY (`checklist_id`) REFERENCES `hr_checklist` (`id`),
  CONSTRAINT `FK_82DE3953CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_app_checklist`
--

LOCK TABLES `hr_app_checklist` WRITE;
/*!40000 ALTER TABLE `hr_app_checklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_app_checklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_app_education`
--

DROP TABLE IF EXISTS `hr_app_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_app_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `elementary` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `highschool` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `vocational` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `college` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `post_graduate` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5BD0AAE3E030ACD` (`application_id`),
  KEY `IDX_5BD0AAEEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_5BD0AAE3E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_5BD0AAEEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_app_education`
--

LOCK TABLES `hr_app_education` WRITE;
/*!40000 ALTER TABLE `hr_app_education` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_app_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_app_exam`
--

DROP TABLE IF EXISTS `hr_app_exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_app_exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `exam_date` datetime DEFAULT NULL,
  `exam_time` datetime DEFAULT NULL,
  `exam_result` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `result` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8670419C3E030ACD` (`application_id`),
  KEY `IDX_8670419CEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_8670419C3E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_8670419CEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_app_exam`
--

LOCK TABLES `hr_app_exam` WRITE;
/*!40000 ALTER TABLE `hr_app_exam` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_app_exam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_app_experience`
--

DROP TABLE IF EXISTS `hr_app_experience`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_app_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `name_address_company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `salary_start` int(11) NOT NULL,
  `salary_last` int(11) NOT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5CFD4B403E030ACD` (`application_id`),
  KEY `IDX_5CFD4B40EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_5CFD4B403E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_5CFD4B40EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_app_experience`
--

LOCK TABLES `hr_app_experience` WRITE;
/*!40000 ALTER TABLE `hr_app_experience` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_app_experience` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_app_interview`
--

DROP TABLE IF EXISTS `hr_app_interview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_app_interview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `interview_result` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_11AA68483E030ACD` (`application_id`),
  KEY `IDX_11AA6848EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_11AA68483E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_11AA6848EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_app_interview`
--

LOCK TABLES `hr_app_interview` WRITE;
/*!40000 ALTER TABLE `hr_app_interview` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_app_interview` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_app_other_info`
--

DROP TABLE IF EXISTS `hr_app_other_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_app_other_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `forced_resign` tinyint(1) NOT NULL,
  `crime_convicted` tinyint(1) NOT NULL,
  `serious_disease` tinyint(1) NOT NULL,
  `license` tinyint(1) NOT NULL,
  `license_type` tinyint(1) DEFAULT NULL,
  `sss_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tin_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `philhealth_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pagibig_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FBA0EDC63E030ACD` (`application_id`),
  KEY `IDX_FBA0EDC6EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_FBA0EDC63E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_FBA0EDC6EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_app_other_info`
--

LOCK TABLES `hr_app_other_info` WRITE;
/*!40000 ALTER TABLE `hr_app_other_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_app_other_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_app_profile`
--

DROP TABLE IF EXISTS `hr_app_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_app_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) DEFAULT NULL,
  `my_phone_id` int(11) DEFAULT NULL,
  `contact_phone_id` int(11) DEFAULT NULL,
  `home_address` int(11) DEFAULT NULL,
  `permanent_address` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `birth_date` datetime NOT NULL,
  `birth_place` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `contact_person` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `civil_status` smallint(6) NOT NULL,
  `no_dependents` smallint(6) NOT NULL,
  `spouse_name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `no_children` smallint(6) NOT NULL,
  `father_name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `father_occupation` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `mother_name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `mother_occupation` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FDF57FC43E030ACD` (`application_id`),
  UNIQUE KEY `UNIQ_FDF57FC4921B684` (`my_phone_id`),
  UNIQUE KEY `UNIQ_FDF57FC4A156BF5C` (`contact_phone_id`),
  UNIQUE KEY `UNIQ_FDF57FC4B86EA4D9` (`home_address`),
  UNIQUE KEY `UNIQ_FDF57FC4367E8A28` (`permanent_address`),
  KEY `IDX_FDF57FC4EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_FDF57FC4367E8A28` FOREIGN KEY (`permanent_address`) REFERENCES `cnt_address` (`id`),
  CONSTRAINT `FK_FDF57FC43E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_FDF57FC4921B684` FOREIGN KEY (`my_phone_id`) REFERENCES `cnt_phone` (`id`),
  CONSTRAINT `FK_FDF57FC4A156BF5C` FOREIGN KEY (`contact_phone_id`) REFERENCES `cnt_phone` (`id`),
  CONSTRAINT `FK_FDF57FC4B86EA4D9` FOREIGN KEY (`home_address`) REFERENCES `cnt_address` (`id`),
  CONSTRAINT `FK_FDF57FC4EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_app_profile`
--

LOCK TABLES `hr_app_profile` WRITE;
/*!40000 ALTER TABLE `hr_app_profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_app_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_app_reference`
--

DROP TABLE IF EXISTS `hr_app_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_app_reference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) DEFAULT NULL,
  `phone_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salutation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `relationship` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_70141D6F3E030ACD` (`application_id`),
  KEY `IDX_70141D6F3B7323CB` (`phone_id`),
  KEY `IDX_70141D6FEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_70141D6F3B7323CB` FOREIGN KEY (`phone_id`) REFERENCES `cnt_phone` (`id`),
  CONSTRAINT `FK_70141D6F3E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_70141D6FEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_app_reference`
--

LOCK TABLES `hr_app_reference` WRITE;
/*!40000 ALTER TABLE `hr_app_reference` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_app_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_app_skills`
--

DROP TABLE IF EXISTS `hr_app_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_app_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `computer` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `related` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `hobbies` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AB0B6ABE3E030ACD` (`application_id`),
  KEY `IDX_AB0B6ABEEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_AB0B6ABE3E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_AB0B6ABEEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_app_skills`
--

LOCK TABLES `hr_app_skills` WRITE;
/*!40000 ALTER TABLE `hr_app_skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_app_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_appraisal`
--

DROP TABLE IF EXISTS `hr_appraisal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_appraisal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `preset_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `overall_quali` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `overall_quanti` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7A39D5108C03F15C` (`employee_id`),
  KEY `IDX_7A39D51080688E6F` (`preset_id`),
  KEY `IDX_7A39D510EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_7A39D51080688E6F` FOREIGN KEY (`preset_id`) REFERENCES `hr_appraisal_settings` (`id`),
  CONSTRAINT `FK_7A39D5108C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_7A39D510EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_appraisal`
--

LOCK TABLES `hr_appraisal` WRITE;
/*!40000 ALTER TABLE `hr_appraisal` DISABLE KEYS */;
INSERT INTO `hr_appraisal` VALUES (4,31,2,30,'2014-10-01 00:00:00','2015-10-01 00:00:00','N/A','N/A','Others','2015-10-05 15:51:09'),(5,36,1,30,'2015-04-01 00:00:00','2015-10-01 00:00:00','Poor','30','Regularization','2015-10-05 15:53:22'),(6,31,2,30,'2015-04-01 00:00:00','2015-10-27 00:00:00','N/A','N/A','Others','2015-10-27 14:43:41'),(7,34,2,30,'2015-01-05 00:00:00','2015-10-27 00:00:00','N/A','N/A','Others','2015-10-27 14:45:58'),(10,30,2,30,'2014-11-01 00:00:00','2015-11-01 00:00:00','Very Satisfactory','86','Others','2015-11-16 19:05:57');
/*!40000 ALTER TABLE `hr_appraisal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_appraisal_settings`
--

DROP TABLE IF EXISTS `hr_appraisal_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_appraisal_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `obj_count` int(11) DEFAULT NULL,
  `obj_percentage` double DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BEFA118AEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_BEFA118AEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_appraisal_settings`
--

LOCK TABLES `hr_appraisal_settings` WRITE;
/*!40000 ALTER TABLE `hr_appraisal_settings` DISABLE KEYS */;
INSERT INTO `hr_appraisal_settings` VALUES (1,30,8,7.5,'2015-09-01 17:10:06','Performance Appraisal'),(2,30,6,10,'2015-10-05 15:45:28','Employee Performance Review');
/*!40000 ALTER TABLE `hr_appraisal_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_attendance`
--

DROP TABLE IF EXISTS `hr_attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `status` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `undertime` int(11) DEFAULT NULL,
  `late` int(11) DEFAULT NULL,
  `overtime` int(11) DEFAULT NULL,
  `adjustment_date` datetime DEFAULT NULL,
  `adjusted_time_in` datetime DEFAULT NULL,
  `adjusted_time_out` datetime DEFAULT NULL,
  `adjustment_status` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adjust_approved` datetime DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `overtime_date` datetime DEFAULT NULL,
  `overtime_pending` int(11) DEFAULT NULL,
  `overtime_status` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `overtime_reason` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `overtime_approved` datetime DEFAULT NULL,
  `halfday` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F6DA1CDFCCCFBA31` (`upload_id`),
  UNIQUE KEY `attendance_idx` (`employee_id`,`date`),
  KEY `IDX_F6DA1CDF8C03F15C` (`employee_id`),
  KEY `IDX_F6DA1CDFBB23766C` (`approver_id`),
  KEY `IDX_F6DA1CDFEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_F6DA1CDF8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_F6DA1CDFBB23766C` FOREIGN KEY (`approver_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_F6DA1CDFCCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_F6DA1CDFEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=621 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_attendance`
--

LOCK TABLES `hr_attendance` WRITE;
/*!40000 ALTER TABLE `hr_attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_benefit`
--

DROP TABLE IF EXISTS `hr_benefit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_benefit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `emp_status` longtext COLLATE utf8_unicode_ci,
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `date_create` datetime NOT NULL,
  `department` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  PRIMARY KEY (`id`),
  KEY `IDX_B20EF9AAEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_B20EF9AAEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_benefit`
--

LOCK TABLES `hr_benefit` WRITE;
/*!40000 ALTER TABLE `hr_benefit` DISABLE KEYS */;
INSERT INTO `hr_benefit` VALUES (1,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Monetary Value','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','SSS','','2015-08-05 13:32:18','[\"2\",\"1\",\"14\",\"15\",\"13\"]'),(2,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Monetary Value','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','PhilHealth','','2015-08-05 13:32:18','[\"2\",\"1\",\"14\",\"15\",\"13\"]'),(3,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Monetary Value','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','PAG-IBIG','','2015-08-05 13:32:18','[\"2\",\"1\",\"14\",\"15\",\"13\"]'),(4,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','Sick Leave',NULL,'2015-08-05 13:32:18',''),(5,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','Vacation Leave',NULL,'2015-08-05 13:32:18',''),(6,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:1:{i:1;s:6:\"Female\";}','Maternity Leave',NULL,'2015-08-05 13:32:18',''),(7,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:1:{i:0;s:4:\"Male\";}','Paternity Leave',NULL,'2015-08-05 13:32:18',''),(8,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','Parental Leave',NULL,'2015-08-05 13:32:18',''),(9,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:1:{i:1;s:6:\"Female\";}','Leave for VAWC',NULL,'2015-08-05 13:32:18',''),(10,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:1:{i:0;s:6:\"Female\";}','Special leave for women ',NULL,'2015-08-05 13:32:18',''),(11,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Monetary Value','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','13th month Pay',NULL,'2015-08-05 13:32:18',''),(12,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Monetary Value','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','Separation Pay',NULL,'2015-08-05 13:32:18','');
/*!40000 ALTER TABLE `hr_benefit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_benefit_characteristic`
--

DROP TABLE IF EXISTS `hr_benefit_characteristic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_benefit_characteristic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8A622FC0EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_8A622FC0EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_benefit_characteristic`
--

LOCK TABLES `hr_benefit_characteristic` WRITE;
/*!40000 ALTER TABLE `hr_benefit_characteristic` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_benefit_characteristic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_checklist`
--

DROP TABLE IF EXISTS `hr_checklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_checklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_1BA93EFEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_1BA93EFEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_checklist`
--

LOCK TABLES `hr_checklist` WRITE;
/*!40000 ALTER TABLE `hr_checklist` DISABLE KEYS */;
INSERT INTO `hr_checklist` VALUES (1,1,'NBI CLearance','2015-08-05 13:32:18',''),(2,1,'Police Clearance','2015-08-05 13:32:18',''),(3,1,'Brgy Clearance','2015-08-05 13:32:18',''),(4,1,'Medical','2015-08-05 13:32:18',''),(5,1,'Drug Test','2015-08-05 13:32:18',''),(6,1,'NSO Birth Certificate','2015-08-05 13:32:18',''),(7,1,'Birth Certificate of Dependents','2015-08-05 13:32:18',''),(8,1,'2x2 Picture','2015-08-05 13:32:18',''),(9,1,'Photocopy of SSS No','2015-08-05 13:32:18',''),(10,1,'Photocopy of Tin No','2015-08-05 13:32:18',''),(11,1,'Photocopy of Philhealth No','2015-08-05 13:32:18',''),(12,1,'Photocopy of Pag-ibig No','2015-08-05 13:32:18',''),(13,1,'Photocopy of Diploma','2015-08-05 13:32:18',''),(14,1,'BIR 2305 w/ stamp','2015-08-05 13:32:18',''),(15,1,'1905 w/ stamp','2015-08-05 13:32:18',''),(16,1,'1902 w/ stamp','2015-08-05 13:32:18',''),(17,1,'PMRF','2015-08-05 13:32:18',''),(18,1,'MDF','2015-08-05 13:32:18',''),(19,1,'SSS loan verification','2015-08-05 13:32:18',''),(20,1,'Photocopy of Certificate of Employement (COE)','2015-08-05 13:32:18',''),(21,1,'Photocopy of Clearance','2015-08-05 13:32:18',''),(22,1,'2316 of current year','2015-08-05 13:32:18','');
/*!40000 ALTER TABLE `hr_checklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_department`
--

DROP TABLE IF EXISTS `hr_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_head_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5624F0C45E237E06` (`name`),
  UNIQUE KEY `UNIQ_5624F0C4CF74E3A9` (`dept_head_id`),
  KEY `IDX_5624F0C4727ACA70` (`parent_id`),
  KEY `IDX_5624F0C4EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_5624F0C4727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `hr_department` (`id`),
  CONSTRAINT `FK_5624F0C4CF74E3A9` FOREIGN KEY (`dept_head_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_5624F0C4EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_department`
--

LOCK TABLES `hr_department` WRITE;
/*!40000 ALTER TABLE `hr_department` DISABLE KEYS */;
INSERT INTO `hr_department` VALUES (1,37,3,1,'2015-08-05 13:32:18','Management'),(2,28,1,1,'2015-08-05 13:32:18','Human Resource'),(3,NULL,NULL,1,'2015-08-05 13:32:18','Admin'),(4,NULL,NULL,1,'2015-08-05 13:32:18','Accounting/Finance'),(5,NULL,NULL,1,'2015-08-05 13:32:18','Marketing'),(6,NULL,NULL,1,'2015-08-05 13:32:18','Mechandising'),(7,NULL,NULL,1,'2015-08-05 13:32:18','Sales'),(8,NULL,NULL,1,'2015-08-05 13:32:18','Sales Monitoring'),(9,NULL,NULL,1,'2015-08-05 13:32:18','Purchasing'),(10,NULL,NULL,1,'2015-08-05 13:32:18','Logistic'),(11,NULL,NULL,1,'2015-08-05 13:32:18','Warehousing'),(12,NULL,NULL,1,'2015-08-05 13:32:18','Research and Development'),(13,30,1,1,'2015-08-05 13:32:18','Quality Assurance'),(14,31,1,1,'2015-08-05 13:32:18','Production'),(15,29,1,30,'2015-09-01 13:40:26','Project Management'),(16,NULL,NULL,30,'2015-09-01 13:51:07','President/CEO');
/*!40000 ALTER TABLE `hr_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_dependent`
--

DROP TABLE IF EXISTS `hr_dependent`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_dependent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `relation` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `flag_qualified` tinyint(1) NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E64389648C03F15C` (`employee_id`),
  KEY `IDX_E6438964EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_E64389648C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_E6438964EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_dependent`
--

LOCK TABLES `hr_dependent` WRITE;
/*!40000 ALTER TABLE `hr_dependent` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_dependent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_downloadables`
--

DROP TABLE IF EXISTS `hr_downloadables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_downloadables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `upload_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5D195B26CCCFBA31` (`upload_id`),
  KEY `IDX_5D195B26EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_5D195B26CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_5D195B26EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_downloadables`
--

LOCK TABLES `hr_downloadables` WRITE;
/*!40000 ALTER TABLE `hr_downloadables` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_downloadables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_employee`
--

DROP TABLE IF EXISTS `hr_employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_id` int(11) DEFAULT NULL,
  `job_title_id` int(11) DEFAULT NULL,
  `job_level_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `schedules_id` int(11) DEFAULT NULL,
  `pay_period_id` int(11) DEFAULT NULL,
  `pay_schedule_id` int(11) DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `first_name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employment_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pay_rate` decimal(10,2) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `email` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_hired` date NOT NULL,
  `marital_status` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flag_new` tinyint(1) NOT NULL,
  `dependents` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:json_array)',
  `exemption` tinyint(1) NOT NULL,
  `date_create` datetime NOT NULL,
  `cashbond_rate` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E67AB75B3E030ACD` (`application_id`),
  KEY `IDX_E67AB75BAE80F5DF` (`department_id`),
  KEY `IDX_E67AB75B6DD822C6` (`job_title_id`),
  KEY `IDX_E67AB75B38F6EEDC` (`job_level_id`),
  KEY `IDX_E67AB75B64D218E` (`location_id`),
  KEY `IDX_E67AB75B116C90BC` (`schedules_id`),
  KEY `IDX_E67AB75BC185C5A2` (`pay_period_id`),
  KEY `IDX_E67AB75B7C5F773C` (`pay_schedule_id`),
  KEY `IDX_E67AB75B727ACA70` (`parent_id`),
  KEY `IDX_E67AB75BEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_E67AB75B116C90BC` FOREIGN KEY (`schedules_id`) REFERENCES `hr_schedule` (`id`),
  CONSTRAINT `FK_E67AB75B38F6EEDC` FOREIGN KEY (`job_level_id`) REFERENCES `hr_job_level` (`id`),
  CONSTRAINT `FK_E67AB75B3E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_E67AB75B64D218E` FOREIGN KEY (`location_id`) REFERENCES `hr_location` (`id`),
  CONSTRAINT `FK_E67AB75B6DD822C6` FOREIGN KEY (`job_title_id`) REFERENCES `hr_job_title` (`id`),
  CONSTRAINT `FK_E67AB75B727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_E67AB75B7C5F773C` FOREIGN KEY (`pay_schedule_id`) REFERENCES `pay_period` (`id`),
  CONSTRAINT `FK_E67AB75BAE80F5DF` FOREIGN KEY (`department_id`) REFERENCES `hr_department` (`id`),
  CONSTRAINT `FK_E67AB75BC185C5A2` FOREIGN KEY (`pay_period_id`) REFERENCES `pay_period` (`id`),
  CONSTRAINT `FK_E67AB75BEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_employee`
--

LOCK TABLES `hr_employee` WRITE;
/*!40000 ALTER TABLE `hr_employee` DISABLE KEYS */;
INSERT INTO `hr_employee` VALUES (28,2,53,3,6,9,2,1,NULL,NULL,1,'Lea','Mirando','Martin','Female','Regular',15000.00,1,'lea@quadrantalpha.com','00028','2013-09-01','Married',0,'[{\"name\":\"Luis Kyle M. Chong\",\"relationship\":\"Child\",\"birthdate\":\"04\\/19\\/2004\",\"remarks\":\"Living Along with Employee\",\"qualified\":null}]',1,'2015-08-12 14:12:37',0.00),(29,15,55,3,6,9,2,1,NULL,NULL,30,'Jovel','Vilbar','San Juan','Female','Regular',15000.00,1,'vhel@quadrantalpha.com','00029','2014-04-01',NULL,0,NULL,0,'2015-08-13 16:39:09',0.00),(30,13,48,1,6,9,2,1,NULL,NULL,30,'Jacinto','Pinto','Gunio Jr.','Male','Regular',15000.00,1,'jun@quadrantalpha.com','00030','2013-10-28',NULL,0,NULL,0,'2015-09-01 13:04:55',0.00),(31,14,54,3,6,9,2,1,NULL,NULL,30,'Lord Wally','Edovas','Noveno','Male','Regular',15000.00,1,'wally@quadrantalpha.com','00031','2014-10-01',NULL,0,NULL,0,'2015-09-01 13:09:17',0.00),(34,14,56,1,6,9,2,1,NULL,NULL,30,'Aurora Terese','Lubiano','Paulo','Female','Regular',15000.00,1,'terese@quadrantalpha.com','00034','2014-07-01',NULL,0,NULL,0,'2015-09-01 13:22:23',0.00),(35,14,56,1,6,9,2,1,NULL,NULL,30,'Karlo David','Ilagan','Laquian','Male','Regular',15000.00,1,'karlo@quadrantalpha.com','00035','2015-01-02',NULL,0,NULL,0,'2015-09-01 13:25:03',0.00),(36,14,56,1,6,9,2,1,NULL,NULL,30,'Richard Dale','Magadia','Umayan','Male','Regular',15000.00,1,'richard@quadrantalpha.com','00036','2015-04-01',NULL,0,NULL,0,'2015-09-01 13:28:36',0.00),(37,1,1,4,6,9,2,2,NULL,NULL,30,'Ashley Jorge','Baraquiel','Co Kehyeng','Male','Regular',15000.00,1,'ashley@quadrantalpha.com','00037','2013-06-26',NULL,0,NULL,0,'2015-09-01 13:46:50',0.00),(38,1,62,4,6,9,2,2,NULL,NULL,1,'Dexter','Pena','Co Kehyeng','Male','Regular',15000.00,1,'dexter@quadrantalpha.com','00038','2013-06-26',NULL,0,NULL,0,'2015-09-21 11:39:37',0.00),(40,14,54,1,6,10,2,1,NULL,NULL,30,'Rommel','Santiago','Pascual','Male','Contractual',15000.00,1,'rommel@quadrantalpha.com','00040','2015-10-01',NULL,0,NULL,0,'2015-10-06 14:02:51',0.00),(41,7,30,1,6,9,2,1,NULL,29,1,'June Clyde','Salvador','Garcia','Male','Probationary',1.00,1,'cl@cl','00079','2016-05-05',NULL,0,NULL,0,'2016-05-05 14:34:22',0.00);
/*!40000 ALTER TABLE `hr_employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_employee_benefits`
--

DROP TABLE IF EXISTS `hr_employee_benefits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_employee_benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `benefit_id` int(11) DEFAULT NULL,
  `leave_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1CAB7B718C03F15C` (`employee_id`),
  KEY `IDX_1CAB7B71B517B89` (`benefit_id`),
  KEY `IDX_1CAB7B711B2ADB5C` (`leave_id`),
  CONSTRAINT `FK_1CAB7B711B2ADB5C` FOREIGN KEY (`leave_id`) REFERENCES `leave_type` (`id`),
  CONSTRAINT `FK_1CAB7B718C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_1CAB7B71B517B89` FOREIGN KEY (`benefit_id`) REFERENCES `hr_benefit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_employee_benefits`
--

LOCK TABLES `hr_employee_benefits` WRITE;
/*!40000 ALTER TABLE `hr_employee_benefits` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_employee_benefits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_employee_checklist`
--

DROP TABLE IF EXISTS `hr_employee_checklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_employee_checklist` (
  `profile_id` int(11) NOT NULL,
  `checklist_id` int(11) NOT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_received` date DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`profile_id`,`checklist_id`),
  UNIQUE KEY `UNIQ_21E402ACCCCFBA31` (`upload_id`),
  KEY `IDX_21E402ACCCFA12B8` (`profile_id`),
  KEY `IDX_21E402ACB16D08A7` (`checklist_id`),
  CONSTRAINT `FK_21E402ACB16D08A7` FOREIGN KEY (`checklist_id`) REFERENCES `hr_checklist` (`id`),
  CONSTRAINT `FK_21E402ACCCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_21E402ACCCFA12B8` FOREIGN KEY (`profile_id`) REFERENCES `hr_employee_profile` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_employee_checklist`
--

LOCK TABLES `hr_employee_checklist` WRITE;
/*!40000 ALTER TABLE `hr_employee_checklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_employee_checklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_employee_leaves`
--

DROP TABLE IF EXISTS `hr_employee_leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_employee_leaves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `leave_id` int(11) DEFAULT NULL,
  `avail_leaves` double NOT NULL,
  `leave_year` int(11) NOT NULL,
  `approved_count` int(11) DEFAULT NULL,
  `pending_count` int(11) DEFAULT NULL,
  `used_count` int(11) DEFAULT NULL,
  `accumulated_leave` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_21E32D68C03F15C` (`employee_id`),
  KEY `IDX_21E32D61B2ADB5C` (`leave_id`),
  CONSTRAINT `FK_21E32D61B2ADB5C` FOREIGN KEY (`leave_id`) REFERENCES `leave_type` (`id`),
  CONSTRAINT `FK_21E32D68C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_employee_leaves`
--

LOCK TABLES `hr_employee_leaves` WRITE;
/*!40000 ALTER TABLE `hr_employee_leaves` DISABLE KEYS */;
INSERT INTO `hr_employee_leaves` VALUES (17,28,2,5,2015,NULL,NULL,NULL,NULL),(18,28,4,12,2015,NULL,NULL,NULL,NULL),(19,28,6,3,2015,NULL,NULL,NULL,NULL),(20,28,7,7,2015,NULL,NULL,NULL,NULL),(21,28,8,2,2015,NULL,NULL,NULL,NULL),(22,28,9,60,2015,NULL,NULL,NULL,NULL),(23,28,10,60,2015,NULL,NULL,NULL,NULL),(24,28,11,78,2015,NULL,NULL,NULL,NULL),(25,29,2,4,2015,1,2,NULL,NULL),(26,29,4,12,2015,NULL,NULL,NULL,NULL),(27,29,6,3,2015,NULL,NULL,NULL,NULL),(28,29,7,7,2015,NULL,NULL,NULL,NULL),(29,29,8,2,2015,NULL,NULL,NULL,NULL),(30,29,9,60,2015,NULL,NULL,NULL,NULL),(31,29,10,60,2015,NULL,NULL,NULL,NULL),(32,29,11,78,2015,NULL,NULL,NULL,NULL),(33,30,2,5,2015,NULL,1,NULL,NULL),(34,30,4,12,2015,NULL,NULL,NULL,NULL),(35,30,5,7,2015,NULL,NULL,NULL,NULL),(36,30,6,3,2015,NULL,NULL,NULL,NULL),(37,30,7,7,2015,NULL,NULL,NULL,NULL),(38,30,8,2,2015,NULL,NULL,NULL,NULL),(39,31,2,5,2015,NULL,NULL,NULL,NULL),(40,31,4,12,2015,NULL,NULL,NULL,NULL),(41,31,5,7,2015,NULL,NULL,NULL,NULL),(42,31,6,3,2015,NULL,NULL,NULL,NULL),(43,31,7,7,2015,NULL,NULL,NULL,NULL),(44,31,8,2,2015,NULL,NULL,NULL,NULL),(57,34,2,5,2015,NULL,1,NULL,NULL),(58,34,4,12,2015,NULL,NULL,NULL,NULL),(59,34,6,3,2015,NULL,NULL,NULL,NULL),(60,34,7,7,2015,NULL,NULL,NULL,NULL),(61,34,8,2,2015,NULL,NULL,NULL,NULL),(62,34,9,60,2015,NULL,NULL,NULL,NULL),(63,34,10,60,2015,NULL,NULL,NULL,NULL),(64,34,11,78,2015,NULL,NULL,NULL,NULL),(65,35,2,5,2015,NULL,NULL,NULL,NULL),(66,35,4,12,2015,NULL,NULL,NULL,NULL),(67,35,5,7,2015,NULL,NULL,NULL,NULL),(68,35,6,3,2015,NULL,NULL,NULL,NULL),(69,35,7,7,2015,NULL,NULL,NULL,NULL),(70,35,8,2,2015,NULL,NULL,NULL,NULL),(71,36,6,3,2015,NULL,NULL,NULL,NULL),(72,36,7,7,2015,NULL,5,NULL,NULL),(73,36,8,2,2015,NULL,NULL,NULL,NULL),(74,37,2,5,2015,NULL,NULL,NULL,NULL),(75,37,4,12,2015,NULL,NULL,NULL,NULL),(76,37,5,7,2015,NULL,NULL,NULL,NULL),(77,37,6,3,2015,NULL,NULL,NULL,NULL),(78,37,7,7,2015,NULL,NULL,NULL,NULL),(79,37,8,2,2015,NULL,NULL,NULL,NULL),(80,38,2,5,2015,NULL,NULL,NULL,NULL),(81,38,4,12,2015,NULL,NULL,NULL,NULL),(82,38,5,7,2015,NULL,NULL,NULL,NULL),(83,38,6,3,2015,NULL,NULL,NULL,NULL),(84,38,7,7,2015,NULL,NULL,NULL,NULL),(85,38,8,2,2015,NULL,NULL,NULL,NULL),(92,41,6,3,2016,NULL,NULL,NULL,NULL),(93,41,7,7,2016,NULL,NULL,NULL,NULL),(94,41,8,2,2016,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `hr_employee_leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_employee_profile`
--

DROP TABLE IF EXISTS `hr_employee_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_employee_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `tin` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sss` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `philhealth` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pagibig` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank_account` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_15ACC9148C03F15C` (`employee_id`),
  UNIQUE KEY `UNIQ_15ACC914CCCFBA31` (`upload_id`),
  KEY `IDX_15ACC914F5B7AF75` (`address_id`),
  CONSTRAINT `FK_15ACC9148C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_15ACC914CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_15ACC914F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `cnt_address` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_employee_profile`
--

LOCK TABLES `hr_employee_profile` WRITE;
/*!40000 ALTER TABLE `hr_employee_profile` DISABLE KEYS */;
INSERT INTO `hr_employee_profile` VALUES (28,28,19,13,'1975-01-19','202-228-070-000','33-2381052-1','1905-0165-5787','',NULL),(29,29,NULL,12,'1992-12-28','463-257-330-000','34-3810615-9','08-025556083-6','1210-8810-2040',NULL),(30,30,23,19,'2015-09-09','','','','',NULL),(31,31,NULL,14,'1986-03-22','302-808-617','34-0576111-6','','',NULL),(34,34,NULL,17,'1993-10-19','','','','',NULL),(35,35,NULL,16,'1990-05-07','','','','',NULL),(36,36,NULL,15,'1994-12-05','','','','',NULL),(37,37,21,18,'2015-09-09','218-555-777','1800-966','','',NULL),(38,38,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(40,40,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(41,41,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `hr_employee_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_evaluator`
--

DROP TABLE IF EXISTS `hr_evaluator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_evaluator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appraisal_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `status` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `quali_rating` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `quanti_rating` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `kpi_details` longtext COLLATE utf8_unicode_ci,
  `pqc_details` longtext COLLATE utf8_unicode_ci,
  `comments` longtext COLLATE utf8_unicode_ci,
  `date_create` datetime NOT NULL,
  `date_evaluated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_28D866CFDD670628` (`appraisal_id`),
  KEY `IDX_28D866CF8C03F15C` (`employee_id`),
  KEY `IDX_28D866CFEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_28D866CF8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_28D866CFDD670628` FOREIGN KEY (`appraisal_id`) REFERENCES `hr_appraisal` (`id`),
  CONSTRAINT `FK_28D866CFEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_evaluator`
--

LOCK TABLES `hr_evaluator` WRITE;
/*!40000 ALTER TABLE `hr_evaluator` DISABLE KEYS */;
INSERT INTO `hr_evaluator` VALUES (5,4,37,NULL,'Pending','N/A','N/A',NULL,NULL,NULL,'2015-10-05 15:51:09',NULL),(6,5,37,NULL,'Pending','N/A','N/A',NULL,NULL,NULL,'2015-10-05 15:53:22',NULL),(7,5,31,NULL,'Completed','Poor','30','a:9:{i:1;a:5:{s:9:\"objective\";s:53:\"Finished each task with quality code 90% of the time.\";s:7:\"percent\";s:4:\"7.5%\";s:5:\"score\";s:1:\"0\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:4:\"7.5%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:2;a:5:{s:9:\"objective\";s:24:\"Meet deadlines set 100%.\";s:7:\"percent\";s:4:\"7.5%\";s:5:\"score\";s:1:\"0\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:4:\"7.5%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:3;a:5:{s:9:\"objective\";s:105:\"Low percentage of issues reported on modules worked on. No more than 50% of modules to be reported broken\";s:7:\"percent\";s:4:\"7.5%\";s:5:\"score\";s:1:\"0\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:4:\"7.5%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:4;a:5:{s:9:\"objective\";s:98:\"No late of more than 3 times the next 6 months.  During those lates each not exceeding 10 minutes.\";s:7:\"percent\";s:4:\"7.5%\";s:5:\"score\";s:1:\"0\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:4:\"7.5%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:5;a:5:{s:9:\"objective\";s:48:\"Communicates via email and skype properly daily.\";s:7:\"percent\";s:4:\"7.5%\";s:5:\"score\";s:1:\"0\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:4:\"7.5%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:6;a:5:{s:9:\"objective\";s:4:\"Test\";s:7:\"percent\";s:4:\"7.5%\";s:5:\"score\";s:1:\"0\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:4:\"7.5%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:7;a:5:{s:9:\"objective\";s:4:\"Test\";s:7:\"percent\";s:4:\"7.5%\";s:5:\"score\";s:1:\"0\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:4:\"7.5%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:8;a:5:{s:9:\"objective\";s:4:\"Test\";s:7:\"percent\";s:4:\"7.5%\";s:5:\"score\";s:1:\"0\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:4:\"7.5%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}s:7:\"comment\";s:0:\"\";}','a:6:{i:0;a:3:{s:7:\"percent\";s:2:\"8%\";s:5:\"score\";s:1:\"6\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:2:\"8%\";}}i:1;a:3:{s:7:\"percent\";s:2:\"8%\";s:5:\"score\";s:1:\"6\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:2:\"8%\";}}i:2;a:3:{s:7:\"percent\";s:2:\"8%\";s:5:\"score\";s:1:\"6\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:2:\"8%\";}}i:3;a:3:{s:7:\"percent\";s:2:\"6%\";s:5:\"score\";s:1:\"4\";s:5:\"grade\";a:5:{i:0;s:2:\"1%\";i:1;s:2:\"2%\";i:2;s:2:\"4%\";i:3;s:2:\"5%\";i:4;s:2:\"6%\";}}i:4;a:3:{s:7:\"percent\";s:2:\"5%\";s:5:\"score\";s:1:\"4\";s:5:\"grade\";a:5:{i:0;s:2:\"1%\";i:1;s:2:\"2%\";i:2;s:2:\"3%\";i:3;s:2:\"4%\";i:4;s:2:\"5%\";}}i:5;a:3:{s:7:\"percent\";s:2:\"5%\";s:5:\"score\";s:1:\"4\";s:5:\"grade\";a:5:{i:0;s:2:\"1%\";i:1;s:2:\"2%\";i:2;s:2:\"3%\";i:3;s:2:\"4%\";i:4;s:2:\"5%\";}}}','a:3:{i:0;s:33:\"Reliable when it comes to tasks. \";i:1;s:59:\"Requires improvement on coding standard and coding styles. \";i:2;s:0:\"\";}','2015-10-05 15:53:22','2015-10-05 17:25:14'),(8,6,37,NULL,'Pending','N/A','N/A',NULL,NULL,NULL,'2015-10-27 14:43:41',NULL),(9,7,37,NULL,'Pending','N/A','N/A',NULL,NULL,NULL,'2015-10-27 14:45:58',NULL),(10,7,31,NULL,'Pending','N/A','N/A',NULL,NULL,NULL,'2015-10-27 14:45:59',NULL),(14,10,37,NULL,'Pending','N/A','N/A',NULL,NULL,NULL,'2015-11-16 19:05:57',NULL),(15,10,31,NULL,'Completed','Very Satisfactory','86','a:7:{i:1;a:5:{s:9:\"objective\";s:13:\"Job Knowldege\";s:7:\"percent\";s:3:\"10%\";s:5:\"score\";s:1:\"8\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"4%\";i:2;s:2:\"6%\";i:3;s:2:\"8%\";i:4;s:3:\"10%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:2;a:5:{s:9:\"objective\";s:12:\"Work Quality\";s:7:\"percent\";s:3:\"10%\";s:5:\"score\";s:1:\"9\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"4%\";i:2;s:2:\"6%\";i:3;s:2:\"8%\";i:4;s:3:\"10%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:3;a:5:{s:9:\"objective\";s:10:\"Attendance\";s:7:\"percent\";s:3:\"10%\";s:5:\"score\";s:1:\"6\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"4%\";i:2;s:2:\"6%\";i:3;s:2:\"8%\";i:4;s:3:\"10%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:4;a:5:{s:9:\"objective\";s:10:\"Initiative\";s:7:\"percent\";s:3:\"10%\";s:5:\"score\";s:1:\"8\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"4%\";i:2;s:2:\"6%\";i:3;s:2:\"8%\";i:4;s:3:\"10%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:5;a:5:{s:9:\"objective\";s:13:\"Communication\";s:7:\"percent\";s:3:\"10%\";s:5:\"score\";s:1:\"7\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"4%\";i:2;s:2:\"6%\";i:3;s:2:\"8%\";i:4;s:3:\"10%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}i:6;a:5:{s:9:\"objective\";s:13:\"Dependability\";s:7:\"percent\";s:3:\"10%\";s:5:\"score\";s:1:\"8\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"4%\";i:2;s:2:\"6%\";i:3;s:2:\"8%\";i:4;s:3:\"10%\";}s:11:\"description\";a:5:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";i:3;s:0:\"\";i:4;s:0:\"\";}}s:7:\"comment\";s:0:\"\";}','a:6:{i:0;a:3:{s:7:\"percent\";s:2:\"8%\";s:5:\"score\";s:1:\"8\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:2:\"8%\";}}i:1;a:3:{s:7:\"percent\";s:2:\"8%\";s:5:\"score\";s:1:\"8\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:2:\"8%\";}}i:2;a:3:{s:7:\"percent\";s:2:\"8%\";s:5:\"score\";s:1:\"8\";s:5:\"grade\";a:5:{i:0;s:2:\"2%\";i:1;s:2:\"3%\";i:2;s:2:\"5%\";i:3;s:2:\"6%\";i:4;s:2:\"8%\";}}i:3;a:3:{s:7:\"percent\";s:2:\"6%\";s:5:\"score\";s:1:\"6\";s:5:\"grade\";a:5:{i:0;s:2:\"1%\";i:1;s:2:\"2%\";i:2;s:2:\"4%\";i:3;s:2:\"5%\";i:4;s:2:\"6%\";}}i:4;a:3:{s:7:\"percent\";s:2:\"5%\";s:5:\"score\";s:1:\"5\";s:5:\"grade\";a:5:{i:0;s:2:\"1%\";i:1;s:2:\"2%\";i:2;s:2:\"3%\";i:3;s:2:\"4%\";i:4;s:2:\"5%\";}}i:5;a:3:{s:7:\"percent\";s:2:\"5%\";s:5:\"score\";s:1:\"5\";s:5:\"grade\";a:5:{i:0;s:2:\"1%\";i:1;s:2:\"2%\";i:2;s:2:\"3%\";i:3;s:2:\"4%\";i:4;s:2:\"5%\";}}}','a:3:{i:0;s:0:\"\";i:1;s:0:\"\";i:2;s:0:\"\";}','2015-11-16 19:05:57','2015-11-16 19:35:57');
/*!40000 ALTER TABLE `hr_evaluator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_event`
--

DROP TABLE IF EXISTS `hr_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL,
  `holiday_type` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1D8FE2FDEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_1D8FE2FDEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_event`
--

LOCK TABLES `hr_event` WRITE;
/*!40000 ALTER TABLE `hr_event` DISABLE KEYS */;
INSERT INTO `hr_event` VALUES (1,39,'2015-09-25 17:00:00','2015-09-25 19:00:00','Company Event','2015-09-09 15:18:49','10th Floor Moon Cake Festival Game');
/*!40000 ALTER TABLE `hr_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_holiday`
--

DROP TABLE IF EXISTS `hr_holiday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_holiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_321F4B81EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_321F4B81EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_holiday`
--

LOCK TABLES `hr_holiday` WRITE;
/*!40000 ALTER TABLE `hr_holiday` DISABLE KEYS */;
INSERT INTO `hr_holiday` VALUES (1,1,'2015-01-01','Regular Holiday','New Year\'s Day','2015-08-05 13:32:18'),(2,1,'2015-01-02','Special Non-Working','Additional special non-working day','2015-08-05 13:32:18'),(3,1,'2015-02-19','Special Non-Working','Chinese New Year','2015-08-05 13:32:18'),(4,1,'2015-04-02','Regular Holiday','Maundy Thursday','2015-08-05 13:32:18'),(5,1,'2015-04-03','Regular Holiday','Good Friday','2015-08-05 13:32:18'),(6,1,'2015-04-04','Special Non-Working','Black Saturday','2015-08-05 13:32:18'),(7,1,'2015-04-09','Regular Holiday','Araw Ng Kagitingan','2015-08-05 13:32:18'),(8,1,'2015-05-01','Regular Holiday','Labor Day','2015-08-05 13:32:18'),(9,1,'2015-06-12','Regular Holiday','Independence Day','2015-08-05 13:32:18'),(10,1,'2015-08-21','Special Non-Working','Ninoy Aquino Day','2015-08-05 13:32:18'),(11,1,'2015-08-31','Regular Holiday','National Heroes Day','2015-08-05 13:32:18'),(12,1,'2015-11-01','Special Non-Working','All Saints Day','2015-08-05 13:32:18'),(13,1,'2015-11-30','Regular Holiday','Bonifacio Day','2015-08-05 13:32:18'),(14,1,'2015-12-24','Special Non-Working','Additional special non-working day','2015-08-05 13:32:18'),(15,1,'2015-12-25','Regular Holiday','Christmas Day','2015-08-05 13:32:18'),(16,1,'2015-12-30','Regular Holiday','Rizal Day','2015-08-05 13:32:18'),(17,1,'2015-12-31','Special Non-Working','Last day of the year','2015-08-05 13:32:18'),(18,39,'2015-09-24','Special Non-Working','Id-ul-Adha (Feast of the Sacrifice)	','2015-09-09 15:32:08');
/*!40000 ALTER TABLE `hr_holiday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_incident_report`
--

DROP TABLE IF EXISTS `hr_incident_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_incident_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `date_filed` datetime NOT NULL,
  `date_happened` datetime NOT NULL,
  `products` longtext COLLATE utf8_unicode_ci,
  `concerns` longtext COLLATE utf8_unicode_ci,
  `action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_DF7A907CA76ED395` (`user_id`),
  KEY `IDX_DF7A907C3E23E247` (`dept_id`),
  KEY `IDX_DF7A907C8C03F15C` (`employee_id`),
  KEY `IDX_DF7A907C64D218E` (`location_id`),
  KEY `IDX_DF7A907CEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_DF7A907C3E23E247` FOREIGN KEY (`dept_id`) REFERENCES `hr_department` (`id`),
  CONSTRAINT `FK_DF7A907C64D218E` FOREIGN KEY (`location_id`) REFERENCES `hr_location` (`id`),
  CONSTRAINT `FK_DF7A907C8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_DF7A907CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_DF7A907CEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_incident_report`
--

LOCK TABLES `hr_incident_report` WRITE;
/*!40000 ALTER TABLE `hr_incident_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_incident_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_issued_property`
--

DROP TABLE IF EXISTS `hr_issued_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_issued_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `item_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `item_name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_code` tinytext COLLATE utf8_unicode_ci,
  `item_desc` longtext COLLATE utf8_unicode_ci,
  `addtl_details` longtext COLLATE utf8_unicode_ci,
  `date_issued` datetime NOT NULL,
  `date_returned` datetime DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C29BA927CCCFBA31` (`upload_id`),
  KEY `IDX_C29BA9278C03F15C` (`employee_id`),
  KEY `IDX_C29BA927EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_C29BA9278C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_C29BA927CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_C29BA927EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_issued_property`
--

LOCK TABLES `hr_issued_property` WRITE;
/*!40000 ALTER TABLE `hr_issued_property` DISABLE KEYS */;
INSERT INTO `hr_issued_property` VALUES (1,34,30,NULL,'2','Laptop',NULL,NULL,'{\"brand\":\"Lenovo\",\"model\":\"Z4070 - 59436162\",\"color\":\"Black\",\"snid\":\"QATSI357157B\"}','2015-04-20 00:00:00',NULL,'2015-09-01 15:54:48'),(3,30,30,NULL,'0','Samsung Tab3','RF2F109VCOJ','White T210',NULL,'2014-01-28 00:00:00',NULL,'2015-10-06 13:47:16'),(4,31,30,NULL,'2','Laptop',NULL,NULL,'{\"brand\":\"Macbook Air\",\"model\":\"MD760ZP\\/B\",\"color\":\"Silver Gray\",\"snid\":\"COZMHIZHG085\"}','2014-11-17 00:00:00',NULL,'2015-10-06 13:51:52'),(5,29,30,NULL,'2','Laptop',NULL,NULL,'{\"brand\":\"Lenovo\",\"model\":\"G410\",\"color\":\"Black\",\"snid\":\"99994764952952\"}','2014-04-22 00:00:00',NULL,'2015-10-06 13:55:52'),(6,30,30,NULL,'2','Laptop',NULL,NULL,'{\"brand\":\"Lenovo\",\"model\":\"Flex I5\",\"color\":\"Red\\/Black\",\"snid\":\"WB15101038\"}','2014-11-17 00:00:00',NULL,'2015-10-06 13:57:50'),(7,40,30,NULL,'2','Laptop',NULL,NULL,'{\"brand\":\"Lenovo\",\"model\":\"Z4070 - 59436162\",\"color\":\"Black\",\"snid\":\"YB06851178\"}','2015-10-01 00:00:00',NULL,'2015-10-06 14:41:54');
/*!40000 ALTER TABLE `hr_issued_property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_job_level`
--

DROP TABLE IF EXISTS `hr_job_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_job_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C6658FA3EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_C6658FA3EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_job_level`
--

LOCK TABLES `hr_job_level` WRITE;
/*!40000 ALTER TABLE `hr_job_level` DISABLE KEYS */;
INSERT INTO `hr_job_level` VALUES (1,1,'Rank and File','2015-08-05 13:32:18'),(2,1,'Officer','2015-08-05 13:32:18'),(3,1,'Managerial','2015-08-05 13:32:18'),(4,1,'Executive','2015-08-05 13:32:18');
/*!40000 ALTER TABLE `hr_job_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_job_title`
--

DROP TABLE IF EXISTS `hr_job_title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_job_title` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `type` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_77B93BDB3E23E247` (`dept_id`),
  KEY `IDX_77B93BDB727ACA70` (`parent_id`),
  KEY `IDX_77B93BDBEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_77B93BDB3E23E247` FOREIGN KEY (`dept_id`) REFERENCES `hr_department` (`id`),
  CONSTRAINT `FK_77B93BDB727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `hr_job_title` (`id`),
  CONSTRAINT `FK_77B93BDBEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_job_title`
--

LOCK TABLES `hr_job_title` WRITE;
/*!40000 ALTER TABLE `hr_job_title` DISABLE KEYS */;
INSERT INTO `hr_job_title` VALUES (1,1,1,1,'Rank','President/CEO','President','2015-08-05 13:32:18'),(2,1,NULL,1,'Rank','VP Operations','','2015-08-05 13:32:18'),(5,2,NULL,1,'Rank','HR Recruitment Assistant','','2015-08-05 13:32:18'),(7,3,NULL,1,'Rank','Admin Officer','','2015-08-05 13:32:18'),(9,3,NULL,1,'Rank','IT/Technical Support Officer','','2015-08-05 13:32:18'),(10,3,NULL,1,'Rank','Document Controller Specialist','','2015-08-05 13:32:18'),(11,3,NULL,1,'Rank','Plant Admin','','2015-08-05 13:32:18'),(13,4,NULL,1,'Rank','Accounting Head','','2015-08-05 13:32:18'),(18,4,NULL,1,'Rank','Treasury Officer','','2015-08-05 13:32:18'),(19,5,NULL,1,'Rank','Trade Marketing Specialist (Key Accounts)','','2015-08-05 13:32:18'),(20,5,NULL,1,'Rank','Trade Marketing Specialist (Distributor)','','2015-08-05 13:32:18'),(22,5,NULL,1,'Rank','Graphic Artist','','2015-08-05 13:32:18'),(23,5,NULL,1,'Rank','Business Development Manager','','2015-08-05 13:32:18'),(24,6,NULL,1,'Rank','Merchandising Office-in-Charge','','2015-08-05 13:32:18'),(25,6,NULL,1,'Rank','Merchandising Operations Supervisor','','2015-08-05 13:32:18'),(26,6,NULL,1,'Rank','Merchandising Support Assistant','','2015-08-05 13:32:18'),(27,6,NULL,1,'Rank','Sales Commando','','2015-08-05 13:32:18'),(28,7,NULL,1,'Rank','National Sales Manager','','2015-08-05 13:32:18'),(29,7,NULL,1,'Rank','Sales Admin Assistant','','2015-08-05 13:32:18'),(30,7,NULL,1,'Rank','Junior Sales Manager','','2015-08-05 13:32:18'),(31,7,NULL,1,'Rank','Distributor Sales Specialist','','2015-08-05 13:32:18'),(32,7,NULL,1,'Rank','Key Account Specialist','','2015-08-05 13:32:18'),(34,7,NULL,1,'Rank','Senior Accounts Specialist','','2015-08-05 13:32:18'),(35,8,NULL,1,'Rank','Sales Monitoring Officer-in-Charge','','2015-08-05 13:32:18'),(36,8,NULL,1,'Rank','Sales Monitoring Staff','','2015-08-05 13:32:18'),(37,9,NULL,1,'Rank','Purchasing Officer-in-Charge','','2015-08-05 13:32:18'),(38,10,NULL,1,'Rank','Logistic Officer-in-Charge','','2015-08-05 13:32:18'),(39,10,NULL,1,'Rank','Logistic Assistant','','2015-08-05 13:32:18'),(40,10,NULL,1,'Rank','Logistic Staff','','2015-08-05 13:32:18'),(43,11,NULL,1,'Rank','Warehousing Supervisor','','2015-08-05 13:32:18'),(44,11,NULL,1,'Rank','Warehousing Assistant','','2015-08-05 13:32:18'),(45,11,NULL,1,'Rank','Warehouseman','','2015-08-05 13:32:18'),(46,12,NULL,1,'Rank','R&D Junior Manager','','2015-08-05 13:32:18'),(47,12,NULL,1,'Rank','R&D Assistant','','2015-08-05 13:32:18'),(48,13,1,1,'Rank','Quality Assurance Specialist','Assures quality work','2015-08-05 13:32:18'),(49,13,NULL,1,'Rank','Quality Assurance Assistant','','2015-08-05 13:32:18'),(50,14,NULL,1,'Rank','Plant Operations Manager','','2015-08-05 13:32:18'),(51,14,NULL,1,'Rank','Production Officer','','2015-08-05 13:32:18'),(52,14,NULL,1,'Rank','Production Worker/Factory Worker','','2015-08-05 13:32:18'),(53,2,2,1,'Rank','HR Admin','HR & Admin duties.','2015-08-11 13:23:32'),(54,14,1,30,'Rank','Senior Software Developer','Develops Software','2015-08-13 16:41:24'),(55,15,1,30,'Rank','Project Manager','Project Management\r\n','2015-08-13 16:42:10'),(56,14,54,30,'Rank','Junior Software Developer','Develops Software','2015-09-01 13:11:03'),(57,14,NULL,39,'Rank','Senior Developer - Part Time','Senior Developer will be able to develop systems, manage junior developers and aid in improving overall ERP. ','2015-09-09 15:22:02'),(58,14,NULL,39,'Rank','Senior Developer - Part Time','Senior Developer will be able to develop systems, manage junior developers and aid in improving overall ERP. ','2015-09-09 15:23:48'),(59,14,NULL,39,'Rank','Senior Developer - Part Time','Senior Developer will be able to develop systems, manage junior developers and aid in improving overall ERP. ','2015-09-09 15:24:01'),(60,14,NULL,39,'Rank','Senior Developer - Part Time','Senior Developer will be able to develop systems, manage junior developers and aid in improving overall ERP. ','2015-09-09 15:24:50'),(61,14,NULL,39,'Rank','Senior Developer ','Senior Developer will be able to develop systems, manage junior developers and aid in improving overall ERP. ','2015-09-09 15:26:44'),(62,1,NULL,1,'Rank','Managing Director/Finance','Managing Director','2015-09-21 11:36:53'),(63,1,NULL,1,'Rank','Managing Director','Managing Director','2015-09-21 11:44:38'),(64,2,2,1,'Rank','HR Admin','twest','2015-10-13 16:29:21');
/*!40000 ALTER TABLE `hr_job_title` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_leave`
--

DROP TABLE IF EXISTS `hr_leave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_leave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `emp_leave_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `approved_by` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_filed` datetime NOT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `date_approved` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `applied_leave_days` int(11) DEFAULT NULL,
  `accept_sat` tinyint(1) NOT NULL,
  `date_reviewed_hr` datetime DEFAULT NULL,
  `date_reviewed_dept_head` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BD91688A8C03F15C` (`employee_id`),
  KEY `IDX_BD91688AEEFE5067` (`user_create_id`),
  KEY `IDX_BD91688A75CE0942` (`emp_leave_id`),
  CONSTRAINT `FK_BD91688A75CE0942` FOREIGN KEY (`emp_leave_id`) REFERENCES `hr_employee_leaves` (`id`),
  CONSTRAINT `FK_BD91688A8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_BD91688AEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_leave`
--

LOCK TABLES `hr_leave` WRITE;
/*!40000 ALTER TABLE `hr_leave` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_leave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_location`
--

DROP TABLE IF EXISTS `hr_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E57B4B31EEFE5067` (`user_create_id`),
  KEY `IDX_E57B4B31F5B7AF75` (`address_id`),
  CONSTRAINT `FK_E57B4B31EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_E57B4B31F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `cnt_address` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_location`
--

LOCK TABLES `hr_location` WRITE;
/*!40000 ALTER TABLE `hr_location` DISABLE KEYS */;
INSERT INTO `hr_location` VALUES (6,1,7,'Main Office','2015-08-11 17:12:35');
/*!40000 ALTER TABLE `hr_location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_manpower_request`
--

DROP TABLE IF EXISTS `hr_manpower_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_manpower_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `approver_id` int(11) DEFAULT NULL,
  `recommended_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `date_received` datetime DEFAULT NULL,
  `date_approved` datetime DEFAULT NULL,
  `date_filed` datetime DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `gender` longtext COLLATE utf8_unicode_ci,
  `age_from` int(11) DEFAULT NULL,
  `age_to` int(11) DEFAULT NULL,
  `experience` longtext COLLATE utf8_unicode_ci,
  `education` longtext COLLATE utf8_unicode_ci,
  `required_courses` longtext COLLATE utf8_unicode_ci,
  `skills` longtext COLLATE utf8_unicode_ci,
  `terms_of_employment` longtext COLLATE utf8_unicode_ci,
  `purpose` longtext COLLATE utf8_unicode_ci,
  `personnel_type` longtext COLLATE utf8_unicode_ci,
  `internal_source_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `external_source_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `internal_candidates` longtext COLLATE utf8_unicode_ci,
  `external_candidates` longtext COLLATE utf8_unicode_ci,
  `vacancy` int(11) DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `noted_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_80C9A85CBB23766C` (`approver_id`),
  KEY `IDX_80C9A85C70C20237` (`recommended_id`),
  KEY `IDX_80C9A85C31989A70` (`noted_id`),
  KEY `IDX_80C9A85CDD842E46` (`position_id`),
  KEY `IDX_80C9A85CEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_80C9A85C70C20237` FOREIGN KEY (`recommended_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_80C9A85CBB23766C` FOREIGN KEY (`approver_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_80C9A85CDD842E46` FOREIGN KEY (`position_id`) REFERENCES `hr_job_title` (`id`),
  CONSTRAINT `FK_80C9A85CEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_manpower_request`
--

LOCK TABLES `hr_manpower_request` WRITE;
/*!40000 ALTER TABLE `hr_manpower_request` DISABLE KEYS */;
INSERT INTO `hr_manpower_request` VALUES (1,NULL,NULL,NULL,39,NULL,NULL,'2015-09-09 00:00:00','Draft','a:2:{s:4:\"MALE\";s:4:\"Male\";s:6:\"FEMALE\";s:6:\"Female\";}',25,35,'a:1:{s:7:\"more_yr\";s:9:\"3 or More\";}','a:1:{i:3;s:27:\"Bachelor\'s / College Degree\";}',NULL,NULL,'a:1:{i:2;s:26:\"Project Hire (Contractual)\";}','a:1:{i:0;s:22:\"Newly Created Position\";}','a:1:{i:0;s:10:\"Managerial\";}',NULL,NULL,NULL,NULL,2,'2015-09-09 15:24:29','',16),(2,NULL,NULL,58,39,NULL,NULL,'2015-09-09 00:00:00','Draft','a:2:{s:4:\"MALE\";s:4:\"Male\";s:6:\"FEMALE\";s:6:\"Female\";}',25,35,'a:1:{s:7:\"more_yr\";s:9:\"3 or More\";}','a:2:{i:3;s:27:\"Bachelor\'s / College Degree\";i:4;s:45:\"Vocational Diploma / Short Course Certificate\";}','a:2:{i:0;s:0:\"\";i:1;s:0:\"\";}',NULL,'a:1:{i:2;s:26:\"Project Hire (Contractual)\";}','a:1:{i:0;s:22:\"Newly Created Position\";}','a:1:{i:0;s:10:\"Managerial\";}',NULL,NULL,NULL,NULL,2,'2015-09-09 15:41:13','',14);
/*!40000 ALTER TABLE `hr_manpower_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_memo`
--

DROP TABLE IF EXISTS `hr_memo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_memo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  `reviewed_id` int(11) DEFAULT NULL,
  `approved_id` int(11) DEFAULT NULL,
  `noted_id` int(11) DEFAULT NULL,
  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date_issued` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5392661A8C03F15C` (`employee_id`),
  KEY `IDX_5392661AEEFE5067` (`user_create_id`),
  KEY `IDX_5392661A5254E55` (`reviewed_id`),
  KEY `IDX_5392661ACE517E2F` (`approved_id`),
  KEY `IDX_5392661A31989A70` (`noted_id`),
  CONSTRAINT `FK_5392661A31989A70` FOREIGN KEY (`noted_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_5392661A5254E55` FOREIGN KEY (`reviewed_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_5392661A8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_5392661ACE517E2F` FOREIGN KEY (`approved_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_5392661AEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_memo`
--

LOCK TABLES `hr_memo` WRITE;
/*!40000 ALTER TABLE `hr_memo` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_memo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_rating_system`
--

DROP TABLE IF EXISTS `hr_rating_system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_rating_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `range_start` int(11) NOT NULL,
  `range_end` int(11) NOT NULL,
  `rating` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_rating_system`
--

LOCK TABLES `hr_rating_system` WRITE;
/*!40000 ALTER TABLE `hr_rating_system` DISABLE KEYS */;
INSERT INTO `hr_rating_system` VALUES (1,90,100,'Excellent','Exceptional Performance in all areas or responsibility. Planned objectives were achieved well above the established standards and accomplishments were made in unexpected areas.'),(2,80,89,'Very Satisfactory','Consistently exceed established standards in most areas of responsibility. All requirements were met and objectives were achieved above the established standards.'),(3,60,79,'Satisfactory','All job requirements were met and planned objectives were accomplished within established standards. There were no critical areas where accomplishments were less than planned.'),(4,40,59,'Needs Improvement','Performance in one or more critical areas does not meet expectations. Not all planned objectives were accomplished within the established standards and some responsibilities were not completely met.'),(5,20,39,'Poor','Does not meet minimum job requirements. Performance is unaaceptable. Responsibilities are not being met and important objectives have not been accomplished. Needs immediate improvement.');
/*!40000 ALTER TABLE `hr_rating_system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_reimbursement`
--

DROP TABLE IF EXISTS `hr_reimbursement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_reimbursement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `date_filed` datetime NOT NULL,
  `date_approved` datetime DEFAULT NULL,
  `receipt_no` longtext COLLATE utf8_unicode_ci NOT NULL,
  `expense_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6851DFE427EB8A5` (`request_id`),
  UNIQUE KEY `UNIQ_6851DFECCCFBA31` (`upload_id`),
  KEY `IDX_6851DFE8C03F15C` (`employee_id`),
  KEY `IDX_6851DFEBB23766C` (`approver_id`),
  KEY `IDX_6851DFEEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_6851DFE427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `hr_request` (`id`),
  CONSTRAINT `FK_6851DFE8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_6851DFEBB23766C` FOREIGN KEY (`approver_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_6851DFECCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_6851DFEEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_reimbursement`
--

LOCK TABLES `hr_reimbursement` WRITE;
/*!40000 ALTER TABLE `hr_reimbursement` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_reimbursement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_request`
--

DROP TABLE IF EXISTS `hr_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `approved_by` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_filed` datetime NOT NULL,
  `date_approved` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_D512762A8C03F15C` (`employee_id`),
  KEY `IDX_D512762AEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_D512762A8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_D512762AEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_request`
--

LOCK TABLES `hr_request` WRITE;
/*!40000 ALTER TABLE `hr_request` DISABLE KEYS */;
INSERT INTO `hr_request` VALUES (8,29,31,NULL,'Certificate of Employment','2015-08-14 00:00:00',NULL,'Pending','2015-08-14 13:37:52','a:9:{s:10:\"csrf_token\";s:32:\"736f18eb80903a33c1c7a78011e904fc\";s:10:\"date_filed\";s:10:\"08/14/2015\";s:8:\"emp_name\";s:15:\"San Juan, Jovel\";s:6:\"emp_id\";s:2:\"29\";s:7:\"emp_pos\";s:15:\"Project Manager\";s:6:\"pos_id\";s:2:\"55\";s:8:\"emp_dept\";s:10:\"Management\";s:7:\"dept_id\";s:1:\"1\";s:10:\"action_btn\";s:3:\"coe\";}');
/*!40000 ALTER TABLE `hr_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_requirements`
--

DROP TABLE IF EXISTS `hr_requirements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_type_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `req_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_74F478EA8313F474` (`leave_type_id`),
  KEY `IDX_74F478EAEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_74F478EA8313F474` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_type` (`id`),
  CONSTRAINT `FK_74F478EAEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_requirements`
--

LOCK TABLES `hr_requirements` WRITE;
/*!40000 ALTER TABLE `hr_requirements` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_requirements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_resign`
--

DROP TABLE IF EXISTS `hr_resign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_resign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `date_filed` datetime NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_approved` datetime DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_935822B4CCCFBA31` (`upload_id`),
  UNIQUE KEY `UNIQ_935822B4427EB8A5` (`request_id`),
  KEY `IDX_935822B48C03F15C` (`employee_id`),
  KEY `IDX_935822B4BB23766C` (`approver_id`),
  KEY `IDX_935822B4EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_935822B4427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `hr_request` (`id`),
  CONSTRAINT `FK_935822B48C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_935822B4BB23766C` FOREIGN KEY (`approver_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_935822B4CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_935822B4EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_resign`
--

LOCK TABLES `hr_resign` WRITE;
/*!40000 ALTER TABLE `hr_resign` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_resign` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_salary_history`
--

DROP TABLE IF EXISTS `hr_salary_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_salary_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `period_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `base_pay` decimal(10,2) NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BFC8CBB88C03F15C` (`employee_id`),
  KEY `IDX_BFC8CBB8EC8B7ADE` (`period_id`),
  KEY `IDX_BFC8CBB8EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_BFC8CBB88C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_BFC8CBB8EC8B7ADE` FOREIGN KEY (`period_id`) REFERENCES `pay_period` (`id`),
  CONSTRAINT `FK_BFC8CBB8EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_salary_history`
--

LOCK TABLES `hr_salary_history` WRITE;
/*!40000 ALTER TABLE `hr_salary_history` DISABLE KEYS */;
INSERT INTO `hr_salary_history` VALUES (3,28,2,1,1.00,'2015-08-12 14:12:37'),(4,29,1,30,15000.00,'2015-08-13 16:39:09'),(5,28,2,30,15000.00,'2015-08-13 16:43:59'),(6,30,1,30,15000.00,'2015-09-01 13:04:55'),(7,31,1,30,15000.00,'2015-09-01 13:09:17'),(10,34,2,30,15000.00,'2015-09-01 13:22:23'),(11,35,2,30,15000.00,'2015-09-01 13:25:03'),(12,36,2,30,15000.00,'2015-09-01 13:28:36'),(13,37,2,30,15000.00,'2015-09-01 13:46:50'),(14,38,2,1,15000.00,'2015-09-21 11:39:37'),(16,40,2,30,15000.00,'2015-10-06 14:02:51'),(17,41,2,1,1.00,'2016-05-05 14:34:22');
/*!40000 ALTER TABLE `hr_salary_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hr_schedule`
--

DROP TABLE IF EXISTS `hr_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hr_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `day_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `day_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `grace_period` int(11) NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `half_day` int(11) NOT NULL,
  `type` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `required_hours` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E1DDD301EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_E1DDD301EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_schedule`
--

LOCK TABLES `hr_schedule` WRITE;
/*!40000 ALTER TABLE `hr_schedule` DISABLE KEYS */;
INSERT INTO `hr_schedule` VALUES (3,1,'2015-08-13 09:30:00','2015-08-13 19:00:00','Monday','Friday',15,'Office Staff - Managerial','2015-08-05 13:32:18',0,NULL,NULL),(4,1,'2015-09-01 09:00:00','2015-09-01 18:30:00','Monday','Friday',60,'Office Staff - Production','2015-08-05 13:32:18',120,NULL,NULL),(9,1,'2016-05-05 10:00:00','2016-05-05 16:00:00','Monday','Friday',15,'General Employees','2016-05-05 10:03:16',120,'semi-flexi',9.5),(10,1,'2016-05-11 20:15:00','2016-05-11 20:15:00','Monday','Friday',0,'Seniors','2016-05-11 20:15:52',0,'flexi',0);
/*!40000 ALTER TABLE `hr_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_rules`
--

DROP TABLE IF EXISTS `leave_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_type_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `emp_status` longtext COLLATE utf8_unicode_ci,
  `payment_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `job_level_id` int(11) DEFAULT NULL,
  `gender` longtext COLLATE utf8_unicode_ci,
  `accrue_enabled` tinyint(1) NOT NULL,
  `accrue_count` double DEFAULT NULL,
  `accrue_rule` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `carried_enabled` tinyint(1) NOT NULL,
  `carry_percentage` int(11) DEFAULT NULL,
  `carry_period` int(11) DEFAULT NULL,
  `leave_count` double NOT NULL,
  `service_months` int(11) DEFAULT NULL,
  `override` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `effectivity` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `count_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_4C4EC0968313F474` (`leave_type_id`),
  KEY `IDX_4C4EC096EEFE5067` (`user_create_id`),
  KEY `IDX_4C4EC0968C03F15C` (`employee_id`),
  KEY `IDX_4C4EC096AE80F5DF` (`department_id`),
  KEY `IDX_4C4EC09638F6EEDC` (`job_level_id`),
  CONSTRAINT `FK_4C4EC09638F6EEDC` FOREIGN KEY (`job_level_id`) REFERENCES `hr_job_level` (`id`),
  CONSTRAINT `FK_4C4EC0968313F474` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_type` (`id`),
  CONSTRAINT `FK_4C4EC0968C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_4C4EC096AE80F5DF` FOREIGN KEY (`department_id`) REFERENCES `hr_department` (`id`),
  CONSTRAINT `FK_4C4EC096EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_rules`
--

LOCK TABLES `leave_rules` WRITE;
/*!40000 ALTER TABLE `leave_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `leave_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_type`
--

DROP TABLE IF EXISTS `leave_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `accrue_enabled` tinyint(1) NOT NULL,
  `accrue_count` double DEFAULT NULL,
  `carried_enabled` tinyint(1) NOT NULL,
  `leave_count` double NOT NULL,
  `service_months` int(11) DEFAULT NULL,
  `payment_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addtl_requirements` longtext COLLATE utf8_unicode_ci,
  `date_create` datetime NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` longtext COLLATE utf8_unicode_ci,
  `emp_status` longtext COLLATE utf8_unicode_ci,
  `accrue_rule` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `carry_percentage` int(11) DEFAULT NULL,
  `carry_period` int(11) DEFAULT NULL,
  `count_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_E2BC4391EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_E2BC4391EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_type`
--

LOCK TABLES `leave_type` WRITE;
/*!40000 ALTER TABLE `leave_type` DISABLE KEYS */;
INSERT INTO `leave_type` VALUES (2,NULL,0,NULL,0,5,12,'FULL',NULL,'2015-07-26 23:20:33','Service Incentive Leave','a:2:{s:4:\"Male\";s:4:\"Male\";s:6:\"Female\";s:6:\"Female\";}','a:1:{s:7:\"Regular\";s:7:\"Regular\";}',NULL,NULL,NULL,'per Year','Government-mandated leave for employees that have rendered at least 1 year of service in the company'),(4,NULL,1,1,1,5,12,'NONE',NULL,'2015-08-07 05:06:51','Sick Leave','a:2:{s:4:\"Male\";s:4:\"Male\";s:6:\"Female\";s:6:\"Female\";}','a:1:{s:7:\"Regular\";s:7:\"Regular\";}','Yearly',100,8,'per Year','12 days of Leave for employees that are sick'),(5,NULL,0,NULL,0,7,6,'NONE',NULL,'2015-08-07 05:38:25','Paternity Leave','a:1:{s:4:\"Male\";s:4:\"Male\";}','a:1:{s:7:\"Regular\";s:7:\"Regular\";}',NULL,NULL,NULL,'per Request','7 days of leave for Male employees during Birth or Miscarriage of Legitimate Spouse'),(6,NULL,0,NULL,0,3,6,'NONE',NULL,'2015-08-07 05:51:17','Bereavement','a:2:{s:4:\"Male\";s:4:\"Male\";s:6:\"Female\";s:6:\"Female\";}','a:2:{s:12:\"Probationary\";s:12:\"Probationary\";s:7:\"Regular\";s:7:\"Regular\";}',NULL,NULL,NULL,'per Request','3 days of leave in the event of death of a family member.'),(7,NULL,0,NULL,0,7,12,'NONE',NULL,'2015-08-07 05:54:26','Solo Parent Leave','a:2:{s:4:\"Male\";s:4:\"Male\";s:6:\"Female\";s:6:\"Female\";}','a:2:{s:12:\"Probationary\";s:12:\"Probationary\";s:7:\"Regular\";s:7:\"Regular\";}',NULL,NULL,NULL,'per Year','Persons who fall under the definition of solo parents and who have rendered service of at least one year are entitled to 7 working days of leave to attend to their parental duties.'),(8,NULL,0,NULL,0,2,6,'NONE',NULL,'2015-08-07 05:56:15','Calamity Leave','a:2:{s:4:\"Male\";s:4:\"Male\";s:6:\"Female\";s:6:\"Female\";}','a:2:{s:12:\"Probationary\";s:12:\"Probationary\";s:7:\"Regular\";s:7:\"Regular\";}',NULL,NULL,NULL,'per Request','2 days of leave for employees that are directly affected by calamity (e.g. Fire, Flood, Typhoon)'),(9,NULL,0,NULL,0,60,6,'NONE',NULL,'2015-08-07 05:58:21','Magna Carta for Women','a:1:{s:6:\"Female\";s:6:\"Female\";}','a:2:{s:12:\"Probationary\";s:12:\"Probationary\";s:7:\"Regular\";s:7:\"Regular\";}',NULL,NULL,NULL,'per Request','Leave for female employees who underwent surgery caused by gynecological disorders'),(10,NULL,0,NULL,0,60,6,'FULL',NULL,'2015-08-07 06:00:25','Maternity Leave (Normal Delivery)','a:1:{s:6:\"Female\";s:6:\"Female\";}','a:2:{s:12:\"Probationary\";s:12:\"Probationary\";s:7:\"Regular\";s:7:\"Regular\";}',NULL,NULL,NULL,'per Request',''),(11,NULL,0,NULL,0,78,6,'FULL',NULL,'2015-08-07 06:01:21','Maternity Leave (Caesarian)','a:1:{s:6:\"Female\";s:6:\"Female\";}','a:2:{s:12:\"Probationary\";s:12:\"Probationary\";s:7:\"Regular\";s:7:\"Regular\";}',NULL,NULL,NULL,'per Request','');
/*!40000 ALTER TABLE `leave_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_entry`
--

DROP TABLE IF EXISTS `log_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date_in` datetime NOT NULL,
  `action_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `data` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B5F762DA76ED395` (`user_id`),
  CONSTRAINT `FK_B5F762DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=398 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_entry`
--

LOCK TABLES `log_entry` WRITE;
/*!40000 ALTER TABLE `log_entry` DISABLE KEYS */;
INSERT INTO `log_entry` VALUES (397,1,'2016-05-12 07:21:30','hris_admin_department_delete','deleted Department 6.','{\"id\":6,\"date_create\":\"2015-08-05 13:32:18\",\"user_create\":{\"id\":1,\"username\":\"admin\",\"email\":\"test@test.com\",\"enabled\":true,\"groups\":[]},\"name\":\"Mechandising\"}','');
/*!40000 ALTER TABLE `log_entry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_storage_localfile`
--

DROP TABLE IF EXISTS `media_storage_localfile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_storage_localfile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `upload_id` int(11) DEFAULT NULL,
  `full_path` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_63985F3ACCCFBA31` (`upload_id`),
  CONSTRAINT `FK_63985F3ACCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_storage_localfile`
--

LOCK TABLES `media_storage_localfile` WRITE;
/*!40000 ALTER TABLE `media_storage_localfile` DISABLE KEYS */;
INSERT INTO `media_storage_localfile` VALUES (17,17,'/html/quadrant.hris.qalphalabs.com/app/../web/uploads/71/a7/71/a7/55cc5e9cea771.png'),(18,18,'/html/quadrant.hris.qalphalabs.com/app/../web/uploads/0a/06/0a/06/55cc5ea21060a.png'),(19,19,'/html/quadrant.hris.qalphalabs.com/app/../web/uploads/a4/ea/a4/ea/55e53f579eaa4.jpg'),(20,20,'/html/quadrant.hris.qalphalabs.com/app/../web/uploads/b2/f7/b2/f7/55efe6768f7b2.JPG'),(21,21,'/html/quadrant.hris.qalphalabs.com/app/../web/uploads/34/c1/34/c1/55efe702ac134.JPG'),(22,22,'/html/quadrant.hris.qalphalabs.com/app/../web/uploads/2d/23/2d/23/55efe7c51232d.jpg'),(23,23,'/html/quadrant.hris.qalphalabs.com/app/../web/uploads/44/2a/44/2a/55efe85cd2a44.jpg'),(25,25,'/html/quadrant.hris.qalphalabs.com/app/../web/uploads/e2/76/e2/76/56136851576e2.jpg'),(26,26,'/html/quadrant.hris.qalphalabs.com/app/../web/uploads/a9/8b/a9/8b/5613687e28ba9.jpg'),(27,27,'/html/quadrant.hris.qalphalabs.com/app/../web/uploads/65/94/65/94/5613689779465.jpg'),(28,28,'/home/richard/hris-quadrant/app/../web/uploads/6e/72/6e/72/572ae821e726e.png'),(29,29,'/home/richard/hris-quadrant/app/../web/uploads/63/79/63/79/572ae89827963.png'),(30,30,'/home/richard/hris-quadrant/app/../web/uploads/2a/ae/2a/ae/572ae8a18ae2a.png'),(31,31,'/home/richard/hris-quadrant/app/../web/uploads/43/e8/43/e8/572b03ae7e843.gif'),(32,32,'/home/richard/hris-quadrant/app/../web/uploads/18/8b/18/8b/572b03d898b18.png'),(33,33,'/home/richard/hris-quadrant/app/../web/uploads/e7/00/e7/00/572bd666a00e7.png'),(34,34,'/home/richard/hris-quadrant/app/../web/uploads/cc/7e/cc/7e/572bd66a97ecc.png');
/*!40000 ALTER TABLE `media_storage_localfile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_upload`
--

DROP TABLE IF EXISTS `media_upload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `filename` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `storage_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ABC47CC1EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_ABC47CC1EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_upload`
--

LOCK TABLES `media_upload` WRITE;
/*!40000 ALTER TABLE `media_upload` DISABLE KEYS */;
INSERT INTO `media_upload` VALUES (17,1,'55cc5e9cea771.png','http://quadrant.hris.qalphalabs.com/uploads/71/a7/55cc5e9cea771.png',1,'local_file','2015-08-13 17:08:44'),(18,1,'55cc5ea21060a.png','http://quadrant.hris.qalphalabs.com/uploads/0a/06/55cc5ea21060a.png',1,'local_file','2015-08-13 17:08:50'),(19,30,'55e53f579eaa4.jpg','http://quadrant.hris.qalphalabs.com/uploads/a4/ea/55e53f579eaa4.jpg',1,'local_file','2015-09-01 14:01:59'),(20,39,'55efe6768f7b2.JPG','http://lilys.qalphalabs.com/uploads/b2/f7/55efe6768f7b2.JPG',1,'local_file','2015-09-09 15:57:42'),(21,39,'55efe702ac134.JPG','http://lilys.qalphalabs.com/uploads/34/c1/55efe702ac134.JPG',1,'local_file','2015-09-09 16:00:02'),(22,32,'55efe7c51232d.jpg','http://lilys.qalphalabs.com/uploads/2d/23/55efe7c51232d.jpg',1,'local_file','2015-09-09 16:03:17'),(23,32,'55efe85cd2a44.jpg','http://quadrant.hris.qalphalabs.com/uploads/44/2a/55efe85cd2a44.jpg',1,'local_file','2015-09-09 16:05:48'),(25,30,'56136851576e2.jpg','http://quadrant.hris.qalphalabs.com/uploads/e2/76/56136851576e2.jpg',1,'local_file','2015-10-06 14:21:05'),(26,30,'5613687e28ba9.jpg','http://quadrant.hris.qalphalabs.com/uploads/a9/8b/5613687e28ba9.jpg',1,'local_file','2015-10-06 14:21:50'),(27,30,'5613689779465.jpg','http://quadrant.hris.qalphalabs.com/uploads/65/94/5613689779465.jpg',1,'local_file','2015-10-06 14:22:15'),(28,1,'572ae821e726e.png','http://lilys.dev/uploads/6e/72/572ae821e726e.png',1,'local_file','2016-05-05 14:28:49'),(29,1,'572ae89827963.png','http://rd.hris-quadrant.dev/uploads/63/79/572ae89827963.png',1,'local_file','2016-05-05 14:30:48'),(30,1,'572ae8a18ae2a.png','http://rd.hris-quadrant.dev/uploads/2a/ae/572ae8a18ae2a.png',1,'local_file','2016-05-05 14:30:57'),(31,1,'572b03ae7e843.gif','http://rd.hris-quadrant.dev/uploads/43/e8/572b03ae7e843.gif',1,'local_file','2016-05-05 16:26:22'),(32,1,'572b03d898b18.png','http://rd.hris-quadrant.dev/uploads/18/8b/572b03d898b18.png',1,'local_file','2016-05-05 16:27:04'),(33,1,'572bd666a00e7.png','http://rd.hris-quadrant/uploads/e7/00/572bd666a00e7.png',1,'local_file','2016-05-06 07:25:26'),(34,1,'572bd66a97ecc.png','http://rd.hris-quadrant/uploads/cc/7e/572bd66a97ecc.png',1,'local_file','2016-05-06 07:25:30');
/*!40000 ALTER TABLE `media_upload` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ntf_notification`
--

DROP TABLE IF EXISTS `ntf_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ntf_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `source` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E73C19C8EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_E73C19C8EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ntf_notification`
--

LOCK TABLES `ntf_notification` WRITE;
/*!40000 ALTER TABLE `ntf_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `ntf_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ntf_notification_queue`
--

DROP TABLE IF EXISTS `ntf_notification_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ntf_notification_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notification_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `flag_read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_73DE7BE9EF1A9D84` (`notification_id`),
  KEY `IDX_73DE7BE9A76ED395` (`user_id`),
  CONSTRAINT `FK_73DE7BE9A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_73DE7BE9EF1A9D84` FOREIGN KEY (`notification_id`) REFERENCES `ntf_notification` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ntf_notification_queue`
--

LOCK TABLES `ntf_notification_queue` WRITE;
/*!40000 ALTER TABLE `ntf_notification_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `ntf_notification_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_13th`
--

DROP TABLE IF EXISTS `pay_13th`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_13th` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `total_taxable` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `year` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `flag_locked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_145D9C2E8C03F15C` (`employee_id`),
  CONSTRAINT `FK_145D9C2E8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_13th`
--

LOCK TABLES `pay_13th` WRITE;
/*!40000 ALTER TABLE `pay_13th` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_13th` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_13thentry`
--

DROP TABLE IF EXISTS `pay_13thentry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_13thentry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payroll_period_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `pay13th_id` int(11) DEFAULT NULL,
  `total_earning` decimal(10,2) NOT NULL,
  `total_deduction` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E88C38BF5DD5005B` (`payroll_period_id`),
  KEY `IDX_E88C38BF8C03F15C` (`employee_id`),
  KEY `IDX_E88C38BFAFEAC96` (`pay13th_id`),
  CONSTRAINT `FK_E88C38BF5DD5005B` FOREIGN KEY (`payroll_period_id`) REFERENCES `pay_payroll_period` (`id`),
  CONSTRAINT `FK_E88C38BF8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_E88C38BFAFEAC96` FOREIGN KEY (`pay13th_id`) REFERENCES `pay_13th` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_13thentry`
--

LOCK TABLES `pay_13thentry` WRITE;
/*!40000 ALTER TABLE `pay_13thentry` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_13thentry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_deduction_entry`
--

DROP TABLE IF EXISTS `pay_deduction_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_deduction_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payroll_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `flag_taxable` tinyint(1) NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_5FBCC334DBA340EA` (`payroll_id`),
  CONSTRAINT `FK_5FBCC334DBA340EA` FOREIGN KEY (`payroll_id`) REFERENCES `pay_payroll` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_deduction_entry`
--

LOCK TABLES `pay_deduction_entry` WRITE;
/*!40000 ALTER TABLE `pay_deduction_entry` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_deduction_entry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_earning_entry`
--

DROP TABLE IF EXISTS `pay_earning_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_earning_entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payroll_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `flag_taxable` tinyint(1) NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_99E320F9DBA340EA` (`payroll_id`),
  CONSTRAINT `FK_99E320F9DBA340EA` FOREIGN KEY (`payroll_id`) REFERENCES `pay_payroll` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_earning_entry`
--

LOCK TABLES `pay_earning_entry` WRITE;
/*!40000 ALTER TABLE `pay_earning_entry` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_earning_entry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_govbenefits_paid`
--

DROP TABLE IF EXISTS `pay_govbenefits_paid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_govbenefits_paid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `type` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `fs_month` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `fs_year` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F9D370878C03F15C` (`employee_id`),
  CONSTRAINT `FK_F9D370878C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_govbenefits_paid`
--

LOCK TABLES `pay_govbenefits_paid` WRITE;
/*!40000 ALTER TABLE `pay_govbenefits_paid` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_govbenefits_paid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_overtime_rate`
--

DROP TABLE IF EXISTS `pay_overtime_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_overtime_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_overtime_rate`
--

LOCK TABLES `pay_overtime_rate` WRITE;
/*!40000 ALTER TABLE `pay_overtime_rate` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_overtime_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_payroll`
--

DROP TABLE IF EXISTS `pay_payroll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_payroll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payroll_period_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `total_earning` decimal(10,2) NOT NULL,
  `total_deduction` decimal(10,2) NOT NULL,
  `total_taxable` decimal(10,2) NOT NULL,
  `total_nontaxable` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `flag_locked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AF757B0D5DD5005B` (`payroll_period_id`),
  KEY `IDX_AF757B0D8C03F15C` (`employee_id`),
  CONSTRAINT `FK_AF757B0D5DD5005B` FOREIGN KEY (`payroll_period_id`) REFERENCES `pay_payroll_period` (`id`),
  CONSTRAINT `FK_AF757B0D8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_payroll`
--

LOCK TABLES `pay_payroll` WRITE;
/*!40000 ALTER TABLE `pay_payroll` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_payroll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_payroll_period`
--

DROP TABLE IF EXISTS `pay_payroll_period`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_payroll_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period_id` int(11) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `fs_month` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `fs_year` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_18830363EC8B7ADE` (`period_id`),
  CONSTRAINT `FK_18830363EC8B7ADE` FOREIGN KEY (`period_id`) REFERENCES `pay_period` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_payroll_period`
--

LOCK TABLES `pay_payroll_period` WRITE;
/*!40000 ALTER TABLE `pay_payroll_period` DISABLE KEYS */;
INSERT INTO `pay_payroll_period` VALUES (1,1,'2015-07-11 00:00:00','2015-07-25 00:00:00','07','2015'),(2,3,'2015-08-31 00:00:00','2015-08-31 00:00:00','08','2015'),(3,1,'1970-01-01 00:00:00','1970-01-01 00:00:00','01','1970'),(4,1,'2015-10-14 00:00:00','2015-10-27 00:00:00','10','2015'),(5,1,'2016-04-14 00:00:00','2016-04-28 00:00:00','04','2016');
/*!40000 ALTER TABLE `pay_payroll_period` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_period`
--

DROP TABLE IF EXISTS `pay_period`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paydays` int(11) NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_period`
--

LOCK TABLES `pay_period` WRITE;
/*!40000 ALTER TABLE `pay_period` DISABLE KEYS */;
INSERT INTO `pay_period` VALUES (1,24,'Semi-Monthly'),(2,12,'Monthly'),(3,52,'Weekly'),(4,104,'Bi-Weekly'),(5,312,'Daily'),(6,4,'Quarterly'),(7,2,'Bi-Annual'),(8,1,'Annual');
/*!40000 ALTER TABLE `pay_period` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_philhealth_rate`
--

DROP TABLE IF EXISTS `pay_philhealth_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_philhealth_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_bracket` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `min_amount` decimal(10,2) NOT NULL,
  `max_amount` decimal(10,2) NOT NULL,
  `employee_contribution` decimal(10,2) NOT NULL,
  `employer_contribution` decimal(10,2) NOT NULL,
  `total_contribution` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_philhealth_rate`
--

LOCK TABLES `pay_philhealth_rate` WRITE;
/*!40000 ALTER TABLE `pay_philhealth_rate` DISABLE KEYS */;
INSERT INTO `pay_philhealth_rate` VALUES (1,'1-8999.99',1.00,8999.99,100.00,100.00,200.00),(2,'9000.00-9999.99',9000.00,9999.99,112.50,112.50,225.00),(3,'10000.00-10999.99',10000.00,10999.99,125.00,125.00,250.00),(4,'11000.00-11999.99',11000.00,11999.99,137.50,137.50,275.00),(5,'12000.00-12999.99',12000.00,12999.99,150.00,150.00,300.00),(6,'13000.00-13999.99',13000.00,13999.99,162.50,162.50,325.00),(7,'14000.00-14999.99',14000.00,14999.99,175.00,175.00,350.00),(8,'15000.00-15999.99',15000.00,15999.99,187.50,187.50,375.00),(9,'16000.00-16999.99',16000.00,16999.99,200.00,200.00,400.00),(10,'17000.00-17999.99',17000.00,17999.99,212.50,212.50,425.00),(11,'18000.00-18999.99',18000.00,18999.99,225.00,225.00,450.00),(12,'19000.00-19999.99',19000.00,19999.99,237.50,237.50,475.00),(13,'20000.00-20999.99',20000.00,20999.99,250.00,250.00,500.00),(14,'21000.00-21999.99',21000.00,21999.99,262.50,262.50,525.00),(15,'22000.00-22999.99',22000.00,22999.99,275.00,275.00,550.00),(16,'23000.00-23999.99',23000.00,23999.99,287.50,287.50,575.00),(17,'24000.00-24999.99',24000.00,24999.99,300.00,300.00,600.00),(18,'25000.00-25999.99',25000.00,25999.99,312.50,312.50,625.00),(19,'26000.00-26999.99',26000.00,26999.99,325.00,325.00,650.00),(20,'27000.00-27999.99',27000.00,27999.99,337.50,337.50,675.00),(21,'28000.00-28999.99',28000.00,28999.99,350.00,350.00,700.00),(22,'29000.00-29999.99',29000.00,29999.99,362.50,362.50,725.00),(23,'30000.00-30999.99',30000.00,30999.99,375.00,375.00,750.00),(24,'31000.00-31999.99',31000.00,31999.99,387.50,387.50,775.00),(25,'32000.00-32999.99',32000.00,32999.99,400.00,400.00,800.00),(26,'33000.00-33999.99',33000.00,33999.99,412.50,412.50,825.00),(27,'34000.00-34999.99',34000.00,34999.99,425.00,425.00,850.00),(28,'35000.00-999999999',35000.00,99999999.99,437.50,437.50,875.00);
/*!40000 ALTER TABLE `pay_philhealth_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_schedule`
--

DROP TABLE IF EXISTS `pay_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `start_end` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:json_array)',
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5668E004EC8B7ADE` (`period_id`),
  KEY `IDX_5668E004EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_5668E004EC8B7ADE` FOREIGN KEY (`period_id`) REFERENCES `pay_period` (`id`),
  CONSTRAINT `FK_5668E004EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_schedule`
--

LOCK TABLES `pay_schedule` WRITE;
/*!40000 ALTER TABLE `pay_schedule` DISABLE KEYS */;
INSERT INTO `pay_schedule` VALUES (1,1,1,'{\"weekly_start\":0,\"weekly_end\":5,\"weekly_pay\":1,\"monthly_start\":28,\"monthly_end\":27,\"monthly_pay\":0,\"semimonthly_start1\":26,\"semimonthly_end1\":10,\"semimonthly_pay1\":15,\"semimonthly_start2\":11,\"semimonthly_end2\":25,\"semimonthly_pay2\":0}','Semi-Monthly','2015-08-05 13:32:18'),(2,3,1,'{\"weekly_start\":0,\"weekly_end\":5,\"weekly_pay\":1,\"monthly_start\":28,\"monthly_end\":27,\"monthly_pay\":0,\"semimonthly_start1\":26,\"semimonthly_end1\":10,\"semimonthly_pay1\":15,\"semimonthly_start2\":11,\"semimonthly_end2\":25,\"semimonthly_pay2\":0}','Weekly','2015-08-05 13:32:18');
/*!40000 ALTER TABLE `pay_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_sss_rate`
--

DROP TABLE IF EXISTS `pay_sss_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_sss_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `salary_bracket` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `min_amount` decimal(15,2) NOT NULL,
  `max_amount` decimal(15,2) NOT NULL,
  `salary_credit` decimal(10,2) NOT NULL,
  `employee_contribution` decimal(10,2) NOT NULL,
  `employer_contribution` decimal(10,2) NOT NULL,
  `total_contribution` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_sss_rate`
--

LOCK TABLES `pay_sss_rate` WRITE;
/*!40000 ALTER TABLE `pay_sss_rate` DISABLE KEYS */;
INSERT INTO `pay_sss_rate` VALUES (1,'1000.00-1249.99',1000.00,1249.99,1000.00,36.30,83.70,120.00),(2,'1250.00-1749.99',1250.00,1749.99,1500.00,54.50,120.50,175.00),(3,'1750.00-2249.99',1750.00,2249.99,2000.00,72.70,157.30,230.00),(4,'2250.00-2749.99',2250.00,2749.99,2500.00,90.80,194.20,285.00),(5,'2750.00-3249.99',2750.00,3249.99,3000.00,109.00,231.00,340.00),(6,'3250.00-3749.99',3250.00,3749.99,3500.00,127.20,267.80,395.00),(7,'3750.00-4249.99',3750.00,4249.99,4000.00,145.30,304.70,450.00),(8,'4250.00-4749.99',4250.00,4749.99,4500.00,163.50,341.50,505.00),(9,'4750.00-5249.99',4750.00,5249.99,5000.00,181.70,378.30,560.00),(10,'5250.00-5749.99',5250.00,5749.99,5500.00,199.80,415.20,615.00),(11,'5750.00-6249.99',5750.00,6249.99,6000.00,218.00,452.00,670.00),(12,'6250.00-6749.99',6250.00,6749.99,6500.00,236.20,488.80,725.00),(13,'6750.00-7249.99',6750.00,7249.99,7000.00,254.30,525.70,780.00),(14,'7250.00-7749.99',7250.00,7749.99,7500.00,272.50,562.50,835.00),(15,'7750.00-8249.99',7750.00,8249.99,8000.00,290.70,599.30,890.00),(16,'8250.00-8749.99',8250.00,8749.99,8500.00,308.80,636.20,945.00),(17,'8750.00-9249.99',8750.00,9249.99,9000.00,327.00,673.00,1000.00),(18,'9250.00-9749.99',9250.00,9749.99,9500.00,345.20,709.80,1055.00),(19,'9750.00-10249.99',9750.00,10249.99,10000.00,363.30,746.70,1110.00),(20,'10250.00-10749.99',10250.00,10749.99,10500.00,381.50,783.50,1165.00),(21,'10750.00-11249.99',10750.00,11249.99,11000.00,399.70,820.30,1220.00),(22,'11250.00-11749.99',11250.00,11749.99,11500.00,417.80,857.20,1275.00),(23,'11750.00-12249.99',11750.00,12249.99,12000.00,436.00,894.00,1330.00),(24,'12250.00-12749.99',12250.00,12749.99,12500.00,454.20,930.80,1385.00),(25,'12750.00-13249.99',12750.00,13249.99,13000.00,472.30,967.70,1440.00),(26,'13250.00-13749.99',13250.00,13749.99,13500.00,490.50,1004.50,1495.00),(27,'13750.00-14249.99',13750.00,14249.99,14000.00,508.70,1041.30,1550.00),(28,'14250.00-14749.99',14250.00,14749.99,14500.00,526.80,1078.20,1605.00),(29,'14750.00-15249.99',14750.00,15249.99,15000.00,545.00,1135.00,1680.00),(30,'15250.00-15749.99',15250.00,15749.99,15500.00,563.20,1171.80,1735.00),(31,'15750.00-999999999',15750.00,999999999.00,16000.00,581.30,1208.70,1790.00);
/*!40000 ALTER TABLE `pay_sss_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_tax`
--

DROP TABLE IF EXISTS `pay_tax`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payroll_id` int(11) DEFAULT NULL,
  `taxmatrix_id` int(11) DEFAULT NULL,
  `taxable_amount` decimal(10,2) NOT NULL,
  `excess_amount` decimal(10,2) NOT NULL,
  `taxed_amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A4C02D84DBA340EA` (`payroll_id`),
  UNIQUE KEY `UNIQ_A4C02D846454EF39` (`taxmatrix_id`),
  CONSTRAINT `FK_A4C02D846454EF39` FOREIGN KEY (`taxmatrix_id`) REFERENCES `pay_taxmatrix` (`id`),
  CONSTRAINT `FK_A4C02D84DBA340EA` FOREIGN KEY (`payroll_id`) REFERENCES `pay_payroll` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_tax`
--

LOCK TABLES `pay_tax` WRITE;
/*!40000 ALTER TABLE `pay_tax` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_tax` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_tax_rate`
--

DROP TABLE IF EXISTS `pay_tax_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_tax_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taxstatus_id` int(11) DEFAULT NULL,
  `period_id` int(11) DEFAULT NULL,
  `bracket` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount_from` decimal(10,2) NOT NULL,
  `amount_to` decimal(10,2) NOT NULL,
  `amount_tax` decimal(10,2) NOT NULL,
  `percent_of_excess` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CF33C13EA5A3E463` (`taxstatus_id`),
  KEY `IDX_CF33C13EEC8B7ADE` (`period_id`),
  CONSTRAINT `FK_CF33C13EA5A3E463` FOREIGN KEY (`taxstatus_id`) REFERENCES `pay_taxstatus` (`id`),
  CONSTRAINT `FK_CF33C13EEC8B7ADE` FOREIGN KEY (`period_id`) REFERENCES `pay_period` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_tax_rate`
--

LOCK TABLES `pay_tax_rate` WRITE;
/*!40000 ALTER TABLE `pay_tax_rate` DISABLE KEYS */;
INSERT INTO `pay_tax_rate` VALUES (1,1,5,'1-1',1.00,1.00,0.00,0.00),(2,1,5,'0-32',0.00,32.00,0.00,5.00),(3,1,5,'33-98',33.00,98.00,1.65,10.00),(4,1,5,'99-230',99.00,230.00,8.25,15.00),(5,1,5,'231-461',231.00,461.00,28.05,20.00),(6,1,5,'462-824',462.00,824.00,74.26,25.00),(7,1,5,'825-1649',825.00,1649.00,165.02,30.00),(8,1,5,'1650-999999999',1650.00,99999999.99,412.54,32.00),(9,2,5,'1-164',1.00,164.00,0.00,0.00),(10,2,5,'165-197',165.00,197.00,0.00,5.00),(11,2,5,'198-263',198.00,263.00,1.65,10.00),(12,2,5,'264-395',264.00,395.00,8.25,15.00),(13,2,5,'396-626',396.00,626.00,28.05,20.00),(14,2,5,'627-989',627.00,989.00,74.26,25.00),(15,2,5,'990-1814',990.00,1814.00,165.02,30.00),(16,2,5,'1815-999999999',1815.00,99999999.99,412.54,32.00),(17,3,5,'1-247',1.00,247.00,0.00,0.00),(18,3,5,'248-280',248.00,280.00,0.00,5.00),(19,3,5,'281-346',281.00,346.00,1.65,10.00),(20,3,5,'347-478',347.00,478.00,8.25,15.00),(21,3,5,'479-709',479.00,709.00,28.05,20.00),(22,3,5,'710-1072',710.00,1072.00,74.26,25.00),(23,3,5,'1073-1897',1073.00,1897.00,165.02,30.00),(24,3,5,'1898-999999999',1898.00,99999999.99,412.54,32.00),(25,4,5,'1-329',1.00,329.00,0.00,0.00),(26,4,5,'330-362',330.00,362.00,0.00,5.00),(27,4,5,'363-428',363.00,428.00,1.65,10.00),(28,4,5,'429-560',429.00,560.00,8.25,15.00),(29,4,5,'561-791',561.00,791.00,28.05,20.00),(30,4,5,'792-1154',792.00,1154.00,74.26,25.00),(31,4,5,'1155-1979',1155.00,1979.00,165.02,30.00),(32,4,5,'1980-999999999',1980.00,99999999.99,412.54,32.00),(33,5,5,'1-412',1.00,412.00,0.00,0.00),(34,5,5,'413-445',413.00,445.00,0.00,5.00),(35,5,5,'446-511',446.00,511.00,1.65,10.00),(36,5,5,'512-643',512.00,643.00,8.25,15.00),(37,5,5,'644-874',644.00,874.00,28.05,20.00),(38,5,5,'875-1237',875.00,1237.00,74.26,25.00),(39,5,5,'1238-2062',1238.00,2062.00,165.02,30.00),(40,5,5,'2063-999999999',2063.00,99999999.99,412.54,32.00),(41,6,5,'1-494',1.00,494.00,0.00,0.00),(42,6,5,'495-527',495.00,527.00,0.00,5.00),(43,6,5,'528-593',528.00,593.00,1.65,10.00),(44,6,5,'594-725',594.00,725.00,8.25,15.00),(45,6,5,'726-956',726.00,956.00,28.05,20.00),(46,6,5,'957-1319',957.00,1319.00,74.26,25.00),(47,6,5,'1320-2144',1320.00,2144.00,165.02,30.00),(48,6,5,'2145-999999999',2145.00,99999999.99,412.54,32.00),(49,1,3,'1-1',1.00,1.00,0.00,0.00),(50,1,3,'0-191',0.00,191.00,0.00,5.00),(51,1,3,'192-576',192.00,576.00,9.62,10.00),(52,1,3,'577-1345',577.00,1345.00,48.08,15.00),(53,1,3,'1346-2691',1346.00,2691.00,163.46,20.00),(54,1,3,'2692-4807',2692.00,4807.00,432.69,25.00),(55,1,3,'4808-9614',4808.00,9614.00,961.54,30.00),(56,1,3,'9615-999999999',9615.00,99999999.99,2403.85,32.00),(57,2,3,'1-961',1.00,961.00,0.00,0.00),(58,2,3,'962-1153',962.00,1153.00,0.00,5.00),(59,2,3,'1154-1537',1154.00,1537.00,9.62,10.00),(60,2,3,'1538-2307',1538.00,2307.00,48.08,15.00),(61,2,3,'2308-3653',2308.00,3653.00,163.46,20.00),(62,2,3,'3654-5768',3654.00,5768.00,432.69,25.00),(63,2,3,'5769-10576',5769.00,10576.00,961.54,30.00),(64,2,3,'10577-999999999',10577.00,99999999.99,2403.85,32.00),(65,3,3,'1-1441',1.00,1441.00,0.00,0.00),(66,3,3,'1442-1634',1442.00,1634.00,0.00,5.00),(67,3,3,'1635-2018',1635.00,2018.00,9.62,10.00),(68,3,3,'2019-2787',2019.00,2787.00,48.08,15.00),(69,3,3,'2788-4134',2788.00,4134.00,163.46,20.00),(70,3,3,'4135-6249',4135.00,6249.00,432.69,25.00),(71,3,3,'6250-11057',6250.00,11057.00,961.54,30.00),(72,3,3,'11058-999999999',11058.00,99999999.99,2403.85,32.00),(73,4,3,'1-1922',1.00,1922.00,0.00,0.00),(74,4,3,'1923-2114',1923.00,2114.00,0.00,5.00),(75,4,3,'2115-2499',2115.00,2499.00,9.62,10.00),(76,4,3,'2500-3268',2500.00,3268.00,48.08,15.00),(77,4,3,'3269-4614',3269.00,4614.00,163.46,20.00),(78,4,3,'4615-6730',4615.00,6730.00,432.69,25.00),(79,4,3,'6731-11537',6731.00,11537.00,961.54,30.00),(80,4,3,'11538-999999999',11538.00,99999999.99,2403.85,32.00),(81,5,3,'1-2403',1.00,2403.00,0.00,0.00),(82,5,3,'2404-2595',2404.00,2595.00,0.00,5.00),(83,5,3,'2596-2980',2596.00,2980.00,9.62,10.00),(84,5,3,'2981-3749',2981.00,3749.00,48.08,15.00),(85,5,3,'3750-5095',3750.00,5095.00,163.46,20.00),(86,5,3,'5096-7211',5096.00,7211.00,432.69,25.00),(87,5,3,'7212-12018',7212.00,12018.00,961.54,30.00),(88,5,3,'12019-999999999',12019.00,99999999.99,2403.85,32.00),(89,6,3,'1-2884',1.00,2884.00,0.00,0.00),(90,6,3,'2885-3076',2885.00,3076.00,0.00,5.00),(91,6,3,'3077-3461',3077.00,3461.00,9.62,10.00),(92,6,3,'3462-4230',3462.00,4230.00,48.08,15.00),(93,6,3,'4231-5576',4231.00,5576.00,163.46,20.00),(94,6,3,'5577-7691',5577.00,7691.00,432.69,25.00),(95,6,3,'7692-12499',7692.00,12499.00,961.54,30.00),(96,6,3,'12500-999999999',12500.00,99999999.99,2403.85,32.00),(97,1,1,'1-1',1.00,1.00,0.00,0.00),(98,1,1,'0-416',0.00,416.00,0.00,5.00),(99,1,1,'417-1249',417.00,1249.00,20.83,10.00),(100,1,1,'1250-2916',1250.00,2916.00,104.17,15.00),(101,1,1,'2917-5832',2917.00,5832.00,354.17,20.00),(102,1,1,'5833-10416',5833.00,10416.00,937.50,25.00),(103,1,1,'10417-20832',10417.00,20832.00,2083.33,30.00),(104,1,1,'20833-999999999',20833.00,99999999.99,5208.33,32.00),(105,2,1,'1-2082',1.00,2082.00,0.00,0.00),(106,2,1,'2083-2499',2083.00,2499.00,0.00,5.00),(107,2,1,'2500-3332',2500.00,3332.00,20.83,10.00),(108,2,1,'3333-4999',3333.00,4999.00,104.17,15.00),(109,2,1,'5000-7916',5000.00,7916.00,354.17,20.00),(110,2,1,'7917-12499',7917.00,12499.00,937.50,25.00),(111,2,1,'12500-22916',12500.00,22916.00,2083.33,30.00),(112,2,1,'22917-999999999',22917.00,99999999.99,5208.33,32.00),(113,3,1,'1-3124',1.00,3124.00,0.00,0.00),(114,3,1,'3125-3541',3125.00,3541.00,0.00,5.00),(115,3,1,'3542-4374',3542.00,4374.00,20.83,10.00),(116,3,1,'4375-6041',4375.00,6041.00,104.17,15.00),(117,3,1,'6042-8957',6042.00,8957.00,354.17,20.00),(118,3,1,'8958-13541',8958.00,13541.00,937.50,25.00),(119,3,1,'13542-23957',13542.00,23957.00,2083.33,30.00),(120,3,1,'23958-999999999',23958.00,99999999.99,5208.33,32.00),(121,4,1,'1-4166',1.00,4166.00,0.00,0.00),(122,4,1,'4167-4582',4167.00,4582.00,0.00,5.00),(123,4,1,'4583-5416',4583.00,5416.00,20.83,10.00),(124,4,1,'5417-7082',5417.00,7082.00,104.17,15.00),(125,4,1,'7083-9999',7083.00,9999.00,354.17,20.00),(126,4,1,'10000-14582',10000.00,14582.00,937.50,25.00),(127,4,1,'14583-24999',14583.00,24999.00,2083.33,30.00),(128,4,1,'25000-999999999',25000.00,99999999.99,5208.33,32.00),(129,5,1,'1-5207',1.00,5207.00,0.00,0.00),(130,5,1,'5208-5624',5208.00,5624.00,0.00,5.00),(131,5,1,'5625-6457',5625.00,6457.00,20.83,10.00),(132,5,1,'6458-8124',6458.00,8124.00,104.17,15.00),(133,5,1,'8125-11041',8125.00,11041.00,354.17,20.00),(134,5,1,'11042-15624',11042.00,15624.00,937.50,25.00),(135,5,1,'15625-26041',15625.00,26041.00,2083.33,30.00),(136,5,1,'26042-999999999',26042.00,99999999.99,5208.33,32.00),(137,6,1,'1-6249',1.00,6249.00,0.00,0.00),(138,6,1,'6250-6666',6250.00,6666.00,0.00,5.00),(139,6,1,'6667-7499',6667.00,7499.00,20.83,10.00),(140,6,1,'7500-9166',7500.00,9166.00,104.17,15.00),(141,6,1,'9167-12082',9167.00,12082.00,354.17,20.00),(142,6,1,'12083-16666',12083.00,16666.00,937.50,25.00),(143,6,1,'16667-27082',16667.00,27082.00,2083.33,30.00),(144,6,1,'27083-999999999',27083.00,99999999.99,5208.33,32.00),(145,1,2,'1-1',1.00,1.00,0.00,0.00),(146,1,2,'0-832',0.00,832.00,0.00,5.00),(147,1,2,'833-2499',833.00,2499.00,41.67,10.00),(148,1,2,'2500-5832',2500.00,5832.00,208.33,15.00),(149,1,2,'5833-11666',5833.00,11666.00,708.33,20.00),(150,1,2,'11667-20832',11667.00,20832.00,1875.00,25.00),(151,1,2,'20833-41666',20833.00,41666.00,4166.67,30.00),(152,1,2,'41667-999999999',41667.00,99999999.99,10416.67,32.00),(153,2,2,'1-4166',1.00,4166.00,0.00,0.00),(154,2,2,'4167-4999',4167.00,4999.00,0.00,5.00),(155,2,2,'5000-6666',5000.00,6666.00,41.67,10.00),(156,2,2,'6667-9999',6667.00,9999.00,208.33,15.00),(157,2,2,'10000-15832',10000.00,15832.00,708.33,20.00),(158,2,2,'15833-24999',15833.00,24999.00,1875.00,25.00),(159,2,2,'25000-45882',25000.00,45882.00,4166.67,30.00),(160,2,2,'45883-999999999',45883.00,99999999.99,10416.67,32.00),(161,3,2,'1-6249',1.00,6249.00,0.00,0.00),(162,3,2,'6250-7082',6250.00,7082.00,0.00,5.00),(163,3,2,'7083-8749',7083.00,8749.00,41.67,10.00),(164,3,2,'8750-12082',8750.00,12082.00,208.33,15.00),(165,3,2,'12083-17916',12083.00,17916.00,708.33,20.00),(166,3,2,'17917-27082',17917.00,27082.00,1875.00,25.00),(167,3,2,'27083-47916',27083.00,47916.00,4166.67,30.00),(168,3,2,'47917-999999999',47917.00,99999999.99,10416.67,32.00),(169,4,2,'1-8332',1.00,8332.00,0.00,0.00),(170,4,2,'8333-9166',8333.00,9166.00,0.00,5.00),(171,4,2,'9167-10832',9167.00,10832.00,41.67,10.00),(172,4,2,'10833-14166',10833.00,14166.00,208.33,15.00),(173,4,2,'14167-19999',14167.00,19999.00,708.33,20.00),(174,4,2,'20000-29166',20000.00,29166.00,1875.00,25.00),(175,4,2,'29167-49999',29167.00,49999.00,4166.67,30.00),(176,4,2,'50000-999999999',50000.00,99999999.99,10416.67,32.00),(177,5,2,'1-10416',1.00,10416.00,0.00,0.00),(178,5,2,'10417-11249',10417.00,11249.00,0.00,5.00),(179,5,2,'11250-12916',11250.00,12916.00,41.67,10.00),(180,5,2,'12917-16249',12917.00,16249.00,208.33,15.00),(181,5,2,'16250-22082',16250.00,22082.00,708.33,20.00),(182,5,2,'22083-31249',22083.00,31249.00,1875.00,25.00),(183,5,2,'31250-52082',31250.00,52082.00,4166.67,30.00),(184,5,2,'52083-999999999',52083.00,99999999.99,10416.67,32.00),(185,6,2,'1-12499',1.00,12499.00,0.00,0.00),(186,6,2,'12500-13332',12500.00,13332.00,0.00,5.00),(187,6,2,'13333-14999',13333.00,14999.00,41.67,10.00),(188,6,2,'15000-18332',15000.00,18332.00,208.33,15.00),(189,6,2,'18333-24166',18333.00,24166.00,708.33,20.00),(190,6,2,'24167-33332',24167.00,33332.00,1875.00,25.00),(191,6,2,'33333-54166',33333.00,54166.00,4166.67,30.00),(192,6,2,'54167-999999999',54167.00,99999999.99,10416.67,32.00);
/*!40000 ALTER TABLE `pay_tax_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_taxmatrix`
--

DROP TABLE IF EXISTS `pay_taxmatrix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_taxmatrix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `taxrate_id` int(11) DEFAULT NULL,
  `taxstatus_id` int(11) DEFAULT NULL,
  `period_id` int(11) DEFAULT NULL,
  `base_amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_EC7FE70E97012B7` (`taxrate_id`),
  KEY `IDX_EC7FE70EA5A3E463` (`taxstatus_id`),
  KEY `IDX_EC7FE70EEC8B7ADE` (`period_id`),
  CONSTRAINT `FK_EC7FE70E97012B7` FOREIGN KEY (`taxrate_id`) REFERENCES `pay_tax_rate` (`id`),
  CONSTRAINT `FK_EC7FE70EA5A3E463` FOREIGN KEY (`taxstatus_id`) REFERENCES `pay_taxstatus` (`id`),
  CONSTRAINT `FK_EC7FE70EEC8B7ADE` FOREIGN KEY (`period_id`) REFERENCES `pay_period` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_taxmatrix`
--

LOCK TABLES `pay_taxmatrix` WRITE;
/*!40000 ALTER TABLE `pay_taxmatrix` DISABLE KEYS */;
INSERT INTO `pay_taxmatrix` VALUES (1,1,1,5,1.00),(2,2,1,5,0.00),(3,3,1,5,33.00),(4,4,1,5,99.00),(5,5,1,5,231.00),(6,6,1,5,462.00),(7,7,1,5,825.00),(8,8,1,5,1650.00),(9,9,2,5,1.00),(10,10,2,5,165.00),(11,11,2,5,198.00),(12,12,2,5,264.00),(13,13,2,5,396.00),(14,14,2,5,627.00),(15,15,2,5,990.00),(16,16,2,5,1815.00),(17,17,3,5,1.00),(18,18,3,5,248.00),(19,19,3,5,281.00),(20,20,3,5,347.00),(21,21,3,5,479.00),(22,22,3,5,710.00),(23,23,3,5,1073.00),(24,24,3,5,1898.00),(25,25,4,5,1.00),(26,26,4,5,330.00),(27,27,4,5,363.00),(28,28,4,5,429.00),(29,29,4,5,561.00),(30,30,4,5,792.00),(31,31,4,5,1155.00),(32,32,4,5,1980.00),(33,33,5,5,1.00),(34,34,5,5,413.00),(35,35,5,5,446.00),(36,36,5,5,512.00),(37,37,5,5,644.00),(38,38,5,5,875.00),(39,39,5,5,1238.00),(40,40,5,5,2063.00),(41,41,6,5,1.00),(42,42,6,5,495.00),(43,43,6,5,528.00),(44,44,6,5,594.00),(45,45,6,5,726.00),(46,46,6,5,957.00),(47,47,6,5,1320.00),(48,48,6,5,2145.00),(49,49,1,3,1.00),(50,50,1,3,0.00),(51,51,1,3,192.00),(52,52,1,3,577.00),(53,53,1,3,1346.00),(54,54,1,3,2692.00),(55,55,1,3,4808.00),(56,56,1,3,9615.00),(57,57,2,3,1.00),(58,58,2,3,962.00),(59,59,2,3,1154.00),(60,60,2,3,1538.00),(61,61,2,3,2308.00),(62,62,2,3,3654.00),(63,63,2,3,5769.00),(64,64,2,3,10577.00),(65,65,3,3,1.00),(66,66,3,3,1442.00),(67,67,3,3,1635.00),(68,68,3,3,2019.00),(69,69,3,3,2788.00),(70,70,3,3,4135.00),(71,71,3,3,6250.00),(72,72,3,3,11058.00),(73,73,4,3,1.00),(74,74,4,3,1923.00),(75,75,4,3,2115.00),(76,76,4,3,2500.00),(77,77,4,3,3269.00),(78,78,4,3,4615.00),(79,79,4,3,6731.00),(80,80,4,3,11538.00),(81,81,5,3,1.00),(82,82,5,3,2404.00),(83,83,5,3,2596.00),(84,84,5,3,2981.00),(85,85,5,3,3750.00),(86,86,5,3,5096.00),(87,87,5,3,7212.00),(88,88,5,3,12019.00),(89,89,6,3,1.00),(90,90,6,3,2885.00),(91,91,6,3,3077.00),(92,92,6,3,3462.00),(93,93,6,3,4231.00),(94,94,6,3,5577.00),(95,95,6,3,7692.00),(96,96,6,3,12500.00),(97,97,1,1,1.00),(98,98,1,1,0.00),(99,99,1,1,417.00),(100,100,1,1,1250.00),(101,101,1,1,2917.00),(102,102,1,1,5833.00),(103,103,1,1,10417.00),(104,104,1,1,20833.00),(105,105,2,1,1.00),(106,106,2,1,2083.00),(107,107,2,1,2500.00),(108,108,2,1,3333.00),(109,109,2,1,5000.00),(110,110,2,1,7917.00),(111,111,2,1,12500.00),(112,112,2,1,22917.00),(113,113,3,1,1.00),(114,114,3,1,3125.00),(115,115,3,1,3542.00),(116,116,3,1,4375.00),(117,117,3,1,6042.00),(118,118,3,1,8958.00),(119,119,3,1,13542.00),(120,120,3,1,23958.00),(121,121,4,1,1.00),(122,122,4,1,4167.00),(123,123,4,1,4583.00),(124,124,4,1,5417.00),(125,125,4,1,7083.00),(126,126,4,1,10000.00),(127,127,4,1,14583.00),(128,128,4,1,25000.00),(129,129,5,1,1.00),(130,130,5,1,5208.00),(131,131,5,1,5625.00),(132,132,5,1,6458.00),(133,133,5,1,8125.00),(134,134,5,1,11042.00),(135,135,5,1,15625.00),(136,136,5,1,26042.00),(137,137,6,1,1.00),(138,138,6,1,6250.00),(139,139,6,1,6667.00),(140,140,6,1,7500.00),(141,141,6,1,9167.00),(142,142,6,1,12083.00),(143,143,6,1,16667.00),(144,144,6,1,27083.00),(145,145,1,2,1.00),(146,146,1,2,0.00),(147,147,1,2,833.00),(148,148,1,2,2500.00),(149,149,1,2,5833.00),(150,150,1,2,11667.00),(151,151,1,2,20833.00),(152,152,1,2,41667.00),(153,153,2,2,1.00),(154,154,2,2,4167.00),(155,155,2,2,5000.00),(156,156,2,2,6667.00),(157,157,2,2,10000.00),(158,158,2,2,15833.00),(159,159,2,2,25000.00),(160,160,2,2,45883.00),(161,161,3,2,1.00),(162,162,3,2,6250.00),(163,163,3,2,7083.00),(164,164,3,2,8750.00),(165,165,3,2,12083.00),(166,166,3,2,17917.00),(167,167,3,2,27083.00),(168,168,3,2,47917.00),(169,169,4,2,1.00),(170,170,4,2,8333.00),(171,171,4,2,9167.00),(172,172,4,2,10833.00),(173,173,4,2,14167.00),(174,174,4,2,20000.00),(175,175,4,2,29167.00),(176,176,4,2,50000.00),(177,177,5,2,1.00),(178,178,5,2,10417.00),(179,179,5,2,11250.00),(180,180,5,2,12917.00),(181,181,5,2,16250.00),(182,182,5,2,22083.00),(183,183,5,2,31250.00),(184,184,5,2,52083.00),(185,185,6,2,1.00),(186,186,6,2,12500.00),(187,187,6,2,13333.00),(188,188,6,2,15000.00),(189,189,6,2,18333.00),(190,190,6,2,24167.00),(191,191,6,2,33333.00),(192,192,6,2,54167.00);
/*!40000 ALTER TABLE `pay_taxmatrix` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_taxstatus`
--

DROP TABLE IF EXISTS `pay_taxstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_taxstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personal_exemption` decimal(10,2) NOT NULL,
  `additional_exemption` decimal(10,2) NOT NULL,
  `total_exemption` decimal(10,2) NOT NULL,
  `code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_taxstatus`
--

LOCK TABLES `pay_taxstatus` WRITE;
/*!40000 ALTER TABLE `pay_taxstatus` DISABLE KEYS */;
INSERT INTO `pay_taxstatus` VALUES (1,0.00,0.00,0.00,'Z'),(2,50000.00,0.00,50000.00,'ME/S'),(3,50000.00,25000.00,75000.00,'ME1/S1'),(4,50000.00,50000.00,100000.00,'ME2/S2'),(5,50000.00,75000.00,125000.00,'ME3/S3'),(6,50000.00,100000.00,150000.00,'ME4/S4');
/*!40000 ALTER TABLE `pay_taxstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_thirteenth`
--

DROP TABLE IF EXISTS `pay_thirteenth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_thirteenth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `fs_year` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `total_taxable` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `flag_locked` tinyint(1) NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_91B4247B8C03F15C` (`employee_id`),
  KEY `IDX_91B4247BEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_91B4247B8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_91B4247BEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_thirteenth`
--

LOCK TABLES `pay_thirteenth` WRITE;
/*!40000 ALTER TABLE `pay_thirteenth` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_thirteenth` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_phone`
--

DROP TABLE IF EXISTS `profile_phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_phone` (
  `profile_id` int(11) NOT NULL,
  `phone_id` int(11) NOT NULL,
  PRIMARY KEY (`profile_id`,`phone_id`),
  KEY `IDX_B39B080FCCFA12B8` (`profile_id`),
  KEY `IDX_B39B080F3B7323CB` (`phone_id`),
  CONSTRAINT `FK_B39B080F3B7323CB` FOREIGN KEY (`phone_id`) REFERENCES `cnt_phone` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B39B080FCCFA12B8` FOREIGN KEY (`profile_id`) REFERENCES `hr_employee_profile` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_phone`
--

LOCK TABLES `profile_phone` WRITE;
/*!40000 ALTER TABLE `profile_phone` DISABLE KEYS */;
INSERT INTO `profile_phone` VALUES (28,10),(29,9),(36,11),(36,12),(37,13);
/*!40000 ALTER TABLE `profile_phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rem_cashbond`
--

DROP TABLE IF EXISTS `rem_cashbond`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rem_cashbond` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL,
  `code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2F81110E8C03F15C` (`employee_id`),
  KEY `IDX_2F81110EEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_2F81110E8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_2F81110EEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rem_cashbond`
--

LOCK TABLES `rem_cashbond` WRITE;
/*!40000 ALTER TABLE `rem_cashbond` DISABLE KEYS */;
/*!40000 ALTER TABLE `rem_cashbond` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rem_cashbondloan`
--

DROP TABLE IF EXISTS `rem_cashbondloan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rem_cashbondloan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `cashbond_id` int(11) DEFAULT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_approved` datetime DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B1A127248C03F15C` (`employee_id`),
  KEY `IDX_B1A12724B8793F9C` (`cashbond_id`),
  KEY `IDX_B1A12724BB23766C` (`approver_id`),
  KEY `IDX_B1A12724EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_B1A127248C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_B1A12724B8793F9C` FOREIGN KEY (`cashbond_id`) REFERENCES `rem_cashbond` (`id`),
  CONSTRAINT `FK_B1A12724BB23766C` FOREIGN KEY (`approver_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_B1A12724EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rem_cashbondloan`
--

LOCK TABLES `rem_cashbondloan` WRITE;
/*!40000 ALTER TABLE `rem_cashbondloan` DISABLE KEYS */;
/*!40000 ALTER TABLE `rem_cashbondloan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rem_cashbondtransaction`
--

DROP TABLE IF EXISTS `rem_cashbondtransaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rem_cashbondtransaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cashbond_id` int(11) DEFAULT NULL,
  `payroll_period_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `debit` decimal(10,2) NOT NULL,
  `credit` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B955A0F7B8793F9C` (`cashbond_id`),
  KEY `IDX_B955A0F75DD5005B` (`payroll_period_id`),
  KEY `IDX_B955A0F7EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_B955A0F75DD5005B` FOREIGN KEY (`payroll_period_id`) REFERENCES `pay_payroll_period` (`id`),
  CONSTRAINT `FK_B955A0F7B8793F9C` FOREIGN KEY (`cashbond_id`) REFERENCES `rem_cashbond` (`id`),
  CONSTRAINT `FK_B955A0F7EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rem_cashbondtransaction`
--

LOCK TABLES `rem_cashbondtransaction` WRITE;
/*!40000 ALTER TABLE `rem_cashbondtransaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `rem_cashbondtransaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rem_incentive`
--

DROP TABLE IF EXISTS `rem_incentive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rem_incentive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `date_filed` datetime NOT NULL,
  `date_approved` datetime DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A0509AA7CCCFBA31` (`upload_id`),
  KEY `IDX_A0509AA78C03F15C` (`employee_id`),
  KEY `IDX_A0509AA7BB23766C` (`approver_id`),
  KEY `IDX_A0509AA7EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_A0509AA78C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_A0509AA7BB23766C` FOREIGN KEY (`approver_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_A0509AA7CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_A0509AA7EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rem_incentive`
--

LOCK TABLES `rem_incentive` WRITE;
/*!40000 ALTER TABLE `rem_incentive` DISABLE KEYS */;
/*!40000 ALTER TABLE `rem_incentive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rem_loan_payments`
--

DROP TABLE IF EXISTS `rem_loan_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rem_loan_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `date_paid` datetime NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C8309007CE73868F` (`loan_id`),
  KEY `IDX_C8309007EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_C8309007CE73868F` FOREIGN KEY (`loan_id`) REFERENCES `rem_loans` (`id`),
  CONSTRAINT `FK_C8309007EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rem_loan_payments`
--

LOCK TABLES `rem_loan_payments` WRITE;
/*!40000 ALTER TABLE `rem_loan_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `rem_loan_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rem_loans`
--

DROP TABLE IF EXISTS `rem_loans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rem_loans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `date_filed` datetime NOT NULL,
  `date_approved` datetime DEFAULT NULL,
  `type` longtext COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `paid` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `flag_auto` tinyint(1) NOT NULL,
  `code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F30E7C84CCCFBA31` (`upload_id`),
  KEY `IDX_F30E7C848C03F15C` (`employee_id`),
  KEY `IDX_F30E7C84BB23766C` (`approver_id`),
  KEY `IDX_F30E7C84EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_F30E7C848C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_F30E7C84BB23766C` FOREIGN KEY (`approver_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_F30E7C84CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_F30E7C84EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rem_loans`
--

LOCK TABLES `rem_loans` WRITE;
/*!40000 ALTER TABLE `rem_loans` DISABLE KEYS */;
/*!40000 ALTER TABLE `rem_loans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `access` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8F02BF9D5E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_group`
--

LOCK TABLES `user_group` WRITE;
/*!40000 ALTER TABLE `user_group` DISABLE KEYS */;
INSERT INTO `user_group` VALUES (1,'hr_admin','a:0:{}','a:188:{s:18:\"cat_dashboard.menu\";b:1;s:15:\"cat_config.menu\";b:1;s:15:\"cat_config.view\";b:1;s:15:\"cat_config.edit\";b:1;s:18:\"cat_user_user.menu\";b:1;s:18:\"cat_user_user.view\";b:1;s:17:\"cat_user_user.add\";b:1;s:18:\"cat_user_user.edit\";b:1;s:20:\"cat_user_user.delete\";b:1;s:19:\"cat_user_group.menu\";b:1;s:19:\"cat_user_group.view\";b:1;s:18:\"cat_user_group.add\";b:1;s:19:\"cat_user_group.edit\";b:1;s:21:\"cat_user_group.delete\";b:1;s:14:\"cat_admin.menu\";b:1;s:17:\"hris_omnibar.view\";b:1;s:15:\"hris_admin.menu\";b:1;s:26:\"hris_admin_department.menu\";b:1;s:26:\"hris_admin_department.view\";b:1;s:25:\"hris_admin_department.add\";b:1;s:26:\"hris_admin_department.edit\";b:1;s:28:\"hris_admin_department.delete\";b:1;s:22:\"hris_admin_events.menu\";b:1;s:22:\"hris_admin_events.view\";b:1;s:21:\"hris_admin_events.add\";b:1;s:22:\"hris_admin_events.edit\";b:1;s:24:\"hris_admin_events.delete\";b:1;s:25:\"hris_admin_jobtitles.menu\";b:1;s:25:\"hris_admin_jobtitles.view\";b:1;s:24:\"hris_admin_jobtitles.add\";b:1;s:25:\"hris_admin_jobtitles.edit\";b:1;s:27:\"hris_admin_jobtitles.delete\";b:1;s:24:\"hris_admin_joblevel.menu\";b:1;s:24:\"hris_admin_joblevel.view\";b:1;s:23:\"hris_admin_joblevel.add\";b:1;s:24:\"hris_admin_joblevel.edit\";b:1;s:26:\"hris_admin_joblevel.delete\";b:1;s:24:\"hris_admin_location.menu\";b:1;s:24:\"hris_admin_location.view\";b:1;s:23:\"hris_admin_location.add\";b:1;s:24:\"hris_admin_location.edit\";b:1;s:26:\"hris_admin_location.delete\";b:1;s:23:\"hris_admin_benefit.menu\";b:1;s:23:\"hris_admin_benefit.view\";b:1;s:22:\"hris_admin_benefit.add\";b:1;s:23:\"hris_admin_benefit.edit\";b:1;s:25:\"hris_admin_benefit.delete\";b:1;s:25:\"hris_admin_schedules.menu\";b:1;s:25:\"hris_admin_schedules.view\";b:1;s:24:\"hris_admin_schedules.add\";b:1;s:25:\"hris_admin_schedules.edit\";b:1;s:28:\"hris_admin_schedules. delete\";b:1;s:25:\"hris_admin_checklist.menu\";b:1;s:25:\"hris_admin_checklist.view\";b:1;s:24:\"hris_admin_checklist.add\";b:1;s:25:\"hris_admin_checklist.edit\";b:1;s:27:\"hris_admin_checklist.delete\";b:1;s:23:\"hris_admin_holiday.menu\";b:1;s:23:\"hris_admin_holiday.view\";b:1;s:22:\"hris_admin_holiday.add\";b:1;s:23:\"hris_admin_holiday.edit\";b:1;s:25:\"hris_admin_holiday.delete\";b:1;s:29:\"hris_admin_downloadables.menu\";b:1;s:29:\"hris_admin_downloadables.view\";b:1;s:28:\"hris_admin_downloadables.add\";b:1;s:29:\"hris_admin_downloadables.edit\";b:1;s:31:\"hris_admin_downloadables.delete\";b:1;s:21:\"hris_admin_leave.menu\";b:1;s:26:\"hris_admin_leave_type.menu\";b:1;s:26:\"hris_admin_leave_type.view\";b:1;s:25:\"hris_admin_leave_type.add\";b:1;s:26:\"hris_admin_leave_type.edit\";b:1;s:28:\"hris_admin_leave_type.delete\";b:1;s:27:\"hris_admin_leave_rules.menu\";b:1;s:27:\"hris_admin_leave_rules.view\";b:1;s:26:\"hris_admin_leave_rules.add\";b:1;s:27:\"hris_admin_leave_rules.edit\";b:1;s:29:\"hris_admin_leave_rules.delete\";b:1;s:34:\"hris_admin_appraisal_settings.menu\";b:1;s:34:\"hris_admin_appraisal_settings.view\";b:1;s:33:\"hris_admin_appraisal_settings.add\";b:1;s:34:\"hris_admin_appraisal_settings.edit\";b:1;s:36:\"hris_admin_appraisal_settings.delete\";b:1;s:25:\"hris_admin_otsetting.menu\";b:1;s:25:\"hris_admin_otsetting.edit\";b:1;s:20:\"hris_biometrics.menu\";b:1;s:20:\"hris_biometrics.view\";b:1;s:19:\"hris_biometrics.add\";b:1;s:19:\"hris_workforce.menu\";b:1;s:28:\"hris_workforce_employee.menu\";b:1;s:28:\"hris_workforce_employee.view\";b:1;s:27:\"hris_workforce_employee.add\";b:1;s:28:\"hris_workforce_employee.edit\";b:1;s:30:\"hris_workforce_attendance.menu\";b:1;s:30:\"hris_workforce_attendance.view\";b:1;s:30:\"hris_workforce_attendance.edit\";b:1;s:32:\"hris_workforce_attendance.review\";b:1;s:33:\"hris_workforce_attendance.approve\";b:1;s:32:\"hris_workforce_attendance.reject\";b:1;s:25:\"hris_workforce_leave.menu\";b:1;s:25:\"hris_workforce_leave.view\";b:1;s:25:\"hris_workforce_leave.edit\";b:1;s:27:\"hris_workforce_leave.reject\";b:1;s:27:\"hris_workforce_leave.review\";b:1;s:30:\"hris_workforce_appraisals.menu\";b:1;s:30:\"hris_workforce_appraisals.view\";b:1;s:29:\"hris_workforce_appraisals.add\";b:1;s:30:\"hris_workforce_appraisals.edit\";b:1;s:32:\"hris_workforce_appraisals.delete\";b:1;s:34:\"hris_workforce_appraisals.evaluate\";b:1;s:35:\"hris_workforce_issued_property.menu\";b:1;s:35:\"hris_workforce_issued_property.view\";b:1;s:34:\"hris_workforce_issued_property.add\";b:1;s:35:\"hris_workforce_issued_property.edit\";b:1;s:14:\"hris_memo.menu\";b:1;s:14:\"hris_memo.view\";b:1;s:13:\"hris_memo.add\";b:1;s:14:\"hris_memo.edit\";b:1;s:26:\"hris_workforce_resign.menu\";b:1;s:26:\"hris_workforce_resign.edit\";b:1;s:26:\"hris_workforce_resign.view\";b:1;s:33:\"hris_workforce_reimbursement.menu\";b:1;s:33:\"hris_workforce_reimbursement.view\";b:1;s:32:\"hris_workforce_reimbursement.add\";b:1;s:33:\"hris_workforce_reimbursement.edit\";b:1;s:17:\"hris_recruit.menu\";b:1;s:22:\"hris_applications.menu\";b:1;s:22:\"hris_applications.view\";b:1;s:21:\"hris_applications.add\";b:1;s:22:\"hris_applications.edit\";b:1;s:24:\"hris_applications.delete\";b:1;s:36:\"hris_applications_edit_progress.view\";b:1;s:36:\"hris_applications_edit_progress.edit\";b:1;s:21:\"hris_requisition.menu\";b:1;s:21:\"hris_requisition.view\";b:1;s:20:\"hris_requisition.add\";b:1;s:21:\"hris_requisition.edit\";b:1;s:23:\"hris_requisition.delete\";b:1;s:17:\"hris_com_info.add\";b:1;s:17:\"hris_payroll.menu\";b:1;s:25:\"hris_payroll_setting.menu\";b:1;s:21:\"hris_payroll_sss.menu\";b:1;s:21:\"hris_payroll_sss.view\";b:1;s:20:\"hris_payroll_sss.add\";b:1;s:21:\"hris_payroll_sss.edit\";b:1;s:23:\"hris_payroll_sss.delete\";b:1;s:28:\"hris_payroll_philhealth.menu\";b:1;s:28:\"hris_payroll_philhealth.view\";b:1;s:27:\"hris_payroll_philhealth.add\";b:1;s:28:\"hris_payroll_philhealth.edit\";b:1;s:30:\"hris_payroll_philhealth.delete\";b:1;s:21:\"hris_payroll_tax.menu\";b:1;s:21:\"hris_payroll_tax.view\";b:1;s:20:\"hris_payroll_tax.add\";b:1;s:21:\"hris_payroll_tax.edit\";b:1;s:23:\"hris_payroll_tax.delete\";b:1;s:24:\"hris_payroll_weekly.menu\";b:1;s:29:\"hris_payroll_semimonthly.menu\";b:1;s:26:\"hris_payroll_generate.menu\";b:1;s:26:\"hris_payroll_generate.view\";b:1;s:33:\"hris_payroll_weekly_generate.menu\";b:1;s:33:\"hris_payroll_weekly_generate.view\";b:1;s:24:\"hris_payroll_review.menu\";b:1;s:24:\"hris_payroll_review.view\";b:1;s:22:\"hris_payroll_view.menu\";b:1;s:22:\"hris_payroll_view.view\";b:1;s:25:\"hris_payroll_view.details\";b:1;s:16:\"hris_report.menu\";b:1;s:22:\"hris_report_leave.menu\";b:1;s:22:\"hris_report_leave.view\";b:1;s:27:\"hris_report_attendance.menu\";b:1;s:27:\"hris_report_attendance.view\";b:1;s:30:\"hris_report_reimbursement.menu\";b:1;s:30:\"hris_report_reimbursement.view\";b:1;s:25:\"hris_report_regulars.menu\";b:1;s:25:\"hris_report_regulars.view\";b:1;s:30:\"hris_report_total_expense.menu\";b:1;s:30:\"hris_report_total_expense.view\";b:1;s:25:\"hris_report_turnover.menu\";b:1;s:25:\"hris_report_turnover.view\";b:1;s:22:\"hris_report_loans.menu\";b:1;s:22:\"hris_report_loans.view\";b:1;s:27:\"hris_report_incentives.menu\";b:1;s:27:\"hris_report_incentives.view\";b:1;s:22:\"hris_report_evals.menu\";b:1;s:22:\"hris_report_evals.view\";b:1;s:24:\"hris_report_payroll.menu\";b:1;s:24:\"hris_report_payroll.view\";b:1;}'),(2,'employee','a:0:{}','a:31:{s:18:\"cat_dashboard.menu\";b:1;s:30:\"hris_workforce_appraisals.menu\";b:1;s:30:\"hris_workforce_appraisals.view\";b:1;s:34:\"hris_workforce_appraisals.evaluate\";b:1;s:33:\"hris_workforce_reimbursement.view\";b:1;s:32:\"hris_workforce_reimbursement.add\";b:1;s:33:\"hris_workforce_reimbursement.edit\";b:1;s:22:\"hris_com_overview.menu\";b:1;s:18:\"hris_com_info.menu\";b:1;s:18:\"hris_com_info.view\";b:1;s:22:\"hris_com_orgchart.menu\";b:1;s:22:\"hris_com_orgchart.view\";b:1;s:23:\"hris_com_directory.menu\";b:1;s:23:\"hris_com_directory.view\";b:1;s:26:\"hris_profile_employee.menu\";b:1;s:26:\"hris_profile_employee.edit\";b:1;s:25:\"hris_profile_request.menu\";b:1;s:24:\"hris_profile_request.add\";b:1;s:25:\"hris_profile_request.view\";b:1;s:25:\"hris_profile_request.edit\";b:1;s:27:\"hris_profile_request.delete\";b:1;s:23:\"hris_profile_leave.menu\";b:1;s:22:\"hris_profile_leave.add\";b:1;s:23:\"hris_profile_leave.view\";b:1;s:23:\"hris_profile_leave.edit\";b:1;s:25:\"hris_profile_leave.delete\";b:1;s:28:\"hris_profile_attendance.menu\";b:1;s:27:\"hris_profile_attendance.add\";b:1;s:28:\"hris_profile_attendance.view\";b:1;s:28:\"hris_profile_attendance.edit\";b:1;s:30:\"hris_profile_attendance.delete\";b:1;}'),(3,'dept_head','a:0:{}','a:19:{s:19:\"hris_workforce.menu\";b:1;s:30:\"hris_workforce_attendance.menu\";b:1;s:30:\"hris_workforce_attendance.view\";b:1;s:25:\"hris_workforce_leave.menu\";b:1;s:25:\"hris_workforce_leave.view\";b:1;s:25:\"hris_workforce_leave.edit\";b:1;s:27:\"hris_workforce_leave.reject\";b:1;s:27:\"hris_workforce_leave.review\";b:1;s:30:\"hris_workforce_appraisals.menu\";b:1;s:30:\"hris_workforce_appraisals.view\";b:1;s:30:\"hris_workforce_appraisals.edit\";b:1;s:17:\"hris_recruit.menu\";b:1;s:21:\"hris_requisition.menu\";b:1;s:21:\"hris_requisition.view\";b:1;s:20:\"hris_requisition.add\";b:1;s:21:\"hris_requisition.edit\";b:1;s:23:\"hris_requisition.delete\";b:1;s:23:\"hris_requisition.review\";b:1;s:23:\"hris_requisition.reject\";b:1;}'),(4,'vp_operations','a:0:{}','a:106:{s:14:\"cat_admin.menu\";b:1;s:17:\"hris_omnibar.view\";b:1;s:15:\"hris_admin.menu\";b:1;s:26:\"hris_admin_department.menu\";b:1;s:26:\"hris_admin_department.view\";b:1;s:25:\"hris_admin_department.add\";b:1;s:26:\"hris_admin_department.edit\";b:1;s:28:\"hris_admin_department.delete\";b:1;s:25:\"hris_admin_jobtitles.menu\";b:1;s:25:\"hris_admin_jobtitles.view\";b:1;s:24:\"hris_admin_jobtitles.add\";b:1;s:25:\"hris_admin_jobtitles.edit\";b:1;s:27:\"hris_admin_jobtitles.delete\";b:1;s:24:\"hris_admin_joblevel.menu\";b:1;s:24:\"hris_admin_joblevel.view\";b:1;s:23:\"hris_admin_joblevel.add\";b:1;s:24:\"hris_admin_joblevel.edit\";b:1;s:26:\"hris_admin_joblevel.delete\";b:1;s:24:\"hris_admin_location.menu\";b:1;s:24:\"hris_admin_location.view\";b:1;s:23:\"hris_admin_location.add\";b:1;s:24:\"hris_admin_location.edit\";b:1;s:26:\"hris_admin_location.delete\";b:1;s:23:\"hris_admin_benefit.menu\";b:1;s:23:\"hris_admin_benefit.view\";b:1;s:22:\"hris_admin_benefit.add\";b:1;s:23:\"hris_admin_benefit.edit\";b:1;s:25:\"hris_admin_benefit.delete\";b:1;s:25:\"hris_admin_schedules.menu\";b:1;s:25:\"hris_admin_schedules.view\";b:1;s:24:\"hris_admin_schedules.add\";b:1;s:25:\"hris_admin_schedules.edit\";b:1;s:28:\"hris_admin_schedules. delete\";b:1;s:25:\"hris_admin_checklist.menu\";b:1;s:25:\"hris_admin_checklist.view\";b:1;s:24:\"hris_admin_checklist.add\";b:1;s:25:\"hris_admin_checklist.edit\";b:1;s:27:\"hris_admin_checklist.delete\";b:1;s:23:\"hris_admin_holiday.menu\";b:1;s:23:\"hris_admin_holiday.view\";b:1;s:22:\"hris_admin_holiday.add\";b:1;s:23:\"hris_admin_holiday.edit\";b:1;s:25:\"hris_admin_holiday.delete\";b:1;s:29:\"hris_admin_downloadables.menu\";b:1;s:29:\"hris_admin_downloadables.view\";b:1;s:28:\"hris_admin_downloadables.add\";b:1;s:29:\"hris_admin_downloadables.edit\";b:1;s:31:\"hris_admin_downloadables.delete\";b:1;s:19:\"hris_workforce.menu\";b:1;s:28:\"hris_workforce_employee.menu\";b:1;s:28:\"hris_workforce_employee.view\";b:1;s:27:\"hris_workforce_employee.add\";b:1;s:28:\"hris_workforce_employee.edit\";b:1;s:30:\"hris_workforce_attendance.menu\";b:1;s:30:\"hris_workforce_attendance.view\";b:1;s:25:\"hris_workforce_leave.menu\";b:1;s:25:\"hris_workforce_leave.view\";b:1;s:25:\"hris_workforce_leave.edit\";b:1;s:28:\"hris_workforce_leave.approve\";b:1;s:27:\"hris_workforce_leave.reject\";b:1;s:30:\"hris_workforce_appraisals.menu\";b:1;s:30:\"hris_workforce_appraisals.view\";b:1;s:29:\"hris_workforce_appraisals.add\";b:1;s:30:\"hris_workforce_appraisals.edit\";b:1;s:35:\"hris_workforce_issued_property.menu\";b:1;s:35:\"hris_workforce_issued_property.view\";b:1;s:34:\"hris_workforce_issued_property.add\";b:1;s:35:\"hris_workforce_issued_property.edit\";b:1;s:26:\"hris_workforce_resign.menu\";b:1;s:26:\"hris_workforce_resign.view\";b:1;s:33:\"hris_workforce_reimbursement.menu\";b:1;s:33:\"hris_workforce_reimbursement.view\";b:1;s:32:\"hris_workforce_reimbursement.add\";b:1;s:33:\"hris_workforce_reimbursement.edit\";b:1;s:17:\"hris_recruit.menu\";b:1;s:21:\"hris_requisition.menu\";b:1;s:21:\"hris_requisition.view\";b:1;s:20:\"hris_requisition.add\";b:1;s:21:\"hris_requisition.edit\";b:1;s:23:\"hris_requisition.delete\";b:1;s:24:\"hris_requisition.approve\";b:1;s:23:\"hris_requisition.reject\";b:1;s:22:\"hris_com_overview.menu\";b:1;s:18:\"hris_com_info.menu\";b:1;s:22:\"hris_com_orgchart.menu\";b:1;s:23:\"hris_com_directory.menu\";b:1;s:17:\"hris_payroll.menu\";b:1;s:25:\"hris_payroll_setting.menu\";b:1;s:21:\"hris_payroll_sss.menu\";b:1;s:21:\"hris_payroll_sss.view\";b:1;s:20:\"hris_payroll_sss.add\";b:1;s:21:\"hris_payroll_sss.edit\";b:1;s:23:\"hris_payroll_sss.delete\";b:1;s:28:\"hris_payroll_philhealth.menu\";b:1;s:28:\"hris_payroll_philhealth.view\";b:1;s:27:\"hris_payroll_philhealth.add\";b:1;s:28:\"hris_payroll_philhealth.edit\";b:1;s:30:\"hris_payroll_philhealth.delete\";b:1;s:21:\"hris_payroll_tax.menu\";b:1;s:21:\"hris_payroll_tax.view\";b:1;s:20:\"hris_payroll_tax.add\";b:1;s:21:\"hris_payroll_tax.edit\";b:1;s:23:\"hris_payroll_tax.delete\";b:1;s:26:\"hris_profile_employee.menu\";b:1;s:16:\"hris_report.menu\";b:1;s:23:\"hris_report_leaves.menu\";b:1;}'),(5,'hr_recruitment','a:0:{}','a:97:{s:18:\"cat_dashboard.menu\";b:1;s:14:\"cat_admin.menu\";b:1;s:17:\"hris_omnibar.view\";b:1;s:15:\"hris_admin.menu\";b:1;s:26:\"hris_admin_department.menu\";b:1;s:26:\"hris_admin_department.view\";b:1;s:25:\"hris_admin_department.add\";b:1;s:26:\"hris_admin_department.edit\";b:1;s:22:\"hris_admin_events.menu\";b:1;s:22:\"hris_admin_events.view\";b:1;s:21:\"hris_admin_events.add\";b:1;s:22:\"hris_admin_events.edit\";b:1;s:25:\"hris_admin_jobtitles.menu\";b:1;s:25:\"hris_admin_jobtitles.view\";b:1;s:24:\"hris_admin_jobtitles.add\";b:1;s:25:\"hris_admin_jobtitles.edit\";b:1;s:24:\"hris_admin_joblevel.menu\";b:1;s:24:\"hris_admin_joblevel.view\";b:1;s:23:\"hris_admin_joblevel.add\";b:1;s:24:\"hris_admin_joblevel.edit\";b:1;s:24:\"hris_admin_location.menu\";b:1;s:24:\"hris_admin_location.view\";b:1;s:23:\"hris_admin_location.add\";b:1;s:24:\"hris_admin_location.edit\";b:1;s:26:\"hris_admin_location.delete\";b:1;s:23:\"hris_admin_benefit.menu\";b:1;s:23:\"hris_admin_benefit.view\";b:1;s:22:\"hris_admin_benefit.add\";b:1;s:25:\"hris_admin_schedules.menu\";b:1;s:25:\"hris_admin_schedules.view\";b:1;s:24:\"hris_admin_schedules.add\";b:1;s:25:\"hris_admin_checklist.menu\";b:1;s:25:\"hris_admin_checklist.view\";b:1;s:24:\"hris_admin_checklist.add\";b:1;s:25:\"hris_admin_checklist.edit\";b:1;s:23:\"hris_admin_holiday.menu\";b:1;s:23:\"hris_admin_holiday.view\";b:1;s:22:\"hris_admin_holiday.add\";b:1;s:23:\"hris_admin_holiday.edit\";b:1;s:29:\"hris_admin_downloadables.menu\";b:1;s:29:\"hris_admin_downloadables.view\";b:1;s:28:\"hris_admin_downloadables.add\";b:1;s:29:\"hris_admin_downloadables.edit\";b:1;s:21:\"hris_admin_leave.menu\";b:1;s:26:\"hris_admin_leave_type.menu\";b:1;s:26:\"hris_admin_leave_type.view\";b:1;s:25:\"hris_admin_leave_type.add\";b:1;s:27:\"hris_admin_leave_rules.menu\";b:1;s:27:\"hris_admin_leave_rules.view\";b:1;s:26:\"hris_admin_leave_rules.add\";b:1;s:34:\"hris_admin_appraisal_settings.menu\";b:1;s:34:\"hris_admin_appraisal_settings.view\";b:1;s:33:\"hris_admin_appraisal_settings.add\";b:1;s:34:\"hris_admin_appraisal_settings.edit\";b:1;s:25:\"hris_admin_otsetting.menu\";b:1;s:25:\"hris_admin_otsetting.edit\";b:1;s:19:\"hris_workforce.menu\";b:1;s:28:\"hris_workforce_employee.menu\";b:1;s:28:\"hris_workforce_employee.view\";b:1;s:30:\"hris_workforce_attendance.menu\";b:1;s:30:\"hris_workforce_attendance.view\";b:1;s:25:\"hris_workforce_leave.menu\";b:1;s:25:\"hris_workforce_leave.view\";b:1;s:28:\"hris_workforce_leave.approve\";b:1;s:27:\"hris_workforce_leave.reject\";b:1;s:27:\"hris_workforce_leave.review\";b:1;s:35:\"hris_workforce_issued_property.menu\";b:1;s:35:\"hris_workforce_issued_property.view\";b:1;s:34:\"hris_workforce_issued_property.add\";b:1;s:26:\"hris_workforce_resign.menu\";b:1;s:26:\"hris_workforce_resign.view\";b:1;s:14:\"hris_cash.menu\";b:1;s:33:\"hris_workforce_reimbursement.menu\";b:1;s:33:\"hris_workforce_reimbursement.view\";b:1;s:32:\"hris_workforce_reimbursement.add\";b:1;s:33:\"hris_workforce_reimbursement.edit\";b:1;s:17:\"hris_recruit.menu\";b:1;s:22:\"hris_applications.menu\";b:1;s:22:\"hris_applications.view\";b:1;s:21:\"hris_applications.add\";b:1;s:22:\"hris_applications.edit\";b:1;s:24:\"hris_applications.delete\";b:1;s:36:\"hris_applications_edit_progress.view\";b:1;s:36:\"hris_applications_edit_progress.edit\";b:1;s:21:\"hris_requisition.menu\";b:1;s:21:\"hris_requisition.view\";b:1;s:20:\"hris_requisition.add\";b:1;s:21:\"hris_requisition.edit\";b:1;s:23:\"hris_requisition.delete\";b:1;s:22:\"hris_com_overview.menu\";b:1;s:18:\"hris_com_info.menu\";b:1;s:18:\"hris_com_info.view\";b:1;s:17:\"hris_com_info.add\";b:1;s:22:\"hris_com_orgchart.menu\";b:1;s:22:\"hris_com_orgchart.view\";b:1;s:23:\"hris_com_directory.menu\";b:1;s:23:\"hris_com_directory.view\";b:1;}'),(6,'hr_comp_ben','a:0:{}','a:15:{s:18:\"cat_dashboard.menu\";b:1;s:15:\"hris_admin.menu\";b:1;s:23:\"hris_admin_benefit.menu\";b:1;s:23:\"hris_admin_benefit.view\";b:1;s:23:\"hris_admin_benefit.edit\";b:1;s:19:\"hris_workforce.menu\";b:1;s:30:\"hris_workforce_attendance.menu\";b:1;s:30:\"hris_workforce_attendance.view\";b:1;s:35:\"hris_workforce_issued_property.menu\";b:1;s:35:\"hris_workforce_issued_property.view\";b:1;s:14:\"hris_cash.menu\";b:1;s:33:\"hris_workforce_reimbursement.menu\";b:1;s:33:\"hris_workforce_reimbursement.view\";b:1;s:32:\"hris_workforce_reimbursement.add\";b:1;s:33:\"hris_workforce_reimbursement.edit\";b:1;}'),(7,'hr_emp_relations','a:0:{}','a:36:{s:18:\"cat_dashboard.menu\";b:1;s:15:\"hris_admin.menu\";b:1;s:23:\"hris_admin_benefit.menu\";b:1;s:23:\"hris_admin_benefit.view\";b:1;s:23:\"hris_admin_benefit.edit\";b:1;s:25:\"hris_admin_schedules.menu\";b:1;s:25:\"hris_admin_schedules.view\";b:1;s:25:\"hris_admin_schedules.edit\";b:1;s:19:\"hris_workforce.menu\";b:1;s:30:\"hris_workforce_attendance.menu\";b:1;s:30:\"hris_workforce_attendance.view\";b:1;s:30:\"hris_workforce_appraisals.menu\";b:1;s:30:\"hris_workforce_appraisals.view\";b:1;s:29:\"hris_workforce_appraisals.add\";b:1;s:30:\"hris_workforce_appraisals.edit\";b:1;s:32:\"hris_workforce_appraisals.delete\";b:1;s:34:\"hris_workforce_appraisals.evaluate\";b:1;s:35:\"hris_workforce_issued_property.menu\";b:1;s:35:\"hris_workforce_issued_property.view\";b:1;s:34:\"hris_workforce_issued_property.add\";b:1;s:26:\"hris_workforce_resign.menu\";b:1;s:26:\"hris_workforce_resign.edit\";b:1;s:26:\"hris_workforce_resign.view\";b:1;s:14:\"hris_cash.menu\";b:1;s:33:\"hris_workforce_reimbursement.menu\";b:1;s:33:\"hris_workforce_reimbursement.view\";b:1;s:32:\"hris_workforce_reimbursement.add\";b:1;s:33:\"hris_workforce_reimbursement.edit\";b:1;s:17:\"hris_recruit.menu\";b:1;s:22:\"hris_applications.menu\";b:1;s:22:\"hris_applications.view\";b:1;s:21:\"hris_applications.add\";b:1;s:22:\"hris_applications.edit\";b:1;s:24:\"hris_applications.delete\";b:1;s:36:\"hris_applications_edit_progress.view\";b:1;s:36:\"hris_applications_edit_progress.edit\";b:1;}'),(8,'Biometrics Operator','a:0:{}','a:7:{s:15:\"hris_admin.menu\";b:1;s:20:\"hris_biometrics.menu\";b:1;s:20:\"hris_biometrics.view\";b:1;s:19:\"hris_biometrics.add\";b:1;s:19:\"hris_workforce.menu\";b:1;s:30:\"hris_workforce_attendance.menu\";b:1;s:30:\"hris_workforce_attendance.view\";b:1;}');
/*!40000 ALTER TABLE `user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_user`
--

DROP TABLE IF EXISTS `user_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `flag_emailnotify` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F7129A8092FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_F7129A808C03F15C` (`employee_id`),
  CONSTRAINT `FK_F7129A808C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_user`
--

LOCK TABLES `user_user` WRITE;
/*!40000 ALTER TABLE `user_user` DISABLE KEYS */;
INSERT INTO `user_user` VALUES (1,NULL,'admin','admin',1,'9qifyejmbyscgwcs8wcggo8oc0w8kgk','LL0z6rUHkl8AgpbQ48O+p2COCtx4DxLrWLOkmst5f9fiaW2IXxcUt7AbU8rscJEiAde33+bHjpzwdYHl0JcHfw==','2016-05-12 07:19:12',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Administrator','test@test.com','test@test.com',0),(2,NULL,'hr_lilys','hr_lilys',1,'4pz5svcrb8g0okkkogkso88440kgwkg','YXVEj8wRJeGktP/6+nfGyvG2uczJm1raJRSGX2ZmbANcL2dZIN9+tzfoxv3jI7SAspqvlak9y55Pu93Hr8EDwQ==',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'HR-Admin','hr@mylilys.com','hr@mylilys.com',0),(30,28,'lmmartin','lmmartin',1,'ixhnr4ecel4cckosgg8g0888o8skssk','xlDFUTCedB+p6v8K9xVqjKVXsiZTH4Y1ZrSgXMo8+IwP+wewFVPLC9cI1pKbJMbg3btP58Q47HdW+l+ymlcN3A==','2015-11-16 17:43:08',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Lea Martin','lea@quadrantalpha.com','lea@quadrantalpha.com',0),(31,29,'jvsanjuan','jvsanjuan',1,'ah1l62l08v4kss404000g0wkggg04kk','kLGDTS5zGIdtKwm5KSzBn87ACJ/bFUKaqgNkPfm3Ceiu5EuQLROrlUqelpMXMDcT4zfOsra1lNgNXOdGa/KBEg==','2015-12-26 00:14:06',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Jovel San Juan','vhel@quadrantalpha.com','vhel@quadrantalpha.com',1),(32,30,'jpguniojr.','jpguniojr.',1,'8bnn00ptnb40kwok8okcgscwogk04s0','mc0JYOT7L1K3gQNv8XOm/vYrtb/9/iibPi8tH+7Ez49CIgcdJM5fGGyvPF1rw2TnFSsP71yV9+5ErFaLaF3qcw==','2016-01-22 16:16:54',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Jacinto Gunio Jr.','jun@quadrantalpha.com','jun@quadrantalpha.com',0),(33,31,'lwenoveno','lwenoveno',1,'dja9ogm0afc48wos0wk8so8wgwog44o','JVXcvzGj4DG99gTxcDwS2oqjgYCril+QnKbt72brqPOz58Mi1MFtIApr460c1kMAxX6L4CYSFwpYXMa2IkqxUQ==','2016-01-21 12:06:11',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Lord Wally Noveno','wally@quadrantalpha.com','wally@quadrantalpha.com',1),(36,34,'atlpaulo','atlpaulo',1,'fjjqtd651egcsg04swg8sskgs8c84wk','rEiU0ibJRgUbxQLzXK0soANUAnbd3+maPI4ZKavUa8xjxBKoCj+wt/IjMB3hecTPFmxZDlIo/g5EKLjgFzOBQg==','2016-01-21 11:10:10',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Aurora Terese Paulo','terese@quadrantalpha.com','terese@quadrantalpha.com',0),(37,35,'kdilaquian','kdilaquian',1,'fbaexf1f02gcgs4gkwwccwc4cg8o4og','KYniKAUmJ6NJH5Faju0fMak2BCT1OkiUoy6IrlQXg+du2SVujh7OSl5YRR/0PC1mRb5L+DT9vYqsmPAWjWE2PQ==','2016-03-15 10:29:35',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Karlo David Laquian','karlo@quadrantalpha.com','karlo@quadrantalpha.com',0),(38,36,'rdmumayan','rdmumayan',1,'a3ytdd9zc8w0gswo0sw80k88ogckg4','1Q0GEmbc2n8iQjlhBmNFahmjGy5lhHiWrQx4+wJLpe0F/CDmkSXRJTVNyy0oOwcu+hQXWNFktz0gG+QDDac17Q==','2016-01-04 10:45:02',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Richard Dale Umayan','richard@quadrantalpha.com','richard@quadrantalpha.com',0),(39,37,'ajbcokehyeng','ajbcokehyeng',1,'t1exgwopp0gkg4kk8ssg88g4ksck0o4','QKi3yYqi1dE70Xqsu5levSJbL2lmQ0UF/s4UNmlJC3P6aW1QE23E2kSEUrY4CVRi7UOKxWiRiLzki5zhzHbyLw==','2015-09-09 15:16:11',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Ashley Jorge Co Kehyeng','ashley@quadrantalpha.com','ashley@quadrantalpha.com',0),(40,38,'dpcokehyeng','dpcokehyeng',1,'lohyn082uj48wcc400s4kwcs40w8wk4','KZBCYbqjuc5WWIJPZLwOqvNd4MvclcIkH9NfqOdaGWsMYy+CeEHPCrofNWPLbMgIQOTA95GJBdM4qMMVMnd8xg==','2015-09-23 10:06:32',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Dexter Co Kehyeng','dexter@quadrantalpha.com','dexter@quadrantalpha.com',1),(42,40,'rspascual','rspascual',1,'halbfdb9w3s404k40g0c0o8osogoog0','QNSHITjLR6ZWWNS7bwesFWWaNQqK2elO9mshSM3HrCpi7byES8bgXnJ4s/asJuU/RQx7jX8y+XgzStxR4u37cA==',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Rommel Pascual','rommel@quadrantalpha.com','rommel@quadrantalpha.com',1),(43,41,'jcsgarcia','jcsgarcia',1,'mp583dn0bbkcsss8osgooo448o04008','vE1cWApV5Hm1C+HvVsPRWM9xR7lIBMFPyfPN1RjfRB2gGKg6FN/dvjzuimTZQxwZKoJUsmBWc+3yDD0a0a/uOQ==',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'June Clyde Garcia','cl@cl','cl@cl',1);
/*!40000 ALTER TABLE `user_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_usergroup`
--

DROP TABLE IF EXISTS `user_usergroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_usergroup` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `IDX_4A84F5F3A76ED395` (`user_id`),
  KEY `IDX_4A84F5F3FE54D947` (`group_id`),
  CONSTRAINT `FK_4A84F5F3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user_user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_4A84F5F3FE54D947` FOREIGN KEY (`group_id`) REFERENCES `user_group` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_usergroup`
--

LOCK TABLES `user_usergroup` WRITE;
/*!40000 ALTER TABLE `user_usergroup` DISABLE KEYS */;
INSERT INTO `user_usergroup` VALUES (2,1),(30,1),(30,2),(30,3),(31,2),(31,3),(32,2),(32,3),(33,2),(33,3),(36,2),(37,2),(38,2),(38,8),(39,1),(39,2),(39,3),(39,4),(40,1),(40,2),(40,3),(40,4),(42,2),(43,2);
/*!40000 ALTER TABLE `user_usergroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `world_location`
--

DROP TABLE IF EXISTS `world_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `world_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_type` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `is_visible` int(11) NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6178 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `world_location`
--

LOCK TABLES `world_location` WRITE;
/*!40000 ALTER TABLE `world_location` DISABLE KEYS */;
INSERT INTO `world_location` VALUES (1,'0',0,0,'Aruba'),(2,'0',0,0,'Afghanistan'),(3,'0',0,0,'Angola'),(4,'0',0,0,'Anguilla'),(5,'0',0,0,'Albania'),(6,'0',0,0,'Andorra'),(7,'0',0,0,'Netherlands Antilles'),(8,'0',0,0,'United Arab Emirates'),(9,'0',0,0,'Argentina'),(10,'0',0,0,'Armenia'),(11,'0',0,0,'American Samoa'),(12,'0',0,0,'Antarctica'),(13,'0',0,0,'French Southern territories'),(14,'0',0,0,'Antigua and Barbuda'),(15,'0',0,0,'Australia'),(16,'0',0,0,'Austria'),(17,'0',0,0,'Azerbaijan'),(18,'0',0,0,'Burundi'),(19,'0',0,0,'Belgium'),(20,'0',0,0,'Benin'),(21,'0',0,0,'Burkina Faso'),(22,'0',0,0,'Bangladesh'),(23,'0',0,0,'Bulgaria'),(24,'0',0,0,'Bahrain'),(25,'0',0,0,'Bahamas'),(26,'0',0,0,'Bosnia and Herzegovina'),(27,'0',0,0,'Belarus'),(28,'0',0,0,'Belize'),(29,'0',0,0,'Bermuda'),(30,'0',0,0,'Bolivia'),(31,'0',0,0,'Brazil'),(32,'0',0,0,'Barbados'),(33,'0',0,0,'Brunei'),(34,'0',0,0,'Bhutan'),(35,'0',0,0,'Bouvet Island'),(36,'0',0,0,'Botswana'),(37,'0',0,0,'Central African Republic'),(38,'0',0,0,'Canada'),(39,'0',0,0,'Cocos (Keeling) Islands'),(40,'0',0,0,'Switzerland'),(41,'0',0,0,'Chile'),(42,'0',0,0,'China'),(43,'0',0,0,'CÃ´te dâ€™Ivoire'),(44,'0',0,0,'Cameroon'),(45,'0',0,0,'Congo, The Democratic Republic'),(46,'0',0,0,'Congo'),(47,'0',0,0,'Cook Islands'),(48,'0',0,0,'Colombia'),(49,'0',0,0,'Comoros'),(50,'0',0,0,'Cape Verde'),(51,'0',0,0,'Costa Rica'),(52,'0',0,0,'Cuba'),(53,'0',0,0,'Christmas Island'),(54,'0',0,0,'Cayman Islands'),(55,'0',0,0,'Cyprus'),(56,'0',0,0,'Czech Republic'),(57,'0',0,0,'Germany'),(58,'0',0,0,'Djibouti'),(59,'0',0,0,'Dominica'),(60,'0',0,0,'Denmark'),(61,'0',0,0,'Dominican Republic'),(62,'0',0,0,'Algeria'),(63,'0',0,0,'Ecuador'),(64,'0',0,0,'Egypt'),(65,'0',0,0,'Eritrea'),(66,'0',0,0,'Western Sahara'),(67,'0',0,0,'Spain'),(68,'0',0,0,'Estonia'),(69,'0',0,0,'Ethiopia'),(70,'0',0,0,'Finland'),(71,'0',0,0,'Fiji Islands'),(72,'0',0,0,'Falkland Islands'),(73,'0',0,0,'France'),(74,'0',0,0,'Faroe Islands'),(75,'0',0,0,'Micronesia, Federated States o'),(76,'0',0,0,'Gabon'),(77,'0',0,0,'United Kingdom'),(78,'0',0,0,'Georgia'),(79,'0',0,0,'Ghana'),(80,'0',0,0,'Gibraltar'),(81,'0',0,0,'Guinea'),(82,'0',0,0,'Guadeloupe'),(83,'0',0,0,'Gambia'),(84,'0',0,0,'Guinea-Bissau'),(85,'0',0,0,'Equatorial Guinea'),(86,'0',0,0,'Greece'),(87,'0',0,0,'Grenada'),(88,'0',0,0,'Greenland'),(89,'0',0,0,'Guatemala'),(90,'0',0,0,'French Guiana'),(91,'0',0,0,'Guam'),(92,'0',0,0,'Guyana'),(93,'0',0,0,'Hong Kong'),(94,'0',0,0,'Heard Island and McDonald Isla'),(95,'0',0,0,'Honduras'),(96,'0',0,0,'Croatia'),(97,'0',0,0,'Haiti'),(98,'0',0,0,'Hungary'),(99,'0',0,0,'Indonesia'),(100,'0',0,0,'India'),(101,'0',0,0,'British Indian Ocean Territory'),(102,'0',0,0,'Ireland'),(103,'0',0,0,'Iran'),(104,'0',0,0,'Iraq'),(105,'0',0,0,'Iceland'),(106,'0',0,0,'Israel'),(107,'0',0,0,'Italy'),(108,'0',0,0,'Jamaica'),(109,'0',0,0,'Jordan'),(110,'0',0,0,'Japan'),(111,'0',0,0,'Kazakstan'),(112,'0',0,0,'Kenya'),(113,'0',0,0,'Kyrgyzstan'),(114,'0',0,0,'Cambodia'),(115,'0',0,0,'Kiribati'),(116,'0',0,0,'Saint Kitts and Nevis'),(117,'0',0,0,'South Korea'),(118,'0',0,0,'Kuwait'),(119,'0',0,0,'Laos'),(120,'0',0,0,'Lebanon'),(121,'0',0,0,'Liberia'),(122,'0',0,0,'Libyan Arab Jamahiriya'),(123,'0',0,0,'Saint Lucia'),(124,'0',0,0,'Liechtenstein'),(125,'0',0,0,'Sri Lanka'),(126,'0',0,0,'Lesotho'),(127,'0',0,0,'Lithuania'),(128,'0',0,0,'Luxembourg'),(129,'0',0,0,'Latvia'),(130,'0',0,0,'Macao'),(131,'0',0,0,'Morocco'),(132,'0',0,0,'Monaco'),(133,'0',0,0,'Moldova'),(134,'0',0,0,'Madagascar'),(135,'0',0,0,'Maldives'),(136,'0',0,0,'Mexico'),(137,'0',0,0,'Marshall Islands'),(138,'0',0,0,'Macedonia'),(139,'0',0,0,'Mali'),(140,'0',0,0,'Malta'),(141,'0',0,0,'Myanmar'),(142,'0',0,0,'Mongolia'),(143,'0',0,0,'Northern Mariana Islands'),(144,'0',0,0,'Mozambique'),(145,'0',0,0,'Mauritania'),(146,'0',0,0,'Montserrat'),(147,'0',0,0,'Martinique'),(148,'0',0,0,'Mauritius'),(149,'0',0,0,'Malawi'),(150,'0',0,0,'Malaysia'),(151,'0',0,0,'Mayotte'),(152,'0',0,0,'Namibia'),(153,'0',0,0,'New Caledonia'),(154,'0',0,0,'Niger'),(155,'0',0,0,'Norfolk Island'),(156,'0',0,0,'Nigeria'),(157,'0',0,0,'Nicaragua'),(158,'0',0,0,'Niue'),(159,'0',0,0,'Netherlands'),(160,'0',0,0,'Norway'),(161,'0',0,0,'Nepal'),(162,'0',0,0,'Nauru'),(163,'0',0,0,'New Zealand'),(164,'0',0,0,'Oman'),(165,'0',0,0,'Pakistan'),(166,'0',0,0,'Panama'),(167,'0',0,0,'Pitcairn'),(168,'0',0,0,'Peru'),(169,'0',0,0,'Philippines'),(170,'0',0,0,'Palau'),(171,'0',0,0,'Papua New Guinea'),(172,'0',0,0,'Poland'),(173,'0',0,0,'Puerto Rico'),(174,'0',0,0,'North Korea'),(175,'0',0,0,'Portugal'),(176,'0',0,0,'Paraguay'),(177,'0',0,0,'Palestine'),(178,'0',0,0,'French Polynesia'),(179,'0',0,0,'Qatar'),(180,'0',0,0,'RÃ©union'),(181,'0',0,0,'Romania'),(182,'0',0,0,'Russian Federation'),(183,'0',0,0,'Rwanda'),(184,'0',0,0,'Saudi Arabia'),(185,'0',0,0,'Sudan'),(186,'0',0,0,'Senegal'),(187,'0',0,0,'Singapore'),(188,'0',0,0,'South Georgia and the South Sa'),(189,'0',0,0,'Saint Helena'),(190,'0',0,0,'Svalbard and Jan Mayen'),(191,'0',0,0,'Solomon Islands'),(192,'0',0,0,'Sierra Leone'),(193,'0',0,0,'El Salvador'),(194,'0',0,0,'San Marino'),(195,'0',0,0,'Somalia'),(196,'0',0,0,'Saint Pierre and Miquelon'),(197,'0',0,0,'Sao Tome and Principe'),(198,'0',0,0,'Suriname'),(199,'0',0,0,'Slovakia'),(200,'0',0,0,'Slovenia'),(201,'0',0,0,'Sweden'),(202,'0',0,0,'Swaziland'),(203,'0',0,0,'Seychelles'),(204,'0',0,0,'Syria'),(205,'0',0,0,'Turks and Caicos Islands'),(206,'0',0,0,'Chad'),(207,'0',0,0,'Togo'),(208,'0',0,0,'Thailand'),(209,'0',0,0,'Tajikistan'),(210,'0',0,0,'Tokelau'),(211,'0',0,0,'Turkmenistan'),(212,'0',0,0,'East Timor'),(213,'0',0,0,'Tonga'),(214,'0',0,0,'Trinidad and Tobago'),(215,'0',0,0,'Tunisia'),(216,'0',0,0,'Turkey'),(217,'0',0,0,'Tuvalu'),(218,'0',0,0,'Taiwan'),(219,'0',0,0,'Tanzania'),(220,'0',0,0,'Uganda'),(221,'0',0,0,'Ukraine'),(222,'0',0,0,'United States Minor Outlying I'),(223,'0',0,0,'Uruguay'),(224,'0',0,0,'United States'),(225,'0',0,0,'Uzbekistan'),(226,'0',0,0,'Holy See (Vatican City State)'),(227,'0',0,0,'Saint Vincent and the Grenadin'),(228,'0',0,0,'Venezuela'),(229,'0',0,0,'Virgin Islands, British'),(230,'0',0,0,'Virgin Islands, U.S.'),(231,'0',0,0,'Vietnam'),(232,'0',0,0,'Vanuatu'),(233,'0',0,0,'Wallis and Futuna'),(234,'0',0,0,'Samoa'),(235,'0',0,0,'Yemen'),(236,'0',0,0,'Yugoslavia'),(237,'0',0,0,'South Africa'),(238,'0',0,0,'Zambia'),(239,'0',0,0,'Zimbabwe'),(240,'1',1,0,'â€“'),(241,'1',2,0,'Balkh'),(242,'1',2,0,'Herat'),(243,'1',2,0,'Kabol'),(244,'1',2,0,'Qandahar'),(245,'1',3,0,'Benguela'),(246,'1',3,0,'Huambo'),(247,'1',3,0,'Luanda'),(248,'1',3,0,'Namibe'),(249,'1',4,0,'â€“'),(250,'1',5,0,'Tirana'),(251,'1',6,0,'Andorra la Vella'),(252,'1',7,0,'CuraÃ§ao'),(253,'1',8,0,'Abu Dhabi'),(254,'1',8,0,'Ajman'),(255,'1',8,0,'Dubai'),(256,'1',8,0,'Sharja'),(257,'1',9,0,'Buenos Aires'),(258,'1',9,0,'Catamarca'),(259,'1',9,0,'CÃ³rdoba'),(260,'1',9,0,'Chaco'),(261,'1',9,0,'Chubut'),(262,'1',9,0,'Corrientes'),(263,'1',9,0,'Distrito Federal'),(264,'1',9,0,'Entre Rios'),(265,'1',9,0,'Formosa'),(266,'1',9,0,'Jujuy'),(267,'1',9,0,'La Rioja'),(268,'1',9,0,'Mendoza'),(269,'1',9,0,'Misiones'),(270,'1',9,0,'NeuquÃ©n'),(271,'1',9,0,'Salta'),(272,'1',9,0,'San Juan'),(273,'1',9,0,'San Luis'),(274,'1',9,0,'Santa FÃ©'),(275,'1',9,0,'Santiago del Estero'),(276,'1',9,0,'TucumÃ¡n'),(277,'1',10,0,'Lori'),(278,'1',10,0,'Yerevan'),(279,'1',10,0,'Å irak'),(280,'1',11,0,'Tutuila'),(281,'1',14,0,'St John'),(282,'1',15,0,'Capital Region'),(283,'1',15,0,'New South Wales'),(284,'1',15,0,'Queensland'),(285,'1',15,0,'South Australia'),(286,'1',15,0,'Tasmania'),(287,'1',15,0,'Victoria'),(288,'1',15,0,'West Australia'),(289,'1',16,0,'KÃ¤rnten'),(290,'1',16,0,'North Austria'),(291,'1',16,0,'Salzburg'),(292,'1',16,0,'Steiermark'),(293,'1',16,0,'Tiroli'),(294,'1',16,0,'Wien'),(295,'1',17,0,'Baki'),(296,'1',17,0,'GÃ¤ncÃ¤'),(297,'1',17,0,'MingÃ¤Ã§evir'),(298,'1',17,0,'Sumqayit'),(299,'1',18,0,'Bujumbura'),(300,'1',19,0,'Antwerpen'),(301,'1',19,0,'Bryssel'),(302,'1',19,0,'East Flanderi'),(303,'1',19,0,'Hainaut'),(304,'1',19,0,'LiÃ¨ge'),(305,'1',19,0,'Namur'),(306,'1',19,0,'West Flanderi'),(307,'1',20,0,'Atacora'),(308,'1',20,0,'Atlantique'),(309,'1',20,0,'Borgou'),(310,'1',20,0,'OuÃ©mÃ©'),(311,'1',21,0,'BoulkiemdÃ©'),(312,'1',21,0,'Houet'),(313,'1',21,0,'Kadiogo'),(314,'1',22,0,'Barisal'),(315,'1',22,0,'Chittagong'),(316,'1',22,0,'Dhaka'),(317,'1',22,0,'Khulna'),(318,'1',22,0,'Rajshahi'),(319,'1',22,0,'Sylhet'),(320,'1',23,0,'Burgas'),(321,'1',23,0,'Grad Sofija'),(322,'1',23,0,'Haskovo'),(323,'1',23,0,'Lovec'),(324,'1',23,0,'Plovdiv'),(325,'1',23,0,'Ruse'),(326,'1',23,0,'Varna'),(327,'1',24,0,'al-Manama'),(328,'1',25,0,'New Providence'),(329,'1',26,0,'Federaatio'),(330,'1',26,0,'Republika Srpska'),(331,'1',27,0,'Brest'),(332,'1',27,0,'Gomel'),(333,'1',27,0,'Grodno'),(334,'1',27,0,'Horad Minsk'),(335,'1',27,0,'Minsk'),(336,'1',27,0,'Mogiljov'),(337,'1',27,0,'Vitebsk'),(338,'1',28,0,'Belize City'),(339,'1',28,0,'Cayo'),(340,'1',29,0,'Hamilton'),(341,'1',29,0,'Saint GeorgeÂ´s'),(342,'1',30,0,'Chuquisaca'),(343,'1',30,0,'Cochabamba'),(344,'1',30,0,'La Paz'),(345,'1',30,0,'Oruro'),(346,'1',30,0,'PotosÃ­'),(347,'1',30,0,'Santa Cruz'),(348,'1',30,0,'Tarija'),(349,'1',31,0,'Acre'),(350,'1',31,0,'Alagoas'),(351,'1',31,0,'AmapÃ¡'),(352,'1',31,0,'Amazonas'),(353,'1',31,0,'Bahia'),(354,'1',31,0,'CearÃ¡'),(355,'1',31,0,'Distrito Federal'),(356,'1',31,0,'EspÃ­rito Santo'),(357,'1',31,0,'GoiÃ¡s'),(358,'1',31,0,'MaranhÃ£o'),(359,'1',31,0,'Mato Grosso'),(360,'1',31,0,'Mato Grosso do Sul'),(361,'1',31,0,'Minas Gerais'),(362,'1',31,0,'ParaÃ­ba'),(363,'1',31,0,'ParanÃ¡'),(364,'1',31,0,'ParÃ¡'),(365,'1',31,0,'Pernambuco'),(366,'1',31,0,'PiauÃ­'),(367,'1',31,0,'Rio de Janeiro'),(368,'1',31,0,'Rio Grande do Norte'),(369,'1',31,0,'Rio Grande do Sul'),(370,'1',31,0,'RondÃ´nia'),(371,'1',31,0,'Roraima'),(372,'1',31,0,'Santa Catarina'),(373,'1',31,0,'SÃ£o Paulo'),(374,'1',31,0,'Sergipe'),(375,'1',31,0,'Tocantins'),(376,'1',32,0,'St Michael'),(377,'1',33,0,'Brunei and Muara'),(378,'1',34,0,'Thimphu'),(379,'1',36,0,'Francistown'),(380,'1',36,0,'Gaborone'),(381,'1',37,0,'Bangui'),(382,'1',38,0,'Alberta'),(383,'1',38,0,'British Colombia'),(384,'1',38,0,'Manitoba'),(385,'1',38,0,'Newfoundland'),(386,'1',38,0,'Nova Scotia'),(387,'1',38,0,'Ontario'),(388,'1',38,0,'QuÃ©bec'),(389,'1',38,0,'Saskatchewan'),(390,'1',39,0,'Home Island'),(391,'1',39,0,'West Island'),(392,'1',40,0,'Basel-Stadt'),(393,'1',40,0,'Bern'),(394,'1',40,0,'Geneve'),(395,'1',40,0,'Vaud'),(396,'1',40,0,'ZÃ¼rich'),(397,'1',41,0,'Antofagasta'),(398,'1',41,0,'Atacama'),(399,'1',41,0,'BÃ­obÃ­o'),(400,'1',41,0,'Coquimbo'),(401,'1',41,0,'La AraucanÃ­a'),(402,'1',41,0,'Los Lagos'),(403,'1',41,0,'Magallanes'),(404,'1',41,0,'Maule'),(405,'1',41,0,'OÂ´Higgins'),(406,'1',41,0,'Santiago'),(407,'1',41,0,'TarapacÃ¡'),(408,'1',41,0,'ValparaÃ­so'),(409,'1',42,0,'Anhui'),(410,'1',42,0,'Chongqing'),(411,'1',42,0,'Fujian'),(412,'1',42,0,'Gansu'),(413,'1',42,0,'Guangdong'),(414,'1',42,0,'Guangxi'),(415,'1',42,0,'Guizhou'),(416,'1',42,0,'Hainan'),(417,'1',42,0,'Hebei'),(418,'1',42,0,'Heilongjiang'),(419,'1',42,0,'Henan'),(420,'1',42,0,'Hubei'),(421,'1',42,0,'Hunan'),(422,'1',42,0,'Inner Mongolia'),(423,'1',42,0,'Jiangsu'),(424,'1',42,0,'Jiangxi'),(425,'1',42,0,'Jilin'),(426,'1',42,0,'Liaoning'),(427,'1',42,0,'Ningxia'),(428,'1',42,0,'Peking'),(429,'1',42,0,'Qinghai'),(430,'1',42,0,'Shaanxi'),(431,'1',42,0,'Shandong'),(432,'1',42,0,'Shanghai'),(433,'1',42,0,'Shanxi'),(434,'1',42,0,'Sichuan'),(435,'1',42,0,'Tianjin'),(436,'1',42,0,'Tibet'),(437,'1',42,0,'Xinxiang'),(438,'1',42,0,'Yunnan'),(439,'1',42,0,'Zhejiang'),(440,'1',43,0,'Abidjan'),(441,'1',43,0,'BouakÃ©'),(442,'1',43,0,'Daloa'),(443,'1',43,0,'Korhogo'),(444,'1',43,0,'Yamoussoukro'),(445,'1',44,0,'Centre'),(446,'1',44,0,'ExtrÃªme-Nord'),(447,'1',44,0,'Littoral'),(448,'1',44,0,'Nord'),(449,'1',44,0,'Nord-Ouest'),(450,'1',44,0,'Ouest'),(451,'1',0,0,'Bandundu'),(452,'1',0,0,'Bas-ZaÃ¯re'),(453,'1',0,0,'East Kasai'),(454,'1',0,0,'Equateur'),(455,'1',0,0,'Haute-ZaÃ¯re'),(456,'1',0,0,'Kinshasa'),(457,'1',0,0,'North Kivu'),(458,'1',0,0,'Shaba'),(459,'1',0,0,'South Kivu'),(460,'1',0,0,'West Kasai'),(461,'1',46,0,'Brazzaville'),(462,'1',46,0,'Kouilou'),(463,'1',47,0,'Rarotonga'),(464,'1',48,0,'Antioquia'),(465,'1',48,0,'AtlÃ¡ntico'),(466,'1',48,0,'BolÃ­var'),(467,'1',48,0,'BoyacÃ¡'),(468,'1',48,0,'Caldas'),(469,'1',48,0,'CaquetÃ¡'),(470,'1',48,0,'Cauca'),(471,'1',48,0,'CÃ³rdoba'),(472,'1',48,0,'Cesar'),(473,'1',48,0,'Cundinamarca'),(474,'1',48,0,'Huila'),(475,'1',48,0,'La Guajira'),(476,'1',48,0,'Magdalena'),(477,'1',48,0,'Meta'),(478,'1',48,0,'NariÃ±o'),(479,'1',48,0,'Norte de Santander'),(480,'1',48,0,'QuindÃ­o'),(481,'1',48,0,'Risaralda'),(482,'1',48,0,'SantafÃ© de BogotÃ¡'),(483,'1',48,0,'Santander'),(484,'1',48,0,'Sucre'),(485,'1',48,0,'Tolima'),(486,'1',48,0,'Valle'),(487,'1',49,0,'Njazidja'),(488,'1',50,0,'SÃ£o Tiago'),(489,'1',51,0,'San JosÃ©'),(490,'1',52,0,'CamagÃ¼ey'),(491,'1',52,0,'Ciego de Ãvila'),(492,'1',52,0,'Cienfuegos'),(493,'1',52,0,'Granma'),(494,'1',52,0,'GuantÃ¡namo'),(495,'1',52,0,'HolguÃ­n'),(496,'1',52,0,'La Habana'),(497,'1',52,0,'Las Tunas'),(498,'1',52,0,'Matanzas'),(499,'1',52,0,'Pinar del RÃ­o'),(500,'1',52,0,'Sancti-SpÃ­ritus'),(501,'1',52,0,'Santiago de Cuba'),(502,'1',52,0,'Villa Clara'),(503,'1',53,0,'â€“'),(504,'1',54,0,'Grand Cayman'),(505,'1',55,0,'Limassol'),(506,'1',55,0,'Nicosia'),(507,'1',56,0,'HlavnÃ­ mesto Praha'),(508,'1',56,0,'JiznÃ­ Cechy'),(509,'1',56,0,'JiznÃ­ Morava'),(510,'1',56,0,'SevernÃ­ Cechy'),(511,'1',56,0,'SevernÃ­ Morava'),(512,'1',56,0,'VÃ½chodnÃ­ Cechy'),(513,'1',56,0,'ZapadnÃ­ Cechy'),(514,'1',57,0,'Anhalt Sachsen'),(515,'1',57,0,'Baden-Württemberg'),(516,'1',57,0,'Baijeri'),(517,'1',57,0,'Berliini'),(518,'1',57,0,'Brandenburg'),(519,'1',57,0,'Bremen'),(520,'1',57,0,'Hamburg'),(521,'1',57,0,'Hessen'),(522,'1',57,0,'Mecklenburg-Vorpomme'),(523,'1',57,0,'Niedersachsen'),(524,'1',57,0,'Nordrhein-Westfalen'),(525,'1',57,0,'Rheinland-Pfalz'),(526,'1',57,0,'Saarland'),(527,'1',57,0,'Saksi'),(528,'1',57,0,'Schleswig-Holstein'),(529,'1',57,0,'Thuringia'),(530,'1',58,0,'Djibouti'),(531,'1',59,0,'St George'),(532,'1',60,0,'Ã…rhus'),(533,'1',60,0,'Frederiksberg'),(534,'1',60,0,'Fyn'),(535,'1',60,0,'KÃ¸benhavn'),(536,'1',60,0,'Nordjylland'),(537,'1',61,0,'Distrito Nacional'),(538,'1',61,0,'Duarte'),(539,'1',61,0,'La Romana'),(540,'1',61,0,'Puerto Plata'),(541,'1',61,0,'San Pedro de MacorÃ­'),(542,'1',61,0,'Santiago'),(543,'1',62,0,'Alger'),(544,'1',62,0,'Annaba'),(545,'1',62,0,'Batna'),(546,'1',62,0,'Bãchar'),(547,'1',62,0,'Béjaïa'),(548,'1',62,0,'Biskra'),(549,'1',62,0,'Blida'),(550,'1',62,0,'Chlef'),(551,'1',62,0,'Constantine'),(552,'1',62,0,'GhardaÃ¯a'),(553,'1',62,0,'Mostaganem'),(554,'1',62,0,'Oran'),(555,'1',62,0,'Sétif'),(556,'1',62,0,'Sidi Bel AbbÃ¨s'),(557,'1',62,0,'Skikda'),(558,'1',62,0,'TÃbessa'),(559,'1',62,0,'Tiaret'),(560,'1',62,0,'Tlemcen'),(561,'1',63,0,'Azuay'),(562,'1',63,0,'Chimborazo'),(563,'1',63,0,'El Oro'),(564,'1',63,0,'Esmeraldas'),(565,'1',63,0,'Guayas'),(566,'1',63,0,'Imbabura'),(567,'1',63,0,'Loja'),(568,'1',63,0,'Los RÃ­os'),(569,'1',63,0,'ManabÃ­'),(570,'1',63,0,'Pichincha'),(571,'1',63,0,'Tungurahua'),(572,'1',64,0,'al-Buhayra'),(573,'1',64,0,'al-Daqahliya'),(574,'1',64,0,'al-Faiyum'),(575,'1',64,0,'al-Gharbiya'),(576,'1',64,0,'al-Minufiya'),(577,'1',64,0,'al-Minya'),(578,'1',64,0,'al-Qalyubiya'),(579,'1',64,0,'al-Sharqiya'),(580,'1',64,0,'Aleksandria'),(581,'1',64,0,'Assuan'),(582,'1',64,0,'Asyut'),(583,'1',64,0,'Bani Suwayf'),(584,'1',64,0,'Giza'),(585,'1',64,0,'Ismailia'),(586,'1',64,0,'Kafr al-Shaykh'),(587,'1',64,0,'Kairo'),(588,'1',64,0,'Luxor'),(589,'1',64,0,'Port Said'),(590,'1',64,0,'Qina'),(591,'1',64,0,'Sawhaj'),(592,'1',64,0,'Shamal Sina'),(593,'1',64,0,'Suez'),(594,'1',65,0,'Maekel'),(595,'1',66,0,'El-AaiÃºn'),(596,'1',67,0,'Andalusia'),(597,'1',67,0,'Aragonia'),(598,'1',67,0,'Asturia'),(599,'1',67,0,'Balears'),(600,'1',67,0,'Baskimaa'),(601,'1',67,0,'Canary Islands'),(602,'1',67,0,'Cantabria'),(603,'1',67,0,'Castile and León'),(604,'1',67,0,'Extremadura'),(605,'1',67,0,'Galicia'),(606,'1',67,0,'Kastilia-La Mancha'),(607,'1',67,0,'Katalonia'),(608,'1',67,0,'La Rioja'),(609,'1',67,0,'Madrid'),(610,'1',67,0,'Murcia'),(611,'1',67,0,'Navarra'),(612,'1',67,0,'Valencia'),(613,'1',68,0,'Harjumaa'),(614,'1',68,0,'Tartumaa'),(615,'1',69,0,'Addis Abeba'),(616,'1',69,0,'Amhara'),(617,'1',69,0,'Dire Dawa'),(618,'1',69,0,'Oromia'),(619,'1',69,0,'Tigray'),(620,'1',70,0,'Newmaa'),(621,'1',70,0,'PÃ¤ijÃ¤t-HÃ¤me'),(622,'1',70,0,'Pirkanmaa'),(623,'1',70,0,'Pohjois-Pohjanmaa'),(624,'1',70,0,'Varsinais-Suomi'),(625,'1',71,0,'Central'),(626,'1',72,0,'East Falkland'),(627,'1',73,0,'Alsace'),(628,'1',73,0,'Aquitaine'),(629,'1',73,0,'Auvergne'),(630,'1',73,0,'Île-de-France'),(631,'1',73,0,'Basse-Normandie'),(632,'1',73,0,'Bourgogne'),(633,'1',73,0,'Bretagne'),(634,'1',73,0,'Centre'),(635,'1',73,0,'Champagne-Ardenne'),(636,'1',73,0,'Franche-ComtÃ©'),(637,'1',73,0,'Haute-Normandie'),(638,'1',73,0,'Languedoc-Roussillon'),(639,'1',73,0,'Limousin'),(640,'1',73,0,'Lorraine'),(641,'1',73,0,'Midi-Pyrénées'),(642,'1',73,0,'Nord-Pas-de-Calais'),(643,'1',73,0,'Pays de la Loire'),(644,'1',73,0,'Picardie'),(645,'1',73,0,'Provence-Alpes-Côte d\'Azur'),(646,'1',73,0,'Rhône-Alpes'),(647,'1',74,0,'Streymoyar'),(648,'1',0,0,'Chuuk'),(649,'1',0,0,'Pohnpei'),(650,'1',76,0,'Estuaire'),(651,'1',77,0,'â€“'),(652,'1',77,0,'England'),(653,'1',77,0,'Jersey'),(654,'1',77,0,'North Ireland'),(655,'1',77,0,'Scotland'),(656,'1',77,0,'Wales'),(657,'1',78,0,'Abhasia [Aphazeti]'),(658,'1',78,0,'Adzaria [AtÅ¡ara]'),(659,'1',78,0,'Imereti'),(660,'1',78,0,'Kvemo Kartli'),(661,'1',78,0,'Tbilisi'),(662,'1',79,0,'Ashanti'),(663,'1',79,0,'Greater Accra'),(664,'1',79,0,'Northern'),(665,'1',79,0,'Western'),(666,'1',80,0,'â€“'),(667,'1',81,0,'Conakry'),(668,'1',82,0,'Basse-Terre'),(669,'1',82,0,'Grande-Terre'),(670,'1',83,0,'Banjul'),(671,'1',83,0,'Kombo St Mary'),(672,'1',84,0,'Bissau'),(673,'1',85,0,'Bioko'),(674,'1',86,0,'Attika'),(675,'1',86,0,'Central Macedonia'),(676,'1',86,0,'Crete'),(677,'1',86,0,'Thessalia'),(678,'1',86,0,'West Greece'),(679,'1',87,0,'St George'),(680,'1',88,0,'Kitaa'),(681,'1',89,0,'Guatemala'),(682,'1',89,0,'Quetzaltenango'),(683,'1',90,0,'Cayenne'),(684,'1',91,0,'â€“'),(685,'1',92,0,'Georgetown'),(686,'1',93,0,'Hongkong'),(687,'1',93,0,'Kowloon and New Kowl'),(688,'1',95,0,'AtlÃ¡ntida'),(689,'1',95,0,'CortÃs'),(690,'1',95,0,'Distrito Central'),(691,'1',96,0,'Grad Zagreb'),(692,'1',96,0,'Osijek-Baranja'),(693,'1',96,0,'Primorje-Gorski Kota'),(694,'1',96,0,'Split-Dalmatia'),(695,'1',97,0,'Nord'),(696,'1',97,0,'Ouest'),(697,'1',98,0,'Baranya'),(698,'1',98,0,'BÃ¡cs-Kiskun'),(699,'1',98,0,'Borsod-AbaÃºj-ZemplÃ'),(700,'1',98,0,'Budapest'),(701,'1',98,0,'CsongrÃ¡d'),(702,'1',98,0,'FejÃr'),(703,'1',98,0,'GyÃ¶r-Moson-Sopron'),(704,'1',98,0,'HajdÃº-Bihar'),(705,'1',98,0,'Szabolcs-SzatmÃ¡r-Be'),(706,'1',99,0,'Aceh'),(707,'1',99,0,'Bali'),(708,'1',99,0,'Bengkulu'),(709,'1',99,0,'Central Java'),(710,'1',99,0,'East Java'),(711,'1',99,0,'Jakarta Raya'),(712,'1',99,0,'Jambi'),(713,'1',99,0,'Kalimantan Barat'),(714,'1',99,0,'Kalimantan Selatan'),(715,'1',99,0,'Kalimantan Tengah'),(716,'1',99,0,'Kalimantan Timur'),(717,'1',99,0,'Lampung'),(718,'1',99,0,'Molukit'),(719,'1',99,0,'Nusa Tenggara Barat'),(720,'1',99,0,'Nusa Tenggara Timur'),(721,'1',99,0,'Riau'),(722,'1',99,0,'Sulawesi Selatan'),(723,'1',99,0,'Sulawesi Tengah'),(724,'1',99,0,'Sulawesi Tenggara'),(725,'1',99,0,'Sulawesi Utara'),(726,'1',99,0,'Sumatera Barat'),(727,'1',99,0,'Sumatera Selatan'),(728,'1',99,0,'Sumatera Utara'),(729,'1',99,0,'West Irian'),(730,'1',99,0,'West Java'),(731,'1',99,0,'Yogyakarta'),(732,'1',100,0,'Andhra Pradesh'),(733,'1',100,0,'Assam'),(734,'1',100,0,'Bihar'),(735,'1',100,0,'Chandigarh'),(736,'1',100,0,'Chhatisgarh'),(737,'1',100,0,'Delhi'),(738,'1',100,0,'Gujarat'),(739,'1',100,0,'Haryana'),(740,'1',100,0,'Jammu and Kashmir'),(741,'1',100,0,'Jharkhand'),(742,'1',100,0,'Karnataka'),(743,'1',100,0,'Kerala'),(744,'1',100,0,'Madhya Pradesh'),(745,'1',100,0,'Maharashtra'),(746,'1',100,0,'Manipur'),(747,'1',100,0,'Meghalaya'),(748,'1',100,0,'Mizoram'),(749,'1',100,0,'Orissa'),(750,'1',100,0,'Pondicherry'),(751,'1',100,0,'Punjab'),(752,'1',100,0,'Rajasthan'),(753,'1',100,0,'Tamil Nadu'),(754,'1',100,0,'Tripura'),(755,'1',100,0,'Uttar Pradesh'),(756,'1',100,0,'Uttaranchal'),(757,'1',100,0,'West Bengali'),(758,'1',102,0,'Leinster'),(759,'1',102,0,'Munster'),(760,'1',103,0,'Ardebil'),(761,'1',103,0,'Bushehr'),(762,'1',103,0,'Chaharmahal va Bakht'),(763,'1',103,0,'East Azerbaidzan'),(764,'1',103,0,'Esfahan'),(765,'1',103,0,'Fars'),(766,'1',103,0,'Gilan'),(767,'1',103,0,'Golestan'),(768,'1',103,0,'Hamadan'),(769,'1',103,0,'Hormozgan'),(770,'1',103,0,'Ilam'),(771,'1',103,0,'Kerman'),(772,'1',103,0,'Kermanshah'),(773,'1',103,0,'Khorasan'),(774,'1',103,0,'Khuzestan'),(775,'1',103,0,'Kordestan'),(776,'1',103,0,'Lorestan'),(777,'1',103,0,'Markazi'),(778,'1',103,0,'Mazandaran'),(779,'1',103,0,'Qazvin'),(780,'1',103,0,'Qom'),(781,'1',103,0,'Semnan'),(782,'1',103,0,'Sistan va Baluchesta'),(783,'1',103,0,'Teheran'),(784,'1',103,0,'West Azerbaidzan'),(785,'1',103,0,'Yazd'),(786,'1',103,0,'Zanjan'),(787,'1',104,0,'al-Anbar'),(788,'1',104,0,'al-Najaf'),(789,'1',104,0,'al-Qadisiya'),(790,'1',104,0,'al-Sulaymaniya'),(791,'1',104,0,'al-Tamim'),(792,'1',104,0,'Babil'),(793,'1',104,0,'Baghdad'),(794,'1',104,0,'Basra'),(795,'1',104,0,'DhiQar'),(796,'1',104,0,'Diyala'),(797,'1',104,0,'Irbil'),(798,'1',104,0,'Karbala'),(799,'1',104,0,'Maysan'),(800,'1',104,0,'Ninawa'),(801,'1',104,0,'Wasit'),(802,'1',105,0,'HÃ¶fuÃ°borgarsvÃ¦Ã°i'),(803,'1',106,0,'Ha Darom'),(804,'1',106,0,'Ha Merkaz'),(805,'1',106,0,'Haifa'),(806,'1',106,0,'Jerusalem'),(807,'1',106,0,'Tel Aviv'),(808,'1',107,0,'Abruzzit'),(809,'1',107,0,'Apulia'),(810,'1',107,0,'Calabria'),(811,'1',107,0,'Campania'),(812,'1',107,0,'Emilia-Romagna'),(813,'1',107,0,'Friuli-Venezia Giuli'),(814,'1',107,0,'Latium'),(815,'1',107,0,'Liguria'),(816,'1',107,0,'Lombardia'),(817,'1',107,0,'Marche'),(818,'1',107,0,'Piemonte'),(819,'1',107,0,'Sardinia'),(820,'1',107,0,'Sisilia'),(821,'1',107,0,'Toscana'),(822,'1',107,0,'Trentino-Alto Adige'),(823,'1',107,0,'Umbria'),(824,'1',107,0,'Veneto'),(825,'1',108,0,'St. Andrew'),(826,'1',108,0,'St. Catherine'),(827,'1',109,0,'al-Zarqa'),(828,'1',109,0,'Amman'),(829,'1',109,0,'Irbid'),(830,'1',110,0,'Aichi'),(831,'1',110,0,'Akita'),(832,'1',110,0,'Aomori'),(833,'1',110,0,'Chiba'),(834,'1',110,0,'Ehime'),(835,'1',110,0,'Fukui'),(836,'1',110,0,'Fukuoka'),(837,'1',110,0,'Fukushima'),(838,'1',110,0,'Gifu'),(839,'1',110,0,'Gumma'),(840,'1',110,0,'Hiroshima'),(841,'1',110,0,'Hokkaido'),(842,'1',110,0,'Hyogo'),(843,'1',110,0,'Ibaragi'),(844,'1',110,0,'Ishikawa'),(845,'1',110,0,'Iwate'),(846,'1',110,0,'Kagawa'),(847,'1',110,0,'Kagoshima'),(848,'1',110,0,'Kanagawa'),(849,'1',110,0,'Kochi'),(850,'1',110,0,'Kumamoto'),(851,'1',110,0,'Kyoto'),(852,'1',110,0,'Mie'),(853,'1',110,0,'Miyagi'),(854,'1',110,0,'Miyazaki'),(855,'1',110,0,'Nagano'),(856,'1',110,0,'Nagasaki'),(857,'1',110,0,'Nara'),(858,'1',110,0,'Niigata'),(859,'1',110,0,'Oita'),(860,'1',110,0,'Okayama'),(861,'1',110,0,'Okinawa'),(862,'1',110,0,'Osaka'),(863,'1',110,0,'Saga'),(864,'1',110,0,'Saitama'),(865,'1',110,0,'Shiga'),(866,'1',110,0,'Shimane'),(867,'1',110,0,'Shizuoka'),(868,'1',110,0,'Tochigi'),(869,'1',110,0,'Tokushima'),(870,'1',110,0,'Tokyo-to'),(871,'1',110,0,'Tottori'),(872,'1',110,0,'Toyama'),(873,'1',110,0,'Wakayama'),(874,'1',110,0,'Yamagata'),(875,'1',110,0,'Yamaguchi'),(876,'1',110,0,'Yamanashi'),(877,'1',111,0,'Almaty'),(878,'1',111,0,'Almaty Qalasy'),(879,'1',111,0,'AqtÃ¶be'),(880,'1',111,0,'Astana'),(881,'1',111,0,'Atyrau'),(882,'1',111,0,'East Kazakstan'),(883,'1',111,0,'Mangghystau'),(884,'1',111,0,'North Kazakstan'),(885,'1',111,0,'Pavlodar'),(886,'1',111,0,'Qaraghandy'),(887,'1',111,0,'Qostanay'),(888,'1',111,0,'Qyzylorda'),(889,'1',111,0,'South Kazakstan'),(890,'1',111,0,'Taraz'),(891,'1',111,0,'West Kazakstan'),(892,'1',112,0,'Central'),(893,'1',112,0,'Coast'),(894,'1',112,0,'Eastern'),(895,'1',112,0,'Nairobi'),(896,'1',112,0,'Nyanza'),(897,'1',112,0,'Rift Valley'),(898,'1',113,0,'Bishkek shaary'),(899,'1',113,0,'Osh'),(900,'1',114,0,'Battambang'),(901,'1',114,0,'Phnom Penh'),(902,'1',114,0,'Siem Reap'),(903,'1',115,0,'South Tarawa'),(904,'1',116,0,'St George Basseterre'),(905,'1',117,0,'Cheju'),(906,'1',117,0,'Chollabuk'),(907,'1',117,0,'Chollanam'),(908,'1',117,0,'Chungchongbuk'),(909,'1',117,0,'Chungchongnam'),(910,'1',117,0,'Inchon'),(911,'1',117,0,'Kang-won'),(912,'1',117,0,'Kwangju'),(913,'1',117,0,'Kyonggi'),(914,'1',117,0,'Kyongsangbuk'),(915,'1',117,0,'Kyongsangnam'),(916,'1',117,0,'Pusan'),(917,'1',117,0,'Seoul'),(918,'1',117,0,'Taegu'),(919,'1',117,0,'Taejon'),(920,'1',118,0,'al-Asima'),(921,'1',118,0,'Hawalli'),(922,'1',119,0,'Savannakhet'),(923,'1',119,0,'Viangchan'),(924,'1',120,0,'al-Shamal'),(925,'1',120,0,'Beirut'),(926,'1',121,0,'Montserrado'),(927,'1',122,0,'al-Zawiya'),(928,'1',122,0,'Bengasi'),(929,'1',122,0,'Misrata'),(930,'1',122,0,'Tripoli'),(931,'1',123,0,'Castries'),(932,'1',124,0,'Schaan'),(933,'1',124,0,'Vaduz'),(934,'1',125,0,'Central'),(935,'1',125,0,'Northern'),(936,'1',125,0,'Western'),(937,'1',126,0,'Maseru'),(938,'1',127,0,'Kaunas'),(939,'1',127,0,'Klaipeda'),(940,'1',127,0,'Panevezys'),(941,'1',127,0,'Vilna'),(942,'1',127,0,'Å iauliai'),(943,'1',128,0,'Luxembourg'),(944,'1',129,0,'Daugavpils'),(945,'1',129,0,'Liepaja'),(946,'1',129,0,'Riika'),(947,'1',130,0,'Macau'),(948,'1',131,0,'Casablanca'),(949,'1',131,0,'Chaouia-Ouardigha'),(950,'1',131,0,'Doukkala-Abda'),(951,'1',131,0,'FÃ¨s-Boulemane'),(952,'1',131,0,'Gharb-Chrarda-BÃ©ni'),(953,'1',131,0,'Marrakech-Tensift-Al'),(954,'1',131,0,'MeknÃ¨s-Tafilalet'),(955,'1',131,0,'Oriental'),(956,'1',131,0,'Rabat-SalÃ©-Zammour-'),(957,'1',131,0,'Souss Massa-DraÃ¢'),(958,'1',131,0,'Tadla-Azilal'),(959,'1',131,0,'Tanger-TÃ©touan'),(960,'1',131,0,'Taza-Al Hoceima-Taou'),(961,'1',132,0,'â€“'),(962,'1',133,0,'Balti'),(963,'1',133,0,'Bender (TÃ®ghina)'),(964,'1',133,0,'Chisinau'),(965,'1',133,0,'Dnjestria'),(966,'1',134,0,'Antananarivo'),(967,'1',134,0,'Fianarantsoa'),(968,'1',134,0,'Mahajanga'),(969,'1',134,0,'Toamasina'),(970,'1',135,0,'Maale'),(971,'1',136,0,'Aguascalientes'),(972,'1',136,0,'Baja California'),(973,'1',136,0,'Baja California Sur'),(974,'1',136,0,'Campeche'),(975,'1',136,0,'Chiapas'),(976,'1',136,0,'Chihuahua'),(977,'1',136,0,'Coahuila de Zaragoza'),(978,'1',136,0,'Colima'),(979,'1',136,0,'Distrito Federal'),(980,'1',136,0,'Durango'),(981,'1',136,0,'Guanajuato'),(982,'1',136,0,'Guerrero'),(983,'1',136,0,'Hidalgo'),(984,'1',136,0,'Jalisco'),(985,'1',136,0,'MÃ©xico'),(986,'1',136,0,'MichoacÃ¡n de Ocampo'),(987,'1',136,0,'Morelos'),(988,'1',136,0,'Nayarit'),(989,'1',136,0,'Nuevo LeÃ³n'),(990,'1',136,0,'Oaxaca'),(991,'1',136,0,'Puebla'),(992,'1',136,0,'QuerÃ©taro'),(993,'1',136,0,'QuerÃ©taro de Arteag'),(994,'1',136,0,'Quintana Roo'),(995,'1',136,0,'San Luis PotosÃ­'),(996,'1',136,0,'Sinaloa'),(997,'1',136,0,'Sonora'),(998,'1',136,0,'Tabasco'),(999,'1',136,0,'Tamaulipas'),(1000,'1',136,0,'Veracruz'),(1001,'1',136,0,'Veracruz-Llave'),(1002,'1',136,0,'YucatÃ¡n'),(1003,'1',136,0,'Zacatecas'),(1004,'1',137,0,'Majuro'),(1005,'1',138,0,'Skopje'),(1006,'1',139,0,'Bamako'),(1007,'1',140,0,'Inner Harbour'),(1008,'1',140,0,'Outer Harbour'),(1009,'1',141,0,'Irrawaddy [Ayeyarwad'),(1010,'1',141,0,'Magwe [Magway]'),(1011,'1',141,0,'Mandalay'),(1012,'1',141,0,'Mon'),(1013,'1',141,0,'Pegu [Bago]'),(1014,'1',141,0,'Rakhine'),(1015,'1',141,0,'Rangoon [Yangon]'),(1016,'1',141,0,'Sagaing'),(1017,'1',141,0,'Shan'),(1018,'1',141,0,'Tenasserim [Tanintha'),(1019,'1',142,0,'Ulaanbaatar'),(1020,'1',143,0,'Saipan'),(1021,'1',144,0,'Gaza'),(1022,'1',144,0,'Inhambane'),(1023,'1',144,0,'Manica'),(1024,'1',144,0,'Maputo'),(1025,'1',144,0,'Nampula'),(1026,'1',144,0,'Sofala'),(1027,'1',144,0,'Tete'),(1028,'1',144,0,'ZambÃ©zia'),(1029,'1',145,0,'Dakhlet NouÃ¢dhibou'),(1030,'1',145,0,'Nouakchott'),(1031,'1',146,0,'Plymouth'),(1032,'1',147,0,'Fort-de-France'),(1033,'1',148,0,'Plaines Wilhelms'),(1034,'1',148,0,'Port-Louis'),(1035,'1',149,0,'Blantyre'),(1036,'1',149,0,'Lilongwe'),(1037,'1',150,0,'Johor'),(1038,'1',150,0,'Kedah'),(1039,'1',150,0,'Kelantan'),(1040,'1',150,0,'Negeri Sembilan'),(1041,'1',150,0,'Pahang'),(1042,'1',150,0,'Perak'),(1043,'1',150,0,'Pulau Pinang'),(1044,'1',150,0,'Sabah'),(1045,'1',150,0,'Sarawak'),(1046,'1',150,0,'Selangor'),(1047,'1',150,0,'Terengganu'),(1048,'1',150,0,'Wilayah Persekutuan'),(1049,'1',151,0,'Mamoutzou'),(1050,'1',152,0,'Khomas'),(1051,'1',153,0,'â€“'),(1052,'1',154,0,'Maradi'),(1053,'1',154,0,'Niamey'),(1054,'1',154,0,'Zinder'),(1055,'1',155,0,'â€“'),(1056,'1',156,0,'Anambra & Enugu & Eb'),(1057,'1',156,0,'Bauchi & Gombe'),(1058,'1',156,0,'Benue'),(1059,'1',156,0,'Borno & Yobe'),(1060,'1',156,0,'Cross River'),(1061,'1',156,0,'Edo & Delta'),(1062,'1',156,0,'Federal Capital Dist'),(1063,'1',156,0,'Imo & Abia'),(1064,'1',156,0,'Kaduna'),(1065,'1',156,0,'Kano & Jigawa'),(1066,'1',156,0,'Katsina'),(1067,'1',156,0,'Kwara & Kogi'),(1068,'1',156,0,'Lagos'),(1069,'1',156,0,'Niger'),(1070,'1',156,0,'Ogun'),(1071,'1',156,0,'Ondo & Ekiti'),(1072,'1',156,0,'Oyo & Osun'),(1073,'1',156,0,'Plateau & Nassarawa'),(1074,'1',156,0,'Rivers & Bayelsa'),(1075,'1',156,0,'Sokoto & Kebbi & Zam'),(1076,'1',157,0,'Chinandega'),(1077,'1',157,0,'LeÃ³n'),(1078,'1',157,0,'Managua'),(1079,'1',157,0,'Masaya'),(1080,'1',158,0,'â€“'),(1081,'1',159,0,'Drenthe'),(1082,'1',159,0,'Flevoland'),(1083,'1',159,0,'Gelderland'),(1084,'1',159,0,'Groningen'),(1085,'1',159,0,'Limburg'),(1086,'1',159,0,'Noord-Brabant'),(1087,'1',159,0,'Noord-Holland'),(1088,'1',159,0,'Overijssel'),(1089,'1',159,0,'Utrecht'),(1090,'1',159,0,'Zuid-Holland'),(1091,'1',160,0,'Akershus'),(1092,'1',160,0,'Hordaland'),(1093,'1',160,0,'Oslo'),(1094,'1',160,0,'Rogaland'),(1095,'1',160,0,'SÃ¸r-TrÃ¸ndelag'),(1096,'1',161,0,'Central'),(1097,'1',161,0,'Eastern'),(1098,'1',161,0,'Western'),(1099,'1',162,0,'â€“'),(1100,'1',163,0,'Auckland'),(1101,'1',163,0,'Canterbury'),(1102,'1',163,0,'Dunedin'),(1103,'1',163,0,'Hamilton'),(1104,'1',163,0,'Wellington'),(1105,'1',164,0,'al-Batina'),(1106,'1',164,0,'Masqat'),(1107,'1',164,0,'Zufar'),(1108,'1',165,0,'Baluchistan'),(1109,'1',165,0,'Islamabad'),(1110,'1',165,0,'Nothwest Border Prov'),(1111,'1',165,0,'Punjab'),(1112,'1',165,0,'Sind'),(1113,'1',165,0,'Sindh'),(1114,'1',166,0,'PanamÃ¡'),(1115,'1',166,0,'San Miguelito'),(1116,'1',167,0,'â€“'),(1117,'1',168,0,'Ancash'),(1118,'1',168,0,'Arequipa'),(1119,'1',168,0,'Ayacucho'),(1120,'1',168,0,'Cajamarca'),(1121,'1',168,0,'Callao'),(1122,'1',168,0,'Cusco'),(1123,'1',168,0,'Huanuco'),(1124,'1',168,0,'Ica'),(1125,'1',168,0,'JunÃ­n'),(1126,'1',168,0,'La Libertad'),(1127,'1',168,0,'Lambayeque'),(1128,'1',168,0,'Lima'),(1129,'1',168,0,'Loreto'),(1130,'1',168,0,'Piura'),(1131,'1',168,0,'Puno'),(1132,'1',168,0,'Tacna'),(1133,'1',168,0,'Ucayali'),(1134,'1',169,0,'ARMM'),(1135,'1',169,0,'Bicol'),(1136,'1',169,0,'Cagayan Valley'),(1137,'1',169,0,'CAR'),(1138,'1',169,0,'Caraga'),(1139,'1',169,0,'Central Luzon'),(1140,'1',169,0,'Central Mindanao'),(1141,'1',169,0,'Central Visayas'),(1142,'1',169,0,'Eastern Visayas'),(1143,'1',169,0,'Ilocos'),(1144,'1',169,0,'National Capital Region'),(1145,'1',169,0,'Northern Mindanao'),(1146,'1',169,0,'Southern Mindanao'),(1147,'1',169,0,'Southern Tagalog'),(1148,'1',169,0,'Western Mindanao'),(1149,'1',169,0,'Western Visayas'),(1150,'1',170,0,'Koror'),(1151,'1',171,0,'National Capital Dis'),(1152,'1',172,0,'Dolnoslaskie'),(1153,'1',172,0,'Kujawsko-Pomorskie'),(1154,'1',172,0,'Lodzkie'),(1155,'1',172,0,'Lubelskie'),(1156,'1',172,0,'Lubuskie'),(1157,'1',172,0,'Malopolskie'),(1158,'1',172,0,'Mazowieckie'),(1159,'1',172,0,'Opolskie'),(1160,'1',172,0,'Podkarpackie'),(1161,'1',172,0,'Podlaskie'),(1162,'1',172,0,'Pomorskie'),(1163,'1',172,0,'Slaskie'),(1164,'1',172,0,'Swietokrzyskie'),(1165,'1',172,0,'Warminsko-Mazurskie'),(1166,'1',172,0,'Wielkopolskie'),(1167,'1',172,0,'Zachodnio-Pomorskie'),(1168,'1',173,0,'Arecibo'),(1169,'1',173,0,'BayamÃ³n'),(1170,'1',173,0,'Caguas'),(1171,'1',173,0,'Carolina'),(1172,'1',173,0,'Guaynabo'),(1173,'1',173,0,'MayagÃ¼ez'),(1174,'1',173,0,'Ponce'),(1175,'1',173,0,'San Juan'),(1176,'1',173,0,'Toa Baja'),(1177,'1',174,0,'Chagang'),(1178,'1',174,0,'Hamgyong N'),(1179,'1',174,0,'Hamgyong P'),(1180,'1',174,0,'Hwanghae N'),(1181,'1',174,0,'Hwanghae P'),(1182,'1',174,0,'Kaesong-si'),(1183,'1',174,0,'Kangwon'),(1184,'1',174,0,'Nampo-si'),(1185,'1',174,0,'Pyongan N'),(1186,'1',174,0,'Pyongan P'),(1187,'1',174,0,'Pyongyang-si'),(1188,'1',174,0,'Yanggang'),(1189,'1',175,0,'Braga'),(1190,'1',175,0,'CoÃ­mbra'),(1191,'1',175,0,'Lisboa'),(1192,'1',175,0,'Porto'),(1193,'1',176,0,'Alto ParanÃ¡'),(1194,'1',176,0,'AsunciÃ³n'),(1195,'1',176,0,'Central'),(1196,'1',177,0,'Gaza'),(1197,'1',177,0,'Hebron'),(1198,'1',177,0,'Khan Yunis'),(1199,'1',177,0,'Nablus'),(1200,'1',177,0,'North Gaza'),(1201,'1',177,0,'Rafah'),(1202,'1',178,0,'Tahiti'),(1203,'1',179,0,'Doha'),(1204,'1',180,0,'Saint-Denis'),(1205,'1',181,0,'Arad'),(1206,'1',181,0,'Arges'),(1207,'1',181,0,'Bacau'),(1208,'1',181,0,'Bihor'),(1209,'1',181,0,'Botosani'),(1210,'1',181,0,'Braila'),(1211,'1',181,0,'Brasov'),(1212,'1',181,0,'Bukarest'),(1213,'1',181,0,'Buzau'),(1214,'1',181,0,'Caras-Severin'),(1215,'1',181,0,'Cluj'),(1216,'1',181,0,'Constanta'),(1217,'1',181,0,'DÃ¢mbovita'),(1218,'1',181,0,'Dolj'),(1219,'1',181,0,'Galati'),(1220,'1',181,0,'Gorj'),(1221,'1',181,0,'Iasi'),(1222,'1',181,0,'Maramures'),(1223,'1',181,0,'Mehedinti'),(1224,'1',181,0,'Mures'),(1225,'1',181,0,'Neamt'),(1226,'1',181,0,'Prahova'),(1227,'1',181,0,'Satu Mare'),(1228,'1',181,0,'Sibiu'),(1229,'1',181,0,'Suceava'),(1230,'1',181,0,'Timis'),(1231,'1',181,0,'Tulcea'),(1232,'1',181,0,'VÃ¢lcea'),(1233,'1',181,0,'Vrancea'),(1234,'1',182,0,'Adygea'),(1235,'1',182,0,'Altai'),(1236,'1',182,0,'Amur'),(1237,'1',182,0,'Arkangeli'),(1238,'1',182,0,'Astrahan'),(1239,'1',182,0,'BaÅ¡kortostan'),(1240,'1',182,0,'Belgorod'),(1241,'1',182,0,'Brjansk'),(1242,'1',182,0,'Burjatia'),(1243,'1',182,0,'Dagestan'),(1244,'1',182,0,'Habarovsk'),(1245,'1',182,0,'Hakassia'),(1246,'1',182,0,'Hanti-Mansia'),(1247,'1',182,0,'Irkutsk'),(1248,'1',182,0,'Ivanovo'),(1249,'1',182,0,'Jaroslavl'),(1250,'1',182,0,'Kabardi-Balkaria'),(1251,'1',182,0,'Kaliningrad'),(1252,'1',182,0,'Kalmykia'),(1253,'1',182,0,'Kaluga'),(1254,'1',182,0,'KamtÅ¡atka'),(1255,'1',182,0,'KaratÅ¡ai-TÅ¡erkessi'),(1256,'1',182,0,'Karjala'),(1257,'1',182,0,'Kemerovo'),(1258,'1',182,0,'Kirov'),(1259,'1',182,0,'Komi'),(1260,'1',182,0,'Kostroma'),(1261,'1',182,0,'Krasnodar'),(1262,'1',182,0,'Krasnojarsk'),(1263,'1',182,0,'Kurgan'),(1264,'1',182,0,'Kursk'),(1265,'1',182,0,'Lipetsk'),(1266,'1',182,0,'Magadan'),(1267,'1',182,0,'Marinmaa'),(1268,'1',182,0,'Mordva'),(1269,'1',182,0,'Moscow (City)'),(1270,'1',182,0,'Moskova'),(1271,'1',182,0,'Murmansk'),(1272,'1',182,0,'Nizni Novgorod'),(1273,'1',182,0,'North Ossetia-Alania'),(1274,'1',182,0,'Novgorod'),(1275,'1',182,0,'Novosibirsk'),(1276,'1',182,0,'Omsk'),(1277,'1',182,0,'Orenburg'),(1278,'1',182,0,'Orjol'),(1279,'1',182,0,'Penza'),(1280,'1',182,0,'Perm'),(1281,'1',182,0,'Pietari'),(1282,'1',182,0,'Pihkova'),(1283,'1',182,0,'Primorje'),(1284,'1',182,0,'Rjazan'),(1285,'1',182,0,'Rostov-na-Donu'),(1286,'1',182,0,'Saha (Jakutia)'),(1287,'1',182,0,'Sahalin'),(1288,'1',182,0,'Samara'),(1289,'1',182,0,'Saratov'),(1290,'1',182,0,'Smolensk'),(1291,'1',182,0,'Stavropol'),(1292,'1',182,0,'Sverdlovsk'),(1293,'1',182,0,'Tambov'),(1294,'1',182,0,'Tatarstan'),(1295,'1',182,0,'Tjumen'),(1296,'1',182,0,'Tomsk'),(1297,'1',182,0,'Tula'),(1298,'1',182,0,'Tver'),(1299,'1',182,0,'Tyva'),(1300,'1',182,0,'TÅ¡eljabinsk'),(1301,'1',182,0,'TÅ¡etÅ¡enia'),(1302,'1',182,0,'TÅ¡ita'),(1303,'1',182,0,'TÅ¡uvassia'),(1304,'1',182,0,'Udmurtia'),(1305,'1',182,0,'Uljanovsk'),(1306,'1',182,0,'Vladimir'),(1307,'1',182,0,'Volgograd'),(1308,'1',182,0,'Vologda'),(1309,'1',182,0,'Voronez'),(1310,'1',182,0,'Yamalin Nenetsia'),(1311,'1',183,0,'Kigali'),(1312,'1',184,0,'al-Khudud al-Samaliy'),(1313,'1',184,0,'al-Qasim'),(1314,'1',184,0,'al-Sharqiya'),(1315,'1',184,0,'Asir'),(1316,'1',184,0,'Hail'),(1317,'1',184,0,'Medina'),(1318,'1',184,0,'Mekka'),(1319,'1',184,0,'Najran'),(1320,'1',184,0,'Qasim'),(1321,'1',184,0,'Riad'),(1322,'1',184,0,'Riyadh'),(1323,'1',184,0,'Tabuk'),(1324,'1',185,0,'al-Bahr al-Abyad'),(1325,'1',185,0,'al-Bahr al-Ahmar'),(1326,'1',185,0,'al-Jazira'),(1327,'1',185,0,'al-Qadarif'),(1328,'1',185,0,'Bahr al-Jabal'),(1329,'1',185,0,'Darfur al-Janubiya'),(1330,'1',185,0,'Darfur al-Shamaliya'),(1331,'1',185,0,'Kassala'),(1332,'1',185,0,'Khartum'),(1333,'1',185,0,'Kurdufan al-Shamaliy'),(1334,'1',186,0,'Cap-Vert'),(1335,'1',186,0,'Diourbel'),(1336,'1',186,0,'Kaolack'),(1337,'1',186,0,'Saint-Louis'),(1338,'1',186,0,'ThiÃ¨s'),(1339,'1',186,0,'Ziguinchor'),(1340,'1',187,0,'â€“'),(1341,'1',189,0,'Saint Helena'),(1342,'1',190,0,'LÃ¤nsimaa'),(1343,'1',191,0,'Honiara'),(1344,'1',192,0,'Western'),(1345,'1',193,0,'La Libertad'),(1346,'1',193,0,'San Miguel'),(1347,'1',193,0,'San Salvador'),(1348,'1',193,0,'Santa Ana'),(1349,'1',194,0,'San Marino'),(1350,'1',194,0,'Serravalle/Dogano'),(1351,'1',195,0,'Banaadir'),(1352,'1',195,0,'Jubbada Hoose'),(1353,'1',195,0,'Woqooyi Galbeed'),(1354,'1',196,0,'Saint-Pierre'),(1355,'1',197,0,'Aqua Grande'),(1356,'1',198,0,'Paramaribo'),(1357,'1',199,0,'Bratislava'),(1358,'1',199,0,'VÃ½chodnÃ© Slovensko'),(1359,'1',200,0,'Osrednjeslovenska'),(1360,'1',200,0,'Podravska'),(1361,'1',201,0,'Ã–rebros lÃ¤n'),(1362,'1',201,0,'East GÃ¶tanmaan lÃ¤n'),(1363,'1',201,0,'GÃ¤vleborgs lÃ¤n'),(1364,'1',201,0,'JÃ¶nkÃ¶pings lÃ¤n'),(1365,'1',201,0,'Lisboa'),(1366,'1',201,0,'SkÃ¥ne lÃ¤n'),(1367,'1',201,0,'Uppsala lÃ¤n'),(1368,'1',201,0,'VÃ¤sterbottens lÃ¤n'),(1369,'1',201,0,'VÃ¤sternorrlands lÃ¤'),(1370,'1',201,0,'VÃ¤stmanlands lÃ¤n'),(1371,'1',201,0,'West GÃ¶tanmaan lÃ¤n'),(1372,'1',202,0,'Hhohho'),(1373,'1',203,0,'MahÃ©'),(1374,'1',204,0,'al-Hasaka'),(1375,'1',204,0,'al-Raqqa'),(1376,'1',204,0,'Aleppo'),(1377,'1',204,0,'Damascus'),(1378,'1',204,0,'Damaskos'),(1379,'1',204,0,'Dayr al-Zawr'),(1380,'1',204,0,'Hama'),(1381,'1',204,0,'Hims'),(1382,'1',204,0,'Idlib'),(1383,'1',204,0,'Latakia'),(1384,'1',205,0,'Grand Turk'),(1385,'1',206,0,'Chari-Baguirmi'),(1386,'1',206,0,'Logone Occidental'),(1387,'1',207,0,'Maritime'),(1388,'1',208,0,'Bangkok'),(1389,'1',208,0,'Chiang Mai'),(1390,'1',208,0,'Khon Kaen'),(1391,'1',208,0,'Nakhon Pathom'),(1392,'1',208,0,'Nakhon Ratchasima'),(1393,'1',208,0,'Nakhon Sawan'),(1394,'1',208,0,'Nonthaburi'),(1395,'1',208,0,'Songkhla'),(1396,'1',208,0,'Ubon Ratchathani'),(1397,'1',208,0,'Udon Thani'),(1398,'1',209,0,'Karotegin'),(1399,'1',209,0,'Khujand'),(1400,'1',210,0,'Fakaofo'),(1401,'1',211,0,'Ahal'),(1402,'1',211,0,'Dashhowuz'),(1403,'1',211,0,'Lebap'),(1404,'1',211,0,'Mary'),(1405,'1',212,0,'Dili'),(1406,'1',213,0,'Tongatapu'),(1407,'1',214,0,'Caroni'),(1408,'1',214,0,'Port-of-Spain'),(1409,'1',215,0,'Ariana'),(1410,'1',215,0,'Biserta'),(1411,'1',215,0,'GabÃ¨s'),(1412,'1',215,0,'Kairouan'),(1413,'1',215,0,'Sfax'),(1414,'1',215,0,'Sousse'),(1415,'1',215,0,'Tunis'),(1416,'1',216,0,'Adana'),(1417,'1',216,0,'Adiyaman'),(1418,'1',216,0,'Afyon'),(1419,'1',216,0,'Aksaray'),(1420,'1',216,0,'Ankara'),(1421,'1',216,0,'Antalya'),(1422,'1',216,0,'Aydin'),(1423,'1',216,0,'Ã‡orum'),(1424,'1',216,0,'Balikesir'),(1425,'1',216,0,'Batman'),(1426,'1',216,0,'Bursa'),(1427,'1',216,0,'Denizli'),(1428,'1',216,0,'Diyarbakir'),(1429,'1',216,0,'Edirne'),(1430,'1',216,0,'ElÃ¢zig'),(1431,'1',216,0,'Erzincan'),(1432,'1',216,0,'Erzurum'),(1433,'1',216,0,'Eskisehir'),(1434,'1',216,0,'Gaziantep'),(1435,'1',216,0,'Hatay'),(1436,'1',216,0,'IÃ§el'),(1437,'1',216,0,'Isparta'),(1438,'1',216,0,'Istanbul'),(1439,'1',216,0,'Izmir'),(1440,'1',216,0,'Kahramanmaras'),(1441,'1',216,0,'KarabÃ¼k'),(1442,'1',216,0,'Karaman'),(1443,'1',216,0,'Kars'),(1444,'1',216,0,'Kayseri'),(1445,'1',216,0,'KÃ¼tahya'),(1446,'1',216,0,'Kilis'),(1447,'1',216,0,'Kirikkale'),(1448,'1',216,0,'Kocaeli'),(1449,'1',216,0,'Konya'),(1450,'1',216,0,'Malatya'),(1451,'1',216,0,'Manisa'),(1452,'1',216,0,'Mardin'),(1453,'1',216,0,'Ordu'),(1454,'1',216,0,'Osmaniye'),(1455,'1',216,0,'Sakarya'),(1456,'1',216,0,'Samsun'),(1457,'1',216,0,'Sanliurfa'),(1458,'1',216,0,'Siirt'),(1459,'1',216,0,'Sivas'),(1460,'1',216,0,'Tekirdag'),(1461,'1',216,0,'Tokat'),(1462,'1',216,0,'Trabzon'),(1463,'1',216,0,'Usak'),(1464,'1',216,0,'Van'),(1465,'1',216,0,'Zonguldak'),(1466,'1',217,0,'Funafuti'),(1467,'1',218,0,''),(1468,'1',218,0,'Changhwa'),(1469,'1',218,0,'Chiayi'),(1470,'1',218,0,'Hsinchu'),(1471,'1',218,0,'Hualien'),(1472,'1',218,0,'Ilan'),(1473,'1',218,0,'Kaohsiung'),(1474,'1',218,0,'Keelung'),(1475,'1',218,0,'Miaoli'),(1476,'1',218,0,'Nantou'),(1477,'1',218,0,'Pingtung'),(1478,'1',218,0,'Taichung'),(1479,'1',218,0,'Tainan'),(1480,'1',218,0,'Taipei'),(1481,'1',218,0,'Taitung'),(1482,'1',218,0,'Taoyuan'),(1483,'1',218,0,'YÃ¼nlin'),(1484,'1',219,0,'Arusha'),(1485,'1',219,0,'Dar es Salaam'),(1486,'1',219,0,'Dodoma'),(1487,'1',219,0,'Kilimanjaro'),(1488,'1',219,0,'Mbeya'),(1489,'1',219,0,'Morogoro'),(1490,'1',219,0,'Mwanza'),(1491,'1',219,0,'Tabora'),(1492,'1',219,0,'Tanga'),(1493,'1',219,0,'Zanzibar West'),(1494,'1',220,0,'Central'),(1495,'1',221,0,'Dnipropetrovsk'),(1496,'1',221,0,'Donetsk'),(1497,'1',221,0,'Harkova'),(1498,'1',221,0,'Herson'),(1499,'1',221,0,'Hmelnytskyi'),(1500,'1',221,0,'Ivano-Frankivsk'),(1501,'1',221,0,'Kiova'),(1502,'1',221,0,'Kirovograd'),(1503,'1',221,0,'Krim'),(1504,'1',221,0,'Lugansk'),(1505,'1',221,0,'Lviv'),(1506,'1',221,0,'Mykolajiv'),(1507,'1',221,0,'Odesa'),(1508,'1',221,0,'Pultava'),(1509,'1',221,0,'Rivne'),(1510,'1',221,0,'Sumy'),(1511,'1',221,0,'Taka-Karpatia'),(1512,'1',221,0,'Ternopil'),(1513,'1',221,0,'TÅ¡erkasy'),(1514,'1',221,0,'TÅ¡ernigiv'),(1515,'1',221,0,'TÅ¡ernivtsi'),(1516,'1',221,0,'Vinnytsja'),(1517,'1',221,0,'Volynia'),(1518,'1',221,0,'Zaporizzja'),(1519,'1',221,0,'Zytomyr'),(1520,'1',223,0,'Montevideo'),(1521,'1',224,0,'Alabama'),(1522,'1',224,0,'Alaska'),(1523,'1',224,0,'Arizona'),(1524,'1',224,0,'Arkansas'),(1525,'1',224,0,'California'),(1526,'1',224,0,'Colorado'),(1527,'1',224,0,'Connecticut'),(1528,'1',224,0,'District of Columbia'),(1529,'1',224,0,'Florida'),(1530,'1',224,0,'Georgia'),(1531,'1',224,0,'Hawaii'),(1532,'1',224,0,'Idaho'),(1533,'1',224,0,'Illinois'),(1534,'1',224,0,'Indiana'),(1535,'1',224,0,'Iowa'),(1536,'1',224,0,'Kansas'),(1537,'1',224,0,'Kentucky'),(1538,'1',224,0,'Louisiana'),(1539,'1',224,0,'Maryland'),(1540,'1',224,0,'Massachusetts'),(1541,'1',224,0,'Michigan'),(1542,'1',224,0,'Minnesota'),(1543,'1',224,0,'Mississippi'),(1544,'1',224,0,'Missouri'),(1545,'1',224,0,'Montana'),(1546,'1',224,0,'Nebraska'),(1547,'1',224,0,'Nevada'),(1548,'1',224,0,'New Hampshire'),(1549,'1',224,0,'New Jersey'),(1550,'1',224,0,'New Mexico'),(1551,'1',224,0,'New York'),(1552,'1',224,0,'North Carolina'),(1553,'1',224,0,'Ohio'),(1554,'1',224,0,'Oklahoma'),(1555,'1',224,0,'Oregon'),(1556,'1',224,0,'Pennsylvania'),(1557,'1',224,0,'Rhode Island'),(1558,'1',224,0,'South Carolina'),(1559,'1',224,0,'South Dakota'),(1560,'1',224,0,'Tennessee'),(1561,'1',224,0,'Texas'),(1562,'1',224,0,'Utah'),(1563,'1',224,0,'Virginia'),(1564,'1',224,0,'Washington'),(1565,'1',224,0,'Wisconsin'),(1566,'1',225,0,'Andijon'),(1567,'1',225,0,'Buhoro'),(1568,'1',225,0,'Cizah'),(1569,'1',225,0,'Fargona'),(1570,'1',225,0,'Karakalpakistan'),(1571,'1',225,0,'Khorazm'),(1572,'1',225,0,'Namangan'),(1573,'1',225,0,'Navoi'),(1574,'1',225,0,'Qashqadaryo'),(1575,'1',225,0,'Samarkand'),(1576,'1',225,0,'Surkhondaryo'),(1577,'1',225,0,'Toskent'),(1578,'1',225,0,'Toskent Shahri'),(1579,'1',226,0,'â€“'),(1580,'1',0,0,'St George'),(1581,'1',228,0,''),(1582,'1',228,0,'AnzoÃ¡tegui'),(1583,'1',228,0,'Apure'),(1584,'1',228,0,'Aragua'),(1585,'1',228,0,'Barinas'),(1586,'1',228,0,'BolÃ­var'),(1587,'1',228,0,'Carabobo'),(1588,'1',228,0,'Distrito Federal'),(1589,'1',228,0,'FalcÃ³n'),(1590,'1',228,0,'GuÃ¡rico'),(1591,'1',228,0,'Lara'),(1592,'1',228,0,'MÃ©rida'),(1593,'1',228,0,'Miranda'),(1594,'1',228,0,'Monagas'),(1595,'1',228,0,'Portuguesa'),(1596,'1',228,0,'Sucre'),(1597,'1',228,0,'TÃ¡chira'),(1598,'1',228,0,'Trujillo'),(1599,'1',228,0,'Yaracuy'),(1600,'1',228,0,'Zulia'),(1601,'1',229,0,'Tortola'),(1602,'1',230,0,'St Thomas'),(1603,'1',231,0,'An Giang'),(1604,'1',231,0,'Ba Ria-Vung Tau'),(1605,'1',231,0,'Bac Thai'),(1606,'1',231,0,'Binh Dinh'),(1607,'1',231,0,'Binh Thuan'),(1608,'1',231,0,'Can Tho'),(1609,'1',231,0,'Dac Lac'),(1610,'1',231,0,'Dong Nai'),(1611,'1',231,0,'Haiphong'),(1612,'1',231,0,'Hanoi'),(1613,'1',231,0,'Ho Chi Minh City'),(1614,'1',231,0,'Khanh Hoa'),(1615,'1',231,0,'Kien Giang'),(1616,'1',231,0,'Lam Dong'),(1617,'1',231,0,'Nam Ha'),(1618,'1',231,0,'Nghe An'),(1619,'1',231,0,'Quang Binh'),(1620,'1',231,0,'Quang Nam-Da Nang'),(1621,'1',231,0,'Quang Ninh'),(1622,'1',231,0,'Thua Thien-Hue'),(1623,'1',231,0,'Tien Giang'),(1624,'1',232,0,'Shefa'),(1625,'1',233,0,'Wallis'),(1626,'1',234,0,'Upolu'),(1627,'1',235,0,'Aden'),(1628,'1',235,0,'Hadramawt'),(1629,'1',235,0,'Hodeida'),(1630,'1',235,0,'Ibb'),(1631,'1',235,0,'Sanaa'),(1632,'1',235,0,'Taizz'),(1633,'1',236,0,'Central Serbia'),(1634,'1',236,0,'Kosovo and Metohija'),(1635,'1',236,0,'Montenegro'),(1636,'1',236,0,'Vojvodina'),(1637,'1',237,0,'Eastern Cape'),(1638,'1',237,0,'Free State'),(1639,'1',237,0,'Gauteng'),(1640,'1',237,0,'KwaZulu-Natal'),(1641,'1',237,0,'Mpumalanga'),(1642,'1',237,0,'North West'),(1643,'1',237,0,'Northern Cape'),(1644,'1',237,0,'Western Cape'),(1645,'1',238,0,'Central'),(1646,'1',238,0,'Copperbelt'),(1647,'1',238,0,'Lusaka'),(1648,'1',239,0,'Bulawayo'),(1649,'1',239,0,'Harare'),(1650,'1',239,0,'Manicaland'),(1651,'1',239,0,'Midlands'),(1652,'2',240,0,'South Hill'),(1653,'2',240,0,'The Valley'),(1654,'2',240,0,'Oranjestad'),(1655,'2',240,0,'Douglas'),(1656,'2',240,0,'Gibraltar'),(1657,'2',240,0,'Tamuning'),(1658,'2',240,0,'AgaÃ±a'),(1659,'2',240,0,'Flying Fish Cove'),(1660,'2',240,0,'Monte-Carlo'),(1661,'2',240,0,'Monaco-Ville'),(1662,'2',240,0,'Yangor'),(1663,'2',240,0,'Yaren'),(1664,'2',240,0,'Alofi'),(1665,'2',240,0,'Kingston'),(1666,'2',240,0,'Adamstown'),(1667,'2',240,0,'Singapore'),(1668,'2',240,0,'NoumÃ©a'),(1669,'2',240,0,'CittÃ  del Vaticano'),(1670,'2',241,0,'Mazar-e-Sharif'),(1671,'2',242,0,'Herat'),(1672,'2',243,0,'Kabul'),(1673,'2',244,0,'Qandahar'),(1674,'2',245,0,'Lobito'),(1675,'2',245,0,'Benguela'),(1676,'2',246,0,'Huambo'),(1677,'2',247,0,'Luanda'),(1678,'2',248,0,'Namibe'),(1679,'2',249,0,'South Hill'),(1680,'2',249,0,'The Valley'),(1681,'2',249,0,'Oranjestad'),(1682,'2',249,0,'Douglas'),(1683,'2',249,0,'Gibraltar'),(1684,'2',249,0,'Tamuning'),(1685,'2',249,0,'AgaÃ±a'),(1686,'2',249,0,'Flying Fish Cove'),(1687,'2',249,0,'Monte-Carlo'),(1688,'2',249,0,'Monaco-Ville'),(1689,'2',249,0,'Yangor'),(1690,'2',249,0,'Yaren'),(1691,'2',249,0,'Alofi'),(1692,'2',249,0,'Kingston'),(1693,'2',249,0,'Adamstown'),(1694,'2',249,0,'Singapore'),(1695,'2',249,0,'NoumÃ©a'),(1696,'2',249,0,'CittÃ  del Vaticano'),(1697,'2',250,0,'Tirana'),(1698,'2',251,0,'Andorra la Vella'),(1699,'2',252,0,'Willemstad'),(1700,'2',253,0,'Abu Dhabi'),(1701,'2',253,0,'al-Ayn'),(1702,'2',254,0,'Ajman'),(1703,'2',255,0,'Dubai'),(1704,'2',256,0,'Sharja'),(1705,'2',257,0,'La Matanza'),(1706,'2',257,0,'Lomas de Zamora'),(1707,'2',257,0,'Quilmes'),(1708,'2',257,0,'Almirante Brown'),(1709,'2',257,0,'La Plata'),(1710,'2',257,0,'Mar del Plata'),(1711,'2',257,0,'LanÃºs'),(1712,'2',257,0,'Merlo'),(1713,'2',257,0,'General San MartÃ­n'),(1714,'2',257,0,'Moreno'),(1715,'2',257,0,'Avellaneda'),(1716,'2',257,0,'Tres de Febrero'),(1717,'2',257,0,'MorÃ³n'),(1718,'2',257,0,'Florencio Varela'),(1719,'2',257,0,'San Isidro'),(1720,'2',257,0,'Tigre'),(1721,'2',257,0,'Malvinas Argentinas'),(1722,'2',257,0,'Vicente LÃ³pez'),(1723,'2',257,0,'Berazategui'),(1724,'2',257,0,'San Miguel'),(1725,'2',257,0,'BahÃ­a Blanca'),(1726,'2',257,0,'Esteban EcheverrÃ­a'),(1727,'2',257,0,'JosÃ© C. Paz'),(1728,'2',257,0,'Hurlingham'),(1729,'2',257,0,'ItuzaingÃ³'),(1730,'2',257,0,'San Fernando'),(1731,'2',257,0,'San NicolÃ¡s de los Arroyos'),(1732,'2',257,0,'Escobar'),(1733,'2',257,0,'Pilar'),(1734,'2',257,0,'Ezeiza'),(1735,'2',257,0,'Tandil'),(1736,'2',258,0,'San Fernando del Valle de Cata'),(1737,'2',259,0,'CÃ³rdoba'),(1738,'2',259,0,'RÃ­o Cuarto'),(1739,'2',259,0,'MonterÃ­a'),(1740,'2',260,0,'Resistencia'),(1741,'2',261,0,'Comodoro Rivadavia'),(1742,'2',262,0,'Corrientes'),(1743,'2',263,0,'Buenos Aires'),(1744,'2',263,0,'BrasÃ­lia'),(1745,'2',263,0,'Ciudad de MÃ©xico'),(1746,'2',263,0,'Caracas'),(1747,'2',263,0,'Catia La Mar'),(1748,'2',264,0,'ParanÃ¡'),(1749,'2',264,0,'Concordia'),(1750,'2',265,0,'Formosa'),(1751,'2',266,0,'San Salvador de Jujuy'),(1752,'2',267,0,'La Rioja'),(1753,'2',267,0,'LogroÃ±o'),(1754,'2',268,0,'Godoy Cruz'),(1755,'2',268,0,'GuaymallÃ©n'),(1756,'2',268,0,'Las Heras'),(1757,'2',268,0,'Mendoza'),(1758,'2',268,0,'San Rafael'),(1759,'2',269,0,'Posadas'),(1760,'2',270,0,'NeuquÃ©n'),(1761,'2',271,0,'Salta'),(1762,'2',272,0,'San Juan'),(1763,'2',272,0,'San Juan'),(1764,'2',273,0,'San Luis'),(1765,'2',274,0,'Rosario'),(1766,'2',274,0,'Santa FÃ©'),(1767,'2',275,0,'Santiago del Estero'),(1768,'2',276,0,'San Miguel de TucumÃ¡n'),(1769,'2',277,0,'Vanadzor'),(1770,'2',278,0,'Yerevan'),(1771,'2',279,0,'Gjumri'),(1772,'2',280,0,'Tafuna'),(1773,'2',280,0,'Fagatogo'),(1774,'2',281,0,'Saint JohnÂ´s'),(1775,'2',282,0,'Canberra'),(1776,'2',283,0,'Sydney'),(1777,'2',283,0,'Newcastle'),(1778,'2',283,0,'Central Coast'),(1779,'2',283,0,'Wollongong'),(1780,'2',284,0,'Brisbane'),(1781,'2',284,0,'Gold Coast'),(1782,'2',284,0,'Townsville'),(1783,'2',284,0,'Cairns'),(1784,'2',285,0,'Adelaide'),(1785,'2',286,0,'Hobart'),(1786,'2',287,0,'Melbourne'),(1787,'2',287,0,'Geelong'),(1788,'2',288,0,'Perth'),(1789,'2',289,0,'Klagenfurt'),(1790,'2',290,0,'Linz'),(1791,'2',291,0,'Salzburg'),(1792,'2',292,0,'Graz'),(1793,'2',293,0,'Innsbruck'),(1794,'2',294,0,'Wien'),(1795,'2',295,0,'Baku'),(1796,'2',296,0,'GÃ¤ncÃ¤'),(1797,'2',297,0,'MingÃ¤Ã§evir'),(1798,'2',298,0,'Sumqayit'),(1799,'2',299,0,'Bujumbura'),(1800,'2',300,0,'Antwerpen'),(1801,'2',301,0,'Bruxelles [Brussel]'),(1802,'2',301,0,'Schaerbeek'),(1803,'2',302,0,'Gent'),(1804,'2',303,0,'Charleroi'),(1805,'2',303,0,'Mons'),(1806,'2',304,0,'LiÃ¨ge'),(1807,'2',305,0,'Namur'),(1808,'2',306,0,'Brugge'),(1809,'2',307,0,'Djougou'),(1810,'2',308,0,'Cotonou'),(1811,'2',309,0,'Parakou'),(1812,'2',310,0,'Porto-Novo'),(1813,'2',311,0,'Koudougou'),(1814,'2',312,0,'Bobo-Dioulasso'),(1815,'2',313,0,'Ouagadougou'),(1816,'2',314,0,'Barisal'),(1817,'2',315,0,'Chittagong'),(1818,'2',315,0,'Comilla'),(1819,'2',315,0,'Brahmanbaria'),(1820,'2',316,0,'Dhaka'),(1821,'2',316,0,'Narayanganj'),(1822,'2',316,0,'Mymensingh'),(1823,'2',316,0,'Tungi'),(1824,'2',316,0,'Tangail'),(1825,'2',316,0,'Jamalpur'),(1826,'2',316,0,'Narsinghdi'),(1827,'2',316,0,'Gazipur'),(1828,'2',317,0,'Khulna'),(1829,'2',317,0,'Jessore'),(1830,'2',318,0,'Rajshahi'),(1831,'2',318,0,'Rangpur'),(1832,'2',318,0,'Nawabganj'),(1833,'2',318,0,'Dinajpur'),(1834,'2',318,0,'Bogra'),(1835,'2',318,0,'Pabna'),(1836,'2',318,0,'Naogaon'),(1837,'2',318,0,'Sirajganj'),(1838,'2',318,0,'Saidpur'),(1839,'2',319,0,'Sylhet'),(1840,'2',320,0,'Burgas'),(1841,'2',320,0,'Sliven'),(1842,'2',321,0,'Sofija'),(1843,'2',322,0,'Stara Zagora'),(1844,'2',323,0,'Pleven'),(1845,'2',324,0,'Plovdiv'),(1846,'2',325,0,'Ruse'),(1847,'2',326,0,'Varna'),(1848,'2',326,0,'Dobric'),(1849,'2',326,0,'Å umen'),(1850,'2',327,0,'al-Manama'),(1851,'2',328,0,'Nassau'),(1852,'2',329,0,'Sarajevo'),(1853,'2',329,0,'Zenica'),(1854,'2',330,0,'Banja Luka'),(1855,'2',331,0,'Brest'),(1856,'2',331,0,'BaranovitÅ¡i'),(1857,'2',331,0,'Pinsk'),(1858,'2',332,0,'Gomel'),(1859,'2',332,0,'Mozyr'),(1860,'2',333,0,'Grodno'),(1861,'2',333,0,'Lida'),(1862,'2',334,0,'Minsk'),(1863,'2',335,0,'Borisov'),(1864,'2',335,0,'Soligorsk'),(1865,'2',335,0,'MolodetÅ¡no'),(1866,'2',336,0,'Mogiljov'),(1867,'2',336,0,'Bobruisk'),(1868,'2',337,0,'Vitebsk'),(1869,'2',337,0,'OrÅ¡a'),(1870,'2',337,0,'Novopolotsk'),(1871,'2',338,0,'Belize City'),(1872,'2',339,0,'Belmopan'),(1873,'2',340,0,'Hamilton'),(1874,'2',340,0,'Hamilton'),(1875,'2',341,0,'Saint George'),(1876,'2',342,0,'Sucre'),(1877,'2',343,0,'Cochabamba'),(1878,'2',344,0,'La Paz'),(1879,'2',344,0,'El Alto'),(1880,'2',345,0,'Oruro'),(1881,'2',346,0,'PotosÃ­'),(1882,'2',347,0,'Santa Cruz de la Sierra'),(1883,'2',348,0,'Tarija'),(1884,'2',349,0,'Rio Branco'),(1885,'2',350,0,'MaceiÃ³'),(1886,'2',350,0,'Arapiraca'),(1887,'2',351,0,'MacapÃ¡'),(1888,'2',352,0,'Manaus'),(1889,'2',353,0,'Salvador'),(1890,'2',353,0,'Feira de Santana'),(1891,'2',353,0,'IlhÃ©us'),(1892,'2',353,0,'VitÃ³ria da Conquista'),(1893,'2',353,0,'Juazeiro'),(1894,'2',353,0,'Itabuna'),(1895,'2',353,0,'JequiÃ©'),(1896,'2',353,0,'CamaÃ§ari'),(1897,'2',353,0,'Barreiras'),(1898,'2',353,0,'Alagoinhas'),(1899,'2',353,0,'Lauro de Freitas'),(1900,'2',353,0,'Teixeira de Freitas'),(1901,'2',353,0,'Paulo Afonso'),(1902,'2',353,0,'EunÃ¡polis'),(1903,'2',353,0,'Jacobina'),(1904,'2',354,0,'Fortaleza'),(1905,'2',354,0,'Caucaia'),(1906,'2',354,0,'Juazeiro do Norte'),(1907,'2',354,0,'MaracanaÃº'),(1908,'2',354,0,'Sobral'),(1909,'2',354,0,'Crato'),(1910,'2',355,0,'Buenos Aires'),(1911,'2',355,0,'BrasÃ­lia'),(1912,'2',355,0,'Ciudad de MÃ©xico'),(1913,'2',355,0,'Caracas'),(1914,'2',355,0,'Catia La Mar'),(1915,'2',356,0,'Cariacica'),(1916,'2',356,0,'Vila Velha'),(1917,'2',356,0,'Serra'),(1918,'2',356,0,'VitÃ³ria'),(1919,'2',356,0,'Cachoeiro de Itapemirim'),(1920,'2',356,0,'Colatina'),(1921,'2',356,0,'Linhares'),(1922,'2',357,0,'GoiÃ¢nia'),(1923,'2',357,0,'Aparecida de GoiÃ¢nia'),(1924,'2',357,0,'AnÃ¡polis'),(1925,'2',357,0,'LuziÃ¢nia'),(1926,'2',357,0,'Rio Verde'),(1927,'2',357,0,'Ãguas Lindas de GoiÃ¡s'),(1928,'2',358,0,'SÃ£o LuÃ­s'),(1929,'2',358,0,'Imperatriz'),(1930,'2',358,0,'Caxias'),(1931,'2',358,0,'Timon'),(1932,'2',358,0,'CodÃ³'),(1933,'2',358,0,'SÃ£o JosÃ© de Ribamar'),(1934,'2',358,0,'Bacabal'),(1935,'2',359,0,'CuiabÃ¡'),(1936,'2',359,0,'VÃ¡rzea Grande'),(1937,'2',359,0,'RondonÃ³polis'),(1938,'2',360,0,'Campo Grande'),(1939,'2',360,0,'Dourados'),(1940,'2',360,0,'CorumbÃ¡'),(1941,'2',361,0,'Belo Horizonte'),(1942,'2',361,0,'Contagem'),(1943,'2',361,0,'UberlÃ¢ndia'),(1944,'2',361,0,'Juiz de Fora'),(1945,'2',361,0,'Betim'),(1946,'2',361,0,'Montes Claros'),(1947,'2',361,0,'Uberaba'),(1948,'2',361,0,'RibeirÃ£o das Neves'),(1949,'2',361,0,'Governador Valadares'),(1950,'2',361,0,'Ipatinga'),(1951,'2',361,0,'DivinÃ³polis'),(1952,'2',361,0,'Sete Lagoas'),(1953,'2',361,0,'Santa Luzia'),(1954,'2',361,0,'PoÃ§os de Caldas'),(1955,'2',361,0,'IbiritÃ©'),(1956,'2',361,0,'TeÃ³filo Otoni'),(1957,'2',361,0,'Patos de Minas'),(1958,'2',361,0,'Barbacena'),(1959,'2',361,0,'Varginha'),(1960,'2',361,0,'SabarÃ¡'),(1961,'2',361,0,'Itabira'),(1962,'2',361,0,'Pouso Alegre'),(1963,'2',361,0,'Passos'),(1964,'2',361,0,'Araguari'),(1965,'2',361,0,'Conselheiro Lafaiete'),(1966,'2',361,0,'Coronel Fabriciano'),(1967,'2',361,0,'Ituiutaba'),(1968,'2',362,0,'JoÃ£o Pessoa'),(1969,'2',362,0,'Campina Grande'),(1970,'2',362,0,'Santa Rita'),(1971,'2',362,0,'Patos'),(1972,'2',363,0,'Curitiba'),(1973,'2',363,0,'Londrina'),(1974,'2',363,0,'MaringÃ¡'),(1975,'2',363,0,'Ponta Grossa'),(1976,'2',363,0,'Foz do IguaÃ§u'),(1977,'2',363,0,'Cascavel'),(1978,'2',363,0,'SÃ£o JosÃ© dos Pinhais'),(1979,'2',363,0,'Colombo'),(1980,'2',363,0,'Guarapuava'),(1981,'2',363,0,'ParanaguÃ¡'),(1982,'2',363,0,'Apucarana'),(1983,'2',363,0,'Toledo'),(1984,'2',363,0,'Pinhais'),(1985,'2',363,0,'Campo Largo'),(1986,'2',364,0,'BelÃ©m'),(1987,'2',364,0,'Ananindeua'),(1988,'2',364,0,'SantarÃ©m'),(1989,'2',364,0,'MarabÃ¡'),(1990,'2',364,0,'Castanhal'),(1991,'2',364,0,'Abaetetuba'),(1992,'2',364,0,'Itaituba'),(1993,'2',364,0,'CametÃ¡'),(1994,'2',365,0,'Recife'),(1995,'2',365,0,'JaboatÃ£o dos Guararapes'),(1996,'2',365,0,'Olinda'),(1997,'2',365,0,'Paulista'),(1998,'2',365,0,'Caruaru'),(1999,'2',365,0,'Petrolina'),(2000,'2',365,0,'Cabo de Santo Agostinho'),(2001,'2',365,0,'Camaragibe'),(2002,'2',365,0,'Garanhuns'),(2003,'2',365,0,'VitÃ³ria de Santo AntÃ£o'),(2004,'2',365,0,'SÃ£o LourenÃ§o da Mata'),(2005,'2',366,0,'Teresina'),(2006,'2',366,0,'ParnaÃ­ba'),(2007,'2',367,0,'Rio de Janeiro'),(2008,'2',367,0,'SÃ£o GonÃ§alo'),(2009,'2',367,0,'Nova IguaÃ§u'),(2010,'2',367,0,'Duque de Caxias'),(2011,'2',367,0,'NiterÃ³i'),(2012,'2',367,0,'SÃ£o JoÃ£o de Meriti'),(2013,'2',367,0,'Belford Roxo'),(2014,'2',367,0,'Campos dos Goytacazes'),(2015,'2',367,0,'PetrÃ³polis'),(2016,'2',367,0,'Volta Redonda'),(2017,'2',367,0,'MagÃ©'),(2018,'2',367,0,'ItaboraÃ­'),(2019,'2',367,0,'Nova Friburgo'),(2020,'2',367,0,'Barra Mansa'),(2021,'2',367,0,'NilÃ³polis'),(2022,'2',367,0,'TeresÃ³polis'),(2023,'2',367,0,'MacaÃ©'),(2024,'2',367,0,'Cabo Frio'),(2025,'2',367,0,'Queimados'),(2026,'2',367,0,'Resende'),(2027,'2',367,0,'Angra dos Reis'),(2028,'2',367,0,'Barra do PiraÃ­'),(2029,'2',368,0,'Natal'),(2030,'2',368,0,'MossorÃ³'),(2031,'2',368,0,'Parnamirim'),(2032,'2',369,0,'Porto Alegre'),(2033,'2',369,0,'Caxias do Sul'),(2034,'2',369,0,'Pelotas'),(2035,'2',369,0,'Canoas'),(2036,'2',369,0,'Novo Hamburgo'),(2037,'2',369,0,'Santa Maria'),(2038,'2',369,0,'GravataÃ­'),(2039,'2',369,0,'ViamÃ£o'),(2040,'2',369,0,'SÃ£o Leopoldo'),(2041,'2',369,0,'Rio Grande'),(2042,'2',369,0,'Alvorada'),(2043,'2',369,0,'Passo Fundo'),(2044,'2',369,0,'Uruguaiana'),(2045,'2',369,0,'BagÃ©'),(2046,'2',369,0,'Sapucaia do Sul'),(2047,'2',369,0,'Santa Cruz do Sul'),(2048,'2',369,0,'Cachoeirinha'),(2049,'2',369,0,'GuaÃ­ba'),(2050,'2',369,0,'Santana do Livramento'),(2051,'2',369,0,'Bento GonÃ§alves'),(2052,'2',370,0,'Porto Velho'),(2053,'2',370,0,'Ji-ParanÃ¡'),(2054,'2',371,0,'Boa Vista'),(2055,'2',372,0,'Joinville'),(2056,'2',372,0,'FlorianÃ³polis'),(2057,'2',372,0,'Blumenau'),(2058,'2',372,0,'CriciÃºma'),(2059,'2',372,0,'SÃ£o JosÃ©'),(2060,'2',372,0,'ItajaÃ­'),(2061,'2',372,0,'ChapecÃ³'),(2062,'2',372,0,'Lages'),(2063,'2',372,0,'JaraguÃ¡ do Sul'),(2064,'2',372,0,'PalhoÃ§a'),(2065,'2',373,0,'SÃ£o Paulo'),(2066,'2',373,0,'Guarulhos'),(2067,'2',373,0,'Campinas'),(2068,'2',373,0,'SÃ£o Bernardo do Campo'),(2069,'2',373,0,'Osasco'),(2070,'2',373,0,'Santo AndrÃ©'),(2071,'2',373,0,'SÃ£o JosÃ© dos Campos'),(2072,'2',373,0,'RibeirÃ£o Preto'),(2073,'2',373,0,'Sorocaba'),(2074,'2',373,0,'Santos'),(2075,'2',373,0,'MauÃ¡'),(2076,'2',373,0,'CarapicuÃ­ba'),(2077,'2',373,0,'SÃ£o JosÃ© do Rio Preto'),(2078,'2',373,0,'Moji das Cruzes'),(2079,'2',373,0,'Diadema'),(2080,'2',373,0,'Piracicaba'),(2081,'2',373,0,'Bauru'),(2082,'2',373,0,'JundÃ­aÃ­'),(2083,'2',373,0,'Franca'),(2084,'2',373,0,'SÃ£o Vicente'),(2085,'2',373,0,'Itaquaquecetuba'),(2086,'2',373,0,'Limeira'),(2087,'2',373,0,'GuarujÃ¡'),(2088,'2',373,0,'TaubatÃ©'),(2089,'2',373,0,'Embu'),(2090,'2',373,0,'Barueri'),(2091,'2',373,0,'TaboÃ£o da Serra'),(2092,'2',373,0,'Suzano'),(2093,'2',373,0,'MarÃ­lia'),(2094,'2',373,0,'SÃ£o Carlos'),(2095,'2',373,0,'SumarÃ©'),(2096,'2',373,0,'Presidente Prudente'),(2097,'2',373,0,'Americana'),(2098,'2',373,0,'Araraquara'),(2099,'2',373,0,'Santa BÃ¡rbara dÂ´Oeste'),(2100,'2',373,0,'JacareÃ­'),(2101,'2',373,0,'AraÃ§atuba'),(2102,'2',373,0,'Praia Grande'),(2103,'2',373,0,'Rio Claro'),(2104,'2',373,0,'Itapevi'),(2105,'2',373,0,'Cotia'),(2106,'2',373,0,'Ferraz de Vasconcelos'),(2107,'2',373,0,'Indaiatuba'),(2108,'2',373,0,'HortolÃ¢ndia'),(2109,'2',373,0,'SÃ£o Caetano do Sul'),(2110,'2',373,0,'Itu'),(2111,'2',373,0,'Itapecerica da Serra'),(2112,'2',373,0,'Moji-GuaÃ§u'),(2113,'2',373,0,'Pindamonhangaba'),(2114,'2',373,0,'Francisco Morato'),(2115,'2',373,0,'Itapetininga'),(2116,'2',373,0,'BraganÃ§a Paulista'),(2117,'2',373,0,'JaÃº'),(2118,'2',373,0,'Franco da Rocha'),(2119,'2',373,0,'RibeirÃ£o Pires'),(2120,'2',373,0,'Catanduva'),(2121,'2',373,0,'Botucatu'),(2122,'2',373,0,'Barretos'),(2123,'2',373,0,'GuaratinguetÃ¡'),(2124,'2',373,0,'CubatÃ£o'),(2125,'2',373,0,'Araras'),(2126,'2',373,0,'Atibaia'),(2127,'2',373,0,'SertÃ£ozinho'),(2128,'2',373,0,'Salto'),(2129,'2',373,0,'Ourinhos'),(2130,'2',373,0,'Birigui'),(2131,'2',373,0,'TatuÃ­'),(2132,'2',373,0,'Votorantim'),(2133,'2',373,0,'PoÃ¡'),(2134,'2',374,0,'Aracaju'),(2135,'2',374,0,'Nossa Senhora do Socorro'),(2136,'2',375,0,'Palmas'),(2137,'2',375,0,'AraguaÃ­na'),(2138,'2',376,0,'Bridgetown'),(2139,'2',377,0,'Bandar Seri Begawan'),(2140,'2',378,0,'Thimphu'),(2141,'2',379,0,'Francistown'),(2142,'2',380,0,'Gaborone'),(2143,'2',381,0,'Bangui'),(2144,'2',382,0,'Calgary'),(2145,'2',382,0,'Edmonton'),(2146,'2',383,0,'Vancouver'),(2147,'2',383,0,'Surrey'),(2148,'2',383,0,'Burnaby'),(2149,'2',383,0,'Richmond'),(2150,'2',383,0,'Abbotsford'),(2151,'2',383,0,'Coquitlam'),(2152,'2',383,0,'Saanich'),(2153,'2',383,0,'Delta'),(2154,'2',383,0,'Kelowna'),(2155,'2',384,0,'Winnipeg'),(2156,'2',385,0,'Saint JohnÂ´s'),(2157,'2',386,0,'Cape Breton'),(2158,'2',386,0,'Halifax'),(2159,'2',387,0,'Toronto'),(2160,'2',387,0,'North York'),(2161,'2',387,0,'Mississauga'),(2162,'2',387,0,'Scarborough'),(2163,'2',387,0,'Etobicoke'),(2164,'2',387,0,'London'),(2165,'2',387,0,'Hamilton'),(2166,'2',387,0,'Ottawa'),(2167,'2',387,0,'Brampton'),(2168,'2',387,0,'Windsor'),(2169,'2',387,0,'Kitchener'),(2170,'2',387,0,'Markham'),(2171,'2',387,0,'York'),(2172,'2',387,0,'Vaughan'),(2173,'2',387,0,'Burlington'),(2174,'2',387,0,'Oshawa'),(2175,'2',387,0,'Oakville'),(2176,'2',387,0,'Saint Catharines'),(2177,'2',387,0,'Richmond Hill'),(2178,'2',387,0,'Thunder Bay'),(2179,'2',387,0,'Nepean'),(2180,'2',387,0,'East York'),(2181,'2',387,0,'Cambridge'),(2182,'2',387,0,'Gloucester'),(2183,'2',387,0,'Guelph'),(2184,'2',387,0,'Sudbury'),(2185,'2',387,0,'Barrie'),(2186,'2',388,0,'MontrÃ©al'),(2187,'2',388,0,'Laval'),(2188,'2',388,0,'QuÃ©bec'),(2189,'2',388,0,'Longueuil'),(2190,'2',388,0,'Gatineau'),(2191,'2',389,0,'Saskatoon'),(2192,'2',389,0,'Regina'),(2193,'2',390,0,'Bantam'),(2194,'2',391,0,'West Island'),(2195,'2',392,0,'Basel'),(2196,'2',393,0,'Bern'),(2197,'2',394,0,'Geneve'),(2198,'2',395,0,'Lausanne'),(2199,'2',396,0,'ZÃ¼rich'),(2200,'2',397,0,'Antofagasta'),(2201,'2',397,0,'Calama'),(2202,'2',398,0,'CopiapÃ³'),(2203,'2',399,0,'Talcahuano'),(2204,'2',399,0,'ConcepciÃ³n'),(2205,'2',399,0,'ChillÃ¡n'),(2206,'2',399,0,'Los Angeles'),(2207,'2',399,0,'Coronel'),(2208,'2',399,0,'San Pedro de la Paz'),(2209,'2',400,0,'Coquimbo'),(2210,'2',400,0,'La Serena'),(2211,'2',400,0,'Ovalle'),(2212,'2',401,0,'Temuco'),(2213,'2',402,0,'Puerto Montt'),(2214,'2',402,0,'Osorno'),(2215,'2',402,0,'Valdivia'),(2216,'2',403,0,'Punta Arenas'),(2217,'2',404,0,'Talca'),(2218,'2',404,0,'CuricÃ³'),(2219,'2',405,0,'Rancagua'),(2220,'2',406,0,'Santiago de Chile'),(2221,'2',406,0,'Puente Alto'),(2222,'2',406,0,'San Bernardo'),(2223,'2',406,0,'Melipilla'),(2224,'2',406,0,'Santiago de los Caballeros'),(2225,'2',407,0,'Arica'),(2226,'2',407,0,'Iquique'),(2227,'2',408,0,'ViÃ±a del Mar'),(2228,'2',408,0,'ValparaÃ­so'),(2229,'2',408,0,'QuilpuÃ©'),(2230,'2',409,0,'Hefei'),(2231,'2',409,0,'Huainan'),(2232,'2',409,0,'Bengbu'),(2233,'2',409,0,'Wuhu'),(2234,'2',409,0,'Huaibei'),(2235,'2',409,0,'MaÂ´anshan'),(2236,'2',409,0,'Anqing'),(2237,'2',409,0,'Tongling'),(2238,'2',409,0,'Fuyang'),(2239,'2',409,0,'Suzhou'),(2240,'2',409,0,'LiuÂ´an'),(2241,'2',409,0,'Chuzhou'),(2242,'2',409,0,'Chaohu'),(2243,'2',409,0,'Xuangzhou'),(2244,'2',409,0,'Bozhou'),(2245,'2',409,0,'Huangshan'),(2246,'2',410,0,'Chongqing'),(2247,'2',411,0,'Fuzhou'),(2248,'2',411,0,'Amoy [Xiamen]'),(2249,'2',411,0,'Nanping'),(2250,'2',411,0,'Quanzhou'),(2251,'2',411,0,'Zhangzhou'),(2252,'2',411,0,'Sanming'),(2253,'2',411,0,'Longyan'),(2254,'2',411,0,'YongÂ´an'),(2255,'2',411,0,'FuÂ´an'),(2256,'2',411,0,'Fuqing'),(2257,'2',411,0,'Putian'),(2258,'2',411,0,'Shaowu'),(2259,'2',412,0,'Lanzhou'),(2260,'2',412,0,'Tianshui'),(2261,'2',412,0,'Baiyin'),(2262,'2',412,0,'Wuwei'),(2263,'2',412,0,'Yumen'),(2264,'2',412,0,'Jinchang'),(2265,'2',412,0,'Pingliang'),(2266,'2',413,0,'Kanton [Guangzhou]'),(2267,'2',413,0,'Shenzhen'),(2268,'2',413,0,'Shantou'),(2269,'2',413,0,'Zhangjiang'),(2270,'2',413,0,'Shaoguan'),(2271,'2',413,0,'Chaozhou'),(2272,'2',413,0,'Dongwan'),(2273,'2',413,0,'Foshan'),(2274,'2',413,0,'Zhongshan'),(2275,'2',413,0,'Jiangmen'),(2276,'2',413,0,'Yangjiang'),(2277,'2',413,0,'Zhaoqing'),(2278,'2',413,0,'Maoming'),(2279,'2',413,0,'Zhuhai'),(2280,'2',413,0,'Qingyuan'),(2281,'2',413,0,'Huizhou'),(2282,'2',413,0,'Meixian'),(2283,'2',413,0,'Heyuan'),(2284,'2',413,0,'Shanwei'),(2285,'2',413,0,'Jieyang'),(2286,'2',414,0,'Nanning'),(2287,'2',414,0,'Liuzhou'),(2288,'2',414,0,'Guilin'),(2289,'2',414,0,'Wuzhou'),(2290,'2',414,0,'Yulin'),(2291,'2',414,0,'Qinzhou'),(2292,'2',414,0,'Guigang'),(2293,'2',414,0,'Beihai'),(2294,'2',414,0,'Bose'),(2295,'2',415,0,'Guiyang'),(2296,'2',415,0,'Liupanshui'),(2297,'2',415,0,'Zunyi'),(2298,'2',415,0,'Anshun'),(2299,'2',415,0,'Duyun'),(2300,'2',415,0,'Kaili'),(2301,'2',416,0,'Haikou'),(2302,'2',416,0,'Sanya'),(2303,'2',417,0,'Shijiazhuang'),(2304,'2',417,0,'Tangshan'),(2305,'2',417,0,'Handan'),(2306,'2',417,0,'Zhangjiakou'),(2307,'2',417,0,'Baoding'),(2308,'2',417,0,'Qinhuangdao'),(2309,'2',417,0,'Xingtai'),(2310,'2',417,0,'Chengde'),(2311,'2',417,0,'Cangzhou'),(2312,'2',417,0,'Langfang'),(2313,'2',417,0,'Renqiu'),(2314,'2',417,0,'Hengshui'),(2315,'2',418,0,'Harbin'),(2316,'2',418,0,'Qiqihar'),(2317,'2',418,0,'Yichun'),(2318,'2',418,0,'Jixi'),(2319,'2',418,0,'Daqing'),(2320,'2',418,0,'Mudanjiang'),(2321,'2',418,0,'Hegang'),(2322,'2',418,0,'Jiamusi'),(2323,'2',418,0,'Shuangyashan'),(2324,'2',418,0,'Tieli'),(2325,'2',418,0,'Suihua'),(2326,'2',418,0,'Shangzi'),(2327,'2',418,0,'Qitaihe'),(2328,'2',418,0,'BeiÂ´an'),(2329,'2',418,0,'Acheng'),(2330,'2',418,0,'Zhaodong'),(2331,'2',418,0,'Shuangcheng'),(2332,'2',418,0,'Anda'),(2333,'2',418,0,'Hailun'),(2334,'2',418,0,'Mishan'),(2335,'2',418,0,'Fujin'),(2336,'2',419,0,'Zhengzhou'),(2337,'2',419,0,'Luoyang'),(2338,'2',419,0,'Kaifeng'),(2339,'2',419,0,'Xinxiang'),(2340,'2',419,0,'Anyang'),(2341,'2',419,0,'Pingdingshan'),(2342,'2',419,0,'Jiaozuo'),(2343,'2',419,0,'Nanyang'),(2344,'2',419,0,'Hebi'),(2345,'2',419,0,'Xuchang'),(2346,'2',419,0,'Xinyang'),(2347,'2',419,0,'Puyang'),(2348,'2',419,0,'Shangqiu'),(2349,'2',419,0,'Zhoukou'),(2350,'2',419,0,'Luohe'),(2351,'2',419,0,'Zhumadian'),(2352,'2',419,0,'Sanmenxia'),(2353,'2',419,0,'Yuzhou'),(2354,'2',420,0,'Wuhan'),(2355,'2',420,0,'Huangshi'),(2356,'2',420,0,'Xiangfan'),(2357,'2',420,0,'Yichang'),(2358,'2',420,0,'Shashi'),(2359,'2',420,0,'Shiyan'),(2360,'2',420,0,'Xiantao'),(2361,'2',420,0,'Qianjiang'),(2362,'2',420,0,'Honghu'),(2363,'2',420,0,'Ezhou'),(2364,'2',420,0,'Tianmen'),(2365,'2',420,0,'Xiaogan'),(2366,'2',420,0,'Zaoyang'),(2367,'2',420,0,'Jinmen'),(2368,'2',420,0,'Suizhou'),(2369,'2',420,0,'Xianning'),(2370,'2',420,0,'Laohekou'),(2371,'2',420,0,'Puqi'),(2372,'2',420,0,'Shishou'),(2373,'2',420,0,'Danjiangkou'),(2374,'2',420,0,'Guangshui'),(2375,'2',420,0,'Enshi'),(2376,'2',421,0,'Changsha'),(2377,'2',421,0,'Hengyang'),(2378,'2',421,0,'Xiangtan'),(2379,'2',421,0,'Zhuzhou'),(2380,'2',421,0,'Yueyang'),(2381,'2',421,0,'Changde'),(2382,'2',421,0,'Shaoyang'),(2383,'2',421,0,'Yiyang'),(2384,'2',421,0,'Chenzhou'),(2385,'2',421,0,'Lengshuijiang'),(2386,'2',421,0,'Leiyang'),(2387,'2',421,0,'Loudi'),(2388,'2',421,0,'Huaihua'),(2389,'2',421,0,'Lianyuan'),(2390,'2',421,0,'Hongjiang'),(2391,'2',421,0,'Zixing'),(2392,'2',421,0,'Liling'),(2393,'2',421,0,'Yuanjiang'),(2394,'2',422,0,'Baotou'),(2395,'2',422,0,'Hohhot'),(2396,'2',422,0,'Yakeshi'),(2397,'2',422,0,'Chifeng'),(2398,'2',422,0,'Wuhai'),(2399,'2',422,0,'Tongliao'),(2400,'2',422,0,'Hailar'),(2401,'2',422,0,'Jining'),(2402,'2',422,0,'Ulanhot'),(2403,'2',422,0,'Linhe'),(2404,'2',422,0,'Zalantun'),(2405,'2',422,0,'Manzhouli'),(2406,'2',422,0,'Xilin Hot'),(2407,'2',423,0,'Nanking [Nanjing]'),(2408,'2',423,0,'Wuxi'),(2409,'2',423,0,'Xuzhou'),(2410,'2',423,0,'Suzhou'),(2411,'2',423,0,'Changzhou'),(2412,'2',423,0,'Zhenjiang'),(2413,'2',423,0,'Lianyungang'),(2414,'2',423,0,'Nantong'),(2415,'2',423,0,'Yangzhou'),(2416,'2',423,0,'Yancheng'),(2417,'2',423,0,'Huaiyin'),(2418,'2',423,0,'Jiangyin'),(2419,'2',423,0,'Yixing'),(2420,'2',423,0,'Dongtai'),(2421,'2',423,0,'Changshu'),(2422,'2',423,0,'Danyang'),(2423,'2',423,0,'Xinghua'),(2424,'2',423,0,'Taizhou'),(2425,'2',423,0,'HuaiÂ´an'),(2426,'2',423,0,'Qidong'),(2427,'2',423,0,'Liyang'),(2428,'2',423,0,'Yizheng'),(2429,'2',423,0,'Suqian'),(2430,'2',423,0,'Kunshan'),(2431,'2',423,0,'Zhangjiagang'),(2432,'2',424,0,'Nanchang'),(2433,'2',424,0,'Pingxiang'),(2434,'2',424,0,'Jiujiang'),(2435,'2',424,0,'Jingdezhen'),(2436,'2',424,0,'Ganzhou'),(2437,'2',424,0,'Fengcheng'),(2438,'2',424,0,'Xinyu'),(2439,'2',424,0,'Yichun'),(2440,'2',424,0,'JiÂ´an'),(2441,'2',424,0,'Shangrao'),(2442,'2',424,0,'Linchuan'),(2443,'2',425,0,'Changchun'),(2444,'2',425,0,'Jilin'),(2445,'2',425,0,'Hunjiang'),(2446,'2',425,0,'Liaoyuan'),(2447,'2',425,0,'Tonghua'),(2448,'2',425,0,'Siping'),(2449,'2',425,0,'Dunhua'),(2450,'2',425,0,'Yanji'),(2451,'2',425,0,'Gongziling'),(2452,'2',425,0,'Baicheng'),(2453,'2',425,0,'Meihekou'),(2454,'2',425,0,'Fuyu'),(2455,'2',425,0,'Jiutai'),(2456,'2',425,0,'Jiaohe'),(2457,'2',425,0,'Huadian'),(2458,'2',425,0,'Taonan'),(2459,'2',425,0,'Longjing'),(2460,'2',425,0,'DaÂ´an'),(2461,'2',425,0,'Yushu'),(2462,'2',425,0,'Tumen'),(2463,'2',426,0,'Shenyang'),(2464,'2',426,0,'Dalian'),(2465,'2',426,0,'Anshan'),(2466,'2',426,0,'Fushun'),(2467,'2',426,0,'Benxi'),(2468,'2',426,0,'Fuxin'),(2469,'2',426,0,'Jinzhou'),(2470,'2',426,0,'Dandong'),(2471,'2',426,0,'Liaoyang'),(2472,'2',426,0,'Yingkou'),(2473,'2',426,0,'Panjin'),(2474,'2',426,0,'Jinxi'),(2475,'2',426,0,'Tieling'),(2476,'2',426,0,'Wafangdian'),(2477,'2',426,0,'Chaoyang'),(2478,'2',426,0,'Haicheng'),(2479,'2',426,0,'Beipiao'),(2480,'2',426,0,'Tiefa'),(2481,'2',426,0,'Kaiyuan'),(2482,'2',426,0,'Xingcheng'),(2483,'2',426,0,'Jinzhou'),(2484,'2',427,0,'Yinchuan'),(2485,'2',427,0,'Shizuishan'),(2486,'2',428,0,'Peking'),(2487,'2',428,0,'Tong Xian'),(2488,'2',429,0,'Xining'),(2489,'2',430,0,'XiÂ´an'),(2490,'2',430,0,'Xianyang'),(2491,'2',430,0,'Baoji'),(2492,'2',430,0,'Tongchuan'),(2493,'2',430,0,'Hanzhong'),(2494,'2',430,0,'Ankang'),(2495,'2',430,0,'Weinan'),(2496,'2',430,0,'YanÂ´an'),(2497,'2',431,0,'Qingdao'),(2498,'2',431,0,'Jinan'),(2499,'2',431,0,'Zibo'),(2500,'2',431,0,'Yantai'),(2501,'2',431,0,'Weifang'),(2502,'2',431,0,'Zaozhuang'),(2503,'2',431,0,'TaiÂ´an'),(2504,'2',431,0,'Linyi'),(2505,'2',431,0,'Tengzhou'),(2506,'2',431,0,'Dongying'),(2507,'2',431,0,'Xintai'),(2508,'2',431,0,'Jining'),(2509,'2',431,0,'Laiwu'),(2510,'2',431,0,'Liaocheng'),(2511,'2',431,0,'Laizhou'),(2512,'2',431,0,'Dezhou'),(2513,'2',431,0,'Heze'),(2514,'2',431,0,'Rizhao'),(2515,'2',431,0,'Liangcheng'),(2516,'2',431,0,'Jiaozhou'),(2517,'2',431,0,'Pingdu'),(2518,'2',431,0,'Longkou'),(2519,'2',431,0,'Laiyang'),(2520,'2',431,0,'Wendeng'),(2521,'2',431,0,'Binzhou'),(2522,'2',431,0,'Weihai'),(2523,'2',431,0,'Qingzhou'),(2524,'2',431,0,'Linqing'),(2525,'2',431,0,'Jiaonan'),(2526,'2',431,0,'Zhucheng'),(2527,'2',431,0,'Junan'),(2528,'2',431,0,'Pingyi'),(2529,'2',432,0,'Shanghai'),(2530,'2',433,0,'Taiyuan'),(2531,'2',433,0,'Datong'),(2532,'2',433,0,'Yangquan'),(2533,'2',433,0,'Changzhi'),(2534,'2',433,0,'Yuci'),(2535,'2',433,0,'Linfen'),(2536,'2',433,0,'Jincheng'),(2537,'2',433,0,'Yuncheng'),(2538,'2',433,0,'Xinzhou'),(2539,'2',434,0,'Chengdu'),(2540,'2',434,0,'Panzhihua'),(2541,'2',434,0,'Zigong'),(2542,'2',434,0,'Leshan'),(2543,'2',434,0,'Mianyang'),(2544,'2',434,0,'Luzhou'),(2545,'2',434,0,'Neijiang'),(2546,'2',434,0,'Yibin'),(2547,'2',434,0,'Daxian'),(2548,'2',434,0,'Deyang'),(2549,'2',434,0,'Guangyuan'),(2550,'2',434,0,'Nanchong'),(2551,'2',434,0,'Jiangyou'),(2552,'2',434,0,'Fuling'),(2553,'2',434,0,'Wanxian'),(2554,'2',434,0,'Suining'),(2555,'2',434,0,'Xichang'),(2556,'2',434,0,'Dujiangyan'),(2557,'2',434,0,'YaÂ´an'),(2558,'2',434,0,'Emeishan'),(2559,'2',434,0,'Huaying'),(2560,'2',435,0,'Tianjin'),(2561,'2',436,0,'Lhasa'),(2562,'2',437,0,'UrumtÅ¡i [ÃœrÃ¼mqi]'),(2563,'2',437,0,'Shihezi'),(2564,'2',437,0,'Qaramay'),(2565,'2',437,0,'Ghulja'),(2566,'2',437,0,'Qashqar'),(2567,'2',437,0,'Aqsu'),(2568,'2',437,0,'Hami'),(2569,'2',437,0,'Korla'),(2570,'2',437,0,'Changji'),(2571,'2',437,0,'Kuytun'),(2572,'2',438,0,'Kunming'),(2573,'2',438,0,'Gejiu'),(2574,'2',438,0,'Qujing'),(2575,'2',438,0,'Dali'),(2576,'2',438,0,'Kaiyuan'),(2577,'2',439,0,'Hangzhou'),(2578,'2',439,0,'Ningbo'),(2579,'2',439,0,'Wenzhou'),(2580,'2',439,0,'Huzhou'),(2581,'2',439,0,'Jiaxing'),(2582,'2',439,0,'Shaoxing'),(2583,'2',439,0,'Xiaoshan'),(2584,'2',439,0,'RuiÂ´an'),(2585,'2',439,0,'Zhoushan'),(2586,'2',439,0,'Jinhua'),(2587,'2',439,0,'Yuyao'),(2588,'2',439,0,'Quzhou'),(2589,'2',439,0,'Cixi'),(2590,'2',439,0,'Haining'),(2591,'2',439,0,'Linhai'),(2592,'2',439,0,'Huangyan'),(2593,'2',440,0,'Abidjan'),(2594,'2',441,0,'BouakÃ©'),(2595,'2',442,0,'Daloa'),(2596,'2',443,0,'Korhogo'),(2597,'2',444,0,'Yamoussoukro'),(2598,'2',445,0,'YaoundÃ©'),(2599,'2',445,0,'Tours'),(2600,'2',445,0,'OrlÃ©ans'),(2601,'2',446,0,'Maroua'),(2602,'2',447,0,'Douala'),(2603,'2',447,0,'Nkongsamba'),(2604,'2',448,0,'Le-Cap-HaÃ¯tien'),(2605,'2',448,0,'Garoua'),(2606,'2',449,0,'Bamenda'),(2607,'2',450,0,'Port-au-Prince'),(2608,'2',450,0,'Carrefour'),(2609,'2',450,0,'Delmas'),(2610,'2',450,0,'Bafoussam'),(2611,'2',451,0,'Kikwit'),(2612,'2',452,0,'Matadi'),(2613,'2',452,0,'Boma'),(2614,'2',453,0,'Mbuji-Mayi'),(2615,'2',453,0,'Mwene-Ditu'),(2616,'2',454,0,'Mbandaka'),(2617,'2',455,0,'Kisangani'),(2618,'2',456,0,'Kinshasa'),(2619,'2',457,0,'Butembo'),(2620,'2',457,0,'Goma'),(2621,'2',458,0,'Lubumbashi'),(2622,'2',458,0,'Kolwezi'),(2623,'2',458,0,'Likasi'),(2624,'2',458,0,'Kalemie'),(2625,'2',459,0,'Bukavu'),(2626,'2',459,0,'Uvira'),(2627,'2',460,0,'Kananga'),(2628,'2',460,0,'Tshikapa'),(2629,'2',461,0,'Brazzaville'),(2630,'2',462,0,'Pointe-Noire'),(2631,'2',463,0,'Avarua'),(2632,'2',464,0,'MedellÃ­n'),(2633,'2',464,0,'Bello'),(2634,'2',464,0,'ItagÃ¼Ã­'),(2635,'2',464,0,'Envigado'),(2636,'2',465,0,'Barranquilla'),(2637,'2',465,0,'Soledad'),(2638,'2',466,0,'Cartagena'),(2639,'2',466,0,'Ciudad Guayana'),(2640,'2',466,0,'Ciudad BolÃ­var'),(2641,'2',467,0,'Tunja'),(2642,'2',467,0,'Sogamoso'),(2643,'2',468,0,'Manizales'),(2644,'2',469,0,'Florencia'),(2645,'2',470,0,'PopayÃ¡n'),(2646,'2',471,0,'CÃ³rdoba'),(2647,'2',471,0,'RÃ­o Cuarto'),(2648,'2',471,0,'MonterÃ­a'),(2649,'2',472,0,'Valledupar'),(2650,'2',473,0,'Soacha'),(2651,'2',473,0,'Girardot'),(2652,'2',474,0,'Neiva'),(2653,'2',475,0,'Maicao'),(2654,'2',476,0,'Santa Marta'),(2655,'2',477,0,'Villavicencio'),(2656,'2',478,0,'Pasto'),(2657,'2',479,0,'CÃºcuta'),(2658,'2',480,0,'Armenia'),(2659,'2',481,0,'Pereira'),(2660,'2',481,0,'Dos Quebradas'),(2661,'2',482,0,'SantafÃ© de BogotÃ¡'),(2662,'2',483,0,'Bucaramanga'),(2663,'2',483,0,'Floridablanca'),(2664,'2',483,0,'Barrancabermeja'),(2665,'2',483,0,'Giron'),(2666,'2',484,0,'Sincelejo'),(2667,'2',484,0,'CumanÃ¡'),(2668,'2',484,0,'CarÃºpano'),(2669,'2',485,0,'IbaguÃ©'),(2670,'2',486,0,'Cali'),(2671,'2',486,0,'Palmira'),(2672,'2',486,0,'Buenaventura'),(2673,'2',486,0,'TuluÃ¡'),(2674,'2',486,0,'Cartago'),(2675,'2',486,0,'Buga'),(2676,'2',487,0,'Moroni'),(2677,'2',488,0,'Praia'),(2678,'2',489,0,'San JosÃ©'),(2679,'2',490,0,'CamagÃ¼ey'),(2680,'2',491,0,'Ciego de Ãvila'),(2681,'2',492,0,'Cienfuegos'),(2682,'2',493,0,'Bayamo'),(2683,'2',493,0,'Manzanillo'),(2684,'2',494,0,'GuantÃ¡namo'),(2685,'2',495,0,'HolguÃ­n'),(2686,'2',496,0,'La Habana'),(2687,'2',497,0,'Victoria de las Tunas'),(2688,'2',498,0,'Matanzas'),(2689,'2',499,0,'Pinar del RÃ­o'),(2690,'2',500,0,'Sancti-SpÃ­ritus'),(2691,'2',501,0,'Santiago de Cuba'),(2692,'2',502,0,'Santa Clara'),(2693,'2',503,0,'South Hill'),(2694,'2',503,0,'The Valley'),(2695,'2',503,0,'Oranjestad'),(2696,'2',503,0,'Douglas'),(2697,'2',503,0,'Gibraltar'),(2698,'2',503,0,'Tamuning'),(2699,'2',503,0,'AgaÃ±a'),(2700,'2',503,0,'Flying Fish Cove'),(2701,'2',503,0,'Monte-Carlo'),(2702,'2',503,0,'Monaco-Ville'),(2703,'2',503,0,'Yangor'),(2704,'2',503,0,'Yaren'),(2705,'2',503,0,'Alofi'),(2706,'2',503,0,'Kingston'),(2707,'2',503,0,'Adamstown'),(2708,'2',503,0,'Singapore'),(2709,'2',503,0,'NoumÃ©a'),(2710,'2',503,0,'CittÃ  del Vaticano'),(2711,'2',504,0,'George Town'),(2712,'2',505,0,'Limassol'),(2713,'2',506,0,'Nicosia'),(2714,'2',507,0,'Praha'),(2715,'2',508,0,'CeskÃ© Budejovice'),(2716,'2',509,0,'Brno'),(2717,'2',510,0,'Liberec'),(2718,'2',510,0,'ÃšstÃ­ nad Labem'),(2719,'2',511,0,'Ostrava'),(2720,'2',511,0,'Olomouc'),(2721,'2',512,0,'Hradec KrÃ¡lovÃ©'),(2722,'2',512,0,'Pardubice'),(2723,'2',513,0,'Plzen'),(2724,'2',514,0,'Halle/Saale'),(2725,'2',514,0,'Magdeburg'),(2726,'2',515,0,'Stuttgart'),(2727,'2',515,0,'Mannheim'),(2728,'2',515,0,'Karlsruhe'),(2729,'2',515,0,'Freiburg im Breisgau'),(2730,'2',515,0,'Heidelberg'),(2731,'2',515,0,'Heilbronn'),(2732,'2',515,0,'Pforzheim'),(2733,'2',515,0,'Ulm'),(2734,'2',515,0,'Reutlingen'),(2735,'2',515,0,'Esslingen am Neckar'),(2736,'2',516,0,'Munich [MÃ¼nchen]'),(2737,'2',516,0,'NÃ¼rnberg'),(2738,'2',516,0,'Augsburg'),(2739,'2',516,0,'WÃ¼rzburg'),(2740,'2',516,0,'Regensburg'),(2741,'2',516,0,'Ingolstadt'),(2742,'2',516,0,'FÃ¼rth'),(2743,'2',516,0,'Erlangen'),(2744,'2',517,0,'Berlin'),(2745,'2',518,0,'Potsdam'),(2746,'2',518,0,'Cottbus'),(2747,'2',519,0,'Bremen'),(2748,'2',519,0,'Bremerhaven'),(2749,'2',520,0,'Hamburg'),(2750,'2',521,0,'Frankfurt am Main'),(2751,'2',521,0,'Wiesbaden'),(2752,'2',521,0,'Kassel'),(2753,'2',521,0,'Darmstadt'),(2754,'2',521,0,'Offenbach am Main'),(2755,'2',522,0,'Rostock'),(2756,'2',522,0,'Schwerin'),(2757,'2',523,0,'Hannover'),(2758,'2',523,0,'Braunschweig'),(2759,'2',523,0,'OsnabrÃ¼ck'),(2760,'2',523,0,'Oldenburg'),(2761,'2',523,0,'GÃ¶ttingen'),(2762,'2',523,0,'Wolfsburg'),(2763,'2',523,0,'Salzgitter'),(2764,'2',523,0,'Hildesheim'),(2765,'2',524,0,'KÃ¶ln'),(2766,'2',524,0,'Essen'),(2767,'2',524,0,'Dortmund'),(2768,'2',524,0,'DÃ¼sseldorf'),(2769,'2',524,0,'Duisburg'),(2770,'2',524,0,'Bochum'),(2771,'2',524,0,'Wuppertal'),(2772,'2',524,0,'Bielefeld'),(2773,'2',524,0,'Bonn'),(2774,'2',524,0,'Gelsenkirchen'),(2775,'2',524,0,'MÃ¼nster'),(2776,'2',524,0,'MÃ¶nchengladbach'),(2777,'2',524,0,'Aachen'),(2778,'2',524,0,'Krefeld'),(2779,'2',524,0,'Oberhausen'),(2780,'2',524,0,'Hagen'),(2781,'2',524,0,'Hamm'),(2782,'2',524,0,'Herne'),(2783,'2',524,0,'MÃ¼lheim an der Ruhr'),(2784,'2',524,0,'Solingen'),(2785,'2',524,0,'Leverkusen'),(2786,'2',524,0,'Neuss'),(2787,'2',524,0,'Paderborn'),(2788,'2',524,0,'Recklinghausen'),(2789,'2',524,0,'Bottrop'),(2790,'2',524,0,'Remscheid'),(2791,'2',524,0,'Siegen'),(2792,'2',524,0,'Moers'),(2793,'2',524,0,'Bergisch Gladbach'),(2794,'2',524,0,'Witten'),(2795,'2',524,0,'Iserlohn'),(2796,'2',524,0,'GÃ¼tersloh'),(2797,'2',524,0,'Marl'),(2798,'2',524,0,'LÃ¼nen'),(2799,'2',524,0,'DÃ¼ren'),(2800,'2',524,0,'Ratingen'),(2801,'2',524,0,'Velbert'),(2802,'2',525,0,'Mainz'),(2803,'2',525,0,'Ludwigshafen am Rhein'),(2804,'2',525,0,'Koblenz'),(2805,'2',525,0,'Kaiserslautern'),(2806,'2',525,0,'Trier'),(2807,'2',526,0,'SaarbrÃ¼cken'),(2808,'2',527,0,'Leipzig'),(2809,'2',527,0,'Dresden'),(2810,'2',527,0,'Chemnitz'),(2811,'2',527,0,'Zwickau'),(2812,'2',528,0,'Kiel'),(2813,'2',528,0,'LÃ¼beck'),(2814,'2',529,0,'Erfurt'),(2815,'2',529,0,'Gera'),(2816,'2',529,0,'Jena'),(2817,'2',530,0,'Djibouti'),(2818,'2',531,0,'Roseau'),(2819,'2',531,0,'Saint GeorgeÂ´s'),(2820,'2',531,0,'Kingstown'),(2821,'2',532,0,'Ã…rhus'),(2822,'2',533,0,'Frederiksberg'),(2823,'2',534,0,'Odense'),(2824,'2',535,0,'KÃ¸benhavn'),(2825,'2',536,0,'Aalborg'),(2826,'2',537,0,'Santo Domingo de GuzmÃ¡n'),(2827,'2',538,0,'San Francisco de MacorÃ­s'),(2828,'2',539,0,'La Romana'),(2829,'2',540,0,'San Felipe de Puerto Plata'),(2830,'2',541,0,'San Pedro de MacorÃ­s'),(2831,'2',542,0,'Santiago de Chile'),(2832,'2',542,0,'Puente Alto'),(2833,'2',542,0,'San Bernardo'),(2834,'2',542,0,'Melipilla'),(2835,'2',542,0,'Santiago de los Caballeros'),(2836,'2',543,0,'Alger'),(2837,'2',544,0,'Annaba'),(2838,'2',545,0,'Batna'),(2839,'2',546,0,'BÃ©char'),(2840,'2',547,0,'BÃ©jaÃ¯a'),(2841,'2',548,0,'Biskra'),(2842,'2',549,0,'Blida (el-Boulaida)'),(2843,'2',550,0,'Ech-Chleff (el-Asnam)'),(2844,'2',551,0,'Constantine'),(2845,'2',552,0,'GhardaÃ¯a'),(2846,'2',553,0,'Mostaganem'),(2847,'2',554,0,'Oran'),(2848,'2',555,0,'SÃ©tif'),(2849,'2',556,0,'Sidi Bel AbbÃ¨s'),(2850,'2',557,0,'Skikda'),(2851,'2',558,0,'TÃ©bessa'),(2852,'2',559,0,'Tiaret'),(2853,'2',560,0,'Tlemcen (Tilimsen)'),(2854,'2',561,0,'Cuenca'),(2855,'2',562,0,'RÃ­obamba'),(2856,'2',563,0,'Machala'),(2857,'2',564,0,'Esmeraldas'),(2858,'2',565,0,'Guayaquil'),(2859,'2',565,0,'Duran [Eloy Alfaro]'),(2860,'2',565,0,'Milagro'),(2861,'2',566,0,'Ibarra'),(2862,'2',567,0,'Loja'),(2863,'2',568,0,'Quevedo'),(2864,'2',569,0,'Portoviejo'),(2865,'2',569,0,'Manta'),(2866,'2',570,0,'Quito'),(2867,'2',570,0,'Santo Domingo de los Colorados'),(2868,'2',571,0,'Ambato'),(2869,'2',572,0,'Kafr al-Dawwar'),(2870,'2',572,0,'Damanhur'),(2871,'2',573,0,'al-Mansura'),(2872,'2',573,0,'Mit Ghamr'),(2873,'2',573,0,'Talkha'),(2874,'2',574,0,'al-Faiyum'),(2875,'2',575,0,'al-Mahallat al-Kubra'),(2876,'2',575,0,'Tanta'),(2877,'2',576,0,'Shibin al-Kawm'),(2878,'2',577,0,'al-Minya'),(2879,'2',577,0,'Mallawi'),(2880,'2',578,0,'Shubra al-Khayma'),(2881,'2',578,0,'Bahtim'),(2882,'2',578,0,'Banha'),(2883,'2',578,0,'Qalyub'),(2884,'2',579,0,'Zagazig'),(2885,'2',579,0,'Bilbays'),(2886,'2',579,0,'al-Dammam'),(2887,'2',579,0,'al-Hufuf'),(2888,'2',579,0,'al-Mubarraz'),(2889,'2',579,0,'al-Khubar'),(2890,'2',579,0,'Jubayl'),(2891,'2',579,0,'Hafar al-Batin'),(2892,'2',579,0,'al-Tuqba'),(2893,'2',579,0,'al-Qatif'),(2894,'2',580,0,'Alexandria'),(2895,'2',581,0,'Assuan'),(2896,'2',582,0,'Asyut'),(2897,'2',583,0,'Bani Suwayf'),(2898,'2',584,0,'Giza'),(2899,'2',584,0,'Bulaq al-Dakrur'),(2900,'2',584,0,'Warraq al-Arab'),(2901,'2',584,0,'al-Hawamidiya'),(2902,'2',585,0,'Ismailia'),(2903,'2',586,0,'Kafr al-Shaykh'),(2904,'2',586,0,'Disuq'),(2905,'2',587,0,'Cairo'),(2906,'2',588,0,'Luxor'),(2907,'2',589,0,'Port Said'),(2908,'2',590,0,'Qina'),(2909,'2',590,0,'Idfu'),(2910,'2',591,0,'Sawhaj'),(2911,'2',591,0,'Jirja'),(2912,'2',592,0,'al-Arish'),(2913,'2',593,0,'Suez'),(2914,'2',594,0,'Asmara'),(2915,'2',595,0,'El-AaiÃºn'),(2916,'2',596,0,'Sevilla'),(2917,'2',596,0,'MÃ¡laga'),(2918,'2',596,0,'CÃ³rdoba'),(2919,'2',596,0,'Granada'),(2920,'2',596,0,'Jerez de la Frontera'),(2921,'2',596,0,'AlmerÃ­a'),(2922,'2',596,0,'CÃ¡diz'),(2923,'2',596,0,'Huelva'),(2924,'2',596,0,'JaÃ©n'),(2925,'2',596,0,'Algeciras'),(2926,'2',596,0,'Marbella'),(2927,'2',596,0,'Dos Hermanas'),(2928,'2',597,0,'Zaragoza'),(2929,'2',598,0,'GijÃ³n'),(2930,'2',598,0,'Oviedo'),(2931,'2',599,0,'Palma de Mallorca'),(2932,'2',600,0,'Bilbao'),(2933,'2',600,0,'Vitoria-Gasteiz'),(2934,'2',600,0,'Donostia-San SebastiÃ¡n'),(2935,'2',600,0,'Barakaldo'),(2936,'2',601,0,'Las Palmas de Gran Canaria'),(2937,'2',601,0,'Santa Cruz de Tenerife'),(2938,'2',601,0,'[San CristÃ³bal de] la Laguna'),(2939,'2',602,0,'Santander'),(2940,'2',603,0,'Valladolid'),(2941,'2',603,0,'Burgos'),(2942,'2',603,0,'Salamanca'),(2943,'2',603,0,'LeÃ³n'),(2944,'2',604,0,'Badajoz'),(2945,'2',605,0,'Vigo'),(2946,'2',605,0,'A CoruÃ±a (La CoruÃ±a)'),(2947,'2',605,0,'Ourense (Orense)'),(2948,'2',605,0,'Santiago de Compostela'),(2949,'2',606,0,'Albacete'),(2950,'2',607,0,'Barcelona'),(2951,'2',607,0,'LÂ´Hospitalet de Llobregat'),(2952,'2',607,0,'Badalona'),(2953,'2',607,0,'Sabadell'),(2954,'2',607,0,'Terrassa'),(2955,'2',607,0,'Santa Coloma de Gramenet'),(2956,'2',607,0,'Tarragona'),(2957,'2',607,0,'Lleida (LÃ©rida)'),(2958,'2',607,0,'MatarÃ³'),(2959,'2',608,0,'La Rioja'),(2960,'2',608,0,'LogroÃ±o'),(2961,'2',609,0,'Madrid'),(2962,'2',609,0,'MÃ³stoles'),(2963,'2',609,0,'LeganÃ©s'),(2964,'2',609,0,'Fuenlabrada'),(2965,'2',609,0,'AlcalÃ¡ de Henares'),(2966,'2',609,0,'Getafe'),(2967,'2',609,0,'AlcorcÃ³n'),(2968,'2',609,0,'TorrejÃ³n de Ardoz'),(2969,'2',610,0,'Murcia'),(2970,'2',610,0,'Cartagena'),(2971,'2',611,0,'Pamplona [IruÃ±a]'),(2972,'2',612,0,'Valencia'),(2973,'2',612,0,'Alicante [Alacant]'),(2974,'2',612,0,'Elche [Elx]'),(2975,'2',612,0,'CastellÃ³n de la Plana [Castel'),(2976,'2',613,0,'Tallinn'),(2977,'2',614,0,'Tartu'),(2978,'2',615,0,'Addis Abeba'),(2979,'2',616,0,'Gonder'),(2980,'2',616,0,'Dese'),(2981,'2',616,0,'Bahir Dar'),(2982,'2',617,0,'Dire Dawa'),(2983,'2',618,0,'Nazret'),(2984,'2',619,0,'Mekele'),(2985,'2',620,0,'Helsinki [Helsingfors]'),(2986,'2',620,0,'Espoo'),(2987,'2',620,0,'Vantaa'),(2988,'2',621,0,'Lahti'),(2989,'2',622,0,'Tampere'),(2990,'2',623,0,'Oulu'),(2991,'2',624,0,'Turku [Ã…bo]'),(2992,'2',625,0,'Suva'),(2993,'2',625,0,'Nyeri'),(2994,'2',625,0,'Kathmandu'),(2995,'2',625,0,'Lalitapur'),(2996,'2',625,0,'Birgunj'),(2997,'2',625,0,'San Lorenzo'),(2998,'2',625,0,'LambarÃ©'),(2999,'2',625,0,'Fernando de la Mora'),(3000,'2',625,0,'Kabwe'),(3001,'2',625,0,'Kandy'),(3002,'2',625,0,'Kampala'),(3003,'2',626,0,'Stanley'),(3004,'2',627,0,'Strasbourg'),(3005,'2',627,0,'Mulhouse'),(3006,'2',628,0,'Bordeaux'),(3007,'2',629,0,'Clermont-Ferrand'),(3008,'2',630,0,'Paris'),(3009,'2',630,0,'Boulogne-Billancourt'),(3010,'2',630,0,'Argenteuil'),(3011,'2',630,0,'Montreuil'),(3012,'2',631,0,'Caen'),(3013,'2',632,0,'Dijon'),(3014,'2',633,0,'St-Ã‰tienne'),(3015,'2',633,0,'Brest'),(3016,'2',634,0,'YaoundÃ©'),(3017,'2',634,0,'Tours'),(3018,'2',634,0,'OrlÃ©ans'),(3019,'2',635,0,'Le Havre'),(3020,'2',636,0,'BesanÃ§on'),(3021,'2',637,0,'Rennes'),(3022,'2',637,0,'Rouen'),(3023,'2',638,0,'Montpellier'),(3024,'2',638,0,'NÃ®mes'),(3025,'2',638,0,'Perpignan'),(3026,'2',639,0,'Limoges'),(3027,'2',640,0,'Metz'),(3028,'2',640,0,'Nancy'),(3029,'2',641,0,'Toulouse'),(3030,'2',642,0,'Reims'),(3031,'2',642,0,'Roubaix'),(3032,'2',642,0,'Tourcoing'),(3033,'2',643,0,'Nantes'),(3034,'2',643,0,'Angers'),(3035,'2',643,0,'Le Mans'),(3036,'2',644,0,'Amiens'),(3037,'2',645,0,'Marseille'),(3038,'2',645,0,'Nice'),(3039,'2',645,0,'Toulon'),(3040,'2',645,0,'Aix-en-Provence'),(3041,'2',646,0,'Lyon'),(3042,'2',646,0,'Lille'),(3043,'2',646,0,'Grenoble'),(3044,'2',646,0,'Villeurbanne'),(3045,'2',647,0,'TÃ³rshavn'),(3046,'2',648,0,'Weno'),(3047,'2',649,0,'Palikir'),(3048,'2',650,0,'Libreville'),(3049,'2',651,0,'South Hill'),(3050,'2',651,0,'The Valley'),(3051,'2',651,0,'Oranjestad'),(3052,'2',651,0,'Douglas'),(3053,'2',651,0,'Gibraltar'),(3054,'2',651,0,'Tamuning'),(3055,'2',651,0,'AgaÃ±a'),(3056,'2',651,0,'Flying Fish Cove'),(3057,'2',651,0,'Monte-Carlo'),(3058,'2',651,0,'Monaco-Ville'),(3059,'2',651,0,'Yangor'),(3060,'2',651,0,'Yaren'),(3061,'2',651,0,'Alofi'),(3062,'2',651,0,'Kingston'),(3063,'2',651,0,'Adamstown'),(3064,'2',651,0,'Singapore'),(3065,'2',651,0,'NoumÃ©a'),(3066,'2',651,0,'CittÃ  del Vaticano'),(3067,'2',652,0,'London'),(3068,'2',652,0,'Birmingham'),(3069,'2',652,0,'Liverpool'),(3070,'2',652,0,'Sheffield'),(3071,'2',652,0,'Manchester'),(3072,'2',652,0,'Leeds'),(3073,'2',652,0,'Bristol'),(3074,'2',652,0,'Coventry'),(3075,'2',652,0,'Leicester'),(3076,'2',652,0,'Bradford'),(3077,'2',652,0,'Nottingham'),(3078,'2',652,0,'Kingston upon Hull'),(3079,'2',652,0,'Plymouth'),(3080,'2',652,0,'Stoke-on-Trent'),(3081,'2',652,0,'Wolverhampton'),(3082,'2',652,0,'Derby'),(3083,'2',652,0,'Southampton'),(3084,'2',652,0,'Northampton'),(3085,'2',652,0,'Dudley'),(3086,'2',652,0,'Portsmouth'),(3087,'2',652,0,'Newcastle upon Tyne'),(3088,'2',652,0,'Sunderland'),(3089,'2',652,0,'Luton'),(3090,'2',652,0,'Swindon'),(3091,'2',652,0,'Southend-on-Sea'),(3092,'2',652,0,'Walsall'),(3093,'2',652,0,'Bournemouth'),(3094,'2',652,0,'Peterborough'),(3095,'2',652,0,'Brighton'),(3096,'2',652,0,'Blackpool'),(3097,'2',652,0,'West Bromwich'),(3098,'2',652,0,'Reading'),(3099,'2',652,0,'Oldbury/Smethwick (Warley)'),(3100,'2',652,0,'Middlesbrough'),(3101,'2',652,0,'Huddersfield'),(3102,'2',652,0,'Oxford'),(3103,'2',652,0,'Poole'),(3104,'2',652,0,'Bolton'),(3105,'2',652,0,'Blackburn'),(3106,'2',652,0,'Preston'),(3107,'2',652,0,'Stockport'),(3108,'2',652,0,'Norwich'),(3109,'2',652,0,'Rotherham'),(3110,'2',652,0,'Cambridge'),(3111,'2',652,0,'Watford'),(3112,'2',652,0,'Ipswich'),(3113,'2',652,0,'Slough'),(3114,'2',652,0,'Exeter'),(3115,'2',652,0,'Cheltenham'),(3116,'2',652,0,'Gloucester'),(3117,'2',652,0,'Saint Helens'),(3118,'2',652,0,'Sutton Coldfield'),(3119,'2',652,0,'York'),(3120,'2',652,0,'Oldham'),(3121,'2',652,0,'Basildon'),(3122,'2',652,0,'Worthing'),(3123,'2',652,0,'Chelmsford'),(3124,'2',652,0,'Colchester'),(3125,'2',652,0,'Crawley'),(3126,'2',652,0,'Gillingham'),(3127,'2',652,0,'Solihull'),(3128,'2',652,0,'Rochdale'),(3129,'2',652,0,'Birkenhead'),(3130,'2',652,0,'Worcester'),(3131,'2',652,0,'Hartlepool'),(3132,'2',652,0,'Halifax'),(3133,'2',652,0,'Woking/Byfleet'),(3134,'2',652,0,'Southport'),(3135,'2',652,0,'Maidstone'),(3136,'2',652,0,'Eastbourne'),(3137,'2',652,0,'Grimsby'),(3138,'2',653,0,'Saint Helier'),(3139,'2',654,0,'Belfast'),(3140,'2',655,0,'Glasgow'),(3141,'2',655,0,'Edinburgh'),(3142,'2',655,0,'Aberdeen'),(3143,'2',655,0,'Dundee'),(3144,'2',656,0,'Cardiff'),(3145,'2',656,0,'Swansea'),(3146,'2',656,0,'Newport'),(3147,'2',657,0,'Sohumi'),(3148,'2',658,0,'Batumi'),(3149,'2',659,0,'Kutaisi'),(3150,'2',660,0,'Rustavi'),(3151,'2',661,0,'Tbilisi'),(3152,'2',662,0,'Kumasi'),(3153,'2',663,0,'Accra'),(3154,'2',663,0,'Tema'),(3155,'2',664,0,'Tamale'),(3156,'2',664,0,'Jaffna'),(3157,'2',665,0,'Sekondi-Takoradi'),(3158,'2',665,0,'Pokhara'),(3159,'2',665,0,'Freetown'),(3160,'2',665,0,'Colombo'),(3161,'2',665,0,'Dehiwala'),(3162,'2',665,0,'Moratuwa'),(3163,'2',665,0,'Sri Jayawardenepura Kotte'),(3164,'2',665,0,'Negombo'),(3165,'2',666,0,'South Hill'),(3166,'2',666,0,'The Valley'),(3167,'2',666,0,'Oranjestad'),(3168,'2',666,0,'Douglas'),(3169,'2',666,0,'Gibraltar'),(3170,'2',666,0,'Tamuning'),(3171,'2',666,0,'AgaÃ±a'),(3172,'2',666,0,'Flying Fish Cove'),(3173,'2',666,0,'Monte-Carlo'),(3174,'2',666,0,'Monaco-Ville'),(3175,'2',666,0,'Yangor'),(3176,'2',666,0,'Yaren'),(3177,'2',666,0,'Alofi'),(3178,'2',666,0,'Kingston'),(3179,'2',666,0,'Adamstown'),(3180,'2',666,0,'Singapore'),(3181,'2',666,0,'NoumÃ©a'),(3182,'2',666,0,'CittÃ  del Vaticano'),(3183,'2',667,0,'Conakry'),(3184,'2',668,0,'Basse-Terre'),(3185,'2',669,0,'Les Abymes'),(3186,'2',670,0,'Banjul'),(3187,'2',671,0,'Serekunda'),(3188,'2',672,0,'Bissau'),(3189,'2',673,0,'Malabo'),(3190,'2',674,0,'Athenai'),(3191,'2',674,0,'Pireus'),(3192,'2',674,0,'Peristerion'),(3193,'2',674,0,'Kallithea'),(3194,'2',675,0,'Thessaloniki'),(3195,'2',676,0,'Herakleion'),(3196,'2',677,0,'Larisa'),(3197,'2',678,0,'Patras'),(3198,'2',679,0,'Roseau'),(3199,'2',679,0,'Saint GeorgeÂ´s'),(3200,'2',679,0,'Kingstown'),(3201,'2',680,0,'Nuuk'),(3202,'2',681,0,'Ciudad de Guatemala'),(3203,'2',681,0,'Mixco'),(3204,'2',681,0,'Villa Nueva'),(3205,'2',682,0,'Quetzaltenango'),(3206,'2',683,0,'Cayenne'),(3207,'2',684,0,'South Hill'),(3208,'2',684,0,'The Valley'),(3209,'2',684,0,'Oranjestad'),(3210,'2',684,0,'Douglas'),(3211,'2',684,0,'Gibraltar'),(3212,'2',684,0,'Tamuning'),(3213,'2',684,0,'AgaÃ±a'),(3214,'2',684,0,'Flying Fish Cove'),(3215,'2',684,0,'Monte-Carlo'),(3216,'2',684,0,'Monaco-Ville'),(3217,'2',684,0,'Yangor'),(3218,'2',684,0,'Yaren'),(3219,'2',684,0,'Alofi'),(3220,'2',684,0,'Kingston'),(3221,'2',684,0,'Adamstown'),(3222,'2',684,0,'Singapore'),(3223,'2',684,0,'NoumÃ©a'),(3224,'2',684,0,'CittÃ  del Vaticano'),(3225,'2',685,0,'Georgetown'),(3226,'2',686,0,'Victoria'),(3227,'2',687,0,'Kowloon and New Kowloon'),(3228,'2',688,0,'La Ceiba'),(3229,'2',689,0,'San Pedro Sula'),(3230,'2',690,0,'Tegucigalpa'),(3231,'2',691,0,'Zagreb'),(3232,'2',692,0,'Osijek'),(3233,'2',693,0,'Rijeka'),(3234,'2',694,0,'Split'),(3235,'2',695,0,'Le-Cap-HaÃ¯tien'),(3236,'2',695,0,'Garoua'),(3237,'2',696,0,'Port-au-Prince'),(3238,'2',696,0,'Carrefour'),(3239,'2',696,0,'Delmas'),(3240,'2',696,0,'Bafoussam'),(3241,'2',697,0,'PÃ©cs'),(3242,'2',698,0,'KecskemÃ©t'),(3243,'2',699,0,'Miskolc'),(3244,'2',700,0,'Budapest'),(3245,'2',701,0,'Szeged'),(3246,'2',702,0,'SzÃ©kesfehÃ©rvÃ¡r'),(3247,'2',703,0,'GyÃ¶r'),(3248,'2',704,0,'Debrecen'),(3249,'2',705,0,'NyiregyhÃ¡za'),(3250,'2',706,0,'Banda Aceh'),(3251,'2',706,0,'Lhokseumawe'),(3252,'2',707,0,'Denpasar'),(3253,'2',708,0,'Bengkulu'),(3254,'2',709,0,'Semarang'),(3255,'2',709,0,'Surakarta'),(3256,'2',709,0,'Pekalongan'),(3257,'2',709,0,'Tegal'),(3258,'2',709,0,'Cilacap'),(3259,'2',709,0,'Purwokerto'),(3260,'2',709,0,'Magelang'),(3261,'2',709,0,'Pemalang'),(3262,'2',709,0,'Klaten'),(3263,'2',709,0,'Salatiga'),(3264,'2',709,0,'Kudus'),(3265,'2',710,0,'Surabaya'),(3266,'2',710,0,'Malang'),(3267,'2',710,0,'Kediri'),(3268,'2',710,0,'Jember'),(3269,'2',710,0,'Madiun'),(3270,'2',710,0,'Pasuruan'),(3271,'2',710,0,'Waru'),(3272,'2',710,0,'Blitar'),(3273,'2',710,0,'Probolinggo'),(3274,'2',710,0,'Taman'),(3275,'2',710,0,'Mojokerto'),(3276,'2',710,0,'Jombang'),(3277,'2',710,0,'Banyuwangi'),(3278,'2',711,0,'Jakarta'),(3279,'2',712,0,'Jambi'),(3280,'2',713,0,'Pontianak'),(3281,'2',714,0,'Banjarmasin'),(3282,'2',715,0,'Palangka Raya'),(3283,'2',716,0,'Samarinda'),(3284,'2',716,0,'Balikpapan'),(3285,'2',717,0,'Bandar Lampung'),(3286,'2',718,0,'Ambon'),(3287,'2',719,0,'Mataram'),(3288,'2',720,0,'Kupang'),(3289,'2',721,0,'Pekan Baru'),(3290,'2',721,0,'Batam'),(3291,'2',721,0,'Tanjung Pinang'),(3292,'2',722,0,'Ujung Pandang'),(3293,'2',723,0,'Palu'),(3294,'2',724,0,'Kendari'),(3295,'2',725,0,'Manado'),(3296,'2',725,0,'Gorontalo'),(3297,'2',726,0,'Padang'),(3298,'2',727,0,'Palembang'),(3299,'2',727,0,'Pangkal Pinang'),(3300,'2',728,0,'Medan'),(3301,'2',728,0,'Pematang Siantar'),(3302,'2',728,0,'Tebing Tinggi'),(3303,'2',728,0,'Percut Sei Tuan'),(3304,'2',728,0,'Binjai'),(3305,'2',728,0,'Sunggal'),(3306,'2',728,0,'Padang Sidempuan'),(3307,'2',729,0,'Jaya Pura'),(3308,'2',730,0,'Bandung'),(3309,'2',730,0,'Tangerang'),(3310,'2',730,0,'Bekasi'),(3311,'2',730,0,'Depok'),(3312,'2',730,0,'Cimahi'),(3313,'2',730,0,'Bogor'),(3314,'2',730,0,'Ciputat'),(3315,'2',730,0,'Pondokgede'),(3316,'2',730,0,'Cirebon'),(3317,'2',730,0,'Cimanggis'),(3318,'2',730,0,'Ciomas'),(3319,'2',730,0,'Tasikmalaya'),(3320,'2',730,0,'Karawang'),(3321,'2',730,0,'Sukabumi'),(3322,'2',730,0,'Serang'),(3323,'2',730,0,'Cilegon'),(3324,'2',730,0,'Cianjur'),(3325,'2',730,0,'Ciparay'),(3326,'2',730,0,'Citeureup'),(3327,'2',730,0,'Cibinong'),(3328,'2',730,0,'Purwakarta'),(3329,'2',730,0,'Garut'),(3330,'2',730,0,'Majalaya'),(3331,'2',730,0,'Pondok Aren'),(3332,'2',730,0,'Sawangan'),(3333,'2',731,0,'Yogyakarta'),(3334,'2',731,0,'Depok'),(3335,'2',732,0,'Hyderabad'),(3336,'2',732,0,'Vishakhapatnam'),(3337,'2',732,0,'Vijayawada'),(3338,'2',732,0,'Guntur'),(3339,'2',732,0,'Warangal'),(3340,'2',732,0,'Rajahmundry'),(3341,'2',732,0,'Nellore'),(3342,'2',732,0,'Kakinada'),(3343,'2',732,0,'Nizamabad'),(3344,'2',732,0,'Kurnool'),(3345,'2',732,0,'Ramagundam'),(3346,'2',732,0,'Eluru'),(3347,'2',732,0,'Kukatpalle'),(3348,'2',732,0,'Anantapur'),(3349,'2',732,0,'Tirupati'),(3350,'2',732,0,'Secunderabad'),(3351,'2',732,0,'Vizianagaram'),(3352,'2',732,0,'Machilipatnam (Masulipatam)'),(3353,'2',732,0,'Lalbahadur Nagar'),(3354,'2',732,0,'Karimnagar'),(3355,'2',732,0,'Tenali'),(3356,'2',732,0,'Adoni'),(3357,'2',732,0,'Proddatur'),(3358,'2',732,0,'Chittoor'),(3359,'2',732,0,'Khammam'),(3360,'2',732,0,'Malkajgiri'),(3361,'2',732,0,'Cuddapah'),(3362,'2',732,0,'Bhimavaram'),(3363,'2',732,0,'Nandyal'),(3364,'2',732,0,'Mahbubnagar'),(3365,'2',732,0,'Guntakal'),(3366,'2',732,0,'Qutubullapur'),(3367,'2',732,0,'Hindupur'),(3368,'2',732,0,'Gudivada'),(3369,'2',732,0,'Ongole'),(3370,'2',733,0,'Guwahati (Gauhati)'),(3371,'2',733,0,'Dibrugarh'),(3372,'2',733,0,'Silchar'),(3373,'2',733,0,'Nagaon'),(3374,'2',734,0,'Patna'),(3375,'2',734,0,'Gaya'),(3376,'2',734,0,'Bhagalpur'),(3377,'2',734,0,'Muzaffarpur'),(3378,'2',734,0,'Darbhanga'),(3379,'2',734,0,'Bihar Sharif'),(3380,'2',734,0,'Arrah (Ara)'),(3381,'2',734,0,'Katihar'),(3382,'2',734,0,'Munger (Monghyr)'),(3383,'2',734,0,'Chapra'),(3384,'2',734,0,'Sasaram'),(3385,'2',734,0,'Dehri'),(3386,'2',734,0,'Bettiah'),(3387,'2',735,0,'Chandigarh'),(3388,'2',736,0,'Raipur'),(3389,'2',736,0,'Bhilai'),(3390,'2',736,0,'Bilaspur'),(3391,'2',736,0,'Durg'),(3392,'2',736,0,'Raj Nandgaon'),(3393,'2',736,0,'Korba'),(3394,'2',736,0,'Raigarh'),(3395,'2',737,0,'Delhi'),(3396,'2',737,0,'New Delhi'),(3397,'2',737,0,'Delhi Cantonment'),(3398,'2',738,0,'Ahmedabad'),(3399,'2',738,0,'Surat'),(3400,'2',738,0,'Vadodara (Baroda)'),(3401,'2',738,0,'Rajkot'),(3402,'2',738,0,'Bhavnagar'),(3403,'2',738,0,'Jamnagar'),(3404,'2',738,0,'Nadiad'),(3405,'2',738,0,'Bharuch (Broach)'),(3406,'2',738,0,'Junagadh'),(3407,'2',738,0,'Navsari'),(3408,'2',738,0,'Gandhinagar'),(3409,'2',738,0,'Veraval'),(3410,'2',738,0,'Porbandar'),(3411,'2',738,0,'Anand'),(3412,'2',738,0,'Surendranagar'),(3413,'2',738,0,'Gandhidham'),(3414,'2',738,0,'Bhuj'),(3415,'2',738,0,'Godhra'),(3416,'2',738,0,'Patan'),(3417,'2',738,0,'Morvi'),(3418,'2',738,0,'Vejalpur'),(3419,'2',739,0,'Faridabad'),(3420,'2',739,0,'Rohtak'),(3421,'2',739,0,'Panipat'),(3422,'2',739,0,'Karnal'),(3423,'2',739,0,'Hisar (Hissar)'),(3424,'2',739,0,'Yamuna Nagar'),(3425,'2',739,0,'Sonipat (Sonepat)'),(3426,'2',739,0,'Gurgaon'),(3427,'2',739,0,'Sirsa'),(3428,'2',739,0,'Ambala'),(3429,'2',739,0,'Bhiwani'),(3430,'2',739,0,'Ambala Sadar'),(3431,'2',740,0,'Srinagar'),(3432,'2',740,0,'Jammu'),(3433,'2',741,0,'Ranchi'),(3434,'2',741,0,'Jamshedpur'),(3435,'2',741,0,'Bokaro Steel City'),(3436,'2',741,0,'Dhanbad'),(3437,'2',741,0,'Purnea (Purnia)'),(3438,'2',741,0,'Mango'),(3439,'2',741,0,'Hazaribag'),(3440,'2',741,0,'Purulia'),(3441,'2',742,0,'Bangalore'),(3442,'2',742,0,'Hubli-Dharwad'),(3443,'2',742,0,'Mysore'),(3444,'2',742,0,'Belgaum'),(3445,'2',742,0,'Gulbarga'),(3446,'2',742,0,'Mangalore'),(3447,'2',742,0,'Davangere'),(3448,'2',742,0,'Bellary'),(3449,'2',742,0,'Bijapur'),(3450,'2',742,0,'Shimoga'),(3451,'2',742,0,'Raichur'),(3452,'2',742,0,'Timkur'),(3453,'2',742,0,'Gadag Betigeri'),(3454,'2',742,0,'Mandya'),(3455,'2',742,0,'Bidar'),(3456,'2',742,0,'Hospet'),(3457,'2',742,0,'Hassan'),(3458,'2',743,0,'Cochin (Kochi)'),(3459,'2',743,0,'Thiruvananthapuram (Trivandrum'),(3460,'2',743,0,'Calicut (Kozhikode)'),(3461,'2',743,0,'Allappuzha (Alleppey)'),(3462,'2',743,0,'Kollam (Quilon)'),(3463,'2',743,0,'Palghat (Palakkad)'),(3464,'2',743,0,'Tellicherry (Thalassery)'),(3465,'2',744,0,'Indore'),(3466,'2',744,0,'Bhopal'),(3467,'2',744,0,'Jabalpur'),(3468,'2',744,0,'Gwalior'),(3469,'2',744,0,'Ujjain'),(3470,'2',744,0,'Sagar'),(3471,'2',744,0,'Ratlam'),(3472,'2',744,0,'Burhanpur'),(3473,'2',744,0,'Dewas'),(3474,'2',744,0,'Murwara (Katni)'),(3475,'2',744,0,'Satna'),(3476,'2',744,0,'Morena'),(3477,'2',744,0,'Khandwa'),(3478,'2',744,0,'Rewa'),(3479,'2',744,0,'Bhind'),(3480,'2',744,0,'Shivapuri'),(3481,'2',744,0,'Guna'),(3482,'2',744,0,'Mandasor'),(3483,'2',744,0,'Damoh'),(3484,'2',744,0,'Chhindwara'),(3485,'2',744,0,'Vidisha'),(3486,'2',745,0,'Mumbai (Bombay)'),(3487,'2',745,0,'Nagpur'),(3488,'2',745,0,'Pune'),(3489,'2',745,0,'Kalyan'),(3490,'2',745,0,'Thane (Thana)'),(3491,'2',745,0,'Nashik (Nasik)'),(3492,'2',745,0,'Solapur (Sholapur)'),(3493,'2',745,0,'Shambajinagar (Aurangabad)'),(3494,'2',745,0,'Pimpri-Chinchwad'),(3495,'2',745,0,'Amravati'),(3496,'2',745,0,'Kolhapur'),(3497,'2',745,0,'Bhiwandi'),(3498,'2',745,0,'Ulhasnagar'),(3499,'2',745,0,'Malegaon'),(3500,'2',745,0,'Akola'),(3501,'2',745,0,'New Bombay'),(3502,'2',745,0,'Dhule (Dhulia)'),(3503,'2',745,0,'Nanded (Nander)'),(3504,'2',745,0,'Jalgaon'),(3505,'2',745,0,'Chandrapur'),(3506,'2',745,0,'Ichalkaranji'),(3507,'2',745,0,'Latur'),(3508,'2',745,0,'Sangli'),(3509,'2',745,0,'Parbhani'),(3510,'2',745,0,'Ahmadnagar'),(3511,'2',745,0,'Mira Bhayandar'),(3512,'2',745,0,'Jalna'),(3513,'2',745,0,'Bhusawal'),(3514,'2',745,0,'Miraj'),(3515,'2',745,0,'Bhir (Bid)'),(3516,'2',745,0,'Gondiya'),(3517,'2',745,0,'Yeotmal (Yavatmal)'),(3518,'2',745,0,'Wardha'),(3519,'2',745,0,'Achalpur'),(3520,'2',745,0,'Satara'),(3521,'2',746,0,'Imphal'),(3522,'2',747,0,'Shillong'),(3523,'2',748,0,'Aizawl'),(3524,'2',749,0,'Bhubaneswar'),(3525,'2',749,0,'Kataka (Cuttack)'),(3526,'2',749,0,'Raurkela'),(3527,'2',749,0,'Brahmapur'),(3528,'2',749,0,'Raurkela Civil Township'),(3529,'2',749,0,'Sambalpur'),(3530,'2',749,0,'Puri'),(3531,'2',750,0,'Pondicherry'),(3532,'2',751,0,'Ludhiana'),(3533,'2',751,0,'Amritsar'),(3534,'2',751,0,'Jalandhar (Jullundur)'),(3535,'2',751,0,'Patiala'),(3536,'2',751,0,'Bhatinda (Bathinda)'),(3537,'2',751,0,'Pathankot'),(3538,'2',751,0,'Hoshiarpur'),(3539,'2',751,0,'Moga'),(3540,'2',751,0,'Abohar'),(3541,'2',751,0,'Lahore'),(3542,'2',751,0,'Faisalabad'),(3543,'2',751,0,'Rawalpindi'),(3544,'2',751,0,'Multan'),(3545,'2',751,0,'Gujranwala'),(3546,'2',751,0,'Sargodha'),(3547,'2',751,0,'Sialkot'),(3548,'2',751,0,'Bahawalpur'),(3549,'2',751,0,'Jhang'),(3550,'2',751,0,'Sheikhupura'),(3551,'2',751,0,'Gujrat'),(3552,'2',751,0,'Kasur'),(3553,'2',751,0,'Rahim Yar Khan'),(3554,'2',751,0,'Sahiwal'),(3555,'2',751,0,'Okara'),(3556,'2',751,0,'Wah'),(3557,'2',751,0,'Dera Ghazi Khan'),(3558,'2',751,0,'Chiniot'),(3559,'2',751,0,'Kamoke'),(3560,'2',751,0,'Mandi Burewala'),(3561,'2',751,0,'Jhelum'),(3562,'2',751,0,'Sadiqabad'),(3563,'2',751,0,'Khanewal'),(3564,'2',751,0,'Hafizabad'),(3565,'2',751,0,'Muzaffargarh'),(3566,'2',751,0,'Khanpur'),(3567,'2',751,0,'Gojra'),(3568,'2',751,0,'Bahawalnagar'),(3569,'2',751,0,'Muridke'),(3570,'2',751,0,'Pak Pattan'),(3571,'2',751,0,'Jaranwala'),(3572,'2',751,0,'Chishtian Mandi'),(3573,'2',751,0,'Daska'),(3574,'2',751,0,'Mandi Bahauddin'),(3575,'2',751,0,'Ahmadpur East'),(3576,'2',751,0,'Kamalia'),(3577,'2',751,0,'Vihari'),(3578,'2',751,0,'Wazirabad'),(3579,'2',752,0,'Jaipur'),(3580,'2',752,0,'Jodhpur'),(3581,'2',752,0,'Kota'),(3582,'2',752,0,'Bikaner'),(3583,'2',752,0,'Ajmer'),(3584,'2',752,0,'Udaipur'),(3585,'2',752,0,'Alwar'),(3586,'2',752,0,'Bhilwara'),(3587,'2',752,0,'Ganganagar'),(3588,'2',752,0,'Bharatpur'),(3589,'2',752,0,'Sikar'),(3590,'2',752,0,'Pali'),(3591,'2',752,0,'Beawar'),(3592,'2',752,0,'Tonk'),(3593,'2',753,0,'Chennai (Madras)'),(3594,'2',753,0,'Madurai'),(3595,'2',753,0,'Coimbatore'),(3596,'2',753,0,'Tiruchirapalli'),(3597,'2',753,0,'Salem'),(3598,'2',753,0,'Tiruppur (Tirupper)'),(3599,'2',753,0,'Ambattur'),(3600,'2',753,0,'Thanjavur'),(3601,'2',753,0,'Tuticorin'),(3602,'2',753,0,'Nagar Coil'),(3603,'2',753,0,'Avadi'),(3604,'2',753,0,'Dindigul'),(3605,'2',753,0,'Vellore'),(3606,'2',753,0,'Tiruvottiyur'),(3607,'2',753,0,'Erode'),(3608,'2',753,0,'Cuddalore'),(3609,'2',753,0,'Kanchipuram'),(3610,'2',753,0,'Kumbakonam'),(3611,'2',753,0,'Tirunelveli'),(3612,'2',753,0,'Alandur'),(3613,'2',753,0,'Neyveli'),(3614,'2',753,0,'Rajapalaiyam'),(3615,'2',753,0,'Pallavaram'),(3616,'2',753,0,'Tiruvannamalai'),(3617,'2',753,0,'Tambaram'),(3618,'2',753,0,'Valparai'),(3619,'2',753,0,'Pudukkottai'),(3620,'2',753,0,'Palayankottai'),(3621,'2',754,0,'Agartala'),(3622,'2',755,0,'Kanpur'),(3623,'2',755,0,'Lucknow'),(3624,'2',755,0,'Varanasi (Benares)'),(3625,'2',755,0,'Agra'),(3626,'2',755,0,'Allahabad'),(3627,'2',755,0,'Meerut'),(3628,'2',755,0,'Bareilly'),(3629,'2',755,0,'Gorakhpur'),(3630,'2',755,0,'Aligarh'),(3631,'2',755,0,'Ghaziabad'),(3632,'2',755,0,'Moradabad'),(3633,'2',755,0,'Saharanpur'),(3634,'2',755,0,'Jhansi'),(3635,'2',755,0,'Rampur'),(3636,'2',755,0,'Muzaffarnagar'),(3637,'2',755,0,'Shahjahanpur'),(3638,'2',755,0,'Mathura'),(3639,'2',755,0,'Firozabad'),(3640,'2',755,0,'Farrukhabad-cum-Fatehgarh'),(3641,'2',755,0,'Mirzapur-cum-Vindhyachal'),(3642,'2',755,0,'Sambhal'),(3643,'2',755,0,'Noida'),(3644,'2',755,0,'Hapur'),(3645,'2',755,0,'Amroha'),(3646,'2',755,0,'Maunath Bhanjan'),(3647,'2',755,0,'Jaunpur'),(3648,'2',755,0,'Bahraich'),(3649,'2',755,0,'Rae Bareli'),(3650,'2',755,0,'Bulandshahr'),(3651,'2',755,0,'Faizabad'),(3652,'2',755,0,'Etawah'),(3653,'2',755,0,'Sitapur'),(3654,'2',755,0,'Fatehpur'),(3655,'2',755,0,'Budaun'),(3656,'2',755,0,'Hathras'),(3657,'2',755,0,'Unnao'),(3658,'2',755,0,'Pilibhit'),(3659,'2',755,0,'Gonda'),(3660,'2',755,0,'Modinagar'),(3661,'2',755,0,'Orai'),(3662,'2',755,0,'Banda'),(3663,'2',755,0,'Meerut Cantonment'),(3664,'2',755,0,'Kanpur Cantonment'),(3665,'2',756,0,'Dehra Dun'),(3666,'2',756,0,'Hardwar (Haridwar)'),(3667,'2',756,0,'Haldwani-cum-Kathgodam'),(3668,'2',757,0,'Calcutta [Kolkata]'),(3669,'2',757,0,'Haora (Howrah)'),(3670,'2',757,0,'Durgapur'),(3671,'2',757,0,'Bhatpara'),(3672,'2',757,0,'Panihati'),(3673,'2',757,0,'Kamarhati'),(3674,'2',757,0,'Asansol'),(3675,'2',757,0,'Barddhaman (Burdwan)'),(3676,'2',757,0,'South Dum Dum'),(3677,'2',757,0,'Barahanagar (Baranagar)'),(3678,'2',757,0,'Siliguri (Shiliguri)'),(3679,'2',757,0,'Bally'),(3680,'2',757,0,'Kharagpur'),(3681,'2',757,0,'Burnpur'),(3682,'2',757,0,'Uluberia'),(3683,'2',757,0,'Hugli-Chinsurah'),(3684,'2',757,0,'Raiganj'),(3685,'2',757,0,'North Dum Dum'),(3686,'2',757,0,'Dabgram'),(3687,'2',757,0,'Ingraj Bazar (English Bazar)'),(3688,'2',757,0,'Serampore'),(3689,'2',757,0,'Barrackpur'),(3690,'2',757,0,'Naihati'),(3691,'2',757,0,'Midnapore (Medinipur)'),(3692,'2',757,0,'Navadwip'),(3693,'2',757,0,'Krishnanagar'),(3694,'2',757,0,'Chandannagar'),(3695,'2',757,0,'Balurghat'),(3696,'2',757,0,'Berhampore (Baharampur)'),(3697,'2',757,0,'Bankura'),(3698,'2',757,0,'Titagarh'),(3699,'2',757,0,'Halisahar'),(3700,'2',757,0,'Santipur'),(3701,'2',757,0,'Kulti-Barakar'),(3702,'2',757,0,'Barasat'),(3703,'2',757,0,'Rishra'),(3704,'2',757,0,'Basirhat'),(3705,'2',757,0,'Uttarpara-Kotrung'),(3706,'2',757,0,'North Barrackpur'),(3707,'2',757,0,'Haldia'),(3708,'2',757,0,'Habra'),(3709,'2',757,0,'Kanchrapara'),(3710,'2',757,0,'Champdani'),(3711,'2',757,0,'Ashoknagar-Kalyangarh'),(3712,'2',757,0,'Bansberia'),(3713,'2',757,0,'Baidyabati'),(3714,'2',758,0,'Dublin'),(3715,'2',759,0,'Cork'),(3716,'2',760,0,'Ardebil'),(3717,'2',761,0,'Bushehr'),(3718,'2',762,0,'Shahr-e Kord'),(3719,'2',763,0,'Tabriz'),(3720,'2',763,0,'Maragheh'),(3721,'2',763,0,'Marand'),(3722,'2',764,0,'Esfahan'),(3723,'2',764,0,'Kashan'),(3724,'2',764,0,'Najafabad'),(3725,'2',764,0,'Khomeynishahr'),(3726,'2',764,0,'Qomsheh'),(3727,'2',765,0,'Shiraz'),(3728,'2',765,0,'Marv Dasht'),(3729,'2',765,0,'Jahrom'),(3730,'2',766,0,'Rasht'),(3731,'2',766,0,'Bandar-e Anzali'),(3732,'2',767,0,'Gorgan'),(3733,'2',768,0,'Hamadan'),(3734,'2',768,0,'Malayer'),(3735,'2',769,0,'Bandar-e-Abbas'),(3736,'2',770,0,'Ilam'),(3737,'2',771,0,'Kerman'),(3738,'2',771,0,'Sirjan'),(3739,'2',771,0,'Rafsanjan'),(3740,'2',772,0,'Kermanshah'),(3741,'2',773,0,'Mashhad'),(3742,'2',773,0,'Sabzevar'),(3743,'2',773,0,'Neyshabur'),(3744,'2',773,0,'Bojnurd'),(3745,'2',773,0,'Birjand'),(3746,'2',773,0,'Torbat-e Heydariyeh'),(3747,'2',774,0,'Ahvaz'),(3748,'2',774,0,'Abadan'),(3749,'2',774,0,'Dezful'),(3750,'2',774,0,'Masjed-e-Soleyman'),(3751,'2',774,0,'Andimeshk'),(3752,'2',774,0,'Khorramshahr'),(3753,'2',775,0,'Sanandaj'),(3754,'2',775,0,'Saqqez'),(3755,'2',776,0,'Khorramabad'),(3756,'2',776,0,'Borujerd'),(3757,'2',777,0,'Arak'),(3758,'2',778,0,'Sari'),(3759,'2',778,0,'Amol'),(3760,'2',778,0,'Babol'),(3761,'2',778,0,'Qaemshahr'),(3762,'2',778,0,'Gonbad-e Qabus'),(3763,'2',779,0,'Qazvin'),(3764,'2',780,0,'Qom'),(3765,'2',780,0,'Saveh'),(3766,'2',781,0,'Shahrud'),(3767,'2',781,0,'Semnan'),(3768,'2',782,0,'Zahedan'),(3769,'2',782,0,'Zabol'),(3770,'2',783,0,'Teheran'),(3771,'2',783,0,'Karaj'),(3772,'2',783,0,'Eslamshahr'),(3773,'2',783,0,'Qarchak'),(3774,'2',783,0,'Qods'),(3775,'2',783,0,'Varamin'),(3776,'2',784,0,'Urmia'),(3777,'2',784,0,'Khoy'),(3778,'2',784,0,'Bukan'),(3779,'2',784,0,'Mahabad'),(3780,'2',784,0,'Miandoab'),(3781,'2',785,0,'Yazd'),(3782,'2',786,0,'Zanjan'),(3783,'2',787,0,'al-Ramadi'),(3784,'2',788,0,'al-Najaf'),(3785,'2',789,0,'al-Diwaniya'),(3786,'2',790,0,'al-Sulaymaniya'),(3787,'2',791,0,'Kirkuk'),(3788,'2',792,0,'al-Hilla'),(3789,'2',793,0,'Baghdad'),(3790,'2',794,0,'Basra'),(3791,'2',795,0,'al-Nasiriya'),(3792,'2',796,0,'Baquba'),(3793,'2',797,0,'Irbil'),(3794,'2',798,0,'Karbala'),(3795,'2',799,0,'al-Amara'),(3796,'2',800,0,'Mosul'),(3797,'2',801,0,'al-Kut'),(3798,'2',802,0,'ReykjavÃ­k'),(3799,'2',803,0,'Beerseba'),(3800,'2',803,0,'Ashdod'),(3801,'2',803,0,'Ashqelon'),(3802,'2',804,0,'Rishon Le Ziyyon'),(3803,'2',804,0,'Petah Tiqwa'),(3804,'2',804,0,'Netanya'),(3805,'2',804,0,'Rehovot'),(3806,'2',805,0,'Haifa'),(3807,'2',806,0,'Jerusalem'),(3808,'2',807,0,'Tel Aviv-Jaffa'),(3809,'2',807,0,'Holon'),(3810,'2',807,0,'Bat Yam'),(3811,'2',807,0,'Bene Beraq'),(3812,'2',807,0,'Ramat Gan'),(3813,'2',808,0,'Pescara'),(3814,'2',809,0,'Bari'),(3815,'2',809,0,'Taranto'),(3816,'2',809,0,'Foggia'),(3817,'2',809,0,'Lecce'),(3818,'2',809,0,'Andria'),(3819,'2',809,0,'Brindisi'),(3820,'2',809,0,'Barletta'),(3821,'2',810,0,'Reggio di Calabria'),(3822,'2',810,0,'Catanzaro'),(3823,'2',811,0,'Napoli'),(3824,'2',811,0,'Salerno'),(3825,'2',811,0,'Torre del Greco'),(3826,'2',811,0,'Giugliano in Campania'),(3827,'2',812,0,'Bologna'),(3828,'2',812,0,'Modena'),(3829,'2',812,0,'Parma'),(3830,'2',812,0,'Reggio nellÂ´ Emilia'),(3831,'2',812,0,'Ravenna'),(3832,'2',812,0,'Ferrara'),(3833,'2',812,0,'Rimini'),(3834,'2',812,0,'ForlÃ¬'),(3835,'2',812,0,'Piacenza'),(3836,'2',812,0,'Cesena'),(3837,'2',813,0,'Trieste'),(3838,'2',813,0,'Udine'),(3839,'2',814,0,'Roma'),(3840,'2',814,0,'Latina'),(3841,'2',815,0,'Genova'),(3842,'2',815,0,'La Spezia'),(3843,'2',816,0,'Milano'),(3844,'2',816,0,'Brescia'),(3845,'2',816,0,'Monza'),(3846,'2',816,0,'Bergamo'),(3847,'2',817,0,'Ancona'),(3848,'2',817,0,'Pesaro'),(3849,'2',818,0,'Torino'),(3850,'2',818,0,'Novara'),(3851,'2',818,0,'Alessandria'),(3852,'2',819,0,'Cagliari'),(3853,'2',819,0,'Sassari'),(3854,'2',820,0,'Palermo'),(3855,'2',820,0,'Catania'),(3856,'2',820,0,'Messina'),(3857,'2',820,0,'Syrakusa'),(3858,'2',821,0,'Firenze'),(3859,'2',821,0,'Prato'),(3860,'2',821,0,'Livorno'),(3861,'2',821,0,'Pisa'),(3862,'2',821,0,'Arezzo'),(3863,'2',822,0,'Trento'),(3864,'2',822,0,'Bolzano'),(3865,'2',823,0,'Perugia'),(3866,'2',823,0,'Terni'),(3867,'2',824,0,'Venezia'),(3868,'2',824,0,'Verona'),(3869,'2',824,0,'Padova'),(3870,'2',824,0,'Vicenza'),(3871,'2',825,0,'Kingston'),(3872,'2',825,0,'Portmore'),(3873,'2',826,0,'Spanish Town'),(3874,'2',827,0,'al-Zarqa'),(3875,'2',827,0,'al-Rusayfa'),(3876,'2',828,0,'Amman'),(3877,'2',828,0,'Wadi al-Sir'),(3878,'2',829,0,'Irbid'),(3879,'2',830,0,'Nagoya'),(3880,'2',830,0,'Toyohashi'),(3881,'2',830,0,'Toyota'),(3882,'2',830,0,'Okazaki'),(3883,'2',830,0,'Kasugai'),(3884,'2',830,0,'Ichinomiya'),(3885,'2',830,0,'Anjo'),(3886,'2',830,0,'Komaki'),(3887,'2',830,0,'Seto'),(3888,'2',830,0,'Kariya'),(3889,'2',830,0,'Toyokawa'),(3890,'2',830,0,'Handa'),(3891,'2',830,0,'Tokai'),(3892,'2',830,0,'Inazawa'),(3893,'2',830,0,'Konan'),(3894,'2',831,0,'Akita'),(3895,'2',832,0,'Aomori'),(3896,'2',832,0,'Hachinohe'),(3897,'2',832,0,'Hirosaki'),(3898,'2',833,0,'Chiba'),(3899,'2',833,0,'Funabashi'),(3900,'2',833,0,'Matsudo'),(3901,'2',833,0,'Ichikawa'),(3902,'2',833,0,'Kashiwa'),(3903,'2',833,0,'Ichihara'),(3904,'2',833,0,'Sakura'),(3905,'2',833,0,'Yachiyo'),(3906,'2',833,0,'Narashino'),(3907,'2',833,0,'Nagareyama'),(3908,'2',833,0,'Urayasu'),(3909,'2',833,0,'Abiko'),(3910,'2',833,0,'Kisarazu'),(3911,'2',833,0,'Noda'),(3912,'2',833,0,'Kamagaya'),(3913,'2',833,0,'Nishio'),(3914,'2',833,0,'Kimitsu'),(3915,'2',833,0,'Mobara'),(3916,'2',833,0,'Narita'),(3917,'2',834,0,'Matsuyama'),(3918,'2',834,0,'Niihama'),(3919,'2',834,0,'Imabari'),(3920,'2',835,0,'Fukui'),(3921,'2',836,0,'Fukuoka'),(3922,'2',836,0,'Kitakyushu'),(3923,'2',836,0,'Kurume'),(3924,'2',836,0,'Omuta'),(3925,'2',836,0,'Kasuga'),(3926,'2',837,0,'Iwaki'),(3927,'2',837,0,'Koriyama'),(3928,'2',837,0,'Fukushima'),(3929,'2',837,0,'Aizuwakamatsu'),(3930,'2',838,0,'Gifu'),(3931,'2',838,0,'Ogaki'),(3932,'2',838,0,'Kakamigahara'),(3933,'2',838,0,'Tajimi'),(3934,'2',839,0,'Maebashi'),(3935,'2',839,0,'Takasaki'),(3936,'2',839,0,'Ota'),(3937,'2',839,0,'Isesaki'),(3938,'2',839,0,'Kiryu'),(3939,'2',840,0,'Hiroshima'),(3940,'2',840,0,'Fukuyama'),(3941,'2',840,0,'Kure'),(3942,'2',840,0,'Higashihiroshima'),(3943,'2',840,0,'Onomichi'),(3944,'2',841,0,'Sapporo'),(3945,'2',841,0,'Asahikawa'),(3946,'2',841,0,'Hakodate'),(3947,'2',841,0,'Kushiro'),(3948,'2',841,0,'Obihiro'),(3949,'2',841,0,'Tomakomai'),(3950,'2',841,0,'Otaru'),(3951,'2',841,0,'Ebetsu'),(3952,'2',841,0,'Kitami'),(3953,'2',841,0,'Muroran'),(3954,'2',842,0,'Kobe'),(3955,'2',842,0,'Amagasaki'),(3956,'2',842,0,'Himeji'),(3957,'2',842,0,'Nishinomiya'),(3958,'2',842,0,'Akashi'),(3959,'2',842,0,'Kakogawa'),(3960,'2',842,0,'Takarazuka'),(3961,'2',842,0,'Itami'),(3962,'2',842,0,'Kawanishi'),(3963,'2',842,0,'Sanda'),(3964,'2',842,0,'Takasago'),(3965,'2',843,0,'Mito'),(3966,'2',843,0,'Hitachi'),(3967,'2',843,0,'Tsukuba'),(3968,'2',843,0,'Tama'),(3969,'2',843,0,'Tsuchiura'),(3970,'2',844,0,'Kanazawa'),(3971,'2',844,0,'Komatsu'),(3972,'2',845,0,'Morioka'),(3973,'2',846,0,'Takamatsu'),(3974,'2',847,0,'Kagoshima'),(3975,'2',848,0,'Jokohama [Yokohama]'),(3976,'2',848,0,'Kawasaki'),(3977,'2',848,0,'Sagamihara'),(3978,'2',848,0,'Yokosuka'),(3979,'2',848,0,'Fujisawa'),(3980,'2',848,0,'Hiratsuka'),(3981,'2',848,0,'Chigasaki'),(3982,'2',848,0,'Atsugi'),(3983,'2',848,0,'Yamato'),(3984,'2',848,0,'Odawara'),(3985,'2',848,0,'Kamakura'),(3986,'2',848,0,'Hadano'),(3987,'2',848,0,'Zama'),(3988,'2',848,0,'Ebina'),(3989,'2',848,0,'Isehara'),(3990,'2',849,0,'Kochi'),(3991,'2',850,0,'Kumamoto'),(3992,'2',850,0,'Yatsushiro'),(3993,'2',851,0,'Kioto'),(3994,'2',851,0,'Uji'),(3995,'2',851,0,'Maizuru'),(3996,'2',851,0,'Kameoka'),(3997,'2',852,0,'Yokkaichi'),(3998,'2',852,0,'Suzuka'),(3999,'2',852,0,'Tsu'),(4000,'2',852,0,'Matsusaka'),(4001,'2',852,0,'Kuwana'),(4002,'2',852,0,'Ise'),(4003,'2',853,0,'Sendai'),(4004,'2',853,0,'Ishinomaki'),(4005,'2',854,0,'Miyazaki'),(4006,'2',854,0,'Miyakonojo'),(4007,'2',854,0,'Nobeoka'),(4008,'2',855,0,'Nagano'),(4009,'2',855,0,'Matsumoto'),(4010,'2',855,0,'Ueda'),(4011,'2',855,0,'Iida'),(4012,'2',856,0,'Nagasaki'),(4013,'2',856,0,'Sasebo'),(4014,'2',856,0,'Isahaya'),(4015,'2',857,0,'Nara'),(4016,'2',857,0,'Kashihara'),(4017,'2',857,0,'Ikoma'),(4018,'2',857,0,'Yamatokoriyama'),(4019,'2',858,0,'Niigata'),(4020,'2',858,0,'Nagaoka'),(4021,'2',858,0,'Joetsu'),(4022,'2',858,0,'Kashiwazaki'),(4023,'2',859,0,'Oita'),(4024,'2',859,0,'Beppu'),(4025,'2',860,0,'Okayama'),(4026,'2',860,0,'Kurashiki'),(4027,'2',860,0,'Tsuyama'),(4028,'2',861,0,'Naha'),(4029,'2',861,0,'Okinawa'),(4030,'2',861,0,'Urasoe'),(4031,'2',862,0,'Osaka'),(4032,'2',862,0,'Sakai'),(4033,'2',862,0,'Higashiosaka'),(4034,'2',862,0,'Hirakata'),(4035,'2',862,0,'Toyonaka'),(4036,'2',862,0,'Takatsuki'),(4037,'2',862,0,'Suita'),(4038,'2',862,0,'Yao'),(4039,'2',862,0,'Ibaraki'),(4040,'2',862,0,'Neyagawa'),(4041,'2',862,0,'Kishiwada'),(4042,'2',862,0,'Izumi'),(4043,'2',862,0,'Moriguchi'),(4044,'2',862,0,'Kadoma'),(4045,'2',862,0,'Matsubara'),(4046,'2',862,0,'Daito'),(4047,'2',862,0,'Minoo'),(4048,'2',862,0,'Tondabayashi'),(4049,'2',862,0,'Kawachinagano'),(4050,'2',862,0,'Habikino'),(4051,'2',862,0,'Ikeda'),(4052,'2',862,0,'Izumisano'),(4053,'2',863,0,'Saga'),(4054,'2',864,0,'Urawa'),(4055,'2',864,0,'Kawaguchi'),(4056,'2',864,0,'Omiya'),(4057,'2',864,0,'Kawagoe'),(4058,'2',864,0,'Tokorozawa'),(4059,'2',864,0,'Koshigaya'),(4060,'2',864,0,'Soka'),(4061,'2',864,0,'Ageo'),(4062,'2',864,0,'Kasukabe'),(4063,'2',864,0,'Sayama'),(4064,'2',864,0,'Kumagaya'),(4065,'2',864,0,'Niiza'),(4066,'2',864,0,'Iruma'),(4067,'2',864,0,'Misato'),(4068,'2',864,0,'Asaka'),(4069,'2',864,0,'Iwatsuki'),(4070,'2',864,0,'Toda'),(4071,'2',864,0,'Fukaya'),(4072,'2',864,0,'Sakado'),(4073,'2',864,0,'Fujimi'),(4074,'2',864,0,'Higashimatsuyama'),(4075,'2',865,0,'Otsu'),(4076,'2',865,0,'Kusatsu'),(4077,'2',865,0,'Hikone'),(4078,'2',866,0,'Matsue'),(4079,'2',867,0,'Hamamatsu'),(4080,'2',867,0,'Shizuoka'),(4081,'2',867,0,'Shimizu'),(4082,'2',867,0,'Fuji'),(4083,'2',867,0,'Numazu'),(4084,'2',867,0,'Fujieda'),(4085,'2',867,0,'Fujinomiya'),(4086,'2',867,0,'Yaizu'),(4087,'2',867,0,'Mishima'),(4088,'2',868,0,'Utsunomiya'),(4089,'2',868,0,'Ashikaga'),(4090,'2',868,0,'Oyama'),(4091,'2',868,0,'Kanuma'),(4092,'2',869,0,'Tokushima'),(4093,'2',870,0,'Tokyo'),(4094,'2',870,0,'Hachioji'),(4095,'2',870,0,'Machida'),(4096,'2',870,0,'Fuchu'),(4097,'2',870,0,'Chofu'),(4098,'2',870,0,'Kodaira'),(4099,'2',870,0,'Mitaka'),(4100,'2',870,0,'Hino'),(4101,'2',870,0,'Tachikawa'),(4102,'2',870,0,'Hitachinaka'),(4103,'2',870,0,'Ome'),(4104,'2',870,0,'Higashimurayama'),(4105,'2',870,0,'Musashino'),(4106,'2',870,0,'Higashikurume'),(4107,'2',870,0,'Koganei'),(4108,'2',870,0,'Kokubunji'),(4109,'2',870,0,'Akishima'),(4110,'2',870,0,'Hoya'),(4111,'2',871,0,'Tottori'),(4112,'2',871,0,'Yonago'),(4113,'2',872,0,'Toyama'),(4114,'2',872,0,'Takaoka'),(4115,'2',873,0,'Wakayama'),(4116,'2',874,0,'Yamagata'),(4117,'2',874,0,'Sakata'),(4118,'2',874,0,'Tsuruoka'),(4119,'2',874,0,'Yonezawa'),(4120,'2',875,0,'Shimonoseki'),(4121,'2',875,0,'Ube'),(4122,'2',875,0,'Yamaguchi'),(4123,'2',875,0,'Hofu'),(4124,'2',875,0,'Tokuyama'),(4125,'2',875,0,'Iwakuni'),(4126,'2',876,0,'Kofu'),(4127,'2',877,0,'Taldyqorghan'),(4128,'2',878,0,'Almaty'),(4129,'2',879,0,'AqtÃ¶be'),(4130,'2',880,0,'Astana'),(4131,'2',881,0,'Atyrau'),(4132,'2',882,0,'Ã–skemen'),(4133,'2',882,0,'Semey'),(4134,'2',883,0,'Aqtau'),(4135,'2',884,0,'Petropavl'),(4136,'2',884,0,'KÃ¶kshetau'),(4137,'2',885,0,'Pavlodar'),(4138,'2',885,0,'Ekibastuz'),(4139,'2',886,0,'Qaraghandy'),(4140,'2',886,0,'Temirtau'),(4141,'2',886,0,'Zhezqazghan'),(4142,'2',887,0,'Qostanay'),(4143,'2',887,0,'Rudnyy'),(4144,'2',888,0,'Qyzylorda'),(4145,'2',889,0,'Shymkent'),(4146,'2',890,0,'Taraz'),(4147,'2',891,0,'Oral'),(4148,'2',892,0,'Suva'),(4149,'2',892,0,'Nyeri'),(4150,'2',892,0,'Kathmandu'),(4151,'2',892,0,'Lalitapur'),(4152,'2',892,0,'Birgunj'),(4153,'2',892,0,'San Lorenzo'),(4154,'2',892,0,'LambarÃ©'),(4155,'2',892,0,'Fernando de la Mora'),(4156,'2',892,0,'Kabwe'),(4157,'2',892,0,'Kandy'),(4158,'2',892,0,'Kampala'),(4159,'2',893,0,'Mombasa'),(4160,'2',894,0,'Machakos'),(4161,'2',894,0,'Meru'),(4162,'2',894,0,'Biratnagar'),(4163,'2',895,0,'Nairobi'),(4164,'2',896,0,'Kisumu'),(4165,'2',897,0,'Nakuru'),(4166,'2',897,0,'Eldoret'),(4167,'2',898,0,'Bishkek'),(4168,'2',899,0,'Osh'),(4169,'2',900,0,'Battambang'),(4170,'2',901,0,'Phnom Penh'),(4171,'2',902,0,'Siem Reap'),(4172,'2',903,0,'Bikenibeu'),(4173,'2',903,0,'Bairiki'),(4174,'2',904,0,'Basseterre'),(4175,'2',905,0,'Cheju'),(4176,'2',906,0,'Chonju'),(4177,'2',906,0,'Iksan'),(4178,'2',906,0,'Kunsan'),(4179,'2',906,0,'Chong-up'),(4180,'2',906,0,'Kimje'),(4181,'2',906,0,'Namwon'),(4182,'2',907,0,'Sunchon'),(4183,'2',907,0,'Mokpo'),(4184,'2',907,0,'Yosu'),(4185,'2',907,0,'Kwang-yang'),(4186,'2',907,0,'Naju'),(4187,'2',908,0,'Chongju'),(4188,'2',908,0,'Chungju'),(4189,'2',908,0,'Chechon'),(4190,'2',909,0,'Chonan'),(4191,'2',909,0,'Asan'),(4192,'2',909,0,'Nonsan'),(4193,'2',909,0,'Sosan'),(4194,'2',909,0,'Kongju'),(4195,'2',909,0,'Poryong'),(4196,'2',910,0,'Inchon'),(4197,'2',911,0,'Wonju'),(4198,'2',911,0,'Chunchon'),(4199,'2',911,0,'Kangnung'),(4200,'2',911,0,'Tonghae'),(4201,'2',912,0,'Kwangju'),(4202,'2',913,0,'Songnam'),(4203,'2',913,0,'Puchon'),(4204,'2',913,0,'Suwon'),(4205,'2',913,0,'Anyang'),(4206,'2',913,0,'Koyang'),(4207,'2',913,0,'Ansan'),(4208,'2',913,0,'Kwangmyong'),(4209,'2',913,0,'Pyongtaek'),(4210,'2',913,0,'Uijongbu'),(4211,'2',913,0,'Yong-in'),(4212,'2',913,0,'Kunpo'),(4213,'2',913,0,'Namyangju'),(4214,'2',913,0,'Paju'),(4215,'2',913,0,'Ichon'),(4216,'2',913,0,'Kuri'),(4217,'2',913,0,'Shihung'),(4218,'2',913,0,'Hanam'),(4219,'2',913,0,'Uiwang'),(4220,'2',914,0,'Pohang'),(4221,'2',914,0,'Kumi'),(4222,'2',914,0,'Kyongju'),(4223,'2',914,0,'Andong'),(4224,'2',914,0,'Kyongsan'),(4225,'2',914,0,'Kimchon'),(4226,'2',914,0,'Yongju'),(4227,'2',914,0,'Sangju'),(4228,'2',914,0,'Yongchon'),(4229,'2',914,0,'Mun-gyong'),(4230,'2',915,0,'Ulsan'),(4231,'2',915,0,'Chang-won'),(4232,'2',915,0,'Masan'),(4233,'2',915,0,'Chinju'),(4234,'2',915,0,'Kimhae'),(4235,'2',915,0,'Yangsan'),(4236,'2',915,0,'Koje'),(4237,'2',915,0,'Tong-yong'),(4238,'2',915,0,'Chinhae'),(4239,'2',915,0,'Miryang'),(4240,'2',915,0,'Sachon'),(4241,'2',916,0,'Pusan'),(4242,'2',917,0,'Seoul'),(4243,'2',918,0,'Taegu'),(4244,'2',919,0,'Taejon'),(4245,'2',920,0,'Kuwait'),(4246,'2',921,0,'al-Salimiya'),(4247,'2',921,0,'Jalib al-Shuyukh'),(4248,'2',922,0,'Savannakhet'),(4249,'2',923,0,'Vientiane'),(4250,'2',924,0,'Tripoli'),(4251,'2',925,0,'Beirut'),(4252,'2',926,0,'Monrovia'),(4253,'2',927,0,'al-Zawiya'),(4254,'2',928,0,'Bengasi'),(4255,'2',929,0,'Misrata'),(4256,'2',930,0,'Tripoli'),(4257,'2',931,0,'Castries'),(4258,'2',932,0,'Schaan'),(4259,'2',933,0,'Vaduz'),(4260,'2',934,0,'Suva'),(4261,'2',934,0,'Nyeri'),(4262,'2',934,0,'Kathmandu'),(4263,'2',934,0,'Lalitapur'),(4264,'2',934,0,'Birgunj'),(4265,'2',934,0,'San Lorenzo'),(4266,'2',934,0,'LambarÃ©'),(4267,'2',934,0,'Fernando de la Mora'),(4268,'2',934,0,'Kabwe'),(4269,'2',934,0,'Kandy'),(4270,'2',934,0,'Kampala'),(4271,'2',935,0,'Tamale'),(4272,'2',935,0,'Jaffna'),(4273,'2',936,0,'Sekondi-Takoradi'),(4274,'2',936,0,'Pokhara'),(4275,'2',936,0,'Freetown'),(4276,'2',936,0,'Colombo'),(4277,'2',936,0,'Dehiwala'),(4278,'2',936,0,'Moratuwa'),(4279,'2',936,0,'Sri Jayawardenepura Kotte'),(4280,'2',936,0,'Negombo'),(4281,'2',937,0,'Maseru'),(4282,'2',938,0,'Kaunas'),(4283,'2',939,0,'Klaipeda'),(4284,'2',940,0,'Panevezys'),(4285,'2',941,0,'Vilnius'),(4286,'2',942,0,'Å iauliai'),(4287,'2',943,0,'Luxembourg [Luxemburg/LÃ«tzebu'),(4288,'2',944,0,'Daugavpils'),(4289,'2',945,0,'Liepaja'),(4290,'2',946,0,'Riga'),(4291,'2',947,0,'Macao'),(4292,'2',948,0,'Casablanca'),(4293,'2',948,0,'Mohammedia'),(4294,'2',949,0,'Khouribga'),(4295,'2',949,0,'Settat'),(4296,'2',950,0,'Safi'),(4297,'2',950,0,'El Jadida'),(4298,'2',951,0,'FÃ¨s'),(4299,'2',952,0,'KÃ©nitra'),(4300,'2',953,0,'Marrakech'),(4301,'2',954,0,'MeknÃ¨s'),(4302,'2',955,0,'Oujda'),(4303,'2',955,0,'Nador'),(4304,'2',956,0,'Rabat'),(4305,'2',956,0,'SalÃ©'),(4306,'2',956,0,'TÃ©mara'),(4307,'2',957,0,'Agadir'),(4308,'2',958,0,'Beni-Mellal'),(4309,'2',959,0,'Tanger'),(4310,'2',959,0,'TÃ©touan'),(4311,'2',959,0,'Ksar el Kebir'),(4312,'2',959,0,'El Araich'),(4313,'2',960,0,'Taza'),(4314,'2',961,0,'South Hill'),(4315,'2',961,0,'The Valley'),(4316,'2',961,0,'Oranjestad'),(4317,'2',961,0,'Douglas'),(4318,'2',961,0,'Gibraltar'),(4319,'2',961,0,'Tamuning'),(4320,'2',961,0,'AgaÃ±a'),(4321,'2',961,0,'Flying Fish Cove'),(4322,'2',961,0,'Monte-Carlo'),(4323,'2',961,0,'Monaco-Ville'),(4324,'2',961,0,'Yangor'),(4325,'2',961,0,'Yaren'),(4326,'2',961,0,'Alofi'),(4327,'2',961,0,'Kingston'),(4328,'2',961,0,'Adamstown'),(4329,'2',961,0,'Singapore'),(4330,'2',961,0,'NoumÃ©a'),(4331,'2',961,0,'CittÃ  del Vaticano'),(4332,'2',962,0,'Balti'),(4333,'2',963,0,'Bender (TÃ®ghina)'),(4334,'2',964,0,'Chisinau'),(4335,'2',965,0,'Tiraspol'),(4336,'2',966,0,'Antananarivo'),(4337,'2',966,0,'AntsirabÃ©'),(4338,'2',967,0,'Fianarantsoa'),(4339,'2',968,0,'Mahajanga'),(4340,'2',969,0,'Toamasina'),(4341,'2',970,0,'Male'),(4342,'2',971,0,'Aguascalientes'),(4343,'2',972,0,'Tijuana'),(4344,'2',972,0,'Mexicali'),(4345,'2',972,0,'Ensenada'),(4346,'2',973,0,'La Paz'),(4347,'2',973,0,'Los Cabos'),(4348,'2',974,0,'Campeche'),(4349,'2',974,0,'Carmen'),(4350,'2',975,0,'Tuxtla GutiÃ©rrez'),(4351,'2',975,0,'Tapachula'),(4352,'2',975,0,'Ocosingo'),(4353,'2',975,0,'San CristÃ³bal de las Casas'),(4354,'2',975,0,'ComitÃ¡n de DomÃ­nguez'),(4355,'2',975,0,'Las Margaritas'),(4356,'2',976,0,'JuÃ¡rez'),(4357,'2',976,0,'Chihuahua'),(4358,'2',976,0,'CuauhtÃ©moc'),(4359,'2',976,0,'Delicias'),(4360,'2',976,0,'Hidalgo del Parral'),(4361,'2',977,0,'Saltillo'),(4362,'2',977,0,'TorreÃ³n'),(4363,'2',977,0,'Monclova'),(4364,'2',977,0,'Piedras Negras'),(4365,'2',977,0,'AcuÃ±a'),(4366,'2',977,0,'Matamoros'),(4367,'2',978,0,'Colima'),(4368,'2',978,0,'Manzanillo'),(4369,'2',978,0,'TecomÃ¡n'),(4370,'2',979,0,'Buenos Aires'),(4371,'2',979,0,'BrasÃ­lia'),(4372,'2',979,0,'Ciudad de MÃ©xico'),(4373,'2',979,0,'Caracas'),(4374,'2',979,0,'Catia La Mar'),(4375,'2',980,0,'Durango'),(4376,'2',980,0,'GÃ³mez Palacio'),(4377,'2',980,0,'Lerdo'),(4378,'2',981,0,'LeÃ³n'),(4379,'2',981,0,'Irapuato'),(4380,'2',981,0,'Celaya'),(4381,'2',981,0,'Salamanca'),(4382,'2',981,0,'PÃ©njamo'),(4383,'2',981,0,'Guanajuato'),(4384,'2',981,0,'Allende'),(4385,'2',981,0,'Silao'),(4386,'2',981,0,'Valle de Santiago'),(4387,'2',981,0,'Dolores Hidalgo'),(4388,'2',981,0,'AcÃ¡mbaro'),(4389,'2',981,0,'San Francisco del RincÃ³n'),(4390,'2',981,0,'San Luis de la Paz'),(4391,'2',981,0,'San Felipe'),(4392,'2',981,0,'Salvatierra'),(4393,'2',982,0,'Acapulco de JuÃ¡rez'),(4394,'2',982,0,'Chilpancingo de los Bravo'),(4395,'2',982,0,'Iguala de la Independencia'),(4396,'2',982,0,'Chilapa de Alvarez'),(4397,'2',982,0,'Taxco de AlarcÃ³n'),(4398,'2',982,0,'JosÃ© Azueta'),(4399,'2',983,0,'Pachuca de Soto'),(4400,'2',983,0,'Tulancingo de Bravo'),(4401,'2',983,0,'Huejutla de Reyes'),(4402,'2',984,0,'Guadalajara'),(4403,'2',984,0,'Zapopan'),(4404,'2',984,0,'Tlaquepaque'),(4405,'2',984,0,'TonalÃ¡'),(4406,'2',984,0,'Puerto Vallarta'),(4407,'2',984,0,'Lagos de Moreno'),(4408,'2',984,0,'Tlajomulco de ZÃºÃ±iga'),(4409,'2',984,0,'TepatitlÃ¡n de Morelos'),(4410,'2',985,0,'Ecatepec de Morelos'),(4411,'2',985,0,'NezahualcÃ³yotl'),(4412,'2',985,0,'Naucalpan de JuÃ¡rez'),(4413,'2',985,0,'Tlalnepantla de Baz'),(4414,'2',985,0,'Toluca'),(4415,'2',985,0,'ChimalhuacÃ¡n'),(4416,'2',985,0,'AtizapÃ¡n de Zaragoza'),(4417,'2',985,0,'CuautitlÃ¡n Izcalli'),(4418,'2',985,0,'TultitlÃ¡n'),(4419,'2',985,0,'Valle de Chalco Solidaridad'),(4420,'2',985,0,'Ixtapaluca'),(4421,'2',985,0,'NicolÃ¡s Romero'),(4422,'2',985,0,'Coacalco de BerriozÃ¡bal'),(4423,'2',985,0,'Chalco'),(4424,'2',985,0,'La Paz'),(4425,'2',985,0,'Texcoco'),(4426,'2',985,0,'Metepec'),(4427,'2',985,0,'Huixquilucan'),(4428,'2',985,0,'San Felipe del Progreso'),(4429,'2',985,0,'TecÃ¡mac'),(4430,'2',985,0,'Zinacantepec'),(4431,'2',985,0,'Ixtlahuaca'),(4432,'2',985,0,'Almoloya de JuÃ¡rez'),(4433,'2',985,0,'Zumpango'),(4434,'2',985,0,'Lerma'),(4435,'2',985,0,'Tejupilco'),(4436,'2',985,0,'Tultepec'),(4437,'2',986,0,'Morelia'),(4438,'2',986,0,'Uruapan'),(4439,'2',986,0,'LÃ¡zaro CÃ¡rdenas'),(4440,'2',986,0,'Zamora'),(4441,'2',986,0,'ZitÃ¡cuaro'),(4442,'2',986,0,'ApatzingÃ¡n'),(4443,'2',986,0,'Hidalgo'),(4444,'2',987,0,'Cuernavaca'),(4445,'2',987,0,'Jiutepec'),(4446,'2',987,0,'Cuautla'),(4447,'2',987,0,'Temixco'),(4448,'2',988,0,'Tepic'),(4449,'2',988,0,'Santiago Ixcuintla'),(4450,'2',989,0,'Monterrey'),(4451,'2',989,0,'Guadalupe'),(4452,'2',989,0,'San NicolÃ¡s de los Garza'),(4453,'2',989,0,'Apodaca'),(4454,'2',989,0,'General Escobedo'),(4455,'2',989,0,'Santa Catarina'),(4456,'2',989,0,'San Pedro Garza GarcÃ­a'),(4457,'2',990,0,'Oaxaca de JuÃ¡rez'),(4458,'2',990,0,'San Juan Bautista Tuxtepec'),(4459,'2',991,0,'Puebla'),(4460,'2',991,0,'TehuacÃ¡n'),(4461,'2',991,0,'San MartÃ­n Texmelucan'),(4462,'2',991,0,'Atlixco'),(4463,'2',991,0,'San Pedro Cholula'),(4464,'2',992,0,'San Juan del RÃ­o'),(4465,'2',993,0,'QuerÃ©taro'),(4466,'2',994,0,'Benito JuÃ¡rez'),(4467,'2',994,0,'OthÃ³n P. Blanco (Chetumal)'),(4468,'2',995,0,'San Luis PotosÃ­'),(4469,'2',995,0,'Soledad de Graciano SÃ¡nchez'),(4470,'2',995,0,'Ciudad Valles'),(4471,'2',996,0,'CuliacÃ¡n'),(4472,'2',996,0,'MazatlÃ¡n'),(4473,'2',996,0,'Ahome'),(4474,'2',996,0,'Guasave'),(4475,'2',996,0,'Navolato'),(4476,'2',996,0,'El Fuerte'),(4477,'2',997,0,'Hermosillo'),(4478,'2',997,0,'Cajeme'),(4479,'2',997,0,'Nogales'),(4480,'2',997,0,'San Luis RÃ­o Colorado'),(4481,'2',997,0,'Navojoa'),(4482,'2',997,0,'Guaymas'),(4483,'2',998,0,'Centro (Villahermosa)'),(4484,'2',998,0,'CÃ¡rdenas'),(4485,'2',998,0,'Comalcalco'),(4486,'2',998,0,'Huimanguillo'),(4487,'2',998,0,'Macuspana'),(4488,'2',998,0,'CunduacÃ¡n'),(4489,'2',999,0,'Reynosa'),(4490,'2',999,0,'Matamoros'),(4491,'2',999,0,'Nuevo Laredo'),(4492,'2',999,0,'Tampico'),(4493,'2',999,0,'Victoria'),(4494,'2',999,0,'Ciudad Madero'),(4495,'2',999,0,'Altamira'),(4496,'2',999,0,'El Mante'),(4497,'2',999,0,'RÃ­o Bravo'),(4498,'2',1000,0,'Veracruz'),(4499,'2',1000,0,'Xalapa'),(4500,'2',1000,0,'Coatzacoalcos'),(4501,'2',1000,0,'CÃ³rdoba'),(4502,'2',1000,0,'Papantla'),(4503,'2',1000,0,'MinatitlÃ¡n'),(4504,'2',1000,0,'Poza Rica de Hidalgo'),(4505,'2',1000,0,'San AndrÃ©s Tuxtla'),(4506,'2',1000,0,'TÃºxpam'),(4507,'2',1000,0,'MartÃ­nez de la Torre'),(4508,'2',1000,0,'Orizaba'),(4509,'2',1000,0,'Temapache'),(4510,'2',1000,0,'Cosoleacaque'),(4511,'2',1000,0,'Tantoyuca'),(4512,'2',1000,0,'PÃ¡nuco'),(4513,'2',1000,0,'Tierra Blanca'),(4514,'2',1001,0,'Boca del RÃ­o'),(4515,'2',1002,0,'MÃ©rida'),(4516,'2',1003,0,'Fresnillo'),(4517,'2',1003,0,'Zacatecas'),(4518,'2',1003,0,'Guadalupe'),(4519,'2',1004,0,'Dalap-Uliga-Darrit'),(4520,'2',1005,0,'Skopje'),(4521,'2',1006,0,'Bamako'),(4522,'2',1007,0,'Valletta'),(4523,'2',1008,0,'Birkirkara'),(4524,'2',1009,0,'Bassein (Pathein)'),(4525,'2',1009,0,'Henzada (Hinthada)'),(4526,'2',1010,0,'Pagakku (Pakokku)'),(4527,'2',1011,0,'Mandalay'),(4528,'2',1011,0,'Meikhtila'),(4529,'2',1011,0,'Myingyan'),(4530,'2',1012,0,'Moulmein (Mawlamyine)'),(4531,'2',1013,0,'Pegu (Bago)'),(4532,'2',1013,0,'Prome (Pyay)'),(4533,'2',1014,0,'Sittwe (Akyab)'),(4534,'2',1015,0,'Rangoon (Yangon)'),(4535,'2',1016,0,'Monywa'),(4536,'2',1017,0,'Taunggyi (Taunggye)'),(4537,'2',1017,0,'Lashio (Lasho)'),(4538,'2',1018,0,'Mergui (Myeik)'),(4539,'2',1018,0,'Tavoy (Dawei)'),(4540,'2',1019,0,'Ulan Bator'),(4541,'2',1020,0,'Garapan'),(4542,'2',1021,0,'Xai-Xai'),(4543,'2',1021,0,'Gaza'),(4544,'2',1022,0,'Maxixe'),(4545,'2',1023,0,'Chimoio'),(4546,'2',1024,0,'Maputo'),(4547,'2',1024,0,'Matola'),(4548,'2',1025,0,'Nampula'),(4549,'2',1025,0,'NaÃ§ala-Porto'),(4550,'2',1026,0,'Beira'),(4551,'2',1027,0,'Tete'),(4552,'2',1028,0,'Quelimane'),(4553,'2',1028,0,'Mocuba'),(4554,'2',1028,0,'Gurue'),(4555,'2',1029,0,'NouÃ¢dhibou'),(4556,'2',1030,0,'Nouakchott'),(4557,'2',1031,0,'Plymouth'),(4558,'2',1032,0,'Fort-de-France'),(4559,'2',1033,0,'Beau Bassin-Rose Hill'),(4560,'2',1033,0,'Vacoas-Phoenix'),(4561,'2',1034,0,'Port-Louis'),(4562,'2',1035,0,'Blantyre'),(4563,'2',1036,0,'Lilongwe'),(4564,'2',1037,0,'Johor Baharu'),(4565,'2',1038,0,'Alor Setar'),(4566,'2',1038,0,'Sungai Petani'),(4567,'2',1039,0,'Kota Bharu'),(4568,'2',1040,0,'Seremban'),(4569,'2',1041,0,'Kuantan'),(4570,'2',1042,0,'Ipoh'),(4571,'2',1042,0,'Taiping'),(4572,'2',1043,0,'Pinang'),(4573,'2',1044,0,'Sandakan'),(4574,'2',1045,0,'Kuching'),(4575,'2',1045,0,'Sibu'),(4576,'2',1046,0,'Petaling Jaya'),(4577,'2',1046,0,'Kelang'),(4578,'2',1046,0,'Selayang Baru'),(4579,'2',1046,0,'Shah Alam'),(4580,'2',1047,0,'Kuala Terengganu'),(4581,'2',1048,0,'Kuala Lumpur'),(4582,'2',1049,0,'Mamoutzou'),(4583,'2',1050,0,'Windhoek'),(4584,'2',1051,0,'South Hill'),(4585,'2',1051,0,'The Valley'),(4586,'2',1051,0,'Oranjestad'),(4587,'2',1051,0,'Douglas'),(4588,'2',1051,0,'Gibraltar'),(4589,'2',1051,0,'Tamuning'),(4590,'2',1051,0,'AgaÃ±a'),(4591,'2',1051,0,'Flying Fish Cove'),(4592,'2',1051,0,'Monte-Carlo'),(4593,'2',1051,0,'Monaco-Ville'),(4594,'2',1051,0,'Yangor'),(4595,'2',1051,0,'Yaren'),(4596,'2',1051,0,'Alofi'),(4597,'2',1051,0,'Kingston'),(4598,'2',1051,0,'Adamstown'),(4599,'2',1051,0,'Singapore'),(4600,'2',1051,0,'NoumÃ©a'),(4601,'2',1051,0,'CittÃ  del Vaticano'),(4602,'2',1052,0,'Maradi'),(4603,'2',1053,0,'Niamey'),(4604,'2',1054,0,'Zinder'),(4605,'2',1055,0,'South Hill'),(4606,'2',1055,0,'The Valley'),(4607,'2',1055,0,'Oranjestad'),(4608,'2',1055,0,'Douglas'),(4609,'2',1055,0,'Gibraltar'),(4610,'2',1055,0,'Tamuning'),(4611,'2',1055,0,'AgaÃ±a'),(4612,'2',1055,0,'Flying Fish Cove'),(4613,'2',1055,0,'Monte-Carlo'),(4614,'2',1055,0,'Monaco-Ville'),(4615,'2',1055,0,'Yangor'),(4616,'2',1055,0,'Yaren'),(4617,'2',1055,0,'Alofi'),(4618,'2',1055,0,'Kingston'),(4619,'2',1055,0,'Adamstown'),(4620,'2',1055,0,'Singapore'),(4621,'2',1055,0,'NoumÃ©a'),(4622,'2',1055,0,'CittÃ  del Vaticano'),(4623,'2',1056,0,'Onitsha'),(4624,'2',1056,0,'Enugu'),(4625,'2',1056,0,'Awka'),(4626,'2',1057,0,'Kumo'),(4627,'2',1057,0,'Deba Habe'),(4628,'2',1057,0,'Gombe'),(4629,'2',1058,0,'Makurdi'),(4630,'2',1059,0,'Maiduguri'),(4631,'2',1060,0,'Calabar'),(4632,'2',1060,0,'Ugep'),(4633,'2',1061,0,'Benin City'),(4634,'2',1061,0,'Sapele'),(4635,'2',1061,0,'Warri'),(4636,'2',1062,0,'Abuja'),(4637,'2',1063,0,'Aba'),(4638,'2',1064,0,'Zaria'),(4639,'2',1064,0,'Kaduna'),(4640,'2',1065,0,'Kano'),(4641,'2',1066,0,'Katsina'),(4642,'2',1067,0,'Ilorin'),(4643,'2',1067,0,'Offa'),(4644,'2',1068,0,'Lagos'),(4645,'2',1068,0,'Mushin'),(4646,'2',1068,0,'Ikorodu'),(4647,'2',1068,0,'Shomolu'),(4648,'2',1068,0,'Agege'),(4649,'2',1068,0,'Epe'),(4650,'2',1069,0,'Minna'),(4651,'2',1069,0,'Bida'),(4652,'2',1070,0,'Abeokuta'),(4653,'2',1070,0,'Ijebu-Ode'),(4654,'2',1070,0,'Shagamu'),(4655,'2',1071,0,'Ado-Ekiti'),(4656,'2',1071,0,'Ikerre'),(4657,'2',1071,0,'Ilawe-Ekiti'),(4658,'2',1071,0,'Owo'),(4659,'2',1071,0,'Ondo'),(4660,'2',1071,0,'Akure'),(4661,'2',1071,0,'Oka-Akoko'),(4662,'2',1071,0,'Ikare'),(4663,'2',1071,0,'Ise-Ekiti'),(4664,'2',1072,0,'Ibadan'),(4665,'2',1072,0,'Ogbomosho'),(4666,'2',1072,0,'Oshogbo'),(4667,'2',1072,0,'Ilesha'),(4668,'2',1072,0,'Iwo'),(4669,'2',1072,0,'Ede'),(4670,'2',1072,0,'Ife'),(4671,'2',1072,0,'Ila'),(4672,'2',1072,0,'Oyo'),(4673,'2',1072,0,'Iseyin'),(4674,'2',1072,0,'Ilobu'),(4675,'2',1072,0,'Ikirun'),(4676,'2',1072,0,'Shaki'),(4677,'2',1072,0,'Effon-Alaiye'),(4678,'2',1072,0,'Ikire'),(4679,'2',1072,0,'Inisa'),(4680,'2',1072,0,'Igboho'),(4681,'2',1072,0,'Ejigbo'),(4682,'2',1073,0,'Jos'),(4683,'2',1073,0,'Lafia'),(4684,'2',1074,0,'Port Harcourt'),(4685,'2',1075,0,'Sokoto'),(4686,'2',1075,0,'Gusau'),(4687,'2',1076,0,'Chinandega'),(4688,'2',1077,0,'LeÃ³n'),(4689,'2',1078,0,'Managua'),(4690,'2',1079,0,'Masaya'),(4691,'2',1080,0,'South Hill'),(4692,'2',1080,0,'The Valley'),(4693,'2',1080,0,'Oranjestad'),(4694,'2',1080,0,'Douglas'),(4695,'2',1080,0,'Gibraltar'),(4696,'2',1080,0,'Tamuning'),(4697,'2',1080,0,'AgaÃ±a'),(4698,'2',1080,0,'Flying Fish Cove'),(4699,'2',1080,0,'Monte-Carlo'),(4700,'2',1080,0,'Monaco-Ville'),(4701,'2',1080,0,'Yangor'),(4702,'2',1080,0,'Yaren'),(4703,'2',1080,0,'Alofi'),(4704,'2',1080,0,'Kingston'),(4705,'2',1080,0,'Adamstown'),(4706,'2',1080,0,'Singapore'),(4707,'2',1080,0,'NoumÃ©a'),(4708,'2',1080,0,'CittÃ  del Vaticano'),(4709,'2',1081,0,'Emmen'),(4710,'2',1082,0,'Almere'),(4711,'2',1083,0,'Apeldoorn'),(4712,'2',1083,0,'Nijmegen'),(4713,'2',1083,0,'Arnhem'),(4714,'2',1083,0,'Ede'),(4715,'2',1084,0,'Groningen'),(4716,'2',1085,0,'Maastricht'),(4717,'2',1085,0,'Heerlen'),(4718,'2',1086,0,'Eindhoven'),(4719,'2',1086,0,'Tilburg'),(4720,'2',1086,0,'Breda'),(4721,'2',1086,0,'Â´s-Hertogenbosch'),(4722,'2',1087,0,'Amsterdam'),(4723,'2',1087,0,'Haarlem'),(4724,'2',1087,0,'Zaanstad'),(4725,'2',1087,0,'Haarlemmermeer'),(4726,'2',1087,0,'Alkmaar'),(4727,'2',1088,0,'Enschede'),(4728,'2',1088,0,'Zwolle'),(4729,'2',1089,0,'Utrecht'),(4730,'2',1089,0,'Amersfoort'),(4731,'2',1090,0,'Rotterdam'),(4732,'2',1090,0,'Haag'),(4733,'2',1090,0,'Dordrecht'),(4734,'2',1090,0,'Leiden'),(4735,'2',1090,0,'Zoetermeer'),(4736,'2',1090,0,'Delft'),(4737,'2',1091,0,'BÃ¦rum'),(4738,'2',1092,0,'Bergen'),(4739,'2',1093,0,'Oslo'),(4740,'2',1094,0,'Stavanger'),(4741,'2',1095,0,'Trondheim'),(4742,'2',1096,0,'Suva'),(4743,'2',1096,0,'Nyeri'),(4744,'2',1096,0,'Kathmandu'),(4745,'2',1096,0,'Lalitapur'),(4746,'2',1096,0,'Birgunj'),(4747,'2',1096,0,'San Lorenzo'),(4748,'2',1096,0,'LambarÃ©'),(4749,'2',1096,0,'Fernando de la Mora'),(4750,'2',1096,0,'Kabwe'),(4751,'2',1096,0,'Kandy'),(4752,'2',1096,0,'Kampala'),(4753,'2',1097,0,'Machakos'),(4754,'2',1097,0,'Meru'),(4755,'2',1097,0,'Biratnagar'),(4756,'2',1098,0,'Sekondi-Takoradi'),(4757,'2',1098,0,'Pokhara'),(4758,'2',1098,0,'Freetown'),(4759,'2',1098,0,'Colombo'),(4760,'2',1098,0,'Dehiwala'),(4761,'2',1098,0,'Moratuwa'),(4762,'2',1098,0,'Sri Jayawardenepura Kotte'),(4763,'2',1098,0,'Negombo'),(4764,'2',1099,0,'South Hill'),(4765,'2',1099,0,'The Valley'),(4766,'2',1099,0,'Oranjestad'),(4767,'2',1099,0,'Douglas'),(4768,'2',1099,0,'Gibraltar'),(4769,'2',1099,0,'Tamuning'),(4770,'2',1099,0,'AgaÃ±a'),(4771,'2',1099,0,'Flying Fish Cove'),(4772,'2',1099,0,'Monte-Carlo'),(4773,'2',1099,0,'Monaco-Ville'),(4774,'2',1099,0,'Yangor'),(4775,'2',1099,0,'Yaren'),(4776,'2',1099,0,'Alofi'),(4777,'2',1099,0,'Kingston'),(4778,'2',1099,0,'Adamstown'),(4779,'2',1099,0,'Singapore'),(4780,'2',1099,0,'NoumÃ©a'),(4781,'2',1099,0,'CittÃ  del Vaticano'),(4782,'2',1100,0,'Auckland'),(4783,'2',1100,0,'Manukau'),(4784,'2',1100,0,'North Shore'),(4785,'2',1100,0,'Waitakere'),(4786,'2',1101,0,'Christchurch'),(4787,'2',1102,0,'Dunedin'),(4788,'2',1103,0,'Hamilton'),(4789,'2',1103,0,'Hamilton'),(4790,'2',1104,0,'Wellington'),(4791,'2',1104,0,'Lower Hutt'),(4792,'2',1105,0,'Suhar'),(4793,'2',1106,0,'al-Sib'),(4794,'2',1106,0,'Bawshar'),(4795,'2',1106,0,'Masqat'),(4796,'2',1107,0,'Salala'),(4797,'2',1108,0,'Quetta'),(4798,'2',1108,0,'Khuzdar'),(4799,'2',1109,0,'Islamabad'),(4800,'2',1110,0,'Peshawar'),(4801,'2',1110,0,'Mardan'),(4802,'2',1110,0,'Mingora'),(4803,'2',1110,0,'Kohat'),(4804,'2',1110,0,'Abottabad'),(4805,'2',1110,0,'Dera Ismail Khan'),(4806,'2',1110,0,'Nowshera'),(4807,'2',1111,0,'Ludhiana'),(4808,'2',1111,0,'Amritsar'),(4809,'2',1111,0,'Jalandhar (Jullundur)'),(4810,'2',1111,0,'Patiala'),(4811,'2',1111,0,'Bhatinda (Bathinda)'),(4812,'2',1111,0,'Pathankot'),(4813,'2',1111,0,'Hoshiarpur'),(4814,'2',1111,0,'Moga'),(4815,'2',1111,0,'Abohar'),(4816,'2',1111,0,'Lahore'),(4817,'2',1111,0,'Faisalabad'),(4818,'2',1111,0,'Rawalpindi'),(4819,'2',1111,0,'Multan'),(4820,'2',1111,0,'Gujranwala'),(4821,'2',1111,0,'Sargodha'),(4822,'2',1111,0,'Sialkot'),(4823,'2',1111,0,'Bahawalpur'),(4824,'2',1111,0,'Jhang'),(4825,'2',1111,0,'Sheikhupura'),(4826,'2',1111,0,'Gujrat'),(4827,'2',1111,0,'Kasur'),(4828,'2',1111,0,'Rahim Yar Khan'),(4829,'2',1111,0,'Sahiwal'),(4830,'2',1111,0,'Okara'),(4831,'2',1111,0,'Wah'),(4832,'2',1111,0,'Dera Ghazi Khan'),(4833,'2',1111,0,'Chiniot'),(4834,'2',1111,0,'Kamoke'),(4835,'2',1111,0,'Mandi Burewala'),(4836,'2',1111,0,'Jhelum'),(4837,'2',1111,0,'Sadiqabad'),(4838,'2',1111,0,'Khanewal'),(4839,'2',1111,0,'Hafizabad'),(4840,'2',1111,0,'Muzaffargarh'),(4841,'2',1111,0,'Khanpur'),(4842,'2',1111,0,'Gojra'),(4843,'2',1111,0,'Bahawalnagar'),(4844,'2',1111,0,'Muridke'),(4845,'2',1111,0,'Pak Pattan'),(4846,'2',1111,0,'Jaranwala'),(4847,'2',1111,0,'Chishtian Mandi'),(4848,'2',1111,0,'Daska'),(4849,'2',1111,0,'Mandi Bahauddin'),(4850,'2',1111,0,'Ahmadpur East'),(4851,'2',1111,0,'Kamalia'),(4852,'2',1111,0,'Vihari'),(4853,'2',1111,0,'Wazirabad'),(4854,'2',1112,0,'Mirpur Khas'),(4855,'2',1112,0,'Nawabshah'),(4856,'2',1112,0,'Jacobabad'),(4857,'2',1112,0,'Shikarpur'),(4858,'2',1112,0,'Tando Adam'),(4859,'2',1112,0,'Khairpur'),(4860,'2',1112,0,'Dadu'),(4861,'2',1113,0,'Karachi'),(4862,'2',1113,0,'Hyderabad'),(4863,'2',1113,0,'Sukkur'),(4864,'2',1113,0,'Larkana'),(4865,'2',1114,0,'Ciudad de PanamÃ¡'),(4866,'2',1115,0,'San Miguelito'),(4867,'2',1116,0,'South Hill'),(4868,'2',1116,0,'The Valley'),(4869,'2',1116,0,'Oranjestad'),(4870,'2',1116,0,'Douglas'),(4871,'2',1116,0,'Gibraltar'),(4872,'2',1116,0,'Tamuning'),(4873,'2',1116,0,'AgaÃ±a'),(4874,'2',1116,0,'Flying Fish Cove'),(4875,'2',1116,0,'Monte-Carlo'),(4876,'2',1116,0,'Monaco-Ville'),(4877,'2',1116,0,'Yangor'),(4878,'2',1116,0,'Yaren'),(4879,'2',1116,0,'Alofi'),(4880,'2',1116,0,'Kingston'),(4881,'2',1116,0,'Adamstown'),(4882,'2',1116,0,'Singapore'),(4883,'2',1116,0,'NoumÃ©a'),(4884,'2',1116,0,'CittÃ  del Vaticano'),(4885,'2',1117,0,'Chimbote'),(4886,'2',1118,0,'Arequipa'),(4887,'2',1119,0,'Ayacucho'),(4888,'2',1120,0,'Cajamarca'),(4889,'2',1121,0,'Callao'),(4890,'2',1121,0,'Ventanilla'),(4891,'2',1122,0,'Cusco'),(4892,'2',1123,0,'HuÃ¡nuco'),(4893,'2',1124,0,'Ica'),(4894,'2',1124,0,'Chincha Alta'),(4895,'2',1125,0,'Huancayo'),(4896,'2',1126,0,'Nueva San Salvador'),(4897,'2',1126,0,'Trujillo'),(4898,'2',1127,0,'Chiclayo'),(4899,'2',1128,0,'Lima'),(4900,'2',1129,0,'Iquitos'),(4901,'2',1130,0,'Piura'),(4902,'2',1130,0,'Sullana'),(4903,'2',1130,0,'Castilla'),(4904,'2',1131,0,'Juliaca'),(4905,'2',1131,0,'Puno'),(4906,'2',1132,0,'Tacna'),(4907,'2',1133,0,'Pucallpa'),(4908,'2',1134,0,'Sultan Kudarat'),(4909,'2',1135,0,'Legazpi'),(4910,'2',1135,0,'Naga'),(4911,'2',1135,0,'Tabaco'),(4912,'2',1135,0,'Daraga (Locsin)'),(4913,'2',1135,0,'Sorsogon'),(4914,'2',1135,0,'Ligao'),(4915,'2',1136,0,'Tuguegarao'),(4916,'2',1136,0,'Ilagan'),(4917,'2',1136,0,'Santiago'),(4918,'2',1136,0,'Cauayan'),(4919,'2',1137,0,'Baguio'),(4920,'2',1138,0,'Butuan'),(4921,'2',1138,0,'Surigao'),(4922,'2',1138,0,'Bislig'),(4923,'2',1138,0,'Bayugan'),(4924,'2',1139,0,'San Jose del Monte'),(4925,'2',1139,0,'Angeles'),(4926,'2',1139,0,'Tarlac'),(4927,'2',1139,0,'Cabanatuan'),(4928,'2',1139,0,'San Fernando'),(4929,'2',1139,0,'Olongapo'),(4930,'2',1139,0,'Malolos'),(4931,'2',1139,0,'Mabalacat'),(4932,'2',1139,0,'Meycauayan'),(4933,'2',1139,0,'Santa Maria'),(4934,'2',1139,0,'Lubao'),(4935,'2',1139,0,'San Miguel'),(4936,'2',1139,0,'Baliuag'),(4937,'2',1139,0,'Concepcion'),(4938,'2',1139,0,'Hagonoy'),(4939,'2',1139,0,'Mexico'),(4940,'2',1139,0,'San Jose'),(4941,'2',1139,0,'Arayat'),(4942,'2',1139,0,'Marilao'),(4943,'2',1139,0,'Talavera'),(4944,'2',1139,0,'Guagua'),(4945,'2',1139,0,'Capas'),(4946,'2',1140,0,'Iligan'),(4947,'2',1140,0,'Cotabato'),(4948,'2',1140,0,'Marawi'),(4949,'2',1140,0,'Midsayap'),(4950,'2',1140,0,'Kidapawan'),(4951,'2',1141,0,'Cebu'),(4952,'2',1141,0,'Mandaue'),(4953,'2',1141,0,'Lapu-Lapu'),(4954,'2',1141,0,'Talisay'),(4955,'2',1141,0,'Toledo'),(4956,'2',1141,0,'Dumaguete'),(4957,'2',1141,0,'Bayawan (Tulong)'),(4958,'2',1141,0,'Danao'),(4959,'2',1142,0,'Tacloban'),(4960,'2',1142,0,'Ormoc'),(4961,'2',1142,0,'Calbayog'),(4962,'2',1142,0,'Baybay'),(4963,'2',1143,0,'San Carlos'),(4964,'2',1143,0,'Dagupan'),(4965,'2',1143,0,'Malasiqui'),(4966,'2',1143,0,'Urdaneta'),(4967,'2',1143,0,'San Fernando'),(4968,'2',1143,0,'Bayambang'),(4969,'2',1143,0,'Laoag'),(4970,'2',1144,0,'Quezon'),(4971,'2',1144,0,'Manila'),(4972,'2',1144,0,'Kalookan'),(4973,'2',1144,0,'Pasig'),(4974,'2',1144,0,'Valenzuela'),(4975,'2',1144,0,'Las Piñas'),(4976,'2',1144,0,'Taguig'),(4977,'2',1144,0,'Parañaque'),(4978,'2',1144,0,'Makati'),(4979,'2',1144,0,'Marikina'),(4980,'2',1144,0,'Muntinlupa'),(4981,'2',1144,0,'Pasay'),(4982,'2',1144,0,'Malabon'),(4983,'2',1144,0,'Mandaluyong'),(4984,'2',1144,0,'Navotas'),(4985,'2',1144,0,'San Juan del Monte'),(4986,'2',1145,0,'Cagayan de Oro'),(4987,'2',1145,0,'Valencia'),(4988,'2',1145,0,'Malaybalay'),(4989,'2',1145,0,'Ozamis'),(4990,'2',1145,0,'Gingoog'),(4991,'2',1146,0,'Davao'),(4992,'2',1146,0,'General Santos'),(4993,'2',1146,0,'Tagum'),(4994,'2',1146,0,'Panabo'),(4995,'2',1146,0,'Koronadal'),(4996,'2',1146,0,'Digos'),(4997,'2',1146,0,'Polomolok'),(4998,'2',1146,0,'Mati'),(4999,'2',1146,0,'Malita'),(5000,'2',1146,0,'Malungon'),(5001,'2',1147,0,'Antipolo'),(5002,'2',1147,0,'DasmariÃ±as'),(5003,'2',1147,0,'Bacoor'),(5004,'2',1147,0,'Calamba'),(5005,'2',1147,0,'Batangas'),(5006,'2',1147,0,'Cainta'),(5007,'2',1147,0,'San Pedro'),(5008,'2',1147,0,'Lipa'),(5009,'2',1147,0,'San Pablo'),(5010,'2',1147,0,'BiÃ±an'),(5011,'2',1147,0,'Taytay'),(5012,'2',1147,0,'Lucena'),(5013,'2',1147,0,'Imus'),(5014,'2',1147,0,'Binangonan'),(5015,'2',1147,0,'Santa Rosa'),(5016,'2',1147,0,'Puerto Princesa'),(5017,'2',1147,0,'Silang'),(5018,'2',1147,0,'San Mateo'),(5019,'2',1147,0,'Tanauan'),(5020,'2',1147,0,'Rodriguez (Montalban)'),(5021,'2',1147,0,'Sariaya'),(5022,'2',1147,0,'General Mariano Alvarez'),(5023,'2',1147,0,'San Jose'),(5024,'2',1147,0,'Tanza'),(5025,'2',1147,0,'General Trias'),(5026,'2',1147,0,'Cabuyao'),(5027,'2',1147,0,'Calapan'),(5028,'2',1147,0,'Cavite'),(5029,'2',1147,0,'Nasugbu'),(5030,'2',1147,0,'Santa Cruz'),(5031,'2',1147,0,'Candelaria'),(5032,'2',1148,0,'Zamboanga'),(5033,'2',1148,0,'Pagadian'),(5034,'2',1148,0,'Dipolog'),(5035,'2',1149,0,'Bacolod'),(5036,'2',1149,0,'Iloilo'),(5037,'2',1149,0,'Kabankalan'),(5038,'2',1149,0,'Cadiz'),(5039,'2',1149,0,'Bago'),(5040,'2',1149,0,'Sagay'),(5041,'2',1149,0,'Roxas'),(5042,'2',1149,0,'San Carlos'),(5043,'2',1149,0,'Silay'),(5044,'2',1150,0,'Koror'),(5045,'2',1151,0,'Port Moresby'),(5046,'2',1152,0,'Wroclaw'),(5047,'2',1152,0,'Walbrzych'),(5048,'2',1152,0,'Legnica'),(5049,'2',1152,0,'Jelenia GÃ³ra'),(5050,'2',1153,0,'Bydgoszcz'),(5051,'2',1153,0,'Torun'),(5052,'2',1153,0,'Wloclawek'),(5053,'2',1153,0,'Grudziadz'),(5054,'2',1154,0,'LÃ³dz'),(5055,'2',1155,0,'Lublin'),(5056,'2',1156,0,'GorzÃw Wielkopolski'),(5057,'2',1156,0,'Zielona GÃra'),(5058,'2',1157,0,'KrakÃw'),(5059,'2',1157,0,'TarnÃw'),(5060,'2',1158,0,'Warszawa'),(5061,'2',1158,0,'Radom'),(5062,'2',1158,0,'Plock'),(5063,'2',1159,0,'Opole'),(5064,'2',1160,0,'RzeszÃ³w'),(5065,'2',1161,0,'Bialystok'),(5066,'2',1162,0,'Gdansk'),(5067,'2',1162,0,'Gdynia'),(5068,'2',1162,0,'Slupsk'),(5069,'2',1163,0,'Katowice'),(5070,'2',1163,0,'Czestochowa'),(5071,'2',1163,0,'Sosnowiec'),(5072,'2',1163,0,'Gliwice'),(5073,'2',1163,0,'Bytom'),(5074,'2',1163,0,'Zabrze'),(5075,'2',1163,0,'Bielsko-Biala'),(5076,'2',1163,0,'Ruda Slaska'),(5077,'2',1163,0,'Rybnik'),(5078,'2',1163,0,'Tychy'),(5079,'2',1163,0,'Dabrowa GÃ³rnicza'),(5080,'2',1163,0,'ChorzÃw'),(5081,'2',1163,0,'Jastrzebie-ZdrÃj'),(5082,'2',1163,0,'Jaworzno'),(5083,'2',1164,0,'Kielce'),(5084,'2',1165,0,'Olsztyn'),(5085,'2',1165,0,'Elblag'),(5086,'2',1166,0,'Poznan'),(5087,'2',1166,0,'Kalisz'),(5088,'2',1167,0,'Szczecin'),(5089,'2',1167,0,'Koszalin'),(5090,'2',1168,0,'Arecibo'),(5091,'2',1169,0,'BayamÃn'),(5092,'2',1170,0,'Caguas'),(5093,'2',1171,0,'Carolina'),(5094,'2',1172,0,'Guaynabo'),(5095,'2',1173,0,'MayagÃez'),(5096,'2',1174,0,'Ponce'),(5097,'2',1175,0,'San Juan'),(5098,'2',1175,0,'San Juan'),(5099,'2',1176,0,'Toa Baja'),(5100,'2',1177,0,'Kanggye'),(5101,'2',1178,0,'Hamhung'),(5102,'2',1179,0,'Chongjin'),(5103,'2',1179,0,'Kimchaek'),(5104,'2',1180,0,'Haeju'),(5105,'2',1181,0,'Sariwon'),(5106,'2',1182,0,'Kaesong'),(5107,'2',1183,0,'Wonsan'),(5108,'2',1184,0,'Nampo'),(5109,'2',1185,0,'Phyongsong'),(5110,'2',1186,0,'Sinuiju'),(5111,'2',1187,0,'Pyongyang'),(5112,'2',1188,0,'Hyesan'),(5113,'2',1189,0,'Braga'),(5114,'2',1190,0,'CoÃ­mbra'),(5115,'2',1191,0,'Lisboa'),(5116,'2',1191,0,'Amadora'),(5117,'2',1191,0,'Stockholm'),(5118,'2',1192,0,'Porto'),(5119,'2',1193,0,'Ciudad del Este'),(5120,'2',1194,0,'AsunciÃ³n'),(5121,'2',1195,0,'Suva'),(5122,'2',1195,0,'Nyeri'),(5123,'2',1195,0,'Kathmandu'),(5124,'2',1195,0,'Lalitapur'),(5125,'2',1195,0,'Birgunj'),(5126,'2',1195,0,'San Lorenzo'),(5127,'2',1195,0,'LambarÃ'),(5128,'2',1195,0,'Fernando de la Mora'),(5129,'2',1195,0,'Kabwe'),(5130,'2',1195,0,'Kandy'),(5131,'2',1195,0,'Kampala'),(5132,'2',1196,0,'Xai-Xai'),(5133,'2',1196,0,'Gaza'),(5134,'2',1197,0,'Hebron'),(5135,'2',1198,0,'Khan Yunis'),(5136,'2',1199,0,'Nablus'),(5137,'2',1200,0,'Jabaliya'),(5138,'2',1201,0,'Rafah'),(5139,'2',1202,0,'Faaa'),(5140,'2',1202,0,'Papeete'),(5141,'2',1203,0,'Doha'),(5142,'2',1204,0,'Saint-Denis'),(5143,'2',1205,0,'Arad'),(5144,'2',1206,0,'Pitesti'),(5145,'2',1207,0,'Bacau'),(5146,'2',1208,0,'Oradea'),(5147,'2',1209,0,'Botosani'),(5148,'2',1210,0,'Braila'),(5149,'2',1211,0,'Brasov'),(5150,'2',1212,0,'Bucuresti'),(5151,'2',1213,0,'Buzau'),(5152,'2',1214,0,'Resita'),(5153,'2',1215,0,'Cluj-Napoca'),(5154,'2',1216,0,'Constanta'),(5155,'2',1217,0,'TÃ¢rgoviste'),(5156,'2',1218,0,'Craiova'),(5157,'2',1219,0,'Galati'),(5158,'2',1220,0,'TÃ¢rgu Jiu'),(5159,'2',1221,0,'Iasi'),(5160,'2',1222,0,'Baia Mare'),(5161,'2',1223,0,'Drobeta-Turnu Severin'),(5162,'2',1224,0,'TÃ¢rgu Mures'),(5163,'2',1225,0,'Piatra Neamt'),(5164,'2',1226,0,'Ploiesti'),(5165,'2',1227,0,'Satu Mare'),(5166,'2',1228,0,'Sibiu'),(5167,'2',1229,0,'Suceava'),(5168,'2',1230,0,'Timisoara'),(5169,'2',1231,0,'Tulcea'),(5170,'2',1232,0,'RÃ¢mnicu VÃ¢lcea'),(5171,'2',1233,0,'Focsani'),(5172,'2',1234,0,'Maikop'),(5173,'2',1235,0,'Barnaul'),(5174,'2',1235,0,'Bijsk'),(5175,'2',1235,0,'Rubtsovsk'),(5176,'2',1236,0,'BlagoveÅ¡tÅ¡ensk'),(5177,'2',1237,0,'Arkangeli'),(5178,'2',1237,0,'Severodvinsk'),(5179,'2',1238,0,'Astrahan'),(5180,'2',1239,0,'Ufa'),(5181,'2',1239,0,'Sterlitamak'),(5182,'2',1239,0,'Salavat'),(5183,'2',1239,0,'Neftekamsk'),(5184,'2',1239,0,'Oktjabrski'),(5185,'2',1240,0,'Belgorod'),(5186,'2',1240,0,'Staryi Oskol'),(5187,'2',1241,0,'Brjansk'),(5188,'2',1242,0,'Ulan-Ude'),(5189,'2',1243,0,'MahatÅ¡kala'),(5190,'2',1243,0,'Derbent'),(5191,'2',1244,0,'Habarovsk'),(5192,'2',1244,0,'Komsomolsk-na-Amure'),(5193,'2',1245,0,'Abakan'),(5194,'2',1246,0,'Surgut'),(5195,'2',1246,0,'Niznevartovsk'),(5196,'2',1246,0,'Neftejugansk'),(5197,'2',1247,0,'Irkutsk'),(5198,'2',1247,0,'Bratsk'),(5199,'2',1247,0,'Angarsk'),(5200,'2',1247,0,'Ust-Ilimsk'),(5201,'2',1247,0,'Usolje-Sibirskoje'),(5202,'2',1248,0,'Ivanovo'),(5203,'2',1248,0,'KineÅ¡ma'),(5204,'2',1249,0,'Jaroslavl'),(5205,'2',1249,0,'Rybinsk'),(5206,'2',1250,0,'NaltÅ¡ik'),(5207,'2',1251,0,'Kaliningrad'),(5208,'2',1252,0,'Elista'),(5209,'2',1253,0,'Kaluga'),(5210,'2',1253,0,'Obninsk'),(5211,'2',1254,0,'Petropavlovsk-KamtÅ¡atski'),(5212,'2',1255,0,'TÅ¡erkessk'),(5213,'2',1256,0,'Petroskoi'),(5214,'2',1257,0,'Novokuznetsk'),(5215,'2',1257,0,'Kemerovo'),(5216,'2',1257,0,'Prokopjevsk'),(5217,'2',1257,0,'Leninsk-Kuznetski'),(5218,'2',1257,0,'Kiseljovsk'),(5219,'2',1257,0,'MezduretÅ¡ensk'),(5220,'2',1257,0,'Anzero-Sudzensk'),(5221,'2',1258,0,'Kirov'),(5222,'2',1258,0,'Kirovo-TÅ¡epetsk'),(5223,'2',1259,0,'Syktyvkar'),(5224,'2',1259,0,'Uhta'),(5225,'2',1259,0,'Vorkuta'),(5226,'2',1260,0,'Kostroma'),(5227,'2',1261,0,'Krasnodar'),(5228,'2',1261,0,'SotÅ¡i'),(5229,'2',1261,0,'Novorossijsk'),(5230,'2',1261,0,'Armavir'),(5231,'2',1262,0,'Krasnojarsk'),(5232,'2',1262,0,'Norilsk'),(5233,'2',1262,0,'AtÅ¡insk'),(5234,'2',1262,0,'Kansk'),(5235,'2',1262,0,'Zeleznogorsk'),(5236,'2',1263,0,'Kurgan'),(5237,'2',1264,0,'Kursk'),(5238,'2',1264,0,'Zeleznogorsk'),(5239,'2',1265,0,'Lipetsk'),(5240,'2',1265,0,'Jelets'),(5241,'2',1266,0,'Magadan'),(5242,'2',1267,0,'JoÅ¡kar-Ola'),(5243,'2',1268,0,'Saransk'),(5244,'2',1269,0,'Moscow'),(5245,'2',1269,0,'Zelenograd'),(5246,'2',1270,0,'Podolsk'),(5247,'2',1270,0,'Ljubertsy'),(5248,'2',1270,0,'MytiÅ¡tÅ¡i'),(5249,'2',1270,0,'Kolomna'),(5250,'2',1270,0,'Elektrostal'),(5251,'2',1270,0,'Himki'),(5252,'2',1270,0,'BalaÅ¡iha'),(5253,'2',1270,0,'Korolev'),(5254,'2',1270,0,'Serpuhov'),(5255,'2',1270,0,'Odintsovo'),(5256,'2',1270,0,'Orehovo-Zujevo'),(5257,'2',1270,0,'Noginsk'),(5258,'2',1270,0,'Sergijev Posad'),(5259,'2',1270,0,'Å tÅ¡olkovo'),(5260,'2',1270,0,'Zeleznodoroznyi'),(5261,'2',1270,0,'Zukovski'),(5262,'2',1270,0,'Krasnogorsk'),(5263,'2',1270,0,'Klin'),(5264,'2',1271,0,'Murmansk'),(5265,'2',1272,0,'Nizni Novgorod'),(5266,'2',1272,0,'Dzerzinsk'),(5267,'2',1272,0,'Arzamas'),(5268,'2',1273,0,'Vladikavkaz'),(5269,'2',1274,0,'Veliki Novgorod'),(5270,'2',1275,0,'Novosibirsk'),(5271,'2',1276,0,'Omsk'),(5272,'2',1277,0,'Orenburg'),(5273,'2',1277,0,'Orsk'),(5274,'2',1277,0,'Novotroitsk'),(5275,'2',1278,0,'Orjol'),(5276,'2',1279,0,'Penza'),(5277,'2',1279,0,'Kuznetsk'),(5278,'2',1280,0,'Perm'),(5279,'2',1280,0,'Berezniki'),(5280,'2',1280,0,'Solikamsk'),(5281,'2',1280,0,'TÅ¡aikovski'),(5282,'2',1281,0,'St Petersburg'),(5283,'2',1281,0,'Kolpino'),(5284,'2',1281,0,'PuÅ¡kin'),(5285,'2',1282,0,'Pihkova'),(5286,'2',1282,0,'Velikije Luki'),(5287,'2',1283,0,'Vladivostok'),(5288,'2',1283,0,'Nahodka'),(5289,'2',1283,0,'Ussurijsk'),(5290,'2',1284,0,'Rjazan'),(5291,'2',1285,0,'Rostov-na-Donu'),(5292,'2',1285,0,'Taganrog'),(5293,'2',1285,0,'Å ahty'),(5294,'2',1285,0,'NovotÅ¡erkassk'),(5295,'2',1285,0,'Volgodonsk'),(5296,'2',1285,0,'NovoÅ¡ahtinsk'),(5297,'2',1285,0,'Bataisk'),(5298,'2',1286,0,'Jakutsk'),(5299,'2',1287,0,'Juzno-Sahalinsk'),(5300,'2',1288,0,'Samara'),(5301,'2',1288,0,'Toljatti'),(5302,'2',1288,0,'Syzran'),(5303,'2',1288,0,'NovokuibyÅ¡evsk'),(5304,'2',1289,0,'Saratov'),(5305,'2',1289,0,'Balakovo'),(5306,'2',1289,0,'Engels'),(5307,'2',1289,0,'BalaÅ¡ov'),(5308,'2',1290,0,'Smolensk'),(5309,'2',1291,0,'Stavropol'),(5310,'2',1291,0,'Nevinnomyssk'),(5311,'2',1291,0,'Pjatigorsk'),(5312,'2',1291,0,'Kislovodsk'),(5313,'2',1291,0,'Jessentuki'),(5314,'2',1292,0,'Jekaterinburg'),(5315,'2',1292,0,'Nizni Tagil'),(5316,'2',1292,0,'Kamensk-Uralski'),(5317,'2',1292,0,'Pervouralsk'),(5318,'2',1292,0,'Serov'),(5319,'2',1292,0,'Novouralsk'),(5320,'2',1293,0,'Tambov'),(5321,'2',1293,0,'MitÅ¡urinsk'),(5322,'2',1294,0,'Kazan'),(5323,'2',1294,0,'Nabereznyje TÅ¡elny'),(5324,'2',1294,0,'Niznekamsk'),(5325,'2',1294,0,'Almetjevsk'),(5326,'2',1294,0,'Zelenodolsk'),(5327,'2',1294,0,'Bugulma'),(5328,'2',1295,0,'Tjumen'),(5329,'2',1295,0,'Tobolsk'),(5330,'2',1296,0,'Tomsk'),(5331,'2',1296,0,'Seversk'),(5332,'2',1297,0,'Tula'),(5333,'2',1297,0,'Novomoskovsk'),(5334,'2',1298,0,'Tver'),(5335,'2',1299,0,'Kyzyl'),(5336,'2',1300,0,'TÅ¡eljabinsk'),(5337,'2',1300,0,'Magnitogorsk'),(5338,'2',1300,0,'Zlatoust'),(5339,'2',1300,0,'Miass'),(5340,'2',1301,0,'Grozny'),(5341,'2',1302,0,'TÅ¡ita'),(5342,'2',1303,0,'TÅ¡eboksary'),(5343,'2',1303,0,'NovotÅ¡eboksarsk'),(5344,'2',1304,0,'Izevsk'),(5345,'2',1304,0,'Glazov'),(5346,'2',1304,0,'Sarapul'),(5347,'2',1304,0,'Votkinsk'),(5348,'2',1305,0,'Uljanovsk'),(5349,'2',1305,0,'Dimitrovgrad'),(5350,'2',1306,0,'Vladimir'),(5351,'2',1306,0,'Kovrov'),(5352,'2',1306,0,'Murom'),(5353,'2',1307,0,'Volgograd'),(5354,'2',1307,0,'Volzski'),(5355,'2',1307,0,'KamyÅ¡in'),(5356,'2',1308,0,'TÅ¡erepovets'),(5357,'2',1308,0,'Vologda'),(5358,'2',1309,0,'Voronez'),(5359,'2',1310,0,'Nojabrsk'),(5360,'2',1310,0,'Novyi Urengoi'),(5361,'2',1311,0,'Kigali'),(5362,'2',1312,0,'AraÂ´ar'),(5363,'2',1313,0,'Burayda'),(5364,'2',1314,0,'Zagazig'),(5365,'2',1314,0,'Bilbays'),(5366,'2',1314,0,'al-Dammam'),(5367,'2',1314,0,'al-Hufuf'),(5368,'2',1314,0,'al-Mubarraz'),(5369,'2',1314,0,'al-Khubar'),(5370,'2',1314,0,'Jubayl'),(5371,'2',1314,0,'Hafar al-Batin'),(5372,'2',1314,0,'al-Tuqba'),(5373,'2',1314,0,'al-Qatif'),(5374,'2',1315,0,'Khamis Mushayt'),(5375,'2',1315,0,'Abha'),(5376,'2',1316,0,'Hail'),(5377,'2',1317,0,'Medina'),(5378,'2',1317,0,'Yanbu'),(5379,'2',1318,0,'Jedda'),(5380,'2',1318,0,'Mekka'),(5381,'2',1318,0,'al-Taif'),(5382,'2',1318,0,'al-Hawiya'),(5383,'2',1319,0,'Najran'),(5384,'2',1320,0,'Unayza'),(5385,'2',1321,0,'al-Kharj'),(5386,'2',1322,0,'Riyadh'),(5387,'2',1323,0,'Tabuk'),(5388,'2',1324,0,'Kusti'),(5389,'2',1325,0,'Port Sudan'),(5390,'2',1326,0,'Wad Madani'),(5391,'2',1327,0,'al-Qadarif'),(5392,'2',1328,0,'Juba'),(5393,'2',1329,0,'Nyala'),(5394,'2',1330,0,'al-Fashir'),(5395,'2',1331,0,'Kassala'),(5396,'2',1332,0,'Omdurman'),(5397,'2',1332,0,'Khartum'),(5398,'2',1332,0,'Sharq al-Nil'),(5399,'2',1333,0,'Obeid'),(5400,'2',1334,0,'Pikine'),(5401,'2',1334,0,'Dakar'),(5402,'2',1334,0,'Rufisque'),(5403,'2',1335,0,'Diourbel'),(5404,'2',1336,0,'Kaolack'),(5405,'2',1337,0,'Saint-Louis'),(5406,'2',1338,0,'ThiÃs'),(5407,'2',1338,0,'Mbour'),(5408,'2',1339,0,'Ziguinchor'),(5409,'2',1340,0,'South Hill'),(5410,'2',1340,0,'The Valley'),(5411,'2',1340,0,'Oranjestad'),(5412,'2',1340,0,'Douglas'),(5413,'2',1340,0,'Gibraltar'),(5414,'2',1340,0,'Tamuning'),(5415,'2',1340,0,'AgaÃ±a'),(5416,'2',1340,0,'Flying Fish Cove'),(5417,'2',1340,0,'Monte-Carlo'),(5418,'2',1340,0,'Monaco-Ville'),(5419,'2',1340,0,'Yangor'),(5420,'2',1340,0,'Yaren'),(5421,'2',1340,0,'Alofi'),(5422,'2',1340,0,'Kingston'),(5423,'2',1340,0,'Adamstown'),(5424,'2',1340,0,'Singapore'),(5425,'2',1340,0,'NoumÃa'),(5426,'2',1340,0,'CittÃ  del Vaticano'),(5427,'2',1341,0,'Jamestown'),(5428,'2',1342,0,'Longyearbyen'),(5429,'2',1343,0,'Honiara'),(5430,'2',1344,0,'Sekondi-Takoradi'),(5431,'2',1344,0,'Pokhara'),(5432,'2',1344,0,'Freetown'),(5433,'2',1344,0,'Colombo'),(5434,'2',1344,0,'Dehiwala'),(5435,'2',1344,0,'Moratuwa'),(5436,'2',1344,0,'Sri Jayawardenepura Kotte'),(5437,'2',1344,0,'Negombo'),(5438,'2',1345,0,'Nueva San Salvador'),(5439,'2',1345,0,'Trujillo'),(5440,'2',1346,0,'San Miguel'),(5441,'2',1347,0,'San Salvador'),(5442,'2',1347,0,'Mejicanos'),(5443,'2',1347,0,'Soyapango'),(5444,'2',1347,0,'Apopa'),(5445,'2',1348,0,'Santa Ana'),(5446,'2',1349,0,'San Marino'),(5447,'2',1350,0,'Serravalle'),(5448,'2',1351,0,'Mogadishu'),(5449,'2',1352,0,'Kismaayo'),(5450,'2',1353,0,'Hargeysa'),(5451,'2',1354,0,'Saint-Pierre'),(5452,'2',1355,0,'SÃ£o TomÃ'),(5453,'2',1356,0,'Paramaribo'),(5454,'2',1357,0,'Bratislava'),(5455,'2',1358,0,'KoÅ¡ice'),(5456,'2',1358,0,'PreÅ¡ov'),(5457,'2',1359,0,'Ljubljana'),(5458,'2',1360,0,'Maribor'),(5459,'2',1361,0,'Ã–rebro'),(5460,'2',1362,0,'LinkÃ¶ping'),(5461,'2',1362,0,'NorrkÃ¶ping'),(5462,'2',1363,0,'GÃ¤vle'),(5463,'2',1364,0,'JÃ¶nkÃ¶ping'),(5464,'2',1365,0,'Lisboa'),(5465,'2',1365,0,'Amadora'),(5466,'2',1365,0,'Stockholm'),(5467,'2',1366,0,'MalmÃ¶'),(5468,'2',1366,0,'Helsingborg'),(5469,'2',1366,0,'Lund'),(5470,'2',1367,0,'Uppsala'),(5471,'2',1368,0,'UmeÃ¥'),(5472,'2',1369,0,'Sundsvall'),(5473,'2',1370,0,'VÃ¤sterÃs'),(5474,'2',1371,0,'Gothenburg [GÃteborg]'),(5475,'2',1371,0,'BorÃ¥s'),(5476,'2',1372,0,'Mbabane'),(5477,'2',1373,0,'Victoria'),(5478,'2',1374,0,'al-Qamishliya'),(5479,'2',1375,0,'al-Raqqa'),(5480,'2',1376,0,'Aleppo'),(5481,'2',1377,0,'Damascus'),(5482,'2',1378,0,'Jaramana'),(5483,'2',1378,0,'Duma'),(5484,'2',1379,0,'Dayr al-Zawr'),(5485,'2',1380,0,'Hama'),(5486,'2',1381,0,'Hims'),(5487,'2',1382,0,'Idlib'),(5488,'2',1383,0,'Latakia'),(5489,'2',1384,0,'Cockburn Town'),(5490,'2',1385,0,'NÂ´DjamÃna'),(5491,'2',1386,0,'Moundou'),(5492,'2',1387,0,'LomÃ'),(5493,'2',1388,0,'Bangkok'),(5494,'2',1389,0,'Chiang Mai'),(5495,'2',1390,0,'Khon Kaen'),(5496,'2',1391,0,'Nakhon Pathom'),(5497,'2',1392,0,'Nakhon Ratchasima'),(5498,'2',1393,0,'Nakhon Sawan'),(5499,'2',1394,0,'Nonthaburi'),(5500,'2',1394,0,'Pak Kret'),(5501,'2',1395,0,'Hat Yai'),(5502,'2',1395,0,'Songkhla'),(5503,'2',1396,0,'Ubon Ratchathani'),(5504,'2',1397,0,'Udon Thani'),(5505,'2',1398,0,'Dushanbe'),(5506,'2',1399,0,'Khujand'),(5507,'2',1400,0,'Fakaofo'),(5508,'2',1401,0,'Ashgabat'),(5509,'2',1402,0,'Dashhowuz'),(5510,'2',1403,0,'ChÃrjew'),(5511,'2',1404,0,'Mary'),(5512,'2',1405,0,'Dili'),(5513,'2',1406,0,'NukuÂ´alofa'),(5514,'2',1407,0,'Chaguanas'),(5515,'2',1408,0,'Port-of-Spain'),(5516,'2',1409,0,'Ariana'),(5517,'2',1409,0,'Ettadhamen'),(5518,'2',1410,0,'Biserta'),(5519,'2',1411,0,'GabÃ¨s'),(5520,'2',1412,0,'Kairouan'),(5521,'2',1413,0,'Sfax'),(5522,'2',1414,0,'Sousse'),(5523,'2',1415,0,'Tunis'),(5524,'2',1416,0,'Adana'),(5525,'2',1416,0,'Tarsus'),(5526,'2',1416,0,'Ceyhan'),(5527,'2',1417,0,'Adiyaman'),(5528,'2',1418,0,'Afyon'),(5529,'2',1419,0,'Aksaray'),(5530,'2',1420,0,'Ankara'),(5531,'2',1421,0,'Antalya'),(5532,'2',1421,0,'Alanya'),(5533,'2',1422,0,'Aydin'),(5534,'2',1422,0,'Nazilli'),(5535,'2',1423,0,'Ã‡orum'),(5536,'2',1424,0,'Balikesir'),(5537,'2',1424,0,'Bandirma'),(5538,'2',1425,0,'Batman'),(5539,'2',1426,0,'Bursa'),(5540,'2',1426,0,'InegÃl'),(5541,'2',1427,0,'Denizli'),(5542,'2',1428,0,'Diyarbakir'),(5543,'2',1428,0,'Bismil'),(5544,'2',1429,0,'Edirne'),(5545,'2',1430,0,'ElÃ¢zig'),(5546,'2',1431,0,'Erzincan'),(5547,'2',1432,0,'Erzurum'),(5548,'2',1433,0,'Eskisehir'),(5549,'2',1434,0,'Gaziantep'),(5550,'2',1435,0,'Iskenderun'),(5551,'2',1435,0,'Hatay (Antakya)'),(5552,'2',1436,0,'Mersin (IÃ§el)'),(5553,'2',1437,0,'Isparta'),(5554,'2',1438,0,'Istanbul'),(5555,'2',1438,0,'Sultanbeyli'),(5556,'2',1439,0,'Izmir'),(5557,'2',1440,0,'Kahramanmaras'),(5558,'2',1441,0,'KarabÃ¼k'),(5559,'2',1442,0,'Karaman'),(5560,'2',1443,0,'Kars'),(5561,'2',1444,0,'Kayseri'),(5562,'2',1445,0,'KÃ¼tahya'),(5563,'2',1446,0,'Kilis'),(5564,'2',1447,0,'Kirikkale'),(5565,'2',1448,0,'Gebze'),(5566,'2',1448,0,'Izmit (Kocaeli)'),(5567,'2',1449,0,'Konya'),(5568,'2',1450,0,'Malatya'),(5569,'2',1451,0,'Manisa'),(5570,'2',1452,0,'Kiziltepe'),(5571,'2',1453,0,'Ordu'),(5572,'2',1454,0,'Osmaniye'),(5573,'2',1455,0,'Sakarya (Adapazari)'),(5574,'2',1456,0,'Samsun'),(5575,'2',1457,0,'Sanliurfa'),(5576,'2',1457,0,'Viransehir'),(5577,'2',1458,0,'Siirt'),(5578,'2',1459,0,'Sivas'),(5579,'2',1460,0,'Ã‡orlu'),(5580,'2',1460,0,'Tekirdag'),(5581,'2',1461,0,'Tokat'),(5582,'2',1462,0,'Trabzon'),(5583,'2',1463,0,'Usak'),(5584,'2',1464,0,'Van'),(5585,'2',1465,0,'Zonguldak'),(5586,'2',1466,0,'Funafuti'),(5587,'2',1467,0,'Taiping'),(5588,'2',1467,0,'Taliao'),(5589,'2',1467,0,'Kueishan'),(5590,'2',1467,0,'Ciudad Losada'),(5591,'2',1468,0,'Changhwa'),(5592,'2',1468,0,'Yuanlin'),(5593,'2',1469,0,'Chiayi'),(5594,'2',1470,0,'Hsinchu'),(5595,'2',1471,0,'Hualien'),(5596,'2',1472,0,'Ilan'),(5597,'2',1473,0,'Kaohsiung'),(5598,'2',1473,0,'Fengshan'),(5599,'2',1473,0,'Kangshan'),(5600,'2',1474,0,'Keelung (Chilung)'),(5601,'2',1475,0,'Miaoli'),(5602,'2',1476,0,'Nantou'),(5603,'2',1476,0,'Tsaotun'),(5604,'2',1477,0,'Pingtung'),(5605,'2',1478,0,'Taichung'),(5606,'2',1478,0,'Tali'),(5607,'2',1478,0,'Fengyuan'),(5608,'2',1479,0,'Tainan'),(5609,'2',1479,0,'Yungkang'),(5610,'2',1480,0,'Taipei'),(5611,'2',1480,0,'Panchiao'),(5612,'2',1480,0,'Chungho'),(5613,'2',1480,0,'Sanchung'),(5614,'2',1480,0,'Hsinchuang'),(5615,'2',1480,0,'Hsintien'),(5616,'2',1480,0,'Yungho'),(5617,'2',1480,0,'Tucheng'),(5618,'2',1480,0,'Luchou'),(5619,'2',1480,0,'Hsichuh'),(5620,'2',1480,0,'Shulin'),(5621,'2',1480,0,'Tanshui'),(5622,'2',1480,0,'Lungtan'),(5623,'2',1481,0,'Taitung'),(5624,'2',1482,0,'Chungli'),(5625,'2',1482,0,'Taoyuan'),(5626,'2',1482,0,'Pingchen'),(5627,'2',1482,0,'Pate'),(5628,'2',1482,0,'Yangmei'),(5629,'2',1483,0,'Touliu'),(5630,'2',1484,0,'Arusha'),(5631,'2',1485,0,'Dar es Salaam'),(5632,'2',1486,0,'Dodoma'),(5633,'2',1487,0,'Moshi'),(5634,'2',1488,0,'Mbeya'),(5635,'2',1489,0,'Morogoro'),(5636,'2',1490,0,'Mwanza'),(5637,'2',1491,0,'Tabora'),(5638,'2',1492,0,'Tanga'),(5639,'2',1493,0,'Zanzibar'),(5640,'2',1494,0,'Suva'),(5641,'2',1494,0,'Nyeri'),(5642,'2',1494,0,'Kathmandu'),(5643,'2',1494,0,'Lalitapur'),(5644,'2',1494,0,'Birgunj'),(5645,'2',1494,0,'San Lorenzo'),(5646,'2',1494,0,'LambarÃ'),(5647,'2',1494,0,'Fernando de la Mora'),(5648,'2',1494,0,'Kabwe'),(5649,'2',1494,0,'Kandy'),(5650,'2',1494,0,'Kampala'),(5651,'2',1495,0,'Dnipropetrovsk'),(5652,'2',1495,0,'Kryvyi Rig'),(5653,'2',1495,0,'Dniprodzerzynsk'),(5654,'2',1495,0,'Nikopol'),(5655,'2',1495,0,'Pavlograd'),(5656,'2',1496,0,'Donetsk'),(5657,'2',1496,0,'Mariupol'),(5658,'2',1496,0,'Makijivka'),(5659,'2',1496,0,'Gorlivka'),(5660,'2',1496,0,'Kramatorsk'),(5661,'2',1496,0,'Slovjansk'),(5662,'2',1496,0,'Jenakijeve'),(5663,'2',1496,0,'Kostjantynivka'),(5664,'2',1497,0,'Harkova [Harkiv]'),(5665,'2',1498,0,'Herson'),(5666,'2',1499,0,'Hmelnytskyi'),(5667,'2',1499,0,'Kamjanets-Podilskyi'),(5668,'2',1500,0,'Ivano-Frankivsk'),(5669,'2',1501,0,'Kyiv'),(5670,'2',1501,0,'Bila Tserkva'),(5671,'2',1501,0,'Brovary'),(5672,'2',1502,0,'Kirovograd'),(5673,'2',1502,0,'Oleksandrija'),(5674,'2',1503,0,'Sevastopol'),(5675,'2',1503,0,'Simferopol'),(5676,'2',1503,0,'KertÅ¡'),(5677,'2',1503,0,'Jevpatorija'),(5678,'2',1504,0,'Lugansk'),(5679,'2',1504,0,'Sjeverodonetsk'),(5680,'2',1504,0,'AltÅ¡evsk'),(5681,'2',1504,0,'LysytÅ¡ansk'),(5682,'2',1504,0,'Krasnyi LutÅ¡'),(5683,'2',1504,0,'Stahanov'),(5684,'2',1505,0,'Lviv'),(5685,'2',1506,0,'Mykolajiv'),(5686,'2',1507,0,'Odesa'),(5687,'2',1507,0,'Izmajil'),(5688,'2',1508,0,'Pultava [Poltava]'),(5689,'2',1508,0,'KrementÅ¡uk'),(5690,'2',1509,0,'Rivne'),(5691,'2',1510,0,'Sumy'),(5692,'2',1510,0,'Konotop'),(5693,'2',1510,0,'Å ostka'),(5694,'2',1511,0,'Uzgorod'),(5695,'2',1511,0,'MukatÅ¡eve'),(5696,'2',1512,0,'Ternopil'),(5697,'2',1513,0,'TÅ¡erkasy'),(5698,'2',1513,0,'Uman'),(5699,'2',1514,0,'TÅ¡ernigiv'),(5700,'2',1515,0,'TÅ¡ernivtsi'),(5701,'2',1516,0,'Vinnytsja'),(5702,'2',1517,0,'Lutsk'),(5703,'2',1518,0,'Zaporizzja'),(5704,'2',1518,0,'Melitopol'),(5705,'2',1518,0,'Berdjansk'),(5706,'2',1519,0,'Zytomyr'),(5707,'2',1519,0,'BerdytÅ¡iv'),(5708,'2',1520,0,'Montevideo'),(5709,'2',1521,0,'Birmingham'),(5710,'2',1521,0,'Montgomery'),(5711,'2',1521,0,'Mobile'),(5712,'2',1521,0,'Huntsville'),(5713,'2',1522,0,'Anchorage'),(5714,'2',1523,0,'Phoenix'),(5715,'2',1523,0,'Tucson'),(5716,'2',1523,0,'Mesa'),(5717,'2',1523,0,'Glendale'),(5718,'2',1523,0,'Scottsdale'),(5719,'2',1523,0,'Chandler'),(5720,'2',1523,0,'Tempe'),(5721,'2',1523,0,'Gilbert'),(5722,'2',1523,0,'Peoria'),(5723,'2',1524,0,'Little Rock'),(5724,'2',1525,0,'Los Angeles'),(5725,'2',1525,0,'San Diego'),(5726,'2',1525,0,'San Jose'),(5727,'2',1525,0,'San Francisco'),(5728,'2',1525,0,'Long Beach'),(5729,'2',1525,0,'Fresno'),(5730,'2',1525,0,'Sacramento'),(5731,'2',1525,0,'Oakland'),(5732,'2',1525,0,'Santa Ana'),(5733,'2',1525,0,'Anaheim'),(5734,'2',1525,0,'Riverside'),(5735,'2',1525,0,'Bakersfield'),(5736,'2',1525,0,'Stockton'),(5737,'2',1525,0,'Fremont'),(5738,'2',1525,0,'Glendale'),(5739,'2',1525,0,'Huntington Beach'),(5740,'2',1525,0,'Modesto'),(5741,'2',1525,0,'San Bernardino'),(5742,'2',1525,0,'Chula Vista'),(5743,'2',1525,0,'Oxnard'),(5744,'2',1525,0,'Garden Grove'),(5745,'2',1525,0,'Oceanside'),(5746,'2',1525,0,'Ontario'),(5747,'2',1525,0,'Santa Clarita'),(5748,'2',1525,0,'Salinas'),(5749,'2',1525,0,'Pomona'),(5750,'2',1525,0,'Santa Rosa'),(5751,'2',1525,0,'Irvine'),(5752,'2',1525,0,'Moreno Valley'),(5753,'2',1525,0,'Pasadena'),(5754,'2',1525,0,'Hayward'),(5755,'2',1525,0,'Torrance'),(5756,'2',1525,0,'Escondido'),(5757,'2',1525,0,'Sunnyvale'),(5758,'2',1525,0,'Fontana'),(5759,'2',1525,0,'Orange'),(5760,'2',1525,0,'Rancho Cucamonga'),(5761,'2',1525,0,'East Los Angeles'),(5762,'2',1525,0,'Fullerton'),(5763,'2',1525,0,'Corona'),(5764,'2',1525,0,'Concord'),(5765,'2',1525,0,'Lancaster'),(5766,'2',1525,0,'Thousand Oaks'),(5767,'2',1525,0,'Vallejo'),(5768,'2',1525,0,'Palmdale'),(5769,'2',1525,0,'El Monte'),(5770,'2',1525,0,'Inglewood'),(5771,'2',1525,0,'Simi Valley'),(5772,'2',1525,0,'Costa Mesa'),(5773,'2',1525,0,'Downey'),(5774,'2',1525,0,'West Covina'),(5775,'2',1525,0,'Daly City'),(5776,'2',1525,0,'Citrus Heights'),(5777,'2',1525,0,'Norwalk'),(5778,'2',1525,0,'Berkeley'),(5779,'2',1525,0,'Santa Clara'),(5780,'2',1525,0,'San Buenaventura'),(5781,'2',1525,0,'Burbank'),(5782,'2',1525,0,'Mission Viejo'),(5783,'2',1525,0,'El Cajon'),(5784,'2',1525,0,'Richmond'),(5785,'2',1525,0,'Compton'),(5786,'2',1525,0,'Fairfield'),(5787,'2',1525,0,'Arden-Arcade'),(5788,'2',1525,0,'San Mateo'),(5789,'2',1525,0,'Visalia'),(5790,'2',1525,0,'Santa Monica'),(5791,'2',1525,0,'Carson'),(5792,'2',1526,0,'Denver'),(5793,'2',1526,0,'Colorado Springs'),(5794,'2',1526,0,'Aurora'),(5795,'2',1526,0,'Lakewood'),(5796,'2',1526,0,'Fort Collins'),(5797,'2',1526,0,'Arvada'),(5798,'2',1526,0,'Pueblo'),(5799,'2',1526,0,'Westminster'),(5800,'2',1526,0,'Boulder'),(5801,'2',1527,0,'Bridgeport'),(5802,'2',1527,0,'New Haven'),(5803,'2',1527,0,'Hartford'),(5804,'2',1527,0,'Stamford'),(5805,'2',1527,0,'Waterbury'),(5806,'2',1528,0,'Washington'),(5807,'2',1529,0,'Jacksonville'),(5808,'2',1529,0,'Miami'),(5809,'2',1529,0,'Tampa'),(5810,'2',1529,0,'Saint Petersburg'),(5811,'2',1529,0,'Hialeah'),(5812,'2',1529,0,'Orlando'),(5813,'2',1529,0,'Fort Lauderdale'),(5814,'2',1529,0,'Tallahassee'),(5815,'2',1529,0,'Hollywood'),(5816,'2',1529,0,'Pembroke Pines'),(5817,'2',1529,0,'Coral Springs'),(5818,'2',1529,0,'Cape Coral'),(5819,'2',1529,0,'Clearwater'),(5820,'2',1529,0,'Miami Beach'),(5821,'2',1529,0,'Gainesville'),(5822,'2',1530,0,'Atlanta'),(5823,'2',1530,0,'Augusta-Richmond County'),(5824,'2',1530,0,'Columbus'),(5825,'2',1530,0,'Savannah'),(5826,'2',1530,0,'Macon'),(5827,'2',1530,0,'Athens-Clarke County'),(5828,'2',1531,0,'Honolulu'),(5829,'2',1532,0,'Boise City'),(5830,'2',1533,0,'Chicago'),(5831,'2',1533,0,'Rockford'),(5832,'2',1533,0,'Aurora'),(5833,'2',1533,0,'Naperville'),(5834,'2',1533,0,'Peoria'),(5835,'2',1533,0,'Springfield'),(5836,'2',1533,0,'Joliet'),(5837,'2',1533,0,'Elgin'),(5838,'2',1534,0,'Indianapolis'),(5839,'2',1534,0,'Fort Wayne'),(5840,'2',1534,0,'Evansville'),(5841,'2',1534,0,'South Bend'),(5842,'2',1534,0,'Gary'),(5843,'2',1535,0,'Des Moines'),(5844,'2',1535,0,'Cedar Rapids'),(5845,'2',1535,0,'Davenport'),(5846,'2',1536,0,'Wichita'),(5847,'2',1536,0,'Overland Park'),(5848,'2',1536,0,'Kansas City'),(5849,'2',1536,0,'Topeka'),(5850,'2',1537,0,'Lexington-Fayette'),(5851,'2',1537,0,'Louisville'),(5852,'2',1538,0,'New Orleans'),(5853,'2',1538,0,'Baton Rouge'),(5854,'2',1538,0,'Shreveport'),(5855,'2',1538,0,'Metairie'),(5856,'2',1538,0,'Lafayette'),(5857,'2',1539,0,'Baltimore'),(5858,'2',1540,0,'Boston'),(5859,'2',1540,0,'Worcester'),(5860,'2',1540,0,'Springfield'),(5861,'2',1540,0,'Lowell'),(5862,'2',1540,0,'Cambridge'),(5863,'2',1540,0,'New Bedford'),(5864,'2',1540,0,'Brockton'),(5865,'2',1540,0,'Fall River'),(5866,'2',1541,0,'Detroit'),(5867,'2',1541,0,'Grand Rapids'),(5868,'2',1541,0,'Warren'),(5869,'2',1541,0,'Flint'),(5870,'2',1541,0,'Sterling Heights'),(5871,'2',1541,0,'Lansing'),(5872,'2',1541,0,'Ann Arbor'),(5873,'2',1541,0,'Livonia'),(5874,'2',1542,0,'Minneapolis'),(5875,'2',1542,0,'Saint Paul'),(5876,'2',1543,0,'Jackson'),(5877,'2',1544,0,'Kansas City'),(5878,'2',1544,0,'Saint Louis'),(5879,'2',1544,0,'Springfield'),(5880,'2',1544,0,'Independence'),(5881,'2',1545,0,'Billings'),(5882,'2',1546,0,'Omaha'),(5883,'2',1546,0,'Lincoln'),(5884,'2',1547,0,'Las Vegas'),(5885,'2',1547,0,'Reno'),(5886,'2',1547,0,'Henderson'),(5887,'2',1547,0,'Paradise'),(5888,'2',1547,0,'North Las Vegas'),(5889,'2',1547,0,'Sunrise Manor'),(5890,'2',1548,0,'Manchester'),(5891,'2',1549,0,'Newark'),(5892,'2',1549,0,'Jersey City'),(5893,'2',1549,0,'Paterson'),(5894,'2',1549,0,'Elizabeth'),(5895,'2',1550,0,'Albuquerque'),(5896,'2',1551,0,'New York'),(5897,'2',1551,0,'Buffalo'),(5898,'2',1551,0,'Rochester'),(5899,'2',1551,0,'Yonkers'),(5900,'2',1551,0,'Syracuse'),(5901,'2',1551,0,'Albany'),(5902,'2',1552,0,'Charlotte'),(5903,'2',1552,0,'Raleigh'),(5904,'2',1552,0,'Greensboro'),(5905,'2',1552,0,'Durham'),(5906,'2',1552,0,'Winston-Salem'),(5907,'2',1552,0,'Fayetteville'),(5908,'2',1552,0,'Cary'),(5909,'2',1553,0,'Columbus'),(5910,'2',1553,0,'Cleveland'),(5911,'2',1553,0,'Cincinnati'),(5912,'2',1553,0,'Toledo'),(5913,'2',1553,0,'Akron'),(5914,'2',1553,0,'Dayton'),(5915,'2',1554,0,'Oklahoma City'),(5916,'2',1554,0,'Tulsa'),(5917,'2',1554,0,'Norman'),(5918,'2',1555,0,'Portland'),(5919,'2',1555,0,'Eugene'),(5920,'2',1555,0,'Salem'),(5921,'2',1556,0,'Philadelphia'),(5922,'2',1556,0,'Pittsburgh'),(5923,'2',1556,0,'Allentown'),(5924,'2',1556,0,'Erie'),(5925,'2',1557,0,'Providence'),(5926,'2',1558,0,'Columbia'),(5927,'2',1558,0,'Charleston'),(5928,'2',1559,0,'Sioux Falls'),(5929,'2',1560,0,'Memphis'),(5930,'2',1560,0,'Nashville-Davidson'),(5931,'2',1560,0,'Knoxville'),(5932,'2',1560,0,'Chattanooga'),(5933,'2',1560,0,'Clarksville'),(5934,'2',1561,0,'Houston'),(5935,'2',1561,0,'Dallas'),(5936,'2',1561,0,'San Antonio'),(5937,'2',1561,0,'Austin'),(5938,'2',1561,0,'El Paso'),(5939,'2',1561,0,'Fort Worth'),(5940,'2',1561,0,'Arlington'),(5941,'2',1561,0,'Corpus Christi'),(5942,'2',1561,0,'Plano'),(5943,'2',1561,0,'Garland'),(5944,'2',1561,0,'Lubbock'),(5945,'2',1561,0,'Irving'),(5946,'2',1561,0,'Laredo'),(5947,'2',1561,0,'Amarillo'),(5948,'2',1561,0,'Brownsville'),(5949,'2',1561,0,'Pasadena'),(5950,'2',1561,0,'Grand Prairie'),(5951,'2',1561,0,'Mesquite'),(5952,'2',1561,0,'Abilene'),(5953,'2',1561,0,'Beaumont'),(5954,'2',1561,0,'Waco'),(5955,'2',1561,0,'Carrollton'),(5956,'2',1561,0,'McAllen'),(5957,'2',1561,0,'Wichita Falls'),(5958,'2',1561,0,'Midland'),(5959,'2',1561,0,'Odessa'),(5960,'2',1562,0,'Salt Lake City'),(5961,'2',1562,0,'West Valley City'),(5962,'2',1562,0,'Provo'),(5963,'2',1562,0,'Sandy'),(5964,'2',1563,0,'Virginia Beach'),(5965,'2',1563,0,'Norfolk'),(5966,'2',1563,0,'Chesapeake'),(5967,'2',1563,0,'Richmond'),(5968,'2',1563,0,'Newport News'),(5969,'2',1563,0,'Arlington'),(5970,'2',1563,0,'Hampton'),(5971,'2',1563,0,'Alexandria'),(5972,'2',1563,0,'Portsmouth'),(5973,'2',1563,0,'Roanoke'),(5974,'2',1564,0,'Seattle'),(5975,'2',1564,0,'Spokane'),(5976,'2',1564,0,'Tacoma'),(5977,'2',1564,0,'Vancouver'),(5978,'2',1564,0,'Bellevue'),(5979,'2',1565,0,'Milwaukee'),(5980,'2',1565,0,'Madison'),(5981,'2',1565,0,'Green Bay'),(5982,'2',1565,0,'Kenosha'),(5983,'2',1566,0,'Andijon'),(5984,'2',1567,0,'Buhoro'),(5985,'2',1568,0,'Cizah'),(5986,'2',1569,0,'KÃ¼kon'),(5987,'2',1569,0,'Fargona'),(5988,'2',1569,0,'Margilon'),(5989,'2',1570,0,'Nukus'),(5990,'2',1571,0,'Ãœrgenc'),(5991,'2',1572,0,'Namangan'),(5992,'2',1573,0,'Navoi'),(5993,'2',1574,0,'Karsi'),(5994,'2',1575,0,'Samarkand'),(5995,'2',1576,0,'Termiz'),(5996,'2',1577,0,'Circik'),(5997,'2',1577,0,'Angren'),(5998,'2',1577,0,'Olmalik'),(5999,'2',1578,0,'Toskent'),(6000,'2',1579,0,'South Hill'),(6001,'2',1579,0,'The Valley'),(6002,'2',1579,0,'Oranjestad'),(6003,'2',1579,0,'Douglas'),(6004,'2',1579,0,'Gibraltar'),(6005,'2',1579,0,'Tamuning'),(6006,'2',1579,0,'AgaÃ±a'),(6007,'2',1579,0,'Flying Fish Cove'),(6008,'2',1579,0,'Monte-Carlo'),(6009,'2',1579,0,'Monaco-Ville'),(6010,'2',1579,0,'Yangor'),(6011,'2',1579,0,'Yaren'),(6012,'2',1579,0,'Alofi'),(6013,'2',1579,0,'Kingston'),(6014,'2',1579,0,'Adamstown'),(6015,'2',1579,0,'Singapore'),(6016,'2',1579,0,'NoumÃ©a'),(6017,'2',1579,0,'CittÃ  del Vaticano'),(6018,'2',1580,0,'Roseau'),(6019,'2',1580,0,'Saint GeorgeÂ´s'),(6020,'2',1580,0,'Kingstown'),(6021,'2',1581,0,'Taiping'),(6022,'2',1581,0,'Taliao'),(6023,'2',1581,0,'Kueishan'),(6024,'2',1581,0,'Ciudad Losada'),(6025,'2',1582,0,'Barcelona'),(6026,'2',1582,0,'Puerto La Cruz'),(6027,'2',1582,0,'El Tigre'),(6028,'2',1582,0,'Pozuelos'),(6029,'2',1583,0,'San Fernando de Apure'),(6030,'2',1584,0,'Maracay'),(6031,'2',1584,0,'Turmero'),(6032,'2',1584,0,'El LimÃ³n'),(6033,'2',1585,0,'Barinas'),(6034,'2',1586,0,'Cartagena'),(6035,'2',1586,0,'Ciudad Guayana'),(6036,'2',1586,0,'Ciudad BolÃ­var'),(6037,'2',1587,0,'Valencia'),(6038,'2',1587,0,'Puerto Cabello'),(6039,'2',1587,0,'Guacara'),(6040,'2',1588,0,'Buenos Aires'),(6041,'2',1588,0,'BrasÃ­lia'),(6042,'2',1588,0,'Ciudad de MÃ©xico'),(6043,'2',1588,0,'Caracas'),(6044,'2',1588,0,'Catia La Mar'),(6045,'2',1589,0,'Santa Ana de Coro'),(6046,'2',1589,0,'Punto Fijo'),(6047,'2',1590,0,'Calabozo'),(6048,'2',1590,0,'Valle de la Pascua'),(6049,'2',1591,0,'Barquisimeto'),(6050,'2',1592,0,'MÃ©rida'),(6051,'2',1593,0,'Petare'),(6052,'2',1593,0,'Baruta'),(6053,'2',1593,0,'Los Teques'),(6054,'2',1593,0,'Guarenas'),(6055,'2',1593,0,'Guatire'),(6056,'2',1593,0,'Ocumare del Tuy'),(6057,'2',1594,0,'MaturÃ­n'),(6058,'2',1595,0,'Acarigua'),(6059,'2',1595,0,'Guanare'),(6060,'2',1595,0,'Araure'),(6061,'2',1596,0,'Sincelejo'),(6062,'2',1596,0,'CumanÃ¡'),(6063,'2',1596,0,'CarÃºpano'),(6064,'2',1597,0,'San CristÃ³bal'),(6065,'2',1598,0,'Valera'),(6066,'2',1599,0,'San Felipe'),(6067,'2',1600,0,'MaracaÃ­bo'),(6068,'2',1600,0,'Cabimas'),(6069,'2',1600,0,'Ciudad Ojeda'),(6070,'2',1601,0,'Road Town'),(6071,'2',1602,0,'Charlotte Amalie'),(6072,'2',1603,0,'Long Xuyen'),(6073,'2',1604,0,'Vung Tau'),(6074,'2',1605,0,'Thai Nguyen'),(6075,'2',1606,0,'Quy Nhon'),(6076,'2',1607,0,'Phan ThiÃªt'),(6077,'2',1608,0,'Can Tho'),(6078,'2',1609,0,'Buon Ma Thuot'),(6079,'2',1610,0,'BiÃªn Hoa'),(6080,'2',1611,0,'Haiphong'),(6081,'2',1612,0,'Hanoi'),(6082,'2',1613,0,'Ho Chi Minh City'),(6083,'2',1614,0,'Nha Trang'),(6084,'2',1614,0,'Cam Ranh'),(6085,'2',1615,0,'Rach Gia'),(6086,'2',1616,0,'Da Lat'),(6087,'2',1617,0,'Nam Dinh'),(6088,'2',1618,0,'Vinh'),(6089,'2',1619,0,'Cam Pha'),(6090,'2',1620,0,'Da Nang'),(6091,'2',1621,0,'Hong Gai'),(6092,'2',1622,0,'Hue'),(6093,'2',1623,0,'My Tho'),(6094,'2',1624,0,'Port-Vila'),(6095,'2',1625,0,'Mata-Utu'),(6096,'2',1626,0,'Apia'),(6097,'2',1627,0,'Aden'),(6098,'2',1628,0,'al-Mukalla'),(6099,'2',1629,0,'Hodeida'),(6100,'2',1630,0,'Ibb'),(6101,'2',1631,0,'Sanaa'),(6102,'2',1632,0,'Taizz'),(6103,'2',1633,0,'Beograd'),(6104,'2',1633,0,'NiÅ¡'),(6105,'2',1633,0,'Kragujevac'),(6106,'2',1634,0,'PriÅ¡tina'),(6107,'2',1634,0,'Prizren'),(6108,'2',1635,0,'Podgorica'),(6109,'2',1636,0,'Novi Sad'),(6110,'2',1636,0,'Subotica'),(6111,'2',1637,0,'Port Elizabeth'),(6112,'2',1637,0,'East London'),(6113,'2',1637,0,'Uitenhage'),(6114,'2',1637,0,'Mdantsane'),(6115,'2',1638,0,'Bloemfontein'),(6116,'2',1638,0,'Welkom'),(6117,'2',1638,0,'Botshabelo'),(6118,'2',1639,0,'Soweto'),(6119,'2',1639,0,'Johannesburg'),(6120,'2',1639,0,'Pretoria'),(6121,'2',1639,0,'Vanderbijlpark'),(6122,'2',1639,0,'Kempton Park'),(6123,'2',1639,0,'Alberton'),(6124,'2',1639,0,'Benoni'),(6125,'2',1639,0,'Randburg'),(6126,'2',1639,0,'Vereeniging'),(6127,'2',1639,0,'Wonderboom'),(6128,'2',1639,0,'Roodepoort'),(6129,'2',1639,0,'Boksburg'),(6130,'2',1639,0,'Soshanguve'),(6131,'2',1639,0,'Krugersdorp'),(6132,'2',1639,0,'Brakpan'),(6133,'2',1639,0,'Oberholzer'),(6134,'2',1639,0,'Germiston'),(6135,'2',1639,0,'Springs'),(6136,'2',1639,0,'Westonaria'),(6137,'2',1639,0,'Randfontein'),(6138,'2',1639,0,'Nigel'),(6139,'2',1640,0,'Inanda'),(6140,'2',1640,0,'Durban'),(6141,'2',1640,0,'Pinetown'),(6142,'2',1640,0,'Pietermaritzburg'),(6143,'2',1640,0,'Umlazi'),(6144,'2',1640,0,'Newcastle'),(6145,'2',1640,0,'Chatsworth'),(6146,'2',1640,0,'Ladysmith'),(6147,'2',1641,0,'Witbank'),(6148,'2',1642,0,'Klerksdorp'),(6149,'2',1642,0,'Potchefstroom'),(6150,'2',1642,0,'Rustenburg'),(6151,'2',1643,0,'Kimberley'),(6152,'2',1644,0,'Cape Town'),(6153,'2',1644,0,'Paarl'),(6154,'2',1644,0,'George'),(6155,'2',1645,0,'Suva'),(6156,'2',1645,0,'Nyeri'),(6157,'2',1645,0,'Kathmandu'),(6158,'2',1645,0,'Lalitapur'),(6159,'2',1645,0,'Birgunj'),(6160,'2',1645,0,'San Lorenzo'),(6161,'2',1645,0,'LambarÃ©'),(6162,'2',1645,0,'Fernando de la Mora'),(6163,'2',1645,0,'Kabwe'),(6164,'2',1645,0,'Kandy'),(6165,'2',1645,0,'Kampala'),(6166,'2',1646,0,'Ndola'),(6167,'2',1646,0,'Kitwe'),(6168,'2',1646,0,'Chingola'),(6169,'2',1646,0,'Mufulira'),(6170,'2',1646,0,'Luanshya'),(6171,'2',1647,0,'Lusaka'),(6172,'2',1648,0,'Bulawayo'),(6173,'2',1649,0,'Harare'),(6174,'2',1649,0,'Chitungwiza'),(6175,'2',1649,0,'Mount Darwin'),(6176,'2',1650,0,'Mutare'),(6177,'2',1651,0,'Gweru');
/*!40000 ALTER TABLE `world_location` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-12  7:26:10
