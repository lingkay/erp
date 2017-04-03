-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2015 at 08:10 AM
-- Server version: 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hris`
--

-- --------------------------------------------------------

--
-- Table structure for table `batchentry`
--

CREATE TABLE IF NOT EXISTS `batchentry` (
`id` bigint(11) NOT NULL,
  `transaction_id` bigint(11) unsigned NOT NULL,
  `expiry_date` datetime NOT NULL,
  `product_id` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `billofmaterial`
--

CREATE TABLE IF NOT EXISTS `billofmaterial` (
`id` int(8) unsigned NOT NULL,
  `name` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bomdimension`
--

CREATE TABLE IF NOT EXISTS `bomdimension` (
`id` bigint(10) unsigned NOT NULL,
  `template_id` int(8) unsigned NOT NULL,
  `shortcode` varchar(25) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bominput`
--

CREATE TABLE IF NOT EXISTS `bominput` (
`id` bigint(10) unsigned NOT NULL,
  `bom_id` int(8) unsigned NOT NULL,
  `product_id` int(8) unsigned NOT NULL,
  `quantity` decimal(10,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bommaterial`
--

CREATE TABLE IF NOT EXISTS `bommaterial` (
`id` int(10) unsigned NOT NULL,
  `template_id` int(8) unsigned NOT NULL,
  `product_id` int(8) unsigned NOT NULL,
  `formula` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bomoutput`
--

CREATE TABLE IF NOT EXISTS `bomoutput` (
`id` bigint(10) unsigned NOT NULL,
  `bom_id` int(8) unsigned NOT NULL,
  `product_id` int(8) unsigned NOT NULL,
  `quantity` decimal(10,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bomtemplate`
--

CREATE TABLE IF NOT EXISTS `bomtemplate` (
`id` int(8) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `output_code` varchar(80) DEFAULT NULL,
  `output_name` varchar(80) DEFAULT NULL,
  `prodgroup_id` int(6) unsigned NOT NULL,
  `uom` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `branchmanagement`
--

CREATE TABLE IF NOT EXISTS `branchmanagement` (
`id` int(11) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `code` varchar(50) NOT NULL,
  `address` varchar(150) NOT NULL,
  `contact_number` varchar(80) NOT NULL,
  `fax` int(50) NOT NULL,
  `pmterms` varchar(80) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `branchmanagement`
--

INSERT INTO `branchmanagement` (`id`, `name`, `code`, `address`, `contact_number`, `fax`, `pmterms`) VALUES
(1, 'BF Homes', 'BF', 'sample address', '1234567', 123456789, 'Cash'),
(2, 'Makati - Rada', 'MKT', 'Makati City', '123-45-67', 123, 'Cash'),
(3, 'Katipunan Ave', 'KAT', 'Katipunan', '123-45-67', 123, ''),
(4, 'Greenfield District', 'GD', 'greenfield district', '123-45-67', 123, ''),
(5, 'Newport Mall', 'NPM', 'Pasay City', '123-45-67', 123, '');

-- --------------------------------------------------------

--
-- Table structure for table `configentry`
--

CREATE TABLE IF NOT EXISTS `configentry` (
  `id` varchar(80) NOT NULL,
  `value` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `configentry`
--

INSERT INTO `configentry` (`id`, `value`) VALUES
('gist_customer_warehouse_default', '2'),
('gist_inventory_restricted_default', '16'),
('gist_product_group_default', '15'),
('gist_service_role_default', '11'),
('gist_super_user_role_default', '4'),
('gist_supplier_warehouse_default', '1');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
`id` int(6) unsigned NOT NULL,
  `date_in` datetime NOT NULL,
  `name` varchar(80) NOT NULL,
  `address` varchar(150) NOT NULL,
  `contact_number` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `contact_person` varchar(80) NOT NULL,
  `notes` text NOT NULL,
  `warehouse_id` int(8) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
`id` int(4) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `roles` text,
  `access` longtext
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`id`, `name`, `roles`, `access`) VALUES
(2, 'User', NULL, 'a:4:{s:18:"cat_dashboard.menu";b:1;s:17:"hris_profile.menu";b:1;s:26:"hris_profile_employee.menu";b:1;s:23:"hris_profile_leave.menu";b:1;}'),
(4, 'Administrator', 'a:0:{}', 'a:56:{s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:27:"hris_workforce_benefits.add";b:1;s:28:"hris_workforce_benefits.edit";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:22:"hris_applications.view";b:1;s:21:"hris_applications.add";b:1;s:22:"hris_applications.edit";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:20:"hris_employeedb.view";b:1;s:13:"hris_faq.menu";b:1;s:13:"hris_faq.view";b:1;s:12:"hris_faq.add";b:1;s:13:"hris_faq.edit";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;}'),
(11, 'Encoder', 'a:0:{}', 'a:1:{s:18:"cat_dashboard.menu";b:1;}'),
(12, 'Approver', 'a:0:{}', 'a:1:{s:18:"cat_dashboard.menu";b:1;}');

-- --------------------------------------------------------

--
-- Table structure for table `inventoryentry`
--

CREATE TABLE IF NOT EXISTS `inventoryentry` (
`id` bigint(12) unsigned NOT NULL,
  `transaction_id` bigint(12) unsigned NOT NULL,
  `warehouse_id` int(8) unsigned NOT NULL,
  `product_id` int(8) unsigned NOT NULL,
  `credit` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `debit` decimal(12,2) unsigned NOT NULL DEFAULT '0.00',
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `inventorystock`
--

CREATE TABLE IF NOT EXISTS `inventorystock` (
`id` bigint(10) unsigned NOT NULL,
  `warehouse_id` int(8) unsigned NOT NULL,
  `product_id` int(8) unsigned NOT NULL,
  `quantity` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `inventorytransaction`
--

CREATE TABLE IF NOT EXISTS `inventorytransaction` (
`id` bigint(12) unsigned NOT NULL,
  `date_in` datetime NOT NULL,
  `description` text NOT NULL,
  `user_id` int(5) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `logentry`
--

CREATE TABLE IF NOT EXISTS `logentry` (
`id` bigint(15) unsigned NOT NULL,
  `date_in` datetime NOT NULL,
  `user_id` int(5) unsigned NOT NULL,
  `action_id` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `data` longtext
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

--
-- Dumping data for table `logentry`
--

INSERT INTO `logentry` (`id`, `date_in`, `user_id`, `action_id`, `description`, `data`) VALUES
(1, '2014-11-30 02:14:24', 1, 'cat_user_branch_add', 'added Branch Management 1.', 'O:8:"stdClass":7:{s:2:"id";i:1;s:4:"name";s:13:"Makati - Rada";s:4:"code";s:6:"MKT-RD";s:7:"address";s:6:"Makati";s:14:"contact_number";s:11:"09221234567";s:3:"fax";s:9:"123456789";s:7:"pmterms";s:4:"Cash";}'),
(2, '2014-11-30 02:20:36', 1, 'cat_user_branch_add', 'added Branch Management 2.', 'O:8:"stdClass":7:{s:2:"id";i:2;s:4:"name";s:13:"Makati - Rada";s:4:"code";s:6:"MKT-RD";s:7:"address";s:6:"makati";s:14:"contact_number";s:11:"09221234567";s:3:"fax";s:9:"123456789";s:7:"pmterms";s:4:"Cash";}'),
(3, '2014-11-30 03:34:39', 1, 'cat_user_branch_add', 'added Branch Management 3.', 'O:8:"stdClass":7:{s:2:"id";i:3;s:4:"name";s:13:"Sample Branch";s:4:"code";s:3:"smp";s:7:"address";s:14:"sample address";s:14:"contact_number";s:11:"09123456789";s:3:"fax";s:9:"123456789";s:7:"pmterms";s:4:"Cash";}'),
(4, '2014-11-30 03:37:29', 1, 'cat_user_branch_add', 'added Branch Management 4.', 'O:8:"stdClass":7:{s:2:"id";i:4;s:4:"name";s:13:"Sample Branch";s:4:"code";s:3:"smp";s:7:"address";s:10:"sample add";s:14:"contact_number";s:11:"09123456789";s:3:"fax";s:9:"123456789";s:7:"pmterms";s:4:"cash";}'),
(5, '2014-11-30 03:37:43', 1, 'cat_user_branch_delete', 'deleted Branch Management 1.', 'O:8:"stdClass":7:{s:2:"id";i:1;s:4:"name";N;s:4:"code";N;s:7:"address";N;s:14:"contact_number";N;s:3:"fax";N;s:7:"pmterms";N;}'),
(6, '2014-11-30 03:37:50', 1, 'cat_user_branch_delete', 'deleted Branch Management 2.', 'O:8:"stdClass":7:{s:2:"id";i:2;s:4:"name";N;s:4:"code";N;s:7:"address";N;s:14:"contact_number";N;s:3:"fax";N;s:7:"pmterms";N;}'),
(7, '2014-11-30 03:48:14', 1, 'cat_user_branch_add', 'added Branch Management 1.', 'O:8:"stdClass":7:{s:2:"id";i:1;s:4:"name";s:13:"Sample Branch";s:4:"code";s:3:"smp";s:7:"address";s:10:"sample add";s:14:"contact_number";s:11:"09123456789";s:3:"fax";s:9:"123456789";s:7:"pmterms";s:4:"Cash";}'),
(8, '2014-11-30 03:56:58', 1, 'cat_user_branch_add', 'added Branch Management 2.', 'O:8:"stdClass":7:{s:2:"id";i:2;s:4:"name";s:13:"Sample Branch";s:4:"code";s:3:"smp";s:7:"address";s:10:"sample add";s:14:"contact_number";s:11:"09123456789";s:3:"fax";s:9:"123456789";s:7:"pmterms";s:4:"Cash";}'),
(9, '2014-11-30 09:07:47', 1, 'cat_user_branch_add', 'added Branch Management 3.', 'O:8:"stdClass":7:{s:2:"id";i:3;s:4:"name";s:8:"Branch 2";s:4:"code";s:5:"bnch2";s:7:"address";s:8:"address2";s:14:"contact_number";s:12:"123456789123";s:3:"fax";s:6:"123456";s:7:"pmterms";s:6:"Credit";}'),
(10, '2014-11-30 09:40:15', 1, 'cat_user_branch_add', 'added Branch Management 1.', 'O:8:"stdClass":7:{s:2:"id";i:1;s:4:"name";s:13:"Sample Branch";s:4:"code";s:4:"bnch";s:7:"address";s:14:"sample address";s:14:"contact_number";s:7:"1234567";s:3:"fax";s:9:"123456789";s:7:"pmterms";s:4:"Cash";}'),
(11, '2014-11-30 09:55:03', 1, 'cat_inv_pg_add', 'added Product Group 24.', 'O:8:"stdClass":3:{s:2:"id";i:24;s:4:"name";s:6:"Sinker";s:4:"code";s:3:"snk";}'),
(12, '2014-11-30 11:30:59', 1, 'cat_inv_pg_add', 'added Product Group 25.', 'O:8:"stdClass":3:{s:2:"id";i:25;s:4:"name";s:6:"Powder";s:4:"code";s:3:"pwd";}'),
(13, '2014-12-01 03:18:15', 1, 'cat_inv_prod_add', 'added Product Material 1467.', 'O:8:"stdClass":17:{s:2:"id";i:1467;s:4:"code";s:7:"SNK-001";s:4:"name";s:14:"Sample Product";s:11:"description";s:18:"sample description";s:8:"quantity";s:1:"2";s:12:"prodgroup_id";i:24;s:8:"brand_id";N;s:8:"color_id";N;s:12:"condition_id";N;s:11:"material_id";N;s:3:"uom";s:2:"kg";s:12:"flag_service";N;s:9:"flag_sale";b:0;s:13:"flag_purchase";b:0;s:10:"price_sale";s:1:"3";s:14:"price_purchase";s:1:"3";s:5:"tasks";a:0:{}}'),
(14, '2014-12-01 03:25:07', 1, 'cat_inv_prod_update', 'updated Product Material 1467.', 'O:8:"stdClass":17:{s:2:"id";i:1467;s:4:"code";s:7:"SNK-001";s:4:"name";s:14:"Sample Product";s:11:"description";s:18:"sample description";s:8:"quantity";s:1:"2";s:12:"prodgroup_id";i:24;s:8:"brand_id";N;s:8:"color_id";N;s:12:"condition_id";N;s:11:"material_id";N;s:3:"uom";s:6:"sample";s:12:"flag_service";N;s:9:"flag_sale";b:0;s:13:"flag_purchase";b:0;s:10:"price_sale";s:4:"3.00";s:14:"price_purchase";s:4:"3.00";s:5:"tasks";a:0:{}}'),
(15, '2014-12-01 07:07:40', 1, 'cat_user_user_update', 'updated User 1.', 'O:8:"stdClass":7:{s:2:"id";i:1;s:8:"username";s:5:"admin";s:5:"email";s:17:"kc@jankstudio.com";s:7:"enabled";b:1;s:8:"password";s:88:"iIztkAEIBytxjqGMyAG2UZX8xvEBoDazic8Zvfr6CVtebLfqSRAgvFLOxbeNdNBbg9dGa7PXk0cAY9/ZDZHDhw==";s:6:"groups";a:1:{i:0;O:8:"stdClass":1:{s:2:"id";i:4;}}s:9:"branch_id";i:1;}'),
(16, '2014-12-01 10:44:37', 1, 'cat_inv_prod_add', 'added Product Ingredient 1468.', 'O:8:"stdClass":17:{s:2:"id";i:1468;s:4:"code";s:7:"pwd-001";s:4:"name";s:12:"Frost Powder";s:11:"description";s:13:"description 2";s:8:"quantity";N;s:12:"prodgroup_id";i:25;s:8:"brand_id";N;s:8:"color_id";N;s:12:"condition_id";N;s:11:"material_id";N;s:3:"uom";s:6:"sample";s:12:"flag_service";b:0;s:9:"flag_sale";b:0;s:13:"flag_purchase";b:0;s:10:"price_sale";s:1:"2";s:14:"price_purchase";s:1:"2";s:5:"tasks";a:0:{}}'),
(17, '2014-12-01 10:51:05', 1, 'cat_pur_po_add', 'added Purchase Order 11.', 'O:8:"stdClass":8:{s:2:"id";i:11;s:4:"code";s:14:"POS-2014-00011";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:51:05.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:1;s:11:"total_price";s:4:"6.00";s:9:"status_id";s:5:"draft";s:7:"entries";a:1:{i:0;O:8:"stdClass":5:{s:2:"id";i:1;s:8:"quantity";s:1:"3";s:5:"price";s:4:"2.00";s:5:"po_id";i:11;s:10:"product_id";i:1468;}}}'),
(18, '2014-12-01 10:51:29', 1, 'cat_pur_po_status_update', 'status updateed for Purchase Order 11.', 'O:8:"stdClass":8:{s:2:"id";i:11;s:4:"code";s:14:"POS-2014-00011";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:51:05.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:1;s:11:"total_price";s:4:"6.00";s:9:"status_id";s:8:"approved";s:7:"entries";a:1:{i:0;O:8:"stdClass":5:{s:2:"id";i:1;s:8:"quantity";s:4:"3.00";s:5:"price";s:4:"2.00";s:5:"po_id";i:11;s:10:"product_id";i:1468;}}}'),
(19, '2014-12-01 10:51:36', 1, 'cat_pur_po_update', 'updated Purchase Order 11.', 'O:8:"stdClass":8:{s:2:"id";i:11;s:4:"code";s:14:"POS-2014-00011";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:51:05.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:1;s:11:"total_price";s:4:"6.00";s:9:"status_id";s:8:"approved";s:7:"entries";a:1:{i:0;O:8:"stdClass":5:{s:2:"id";i:2;s:8:"quantity";s:4:"3.00";s:5:"price";s:4:"2.00";s:5:"po_id";i:11;s:10:"product_id";i:1468;}}}'),
(20, '2014-12-01 10:51:42', 1, 'cat_pur_po_status_update', 'status updateed for Purchase Order 11.', 'O:8:"stdClass":8:{s:2:"id";i:11;s:4:"code";s:14:"POS-2014-00011";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:51:05.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:1;s:11:"total_price";s:4:"6.00";s:9:"status_id";s:4:"sent";s:7:"entries";a:1:{i:0;O:8:"stdClass":5:{s:2:"id";i:2;s:8:"quantity";s:4:"3.00";s:5:"price";s:4:"2.00";s:5:"po_id";i:11;s:10:"product_id";i:1468;}}}'),
(21, '2014-12-01 10:52:00', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1280.', 'O:8:"stdClass":5:{s:2:"id";i:1280;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:52:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:32:"Delivery  for PO POS-2014-00011.";s:4:"user";i:1;s:7:"entries";a:2:{i:0;O:8:"stdClass":6:{s:2:"id";i:1;s:6:"credit";s:1:"2";s:5:"debit";i:0;s:14:"transaction_id";i:1280;s:12:"warehouse_id";i:1;s:10:"product_id";i:1468;}i:1;O:8:"stdClass":6:{s:2:"id";i:2;s:6:"credit";i:0;s:5:"debit";s:1:"2";s:14:"transaction_id";i:1280;s:12:"warehouse_id";i:2;s:10:"product_id";i:1468;}}}'),
(22, '2014-12-01 10:52:00', 1, 'cat_pur_del_add', 'added Purchase Delivery 1.', 'O:8:"stdClass":6:{s:2:"id";i:1;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:52:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:11;s:7:"entries";a:1:{i:0;O:8:"stdClass":4:{s:2:"id";i:1;s:8:"quantity";s:1:"2";s:11:"delivery_id";i:1;s:10:"product_id";i:1468;}}}'),
(23, '2014-12-01 10:54:47', 1, 'cat_pur_po_add', 'added Purchase Order 12.', 'O:8:"stdClass":8:{s:2:"id";i:12;s:4:"code";s:14:"POS-2014-00012";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:54:47.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:2;s:11:"total_price";s:6:"418.00";s:9:"status_id";s:5:"draft";s:7:"entries";a:2:{i:0;O:8:"stdClass":5:{s:2:"id";i:3;s:8:"quantity";s:1:"3";s:5:"price";s:1:"6";s:5:"po_id";i:12;s:10:"product_id";i:1467;}i:1;O:8:"stdClass":5:{s:2:"id";i:4;s:8:"quantity";s:1:"8";s:5:"price";s:2:"50";s:5:"po_id";i:12;s:10:"product_id";i:1468;}}}'),
(24, '2014-12-01 10:55:05', 1, 'cat_pur_po_status_update', 'status updateed for Purchase Order 12.', 'O:8:"stdClass":8:{s:2:"id";i:12;s:4:"code";s:14:"POS-2014-00012";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:54:47.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:2;s:11:"total_price";s:6:"418.00";s:9:"status_id";s:4:"sent";s:7:"entries";a:2:{i:0;O:8:"stdClass":5:{s:2:"id";i:3;s:8:"quantity";s:4:"3.00";s:5:"price";s:4:"6.00";s:5:"po_id";i:12;s:10:"product_id";i:1467;}i:1;O:8:"stdClass":5:{s:2:"id";i:4;s:8:"quantity";s:4:"8.00";s:5:"price";s:5:"50.00";s:5:"po_id";i:12;s:10:"product_id";i:1468;}}}'),
(25, '2014-12-01 10:55:28', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1281.', 'O:8:"stdClass":5:{s:2:"id";i:1281;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:55:28.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:32:"Delivery  for PO POS-2014-00012.";s:4:"user";i:1;s:7:"entries";a:4:{i:0;O:8:"stdClass":6:{s:2:"id";i:3;s:6:"credit";s:1:"1";s:5:"debit";i:0;s:14:"transaction_id";i:1281;s:12:"warehouse_id";i:1;s:10:"product_id";i:1467;}i:1;O:8:"stdClass":6:{s:2:"id";i:4;s:6:"credit";i:0;s:5:"debit";s:1:"1";s:14:"transaction_id";i:1281;s:12:"warehouse_id";i:2;s:10:"product_id";i:1467;}i:2;O:8:"stdClass":6:{s:2:"id";i:5;s:6:"credit";s:1:"4";s:5:"debit";i:0;s:14:"transaction_id";i:1281;s:12:"warehouse_id";i:1;s:10:"product_id";i:1468;}i:3;O:8:"stdClass":6:{s:2:"id";i:6;s:6:"credit";i:0;s:5:"debit";s:1:"4";s:14:"transaction_id";i:1281;s:12:"warehouse_id";i:2;s:10:"product_id";i:1468;}}}'),
(26, '2014-12-01 10:55:28', 1, 'cat_pur_del_add', 'added Purchase Delivery 2.', 'O:8:"stdClass":6:{s:2:"id";i:2;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:55:28.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:12;s:7:"entries";a:2:{i:0;O:8:"stdClass":4:{s:2:"id";i:2;s:8:"quantity";s:1:"1";s:11:"delivery_id";i:2;s:10:"product_id";i:1467;}i:1;O:8:"stdClass":4:{s:2:"id";i:3;s:8:"quantity";s:1:"4";s:11:"delivery_id";i:2;s:10:"product_id";i:1468;}}}'),
(27, '2014-12-01 11:01:13', 1, 'cat_pur_po_add', 'added Purchase Order 13.', 'O:8:"stdClass":8:{s:2:"id";i:13;s:4:"code";s:14:"POS-2014-00013";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 11:01:13.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:1;s:11:"total_price";s:4:"6.00";s:9:"status_id";s:5:"draft";s:7:"entries";a:1:{i:0;O:8:"stdClass":5:{s:2:"id";i:5;s:8:"quantity";s:1:"2";s:5:"price";s:4:"3.00";s:5:"po_id";i:13;s:10:"product_id";i:1467;}}}'),
(28, '2014-12-01 11:01:22', 1, 'cat_pur_po_status_update', 'status updateed for Purchase Order 13.', 'O:8:"stdClass":8:{s:2:"id";i:13;s:4:"code";s:14:"POS-2014-00013";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 11:01:13.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:1;s:11:"total_price";s:4:"6.00";s:9:"status_id";s:4:"sent";s:7:"entries";a:1:{i:0;O:8:"stdClass":5:{s:2:"id";i:5;s:8:"quantity";s:4:"2.00";s:5:"price";s:4:"3.00";s:5:"po_id";i:13;s:10:"product_id";i:1467;}}}'),
(29, '2014-12-01 11:01:34', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1282.', 'O:8:"stdClass":5:{s:2:"id";i:1282;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 11:01:34.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:32:"Delivery  for PO POS-2014-00013.";s:4:"user";i:1;s:7:"entries";a:2:{i:0;O:8:"stdClass":6:{s:2:"id";i:7;s:6:"credit";s:1:"2";s:5:"debit";i:0;s:14:"transaction_id";i:1282;s:12:"warehouse_id";i:1;s:10:"product_id";i:1467;}i:1;O:8:"stdClass":6:{s:2:"id";i:8;s:6:"credit";i:0;s:5:"debit";s:1:"2";s:14:"transaction_id";i:1282;s:12:"warehouse_id";i:2;s:10:"product_id";i:1467;}}}'),
(30, '2014-12-01 11:01:34', 1, 'cat_pur_del_add', 'added Purchase Delivery 3.', 'O:8:"stdClass":6:{s:2:"id";i:3;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 11:01:34.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:13;s:7:"entries";a:1:{i:0;O:8:"stdClass":4:{s:2:"id";i:4;s:8:"quantity";s:1:"2";s:11:"delivery_id";i:3;s:10:"product_id";i:1467;}}}'),
(31, '2014-12-02 09:24:36', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:156:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:20:"cat_user_branch.menu";b:1;s:20:"cat_user_branch.view";b:1;s:19:"cat_user_branch.add";b:1;s:20:"cat_user_branch.edit";b:1;s:22:"cat_user_branch.delete";b:1;s:12:"cat_inv.menu";b:1;s:15:"cat_inv_wh.view";b:1;s:14:"cat_inv_wh.add";b:1;s:15:"cat_inv_wh.edit";b:1;s:17:"cat_inv_wh.delete";b:1;s:18:"serenitea_inv.menu";b:1;s:18:"serenitea_inv.view";b:1;s:17:"serenitea_inv.add";b:1;s:18:"serenitea_inv.edit";b:1;s:20:"serenitea_inv.delete";b:1;s:21:"serenitea_inv_br.menu";b:1;s:21:"serenitea_inv_br.view";b:1;s:20:"serenitea_inv_br.add";b:1;s:21:"serenitea_inv_br.edit";b:1;s:23:"serenitea_inv_br.delete";b:1;s:15:"cat_inv_pg.menu";b:1;s:15:"cat_inv_pg.view";b:1;s:14:"cat_inv_pg.add";b:1;s:15:"cat_inv_pg.edit";b:1;s:17:"cat_inv_pg.delete";b:1;s:17:"cat_inv_prod.menu";b:1;s:17:"cat_inv_prod.view";b:1;s:16:"cat_inv_prod.add";b:1;s:17:"cat_inv_prod.edit";b:1;s:19:"cat_inv_prod.delete";b:1;s:28:"cat_inv_prod.view_sell_price";b:1;s:28:"cat_inv_prod.view_cost_price";b:1;s:23:"cat_inv_prod.restricted";b:1;s:18:"cat_inv_trans.menu";b:1;s:16:"cat_mfg_tpl.menu";b:1;s:16:"cat_mfg_tpl.view";b:1;s:15:"cat_mfg_tpl.add";b:1;s:16:"cat_mfg_tpl.edit";b:1;s:18:"cat_mfg_tpl.delete";b:1;s:20:"cat_mfg_tpl.generate";b:1;s:16:"cat_mfg_bom.menu";b:1;s:16:"cat_mfg_bom.view";b:1;s:15:"cat_mfg_bom.add";b:1;s:16:"cat_mfg_bom.edit";b:1;s:18:"cat_mfg_bom.delete";b:1;s:15:"cat_mfg_wo.menu";b:1;s:12:"cat_pur.menu";b:1;s:17:"cat_pur_supp.menu";b:1;s:17:"cat_pur_supp.view";b:1;s:16:"cat_pur_supp.add";b:1;s:17:"cat_pur_supp.edit";b:1;s:19:"cat_pur_supp.delete";b:1;s:15:"cat_pur_po.menu";b:1;s:15:"cat_pur_po.view";b:1;s:14:"cat_pur_po.add";b:1;s:15:"cat_pur_po.edit";b:1;s:18:"cat_pur_po.approve";b:1;s:15:"cat_pur_po.send";b:1;s:18:"cat_pur_po.fulfill";b:1;s:17:"cat_pur_po.cancel";b:1;s:19:"cat_sales_cust.menu";b:1;s:19:"cat_sales_cust.view";b:1;s:18:"cat_sales_cust.add";b:1;s:19:"cat_sales_cust.edit";b:1;s:21:"cat_sales_cust.delete";b:1;s:20:"cat_sales_quote.view";b:1;s:19:"cat_sales_quote.add";b:1;s:20:"cat_sales_quote.edit";b:1;s:22:"cat_sales_quote.delete";b:1;s:17:"cat_sales_so.menu";b:1;s:17:"cat_sales_so.view";b:1;s:16:"cat_sales_so.add";b:1;s:17:"cat_sales_so.edit";b:1;s:19:"cat_sales_so.delete";b:1;s:17:"cat_sales_pm.menu";b:1;s:17:"cat_sales_pm.view";b:1;s:16:"cat_sales_pm.add";b:1;s:17:"cat_sales_pm.edit";b:1;s:19:"cat_sales_pm.delete";b:1;s:19:"cat_service_so.menu";b:1;s:19:"cat_service_so.view";b:1;s:18:"cat_service_so.add";b:1;s:19:"cat_service_so.edit";b:1;s:21:"cat_service_so.delete";b:1;s:15:"cat_report.menu";b:1;s:18:"cat_report_dr.menu";b:1;s:18:"cat_report_dr.view";b:1;s:27:"cat_report_consumables.menu";b:1;s:27:"cat_report_consumables.view";b:1;s:23:"cat_report_damaged.menu";b:1;s:23:"cat_report_damaged.view";b:1;s:24:"cat_report_expiring.menu";b:1;s:24:"cat_report_expiring.view";b:1;s:21:"cat_report_stock.menu";b:1;s:21:"cat_report_stock.view";b:1;s:25:"cat_report_prcadjust.menu";b:1;s:25:"cat_report_prcadjust.view";b:1;s:23:"cat_report_compare.menu";b:1;s:23:"cat_report_compare.view";b:1;s:14:"ser_order.menu";b:1;s:16:"ser_request.menu";b:1;s:16:"ser_request.view";b:1;s:15:"ser_request.add";b:1;s:16:"ser_request.edit";b:1;s:18:"ser_request.delete";b:1;s:16:"ser_receive.menu";b:1;s:16:"ser_receive.view";b:1;s:15:"ser_receive.add";b:1;s:16:"ser_receive.edit";b:1;s:19:"ser_receive.approve";b:1;s:16:"ser_receive.send";b:1;s:19:"ser_receive.fulfill";b:1;s:18:"ser_receive.cancel";b:1;s:18:"ser_receive.delete";b:1;s:20:"serenitea_rlist.menu";b:1;s:20:"serenitea_rlist.view";b:1;s:19:"serenitea_rlist.add";b:1;s:20:"serenitea_rlist.edit";b:1;s:22:"serenitea_rlist.delete";b:1;s:20:"serenitea_dlist.menu";b:1;s:20:"serenitea_dlist.view";b:1;s:19:"serenitea_dlist.add";b:1;s:20:"serenitea_dlist.edit";b:1;s:23:"serenitea_dlist.approve";b:1;s:20:"serenitea_dlist.send";b:1;s:23:"serenitea_dlist.fulfill";b:1;s:22:"serenitea_dlist.cancel";b:1;s:20:"serenitea_forms.menu";b:1;s:22:"serenitea_pullout.menu";b:1;s:22:"serenitea_pullout.view";b:1;s:21:"serenitea_pullout.add";b:1;s:22:"serenitea_pullout.edit";b:1;s:24:"serenitea_pullout.delete";b:1;s:21:"serenitea_repair.menu";b:1;s:21:"serenitea_repair.view";b:1;s:20:"serenitea_repair.add";b:1;s:21:"serenitea_repair.edit";b:1;s:24:"serenitea_repair.approve";b:1;s:21:"serenitea_repair.send";b:1;s:24:"serenitea_repair.fulfill";b:1;s:23:"serenitea_repair.cancel";b:1;}}'),
(32, '2014-12-02 09:25:24', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:156:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:20:"cat_user_branch.menu";b:1;s:20:"cat_user_branch.view";b:1;s:19:"cat_user_branch.add";b:1;s:20:"cat_user_branch.edit";b:1;s:22:"cat_user_branch.delete";b:1;s:12:"cat_inv.menu";b:1;s:15:"cat_inv_wh.view";b:1;s:14:"cat_inv_wh.add";b:1;s:15:"cat_inv_wh.edit";b:1;s:17:"cat_inv_wh.delete";b:1;s:18:"serenitea_inv.menu";b:1;s:18:"serenitea_inv.view";b:1;s:17:"serenitea_inv.add";b:1;s:18:"serenitea_inv.edit";b:1;s:20:"serenitea_inv.delete";b:1;s:21:"serenitea_inv_br.menu";b:1;s:21:"serenitea_inv_br.view";b:1;s:20:"serenitea_inv_br.add";b:1;s:21:"serenitea_inv_br.edit";b:1;s:23:"serenitea_inv_br.delete";b:1;s:15:"cat_inv_pg.menu";b:1;s:15:"cat_inv_pg.view";b:1;s:14:"cat_inv_pg.add";b:1;s:15:"cat_inv_pg.edit";b:1;s:17:"cat_inv_pg.delete";b:1;s:17:"cat_inv_prod.menu";b:1;s:17:"cat_inv_prod.view";b:1;s:16:"cat_inv_prod.add";b:1;s:17:"cat_inv_prod.edit";b:1;s:19:"cat_inv_prod.delete";b:1;s:28:"cat_inv_prod.view_sell_price";b:1;s:28:"cat_inv_prod.view_cost_price";b:1;s:23:"cat_inv_prod.restricted";b:1;s:18:"cat_inv_trans.menu";b:1;s:16:"cat_mfg_tpl.menu";b:1;s:16:"cat_mfg_tpl.view";b:1;s:15:"cat_mfg_tpl.add";b:1;s:16:"cat_mfg_tpl.edit";b:1;s:18:"cat_mfg_tpl.delete";b:1;s:20:"cat_mfg_tpl.generate";b:1;s:16:"cat_mfg_bom.menu";b:1;s:16:"cat_mfg_bom.view";b:1;s:15:"cat_mfg_bom.add";b:1;s:16:"cat_mfg_bom.edit";b:1;s:18:"cat_mfg_bom.delete";b:1;s:15:"cat_mfg_wo.menu";b:1;s:12:"cat_pur.menu";b:1;s:17:"cat_pur_supp.menu";b:1;s:17:"cat_pur_supp.view";b:1;s:16:"cat_pur_supp.add";b:1;s:17:"cat_pur_supp.edit";b:1;s:19:"cat_pur_supp.delete";b:1;s:15:"cat_pur_po.menu";b:1;s:15:"cat_pur_po.view";b:1;s:14:"cat_pur_po.add";b:1;s:15:"cat_pur_po.edit";b:1;s:18:"cat_pur_po.approve";b:1;s:15:"cat_pur_po.send";b:1;s:18:"cat_pur_po.fulfill";b:1;s:17:"cat_pur_po.cancel";b:1;s:19:"cat_sales_cust.menu";b:1;s:19:"cat_sales_cust.view";b:1;s:18:"cat_sales_cust.add";b:1;s:19:"cat_sales_cust.edit";b:1;s:21:"cat_sales_cust.delete";b:1;s:20:"cat_sales_quote.view";b:1;s:19:"cat_sales_quote.add";b:1;s:20:"cat_sales_quote.edit";b:1;s:22:"cat_sales_quote.delete";b:1;s:17:"cat_sales_so.menu";b:1;s:17:"cat_sales_so.view";b:1;s:16:"cat_sales_so.add";b:1;s:17:"cat_sales_so.edit";b:1;s:19:"cat_sales_so.delete";b:1;s:17:"cat_sales_pm.menu";b:1;s:17:"cat_sales_pm.view";b:1;s:16:"cat_sales_pm.add";b:1;s:17:"cat_sales_pm.edit";b:1;s:19:"cat_sales_pm.delete";b:1;s:19:"cat_service_so.menu";b:1;s:19:"cat_service_so.view";b:1;s:18:"cat_service_so.add";b:1;s:19:"cat_service_so.edit";b:1;s:21:"cat_service_so.delete";b:1;s:15:"cat_report.menu";b:1;s:18:"cat_report_po.menu";b:1;s:18:"cat_report_po.view";b:1;s:18:"cat_report_dr.menu";b:1;s:18:"cat_report_dr.view";b:1;s:23:"cat_report_damaged.menu";b:1;s:23:"cat_report_damaged.view";b:1;s:24:"cat_report_expiring.menu";b:1;s:24:"cat_report_expiring.view";b:1;s:21:"cat_report_stock.menu";b:1;s:21:"cat_report_stock.view";b:1;s:25:"cat_report_prcadjust.menu";b:1;s:25:"cat_report_prcadjust.view";b:1;s:23:"cat_report_compare.menu";b:1;s:23:"cat_report_compare.view";b:1;s:14:"ser_order.menu";b:1;s:16:"ser_request.menu";b:1;s:16:"ser_request.view";b:1;s:15:"ser_request.add";b:1;s:16:"ser_request.edit";b:1;s:18:"ser_request.delete";b:1;s:16:"ser_receive.menu";b:1;s:16:"ser_receive.view";b:1;s:15:"ser_receive.add";b:1;s:16:"ser_receive.edit";b:1;s:19:"ser_receive.approve";b:1;s:16:"ser_receive.send";b:1;s:19:"ser_receive.fulfill";b:1;s:18:"ser_receive.cancel";b:1;s:18:"ser_receive.delete";b:1;s:20:"serenitea_rlist.menu";b:1;s:20:"serenitea_rlist.view";b:1;s:19:"serenitea_rlist.add";b:1;s:20:"serenitea_rlist.edit";b:1;s:22:"serenitea_rlist.delete";b:1;s:20:"serenitea_dlist.menu";b:1;s:20:"serenitea_dlist.view";b:1;s:19:"serenitea_dlist.add";b:1;s:20:"serenitea_dlist.edit";b:1;s:23:"serenitea_dlist.approve";b:1;s:20:"serenitea_dlist.send";b:1;s:23:"serenitea_dlist.fulfill";b:1;s:22:"serenitea_dlist.cancel";b:1;s:20:"serenitea_forms.menu";b:1;s:22:"serenitea_pullout.menu";b:1;s:22:"serenitea_pullout.view";b:1;s:21:"serenitea_pullout.add";b:1;s:22:"serenitea_pullout.edit";b:1;s:24:"serenitea_pullout.delete";b:1;s:21:"serenitea_repair.menu";b:1;s:21:"serenitea_repair.view";b:1;s:20:"serenitea_repair.add";b:1;s:21:"serenitea_repair.edit";b:1;s:24:"serenitea_repair.approve";b:1;s:21:"serenitea_repair.send";b:1;s:24:"serenitea_repair.fulfill";b:1;s:23:"serenitea_repair.cancel";b:1;}}'),
(33, '2014-12-02 09:47:41', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:156:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:20:"cat_user_branch.menu";b:1;s:20:"cat_user_branch.view";b:1;s:19:"cat_user_branch.add";b:1;s:20:"cat_user_branch.edit";b:1;s:22:"cat_user_branch.delete";b:1;s:12:"cat_inv.menu";b:1;s:15:"cat_inv_wh.view";b:1;s:14:"cat_inv_wh.add";b:1;s:15:"cat_inv_wh.edit";b:1;s:17:"cat_inv_wh.delete";b:1;s:18:"serenitea_inv.menu";b:1;s:18:"serenitea_inv.view";b:1;s:17:"serenitea_inv.add";b:1;s:18:"serenitea_inv.edit";b:1;s:20:"serenitea_inv.delete";b:1;s:21:"serenitea_inv_br.menu";b:1;s:21:"serenitea_inv_br.view";b:1;s:20:"serenitea_inv_br.add";b:1;s:21:"serenitea_inv_br.edit";b:1;s:23:"serenitea_inv_br.delete";b:1;s:15:"cat_inv_pg.menu";b:1;s:15:"cat_inv_pg.view";b:1;s:14:"cat_inv_pg.add";b:1;s:15:"cat_inv_pg.edit";b:1;s:17:"cat_inv_pg.delete";b:1;s:17:"cat_inv_prod.menu";b:1;s:17:"cat_inv_prod.view";b:1;s:16:"cat_inv_prod.add";b:1;s:17:"cat_inv_prod.edit";b:1;s:19:"cat_inv_prod.delete";b:1;s:28:"cat_inv_prod.view_sell_price";b:1;s:28:"cat_inv_prod.view_cost_price";b:1;s:23:"cat_inv_prod.restricted";b:1;s:18:"cat_inv_trans.menu";b:1;s:16:"cat_mfg_tpl.menu";b:1;s:16:"cat_mfg_tpl.view";b:1;s:15:"cat_mfg_tpl.add";b:1;s:16:"cat_mfg_tpl.edit";b:1;s:18:"cat_mfg_tpl.delete";b:1;s:20:"cat_mfg_tpl.generate";b:1;s:16:"cat_mfg_bom.menu";b:1;s:16:"cat_mfg_bom.view";b:1;s:15:"cat_mfg_bom.add";b:1;s:16:"cat_mfg_bom.edit";b:1;s:18:"cat_mfg_bom.delete";b:1;s:15:"cat_mfg_wo.menu";b:1;s:12:"cat_pur.menu";b:1;s:17:"cat_pur_supp.menu";b:1;s:17:"cat_pur_supp.view";b:1;s:16:"cat_pur_supp.add";b:1;s:17:"cat_pur_supp.edit";b:1;s:19:"cat_pur_supp.delete";b:1;s:15:"cat_pur_po.menu";b:1;s:15:"cat_pur_po.view";b:1;s:14:"cat_pur_po.add";b:1;s:15:"cat_pur_po.edit";b:1;s:18:"cat_pur_po.approve";b:1;s:15:"cat_pur_po.send";b:1;s:18:"cat_pur_po.fulfill";b:1;s:17:"cat_pur_po.cancel";b:1;s:19:"cat_sales_cust.menu";b:1;s:19:"cat_sales_cust.view";b:1;s:18:"cat_sales_cust.add";b:1;s:19:"cat_sales_cust.edit";b:1;s:21:"cat_sales_cust.delete";b:1;s:20:"cat_sales_quote.view";b:1;s:19:"cat_sales_quote.add";b:1;s:20:"cat_sales_quote.edit";b:1;s:22:"cat_sales_quote.delete";b:1;s:17:"cat_sales_so.menu";b:1;s:17:"cat_sales_so.view";b:1;s:16:"cat_sales_so.add";b:1;s:17:"cat_sales_so.edit";b:1;s:19:"cat_sales_so.delete";b:1;s:17:"cat_sales_pm.menu";b:1;s:17:"cat_sales_pm.view";b:1;s:16:"cat_sales_pm.add";b:1;s:17:"cat_sales_pm.edit";b:1;s:19:"cat_sales_pm.delete";b:1;s:19:"cat_service_so.menu";b:1;s:19:"cat_service_so.view";b:1;s:18:"cat_service_so.add";b:1;s:19:"cat_service_so.edit";b:1;s:21:"cat_service_so.delete";b:1;s:15:"cat_report.menu";b:1;s:18:"cat_report_po.menu";b:1;s:18:"cat_report_po.view";b:1;s:18:"cat_report_dr.menu";b:1;s:18:"cat_report_dr.view";b:1;s:23:"cat_report_damaged.menu";b:1;s:23:"cat_report_damaged.view";b:1;s:24:"cat_report_returned.menu";b:1;s:24:"cat_report_returned.view";b:1;s:21:"cat_report_stock.menu";b:1;s:21:"cat_report_stock.view";b:1;s:25:"cat_report_prcadjust.menu";b:1;s:25:"cat_report_prcadjust.view";b:1;s:23:"cat_report_compare.menu";b:1;s:23:"cat_report_compare.view";b:1;s:14:"ser_order.menu";b:1;s:16:"ser_request.menu";b:1;s:16:"ser_request.view";b:1;s:15:"ser_request.add";b:1;s:16:"ser_request.edit";b:1;s:18:"ser_request.delete";b:1;s:16:"ser_receive.menu";b:1;s:16:"ser_receive.view";b:1;s:15:"ser_receive.add";b:1;s:16:"ser_receive.edit";b:1;s:19:"ser_receive.approve";b:1;s:16:"ser_receive.send";b:1;s:19:"ser_receive.fulfill";b:1;s:18:"ser_receive.cancel";b:1;s:18:"ser_receive.delete";b:1;s:20:"serenitea_rlist.menu";b:1;s:20:"serenitea_rlist.view";b:1;s:19:"serenitea_rlist.add";b:1;s:20:"serenitea_rlist.edit";b:1;s:22:"serenitea_rlist.delete";b:1;s:20:"serenitea_dlist.menu";b:1;s:20:"serenitea_dlist.view";b:1;s:19:"serenitea_dlist.add";b:1;s:20:"serenitea_dlist.edit";b:1;s:23:"serenitea_dlist.approve";b:1;s:20:"serenitea_dlist.send";b:1;s:23:"serenitea_dlist.fulfill";b:1;s:22:"serenitea_dlist.cancel";b:1;s:20:"serenitea_forms.menu";b:1;s:22:"serenitea_pullout.menu";b:1;s:22:"serenitea_pullout.view";b:1;s:21:"serenitea_pullout.add";b:1;s:22:"serenitea_pullout.edit";b:1;s:24:"serenitea_pullout.delete";b:1;s:21:"serenitea_repair.menu";b:1;s:21:"serenitea_repair.view";b:1;s:20:"serenitea_repair.add";b:1;s:21:"serenitea_repair.edit";b:1;s:24:"serenitea_repair.approve";b:1;s:21:"serenitea_repair.send";b:1;s:24:"serenitea_repair.fulfill";b:1;s:23:"serenitea_repair.cancel";b:1;}}'),
(34, '2014-12-02 11:15:51', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:158:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:20:"cat_user_branch.menu";b:1;s:20:"cat_user_branch.view";b:1;s:19:"cat_user_branch.add";b:1;s:20:"cat_user_branch.edit";b:1;s:22:"cat_user_branch.delete";b:1;s:12:"cat_inv.menu";b:1;s:15:"cat_inv_wh.view";b:1;s:14:"cat_inv_wh.add";b:1;s:15:"cat_inv_wh.edit";b:1;s:17:"cat_inv_wh.delete";b:1;s:18:"serenitea_inv.menu";b:1;s:18:"serenitea_inv.view";b:1;s:17:"serenitea_inv.add";b:1;s:18:"serenitea_inv.edit";b:1;s:20:"serenitea_inv.delete";b:1;s:21:"serenitea_inv_br.menu";b:1;s:21:"serenitea_inv_br.view";b:1;s:20:"serenitea_inv_br.add";b:1;s:21:"serenitea_inv_br.edit";b:1;s:23:"serenitea_inv_br.delete";b:1;s:15:"cat_inv_pg.menu";b:1;s:15:"cat_inv_pg.view";b:1;s:14:"cat_inv_pg.add";b:1;s:15:"cat_inv_pg.edit";b:1;s:17:"cat_inv_pg.delete";b:1;s:17:"cat_inv_prod.menu";b:1;s:17:"cat_inv_prod.view";b:1;s:16:"cat_inv_prod.add";b:1;s:17:"cat_inv_prod.edit";b:1;s:19:"cat_inv_prod.delete";b:1;s:28:"cat_inv_prod.view_sell_price";b:1;s:28:"cat_inv_prod.view_cost_price";b:1;s:23:"cat_inv_prod.restricted";b:1;s:18:"cat_inv_trans.menu";b:1;s:16:"cat_mfg_tpl.menu";b:1;s:16:"cat_mfg_tpl.view";b:1;s:15:"cat_mfg_tpl.add";b:1;s:16:"cat_mfg_tpl.edit";b:1;s:18:"cat_mfg_tpl.delete";b:1;s:20:"cat_mfg_tpl.generate";b:1;s:16:"cat_mfg_bom.menu";b:1;s:16:"cat_mfg_bom.view";b:1;s:15:"cat_mfg_bom.add";b:1;s:16:"cat_mfg_bom.edit";b:1;s:18:"cat_mfg_bom.delete";b:1;s:15:"cat_mfg_wo.menu";b:1;s:12:"cat_pur.menu";b:1;s:17:"cat_pur_supp.menu";b:1;s:17:"cat_pur_supp.view";b:1;s:16:"cat_pur_supp.add";b:1;s:17:"cat_pur_supp.edit";b:1;s:19:"cat_pur_supp.delete";b:1;s:15:"cat_pur_po.menu";b:1;s:15:"cat_pur_po.view";b:1;s:14:"cat_pur_po.add";b:1;s:15:"cat_pur_po.edit";b:1;s:18:"cat_pur_po.approve";b:1;s:15:"cat_pur_po.send";b:1;s:18:"cat_pur_po.fulfill";b:1;s:17:"cat_pur_po.cancel";b:1;s:19:"cat_sales_cust.menu";b:1;s:19:"cat_sales_cust.view";b:1;s:18:"cat_sales_cust.add";b:1;s:19:"cat_sales_cust.edit";b:1;s:21:"cat_sales_cust.delete";b:1;s:20:"cat_sales_quote.view";b:1;s:19:"cat_sales_quote.add";b:1;s:20:"cat_sales_quote.edit";b:1;s:22:"cat_sales_quote.delete";b:1;s:17:"cat_sales_so.menu";b:1;s:17:"cat_sales_so.view";b:1;s:16:"cat_sales_so.add";b:1;s:17:"cat_sales_so.edit";b:1;s:19:"cat_sales_so.delete";b:1;s:17:"cat_sales_pm.menu";b:1;s:17:"cat_sales_pm.view";b:1;s:16:"cat_sales_pm.add";b:1;s:17:"cat_sales_pm.edit";b:1;s:19:"cat_sales_pm.delete";b:1;s:19:"cat_service_so.menu";b:1;s:19:"cat_service_so.view";b:1;s:18:"cat_service_so.add";b:1;s:19:"cat_service_so.edit";b:1;s:21:"cat_service_so.delete";b:1;s:15:"cat_report.menu";b:1;s:18:"cat_report_po.menu";b:1;s:18:"cat_report_po.view";b:1;s:18:"cat_report_dr.menu";b:1;s:18:"cat_report_dr.view";b:1;s:23:"cat_report_damaged.menu";b:1;s:23:"cat_report_damaged.view";b:1;s:24:"cat_report_returned.menu";b:1;s:24:"cat_report_returned.view";b:1;s:21:"cat_report_stock.menu";b:1;s:21:"cat_report_stock.view";b:1;s:23:"cat_report_balance.menu";b:1;s:23:"cat_report_balance.view";b:1;s:25:"cat_report_prcadjust.menu";b:1;s:25:"cat_report_prcadjust.view";b:1;s:23:"cat_report_compare.menu";b:1;s:23:"cat_report_compare.view";b:1;s:14:"ser_order.menu";b:1;s:16:"ser_request.menu";b:1;s:16:"ser_request.view";b:1;s:15:"ser_request.add";b:1;s:16:"ser_request.edit";b:1;s:18:"ser_request.delete";b:1;s:16:"ser_receive.menu";b:1;s:16:"ser_receive.view";b:1;s:15:"ser_receive.add";b:1;s:16:"ser_receive.edit";b:1;s:19:"ser_receive.approve";b:1;s:16:"ser_receive.send";b:1;s:19:"ser_receive.fulfill";b:1;s:18:"ser_receive.cancel";b:1;s:18:"ser_receive.delete";b:1;s:20:"serenitea_rlist.menu";b:1;s:20:"serenitea_rlist.view";b:1;s:19:"serenitea_rlist.add";b:1;s:20:"serenitea_rlist.edit";b:1;s:22:"serenitea_rlist.delete";b:1;s:20:"serenitea_dlist.menu";b:1;s:20:"serenitea_dlist.view";b:1;s:19:"serenitea_dlist.add";b:1;s:20:"serenitea_dlist.edit";b:1;s:23:"serenitea_dlist.approve";b:1;s:20:"serenitea_dlist.send";b:1;s:23:"serenitea_dlist.fulfill";b:1;s:22:"serenitea_dlist.cancel";b:1;s:20:"serenitea_forms.menu";b:1;s:22:"serenitea_pullout.menu";b:1;s:22:"serenitea_pullout.view";b:1;s:21:"serenitea_pullout.add";b:1;s:22:"serenitea_pullout.edit";b:1;s:24:"serenitea_pullout.delete";b:1;s:21:"serenitea_repair.menu";b:1;s:21:"serenitea_repair.view";b:1;s:20:"serenitea_repair.add";b:1;s:21:"serenitea_repair.edit";b:1;s:24:"serenitea_repair.approve";b:1;s:21:"serenitea_repair.send";b:1;s:24:"serenitea_repair.fulfill";b:1;s:23:"serenitea_repair.cancel";b:1;}}'),
(35, '2014-12-03 04:34:26', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1284.', 'O:8:"stdClass":5:{s:2:"id";i:1284;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 04:34:26.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:29:"Delivery  for POS-2014-00011.";s:4:"user";i:1;s:7:"entries";a:2:{i:0;O:8:"stdClass":7:{s:2:"id";i:9;s:6:"credit";s:1:"3";s:5:"debit";i:0;s:14:"transaction_id";i:1284;s:12:"warehouse_id";i:1;s:10:"product_id";i:1468;s:5:"batch";N;}i:1;O:8:"stdClass":7:{s:2:"id";i:10;s:6:"credit";i:0;s:5:"debit";s:1:"3";s:14:"transaction_id";i:1284;s:12:"warehouse_id";i:2;s:10:"product_id";i:1468;s:5:"batch";N;}}}'),
(36, '2014-12-03 04:34:26', 1, 'cat_pur_del_add', 'added Purchase Delivery 5.', 'O:8:"stdClass":6:{s:2:"id";i:5;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 04:34:26.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:11;s:7:"entries";a:1:{i:0;O:8:"stdClass":4:{s:2:"id";i:6;s:8:"quantity";s:1:"3";s:11:"delivery_id";i:5;s:10:"product_id";i:1468;}}}'),
(37, '2014-12-03 04:35:27', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1285.', 'O:8:"stdClass":5:{s:2:"id";i:1285;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 04:35:27.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:29:"Delivery  for POS-2014-00011.";s:4:"user";i:1;s:7:"entries";a:2:{i:0;O:8:"stdClass":7:{s:2:"id";i:11;s:6:"credit";s:1:"3";s:5:"debit";i:0;s:14:"transaction_id";i:1285;s:12:"warehouse_id";i:1;s:10:"product_id";i:1468;s:5:"batch";N;}i:1;O:8:"stdClass":7:{s:2:"id";i:12;s:6:"credit";i:0;s:5:"debit";s:1:"3";s:14:"transaction_id";i:1285;s:12:"warehouse_id";i:2;s:10:"product_id";i:1468;s:5:"batch";N;}}}'),
(38, '2014-12-03 04:35:27', 1, 'cat_pur_del_add', 'added Purchase Delivery 6.', 'O:8:"stdClass":6:{s:2:"id";i:6;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 04:35:27.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:11;s:7:"entries";a:1:{i:0;O:8:"stdClass":4:{s:2:"id";i:7;s:8:"quantity";s:1:"3";s:11:"delivery_id";i:6;s:10:"product_id";i:1468;}}}'),
(39, '2014-12-03 08:09:17', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1290.', 'O:8:"stdClass":5:{s:2:"id";i:1290;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:09:17.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:29:"Delivery  for POS-2014-00012.";s:4:"user";i:1;s:7:"entries";a:4:{i:0;O:8:"stdClass":6:{s:2:"id";i:13;s:6:"credit";s:1:"2";s:5:"debit";i:0;s:14:"transaction_id";i:1290;s:12:"warehouse_id";i:1;s:10:"product_id";i:1467;}i:1;O:8:"stdClass":6:{s:2:"id";i:14;s:6:"credit";i:0;s:5:"debit";s:1:"2";s:14:"transaction_id";i:1290;s:12:"warehouse_id";i:2;s:10:"product_id";i:1467;}i:2;O:8:"stdClass":6:{s:2:"id";i:15;s:6:"credit";s:1:"4";s:5:"debit";i:0;s:14:"transaction_id";i:1290;s:12:"warehouse_id";i:1;s:10:"product_id";i:1468;}i:3;O:8:"stdClass":6:{s:2:"id";i:16;s:6:"credit";i:0;s:5:"debit";s:1:"4";s:14:"transaction_id";i:1290;s:12:"warehouse_id";i:2;s:10:"product_id";i:1468;}}}'),
(40, '2014-12-03 08:09:17', 1, 'cat_pur_del_add', 'added Purchase Delivery 11.', 'O:8:"stdClass":6:{s:2:"id";i:11;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:09:17.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:12;s:7:"entries";a:2:{i:0;O:8:"stdClass":4:{s:2:"id";i:16;s:8:"quantity";s:1:"2";s:11:"delivery_id";i:11;s:10:"product_id";i:1467;}i:1;O:8:"stdClass":4:{s:2:"id";i:17;s:8:"quantity";s:1:"4";s:11:"delivery_id";i:11;s:10:"product_id";i:1468;}}}'),
(41, '2014-12-03 08:13:07', 1, 'cat_pur_po_update', 'updated Purchase Order 11.', 'O:8:"stdClass":8:{s:2:"id";i:11;s:4:"code";s:14:"POS-2014-00011";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:51:05.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:1;s:11:"total_price";s:7:"2000.00";s:9:"status_id";s:4:"sent";s:7:"entries";a:1:{i:0;O:8:"stdClass":6:{s:2:"id";i:1;s:8:"quantity";s:2:"10";s:5:"price";s:3:"200";s:5:"po_id";i:11;s:10:"product_id";i:1467;s:11:"expiry_date";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:13:07.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}}}}'),
(42, '2014-12-03 08:19:30', 1, 'cat_pur_po_update', 'updated Purchase Order 11.', 'O:8:"stdClass":8:{s:2:"id";i:11;s:4:"code";s:14:"POS-2014-00011";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:51:05.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:1;s:11:"total_price";s:7:"9500.00";s:9:"status_id";s:4:"sent";s:7:"entries";a:2:{i:0;O:8:"stdClass":6:{s:2:"id";i:2;s:8:"quantity";s:5:"10.00";s:5:"price";s:6:"200.00";s:5:"po_id";i:11;s:10:"product_id";i:1467;s:11:"expiry_date";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:19:30.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}}i:1;O:8:"stdClass":6:{s:2:"id";i:3;s:8:"quantity";s:2:"15";s:5:"price";s:3:"500";s:5:"po_id";i:11;s:10:"product_id";i:1467;s:11:"expiry_date";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:19:30.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}}}}'),
(43, '2014-12-03 08:36:14', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1291.', 'O:8:"stdClass":5:{s:2:"id";i:1291;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:36:14.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:29:"Delivery  for POS-2014-00011.";s:4:"user";i:1;s:7:"entries";a:2:{i:0;O:8:"stdClass":6:{s:2:"id";i:1;s:6:"credit";s:1:"3";s:5:"debit";i:0;s:14:"transaction_id";i:1291;s:12:"warehouse_id";i:1;s:10:"product_id";i:1467;}i:1;O:8:"stdClass":6:{s:2:"id";i:2;s:6:"credit";i:0;s:5:"debit";s:1:"3";s:14:"transaction_id";i:1291;s:12:"warehouse_id";i:2;s:10:"product_id";i:1467;}}}'),
(44, '2014-12-03 08:36:14', 1, 'cat_pur_del_add', 'added Purchase Delivery 1.', 'O:8:"stdClass":6:{s:2:"id";i:1;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:36:14.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:11;s:7:"entries";a:1:{i:0;O:8:"stdClass":4:{s:2:"id";i:1;s:8:"quantity";s:1:"3";s:11:"delivery_id";i:1;s:10:"product_id";i:1467;}}}'),
(45, '2014-12-03 08:39:11', 1, 'cat_pur_po_update', 'updated Purchase Order 12.', 'O:8:"stdClass":8:{s:2:"id";i:12;s:4:"code";s:14:"POS-2014-00012";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:54:47.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:2;s:11:"total_price";s:5:"69.00";s:9:"status_id";s:4:"sent";s:7:"entries";a:1:{i:0;O:8:"stdClass":5:{s:2:"id";i:4;s:8:"quantity";s:1:"3";s:5:"price";s:2:"23";s:5:"po_id";i:12;s:10:"product_id";i:1468;}}}'),
(46, '2014-12-03 08:39:19', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1292.', 'O:8:"stdClass":5:{s:2:"id";i:1292;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:39:19.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:29:"Delivery  for POS-2014-00012.";s:4:"user";i:1;s:7:"entries";a:2:{i:0;O:8:"stdClass":6:{s:2:"id";i:3;s:6:"credit";s:1:"2";s:5:"debit";i:0;s:14:"transaction_id";i:1292;s:12:"warehouse_id";i:1;s:10:"product_id";i:1468;}i:1;O:8:"stdClass":6:{s:2:"id";i:4;s:6:"credit";i:0;s:5:"debit";s:1:"2";s:14:"transaction_id";i:1292;s:12:"warehouse_id";i:2;s:10:"product_id";i:1468;}}}'),
(47, '2014-12-03 08:39:19', 1, 'cat_pur_del_add', 'added Purchase Delivery 2.', 'O:8:"stdClass":6:{s:2:"id";i:2;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:39:19.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:12;s:7:"entries";a:1:{i:0;O:8:"stdClass":4:{s:2:"id";i:2;s:8:"quantity";s:1:"2";s:11:"delivery_id";i:2;s:10:"product_id";i:1468;}}}'),
(48, '2014-12-03 08:46:55', 1, 'cat_pur_po_update', 'updated Purchase Order 13.', 'O:8:"stdClass":8:{s:2:"id";i:13;s:4:"code";s:14:"POS-2014-00013";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 11:01:13.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:1;s:11:"total_price";s:7:"2330.00";s:9:"status_id";s:4:"sent";s:7:"entries";a:2:{i:0;O:8:"stdClass":5:{s:2:"id";i:5;s:8:"quantity";s:2:"10";s:5:"price";s:3:"200";s:5:"po_id";i:13;s:10:"product_id";i:1468;}i:1;O:8:"stdClass":5:{s:2:"id";i:6;s:8:"quantity";s:1:"3";s:5:"price";s:3:"110";s:5:"po_id";i:13;s:10:"product_id";i:1468;}}}'),
(49, '2014-12-03 08:47:51', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1293.', 'O:8:"stdClass":5:{s:2:"id";i:1293;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:47:51.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:29:"Delivery  for POS-2014-00013.";s:4:"user";i:1;s:7:"entries";a:2:{i:0;O:8:"stdClass":6:{s:2:"id";i:5;s:6:"credit";s:1:"3";s:5:"debit";i:0;s:14:"transaction_id";i:1293;s:12:"warehouse_id";i:1;s:10:"product_id";i:1468;}i:1;O:8:"stdClass":6:{s:2:"id";i:6;s:6:"credit";i:0;s:5:"debit";s:1:"3";s:14:"transaction_id";i:1293;s:12:"warehouse_id";i:2;s:10:"product_id";i:1468;}}}'),
(50, '2014-12-03 08:47:51', 1, 'cat_pur_del_add', 'added Purchase Delivery 3.', 'O:8:"stdClass":6:{s:2:"id";i:3;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:47:51.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-17 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:13;s:7:"entries";a:1:{i:0;O:8:"stdClass":4:{s:2:"id";i:3;s:8:"quantity";s:1:"3";s:11:"delivery_id";i:3;s:10:"product_id";i:1468;}}}'),
(51, '2014-12-03 08:48:52', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1294.', 'O:8:"stdClass":5:{s:2:"id";i:1294;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:48:52.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:29:"Delivery  for POS-2014-00013.";s:4:"user";i:1;s:7:"entries";a:2:{i:0;O:8:"stdClass":6:{s:2:"id";i:7;s:6:"credit";s:1:"1";s:5:"debit";i:0;s:14:"transaction_id";i:1294;s:12:"warehouse_id";i:1;s:10:"product_id";i:1468;}i:1;O:8:"stdClass":6:{s:2:"id";i:8;s:6:"credit";i:0;s:5:"debit";s:1:"1";s:14:"transaction_id";i:1294;s:12:"warehouse_id";i:2;s:10:"product_id";i:1468;}}}'),
(52, '2014-12-03 08:48:53', 1, 'cat_pur_del_add', 'added Purchase Delivery 4.', 'O:8:"stdClass":6:{s:2:"id";i:4;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 08:48:52.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:13;s:7:"entries";a:1:{i:0;O:8:"stdClass":4:{s:2:"id";i:4;s:8:"quantity";s:1:"1";s:11:"delivery_id";i:4;s:10:"product_id";i:1468;}}}'),
(53, '2014-12-03 09:09:34', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1295.', 'O:8:"stdClass":5:{s:2:"id";i:1295;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 09:09:34.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:29:"Delivery  for POS-2014-00013.";s:4:"user";i:1;s:7:"entries";a:2:{i:0;O:8:"stdClass":6:{s:2:"id";i:9;s:6:"credit";s:1:"3";s:5:"debit";i:0;s:14:"transaction_id";i:1295;s:12:"warehouse_id";i:1;s:10:"product_id";i:1468;}i:1;O:8:"stdClass":6:{s:2:"id";i:10;s:6:"credit";i:0;s:5:"debit";s:1:"3";s:14:"transaction_id";i:1295;s:12:"warehouse_id";i:2;s:10:"product_id";i:1468;}}}'),
(54, '2014-12-03 09:09:34', 1, 'cat_pur_del_add', 'added Purchase Delivery 5.', 'O:8:"stdClass":6:{s:2:"id";i:5;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 09:09:34.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:13;s:7:"entries";a:1:{i:0;O:8:"stdClass":4:{s:2:"id";i:5;s:8:"quantity";s:1:"3";s:11:"delivery_id";i:5;s:10:"product_id";i:1468;}}}'),
(55, '2014-12-03 09:11:18', 1, 'cat_pur_po_update', 'updated Purchase Order 12.', 'O:8:"stdClass":8:{s:2:"id";i:12;s:4:"code";s:14:"POS-2014-00012";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 10:54:47.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:10:"date_issue";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-01 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"supplier_id";i:2;s:11:"total_price";s:8:"10069.00";s:9:"status_id";s:4:"sent";s:7:"entries";a:2:{i:0;O:8:"stdClass":5:{s:2:"id";i:7;s:8:"quantity";s:4:"3.00";s:5:"price";s:5:"23.00";s:5:"po_id";i:12;s:10:"product_id";i:1468;}i:1;O:8:"stdClass":5:{s:2:"id";i:8;s:8:"quantity";s:2:"20";s:5:"price";s:3:"500";s:5:"po_id";i:12;s:10:"product_id";i:1467;}}}'),
(56, '2014-12-03 09:11:39', 1, 'cat_inv_trans_add', 'added Inventory Transaction 1296.', 'O:8:"stdClass":5:{s:2:"id";i:1296;s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 09:11:39.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:11:"description";s:29:"Delivery  for POS-2014-00012.";s:4:"user";i:1;s:7:"entries";a:4:{i:0;O:8:"stdClass":6:{s:2:"id";i:11;s:6:"credit";s:1:"2";s:5:"debit";i:0;s:14:"transaction_id";i:1296;s:12:"warehouse_id";i:1;s:10:"product_id";i:1468;}i:1;O:8:"stdClass":6:{s:2:"id";i:12;s:6:"credit";i:0;s:5:"debit";s:1:"2";s:14:"transaction_id";i:1296;s:12:"warehouse_id";i:2;s:10:"product_id";i:1468;}i:2;O:8:"stdClass":6:{s:2:"id";i:13;s:6:"credit";s:2:"15";s:5:"debit";i:0;s:14:"transaction_id";i:1296;s:12:"warehouse_id";i:1;s:10:"product_id";i:1467;}i:3;O:8:"stdClass":6:{s:2:"id";i:14;s:6:"credit";i:0;s:5:"debit";s:2:"15";s:14:"transaction_id";i:1296;s:12:"warehouse_id";i:2;s:10:"product_id";i:1467;}}}');
INSERT INTO `logentry` (`id`, `date_in`, `user_id`, `action_id`, `description`, `data`) VALUES
(57, '2014-12-03 09:11:39', 1, 'cat_pur_del_add', 'added Purchase Delivery 6.', 'O:8:"stdClass":6:{s:2:"id";i:6;s:4:"code";s:0:"";s:7:"date_in";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 09:11:39.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:12:"date_deliver";O:8:"DateTime":3:{s:4:"date";s:26:"2014-12-03 00:00:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Berlin";}s:5:"po_id";i:12;s:7:"entries";a:2:{i:0;O:8:"stdClass":4:{s:2:"id";i:6;s:8:"quantity";s:1:"2";s:11:"delivery_id";i:6;s:10:"product_id";i:1468;}i:1;O:8:"stdClass":4:{s:2:"id";i:7;s:8:"quantity";s:2:"15";s:11:"delivery_id";i:6;s:10:"product_id";i:1467;}}}'),
(58, '2014-12-05 01:52:46', 1, 'cat_inv_pg_add', 'added Product Category 26.', 'O:8:"stdClass":3:{s:2:"id";i:26;s:4:"name";s:5:"Syrup";s:4:"code";s:3:"SYR";}'),
(59, '2014-12-05 01:53:04', 1, 'cat_inv_pg_update', 'updated Product Category 25.', 'O:8:"stdClass":3:{s:2:"id";i:25;s:4:"name";s:6:"Powder";s:4:"code";s:3:"PWD";}'),
(60, '2014-12-05 01:53:17', 1, 'cat_inv_pg_update', 'updated Product Category 24.', 'O:8:"stdClass":3:{s:2:"id";i:24;s:4:"name";s:6:"Sinker";s:4:"code";s:3:"SNK";}'),
(61, '2014-12-05 01:56:25', 1, 'cat_inv_pg_add', 'added Product Category 27.', 'O:8:"stdClass":3:{s:2:"id";i:27;s:4:"name";s:7:"Teabags";s:4:"code";s:3:"TEA";}'),
(62, '2014-12-05 01:56:49', 1, 'cat_inv_pg_add', 'added Product Category 28.', 'O:8:"stdClass":3:{s:2:"id";i:28;s:4:"name";s:8:"Sanitary";s:4:"code";s:3:"SAN";}'),
(63, '2014-12-05 01:58:46', 1, 'cat_user_branch_add', 'added Branch Management 2.', 'O:8:"stdClass":7:{s:2:"id";i:2;s:4:"name";s:13:"Makati - Rada";s:4:"code";s:3:"MKT";s:7:"address";s:11:"Makati City";s:14:"contact_number";s:9:"123-45-67";s:3:"fax";s:9:"123-45-67";s:7:"pmterms";s:4:"Cash";}'),
(64, '2014-12-05 01:59:43', 1, 'cat_user_branch_update', 'updated Branch Management 1.', 'O:8:"stdClass":7:{s:2:"id";i:1;s:4:"name";s:8:"BF Homes";s:4:"code";s:2:"BF";s:7:"address";s:14:"sample address";s:14:"contact_number";s:7:"1234567";s:3:"fax";s:9:"123456789";s:7:"pmterms";s:4:"Cash";}'),
(65, '2014-12-05 02:00:20', 1, 'cat_user_branch_add', 'added Branch Management 3.', 'O:8:"stdClass":7:{s:2:"id";i:3;s:4:"name";s:13:"Katipunan Ave";s:4:"code";s:3:"KAT";s:7:"address";s:9:"Katipunan";s:14:"contact_number";s:9:"123-45-67";s:3:"fax";s:9:"123-45-67";s:7:"pmterms";s:0:"";}'),
(66, '2014-12-05 02:01:10', 1, 'cat_user_branch_add', 'added Branch Management 4.', 'O:8:"stdClass":7:{s:2:"id";i:4;s:4:"name";s:19:"Greenfield District";s:4:"code";s:2:"GD";s:7:"address";s:19:"greenfield district";s:14:"contact_number";s:9:"123-45-67";s:3:"fax";s:9:"123-45-67";s:7:"pmterms";s:0:"";}'),
(67, '2014-12-05 02:01:35', 1, 'cat_user_branch_add', 'added Branch Management 5.', 'O:8:"stdClass":7:{s:2:"id";i:5;s:4:"name";s:12:"Newport Mall";s:4:"code";s:3:"NPM";s:7:"address";s:10:"Pasay City";s:14:"contact_number";s:9:"123-45-67";s:3:"fax";s:9:"123-45-67";s:7:"pmterms";s:0:"";}'),
(68, '2014-12-05 02:07:42', 1, 'cat_inv_prod_update', 'updated Product Ingredient 1468.', 'O:8:"stdClass":18:{s:2:"id";i:1468;s:4:"code";s:7:"PWD-002";s:4:"name";s:12:"Frost Powder";s:11:"description";s:13:"description 2";s:8:"quantity";N;s:12:"prodgroup_id";i:25;s:8:"brand_id";N;s:8:"color_id";N;s:12:"condition_id";N;s:11:"material_id";N;s:3:"uom";s:6:"sample";s:12:"flag_service";b:0;s:9:"flag_sale";b:0;s:13:"flag_purchase";b:0;s:10:"price_sale";s:4:"2.00";s:14:"price_purchase";s:4:"2.00";s:11:"supplier_id";i:1;s:5:"tasks";a:0:{}}'),
(69, '2014-12-05 02:08:34', 1, 'cat_inv_prod_update', 'updated Product Ingredient 1467.', 'O:8:"stdClass":18:{s:2:"id";i:1467;s:4:"code";s:7:"SNK-001";s:4:"name";s:10:"Taro Jelly";s:11:"description";s:18:"sample description";s:8:"quantity";i:2;s:12:"prodgroup_id";i:24;s:8:"brand_id";N;s:8:"color_id";N;s:12:"condition_id";N;s:11:"material_id";N;s:3:"uom";s:6:"sample";s:12:"flag_service";b:0;s:9:"flag_sale";b:0;s:13:"flag_purchase";b:0;s:10:"price_sale";s:4:"3.00";s:14:"price_purchase";s:4:"3.00";s:11:"supplier_id";i:1;s:5:"tasks";a:0:{}}'),
(70, '2014-12-05 02:08:59', 1, 'cat_inv_prod_update', 'updated Product Ingredient 1468.', 'O:8:"stdClass":18:{s:2:"id";i:1468;s:4:"code";s:7:"PWD-001";s:4:"name";s:12:"Frost Powder";s:11:"description";s:13:"description 2";s:8:"quantity";N;s:12:"prodgroup_id";i:25;s:8:"brand_id";N;s:8:"color_id";N;s:12:"condition_id";N;s:11:"material_id";N;s:3:"uom";s:6:"sample";s:12:"flag_service";b:0;s:9:"flag_sale";b:0;s:13:"flag_purchase";b:0;s:10:"price_sale";s:4:"2.00";s:14:"price_purchase";s:4:"2.00";s:11:"supplier_id";i:1;s:5:"tasks";a:0:{}}'),
(71, '2014-12-05 02:09:37', 1, 'cat_inv_prod_add', 'added Product Ingredient 1469.', 'O:8:"stdClass":18:{s:2:"id";i:1469;s:4:"code";s:7:"PWD-002";s:4:"name";s:15:"Oreo Strawberry";s:11:"description";s:4:"oreo";s:8:"quantity";N;s:12:"prodgroup_id";i:25;s:8:"brand_id";N;s:8:"color_id";N;s:12:"condition_id";N;s:11:"material_id";N;s:3:"uom";s:0:"";s:12:"flag_service";b:0;s:9:"flag_sale";b:0;s:13:"flag_purchase";b:0;s:10:"price_sale";s:4:"0.00";s:14:"price_purchase";s:4:"0.00";s:11:"supplier_id";i:1;s:5:"tasks";a:0:{}}'),
(72, '2014-12-05 02:10:39', 1, 'cat_inv_prod_add', 'added Product Ingredient 1470.', 'O:8:"stdClass":18:{s:2:"id";i:1470;s:4:"code";s:7:"SYR-001";s:4:"name";s:6:"Yakult";s:11:"description";s:4:"milk";s:8:"quantity";N;s:12:"prodgroup_id";i:26;s:8:"brand_id";N;s:8:"color_id";N;s:12:"condition_id";N;s:11:"material_id";N;s:3:"uom";s:0:"";s:12:"flag_service";b:0;s:9:"flag_sale";b:0;s:13:"flag_purchase";b:0;s:10:"price_sale";s:4:"0.00";s:14:"price_purchase";s:4:"0.00";s:11:"supplier_id";i:1;s:5:"tasks";a:0:{}}'),
(73, '2014-12-05 02:11:11', 1, 'cat_inv_prod_add', 'added Product Ingredient 1471.', 'O:8:"stdClass":18:{s:2:"id";i:1471;s:4:"code";s:7:"TEA-001";s:4:"name";s:9:"Assam Tea";s:11:"description";s:3:"tea";s:8:"quantity";N;s:12:"prodgroup_id";i:27;s:8:"brand_id";N;s:8:"color_id";N;s:12:"condition_id";N;s:11:"material_id";N;s:3:"uom";s:0:"";s:12:"flag_service";b:0;s:9:"flag_sale";b:0;s:13:"flag_purchase";b:0;s:10:"price_sale";s:4:"0.00";s:14:"price_purchase";s:4:"0.00";s:11:"supplier_id";i:1;s:5:"tasks";a:0:{}}'),
(74, '2014-12-06 11:47:53', 1, 'cat_user_user_add', 'added User 2.', 'O:8:"stdClass":7:{s:2:"id";i:2;s:8:"username";s:5:"staff";s:5:"email";s:13:"test@test.com";s:7:"enabled";b:1;s:8:"password";s:88:"HTmSqFcI0JcaX+vsIXrbZpOuumcTuE3J+WU+3ngUc8hWk2Q6ciP9A0P1a7l/3buuOPT7fe2a+Uj64QIETy6H4Q==";s:6:"groups";a:1:{i:0;O:8:"stdClass":1:{s:2:"id";i:2;}}s:9:"branch_id";i:1;}'),
(75, '2014-12-06 11:49:52', 1, 'cat_user_group_update', 'updated Role 2.', 'O:8:"stdClass":2:{s:2:"id";i:2;s:6:"access";a:29:{s:18:"cat_dashboard.menu";b:1;s:12:"cat_inv.menu";b:1;s:21:"serenitea_inv_br.menu";b:1;s:21:"serenitea_inv_br.view";b:1;s:20:"serenitea_inv_br.add";b:1;s:21:"serenitea_inv_br.edit";b:1;s:23:"serenitea_inv_br.delete";b:1;s:14:"ser_order.menu";b:1;s:16:"ser_request.menu";b:1;s:16:"ser_request.view";b:1;s:15:"ser_request.add";b:1;s:16:"ser_request.edit";b:1;s:16:"ser_receive.menu";b:1;s:16:"ser_receive.view";b:1;s:15:"ser_receive.add";b:1;s:16:"ser_receive.edit";b:1;s:16:"ser_receive.send";b:1;s:18:"ser_receive.cancel";b:1;s:20:"serenitea_forms.menu";b:1;s:22:"serenitea_pullout.menu";b:1;s:22:"serenitea_pullout.view";b:1;s:21:"serenitea_pullout.add";b:1;s:22:"serenitea_pullout.edit";b:1;s:21:"serenitea_repair.menu";b:1;s:21:"serenitea_repair.view";b:1;s:20:"serenitea_repair.add";b:1;s:21:"serenitea_repair.edit";b:1;s:21:"serenitea_repair.send";b:1;s:23:"serenitea_repair.cancel";b:1;}}'),
(76, '2014-12-08 12:23:06', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:126:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:20:"cat_user_branch.menu";b:1;s:20:"cat_user_branch.view";b:1;s:19:"cat_user_branch.add";b:1;s:20:"cat_user_branch.edit";b:1;s:22:"cat_user_branch.delete";b:1;s:12:"cat_inv.menu";b:1;s:15:"cat_inv_wh.view";b:1;s:14:"cat_inv_wh.add";b:1;s:15:"cat_inv_wh.edit";b:1;s:17:"cat_inv_wh.delete";b:1;s:18:"serenitea_inv.menu";b:1;s:18:"serenitea_inv.view";b:1;s:17:"serenitea_inv.add";b:1;s:18:"serenitea_inv.edit";b:1;s:20:"serenitea_inv.delete";b:1;s:21:"serenitea_inv_br.menu";b:1;s:21:"serenitea_inv_br.view";b:1;s:20:"serenitea_inv_br.add";b:1;s:21:"serenitea_inv_br.edit";b:1;s:23:"serenitea_inv_br.delete";b:1;s:15:"cat_inv_pg.menu";b:1;s:15:"cat_inv_pg.view";b:1;s:14:"cat_inv_pg.add";b:1;s:15:"cat_inv_pg.edit";b:1;s:17:"cat_inv_pg.delete";b:1;s:17:"cat_inv_prod.menu";b:1;s:17:"cat_inv_prod.view";b:1;s:16:"cat_inv_prod.add";b:1;s:17:"cat_inv_prod.edit";b:1;s:19:"cat_inv_prod.delete";b:1;s:28:"cat_inv_prod.view_sell_price";b:1;s:28:"cat_inv_prod.view_cost_price";b:1;s:23:"cat_inv_prod.restricted";b:1;s:18:"cat_inv_trans.menu";b:1;s:12:"cat_pur.menu";b:1;s:17:"cat_pur_supp.menu";b:1;s:17:"cat_pur_supp.view";b:1;s:16:"cat_pur_supp.add";b:1;s:17:"cat_pur_supp.edit";b:1;s:19:"cat_pur_supp.delete";b:1;s:15:"cat_pur_po.menu";b:1;s:15:"cat_pur_po.view";b:1;s:14:"cat_pur_po.add";b:1;s:15:"cat_pur_po.edit";b:1;s:18:"cat_pur_po.approve";b:1;s:15:"cat_pur_po.send";b:1;s:18:"cat_pur_po.fulfill";b:1;s:17:"cat_pur_po.cancel";b:1;s:15:"cat_report.menu";b:1;s:18:"cat_report_po.menu";b:1;s:18:"cat_report_po.view";b:1;s:18:"cat_report_dr.menu";b:1;s:18:"cat_report_dr.view";b:1;s:23:"cat_report_damaged.menu";b:1;s:23:"cat_report_damaged.view";b:1;s:24:"cat_report_returned.menu";b:1;s:24:"cat_report_returned.view";b:1;s:21:"cat_report_stock.menu";b:1;s:21:"cat_report_stock.view";b:1;s:23:"cat_report_balance.menu";b:1;s:23:"cat_report_balance.view";b:1;s:25:"cat_report_prcadjust.menu";b:1;s:25:"cat_report_prcadjust.view";b:1;s:23:"cat_report_compare.menu";b:1;s:23:"cat_report_compare.view";b:1;s:14:"ser_order.menu";b:1;s:16:"ser_request.menu";b:1;s:16:"ser_request.view";b:1;s:15:"ser_request.add";b:1;s:16:"ser_request.edit";b:1;s:18:"ser_request.delete";b:1;s:16:"ser_receive.menu";b:1;s:16:"ser_receive.view";b:1;s:15:"ser_receive.add";b:1;s:16:"ser_receive.edit";b:1;s:19:"ser_receive.approve";b:1;s:16:"ser_receive.send";b:1;s:19:"ser_receive.fulfill";b:1;s:18:"ser_receive.cancel";b:1;s:18:"ser_receive.delete";b:1;s:17:"ser_complete.menu";b:1;s:17:"ser_complete.view";b:1;s:20:"ser_complete.approve";b:1;s:19:"ser_complete.cancel";b:1;s:20:"serenitea_rlist.menu";b:1;s:20:"serenitea_rlist.view";b:1;s:19:"serenitea_rlist.add";b:1;s:20:"serenitea_rlist.edit";b:1;s:22:"serenitea_rlist.delete";b:1;s:20:"serenitea_dlist.menu";b:1;s:20:"serenitea_dlist.view";b:1;s:19:"serenitea_dlist.add";b:1;s:20:"serenitea_dlist.edit";b:1;s:23:"serenitea_dlist.approve";b:1;s:20:"serenitea_dlist.send";b:1;s:23:"serenitea_dlist.fulfill";b:1;s:22:"serenitea_dlist.cancel";b:1;s:20:"serenitea_forms.menu";b:1;s:22:"serenitea_pullout.menu";b:1;s:22:"serenitea_pullout.view";b:1;s:21:"serenitea_pullout.add";b:1;s:22:"serenitea_pullout.edit";b:1;s:24:"serenitea_pullout.delete";b:1;s:21:"serenitea_repair.menu";b:1;s:21:"serenitea_repair.view";b:1;s:20:"serenitea_repair.add";b:1;s:21:"serenitea_repair.edit";b:1;s:24:"serenitea_repair.approve";b:1;s:21:"serenitea_repair.send";b:1;s:24:"serenitea_repair.fulfill";b:1;s:23:"serenitea_repair.cancel";b:1;}}'),
(77, '2014-12-09 04:22:22', 1, 'cat_user_group_update', 'updated Role 2.', 'O:8:"stdClass":2:{s:2:"id";i:2;s:6:"access";a:31:{s:18:"cat_dashboard.menu";b:1;s:12:"cat_inv.menu";b:1;s:21:"serenitea_inv_br.menu";b:1;s:21:"serenitea_inv_br.view";b:1;s:20:"serenitea_inv_br.add";b:1;s:21:"serenitea_inv_br.edit";b:1;s:23:"serenitea_inv_br.delete";b:1;s:14:"ser_order.menu";b:1;s:16:"ser_request.menu";b:1;s:16:"ser_request.view";b:1;s:15:"ser_request.add";b:1;s:16:"ser_request.edit";b:1;s:16:"ser_receive.menu";b:1;s:16:"ser_receive.view";b:1;s:15:"ser_receive.add";b:1;s:16:"ser_receive.edit";b:1;s:16:"ser_receive.send";b:1;s:18:"ser_receive.cancel";b:1;s:17:"ser_complete.menu";b:1;s:17:"ser_complete.view";b:1;s:20:"serenitea_forms.menu";b:1;s:22:"serenitea_pullout.menu";b:1;s:22:"serenitea_pullout.view";b:1;s:21:"serenitea_pullout.add";b:1;s:22:"serenitea_pullout.edit";b:1;s:21:"serenitea_repair.menu";b:1;s:21:"serenitea_repair.view";b:1;s:20:"serenitea_repair.add";b:1;s:21:"serenitea_repair.edit";b:1;s:21:"serenitea_repair.send";b:1;s:23:"serenitea_repair.cancel";b:1;}}'),
(78, '2015-01-28 06:55:02', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:26:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;}}'),
(79, '2015-01-28 07:28:31', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:34:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;}}'),
(80, '2015-01-28 07:29:00', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:34:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;}}'),
(81, '2015-01-28 07:49:55', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:35:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;}}'),
(82, '2015-01-28 07:53:37', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:36:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;}}'),
(83, '2015-01-28 08:19:30', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:37:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;}}'),
(84, '2015-01-28 08:23:56', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:39:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;}}'),
(85, '2015-01-28 08:52:47', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:40:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;}}'),
(86, '2015-01-28 08:56:53', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:41:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;}}'),
(87, '2015-01-28 09:16:52', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:42:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;}}'),
(88, '2015-01-28 09:20:25', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:43:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;}}'),
(89, '2015-01-28 09:42:40', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:44:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;}}'),
(90, '2015-01-28 09:45:02', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:45:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;}}'),
(91, '2015-01-28 09:48:43', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:48:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;}}'),
(92, '2015-01-28 10:26:03', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:49:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;s:17:"hris_profile.menu";b:1;}}'),
(93, '2015-01-28 10:27:56', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:50:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;s:17:"hris_profile.menu";b:1;s:26:"hris_profile_employee.menu";b:1;}}'),
(94, '2015-01-28 10:42:58', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:51:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;s:17:"hris_profile.menu";b:1;s:26:"hris_profile_employee.menu";b:1;s:23:"hris_profile_leave.menu";b:1;}}'),
(95, '2015-01-28 11:04:19', 1, 'cat_user_group_update', 'updated Role 2.', 'O:8:"stdClass":2:{s:2:"id";i:2;s:6:"access";a:4:{s:18:"cat_dashboard.menu";b:1;s:17:"hris_profile.menu";b:1;s:26:"hris_profile_employee.menu";b:1;s:23:"hris_profile_leave.menu";b:1;}}'),
(96, '2015-01-28 11:05:46', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:47:{s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;}}'),
(97, '2015-01-29 09:18:19', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:49:{s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:27:"hris_workforce_benefits.add";b:1;s:28:"hris_workforce_benefits.edit";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;}}'),
(98, '2015-01-29 09:33:11', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:52:{s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:27:"hris_workforce_benefits.add";b:1;s:28:"hris_workforce_benefits.edit";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:22:"hris_applications.view";b:1;s:21:"hris_applications.add";b:1;s:22:"hris_applications.edit";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;}}'),
(99, '2015-01-29 09:41:34', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:56:{s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:27:"hris_workforce_benefits.add";b:1;s:28:"hris_workforce_benefits.edit";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:22:"hris_applications.view";b:1;s:21:"hris_applications.add";b:1;s:22:"hris_applications.edit";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:20:"hris_employeedb.view";b:1;s:19:"hris_employeedb.add";b:1;s:20:"hris_employeedb.edit";b:1;s:22:"hris_employeedb.delete";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;}}'),
(100, '2015-01-29 09:43:34', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:53:{s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:27:"hris_workforce_benefits.add";b:1;s:28:"hris_workforce_benefits.edit";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:22:"hris_applications.view";b:1;s:21:"hris_applications.add";b:1;s:22:"hris_applications.edit";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:20:"hris_employeedb.view";b:1;s:13:"hris_faq.menu";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;}}');
INSERT INTO `logentry` (`id`, `date_in`, `user_id`, `action_id`, `description`, `data`) VALUES
(101, '2015-01-29 09:47:48', 1, 'cat_user_group_update', 'updated Role 4.', 'O:8:"stdClass":2:{s:2:"id";i:4;s:6:"access";a:56:{s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;s:19:"hris_workforce.menu";b:1;s:28:"hris_workforce_employee.menu";b:1;s:28:"hris_workforce_employee.view";b:1;s:27:"hris_workforce_employee.add";b:1;s:28:"hris_workforce_employee.edit";b:1;s:30:"hris_workforce_attendance.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:30:"hris_workforce_appraisals.menu";b:1;s:30:"hris_workforce_appraisals.view";b:1;s:29:"hris_workforce_appraisals.add";b:1;s:30:"hris_workforce_appraisals.edit";b:1;s:28:"hris_workforce_benefits.menu";b:1;s:28:"hris_workforce_benefits.view";b:1;s:27:"hris_workforce_benefits.add";b:1;s:28:"hris_workforce_benefits.edit";b:1;s:29:"hris_workforce_reimburse.menu";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:29:"hris_workforce_reimburse.edit";b:1;s:26:"hris_workforce_resign.menu";b:1;s:26:"hris_workforce_resign.view";b:1;s:17:"hris_recruit.menu";b:1;s:22:"hris_applications.menu";b:1;s:22:"hris_applications.view";b:1;s:21:"hris_applications.add";b:1;s:22:"hris_applications.edit";b:1;s:18:"hris_archives.menu";b:1;s:20:"hris_employeedb.menu";b:1;s:20:"hris_employeedb.view";b:1;s:13:"hris_faq.menu";b:1;s:13:"hris_faq.view";b:1;s:12:"hris_faq.add";b:1;s:13:"hris_faq.edit";b:1;s:26:"hris_emp_satisfaction.menu";b:1;s:19:"hris_discounts.menu";b:1;s:18:"hris_training.menu";b:1;s:16:"hris_etrain.menu";b:1;s:22:"hris_com_overview.menu";b:1;s:18:"hris_com_info.menu";b:1;s:22:"hris_com_orgchart.menu";b:1;s:23:"hris_com_directory.menu";b:1;s:22:"hris_com_handbook.menu";b:1;}}');

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethod`
--

CREATE TABLE IF NOT EXISTS `paymentmethod` (
`id` int(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `paymentmethod`
--

INSERT INTO `paymentmethod` (`id`, `name`) VALUES
(1, 'Cash'),
(2, 'Credit Card'),
(3, 'Gift Check'),
(4, 'Check'),
(5, 'Debit Card'),
(6, 'Bank Deposit'),
(7, 'Free of Charge'),
(8, 'Groupon P1000');

-- --------------------------------------------------------

--
-- Table structure for table `pbrand`
--

CREATE TABLE IF NOT EXISTS `pbrand` (
`id` int(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pcolor`
--

