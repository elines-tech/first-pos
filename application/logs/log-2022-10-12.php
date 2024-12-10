<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-10-12 10:57:31 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 11:01:13 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 11:01:13 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:15:05 --> 404 Page Not Found: Order/listRecords
ERROR - 2022-10-12 11:15:37 --> 404 Page Not Found: OrderList/getOrderList
ERROR - 2022-10-12 11:24:36 --> 404 Page Not Found: Upload/productImage
ERROR - 2022-10-12 11:25:10 --> 404 Page Not Found: Upload/subcategory
ERROR - 2022-10-12 11:25:55 --> 404 Page Not Found: Upload/subcategory
ERROR - 2022-10-12 11:25:56 --> 404 Page Not Found: Upload/subcategory
ERROR - 2022-10-12 11:26:09 --> 404 Page Not Found: Upload/productImage
ERROR - 2022-10-12 11:31:19 --> 404 Page Not Found: Upload/productImage
ERROR - 2022-10-12 11:31:39 --> 404 Page Not Found: Upload/productImage
ERROR - 2022-10-12 09:03:02 --> Query error: Unknown column 'productmaster.productCode' in 'where clause' - Invalid query: SELECT `productmaster`.`ingredientFactor`
FROM `productmaster`
WHERE `productmaster`.`productCode` = 'PRO22_1'
ERROR - 2022-10-12 11:49:28 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:49:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:51:03 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:51:39 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:53:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:53:08 --> 404 Page Not Found: OrderList/getOrderList
ERROR - 2022-10-12 11:57:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 09:27:09 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.tablemaster' doesn't exist - Invalid query: SELECT ordermaster.*,branchmaster.branchName,tablemaster.tableNumber,sectorzonemaster.zoneName
FROM `ordermaster`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`ordermaster`.`branchCode`
INNER JOIN `tablemaster` ON `tablemaster`.`code`=`ordermaster`.`tableNumber`
INNER JOIN `sectorzonemaster` ON `sectorzonemaster`.`id`=`ordermaster`.`tableSection`
WHERE (ordermaster.isDelete =0 OR `ordermaster`.`isDelete` IS NULL)
ORDER BY `ordermaster`.`id` DESC
 LIMIT 10
ERROR - 2022-10-12 12:13:55 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 09:43:55 --> Query error: Unknown column 'ordermaster.isDelete' in 'where clause' - Invalid query: SELECT ordermaster.*,branchmaster.branchName,usermaster.userEmpNo as cashier,countermaster.counterName
FROM `ordermaster`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`ordermaster`.`branchCode`
INNER JOIN `usermaster` ON `usermaster`.`code`=`ordermaster`.`addID`
INNER JOIN `countermaster` ON `countermaster`.`code`=`ordermaster`.`counter`
WHERE (ordermaster.isDelete =0 OR `ordermaster`.`isDelete` IS NULL)
ORDER BY `ordermaster`.`id` DESC
 LIMIT 10
ERROR - 2022-10-12 12:14:55 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 09:44:55 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT ordermaster.*,branchmaster.branchName,usermaster.userEmpNo as cashier,countermaster.counterName
FROM `ordermaster`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`ordermaster`.`branchCode`
INNER JOIN `usermaster` ON `usermaster`.`code`=`ordermaster`.`addID`
INNER JOIN `countermaster` ON `countermaster`.`code`=`ordermaster`.`counter`
ORDER BY `ordermaster`.`id` DESC
 LIMIT 10
ERROR - 2022-10-12 12:16:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 09:46:42 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT ordermaster.*,branchmaster.branchName,usermaster.userEmpNo as cashier,countermaster.counterName
FROM `ordermaster`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`ordermaster`.`branchCode`
INNER JOIN `usermaster` ON `usermaster`.`code`=`ordermaster`.`addID`
INNER JOIN `countermaster` ON `countermaster`.`code`=`ordermaster`.`counter`
ORDER BY `ordermaster`.`id` DESC
 LIMIT 10
ERROR - 2022-10-12 09:47:16 --> Query error: Unknown column 'productmaster.productCode' in 'where clause' - Invalid query: SELECT `productmaster`.`ingredientFactor`
FROM `productmaster`
WHERE `productmaster`.`productCode` = 'PRO22_1'
ERROR - 2022-10-12 12:17:23 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 09:47:30 --> Query error: Unknown column 'productmaster.productCode' in 'where clause' - Invalid query: SELECT `productmaster`.`ingredientFactor`
FROM `productmaster`
WHERE `productmaster`.`productCode` = 'PRO22_1'
ERROR - 2022-10-12 09:48:40 --> Severity: Notice --> Undefined index: id /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 375
ERROR - 2022-10-12 09:48:40 --> Severity: Notice --> Undefined index: id /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 375
ERROR - 2022-10-12 12:18:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 09:48:43 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT ordermaster.*,branchmaster.branchName,usermaster.userEmpNo as cashier,countermaster.counterName
FROM `ordermaster`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`ordermaster`.`branchCode`
INNER JOIN `usermaster` ON `usermaster`.`code`=`ordermaster`.`addID`
INNER JOIN `countermaster` ON `countermaster`.`code`=`ordermaster`.`counter`
ORDER BY `ordermaster`.`id` DESC
 LIMIT 10
