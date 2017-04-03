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
(2, 'User', NULL, 'a:8:{s:18:"cat_dashboard.menu";b:1;s:30:"hris_workforce_attendance.view";b:1;s:29:"hris_workforce_reimburse.view";b:1;s:28:"hris_workforce_reimburse.add";b:1;s:17:"hris_profile.menu";b:1;s:26:"hris_profile_employee.menu";b:1;s:23:"hris_profile_leave.menu";b:1;s:22:"hris_profile_leave.add";b:1;}'),
(4, 'Administrator', 'a:0:{}', 'a:15:{s:18:"cat_dashboard.menu";b:1;s:14:"cat_admin.menu";b:1;s:15:"cat_config.menu";b:1;s:15:"cat_config.view";b:1;s:15:"cat_config.edit";b:1;s:18:"cat_user_user.menu";b:1;s:18:"cat_user_user.view";b:1;s:17:"cat_user_user.add";b:1;s:18:"cat_user_user.edit";b:1;s:20:"cat_user_user.delete";b:1;s:19:"cat_user_group.menu";b:1;s:19:"cat_user_group.view";b:1;s:18:"cat_user_group.add";b:1;s:19:"cat_user_group.edit";b:1;s:21:"cat_user_group.delete";b:1;}'),
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

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethod`
--

CREATE TABLE IF NOT EXISTS `paymentmethod` (
`id` int(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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
(2, 'staff', 'staff', 'employee', 1, 'test@test.com', 'test@test.com', 1, 'h9oasdhk6fwwk4o0s8os8s00k8888cs', 'HTmSqFcI0JcaX+vsIXrbZpOuumcTuE3J+WU+3ngUc8hWk2Q6ciP9A0P1a7l/3buuOPT7fe2a+Uj64QIETy6H4Q==', '2015-01-29 03:48:49', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL),
(3, 'employee', 'employee', 'Employee', 1, 'employee@temporary.com', 'employee@temporary.com', 1, 'c9507xittqg400oc84ook48wwcs48ss', 'mOn3qpZNpRH2RWJP+CKLOOayIJztjHGWI42gLtp5g05DQo0wiyghgwt9+21KXvZQdssq943gUWsf+lxag7AVcw==', '2015-03-28 07:23:42', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL);

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
(3, 2),
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
