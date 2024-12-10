<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-09-05 10:19:29 --> Query error: Unknown column 'categorymaster.tableNumber' in 'where clause' - Invalid query: SELECT categorymaster.code,categorymaster.categoryName,categorymaster.icon,categorymaster.categorySName,categorymaster.isActive
FROM `categorymaster`
WHERE  categorymaster.tableNumber  LIKE '%n%' ESCAPE '!' 
OR  categorymaster.tableSection  LIKE '%n%' ESCAPE '!' 
AND  `categorymaster`.`isDelete` =0 OR `categorymaster`.`isDelete` IS NULL
ORDER BY `categorymaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-05 10:22:56 --> Query error: Unknown column 'unitmaster.tableNumber' in 'where clause' - Invalid query: SELECT unitmaster.code,unitmaster.unitName,unitmaster.unitSName,unitmaster.isActive
FROM `unitmaster`
WHERE  unitmaster.tableNumber  LIKE '%t%' ESCAPE '!' 
OR  unitmaster.tableSection  LIKE '%t%' ESCAPE '!' 
AND  `unitmaster`.`isDelete` =0 OR `unitmaster`.`isDelete` IS NULL
ORDER BY `unitmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-05 10:49:09 --> Severity: Notice --> Undefined variable: orderstatus /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 47
ERROR - 2022-09-05 10:49:43 --> Severity: Notice --> Undefined variable: orderstatus /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 47
ERROR - 2022-09-05 00:19:05 --> 404 Page Not Found: KitchenOrders/getKitchenOrder
ERROR - 2022-09-05 12:10:34 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:11:37 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:12:38 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:13:38 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:14:39 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:15:41 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:16:45 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 01:04:54 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 01:08:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 01:08:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 12:51:06 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:52:06 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:53:09 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:54:08 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:55:10 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:55:13 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 12:55:21 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 01:25:26 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 12:55:30 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FROM `orderstatusmaster`' at line 2 - Invalid query: SELECT orderstatusmaster.*,
FROM `orderstatusmaster`
ERROR - 2022-09-05 01:27:03 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 12:57:03 --> Query error: Unknown column 'bookorderstatuslineentries.statusLine' in 'field list' - Invalid query: SELECT ordermaster.*,bookorderstatuslineentries.statusTime,bookorderstatuslineentries.statusLine
FROM `ordermaster`
INNER JOIN `bookorderstatuslineentries` ON `bookorderstatuslineentries`.`orderCode`=`ordermaster`.`code` and `bookorderstatuslineentries`.`statusLine`=`ordermaster`.`orderStatus`
WHERE `ordermaster`.`orderStatus` in ('PND','PRO','PRE','RTS') and (`ordermaster`.`isDelete` =0 OR `ordermaster`.`isDelete` IS NULL)
ORDER BY `ordermaster`.`id` DESC
ERROR - 2022-09-05 12:57:24 --> Query error: Unknown column 'bookorderstatuslineentries.statusLine' in 'field list' - Invalid query: SELECT ordermaster.*,bookorderstatuslineentries.statusTime,bookorderstatuslineentries.statusLine
FROM `ordermaster`
INNER JOIN `bookorderstatuslineentries` ON `bookorderstatuslineentries`.`orderCode`=`ordermaster`.`code` and `bookorderstatuslineentries`.`statusLine`=`ordermaster`.`orderStatus`
WHERE `ordermaster`.`orderStatus` in ('PND','PRO','PRE','RTS') and (`ordermaster`.`isDelete` =0 OR `ordermaster`.`isDelete` IS NULL)
ORDER BY `ordermaster`.`id` DESC
ERROR - 2022-09-05 12:58:06 --> Query error: Unknown column 'bookorderstatuslineentries.statusLine' in 'field list' - Invalid query: SELECT ordermaster.*,bookorderstatuslineentries.statusTime,bookorderstatuslineentries.statusLine
FROM `ordermaster`
INNER JOIN `bookorderstatuslineentries` ON `bookorderstatuslineentries`.`orderCode`=`ordermaster`.`code` and `bookorderstatuslineentries`.`statusLine`=`ordermaster`.`orderStatus`
WHERE `ordermaster`.`orderStatus` in ('PND','PRO','PRE','RTS') and (`ordermaster`.`isDelete` =0 OR `ordermaster`.`isDelete` IS NULL)
ORDER BY `ordermaster`.`id` DESC
ERROR - 2022-09-05 12:58:36 --> Query error: Unknown column 'bookorderstatuslineentries.statusLine' in 'field list' - Invalid query: SELECT ordermaster.*,bookorderstatuslineentries.statusTime,bookorderstatuslineentries.statusLine
FROM `ordermaster`
INNER JOIN `bookorderstatuslineentries` ON `bookorderstatuslineentries`.`orderCode`=`ordermaster`.`code` and `bookorderstatuslineentries`.`statusLine`=`ordermaster`.`orderStatus`
WHERE `ordermaster`.`orderStatus` in ('PND','PRO','PRE','RTS') and (`ordermaster`.`isDelete` =0 OR `ordermaster`.`isDelete` IS NULL)
ORDER BY `ordermaster`.`id` DESC
ERROR - 2022-09-05 01:28:52 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 01:38:35 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 01:39:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 01:39:57 --> 404 Page Not Found: Recipe/index.php
ERROR - 2022-09-05 01:40:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 13:13:49 --> Severity: Notice --> Undefined property: stdClass::$itemName /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 207
ERROR - 2022-09-05 13:14:14 --> Query error: Unknown column 'itemmaster.ItemName' in 'where clause' - Invalid query: SELECT itemmaster.code,itemmaster.itemEngName,itemmaster.ingredientUnit,itemmaster.ingredientFactor,unitmaster.unitSName,unitmaster.unitName,u.unitName as ingredientUnitName,u.unitSName as ingredientUnitSName,itemmaster.storageUnit,itemmaster.itemPrice,itemmaster.isActive
FROM `itemmaster`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`itemmaster`.`storageUnit`
LEFT JOIN `unitmaster` `u` ON `u`.`code`=`itemmaster`.`ingredientUnit`
WHERE  itemmaster.ItemName  LIKE '%b%' ESCAPE '!' 
AND  `itemmaster`.`isDelete` =0 OR `itemmaster`.`isDelete` IS NULL
ORDER BY `itemmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-05 13:14:14 --> Query error: Unknown column 'itemmaster.ItemName' in 'where clause' - Invalid query: SELECT itemmaster.code,itemmaster.itemEngName,itemmaster.ingredientUnit,itemmaster.ingredientFactor,unitmaster.unitSName,unitmaster.unitName,u.unitName as ingredientUnitName,u.unitSName as ingredientUnitSName,itemmaster.storageUnit,itemmaster.itemPrice,itemmaster.isActive
FROM `itemmaster`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`itemmaster`.`storageUnit`
LEFT JOIN `unitmaster` `u` ON `u`.`code`=`itemmaster`.`ingredientUnit`
WHERE  itemmaster.ItemName  LIKE '%bre%' ESCAPE '!' 
AND  `itemmaster`.`isDelete` =0 OR `itemmaster`.`isDelete` IS NULL
ORDER BY `itemmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-05 01:46:06 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 02:01:05 --> 404 Page Not Found: Branch/index.php
ERROR - 2022-09-05 02:08:25 --> 404 Page Not Found: KitchenOrders/listOrders
ERROR - 2022-09-05 13:45:15 --> Severity: Notice --> Undefined property: stdClass::$coupanCode /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/KitchenOrders.php 85
ERROR - 2022-09-05 13:45:16 --> Severity: Notice --> Undefined property: stdClass::$coupanCode /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/KitchenOrders.php 85
ERROR - 2022-09-05 13:46:18 --> Severity: Notice --> Undefined property: stdClass::$coupanCode /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/KitchenOrders.php 85
ERROR - 2022-09-05 02:21:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 02:21:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 02:23:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 02:24:18 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 13:55:45 --> Severity: Notice --> Undefined index: tableCode /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 25
ERROR - 2022-09-05 13:55:45 --> Severity: Notice --> Undefined index: code /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 27
ERROR - 2022-09-05 13:55:45 --> Severity: Notice --> Undefined index: addDate /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 31
ERROR - 2022-09-05 02:26:39 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 13:56:47 --> Severity: Notice --> Undefined index: tableCode /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 25
ERROR - 2022-09-05 13:56:47 --> Severity: Notice --> Undefined index: code /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 27
ERROR - 2022-09-05 13:56:47 --> Severity: Notice --> Undefined index: addDate /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 31
ERROR - 2022-09-05 13:57:23 --> Severity: Notice --> Undefined offset: 0 /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Branch.php 197
ERROR - 2022-09-05 13:57:23 --> Severity: Notice --> Trying to get property 'branchName' of non-object /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Branch.php 197
ERROR - 2022-09-05 13:57:47 --> Severity: Notice --> Undefined index: tableCode /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 25
ERROR - 2022-09-05 13:57:47 --> Severity: Notice --> Undefined index: addDate /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 31
ERROR - 2022-09-05 02:28:15 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 02:31:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 14:24:37 --> Severity: error --> Exception: syntax error, unexpected end of file /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 314
ERROR - 2022-09-05 14:24:46 --> Severity: error --> Exception: syntax error, unexpected end of file /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 314
ERROR - 2022-09-05 14:25:17 --> Severity: error --> Exception: syntax error, unexpected end of file /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 314
ERROR - 2022-09-05 14:29:24 --> Query error: Unknown column 'taxgroupmaster.taxName' in 'where clause' - Invalid query: SELECT taxgroupmaster.code,taxgroupmaster.taxGroupName,taxgroupmaster.taxes,taxgroupmaster.isActive
FROM `taxgroupmaster`
WHERE  taxgroupmaster.taxName  LIKE '%G%' ESCAPE '!' 
AND  `taxgroupmaster`.`isDelete` =0 OR `taxgroupmaster`.`isDelete` IS NULL
ORDER BY `taxgroupmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-05 03:00:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 14:53:40 --> Query error: Column 'taxPer' cannot be null - Invalid query: INSERT INTO `taxes` (`taxName`, `taxPer`, `isActive`, `addID`, `addIP`) VALUES ('', NULL, '0', '1', '49.206.14.70')
ERROR - 2022-09-05 14:57:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:57:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:57:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:57:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:57:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:58:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:58:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:58:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:58:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:58:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:59:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:59:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:59:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:59:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 14:59:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:00:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:00:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:00:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:00:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:00:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:01:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:01:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:01:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:01:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:01:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:02:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:02:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:02:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:02:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:02:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:03:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:03:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:03:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:03:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:03:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:04:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:04:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:04:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:04:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:04:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:04:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:04:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:04:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:04:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:04:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:05:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:05:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:05:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:05:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:05:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 83
ERROR - 2022-09-05 15:06:45 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:06:45 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:06:45 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:06:45 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:06:45 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:07:48 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:07:48 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:07:48 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:07:48 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:07:48 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:08:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:08:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:08:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:08:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:08:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:09:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:09:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:09:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:09:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:09:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:10:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:10:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:10:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:10:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:10:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:11:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:11:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:11:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:11:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:11:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:13:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:13:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:13:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:13:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:13:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:14:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:14:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:14:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:14:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:14:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:15:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:15:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:15:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:15:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:15:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:16:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:16:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:16:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:16:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:16:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:17:16 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:17:16 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:17:16 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:17:16 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:17:16 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:18:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:18:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:18:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:18:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:18:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:19:18 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:19:18 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:19:18 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:19:18 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:19:18 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:20:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:20:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:20:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:20:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:20:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:21:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:21:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:21:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:21:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:21:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:22:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:22:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:22:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:22:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:22:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:23:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:23:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:23:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:23:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:23:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:24:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:24:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:24:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:24:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:24:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:25:08 --> Severity: Notice --> Undefined index: logged_in /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Supplier.php 94
ERROR - 2022-09-05 15:25:08 --> Severity: Notice --> Trying to access array offset on value of type null /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Supplier.php 94
ERROR - 2022-09-05 15:25:08 --> Severity: Notice --> Undefined index: logged_in /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Supplier.php 95
ERROR - 2022-09-05 15:25:08 --> Severity: Notice --> Trying to access array offset on value of type null /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Supplier.php 95
ERROR - 2022-09-05 15:25:08 --> Severity: Notice --> Undefined index: logged_in /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Supplier.php 96
ERROR - 2022-09-05 15:25:08 --> Severity: Notice --> Trying to access array offset on value of type null /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Supplier.php 96
ERROR - 2022-09-05 15:25:08 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Exceptions.php:271) /home4/snhxummy/public_html/subkaemsoftware/development/system/helpers/url_helper.php 561
ERROR - 2022-09-05 15:26:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:26:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:26:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:26:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:26:11 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:27:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:27:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:27:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:27:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:27:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:27:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:27:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:27:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:27:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:27:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:28:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:28:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:28:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:28:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:28:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:29:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:29:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:29:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:29:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:29:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:30:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:30:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:30:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:30:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:30:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:31:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:31:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:31:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:31:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:31:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:32:29 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:32:29 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:32:29 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:32:29 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:32:29 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:32:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:32:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:32:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:32:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:32:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:33:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:33:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:33:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:33:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:33:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:34:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:34:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:34:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:34:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:34:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:35:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:35:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:35:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:35:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:35:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:35:41 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:35:41 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:35:41 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:35:41 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:35:41 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 04:05:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 15:36:44 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:36:44 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:36:44 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:36:44 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:36:44 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 04:06:56 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 15:37:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:37:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:37:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:37:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:37:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:38:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:38:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:38:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:38:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:38:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:39:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:39:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:39:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:39:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:39:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:40:48 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:40:48 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:40:48 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:40:48 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:40:48 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:41:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:41:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:41:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:41:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:41:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:44:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:44:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:44:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:44:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:44:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:45:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:45:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:45:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:45:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:45:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:46:34 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:46:34 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:46:34 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:46:34 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:46:34 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:47:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:47:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:47:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:47:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:47:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:47:58 --> Severity: Notice --> Undefined property: stdClass::$itemName /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 207
ERROR - 2022-09-05 15:48:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:48:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:48:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:48:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:48:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:49:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:49:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:49:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:49:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:49:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:51:39 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:51:39 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:51:39 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:51:39 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:51:39 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:52:41 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:52:41 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:52:41 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:52:41 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:52:41 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:52:50 --> Severity: Notice --> Undefined offset: 0 /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Recipe.php 214
ERROR - 2022-09-05 15:52:50 --> Severity: Notice --> Trying to get property 'productCode' of non-object /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Recipe.php 214
ERROR - 2022-09-05 15:53:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:53:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:53:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:53:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:53:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:54:45 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:54:45 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:54:45 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:54:45 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:54:45 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:55:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:55:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:55:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:55:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:55:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:56:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:56:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:56:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:56:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:56:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:57:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:57:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:57:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:57:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:57:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:58:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:58:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:58:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:58:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 15:58:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:11:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:11:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:11:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:11:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:11:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:20:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:20:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:20:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:20:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:20:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:21:03 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:21:03 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:21:03 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:21:03 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:21:03 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:22:03 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:22:03 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:22:03 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:22:03 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:22:03 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:23:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:23:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:23:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:23:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:23:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:24:07 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:24:07 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:24:07 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:24:07 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:24:07 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:26:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:26:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:26:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:26:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:26:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:27:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:27:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:27:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:27:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:27:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:28:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:28:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:28:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:28:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:28:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:29:16 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:29:16 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:29:16 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:29:16 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:29:16 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:31:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:31:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:31:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:31:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:31:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:32:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:32:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:32:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:32:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:32:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:33:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:33:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:33:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:33:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:33:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:34:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:34:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:34:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:34:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:34:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:35:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:35:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:35:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:35:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:35:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:36:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:36:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:36:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:36:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:36:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:37:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:37:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:37:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:37:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:37:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:38:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:38:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:38:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:38:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:38:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:40:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:40:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:40:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:40:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:40:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:40:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:40:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:40:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:40:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:40:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:41:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:41:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:41:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:41:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:41:32 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:42:34 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:42:34 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:42:34 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:42:34 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:42:34 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:43:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:43:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:43:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:43:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:43:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 05:13:20 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 16:44:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:44:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:44:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:44:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:44:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 05:14:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 16:44:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:44:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:44:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:44:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:44:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:45:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:45:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:45:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:45:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:45:51 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:46:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:46:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:46:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:46:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:46:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:47:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:47:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:47:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:47:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:47:52 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:48:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:48:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:48:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:48:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:48:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:49:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:49:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:49:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:49:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:49:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:50:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:50:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:50:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:50:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:50:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:51:22 --> Query error: Unknown column 'preparingMinutes' in 'field list' - Invalid query: UPDATE `ordermaster` SET `orderStatus` = 'PRO', `editID` = 'USR22_5', `editDate` = '2022-09-05 16:51:22', `preparingMinutes` = 25
WHERE `code` = 'ORDER_1'
ERROR - 2022-09-05 16:52:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:52:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:52:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:52:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:52:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:53:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:53:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:53:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:53:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:53:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 05:23:36 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 16:53:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:53:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:53:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:53:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:53:36 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 05:24:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 16:54:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:54:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:54:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:54:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:54:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:54:31 --> Query error: Unknown column 'statusPutCode' in 'field list' - Invalid query: INSERT INTO `bookorderstatuslineentries` (`orderCode`, `statusPutCode`, `orderStatus`, `statusTime`, `statusTest`, `isActive`, `addID`, `addDate`, `addIP`) VALUES ('ORDER_1', 'USR22_5', 'PRO', '2022-09-05 16:54:31', 'Food is processing', 1, 'USR22_5', '2022-09-05 16:54:31', '152.57.192.85')
ERROR - 2022-09-05 05:25:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 16:56:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:56:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:56:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:56:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:56:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:56:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:56:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:56:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:56:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:56:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:56:49 --> Query error: Unknown column 'statusTest' in 'field list' - Invalid query: INSERT INTO `bookorderstatuslineentries` (`orderCode`, `orderStatus`, `statusTime`, `statusTest`, `isActive`, `addID`, `addDate`, `addIP`) VALUES ('ORDER_1', 'PRO', '2022-09-05 16:56:49', 'Food is processing', 1, 'USR22_5', '2022-09-05 16:56:49', '152.57.192.85')
ERROR - 2022-09-05 16:56:57 --> Query error: Unknown column 'statusTest' in 'field list' - Invalid query: INSERT INTO `bookorderstatuslineentries` (`orderCode`, `orderStatus`, `statusTime`, `statusTest`, `isActive`, `addID`, `addDate`, `addIP`) VALUES ('ORDER_1', 'PRO', '2022-09-05 16:56:57', 'Food is processing', 1, 'USR22_5', '2022-09-05 16:56:57', '152.57.192.85')
ERROR - 2022-09-05 05:27:04 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 16:57:22 --> Query error: Unknown column 'statusTest' in 'field list' - Invalid query: INSERT INTO `bookorderstatuslineentries` (`orderCode`, `orderStatus`, `statusTime`, `statusTest`, `isActive`, `addID`, `addDate`, `addIP`) VALUES ('ORDER_1', 'PRO', '2022-09-05 16:57:22', 'Food is processing', 1, 'USR22_5', '2022-09-05 16:57:22', '152.57.192.85')
ERROR - 2022-09-05 05:28:21 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 16:58:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:58:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:58:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:58:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:58:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:58:30 --> Query error: Table 'snhxummy_test_kaemsoftware_restaurent.rordermaster' doesn't exist - Invalid query: SELECT ordermaster.*
FROM `rordermaster`
WHERE `ordermaster`.`code` = 'ORDER_1'
ERROR - 2022-09-05 16:59:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:27 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 05:29:35 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 16:59:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 16:59:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:00:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:00:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:00:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:00:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:00:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:00:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:00:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:00:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:00:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:00:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 05:32:20 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 17:02:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:02:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:02:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:02:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:02:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:02:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:02:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:02:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:02:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:02:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:24 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 05:33:28 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 17:03:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 05:33:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 17:03:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:03:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:04:58 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:04:58 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:04:58 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:04:58 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:04:58 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:04:58 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:04:58 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:04:58 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:04:58 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:04:58 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 05:36:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 17:06:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:06:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:06:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:06:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:06:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:06:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:06:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:06:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:06:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:06:43 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:07:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:07:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:07:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:07:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:07:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:07:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:07:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:07:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:07:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:07:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:08:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:08:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:08:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:08:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:08:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:08:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:08:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:08:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:08:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:08:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 17:10:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:10:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:10:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:10:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:10:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:10:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:10:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:10:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:10:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:10:54 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 05:41:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 17:11:55 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:11:55 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:11:55 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:11:55 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:11:55 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:11:55 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:11:55 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:11:55 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:11:55 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:11:55 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 05:42:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 17:12:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:12:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:12:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:12:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:12:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:12:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:12:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:12:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:12:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:12:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:14:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:14:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:14:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:14:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:14:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:14:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:14:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:14:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:14:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:14:05 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:15:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:15:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:15:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:15:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:15:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:15:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:15:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:15:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:15:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:15:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:16:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:16:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:16:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:16:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:16:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:16:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:16:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:16:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:16:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:16:06 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:17:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:17:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:17:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:17:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:17:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:17:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:17:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:17:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:17:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:17:08 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:18:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:18:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:18:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:18:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:18:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:18:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:18:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:18:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:18:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 17:18:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:08:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:08:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:08:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:08:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:08:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:08:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:08:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:08:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:08:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:08:15 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 06:38:26 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 18:09:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:09:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:10:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:10:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:10:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:10:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:10:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:10:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:10:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:10:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:10:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:10:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:11:14 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:11:14 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:11:14 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:11:14 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:11:14 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:11:14 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:11:14 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:11:14 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:11:14 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:11:14 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 82
ERROR - 2022-09-05 18:12:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:12:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:12:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:12:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:12:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:12:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:12:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:12:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:12:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:12:56 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 06:43:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 18:14:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 06:44:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 18:14:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:14:42 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 91
ERROR - 2022-09-05 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:15:47 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 06:47:02 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 18:17:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:17:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:17:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:17:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:17:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:17:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:17:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:17:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:17:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:17:02 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:18:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:18:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:18:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:18:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:18:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:18:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:18:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:18:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:18:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:18:10 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:19:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:19:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:19:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:19:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:19:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:19:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:19:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:19:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:19:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:19:13 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:20:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:20:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:20:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:20:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:20:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:20:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:20:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:20:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:20:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 18:20:35 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 80
ERROR - 2022-09-05 06:51:46 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 18:21:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:21:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:21:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:21:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:21:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:21:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:21:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:21:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:21:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:21:46 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:22:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:22:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:22:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:22:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:22:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:22:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:22:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:22:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:22:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:22:49 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:23:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:23:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:23:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:23:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:23:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:23:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:23:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:23:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:23:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:23:53 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:25:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:25:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:25:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:25:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:25:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:25:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:25:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:25:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:25:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:25:01 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:50 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:50 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:50 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:50 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:50 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:50 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:50 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:50 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:50 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:50 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:27:57 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:28:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:28:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:28:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:28:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:28:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:28:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:28:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:28:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:28:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:28:59 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:00 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:17 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:30:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:09 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:31:19 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:32:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:32:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:32:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:32:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:32:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:32:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:32:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:32:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:32:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:32:20 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:33:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:33:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:33:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:33:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:33:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:33:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:33:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:33:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:33:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:33:21 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:34:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:34:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:34:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:34:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:34:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:34:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:34:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:34:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:34:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:34:22 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:35:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:35:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:35:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:35:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:35:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:35:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:35:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:35:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:35:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:35:23 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:36:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:36:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:36:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:36:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:36:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:36:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:36:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:36:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:36:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:36:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:37:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:37:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:37:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:37:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:37:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:37:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:37:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:37:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:37:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:37:25 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:38:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:38:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:38:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:38:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:38:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:38:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:38:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:38:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:38:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:38:26 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:39:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:39:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:39:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:39:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:39:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:39:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:39:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:39:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:39:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:39:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:40:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:40:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:40:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:40:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:40:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:40:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:40:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:40:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:40:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:40:28 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:41:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:41:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:41:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:41:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:41:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:41:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:41:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:41:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:41:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:41:30 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:42:31 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:42:31 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:42:31 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:42:31 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:42:31 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:42:31 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:42:31 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:42:31 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:42:31 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 18:42:31 --> Severity: Notice --> Undefined property: stdClass::$statusSNam /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/kitchenorders/kitchenorder.php 79
ERROR - 2022-09-05 22:58:09 --> 404 Page Not Found: Order/edit-custpos.php
ERROR - 2022-09-05 23:31:51 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 23:32:58 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 23:34:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 23:35:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 23:37:04 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 23:37:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 23:50:51 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-05 23:56:17 --> 404 Page Not Found: Assets/admin
