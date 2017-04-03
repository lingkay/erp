-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2015 at 07:40 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hris2`
--

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

CREATE TABLE IF NOT EXISTS `leave_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_create_id` int(11) DEFAULT NULL,
  `accrue_enabled` tinyint(1) NOT NULL,
  `accrue_count` double DEFAULT NULL,
  `carried_enabled` tinyint(1) NOT NULL,
  `leave_count` double NOT NULL,
  `service_months` int(11) DEFAULT NULL,
  `payment_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addtl_requirements` longtext COLLATE utf8_unicode_ci,
  `accrue_rule` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `carry_percentage` int(11) DEFAULT NULL,
  `carry_period` int(11) DEFAULT NULL,
  `gender` longtext COLLATE utf8_unicode_ci,
  `emp_status` longtext COLLATE utf8_unicode_ci,
  `count_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_E2BC4391EEFE5067` (`user_create_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `leave_type`
--

INSERT INTO `leave_type` (`id`, `user_create_id`, `accrue_enabled`, `accrue_count`, `carried_enabled`, `leave_count`, `service_months`, `payment_type`, `date_create`, `name`, `addtl_requirements`, `accrue_rule`, `carry_percentage`, `carry_period`, `gender`, `emp_status`, `count_type`, `notes`) VALUES
(2, NULL, 0, NULL, 0, 5, 12, 'FULL', '2015-07-26 23:20:33', 'Service Incentive Leave', NULL, NULL, NULL, NULL, 'a:2:{s:4:"Male";s:4:"Male";s:6:"Female";s:6:"Female";}', 'a:1:{s:7:"Regular";s:7:"Regular";}', 'per Year', 'Government-mandated leave for employees that have rendered at least 1 year of service in the company'),
(4, NULL, 1, 1, 1, 12, 12, 'NONE', '2015-08-07 05:06:51', 'Sick Leave', NULL, 'Yearly', 100, 8, 'a:2:{s:4:"Male";s:4:"Male";s:6:"Female";s:6:"Female";}', 'a:1:{s:7:"Regular";s:7:"Regular";}', 'per Year', '12 days of Leave for employees that are sick'),
(5, NULL, 0, NULL, 0, 7, 6, 'NONE', '2015-08-07 05:38:25', 'Paternity Leave', NULL, NULL, NULL, NULL, 'a:1:{s:4:"Male";s:4:"Male";}', 'a:1:{s:7:"Regular";s:7:"Regular";}', 'per Request', '7 days of leave for Male employees during Birth or Miscarriage of Legitimate Spouse'),
(6, NULL, 0, NULL, 0, 3, 6, 'NONE', '2015-08-07 05:51:17', 'Bereavement', NULL, NULL, NULL, NULL, 'a:2:{s:4:"Male";s:4:"Male";s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 'per Request', '3 days of leave in the event of death of a family member.'),
(7, NULL, 0, NULL, 0, 7, 12, 'NONE', '2015-08-07 05:54:26', 'Solo Parent Leave', NULL, NULL, NULL, NULL, 'a:2:{s:4:"Male";s:4:"Male";s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 'per Year', 'Persons who fall under the definition of solo parents and who have rendered service of at least one year are entitled to 7 working days of leave to attend to their parental duties.'),
(8, NULL, 0, NULL, 0, 2, 6, 'NONE', '2015-08-07 05:56:15', 'Calamity Leave', NULL, NULL, NULL, NULL, 'a:2:{s:4:"Male";s:4:"Male";s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 'per Request', '2 days of leave for employees that are directly affected by calamity (e.g. Fire, Flood, Typhoon)'),
(9, NULL, 0, NULL, 0, 60, 6, 'NONE', '2015-08-07 05:58:21', 'Magna Carta for Women', NULL, NULL, NULL, NULL, 'a:1:{s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 'per Request', 'Leave for female employees who underwent surgery caused by gynecological disorders'),
(10, NULL, 0, NULL, 0, 60, 6, 'FULL', '2015-08-07 06:00:25', 'Maternity Leave (Normal Delivery)', NULL, NULL, NULL, NULL, 'a:1:{s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 'per Request', ''),
(11, NULL, 0, NULL, 0, 78, 6, 'FULL', '2015-08-07 06:01:21', 'Maternity Leave (Caesarian)', NULL, NULL, NULL, NULL, 'a:1:{s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 'per Request', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
