<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-12-07 11:08:28 --> Severity: Warning --> Array to string conversion /home/myvegizc/public_html/subkaemsoftware/development/supermarket/system/database/DB_query_builder.php 2442
ERROR - 2022-12-07 11:08:28 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: SELECT count(distinct ordermaster.code) as cnt
FROM `ordermaster`
WHERE 0 = Array
ERROR - 2022-12-07 11:11:51 --> Severity: Warning --> Undefined array key "code" /home/myvegizc/public_html/subkaemsoftware/development/supermarket/application/controllers/Cashier/Authentication.php 39
ERROR - 2022-12-07 15:57:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 15:57:45 --> Severity: Warning --> Undefined array key "code" /home/myvegizc/public_html/subkaemsoftware/development/supermarket/application/controllers/Cashier/Authentication.php 39
ERROR - 2022-12-07 15:57:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 15:57:54 --> Severity: Warning --> Undefined array key "code" /home/myvegizc/public_html/subkaemsoftware/development/supermarket/application/controllers/Cashier/Authentication.php 39
ERROR - 2022-12-07 15:57:59 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 16:26:37 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 16:26:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 16:28:23 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 16:28:23 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 16:29:25 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 16:29:26 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 16:55:06 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 16:55:06 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 16:57:53 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 16:57:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 17:03:26 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 17:03:26 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 17:43:37 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 17:43:39 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 22:31:06 --> Severity: Warning --> Undefined array key "code" /home/myvegizc/public_html/subkaemsoftware/development/supermarket/application/controllers/Cashier/Authentication.php 39
ERROR - 2022-12-07 22:36:17 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'JOIN `ordertemp` ON `ordertemp`.`orderId`=`ordertempproducts`.`orderId`
WHERE `o' at line 2 - Invalid query: SELECT IFNULL(SUM(ordertempproducts.qty), 0) as qty
JOIN `ordertemp` ON `ordertemp`.`orderId`=`ordertempproducts`.`orderId`
WHERE `ordertempproducts`.`barcode` = '08135351'
AND `ordertemp`.`branchCode` = 'BR22_2'
AND `ordertemp`.`orderId` = ''
ERROR - 2022-12-07 22:36:28 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 22:36:28 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 22:36:34 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'JOIN `ordertemp` ON `ordertemp`.`orderId`=`ordertempproducts`.`orderId`
WHERE `o' at line 2 - Invalid query: SELECT IFNULL(SUM(ordertempproducts.qty), 0) as qty
JOIN `ordertemp` ON `ordertemp`.`orderId`=`ordertempproducts`.`orderId`
WHERE `ordertempproducts`.`barcode` = '08135351'
AND `ordertemp`.`branchCode` = 'BR22_2'
AND `ordertemp`.`orderId` = ''
ERROR - 2022-12-07 22:39:54 --> Severity: Warning --> Undefined array key "unit" /home/myvegizc/public_html/subkaemsoftware/development/supermarket/application/models/PosorderModel.php 301
ERROR - 2022-12-07 22:40:41 --> Severity: Warning --> Undefined array key "unit" /home/myvegizc/public_html/subkaemsoftware/development/supermarket/application/models/PosorderModel.php 303
ERROR - 2022-12-07 22:48:41 --> Query error: Unknown column 'unitmaster.unitRoundin' in 'field list' - Invalid query: SELECT `unitmaster`.`conversionFactor`, `unitmaster`.`unitRoundin`
FROM `productmaster`
JOIN `unitmaster` ON `unitmaster`.`code`=`productmaster`.`storageUnit`
WHERE `productmaster`.`code` = 'PRO22_12'
ERROR - 2022-12-07 22:49:58 --> Query error: Unknown column 'stockinfo.stoc' in 'field list' - Invalid query: SELECT IFNULL(SUM(stockinfo.stoc), 0) as stock
FROM `stockinfo`
WHERE `stockinfo`.`productCode` = 'PRO22_12'
AND `stockinfo`.`branchCode` = 'BR22_2'
AND `stockinfo`.`stock` > '0'
ERROR - 2022-12-07 22:51:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 23:02:24 --> Severity: Warning --> Undefined array key "code" /home/myvegizc/public_html/subkaemsoftware/development/supermarket/application/controllers/Cashier/Authentication.php 39
ERROR - 2022-12-07 23:02:27 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 23:02:30 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 23:02:30 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 23:02:47 --> Query error: Unknown column 'stockinfo.stoc' in 'field list' - Invalid query: SELECT IFNULL(SUM(stockinfo.stoc), 0) as stock
FROM `stockinfo`
WHERE `stockinfo`.`productCode` = 'PRO22_12'
AND `stockinfo`.`branchCode` = 'BR22_2'
AND `stockinfo`.`stock` > '0'
ERROR - 2022-12-07 23:03:32 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 23:03:43 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 23:03:51 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 23:04:15 --> Severity: Warning --> Undefined array key "code" /home/myvegizc/public_html/subkaemsoftware/development/supermarket/application/controllers/Cashier/Authentication.php 39
ERROR - 2022-12-07 23:04:18 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 23:04:22 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 23:04:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-12-07 23:06:57 --> Query error: Unknown column 'stockinfo.stoc' in 'field list' - Invalid query: SELECT IFNULL(SUM(stockinfo.stoc), 0) as stock
FROM `stockinfo`
WHERE `stockinfo`.`productCode` = 'PRO22_12'
AND `stockinfo`.`branchCode` = 'BR22_1'
AND `stockinfo`.`stock` > '0'
ERROR - 2022-12-07 23:08:13 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'JOIN `ordertemp` ON `ordertemp`.`orderId`=`ordertempproducts`.`orderId`
WHERE `o' at line 2 - Invalid query: SELECT IFNULL(SUM(ordertempproducts.qty), 0) as qty
JOIN `ordertemp` ON `ordertemp`.`orderId`=`ordertempproducts`.`orderId`
WHERE `ordertempproducts`.`barcode` = '08135351'
AND `ordertemp`.`branchCode` = 'BR22_1'
AND `ordertemp`.`orderId` = '1'
ERROR - 2022-12-07 23:09:36 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_driver::table() /home/myvegizc/public_html/subkaemsoftware/development/supermarket/application/models/PosorderModel.php 285
ERROR - 2022-12-07 23:11:07 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_result::row_aray() /home/myvegizc/public_html/subkaemsoftware/development/supermarket/application/models/PosorderModel.php 290
ERROR - 2022-12-07 23:18:09 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-12-07 23:18:09 --> 404 Page Not Found: Assets/admin