CREATE TABLE IF NOT EXISTS `pcolor` (
`id` int(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pcondition`
--

CREATE TABLE IF NOT EXISTS `pcondition` (
`id` int(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pmaterial`
--

CREATE TABLE IF NOT EXISTS `pmaterial` (
`id` int(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `podelivery`
--

CREATE TABLE IF NOT EXISTS `podelivery` (
`id` bigint(10) unsigned NOT NULL,
  `po_id` bigint(10) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `date_in` datetime NOT NULL,
  `date_deliver` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `podeliveryentry`
--

CREATE TABLE IF NOT EXISTS `podeliveryentry` (
`id` bigint(10) unsigned NOT NULL,
  `podel_id` bigint(10) unsigned NOT NULL,
  `product_id` bigint(10) unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `poentry`
--

CREATE TABLE IF NOT EXISTS `poentry` (
`id` bigint(10) unsigned NOT NULL,
  `po_id` bigint(10) unsigned NOT NULL,
  `product_id` int(8) unsigned NOT NULL,
  `quantity` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
`id` int(8) unsigned NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(180) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `prodgroup_id` int(6) unsigned NOT NULL,
  `supplier_id` int(10) unsigned DEFAULT NULL,
  `brand_id` int(5) unsigned DEFAULT NULL,
  `color_id` int(5) unsigned DEFAULT NULL,
  `condition_id` int(5) unsigned DEFAULT NULL,
  `material_id` int(5) unsigned DEFAULT NULL,
  `uom` varchar(20) NOT NULL,
  `flag_service` tinyint(1) unsigned DEFAULT '0',
  `flag_sale` tinyint(1) unsigned DEFAULT '1',
  `flag_purchase` tinyint(1) unsigned DEFAULT '1',
  `price_sale` decimal(13,2) NOT NULL DEFAULT '0.00',
  `price_purchase` decimal(13,2) NOT NULL DEFAULT '0.00',
  `stock_min` decimal(10,2) DEFAULT '0.00',
  `stock_max` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `productgroup`
--

CREATE TABLE IF NOT EXISTS `productgroup` (
`id` int(6) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `purchaseorder`
--

CREATE TABLE IF NOT EXISTS `purchaseorder` (
`id` bigint(10) unsigned NOT NULL,
  `code` varchar(40) DEFAULT NULL,
  `supplier_id` int(5) unsigned NOT NULL,
  `date_in` datetime NOT NULL,
  `date_issue` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status_id` varchar(30) NOT NULL DEFAULT 'draft'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `purchaseorder`
--

INSERT INTO `purchaseorder` (`id`, `code`, `supplier_id`, `date_in`, `date_issue`, `total_price`, `status_id`) VALUES
(11, 'POS-2014-00011', 1, '2014-12-01 10:51:05', '2014-12-01', '9500.00', 'sent'),
(12, 'POS-2014-00012', 2, '2014-12-01 10:54:47', '2014-12-01', '10069.00', 'sent'),
(13, 'POS-2014-00013', 1, '2014-12-01 11:01:13', '2014-12-01', '2330.00', 'sent');

-- --------------------------------------------------------

--
-- Table structure for table `salesorder`
--

CREATE TABLE IF NOT EXISTS `salesorder` (
`id` bigint(10) unsigned NOT NULL,
  `code` varchar(40) NOT NULL,
  `customer_id` int(5) unsigned NOT NULL,
  `warehouse_id` int(8) unsigned NOT NULL,
  `user_id` int(5) unsigned NOT NULL,
  `date_in` datetime NOT NULL,
  `date_issue` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status_id` varchar(30) NOT NULL DEFAULT 'draft',
  `payment_id` int(5) unsigned DEFAULT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `downpayment` decimal(10,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `serviceorder`
--

CREATE TABLE IF NOT EXISTS `serviceorder` (
`id` bigint(10) unsigned NOT NULL,
  `code` varchar(40) NOT NULL,
  `customer_id` int(5) unsigned NOT NULL,
  `warehouse_id` int(8) unsigned NOT NULL,
  `product_id` bigint(10) unsigned NOT NULL,
  `user_id` int(5) unsigned NOT NULL,
  `date_in` datetime NOT NULL,
  `date_need` date NOT NULL,
  `date_need_repairman` date DEFAULT NULL,
  `date_issue` date NOT NULL,
  `date_completed` datetime DEFAULT NULL,
  `date_claimed` datetime DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status_id` varchar(30) NOT NULL DEFAULT 'draft',
  `note` text,
  `data` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `servicetask`
--

CREATE TABLE IF NOT EXISTS `servicetask` (
`id` int(8) unsigned NOT NULL,
  `product_id` int(8) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `sell_price` decimal(13,2) NOT NULL DEFAULT '0.00',
  `cost_price` decimal(13,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `soentry`
--

CREATE TABLE IF NOT EXISTS `soentry` (
`id` bigint(10) unsigned NOT NULL,
  `so_id` bigint(10) unsigned NOT NULL,
  `product_id` bigint(10) unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(20) NOT NULL DEFAULT 'percent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
`id` int(6) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `address` varchar(150) NOT NULL,
  `contact_number` varchar(80) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `contact_person` varchar(80) NOT NULL,
  `notes` text,
  `warehouse_id` int(8) unsigned NOT NULL,
  `fax_number` int(50) NOT NULL,
  `tin_number` int(12) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `address`, `contact_number`, `email`, `contact_person`, `notes`, `warehouse_id`, `fax_number`, `tin_number`) VALUES
(1, 'Test Supplier', 'Sample address somewhere out there, Metro Manila, Philippines', '123-45-67', 'test@test.com', 'Sample Contact', 'Sample note.', 1, 1212121212, 1212121212),
(2, 'Aminosan', 'makati city', '1442520', NULL, 'Juan Dela Cruz', NULL, 1, 33625421, 2147483647),
(3, 'P&G', 'manila', '115-55-55', NULL, 'Juan Dela Cruz', NULL, 1, 1212154545, 2147483647),
(4, 'Coca cola', 'Mandaluyong', '4445896', NULL, 'James Chua', NULL, 1, 12452510, 1247859521);

-- --------------------------------------------------------

--
-- Table structure for table `sventry`
--

CREATE TABLE IF NOT EXISTS `sventry` (
`id` bigint(10) unsigned NOT NULL,
  `so_id` bigint(10) unsigned NOT NULL,
  `product_id` bigint(10) unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_type` varchar(20) NOT NULL DEFAULT 'percent',
  `assigned_id` int(5) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `svetask`
--

CREATE TABLE IF NOT EXISTS `svetask` (
`id` bigint(11) unsigned NOT NULL,
  `sve_id` bigint(10) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `sell_price` decimal(13,2) NOT NULL DEFAULT '0.00',
  `cost_price` decimal(13,2) NOT NULL DEFAULT '0.00',
  `assigned_id` int(5) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(5) unsigned NOT NULL,
  `username` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `branch_id` int(11) unsigned NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
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
  `credentials_expire_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `username_canonical`, `name`, `branch_id`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`) VALUES
(1, 'admin', 'admin', 'Administrator', 1, 'kc@jankstudio.com', 'kc@jankstudio.com', 1, 'fxffy0ovvgg08wkwkc8g04ocws408kc', 'iIztkAEIBytxjqGMyAG2UZX8xvEBoDazic8Zvfr6CVtebLfqSRAgvFLOxbeNdNBbg9dGa7PXk0cAY9/ZDZHDhw==', '2015-02-03 11:06:13', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL),
(2, 'staff', 'staff', 'employee', 1, 'test@test.com', 'test@test.com', 1, 'h9oasdhk6fwwk4o0s8os8s00k8888cs', 'HTmSqFcI0JcaX+vsIXrbZpOuumcTuE3J+WU+3ngUc8hWk2Q6ciP9A0P1a7l/3buuOPT7fe2a+Uj64QIETy6H4Q==', '2015-01-29 03:48:49', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE IF NOT EXISTS `usergroup` (
  `user_id` int(5) unsigned NOT NULL,
  `group_id` int(4) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`user_id`, `group_id`) VALUES
(2, 2),
(1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE IF NOT EXISTS `warehouse` (
`id` int(8) unsigned NOT NULL,
  `type_id` varchar(20) NOT NULL DEFAULT 'physical',
  `name` varchar(50) NOT NULL,
  `contact_num` varchar(80) NOT NULL,
  `address` varchar(200) NOT NULL,
  `flag_threshold` tinyint(1) unsigned NOT NULL,
  `flag_shopfront` tinyint(1) unsigned NOT NULL,
  `flag_stocktrack` tinyint(1) DEFAULT NULL,
  `internal_code` varchar(25) NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`id`, `type_id`, `name`, `contact_num`, `address`, `flag_threshold`, `flag_shopfront`, `flag_stocktrack`, `internal_code`) VALUES
(1, 'virtual', 'Supplier', '', 'Virtual warehouse for all suppliers.', 0, 0, NULL, ''),
(2, 'virtual', 'Customer', '', '', 0, 0, NULL, ''),
(9, 'physical', 'Greenbelt 5 Branch', '', '2nd Floor Greenbelt 5', 1, 1, 1, '06'),
(10, 'physical', 'SM Mall of Asia Branch', '551-3515, 0998-5181826', '2nd floor Entertainment Mall, SM Mall of Asia, Pasay', 1, 1, 1, '03'),
(11, 'physical', 'Greenhills Branch', '650-0944, 0998-3901800', '3rd floor Shoppesville, Greenhills Shopping Center, San Juan', 1, 1, 1, '01'),
(12, 'physical', 'Glorietta 5 Branch', '950-9577, 0998-3901505', '3rd floor Glorietta 5, Ayala Center, Makati', 1, 1, 1, '02'),
(13, 'physical', 'Eastwood Branch', '584-5425, 0998-1945777', '2nd floor Eastwood Citywalk 2, Quezon City', 1, 1, 1, '04'),
(14, 'physical', 'SM Megamall Fashion Hall Branch', '', '5th Floor SM Megamall Fashion Hall (Building D)', 1, 1, 1, '05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batchentry`
--
ALTER TABLE `batchentry`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billofmaterial`
--
ALTER TABLE `billofmaterial`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bomdimension`
--
ALTER TABLE `bomdimension`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_BOMDimension_Template` (`template_id`);

--
-- Indexes for table `bominput`
--
ALTER TABLE `bominput`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_BOMInput_Product` (`product_id`), ADD KEY `FK_BOMInput_BOM` (`bom_id`);

--
-- Indexes for table `bommaterial`
--
ALTER TABLE `bommaterial`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_BOMMaterial_Product` (`product_id`), ADD KEY `FK_BOMMaterial_Template` (`template_id`);

--
-- Indexes for table `bomoutput`
--
ALTER TABLE `bomoutput`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_BOMOutput_Product` (`product_id`), ADD KEY `FK_BOMOutput_BOM` (`bom_id`);

--
-- Indexes for table `bomtemplate`
--
ALTER TABLE `bomtemplate`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_BOMTemplate_ProductGroup` (`prodgroup_id`);

--
-- Indexes for table `branchmanagement`
--
ALTER TABLE `branchmanagement`
 ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `configentry`
--
ALTER TABLE `configentry`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_Customer_Warehouse` (`warehouse_id`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventoryentry`
--
ALTER TABLE `inventoryentry`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_InventoryEntry_Product` (`product_id`), ADD KEY `FK_InventoryEntry_Warehouse` (`warehouse_id`), ADD KEY `FK_InventoryEntry_Transaction` (`transaction_id`);

--
-- Indexes for table `inventorystock`
--
ALTER TABLE `inventorystock`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `NewIndex1` (`warehouse_id`,`product_id`), ADD KEY `FK_InventoryStock_Product` (`product_id`);

--
-- Indexes for table `inventorytransaction`
--
ALTER TABLE `inventorytransaction`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_InventoryTransaction_User` (`user_id`);

--
-- Indexes for table `logentry`
--
ALTER TABLE `logentry`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_LogEntry_User` (`user_id`);

--
-- Indexes for table `paymentmethod`
--
ALTER TABLE `paymentmethod`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pbrand`
--
ALTER TABLE `pbrand`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcolor`
--
ALTER TABLE `pcolor`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pcondition`
--
ALTER TABLE `pcondition`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pmaterial`
--
ALTER TABLE `pmaterial`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `podelivery`
--
ALTER TABLE `podelivery`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `podeliveryentry`
--
ALTER TABLE `podeliveryentry`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poentry`
--
ALTER TABLE `poentry`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_POEntry_Product` (`product_id`), ADD KEY `FK_POEntry_PO` (`po_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_Product` (`prodgroup_id`), ADD KEY `brand_id` (`brand_id`), ADD KEY `color_id` (`color_id`), ADD KEY `condition_id` (`condition_id`), ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `productgroup`
--
ALTER TABLE `productgroup`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchaseorder`
--
ALTER TABLE `purchaseorder`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_PurchaseOrder_Supplier` (`supplier_id`);

--
-- Indexes for table `salesorder`
--
ALTER TABLE `salesorder`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD KEY `FK_SalesOrder_Customer` (`customer_id`), ADD KEY `FK_SalesOrder_Warehouse` (`warehouse_id`), ADD KEY `FK_SalesOrder_User` (`user_id`);

--
-- Indexes for table `serviceorder`
--
ALTER TABLE `serviceorder`
 ADD PRIMARY KEY (`id`,`code`), ADD UNIQUE KEY `id` (`id`), ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `servicetask`
--
ALTER TABLE `servicetask`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `soentry`
--
ALTER TABLE `soentry`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
 ADD PRIMARY KEY (`id`), ADD KEY `FK_Supplier_Warehouse` (`warehouse_id`);

--
-- Indexes for table `sventry`
--
ALTER TABLE `sventry`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `svetask`
--
ALTER TABLE `svetask`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `UNIQ_2DA1797792FC23A8` (`username_canonical`), ADD UNIQUE KEY `UNIQ_2DA17977A0D96FBF` (`email_canonical`);

--
-- Indexes for table `usergroup`
--
ALTER TABLE `usergroup`
 ADD PRIMARY KEY (`user_id`,`group_id`), ADD KEY `FK_UserGroup_Group` (`group_id`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
 ADD PRIMARY KEY (`id`), ADD KEY `type_id` (`type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batchentry`
--
ALTER TABLE `batchentry`
MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `billofmaterial`
--
ALTER TABLE `billofmaterial`
MODIFY `id` int(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bomdimension`
--
ALTER TABLE `bomdimension`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bominput`
--
ALTER TABLE `bominput`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bommaterial`
--
ALTER TABLE `bommaterial`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bomoutput`
--
ALTER TABLE `bomoutput`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bomtemplate`
--
ALTER TABLE `bomtemplate`
MODIFY `id` int(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `branchmanagement`
--
ALTER TABLE `branchmanagement`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
MODIFY `id` int(6) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
MODIFY `id` int(4) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `inventoryentry`
--
ALTER TABLE `inventoryentry`
MODIFY `id` bigint(12) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inventorystock`
--
ALTER TABLE `inventorystock`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inventorytransaction`
--
ALTER TABLE `inventorytransaction`
MODIFY `id` bigint(12) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logentry`
--
ALTER TABLE `logentry`
MODIFY `id` bigint(15) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `paymentmethod`
--
ALTER TABLE `paymentmethod`
MODIFY `id` int(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `pbrand`
--
ALTER TABLE `pbrand`
MODIFY `id` int(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pcolor`
--
ALTER TABLE `pcolor`
MODIFY `id` int(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pcondition`
--
ALTER TABLE `pcondition`
MODIFY `id` int(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pmaterial`
--
ALTER TABLE `pmaterial`
MODIFY `id` int(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `podelivery`
--
ALTER TABLE `podelivery`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `podeliveryentry`
--
ALTER TABLE `podeliveryentry`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `poentry`
--
ALTER TABLE `poentry`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
MODIFY `id` int(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `productgroup`
--
ALTER TABLE `productgroup`
MODIFY `id` int(6) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchaseorder`
--
ALTER TABLE `purchaseorder`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `salesorder`
--
ALTER TABLE `salesorder`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `serviceorder`
--
ALTER TABLE `serviceorder`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `servicetask`
--
ALTER TABLE `servicetask`
MODIFY `id` int(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `soentry`
--
ALTER TABLE `soentry`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
MODIFY `id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sventry`
--
ALTER TABLE `sventry`
MODIFY `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `svetask`
--
ALTER TABLE `svetask`
MODIFY `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
MODIFY `id` int(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bomdimension`
--
ALTER TABLE `bomdimension`
ADD CONSTRAINT `FK_BOMDimension_Template` FOREIGN KEY (`template_id`) REFERENCES `bomtemplate` (`id`);

--
-- Constraints for table `bominput`
--
ALTER TABLE `bominput`
ADD CONSTRAINT `FK_BOMInput_BOM` FOREIGN KEY (`bom_id`) REFERENCES `billofmaterial` (`id`),
ADD CONSTRAINT `FK_BOMInput_Product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `bommaterial`
--
ALTER TABLE `bommaterial`
ADD CONSTRAINT `FK_BOMMaterial_Product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
ADD CONSTRAINT `FK_BOMMaterial_Template` FOREIGN KEY (`template_id`) REFERENCES `bomtemplate` (`id`);

--
-- Constraints for table `bomoutput`
--
ALTER TABLE `bomoutput`
ADD CONSTRAINT `FK_BOMOutput_BOM` FOREIGN KEY (`bom_id`) REFERENCES `billofmaterial` (`id`),
ADD CONSTRAINT `FK_BOMOutput_Product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `bomtemplate`
--
ALTER TABLE `bomtemplate`
ADD CONSTRAINT `FK_BOMTemplate_ProductGroup` FOREIGN KEY (`prodgroup_id`) REFERENCES `productgroup` (`id`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
ADD CONSTRAINT `FK_Customer_Warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`);

--
-- Constraints for table `inventoryentry`
--
ALTER TABLE `inventoryentry`
ADD CONSTRAINT `FK_InventoryEntry_Product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
ADD CONSTRAINT `FK_InventoryEntry_Transaction` FOREIGN KEY (`transaction_id`) REFERENCES `inventorytransaction` (`id`),
ADD CONSTRAINT `FK_InventoryEntry_Warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`);

--
-- Constraints for table `inventorystock`
--
ALTER TABLE `inventorystock`
ADD CONSTRAINT `FK_InventoryStock_Product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
ADD CONSTRAINT `FK_InventoryStock_Warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`);

--
-- Constraints for table `inventorytransaction`
--
ALTER TABLE `inventorytransaction`
ADD CONSTRAINT `FK_InventoryTransaction_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `logentry`
--
ALTER TABLE `logentry`
ADD CONSTRAINT `FK_LogEntry_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `poentry`
--
ALTER TABLE `poentry`
ADD CONSTRAINT `FK_POEntry_PO` FOREIGN KEY (`po_id`) REFERENCES `purchaseorder` (`id`),
ADD CONSTRAINT `FK_POEntry_Product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
ADD CONSTRAINT `FK_Product` FOREIGN KEY (`prodgroup_id`) REFERENCES `productgroup` (`id`),
ADD CONSTRAINT `Product_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `pbrand` (`id`),
ADD CONSTRAINT `Product_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `pcolor` (`id`),
ADD CONSTRAINT `Product_ibfk_3` FOREIGN KEY (`condition_id`) REFERENCES `pcondition` (`id`),
ADD CONSTRAINT `Product_ibfk_4` FOREIGN KEY (`material_id`) REFERENCES `pmaterial` (`id`);

--
-- Constraints for table `purchaseorder`
--
ALTER TABLE `purchaseorder`
ADD CONSTRAINT `FK_PurchaseOrder_Supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`);

--
-- Constraints for table `salesorder`
--
ALTER TABLE `salesorder`
ADD CONSTRAINT `FK_SalesOrder_Customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
ADD CONSTRAINT `FK_SalesOrder_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
ADD CONSTRAINT `FK_SalesOrder_Warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`);

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
ADD CONSTRAINT `FK_Supplier_Warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`);

--
-- Constraints for table `usergroup`
--
ALTER TABLE `usergroup`
ADD CONSTRAINT `FK_UserGroup_Group` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`),
ADD CONSTRAINT `FK_UserGroup_User` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
