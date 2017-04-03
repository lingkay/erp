-- MySQL dump 10.13  Distrib 5.6.16, for Win32 (x86)
--
-- Host: localhost    Database: hris2
-- ------------------------------------------------------
-- Server version	5.6.16

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
INSERT INTO `cfg_entry` VALUES ('hris_hr_department','2'),('hris_request_approver',''),('hris_vp_operations','1');
/*!40000 ALTER TABLE `cfg_entry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cnt_address`
--

CREATE TABLE IF NOT EXISTS `cnt_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `is_primary` tinyint(1) NOT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DCF4B65A2D5B0234` (`city`),
  KEY `IDX_DCF4B65AA393D2FB` (`state`),
  KEY `IDX_DCF4B65A5373C966` (`country`),
  KEY `IDX_DCF4B65AEEFE5067` (`user_create_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cnt_address`
--

INSERT INTO `cnt_address` (`id`, `city`, `state`, `country`, `user_create_id`, `name`, `street`, `latitude`, `longitude`, `is_primary`, `date_create`) VALUES
(1, 4976, 1144, 169, NULL, '43 Trion Towers', 'Thyme Street', '0.0000000', '0.0000000', 0, '2015-08-26 12:32:41');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cnt_address`
--
ALTER TABLE `cnt_address`
  ADD CONSTRAINT `FK_DCF4B65AEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  ADD CONSTRAINT `FK_DCF4B65A2D5B0234` FOREIGN KEY (`city`) REFERENCES `world_location` (`id`),
  ADD CONSTRAINT `FK_DCF4B65A5373C966` FOREIGN KEY (`country`) REFERENCES `world_location` (`id`),
  ADD CONSTRAINT `FK_DCF4B65AA393D2FB` FOREIGN KEY (`state`) REFERENCES `world_location` (`id`);

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
  CONSTRAINT `FK_904E246EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_904E24671C8FDB3` FOREIGN KEY (`releaser_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_904E2468C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
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
  `user_create_id` int(11) DEFAULT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  `preset_id` int(11) DEFAULT NULL,
  `overall_quali` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `overall_quanti` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7A39D5108C03F15C` (`employee_id`),
  KEY `IDX_7A39D510EEFE5067` (`user_create_id`),
  KEY `IDX_7A39D51080688E6F` (`preset_id`),
  CONSTRAINT `FK_7A39D51080688E6F` FOREIGN KEY (`preset_id`) REFERENCES `hr_appraisal_settings` (`id`),
  CONSTRAINT `FK_7A39D5108C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_7A39D510EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_appraisal`
--

LOCK TABLES `hr_appraisal` WRITE;
/*!40000 ALTER TABLE `hr_appraisal` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_appraisal_settings`
--

LOCK TABLES `hr_appraisal_settings` WRITE;
/*!40000 ALTER TABLE `hr_appraisal_settings` DISABLE KEYS */;
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
  `user_create_id` int(11) DEFAULT NULL,
  `status` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `undertime` int(11) DEFAULT NULL,
  `late` int(11) DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `approver_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `overtime` int(11) DEFAULT NULL,
  `adjustment_date` datetime DEFAULT NULL,
  `adjusted_time_in` datetime DEFAULT NULL,
  `adjusted_time_out` datetime DEFAULT NULL,
  `adjustment_status` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adjust_approved` datetime DEFAULT NULL,
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
  KEY `IDX_F6DA1CDFEEFE5067` (`user_create_id`),
  KEY `IDX_F6DA1CDFBB23766C` (`approver_id`),
  CONSTRAINT `FK_F6DA1CDFCCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_F6DA1CDF8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_F6DA1CDFBB23766C` FOREIGN KEY (`approver_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_F6DA1CDFEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
INSERT INTO `hr_benefit` VALUES (1,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Monetary Value','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','SSS',NULL,'2015-06-26 13:31:56'),(2,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Monetary Value','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','PhilHealth',NULL,'2015-06-26 13:31:56'),(3,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Monetary Value','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','PAG-IBIG',NULL,'2015-06-26 13:31:56'),(4,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','Sick Leave',NULL,'2015-06-26 13:31:56'),(5,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','Vacation Leave',NULL,'2015-06-26 13:31:56'),(6,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:1:{i:1;s:6:\"Female\";}','Maternity Leave',NULL,'2015-06-26 13:31:56'),(7,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:1:{i:0;s:4:\"Male\";}','Paternity Leave',NULL,'2015-06-26 13:31:56'),(8,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','Parental Leave',NULL,'2015-06-26 13:31:56'),(9,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:1:{i:1;s:6:\"Female\";}','Leave for VAWC',NULL,'2015-06-26 13:31:56'),(10,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Days','a:1:{i:0;s:6:\"Female\";}','Special leave for women ',NULL,'2015-06-26 13:31:56'),(11,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Monetary Value','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','13th month Pay',NULL,'2015-06-26 13:31:56'),(12,1,'a:2:{i:1;s:12:\"Probationary\";i:3;s:7:\"Regular\";}','Quantified by Monetary Value','a:2:{i:0;s:4:\"Male\";i:1;s:6:\"Female\";}','Separation Pay',NULL,'2015-06-26 13:31:56');
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
INSERT INTO `hr_checklist` VALUES (1,1,'NBI CLearance','2015-06-26 13:31:56',''),(2,1,'Police Clearance','2015-06-26 13:31:56',''),(3,1,'Brgy Clearance','2015-06-26 13:31:56',''),(4,1,'Medical','2015-06-26 13:31:56',''),(5,1,'Drug Test','2015-06-26 13:31:56',''),(6,1,'NSO Birth Certificate','2015-06-26 13:31:56',''),(7,1,'Birth Certificate of Dependents','2015-06-26 13:31:56',''),(8,1,'2x2 Picture','2015-06-26 13:31:56',''),(9,1,'Photocopy of SSS No','2015-06-26 13:31:56',''),(10,1,'Photocopy of Tin No','2015-06-26 13:31:56',''),(11,1,'Photocopy of Philhealth No','2015-06-26 13:31:56',''),(12,1,'Photocopy of Pag-ibig No','2015-06-26 13:31:56',''),(13,1,'Photocopy of Diploma','2015-06-26 13:31:56',''),(14,1,'BIR 2305 w/ stamp','2015-06-26 13:31:56',''),(15,1,'1905 w/ stamp','2015-06-26 13:31:56',''),(16,1,'1902 w/ stamp','2015-06-26 13:31:56',''),(17,1,'PMRF','2015-06-26 13:31:56',''),(18,1,'MDF','2015-06-26 13:31:56',''),(19,1,'SSS loan verification','2015-06-26 13:31:56',''),(20,1,'Photocopy of Certificate of Employement (COE)','2015-06-26 13:31:56',''),(21,1,'Photocopy of Clearance','2015-06-26 13:31:56',''),(22,1,'2316 of current year','2015-06-26 13:31:56','');
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_department`
--

LOCK TABLES `hr_department` WRITE;
/*!40000 ALTER TABLE `hr_department` DISABLE KEYS */;
INSERT INTO `hr_department` VALUES (1,NULL,NULL,1,'2015-06-26 13:31:56','Management'),(2,NULL,NULL,1,'2015-06-26 13:31:56','Human Resource'),(3,NULL,NULL,1,'2015-06-26 13:31:56','Admin'),(4,NULL,NULL,1,'2015-06-26 13:31:56','Accounting/Finance'),(5,NULL,NULL,1,'2015-06-26 13:31:56','Marketing'),(6,NULL,NULL,1,'2015-06-26 13:31:56','Mechandising'),(7,NULL,NULL,1,'2015-06-26 13:31:56','Sales'),(8,NULL,NULL,1,'2015-06-26 13:31:56','Sales Monitoring'),(9,NULL,NULL,1,'2015-06-26 13:31:56','Purchasing'),(10,NULL,NULL,1,'2015-06-26 13:31:56','Logistic'),(11,NULL,NULL,1,'2015-06-26 13:31:56','Warehousing'),(12,NULL,NULL,1,'2015-06-26 13:31:56','Research and Development'),(13,NULL,NULL,1,'2015-06-26 13:31:56','Quality Assurance'),(14,NULL,NULL,1,'2015-06-26 13:31:56','Production'),(17,NULL,NULL,4,'2015-07-09 18:05:34','Production Department');
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
  `user_create_id` int(11) DEFAULT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `date_create` datetime NOT NULL,
  `upload_id` int(11) DEFAULT NULL,
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
  `date_create` datetime NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `exemption` tinyint(1) NOT NULL,
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
  CONSTRAINT `FK_E67AB75B3E030ACD` FOREIGN KEY (`application_id`) REFERENCES `hr_app` (`id`),
  CONSTRAINT `FK_E67AB75B116C90BC` FOREIGN KEY (`schedules_id`) REFERENCES `hr_schedule` (`id`),
  CONSTRAINT `FK_E67AB75B38F6EEDC` FOREIGN KEY (`job_level_id`) REFERENCES `hr_job_level` (`id`),
  CONSTRAINT `FK_E67AB75B64D218E` FOREIGN KEY (`location_id`) REFERENCES `hr_location` (`id`),
  CONSTRAINT `FK_E67AB75B6DD822C6` FOREIGN KEY (`job_title_id`) REFERENCES `hr_job_title` (`id`),
  CONSTRAINT `FK_E67AB75B727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_E67AB75B7C5F773C` FOREIGN KEY (`pay_schedule_id`) REFERENCES `pay_period` (`id`),
  CONSTRAINT `FK_E67AB75BAE80F5DF` FOREIGN KEY (`department_id`) REFERENCES `hr_department` (`id`),
  CONSTRAINT `FK_E67AB75BC185C5A2` FOREIGN KEY (`pay_period_id`) REFERENCES `pay_period` (`id`),
  CONSTRAINT `FK_E67AB75BEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_employee`
--

LOCK TABLES `hr_employee` WRITE;
/*!40000 ALTER TABLE `hr_employee` DISABLE KEYS */;
INSERT INTO `hr_employee` VALUES (1,1,2,4,2,3,1,1,NULL,1,'Christine','U','Pua','Female','Regular',80000.00,1,'christinepua@mylilys.com','00001','2015-06-26',NULL,0,NULL,'2015-06-26 13:35:46',NULL,0),(4,1,1,4,1,3,2,2,NULL,5,'Ramon','Tancuan','Pua','Male','Regular',100000.00,1,'ramonpua72@gmail.com','00004','2015-07-24',NULL,0,NULL,'2015-07-24 18:01:30',NULL,0),(5,4,13,4,1,3,2,1,NULL,5,'Helen','Uy','Pua','Female','Regular',100000.00,1,'accountinghead.nfpi@gmail.com','00005','2015-07-27',NULL,0,NULL,'2015-07-27 11:21:34',NULL,0),(7,7,31,2,2,2,1,1,4,5,'Norberto','Jara','Castillo','Male','Regular',15000.00,1,'norberto.newbornfoodproducts@gmail.com','00007','2015-07-27',NULL,0,NULL,'2015-07-27 12:07:01',NULL,0),(19,2,3,2,2,4,1,1,1,5,'Leoncio, III','Francisco','Fraga','Male','Regular',15787.00,1,'employee.relations@mylilys.com','00019','2015-07-28',NULL,0,NULL,'2015-07-28 16:46:41',NULL,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_employee_benefits`
--

LOCK TABLES `hr_employee_benefits` WRITE;
/*!40000 ALTER TABLE `hr_employee_benefits` DISABLE KEYS */;
INSERT INTO `hr_employee_benefits` VALUES (1,1,1,NULL),(2,1,2,NULL),(3,1,3,NULL),(4,1,4,NULL),(5,1,5,NULL),(6,1,6,NULL),(7,1,8,NULL),(8,1,9,NULL),(9,1,10,NULL),(10,1,11,NULL),(11,1,12,NULL),(21,4,1,NULL),(22,4,2,NULL),(23,4,3,NULL),(24,4,4,NULL),(25,4,5,NULL),(26,4,7,NULL),(27,4,8,NULL),(28,4,11,NULL),(29,4,12,NULL),(30,5,1,NULL),(31,5,2,NULL),(32,5,3,NULL),(33,5,4,NULL),(34,5,5,NULL),(35,5,6,NULL),(36,5,8,NULL),(37,5,9,NULL),(38,5,10,NULL),(39,5,11,NULL),(40,5,12,NULL),(52,7,1,NULL),(53,7,2,NULL),(54,7,3,NULL),(55,7,4,NULL),(56,7,5,NULL),(57,7,7,NULL),(58,7,8,NULL),(59,7,11,NULL),(60,7,12,NULL),(79,19,1,NULL),(80,19,2,NULL),(81,19,3,NULL),(82,19,4,NULL),(83,19,5,NULL),(84,19,7,NULL),(85,19,8,NULL),(86,19,11,NULL),(87,19,12,NULL);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_employee_leaves`
--

LOCK TABLES `hr_employee_leaves` WRITE;
/*!40000 ALTER TABLE `hr_employee_leaves` DISABLE KEYS */;
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_15ACC9148C03F15C` (`employee_id`),
  UNIQUE KEY `UNIQ_15ACC914CCCFBA31` (`upload_id`),
  KEY `IDX_15ACC914F5B7AF75` (`address_id`),
  CONSTRAINT `FK_15ACC9148C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_15ACC914CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_15ACC914F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `cnt_address` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_employee_profile`
--

LOCK TABLES `hr_employee_profile` WRITE;
/*!40000 ALTER TABLE `hr_employee_profile` DISABLE KEYS */;
INSERT INTO `hr_employee_profile` VALUES (1,1,1,1,'1990-07-18','000-000-000-000','00-0000000-0','0000-0000-0000','0000-0000-0000'),(4,4,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(5,5,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,7,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(19,19,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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
  PRIMARY KEY (`id`),
  KEY `IDX_28D866CFDD670628` (`appraisal_id`),
  KEY `IDX_28D866CF8C03F15C` (`employee_id`),
  KEY `IDX_28D866CFEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_28D866CF8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_28D866CFDD670628` FOREIGN KEY (`appraisal_id`) REFERENCES `hr_appraisal` (`id`),
  CONSTRAINT `FK_28D866CFEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_evaluator`
--

LOCK TABLES `hr_evaluator` WRITE;
/*!40000 ALTER TABLE `hr_evaluator` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_event`
--

LOCK TABLES `hr_event` WRITE;
/*!40000 ALTER TABLE `hr_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_event` ENABLE KEYS */;
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
  CONSTRAINT `FK_DF7A907CEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_DF7A907C3E23E247` FOREIGN KEY (`dept_id`) REFERENCES `hr_department` (`id`),
  CONSTRAINT `FK_DF7A907C64D218E` FOREIGN KEY (`location_id`) REFERENCES `hr_location` (`id`),
  CONSTRAINT `FK_DF7A907C8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_DF7A907CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user_user` (`id`)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_issued_property`
--

LOCK TABLES `hr_issued_property` WRITE;
/*!40000 ALTER TABLE `hr_issued_property` DISABLE KEYS */;
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
INSERT INTO `hr_job_level` VALUES (1,1,'Rank and File','2015-06-26 13:31:56'),(2,1,'Officer','2015-06-26 13:31:56'),(3,1,'Managerial','2015-06-26 13:31:56'),(4,1,'Executive','2015-06-26 13:31:56');
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
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_job_title`
--

LOCK TABLES `hr_job_title` WRITE;
/*!40000 ALTER TABLE `hr_job_title` DISABLE KEYS */;
INSERT INTO `hr_job_title` VALUES (1,1,NULL,1,'Rank','President/CEO','','2015-06-26 13:31:56'),(2,1,NULL,1,'Rank','VP Operations','','2015-06-26 13:31:56'),(3,2,NULL,1,'Rank','HR Employee Relations Officer','','2015-06-26 13:31:56'),(4,2,NULL,1,'Rank','HR Compensation and Benefits Assistant','','2015-06-26 13:31:56'),(5,2,NULL,1,'Rank','HR Recruitment Assistant','','2015-06-26 13:31:56'),(6,3,NULL,1,'Rank','Admin Officer-in-Charge','','2015-06-26 13:31:56'),(7,3,NULL,1,'Rank','Admin Officer','','2015-06-26 13:31:56'),(8,3,NULL,1,'Rank','Admin Assistant','','2015-06-26 13:31:56'),(9,3,NULL,1,'Rank','IT/Technical Support Officer','','2015-06-26 13:31:56'),(10,3,NULL,1,'Rank','Document Controller Specialist','','2015-06-26 13:31:56'),(11,3,NULL,1,'Rank','Plant Admin','','2015-06-26 13:31:56'),(12,3,NULL,1,'Rank','Janitor/Maintenance','','2015-06-26 13:31:56'),(13,4,NULL,1,'Rank','Accounting Head','','2015-06-26 13:31:56'),(14,4,NULL,1,'Rank','Accounting Officer-in-Charge','','2015-06-26 13:31:56'),(15,4,NULL,1,'Rank','Accounting Assistant','','2015-06-26 13:31:56'),(16,4,NULL,1,'Rank','Account Receivable','','2015-06-26 13:31:56'),(17,4,NULL,1,'Rank','Account Receivable Staff','','2015-06-26 13:31:56'),(18,4,NULL,1,'Rank','Treasury Officer','','2015-06-26 13:31:56'),(19,5,NULL,1,'Rank','Trade Marketing Specialist (Key Accounts)','','2015-06-26 13:31:56'),(20,5,NULL,1,'Rank','Trade Marketing Specialist (Distributor)','','2015-06-26 13:31:56'),(21,5,NULL,1,'Rank','Brand Marketing Officer','','2015-06-26 13:31:56'),(22,5,NULL,1,'Rank','Graphic Artist','','2015-06-26 13:31:56'),(23,5,NULL,1,'Rank','Business Development Manager','','2015-06-26 13:31:56'),(24,6,NULL,1,'Rank','Merchandising Office-in-Charge','','2015-06-26 13:31:56'),(25,6,NULL,1,'Rank','Merchandising Operations Supervisor','','2015-06-26 13:31:56'),(26,6,NULL,1,'Rank','Merchandising Support Assistant','','2015-06-26 13:31:56'),(27,6,NULL,1,'Rank','Sales Commando','','2015-06-26 13:31:56'),(28,7,NULL,1,'Rank','National Sales Manager','','2015-06-26 13:31:56'),(29,7,NULL,1,'Rank','Sales Admin Assistant','','2015-06-26 13:31:56'),(30,7,NULL,1,'Rank','Junior Sales Manager','','2015-06-26 13:31:56'),(31,7,NULL,1,'Rank','Distributor Sales Specialist','','2015-06-26 13:31:56'),(32,7,NULL,1,'Rank','Key Account Specialist','','2015-06-26 13:31:56'),(33,7,NULL,1,'Rank','Channel Sales Supervisor','','2015-06-26 13:31:56'),(34,7,NULL,1,'Rank','Senior Accounts Specialist','','2015-06-26 13:31:56'),(35,8,NULL,1,'Rank','Sales Monitoring Officer-in-Charge','','2015-06-26 13:31:56'),(36,8,NULL,1,'Rank','Sales Monitoring Staff','','2015-06-26 13:31:56'),(37,9,NULL,1,'Rank','Purchasing Officer-in-Charge','','2015-06-26 13:31:56'),(38,10,NULL,1,'Rank','Logistic Officer-in-Charge','','2015-06-26 13:31:56'),(39,10,NULL,1,'Rank','Logistic Assistant','','2015-06-26 13:31:56'),(40,10,NULL,1,'Rank','Logistic Staff','','2015-06-26 13:31:56'),(41,10,NULL,1,'Rank','Delivery Driver','','2015-06-26 13:31:56'),(42,10,NULL,1,'Rank','Delivery Helper','','2015-06-26 13:31:56'),(43,11,NULL,1,'Rank','Warehousing Supervisor','','2015-06-26 13:31:56'),(44,11,NULL,1,'Rank','Warehousing Assistant','','2015-06-26 13:31:56'),(45,11,NULL,1,'Rank','Warehouseman','','2015-06-26 13:31:56'),(46,12,NULL,1,'Rank','R&D Junior Manager','','2015-06-26 13:31:56'),(47,12,NULL,1,'Rank','R&D Assistant','','2015-06-26 13:31:56'),(48,13,NULL,1,'Rank','Quality Assurance Specialist','','2015-06-26 13:31:56'),(49,13,NULL,1,'Rank','Quality Assurance Assistant','','2015-06-26 13:31:56'),(50,14,NULL,1,'Rank','Plant Operations Manager','','2015-06-26 13:31:56'),(51,14,NULL,1,'Rank','Production Officer','','2015-06-26 13:31:56'),(52,14,NULL,1,'Rank','Production Worker/Factory Worker','','2015-06-26 13:31:56');
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
  `applied_leave_days` int(11) DEFAULT NULL,
  `code` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_BD91688A8C03F15C` (`employee_id`),
  KEY `IDX_BD91688A75CE0942` (`emp_leave_id`),
  KEY `IDX_BD91688AEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_BD91688AEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_BD91688A75CE0942` FOREIGN KEY (`emp_leave_id`) REFERENCES `hr_employee_leaves` (`id`),
  CONSTRAINT `FK_BD91688A8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_leave`
--

LOCK TABLES `hr_leave` WRITE;
/*!40000 ALTER TABLE `hr_leave` DISABLE KEYS */;
/*!40000 ALTER TABLE `hr_leave` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_manpower_request`
--

LOCK TABLES `hr_manpower_request` WRITE;
/*!40000 ALTER TABLE `hr_manpower_request` DISABLE KEYS */;
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
  PRIMARY KEY (`id`),
  KEY `IDX_5392661A8C03F15C` (`employee_id`),
  KEY `IDX_5392661AEEFE5067` (`user_create_id`),
  CONSTRAINT `FK_5392661AEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_5392661A8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
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
  `approver_id` int(11) DEFAULT NULL,
  `request_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6851DFECCCFBA31` (`upload_id`),
  UNIQUE KEY `UNIQ_6851DFE427EB8A5` (`request_id`),
  KEY `IDX_6851DFE8C03F15C` (`employee_id`),
  KEY `IDX_6851DFEEEFE5067` (`user_create_id`),
  KEY `IDX_6851DFEBB23766C` (`approver_id`),
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
  CONSTRAINT `FK_D512762AEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_D512762A8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_request`
--

LOCK TABLES `hr_request` WRITE;
/*!40000 ALTER TABLE `hr_request` DISABLE KEYS */;
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
  CONSTRAINT `FK_74F478EAEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_74F478EA8313F474` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_type` (`id`)
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
  `request_id` int(11) DEFAULT NULL,
  `user_create_id` int(11) DEFAULT NULL,
  `upload_id` int(11) DEFAULT NULL,
  `date_filed` datetime NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_approved` datetime DEFAULT NULL,
  `date_create` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_935822B4427EB8A5` (`request_id`),
  UNIQUE KEY `UNIQ_935822B4CCCFBA31` (`upload_id`),
  KEY `IDX_935822B48C03F15C` (`employee_id`),
  KEY `IDX_935822B4BB23766C` (`approver_id`),
  KEY `IDX_935822B4EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_935822B4CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_935822B4427EB8A5` FOREIGN KEY (`request_id`) REFERENCES `hr_request` (`id`),
  CONSTRAINT `FK_935822B48C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_935822B4BB23766C` FOREIGN KEY (`approver_id`) REFERENCES `user_user` (`id`),
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
  CONSTRAINT `FK_BFC8CBB8EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_BFC8CBB88C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_BFC8CBB8EC8B7ADE` FOREIGN KEY (`period_id`) REFERENCES `pay_period` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_salary_history`
--

LOCK TABLES `hr_salary_history` WRITE;
/*!40000 ALTER TABLE `hr_salary_history` DISABLE KEYS */;
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
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `grace_period` int(11) NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `day_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `day_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `half_day` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E1DDD301EEFE5067` (`user_create_id`),
  CONSTRAINT `FK_E1DDD301EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hr_schedule`
--

LOCK TABLES `hr_schedule` WRITE;
/*!40000 ALTER TABLE `hr_schedule` DISABLE KEYS */;
INSERT INTO `hr_schedule` VALUES (1,1,'2015-04-14 08:30:00','2015-04-14 17:30:00',30,'Merchandising, Sales and Marketing - Managerial','2015-06-26 13:31:56','','',0),(2,1,'2015-04-14 08:30:00','2015-04-14 17:30:00',15,'Merchandising, Sales and Marketing - Rank and File','2015-06-26 13:31:56','','',0),(3,1,'2015-04-14 09:00:00','2015-04-14 18:00:00',30,'Office Staff - Managerial','2015-06-26 13:31:56','','',0),(4,1,'2015-04-14 09:00:00','2015-04-14 18:00:00',15,'Office Staff - Rank and File','2015-06-26 13:31:56','','',0),(5,1,'2015-04-14 07:00:00','2015-04-14 16:00:00',30,'Production - Managerial','2015-06-26 13:31:56','','',0),(6,1,'2015-04-14 07:00:00','2015-04-14 16:00:00',15,'Production - Rank and File','2015-06-26 13:31:56','','',0),(7,1,'2015-04-14 06:00:00','2015-04-14 15:00:00',30,'Delivery - Managerial','2015-06-26 13:31:56','','',0),(8,1,'2015-04-14 06:00:00','2015-04-14 15:00:00',15,'Delivery - Rank and File','2015-06-26 13:31:56','','',0);
/*!40000 ALTER TABLE `hr_schedule` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_storage_localfile`
--

LOCK TABLES `media_storage_localfile` WRITE;
/*!40000 ALTER TABLE `media_storage_localfile` DISABLE KEYS */;
INSERT INTO `media_storage_localfile` VALUES (1,1,'/html/lilys.qalphalabs.com/app/../web/uploads/f4/fa/f4/fa/558ce4b99faf4.jpg'),(2,2,'/html/lilys.qalphalabs.com/app/../web/uploads/47/33/47/33/558ce65643347.jpg');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_upload`
--

LOCK TABLES `media_upload` WRITE;
/*!40000 ALTER TABLE `media_upload` DISABLE KEYS */;
INSERT INTO `media_upload` VALUES (1,1,'558ce4b99faf4.jpg','http://lilys.qalphalabs.com/uploads/f4/fa/558ce4b99faf4.jpg',1,'local_file','2015-06-26 13:35:53'),(2,1,'558ce65643347.jpg','http://lilys.qalphalabs.com/uploads/47/33/558ce65643347.jpg',1,'local_file','2015-06-26 13:42:46');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
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
  CONSTRAINT `FK_E88C38BFAFEAC96` FOREIGN KEY (`pay13th_id`) REFERENCES `pay_13th` (`id`),
  CONSTRAINT `FK_E88C38BF5DD5005B` FOREIGN KEY (`payroll_period_id`) REFERENCES `pay_payroll_period` (`id`),
  CONSTRAINT `FK_E88C38BF8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
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
  CONSTRAINT `FK_AF757B0D8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_AF757B0D5DD5005B` FOREIGN KEY (`payroll_period_id`) REFERENCES `pay_payroll_period` (`id`)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_payroll_period`
--

LOCK TABLES `pay_payroll_period` WRITE;
/*!40000 ALTER TABLE `pay_payroll_period` DISABLE KEYS */;
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
INSERT INTO `pay_schedule` VALUES (1,1,1,'{\"weekly_start\":0,\"weekly_end\":5,\"weekly_pay\":1,\"monthly_start\":28,\"monthly_end\":27,\"monthly_pay\":0,\"semimonthly_start1\":26,\"semimonthly_end1\":10,\"semimonthly_pay1\":15,\"semimonthly_start2\":11,\"semimonthly_end2\":25,\"semimonthly_pay2\":0}','Semi-Monthly','2015-06-26 13:31:56'),(2,3,1,'{\"weekly_start\":0,\"weekly_end\":5,\"weekly_pay\":1,\"monthly_start\":28,\"monthly_end\":27,\"monthly_pay\":0,\"semimonthly_start1\":26,\"semimonthly_end1\":10,\"semimonthly_pay1\":15,\"semimonthly_start2\":11,\"semimonthly_end2\":25,\"semimonthly_pay2\":0}','Weekly','2015-06-26 13:31:56');
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
  CONSTRAINT `FK_91B4247BEEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_91B4247B8C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
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
/*!40000 ALTER TABLE `profile_phone` ENABLE KEYS */;
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
  CONSTRAINT `FK_A0509AA7CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_A0509AA78C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_A0509AA7BB23766C` FOREIGN KEY (`approver_id`) REFERENCES `hr_employee` (`id`),
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
  CONSTRAINT `FK_C8309007EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`),
  CONSTRAINT `FK_C8309007CE73868F` FOREIGN KEY (`loan_id`) REFERENCES `rem_loans` (`id`)
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
  CONSTRAINT `FK_F30E7C84CCCFBA31` FOREIGN KEY (`upload_id`) REFERENCES `media_upload` (`id`),
  CONSTRAINT `FK_F30E7C848C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`),
  CONSTRAINT `FK_F30E7C84BB23766C` FOREIGN KEY (`approver_id`) REFERENCES `hr_employee` (`id`),
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F7129A8092FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_F7129A808C03F15C` (`employee_id`),
  CONSTRAINT `FK_F7129A808C03F15C` FOREIGN KEY (`employee_id`) REFERENCES `hr_employee` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_user`