ERROR - 2022-10-12 12:19:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 09:49:50 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT ordermaster.*,branchmaster.branchName,usermaster.userEmpNo as cashier,countermaster.counterName
FROM `ordermaster`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`ordermaster`.`branchCode`
INNER JOIN `usermaster` ON `usermaster`.`code`=`ordermaster`.`addID`
INNER JOIN `countermaster` ON `countermaster`.`code`=`ordermaster`.`counter`
ORDER BY `ordermaster`.`id` DESC
 LIMIT 10
ERROR - 2022-10-12 12:19:53 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 12:20:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:20:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined index: code /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 375
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined variable: stockId /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 383
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined index: code /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 375
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined variable: stockId /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 383
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined index: code /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 375
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined variable: stockId /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 383
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined index: code /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 375
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined variable: stockId /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 383
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined index: code /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 375
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined variable: stockId /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 383
ERROR - 2022-10-12 09:51:22 --> Severity: Notice --> Undefined index: code /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 375
ERROR - 2022-10-12 12:22:25 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 12:22:27 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:24:24 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:35:01 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 12:35:03 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:36:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:40:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:40:54 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 12:40:54 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:41:27 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 12:41:28 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:42:30 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 12:42:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:46:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 10:17:41 --> Severity: Notice --> Array to string conversion /home4/snhxummy/public_html/subkaemsoftware/supermarket/system/database/DB_query_builder.php 2442
ERROR - 2022-10-12 10:17:41 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: UPDATE `stockinfo` SET `stock` = '0'
WHERE `code` = Array
ERROR - 2022-10-12 12:51:03 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 12:51:04 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:52:08 --> 404 Page Not Found: Upload/productImage
ERROR - 2022-10-12 12:52:37 --> 404 Page Not Found: Upload/productImage
ERROR - 2022-10-12 12:56:22 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 12:56:23 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 10:26:57 --> check =>BR22_27 product {"productCode":"PRO22_1","variantCode":"PVAR22_6","barcode":"12345677","unit":"UOM_6","price":"520.00","qty":"3.000","amount":"1560.00","discountPrice":"30.00","discount":"90.00","taxPercent":"27.00","tax":"396.90","totalPrice":"1866.90"}
ERROR - 2022-10-12 10:28:02 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 183
ERROR - 2022-10-12 10:28:02 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 183
ERROR - 2022-10-12 10:28:02 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 183
ERROR - 2022-10-12 10:28:02 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 183
ERROR - 2022-10-12 10:28:02 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 183
ERROR - 2022-10-12 10:28:02 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 183
ERROR - 2022-10-12 10:29:40 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:29:40 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:29:40 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:29:40 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:29:40 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:29:40 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 13:00:27 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 10:30:31 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:30:31 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:30:31 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:30:31 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:30:31 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:30:31 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$unitName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 186
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$unitName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 186
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$unitName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 186
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$unitName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 186
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$unitName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 186
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:31:30 --> Severity: Notice --> Undefined property: stdClass::$unitName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 186
ERROR - 2022-10-12 10:32:03 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:03 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:03 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:03 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:03 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:03 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:38 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:38 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:38 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:38 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:38 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 10:32:38 --> Severity: Notice --> Undefined property: stdClass::$branchNo /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Barcode.php 184
ERROR - 2022-10-12 13:07:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:09:56 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:10:29 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:10:56 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:11:29 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:11:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:11:57 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:11:57 --> 404 Page Not Found: OrderList/getOrderDetails
ERROR - 2022-10-12 13:13:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:13:50 --> 404 Page Not Found: OrderList/getOrderDetails
ERROR - 2022-10-12 13:14:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:14:47 --> 404 Page Not Found: OrderList/getOrderDetails
ERROR - 2022-10-12 13:15:16 --> 404 Page Not Found: OrderList/getOrderDetails
ERROR - 2022-10-12 13:15:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:18:59 --> 404 Page Not Found: OrderList/getOrderDetails
ERROR - 2022-10-12 13:19:37 --> 404 Page Not Found: OrderList/getOrderDetails
ERROR - 2022-10-12 13:20:13 --> 404 Page Not Found: OrderList/getOrderDetails
ERROR - 2022-10-12 13:20:40 --> 404 Page Not Found: OrderList/getOrderDetails
ERROR - 2022-10-12 13:21:00 --> 404 Page Not Found: OrderList/getOrderDetails
ERROR - 2022-10-12 13:21:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:21:41 --> 404 Page Not Found: OrderList/getOrderDetails
ERROR - 2022-10-12 13:21:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:22:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 10:52:07 --> Query error: Unknown column 'orderlineentries.addons' in 'field list' - Invalid query: SELECT orderlineentries.orderCode,orderlineentries.addons,orderlineentries.comboProducts,orderlineentries.productCode,orderlineentries.productPrice,orderlineentries.productQty,orderlineentries.totalPrice,orderlineentries.isActive,productmaster.productEngName,productmaster.productArbName,productmaster.productHinName,productmaster.productUrduName,productmaster.productImage
FROM `orderlineentries`
LEFT JOIN `productmaster` ON `productmaster`.`code`=`orderlineentries`.`productCode`
WHERE `orderlineentries`.`orderCode` = 'ORD22_3'
AND `orderlineentries`.`isActive` = 1
GROUP BY `orderlineentries`.`productCode`
ORDER BY `orderlineentries`.`id` ASC
 LIMIT 10
