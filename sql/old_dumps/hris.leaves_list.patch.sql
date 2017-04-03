-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2015 at 04:15 AM
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
  `gender` longtext COLLATE utf8_unicode_ci,
  `emp_status` longtext COLLATE utf8_unicode_ci,
  `accrue_enabled` tinyint(1) NOT NULL,
  `accrue_count` double DEFAULT NULL,
  `accrue_rule` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `carried_enabled` tinyint(1) NOT NULL,
  `carry_percentage` int(11) DEFAULT NULL,
  `carry_period` int(11) DEFAULT NULL,
  `leave_count` double NOT NULL,
  `count_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `service_months` int(11) DEFAULT NULL,
  `payment_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `addtl_requirements` longtext COLLATE utf8_unicode_ci,
  `date_create` datetime NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_E2BC4391EEFE5067` (`user_create_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `leave_type`
--

INSERT INTO `leave_type` (`id`, `user_create_id`, `gender`, `emp_status`, `accrue_enabled`, `accrue_count`, `accrue_rule`, `carried_enabled`, `carry_percentage`, `carry_period`, `leave_count`, `count_type`, `service_months`, `payment_type`, `addtl_requirements`, `date_create`, `name`, `notes`) VALUES
(2, NULL, 'a:2:{s:4:"Male";s:4:"Male";s:6:"Female";s:6:"Female";}', 'a:1:{s:7:"Regular";s:7:"Regular";}', 0, NULL, NULL, 0, NULL, NULL, 5, 'per Year', 12, 'FULL', NULL, '2015-07-26 23:20:33', 'Service Incentive Leave', 'Government-mandated leave for employees that have rendered at least 1 year of service in the company'),
(4, NULL, 'a:2:{s:4:"Male";s:4:"Male";s:6:"Female";s:6:"Female";}', 'a:1:{s:7:"Regular";s:7:"Regular";}', 1, 1, 'Yearly', 1, 100, 8, 12, 'per Year', 12, 'NONE', NULL, '2015-08-07 05:06:51', 'Sick Leave', '12 days of Leave for employees that are sick'),
(5, NULL, 'a:1:{s:4:"Male";s:4:"Male";}', 'a:1:{s:7:"Regular";s:7:"Regular";}', 0, NULL, NULL, 0, NULL, NULL, 7, 'per Request', 6, 'NONE', NULL, '2015-08-07 05:38:25', 'Paternity Leave', '7 days of leave for Male employees during Birth or Miscarriage of Legitimate Spouse'),
(6, NULL, 'a:2:{s:4:"Male";s:4:"Male";s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 0, NULL, NULL, 0, NULL, NULL, 3, 'per Request', 6, 'NONE', NULL, '2015-08-07 05:51:17', 'Bereavement', '3 days of leave in the event of death of a family member.'),
(7, NULL, 'a:2:{s:4:"Male";s:4:"Male";s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 0, NULL, NULL, 0, NULL, NULL, 7, 'per Year', 12, 'NONE', NULL, '2015-08-07 05:54:26', 'Solo Parent Leave', 'Persons who fall under the definition of solo parents and who have rendered service of at least one year are entitled to 7 working days of leave to attend to their parental duties.'),
(8, NULL, 'a:2:{s:4:"Male";s:4:"Male";s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 0, NULL, NULL, 0, NULL, NULL, 2, 'per Request', 6, 'NONE', NULL, '2015-08-07 05:56:15', 'Calamity Leave', '2 days of leave for employees that are directly affected by calamity (e.g. Fire, Flood, Typhoon)'),
(9, NULL, 'a:1:{s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 0, NULL, NULL, 0, NULL, NULL, 60, 'per Request', 6, 'NONE', NULL, '2015-08-07 05:58:21', 'Magna Carta for Women', 'Leave for female employees who underwent surgery caused by gynecological disorders'),
(10, NULL, 'a:1:{s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 0, NULL, NULL, 0, NULL, NULL, 60, 'per Request', 6, 'FULL', NULL, '2015-08-07 06:00:25', 'Maternity Leave (Normal Delivery)', ''),
(11, NULL, 'a:1:{s:6:"Female";s:6:"Female";}', 'a:2:{s:12:"Probationary";s:12:"Probationary";s:7:"Regular";s:7:"Regular";}', 0, NULL, NULL, 0, NULL, NULL, 78, 'per Request', 6, 'FULL', NULL, '2015-08-07 06:01:21', 'Maternity Leave (Caesarian)', '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leave_type`
--
ALTER TABLE `leave_type`
  ADD CONSTRAINT `FK_E2BC4391EEFE5067` FOREIGN KEY (`user_create_id`) REFERENCES `user_user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
