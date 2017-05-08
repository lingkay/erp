
INSERT INTO `cfg_entry` VALUES ('gist_warehouse_stock_adjustment','5'),('accounting_cash_voucher_type','cash_voucher'),('accounting_check_voucher_type','check_voucher'),('accounting_current_period','1'),('catalyst_warehouse_equipment','3'),('catalyst_warehouse_main','4'),('catalyst_warehouse_production_tank','6'),('catalyst_warehouse_sales','5'),('catalyst_warehouse_stock_adjustment','5'),('fareast_def_mol_prod','16'),('zulu_acct_current_period','1');


INSERT INTO `inv_account` VALUES 
(1,1,1,'2015-07-22 18:09:16','Warehouse: ss, '),
(2,1,0,'2015-07-23 16:27:13','Warehouse: Test Customer'),
(3,1,1,'2015-08-10 14:36:19','Supplier: , Test'),
(4,1,0,'2015-08-10 15:14:49','Warehouse: Receiving Warehouse'),
(5,1,0,'2016-02-04 19:08:16','Warehouse: Sales Warehouse'),
(6,1,0,'2016-02-04 19:08:22','Warehouse: Equipment Warehouse');

INSERT INTO `inv_product_group` VALUES (1,'FG','Finished Goods'),(2,'ALC','Alcohol'),(3,'HT','Hand Tools'),(4,'HDR','Drill'),(5,'CL2','Coco Lumber 2 x 2 x 10 ft.'),(6,'SE','Supplies and Equipment');

INSERT INTO `inv_warehouse` VALUES 
(1,1,1,'',0,0,0,NULL,1,NULL,'physical','','','Receiving Warehouse','2015-08-10 15:14:49'),
(2,1,2,'',1,0,1,NULL,0,NULL,'physical','','','Sales Warehouse','2016-02-04 19:08:16'),
(3,1,3,'',1,0,0,0,0,NULL,'physical	','asd','213','Equipment Warehouse','2016-02-04 19:08:22'),
(4,1,4,'',1,0,0,NULL,0,NULL,'physical','','','Main Warehouse','2016-02-04 19:08:54'),
(5,1,5,'',1,0,0,0,0,NULL,'adjustment',NULL,NULL,'Adjustment Warehouse','2016-02-04 19:09:07'),
(6,1,6,'',1,0,0,NULL,0,NULL,'tank',NULL,NULL,'Production Tank','2016-02-04 19:09:38');