ERROR - 2022-10-12 13:36:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:06:41 --> Query error: Unknown column 'orderlineentries.isActive' in 'where clause' - Invalid query: SELECT orderlineentries.orderCode,orderlineentries.*,productvariants.variantName,productmaster.productEngName,productmaster.productArbName,unitmaster.unitSName,productmaster.productHinName,productmaster.productUrduName,productmaster.productImage
FROM `orderlineentries`
INNER JOIN `productmaster` ON `productmaster`.`code`=`orderlineentries`.`productCode`
LEFT JOIN `productvariants` ON `productvariants`.`code`=`orderlineentries`.`variantCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`orderlineentries`.`unit`
WHERE `orderlineentries`.`orderCode` = 'ORD22_3'
AND `orderlineentries`.`isActive` = 1
GROUP BY `orderlineentries`.`productCode`
ORDER BY `orderlineentries`.`id` ASC
 LIMIT 10
ERROR - 2022-10-12 11:07:21 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT orderlineentries.orderCode,orderlineentries.*,productvariants.variantName,productmaster.productEngName,productmaster.productArbName,unitmaster.unitSName,productmaster.productHinName,productmaster.productUrduName,productmaster.productImage
FROM `orderlineentries`
INNER JOIN `productmaster` ON `productmaster`.`code`=`orderlineentries`.`productCode`
LEFT JOIN `productvariants` ON `productvariants`.`code`=`orderlineentries`.`variantCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`orderlineentries`.`unit`
WHERE `orderlineentries`.`orderCode` = 'ORD22_3'
GROUP BY `orderlineentries`.`productCode`
ORDER BY `orderlineentries`.`id` ASC
 LIMIT 10
ERROR - 2022-10-12 13:37:21 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:37:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:39:43 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:09:43 --> Query error: Illegal mix of collations (utf8_unicode_ci,IMPLICIT) and (utf8_general_ci,IMPLICIT) for operation '=' - Invalid query: SELECT orderlineentries.orderCode,orderlineentries.*,productvariants.variantName,productmaster.productEngName,productmaster.productArbName,unitmaster.unitSName,productmaster.productHinName,productmaster.productUrduName,productmaster.productImage
FROM `orderlineentries`
INNER JOIN `productmaster` ON `productmaster`.`code`=`orderlineentries`.`productCode`
LEFT JOIN `productvariants` ON `productvariants`.`code`=`orderlineentries`.`variantCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`orderlineentries`.`unit`
WHERE `orderlineentries`.`orderCode` = 'ORD22_3'
GROUP BY `orderlineentries`.`productCode`
ORDER BY `orderlineentries`.`id` ASC
 LIMIT 10
ERROR - 2022-10-12 13:41:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:11:53 --> Query error: Illegal mix of collations (utf8_unicode_ci,IMPLICIT) and (utf8_general_ci,IMPLICIT) for operation '=' - Invalid query: SELECT orderlineentries.orderCode,orderlineentries.*,productvariants.variantName,productmaster.productEngName,productmaster.productArbName,unitmaster.unitSName,productmaster.productHinName,productmaster.productUrduName,productmaster.productImage
FROM `orderlineentries`
INNER JOIN `productmaster` ON `productmaster`.`code`=`orderlineentries`.`productCode`
LEFT JOIN `productvariants` ON `productvariants`.`code`=`orderlineentries`.`variantCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`orderlineentries`.`unit`
WHERE `orderlineentries`.`orderCode` = 'ORD22_3'
GROUP BY `orderlineentries`.`productCode`
ORDER BY `orderlineentries`.`id` ASC
 LIMIT 10