--

LOCK TABLES `user_user` WRITE;
/*!40000 ALTER TABLE `user_user` DISABLE KEYS */;
INSERT INTO `user_user` VALUES (1,NULL,'admin','admin',1,'c4oj60rdqjso0444gccoosg40sccss4','cGd3TCpRgl0XSFIVSkeSVqEEIhscQEumO3jjxUE3kKwIpbD+zt0tzcr3hK/scFh25EHShYaHQa/VcYyTZLB3IA==','2015-08-22 10:57:01',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Administrator','test@test.com','test@test.com'),(2,NULL,'hr_lilys','hr_lilys',1,'nt3r1xjhoxcssc0gsk8800ocwowk8oc','etLcKBY0+u8CU1kkUjGO1Se3hEFOnJ7vh20MAsWF5NyG7fNGbskLj1zxmkWXPbSAdOSpFaVg+Zl4lgCQg8H3bQ==','2015-07-23 09:16:32',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'HR-Admin','hr@mylilys.com','hr@mylilys.com'),(3,1,'cupua','cupua',1,'dnagoyhfj8ggsssgsswk8ks4skwo8cs','RhDLRz9tzWwr47Ew0MDkF42L6oB+jnX0wATOEus8e3vYhzm/nzir2+R7agciNNlbS/MrdFjckl7Ke629oX4+yw==','2015-07-23 18:25:05',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Christine Pua','christinepua@mylilys.com','christinepua@mylilys.com'),(5,NULL,'Recruitment','recruitment',1,'sijvpkcwkgg80wcwco0wso0o4wg0wws','EqYBuaTGMfsXhqm5wIYlpm6hbgR3Li0/NrUoEUq3xhboZuClpCP2yOj+9fmobm10ALUpKNw3rMuqJ3SZbgGo2g==','2015-07-28 17:02:46',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Charmaine Oquendo','human_resource@mylilys.com','human_resource@mylilys.com'),(9,4,'rtpua','rtpua',1,'8cygrqts2gw04444sk4kk8sck0ok448','B/gi/JPBUsPUnQ0bxQq/UqIx0J3iNFtSDTXnR/mLG0GUYTjqgutlgsOJtGUCQL2DmdTmj2zOJfhsuUaeB6hiFw==',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Ramon Pua','ramonpua72@gmail.com','ramonpua72@gmail.com'),(10,5,'hupua','hupua',1,'71jy2aextyos448wk48o48884408g8g','x0NwKPZ0qc99tukSL8Z3dO5Nk54WUTpXlSVsj6rBLoQa2f8tPziGFCycYDDsYK13WvkzsqWMYwyNDHVgjYs6FQ==',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Helen Pua','accountinghead.nfpi@gmail.com','accountinghead.nfpi@gmail.com'),(12,7,'njcastillo','njcastillo',1,'m7uun5y1g2s0ck84sso0kcsckg4c8g0','P8TQB8ls36Y3GcvGFDHrGowZCTix0kS8ffmDkBinSfDyYT7sTA9KF8EX5en34iKE9kUuBTSiak1zzI0Co76Xsg==',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Norberto Castillo','norberto.newbornfoodproducts@gmail.com','norberto.newbornfoodproducts@gmail.com'),(24,19,'liffraga','liffraga',1,'tgh9mf4bpm8skgsgs00cosg8w0ssggs','+BR73DiUNGLhhzeBMp9uac0IQockqrWIA2xeSp76y3Wp6azdrFV/0IwbD6B8B6mXkhyjRCzvvQEHNaFX3z9wFQ==','2015-07-28 17:00:42',0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Leoncio, III Fraga','employee.relations@mylilys.com','employee.relations@mylilys.com');
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
INSERT INTO `user_usergroup` VALUES (2,1),(3,1),(5,1),(9,2),(10,2),(12,2),(24,2);
/*!40000 ALTER TABLE `user_usergroup` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-08-26 16:05:37
