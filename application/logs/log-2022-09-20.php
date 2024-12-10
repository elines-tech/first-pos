<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-09-20 10:31:30 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:31:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:34:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:37:23 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:40:52 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:41:20 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:42:03 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:43:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:45:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:45:36 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:48:04 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:53:59 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 10:56:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 09:59:25 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.itemmaster' doesn't exist - Invalid query: SELECT count(distinct itemmaster.code) as cnt
FROM `itemmaster`
WHERE `itemmaster`.`isActive` = 1
ERROR - 2022-09-20 09:59:25 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.itemmaster' doesn't exist - Invalid query: SELECT `categorymaster`.`categoryName`, SUM(stock) as totalStock
FROM `stockinfo`
JOIN `itemmaster` ON `itemmaster`.`code` = `stockinfo`.`itemCode`
JOIN `categorymaster` ON `categorymaster`.`code` = `itemmaster`.`categoryCode`
GROUP BY `categorymaster`.`code`
ERROR - 2022-09-20 09:59:25 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.inwardentries' doesn't exist - Invalid query: SELECT inwardentries.code,inwardentries.branchCode,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive
FROM `inwardentries`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`inwardentries`.`branchCode`
INNER JOIN `suppliermaster` ON `suppliermaster`.`code`=`inwardentries`.`supplierCode`
WHERE `inwardentries`.`isDelete` =0 OR `inwardentries`.`isDelete` IS NULL
ORDER BY `inwardentries`.`id` DESC
 LIMIT 5
ERROR - 2022-09-20 10:00:21 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.taxgroupmaster' doesn't exist - Invalid query: SELECT * FROM `taxgroupmaster` WHERE `isActive` = '1'
ERROR - 2022-09-20 11:59:44 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.suppliermaster' doesn't exist - Invalid query: SELECT suppliermaster.*
FROM `suppliermaster`
WHERE (suppliermaster.isDelete =0 OR `suppliermaster`.`isDelete` IS NULL)
ORDER BY `suppliermaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-20 12:20:59 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.productcategorymaster' doesn't exist - Invalid query: SELECT productmaster.*,productcategorymaster.categoryName,taxgroupmaster.taxGroupName,productsubcategorymaster.subcategoryName
FROM `productmaster`
INNER JOIN `taxgroupmaster` ON `taxgroupmaster`.`code`=`productmaster`.`productTaxGrp`
LEFT OUTER JOIN `productcategorymaster` ON `productcategorymaster`.`code`=`productmaster`.`productCategory`
LEFT OUTER JOIN `productsubcategorymaster` ON `productsubcategorymaster`.`code`=`productmaster`.`subcategory`
WHERE (productmaster.isDelete =0 OR `productmaster`.`isDelete` IS NULL)
ORDER BY `productmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-20 15:38:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 13:09:35 --> Query error: Unknown column 'productMethod' in 'field list' - Invalid query: INSERT INTO `productmaster` (`productEngName`, `productArbName`, `productHinName`, `productUrduName`, `productCategory`, `productMethod`, `productPrice`, `productTaxGrp`, `productEngDesc`, `productArbDesc`, `productHinDesc`, `productUrduDesc`, `isActive`, `addID`, `addIP`) VALUES ('Perfume', 'برفم', 'परफ्यूम', 'پرفومے', 'CAT_16', NULL, '250', 'TAG_28', '', '', '', '', 1, 'USR22_1', '106.210.176.18')
ERROR - 2022-09-20 13:19:13 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.inwardentries' doesn't exist - Invalid query: SELECT inwardentries.code,inwardentries.branchCode,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive
FROM `inwardentries`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`inwardentries`.`branchCode`
INNER JOIN `suppliermaster` ON `suppliermaster`.`code`=`inwardentries`.`supplierCode`
WHERE `inwardentries`.`isDelete` =0 OR `inwardentries`.`isDelete` IS NULL
ORDER BY `inwardentries`.`id` DESC
 LIMIT 10