ERROR - 2022-10-12 13:43:57 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:13:57 --> Query error: Illegal mix of collations (utf8_unicode_ci,IMPLICIT) and (utf8_general_ci,IMPLICIT) for operation '=' - Invalid query: SELECT orderlineentries.orderCode,orderlineentries.*,productvariants.variantName,productmaster.productEngName,productmaster.productArbName,unitmaster.unitSName,productmaster.productHinName,productmaster.productUrduName,productmaster.productImage
FROM `orderlineentries`
INNER JOIN `productmaster` ON `productmaster`.`code`=`orderlineentries`.`productCode`
LEFT JOIN `productvariants` ON `productvariants`.`code`=`orderlineentries`.`variantCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`orderlineentries`.`unit`
WHERE `orderlineentries`.`orderCode` = 'ORD22_3'
GROUP BY `orderlineentries`.`productCode`
ORDER BY `orderlineentries`.`id` ASC
 LIMIT 10
ERROR - 2022-10-12 13:46:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:16:49 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT productmaster.*, productvariants.variantName, productvariants.code as variantCode, productvariants.sku as variantSKU
FROM `productmaster`
LEFT JOIN `productvariants` ON `productvariants`.`productCode`=`productmaster`.`code`
WHERE `productmaster`.`isActive` = 1
AND `productvariants`.`isActive` = 1
ERROR - 2022-10-12 11:16:54 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT productmaster.*, productvariants.variantName, productvariants.code as variantCode, productvariants.sku as variantSKU
FROM `productmaster`
LEFT JOIN `productvariants` ON `productvariants`.`productCode`=`productmaster`.`code`
WHERE `productmaster`.`isActive` = 1
AND `productvariants`.`isActive` = 1
ERROR - 2022-10-12 13:47:04 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:17:17 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT productmaster.*, productvariants.variantName, productvariants.code as variantCode, productvariants.sku as variantSKU
FROM `productmaster`
LEFT JOIN `productvariants` ON `productvariants`.`productCode`=`productmaster`.`code`
WHERE `productmaster`.`isActive` = 1
ERROR - 2022-10-12 13:47:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:47:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:17:53 --> Severity: Notice --> Undefined variable: productImage /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Order.php 117
ERROR - 2022-10-12 13:50:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:20:18 --> Severity: Notice --> Undefined variable: productImage /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Order.php 120
ERROR - 2022-10-12 13:50:56 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:54:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:55:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:55:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:56:00 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:56:35 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 11:26:37 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT productmaster.*, productvariants.variantName, productvariants.code as variantCode, productvariants.sku as variantSKU
FROM `productmaster`
LEFT JOIN `productvariants` ON `productvariants`.`productCode`=`productmaster`.`code`
WHERE `productmaster`.`isActive` = 1
ERROR - 2022-10-12 11:26:48 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT productmaster.*, productvariants.variantName, productvariants.code as variantCode, productvariants.sku as variantSKU
FROM `productmaster`
LEFT JOIN `productvariants` ON `productvariants`.`productCode`=`productmaster`.`code`
WHERE `productmaster`.`isActive` = 1
ERROR - 2022-10-12 13:57:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:57:18 --> 404 Page Not Found: Report/dayClosingReport
ERROR - 2022-10-12 14:00:33 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:00:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:03:23 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:03:24 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:03:29 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:03:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:03:33 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:03:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:09:16 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:09:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:10:06 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:10:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:10:43 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:10:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:11:38 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:11:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:13:10 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:13:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:18:39 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:18:40 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:24:16 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:24:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:28:01 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 14:28:02 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:40:22 --> 404 Page Not Found: Report/dayClosingReport
ERROR - 2022-10-12 14:40:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:40:39 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:10:40 --> Severity: Notice --> Undefined property: stdClass::$code /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 64
ERROR - 2022-10-12 12:10:40 --> Severity: Notice --> Undefined property: stdClass::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 65
ERROR - 2022-10-12 12:10:40 --> Severity: Notice --> Undefined property: stdClass::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 67
ERROR - 2022-10-12 12:10:40 --> Severity: Notice --> Undefined property: stdClass::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:10:40 --> Severity: Notice --> Undefined property: stdClass::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:10:40 --> Severity: Notice --> Undefined property: stdClass::$counter /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 78
ERROR - 2022-10-12 14:41:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:11:08 --> Severity: Notice --> Undefined property: stdClass::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 64
ERROR - 2022-10-12 12:11:08 --> Severity: Notice --> Undefined property: stdClass::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 66
ERROR - 2022-10-12 12:11:08 --> Severity: Notice --> Undefined property: stdClass::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 68
ERROR - 2022-10-12 12:11:08 --> Severity: Notice --> Undefined property: stdClass::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 70
ERROR - 2022-10-12 12:11:08 --> Severity: Notice --> Undefined property: stdClass::$counter /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 77
ERROR - 2022-10-12 14:49:40 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined variable: orderColumns1 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 60
ERROR - 2022-10-12 12:19:40 --> Severity: Warning --> Invalid argument supplied for foreach() /home4/snhxummy/public_html/subkaemsoftware/supermarket/system/database/DB_query_builder.php 294
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: stdClass::$cashier /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 65
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: stdClass::$totalOrders /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 85
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: stdClass::$totalSale /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 86
ERROR - 2022-10-12 12:19:40 --> Severity: Notice --> Undefined property: stdClass::$offerApplied /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 91
ERROR - 2022-10-12 12:19:40 --> Query error: Unknown column 'ordermaster.cashier' in 'field list' - Invalid query: SELECT ordermaster.branchCode,ordermaster.cashier,branchmaster.counter,ifnull(count(ordermaster.id),0) as totalOrders,ifnull(sum(ordermaster.totalPayable),0) as totalSale,ifnull(count(offerType),0) as offerApplied,ifnull(sum(offerDiscount),0) as offerDiscount,branchmaster.branchName,usermaster.userEmpNo
FROM `ordermaster`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`ordermaster`.`branchCode`
INNER JOIN `usermaster` ON `usermaster`.`code`=`ordermaster`.`addID`
GROUP BY `ordermaster`.`branchCode`, `ordermaster`.`addID`, `ordermaster`.`counter`
ORDER BY `ordermaster`.`id` DESC
ERROR - 2022-10-12 14:50:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined variable: orderColumns1 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 60
ERROR - 2022-10-12 12:20:09 --> Severity: Warning --> Invalid argument supplied for foreach() /home4/snhxummy/public_html/subkaemsoftware/supermarket/system/database/DB_query_builder.php 294
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: stdClass::$cashier /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 65
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: stdClass::$totalOrders /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 85
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: stdClass::$totalSale /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 86
ERROR - 2022-10-12 12:20:09 --> Severity: Notice --> Undefined property: stdClass::$offerApplied /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 91
ERROR - 2022-10-12 12:20:09 --> Query error: Unknown column 'ordermaster.cashier' in 'field list' - Invalid query: SELECT ordermaster.branchCode,ordermaster.cashier,branchmaster.counter,ifnull(count(ordermaster.id),0) as totalOrders,ifnull(sum(ordermaster.totalPayable),0) as totalSale,ifnull(count(offerType),0) as offerApplied,ifnull(sum(offerDiscount),0) as offerDiscount,branchmaster.branchName,usermaster.userEmpNo
FROM `ordermaster`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`ordermaster`.`branchCode`
INNER JOIN `usermaster` ON `usermaster`.`code`=`ordermaster`.`addID`
GROUP BY `ordermaster`.`branchCode`, `ordermaster`.`addID`, `ordermaster`.`counter`
ORDER BY `ordermaster`.`id` DESC
ERROR - 2022-10-12 14:50:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:20:34 --> Query error: Unknown column 'ordermaster.cashier' in 'field list' - Invalid query: SELECT ordermaster.branchCode,ordermaster.cashier,branchmaster.counter,ifnull(count(ordermaster.id),0) as totalOrders,ifnull(sum(ordermaster.totalPayable),0) as totalSale,ifnull(count(offerType),0) as offerApplied,ifnull(sum(offerDiscount),0) as offerDiscount,branchmaster.branchName,usermaster.userEmpNo
FROM `ordermaster`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`ordermaster`.`branchCode`
INNER JOIN `usermaster` ON `usermaster`.`code`=`ordermaster`.`addID`
GROUP BY `ordermaster`.`branchCode`, `ordermaster`.`addID`, `ordermaster`.`counter`
ORDER BY `ordermaster`.`id` DESC
 LIMIT 10
ERROR - 2022-10-12 14:51:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:21:10 --> Query error: Unknown column 'branchmaster.counter' in 'field list' - Invalid query: SELECT ordermaster.branchCode,ordermaster.addID,branchmaster.counter,ifnull(count(ordermaster.id),0) as totalOrders,ifnull(sum(ordermaster.totalPayable),0) as totalSale,ifnull(count(offerType),0) as offerApplied,ifnull(sum(offerDiscount),0) as offerDiscount,branchmaster.branchName,usermaster.userEmpNo
FROM `ordermaster`
INNER JOIN `branchmaster` ON `branchmaster`.`code`=`ordermaster`.`branchCode`
INNER JOIN `usermaster` ON `usermaster`.`code`=`ordermaster`.`addID`
GROUP BY `ordermaster`.`branchCode`, `ordermaster`.`addID`, `ordermaster`.`counter`
ORDER BY `ordermaster`.`id` DESC
 LIMIT 10
ERROR - 2022-10-12 14:51:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Undefined property: mysqli::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Undefined property: mysqli_result::$paymentMode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 69
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 71
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 73
ERROR - 2022-10-12 12:21:41 --> Severity: Notice --> Trying to get property 'paymentMode' of non-object /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 75
ERROR - 2022-10-12 14:52:04 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:22:04 --> Severity: Notice --> Undefined property: stdClass::$totalPayable /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 72
ERROR - 2022-10-12 12:22:04 --> Severity: Notice --> Undefined property: stdClass::$totalPayable /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 70
ERROR - 2022-10-12 12:22:04 --> Severity: Notice --> Undefined property: stdClass::$totalPayable /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 76
ERROR - 2022-10-12 14:52:32 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:54:35 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:57:57 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:58:55 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:00:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:01:13 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:02:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:56:26 --> Severity: Notice --> Undefined index: rolecode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 14
ERROR - 2022-10-12 15:26:30 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 12:56:38 --> Severity: Notice --> Undefined index: rolecode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Report.php 14
ERROR - 2022-10-12 15:27:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:28:15 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:30:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:31:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:44:02 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:46:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:57:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:57:18 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:57:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:57:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:11:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:11:58 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:42:05 --> Severity: Notice --> Undefined index: userName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/GlobalModel.php 1080
ERROR - 2022-10-12 13:42:05 --> Severity: Notice --> Undefined index: userPassword /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/GlobalModel.php 1080
ERROR - 2022-10-12 16:12:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:13:00 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:13:32 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:13:43 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:13:43 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:15:24 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:15:25 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:16:20 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:16:20 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 13:47:16 --> Query error: Illegal mix of collations (utf8_unicode_ci,IMPLICIT) and (utf8_general_ci,IMPLICIT) for operation '=' - Invalid query: SELECT productmaster.productEngName, unitmaster.unitName, barcodeentries.*
FROM `barcodeentries`
INNER JOIN `inwardlineentries` ON `inwardlineentries`.`code`=`barcodeentries`.`inwardLineCode`
INNER JOIN `productmaster` ON `productmaster`.`code`=`inwardlineentries`.`productCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`barcodeentries`.`sellingUnit`
WHERE `barcodeentries`.`code` = 'BAR22_8'
ERROR - 2022-10-12 16:17:29 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:17:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:17:46 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:18:02 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:18:39 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:18:40 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:19:23 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:19:24 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:19:27 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:19:28 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:19:45 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:19:46 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:21:07 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:21:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:21:16 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:21:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:21:36 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:21:36 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:23:02 --> 404 Page Not Found: Cashier/Report/index
ERROR - 2022-10-12 16:23:37 --> 404 Page Not Found: Cashier/Report/index
ERROR - 2022-10-12 16:26:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:27:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:29:06 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:29:06 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:29:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:29:18 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:29:29 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:29:29 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:29:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:30:04 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:30:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:30:08 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 16:30:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:30:23 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:33:46 --> 404 Page Not Found: Cashier/login
ERROR - 2022-10-12 16:34:14 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:34:21 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:35:34 --> Query error: Illegal mix of collations (utf8_general_ci,IMPLICIT) and (utf8_unicode_ci,IMPLICIT) for operation '=' - Invalid query: SELECT `productmaster`.`productEngName`, `productmaster`.`productArbName`, `productmaster`.`productHinName`, `productmaster`.`productUrduName`, `productvariants`.`variantName`, `ordertempproducts`.`productCode`, `ordertempproducts`.`variantCode`, `ordertempproducts`.`barcode`, `ordertempproducts`.`price`, `ordertempproducts`.`amount`, `ordertempproducts`.`discountPrice`, `ordertempproducts`.`discount`, `ordertempproducts`.`taxPercent`, `ordertempproducts`.`tax`, sum(ordertempproducts.qty) as qty, sum(ordertempproducts.totalPrice) as totalPrice
FROM `ordertempproducts`
JOIN `productvariants` ON `ordertempproducts`.`variantCode`=`productvariants`.`code`
JOIN `productmaster` ON `ordertempproducts`.`productCode`=`productmaster`.`code`
WHERE `ordertempproducts`.`orderId` = 'TKMN300'
GROUP BY `ordertempproducts`.`productCode`, `ordertempproducts`.`variantCode`, `ordertempproducts`.`barcode`
ERROR - 2022-10-12 17:19:21 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 17:19:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:51:56 --> Query error: Illegal mix of collations (utf8_unicode_ci,IMPLICIT) and (utf8_general_ci,IMPLICIT) for operation '=' - Invalid query: SELECT productmaster.productEngName, unitmaster.unitName, barcodeentries.*
FROM `barcodeentries`
INNER JOIN `inwardlineentries` ON `inwardlineentries`.`code`=`barcodeentries`.`inwardLineCode`
INNER JOIN `productmaster` ON `productmaster`.`code`=`inwardlineentries`.`productCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`barcodeentries`.`sellingUnit`
WHERE `barcodeentries`.`code` = 'BAR22_8'
ERROR - 2022-10-12 14:52:04 --> Query error: Illegal mix of collations (utf8_unicode_ci,IMPLICIT) and (utf8_general_ci,IMPLICIT) for operation '=' - Invalid query: SELECT productmaster.productEngName, unitmaster.unitName, barcodeentries.*
FROM `barcodeentries`
INNER JOIN `inwardlineentries` ON `inwardlineentries`.`code`=`barcodeentries`.`inwardLineCode`
INNER JOIN `productmaster` ON `productmaster`.`code`=`inwardlineentries`.`productCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`barcodeentries`.`sellingUnit`
WHERE `barcodeentries`.`code` = 'BAR22_8'
ERROR - 2022-10-12 14:52:17 --> Query error: Illegal mix of collations (utf8_unicode_ci,IMPLICIT) and (utf8_general_ci,IMPLICIT) for operation '=' - Invalid query: SELECT productmaster.productEngName, unitmaster.unitName, barcodeentries.*
FROM `barcodeentries`
INNER JOIN `inwardlineentries` ON `inwardlineentries`.`code`=`barcodeentries`.`inwardLineCode`
INNER JOIN `productmaster` ON `productmaster`.`code`=`inwardlineentries`.`productCode`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`barcodeentries`.`sellingUnit`
WHERE `barcodeentries`.`code` = 'BAR22_8'
ERROR - 2022-10-12 17:22:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:22:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:23:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:23:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:00 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:03 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:13 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:18 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:20 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:21 --> 404 Page Not Found: Upload/subcategory
ERROR - 2022-10-12 17:24:25 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:29 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:30 --> 404 Page Not Found: Upload/productImage
ERROR - 2022-10-12 17:24:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:24:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:25:02 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:25:15 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:25:24 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:25:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:25:43 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:26:27 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 17:26:27 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:27:46 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 17:27:48 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:29:34 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 17:29:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 14:59:42 --> Severity: Notice --> A non well formed numeric value encountered /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 250
ERROR - 2022-10-12 14:59:42 --> Severity: Notice --> A non well formed numeric value encountered /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 249
ERROR - 2022-10-12 15:00:50 --> Severity: Notice --> A non well formed numeric value encountered /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 248
ERROR - 2022-10-12 15:00:50 --> Severity: Notice --> A non well formed numeric value encountered /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 249
ERROR - 2022-10-12 15:00:50 --> Severity: Notice --> A non well formed numeric value encountered /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 248
ERROR - 2022-10-12 15:00:50 --> Severity: Notice --> A non well formed numeric value encountered /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 250
ERROR - 2022-10-12 15:02:50 --> Severity: Warning --> number_format() expects parameter 2 to be int, string given /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 247
ERROR - 2022-10-12 15:02:50 --> Severity: Warning --> number_format() expects parameter 2 to be int, string given /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 247
ERROR - 2022-10-12 15:02:50 --> Severity: Warning --> number_format() expects parameter 2 to be int, string given /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 247
ERROR - 2022-10-12 17:34:42 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 17:34:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:04:54 --> Severity: Notice --> A non well formed numeric value encountered /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 290
ERROR - 2022-10-12 17:39:02 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 17:39:02 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 15:31:27 --> Severity: Notice --> Undefined index: customerName /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 24
ERROR - 2022-10-12 15:31:27 --> Severity: Notice --> Undefined index: customerPhone /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 25
ERROR - 2022-10-12 15:31:27 --> Severity: Notice --> Undefined index: tempOrderId /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 26
ERROR - 2022-10-12 15:31:27 --> Severity: Notice --> Undefined index: customerCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 27
ERROR - 2022-10-12 15:31:27 --> Severity: Notice --> Undefined index: userCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 33
ERROR - 2022-10-12 15:31:27 --> Severity: error --> Exception: Argument 1 passed to PosorderModel::fetch_counter_number_for_user() must be of the type string, null given, called in /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php on line 34 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 14
ERROR - 2022-10-12 15:32:27 --> Severity: Notice --> Undefined index: userCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 33
ERROR - 2022-10-12 15:32:27 --> Severity: error --> Exception: Argument 1 passed to PosorderModel::fetch_counter_number_for_user() must be of the type string, null given, called in /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php on line 34 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/models/PosorderModel.php 14
ERROR - 2022-10-12 15:34:09 --> Severity: Notice --> Array to string conversion /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 35
ERROR - 2022-10-12 15:50:10 --> check =>BR22_27 product {"productCode":"PRO22_5","variantCode":"PVAR22_10","barcode":"97596838","unit":"UOM_8","price":"50.00","qty":"1.000","amount":"50.00","discountPrice":"10.00","discount":"10.00","taxPercent":"18.00","tax":"7.20","totalPrice":"47.20"}
ERROR - 2022-10-12 18:20:59 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:21:01 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 18:21:24 --> Severity: error --> Exception: syntax error, unexpected 'return' (T_RETURN) /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 289
ERROR - 2022-10-12 18:21:29 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:21:44 --> Severity: error --> Exception: syntax error, unexpected 'return' (T_RETURN) /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Api/Posorder.php 324
ERROR - 2022-10-12 15:53:40 --> check =>BR22_27 product {"productCode":"PRO22_1","variantCode":"PVAR22_1","barcode":"12345672","unit":"UOM_6","price":"520.00","qty":"1.000","amount":"520.00","discountPrice":"30.00","discount":"30.00","taxPercent":"27.00","tax":"132.30","totalPrice":"622.30"}
ERROR - 2022-10-12 15:53:40 --> check =>BR22_27 product {"productCode":"PRO22_1","variantCode":"PVAR22_3","barcode":"12345678","unit":"UOM_6","price":"520.00","qty":"1.000","amount":"520.00","discountPrice":"30.00","discount":"30.00","taxPercent":"27.00","tax":"132.30","totalPrice":"622.30"}
ERROR - 2022-10-12 15:53:40 --> check =>BR22_27 product {"productCode":"PRO22_1","variantCode":"PVAR22_4","barcode":"12345675","unit":"UOM_6","price":"520.00","qty":"1.000","amount":"520.00","discountPrice":"30.00","discount":"30.00","taxPercent":"27.00","tax":"132.30","totalPrice":"622.30"}
ERROR - 2022-10-12 18:23:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:27:40 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:29:03 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:33:43 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:34:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:35:32 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:38:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:40:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:45:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:46:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:47:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:48:52 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:49:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:50:50 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 18:50:52 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:51:04 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 18:51:04 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:28:28 --> Severity: Notice --> Undefined index: cash_logged_in /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 14
ERROR - 2022-10-12 16:28:28 --> Severity: Notice --> Trying to access array offset on value of type null /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 14
ERROR - 2022-10-12 16:28:28 --> Severity: Notice --> Undefined index: cash_logged_in /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 14
ERROR - 2022-10-12 16:28:28 --> Severity: Notice --> Trying to access array offset on value of type null /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 14
ERROR - 2022-10-12 18:58:41 --> 404 Page Not Found: Logout/index
ERROR - 2022-10-12 18:58:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 18:59:49 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:02:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:02:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:02:32 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:02:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:03:23 --> 404 Page Not Found: Cashier/Logout/index
ERROR - 2022-10-12 19:03:26 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:03:27 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:04:57 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:04:59 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:06:43 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:06:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:06:44 --> 404 Page Not Found: Report/getCounterData
ERROR - 2022-10-12 19:06:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:37:09 --> Severity: Notice --> Undefined variable: cashierCode /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 33
ERROR - 2022-10-12 16:37:09 --> Severity: Notice --> Undefined variable: cashPayments1 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 87
ERROR - 2022-10-12 16:37:09 --> Severity: Notice --> Undefined variable: cardPayments1 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 90
ERROR - 2022-10-12 16:37:09 --> Severity: Notice --> Undefined variable: upiPayments1 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 93
ERROR - 2022-10-12 16:37:09 --> Severity: Notice --> Undefined variable: netbankingPayments1 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 96
ERROR - 2022-10-12 16:37:09 --> Severity: Notice --> Undefined variable: cashPayments2 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 87
ERROR - 2022-10-12 16:37:09 --> Severity: Notice --> Undefined variable: cardPayments2 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 90
ERROR - 2022-10-12 16:37:09 --> Severity: Notice --> Undefined variable: upiPayments2 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 93
ERROR - 2022-10-12 16:37:09 --> Severity: Notice --> Undefined variable: netbankingPayments2 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 96
ERROR - 2022-10-12 19:07:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:07:40 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:37:40 --> Severity: Notice --> Undefined variable: cashPayments1 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 87
ERROR - 2022-10-12 16:37:40 --> Severity: Notice --> Undefined variable: cardPayments1 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 90
ERROR - 2022-10-12 16:37:40 --> Severity: Notice --> Undefined variable: upiPayments1 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 93
ERROR - 2022-10-12 16:37:40 --> Severity: Notice --> Undefined variable: netbankingPayments1 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 96
ERROR - 2022-10-12 16:37:40 --> Severity: Notice --> Undefined variable: cashPayments2 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 87
ERROR - 2022-10-12 16:37:40 --> Severity: Notice --> Undefined variable: cardPayments2 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 90
ERROR - 2022-10-12 16:37:40 --> Severity: Notice --> Undefined variable: upiPayments2 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 93
ERROR - 2022-10-12 16:37:40 --> Severity: Notice --> Undefined variable: netbankingPayments2 /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 96
ERROR - 2022-10-12 19:08:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:38:38 --> Severity: Notice --> Undefined variable: cashPayments /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 87
ERROR - 2022-10-12 16:38:38 --> Severity: Notice --> Undefined variable: cardPayments /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 90
ERROR - 2022-10-12 16:38:38 --> Severity: Notice --> Undefined variable: upiPayments /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 93
ERROR - 2022-10-12 16:38:38 --> Severity: Notice --> Undefined variable: netbankingPayments /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 96
ERROR - 2022-10-12 16:38:38 --> Severity: Notice --> Undefined variable: cashPayments /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 87
ERROR - 2022-10-12 16:38:38 --> Severity: Notice --> Undefined variable: cardPayments /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 90
ERROR - 2022-10-12 16:38:38 --> Severity: Notice --> Undefined variable: upiPayments /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 93
ERROR - 2022-10-12 16:38:38 --> Severity: Notice --> Undefined variable: netbankingPayments /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 96
ERROR - 2022-10-12 19:09:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 16:39:11 --> Severity: Notice --> Undefined variable: netbankingPayment /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 96
ERROR - 2022-10-12 16:39:11 --> Severity: Notice --> Undefined variable: netbankingPayment /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 96
ERROR - 2022-10-12 19:09:43 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:11:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:13:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:13:24 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:14:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:15:21 --> Severity: error --> Exception: syntax error, unexpected '$response' (T_VARIABLE) /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Cashier/Report.php 110
ERROR - 2022-10-12 19:16:00 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:16:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:16:36 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:16:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:17:39 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:18:02 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:29:44 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:29:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:37:39 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:37:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:37:54 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:40:21 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:40:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:41:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:41:37 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:41:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:42:26 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:42:27 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 17:12:46 --> check =>BR22_27 product {"productCode":"PRO22_1","variantCode":"PVAR22_5","barcode":"12345676","unit":"UOM_6","price":"520.00","qty":"2.000","amount":"1040.00","discountPrice":"30.00","discount":"60.00","taxPercent":"27.00","tax":"264.60","totalPrice":"1244.60"}
ERROR - 2022-10-12 17:12:46 --> check =>BR22_27 product {"productCode":"PRO22_1","variantCode":"PVAR22_7","barcode":"12345674","unit":"UOM_6","price":"520.00","qty":"3.000","amount":"1560.00","discountPrice":"30.00","discount":"90.00","taxPercent":"27.00","tax":"396.90","totalPrice":"1866.90"}
ERROR - 2022-10-12 19:45:06 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:45:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 19:47:41 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 19:47:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 20:03:49 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 20:03:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 20:04:03 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 20:04:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 20:04:23 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 20:04:25 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 20:06:05 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 20:07:38 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 20:07:43 --> 404 Page Not Found: Assets/init_site
ERROR - 2022-10-12 20:08:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 20:08:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-10-12 20:08:42 --> 404 Page Not Found: Assets/admin