ERROR - 2022-09-20 13:30:01 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.inwardentries' doesn't exist - Invalid query: SELECT inwardentries.code,inwardentries.branchCode,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive
FROM `inwardentries`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`inwardentries`.`branchCode`
INNER JOIN `suppliermaster` ON `suppliermaster`.`code`=`inwardentries`.`supplierCode`
WHERE `inwardentries`.`isDelete` =0 OR `inwardentries`.`isDelete` IS NULL
ORDER BY `inwardentries`.`id` DESC
 LIMIT 10
ERROR - 2022-09-20 16:00:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:01:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:03:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:03:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:03:55 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:04:04 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:04:26 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:04:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:04:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:05:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 13:38:42 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.inwardentries' doesn't exist - Invalid query: SELECT inwardentries.code,inwardentries.branchCode,branchmaster.branchName,suppliermaster.supplierName,inwardentries.inwardDate,inwardentries.total, inwardentries.isActive
FROM `inwardentries`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`inwardentries`.`branchCode`
INNER JOIN `suppliermaster` ON `suppliermaster`.`code`=`inwardentries`.`supplierCode`
WHERE `inwardentries`.`isDelete` =0 OR `inwardentries`.`isDelete` IS NULL
ORDER BY `inwardentries`.`id` DESC
 LIMIT 10
ERROR - 2022-09-20 13:38:46 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.itemmaster' doesn't exist - Invalid query: SELECT * FROM `itemmaster` WHERE `isActive` = '1'
ERROR - 2022-09-20 13:40:48 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.itemmaster' doesn't exist - Invalid query: SELECT * FROM `itemmaster` WHERE `isActive` = '1'
ERROR - 2022-09-20 13:41:51 --> Severity: Notice --> Undefined property: stdClass::$itemEngName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/add.php 114
ERROR - 2022-09-20 13:41:51 --> Severity: Notice --> Undefined property: stdClass::$itemEngName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/add.php 114
ERROR - 2022-09-20 13:41:51 --> Severity: Notice --> Undefined property: stdClass::$itemEngName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/add.php 114
ERROR - 2022-09-20 16:33:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:34:48 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:36:01 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 14:06:07 --> Severity: Notice --> Undefined index: unitmaster /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/GlobalModel.php 206
ERROR - 2022-09-20 16:38:30 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:43:43 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:44:57 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 14:16:23 --> Severity: Notice --> Undefined variable: itemCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Inward.php 276
ERROR - 2022-09-20 14:16:23 --> Severity: Notice --> Undefined variable: itemCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Inward.php 276
ERROR - 2022-09-20 16:46:26 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 14:16:29 --> Query error: Unknown column 'inwardlineentries.itemUom' in 'on clause' - Invalid query: SELECT inwardlineentries.*, unitmaster.unitName
FROM `inwardlineentries`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`inwardlineentries`.`itemUom`
WHERE `inwardlineentries`.`inwardCode` = 'IN22_12'
AND `inwardlineentries`.`isActive` = 1
ERROR - 2022-09-20 16:49:03 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:55:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:55:39 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:56:06 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:56:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 124
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined property: stdClass::$itemEngName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 127
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 124
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined property: stdClass::$itemEngName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 127
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 124
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined property: stdClass::$itemEngName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 127
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemQty /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 151
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemPrice /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 154
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 124
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined property: stdClass::$itemEngName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 127
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 124
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined property: stdClass::$itemEngName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 127
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 124
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined property: stdClass::$itemEngName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 127
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemUom /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 140
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemQty /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 151
ERROR - 2022-09-20 14:26:49 --> Severity: Notice --> Undefined index: itemPrice /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/views/dashboard/inward/view.php 154
ERROR - 2022-09-20 16:56:51 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:58:58 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 16:59:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:00:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:01:39 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:01:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:03:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:03:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:03:57 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:05:30 --> Severity: error --> Exception: syntax error, unexpected '$actionHtml' (T_VARIABLE), expecting ')' /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Inward.php 75
ERROR - 2022-09-20 17:05:40 --> 404 Page Not Found: Inward/listRecords
ERROR - 2022-09-20 17:05:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 14:35:44 --> Severity: Notice --> Undefined property: stdClass::$batchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Inward.php 70
ERROR - 2022-09-20 17:06:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:06:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:06:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:07:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:07:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 14:37:17 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.itemmaster' doesn't exist - Invalid query: SELECT stockinfo.code,stockinfo.itemCode,itemmaster.itemEngName,unitmaster.unitName,unitmaster.unitSName,stockinfo.stock,branchmaster.branchName,stockinfo.addDate
FROM `stockinfo`
INNER JOIN `itemmaster` ON `itemmaster`.`code`=`stockinfo`.`itemCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`stockinfo`.`unitCode`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`stockinfo`.`branchCode`
WHERE `stockinfo`.`isActive` = 1
ORDER BY `stockinfo`.`id` DESC
 LIMIT 10
ERROR - 2022-09-20 17:10:15 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 14:40:15 --> Severity: Notice --> Undefined variable: branchCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Stock.php 34
ERROR - 2022-09-20 14:40:15 --> Severity: Notice --> Undefined property: stdClass::$batchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Stock.php 52
ERROR - 2022-09-20 14:40:15 --> Severity: Notice --> Undefined property: stdClass::$batchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Stock.php 52
ERROR - 2022-09-20 14:40:15 --> Severity: Notice --> Undefined property: stdClass::$batchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Stock.php 52
ERROR - 2022-09-20 17:12:46 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:13:27 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:14:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:15:26 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 14:45:26 --> Severity: Notice --> Undefined property: stdClass::$batchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Stock.php 53
ERROR - 2022-09-20 14:45:26 --> Severity: Notice --> Undefined property: stdClass::$batchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Stock.php 53
ERROR - 2022-09-20 14:45:26 --> Severity: Notice --> Undefined property: stdClass::$batchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Stock.php 53
ERROR - 2022-09-20 17:16:13 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:16:58 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:29:35 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:29:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:30:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:30:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:31:21 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:31:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:32:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 15:02:13 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.itemmaster' doesn't exist - Invalid query: SELECT * FROM `itemmaster` WHERE `isActive` = '1'
ERROR - 2022-09-20 17:35:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:38:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:39:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 17:39:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 15:24:19 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Inward.php 303
ERROR - 2022-09-20 17:55:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 18:07:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 18:09:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 18:09:58 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 18:10:02 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 18:10:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 18:10:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 15:40:13 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.itemmaster' doesn't exist - Invalid query: SELECT * FROM `itemmaster` WHERE `isActive` = '1'
ERROR - 2022-09-20 15:41:29 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.itemmaster' doesn't exist - Invalid query: SELECT * FROM `itemmaster` WHERE `isActive` = '1'
ERROR - 2022-09-20 15:42:28 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.itemmaster' doesn't exist - Invalid query: SELECT * FROM `itemmaster` WHERE `isActive` = '1'
ERROR - 2022-09-20 15:42:43 --> Query error: Unknown column 'itemmaster.storageUnit' in 'on clause' - Invalid query: SELECT returnentries.*, inwardentries.inwardDate, inwardentries.branchCode, inwardentries.supplierCode, inwardentries.code as inwardCode, unitmaster.unitName
FROM `returnentries`
INNER JOIN `inwardentries` ON `inwardentries`.`code`=`returnentries`.`inwardCode`
INNER JOIN `productmaster` ON `productmaster`.`code`=`returnentries`.`productCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`itemmaster`.`storageUnit`
WHERE `returnentries`.`inwardCode` = 'IN22_12'
ORDER BY `returnentries`.`id` DESC
ERROR - 2022-09-20 18:13:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 18:16:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 18:16:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-20 18:17:48 --> 404 Page Not Found: Assets/admin
