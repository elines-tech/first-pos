<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-09-01 10:58:29 --> Severity: Notice --> Trying to access array offset on value of type null /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 34
ERROR - 2022-09-01 10:58:29 --> Severity: Notice --> Undefined index: start /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 46
ERROR - 2022-09-01 10:58:29 --> Severity: Notice --> Undefined index: draw /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 69
ERROR - 2022-09-01 00:01:58 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 00:03:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 00:10:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 00:13:01 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 00:16:14 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:47:10 --> Severity: Notice --> Undefined property: ProductCombo::$globalModel /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 142
ERROR - 2022-09-01 11:47:10 --> Severity: error --> Exception: Call to a member function addNew() on null /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 142
ERROR - 2022-09-01 11:47:10 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Exceptions.php:271) /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Common.php 570
ERROR - 2022-09-01 12:11:18 --> Severity: Notice --> Undefined offset: 0 /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Tax.php 323
ERROR - 2022-09-01 12:11:18 --> Severity: Notice --> Trying to get property 'taxName' of non-object /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Tax.php 323
ERROR - 2022-09-01 00:41:28 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 00:42:59 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 12:13:06 --> Severity: Notice --> Undefined offset: 0 /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Tax.php 323
ERROR - 2022-09-01 12:13:06 --> Severity: Notice --> Trying to get property 'taxName' of non-object /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Tax.php 323
ERROR - 2022-09-01 00:43:39 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 12:13:47 --> Severity: Notice --> Undefined property: stdClass::$taxName /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 176
ERROR - 2022-09-01 01:04:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 01:04:31 --> 404 Page Not Found: Productcombo/editProductCombo
ERROR - 2022-09-01 01:17:07 --> 404 Page Not Found: Productcombo/editProductCombo
ERROR - 2022-09-01 01:57:18 --> 404 Page Not Found: Product/view
ERROR - 2022-09-01 02:12:51 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 02:13:04 --> 404 Page Not Found: Productcombo/editProductCombo
ERROR - 2022-09-01 02:13:35 --> 404 Page Not Found: Productcombo/editProductCombo
ERROR - 2022-09-01 02:14:23 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 02:15:20 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 02:16:01 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 02:18:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 02:20:40 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 02:24:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 02:25:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 02:29:52 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 02:31:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 02:43:43 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 03:08:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 03:14:00 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 03:18:56 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 03:19:16 --> 404 Page Not Found: Stock/listRecords
ERROR - 2022-09-01 14:49:34 --> Severity: Notice --> Array to string conversion /home4/snhxummy/public_html/subkaemsoftware/development/system/database/DB_query_builder.php 2442
ERROR - 2022-09-01 14:49:34 --> Query error: Not unique table/alias: 'stockinfo' - Invalid query: SELECT branchmaster.branchName,ifnull(count(stockinfo.code),0) as stockCount
FROM `stockinfo`
INNER JOIN `stockinfo` ON `stockinfo`.`itemCode`=`inwardlineentries`.`productCode`
INNER JOIN `itemmaster` ON `itemmaster`.`code`=`stockinfo`.`itemCode`
WHERE `branchmaster`.`isActive` = Array
GROUP BY `branchmaster`.`code`
ORDER BY `branchmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 14:49:34 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Exceptions.php:271) /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Common.php 570
ERROR - 2022-09-01 03:19:56 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 14:50:14 --> Severity: Notice --> Array to string conversion /home4/snhxummy/public_html/subkaemsoftware/development/system/database/DB_query_builder.php 2442
ERROR - 2022-09-01 14:50:14 --> Query error: Not unique table/alias: 'stockinfo' - Invalid query: SELECT branchmaster.branchName,ifnull(count(stockinfo.code),0) as stockCount
FROM `stockinfo`
INNER JOIN `stockinfo` ON `stockinfo`.`itemCode`=`inwardlineentries`.`productCode`
INNER JOIN `itemmaster` ON `itemmaster`.`code`=`stockinfo`.`itemCode`
WHERE `branchmaster`.`isActive` = Array
GROUP BY `branchmaster`.`code`
ORDER BY `branchmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 14:50:14 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Exceptions.php:271) /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Common.php 570
ERROR - 2022-09-01 14:50:18 --> Severity: Notice --> Array to string conversion /home4/snhxummy/public_html/subkaemsoftware/development/system/database/DB_query_builder.php 2442
ERROR - 2022-09-01 14:50:18 --> Query error: Not unique table/alias: 'stockinfo' - Invalid query: SELECT branchmaster.branchName,ifnull(count(stockinfo.code),0) as stockCount
FROM `stockinfo`
INNER JOIN `stockinfo` ON `stockinfo`.`itemCode`=`inwardlineentries`.`productCode`
INNER JOIN `itemmaster` ON `itemmaster`.`code`=`stockinfo`.`itemCode`
WHERE `branchmaster`.`isActive` = Array
GROUP BY `branchmaster`.`code`
ORDER BY `branchmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 14:50:18 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Exceptions.php:271) /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Common.php 570
ERROR - 2022-09-01 03:20:24 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 03:20:43 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 14:50:43 --> Severity: Notice --> Array to string conversion /home4/snhxummy/public_html/subkaemsoftware/development/system/database/DB_query_builder.php 2442
ERROR - 2022-09-01 14:50:43 --> Query error: Not unique table/alias: 'stockinfo' - Invalid query: SELECT branchmaster.branchName,ifnull(count(stockinfo.code),0) as stockCount
FROM `stockinfo`
INNER JOIN `stockinfo` ON `stockinfo`.`itemCode`=`inwardlineentries`.`productCode`
INNER JOIN `itemmaster` ON `itemmaster`.`code`=`stockinfo`.`itemCode`
WHERE `branchmaster`.`isActive` = Array
GROUP BY `branchmaster`.`code`
ORDER BY `branchmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 14:50:43 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Exceptions.php:271) /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Common.php 570
ERROR - 2022-09-01 03:21:00 --> 404 Page Not Found: ProductCombo/listRecords
ERROR - 2022-09-01 03:21:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 03:21:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 14:51:22 --> Query error: Not unique table/alias: 'stockinfo' - Invalid query: SELECT branchmaster.branchName,ifnull(count(stockinfo.code),0) as stockCount
FROM `stockinfo`
INNER JOIN `stockinfo` ON `stockinfo`.`itemCode`=`inwardlineentries`.`productCode`
INNER JOIN `itemmaster` ON `itemmaster`.`code`=`stockinfo`.`itemCode`
WHERE `branchmaster`.`isActive` = 1
GROUP BY `branchmaster`.`code`
ORDER BY `branchmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 03:24:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 14:54:54 --> Severity: Notice --> Undefined property: stdClass::$code /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 45
ERROR - 2022-09-01 14:54:54 --> Severity: Notice --> Undefined property: stdClass::$code /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 45
ERROR - 2022-09-01 03:28:03 --> Severity: error --> Exception: syntax error, unexpected end of file, expecting function (T_FUNCTION) or const (T_CONST) /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 312
ERROR - 2022-09-01 03:28:08 --> Severity: error --> Exception: syntax error, unexpected end of file, expecting function (T_FUNCTION) or const (T_CONST) /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 312
ERROR - 2022-09-01 03:28:50 --> Severity: error --> Exception: syntax error, unexpected end of file, expecting function (T_FUNCTION) or const (T_CONST) /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 312
ERROR - 2022-09-01 03:29:30 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 14:59:39 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 257
ERROR - 2022-09-01 15:02:44 --> Severity: Notice --> Undefined property: stdClass::$productComboPrice /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 283
ERROR - 2022-09-01 15:03:10 --> Severity: Notice --> Undefined property: stdClass::$productComboPrice /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 283
ERROR - 2022-09-01 03:48:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 03:51:32 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 03:54:46 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 03:56:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 03:58:54 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:01:14 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:03:04 --> Severity: error --> Exception: syntax error, unexpected ''" name="pr_price1[]" type="te' (T_ENCAPSED_AND_WHITESPACE) /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 262
ERROR - 2022-09-01 04:03:12 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:04:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:06:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:12:03 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:19:23 --> Severity: error --> Exception: syntax error, unexpected ')' /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 31
ERROR - 2022-09-01 04:19:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:21:12 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:23:30 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 15:53:30 --> Severity: Notice --> Undefined property: stdClass::$code /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 45
ERROR - 2022-09-01 15:53:30 --> Severity: Notice --> Undefined property: stdClass::$code /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 45
ERROR - 2022-09-01 15:53:30 --> Severity: Notice --> Undefined property: stdClass::$code /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 45
ERROR - 2022-09-01 04:23:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:24:25 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 15:54:26 --> Severity: Notice --> Undefined property: stdClass::$code /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 45
ERROR - 2022-09-01 15:54:26 --> Severity: Notice --> Undefined property: stdClass::$code /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 45
ERROR - 2022-09-01 15:54:26 --> Severity: Notice --> Undefined property: stdClass::$code /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 45
ERROR - 2022-09-01 15:54:26 --> Severity: Notice --> Undefined property: stdClass::$code /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 45
ERROR - 2022-09-01 04:24:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:26:19 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:26:29 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:36:32 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:40:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:41:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:43:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:51:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:54:00 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:55:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:58:58 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 04:59:10 --> Severity: error --> Exception: syntax error, unexpected '$this' (T_VARIABLE) /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 77
ERROR - 2022-09-01 04:59:57 --> Severity: error --> Exception: syntax error, unexpected '$this' (T_VARIABLE) /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 76
ERROR - 2022-09-01 05:00:24 --> Severity: error --> Exception: syntax error, unexpected ')' /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 90
ERROR - 2022-09-01 05:01:21 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:01:51 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:01:52 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:01:57 --> 404 Page Not Found: Stock/productList
ERROR - 2022-09-01 05:02:54 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:08:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:11:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 16:52:02 --> Severity: Notice --> Undefined property: Stock::$url /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 75
ERROR - 2022-09-01 16:52:02 --> Severity: error --> Exception: Call to a member function segment() on null /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Stock.php 75
ERROR - 2022-09-01 16:52:02 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Exceptions.php:271) /home4/snhxummy/public_html/subkaemsoftware/development/system/core/Common.php 570
ERROR - 2022-09-01 05:22:39 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:23:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:23:20 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:23:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 16:53:56 --> Query error: Unknown column 'usermaster.unitName' in 'where clause' - Invalid query: SELECT stockinfo.unitCode,unitmaster.unitName,unitmaster.unitSName,stockinfo.stock
FROM `stockinfo`
JOIN `unitmaster` ON `unitmaster`.`code`=`stockinfo`.`unitCode`
WHERE `stockinfo`.`itemCode` = 'PRO22_12'
AND `unitmaster`.`isActive` = 1
AND  usermaster.unitName  LIKE '%l%' ESCAPE '!' 
ORDER BY `stockinfo`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 16:53:56 --> Query error: Unknown column 'usermaster.unitName' in 'where clause' - Invalid query: SELECT stockinfo.unitCode,unitmaster.unitName,unitmaster.unitSName,stockinfo.stock
FROM `stockinfo`
JOIN `unitmaster` ON `unitmaster`.`code`=`stockinfo`.`unitCode`
WHERE `stockinfo`.`itemCode` = 'PRO22_12'
AND `unitmaster`.`isActive` = 1
AND  usermaster.unitName  LIKE '%lt%' ESCAPE '!' 
ORDER BY `stockinfo`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 05:31:04 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:35:24 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:05:34 --> Severity: error --> Exception: Cannot use object of type stdClass as array /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 7
ERROR - 2022-09-01 05:37:20 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:07:30 --> Severity: error --> Exception: Cannot use object of type stdClass as array /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 7
ERROR - 2022-09-01 17:07:49 --> Severity: Notice --> Undefined variable: taxes /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/tax/addGroup.php 22
ERROR - 2022-09-01 17:07:49 --> Severity: error --> Exception: Object of class CI_Loader could not be converted to string /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Tax.php 28
ERROR - 2022-09-01 05:37:54 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:38:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:08:23 --> Severity: Notice --> Undefined variable: taxes /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/tax/addGroup.php 22
ERROR - 2022-09-01 17:08:23 --> Severity: error --> Exception: Object of class CI_Loader could not be converted to string /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Tax.php 28
ERROR - 2022-09-01 17:09:40 --> Severity: Notice --> Undefined variable: taxes /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/tax/addGroup.php 22
ERROR - 2022-09-01 05:40:12 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:10:19 --> Severity: error --> Exception: Cannot use object of type stdClass as array /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 7
ERROR - 2022-09-01 05:40:25 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:40:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:41:25 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:41:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:11:55 --> Severity: Notice --> Undefined variable: taxes /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/tax/addGroup.php 22
ERROR - 2022-09-01 05:42:26 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 05:45:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:15:42 --> Severity: error --> Exception: Cannot use object of type stdClass as array /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 7
ERROR - 2022-09-01 05:50:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:20:29 --> Severity: Notice --> Undefined variable: row /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 17:20:29 --> Severity: Notice --> Trying to get property 'productCategoryCode' of non-object /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 17:20:29 --> Severity: Notice --> Undefined variable: row /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 17:20:29 --> Severity: Notice --> Trying to get property 'productCategoryCode' of non-object /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 05:55:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:25:19 --> Severity: Notice --> Undefined variable: row /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 17:25:19 --> Severity: Notice --> Trying to get property 'productCategoryCode' of non-object /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 17:25:19 --> Severity: Notice --> Undefined variable: row /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 17:25:19 --> Severity: Notice --> Trying to get property 'productCategoryCode' of non-object /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 17:26:37 --> Severity: Notice --> Undefined variable: row /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 17:26:37 --> Severity: Notice --> Trying to get property 'productCategoryCode' of non-object /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 17:26:37 --> Severity: Notice --> Undefined variable: row /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 17:26:37 --> Severity: Notice --> Trying to get property 'productCategoryCode' of non-object /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/productcombo/editcombo.php 22
ERROR - 2022-09-01 06:00:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:33:43 --> Severity: Notice --> Undefined property: stdClass::$taxName /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Tax.php 329
ERROR - 2022-09-01 17:54:18 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 280
ERROR - 2022-09-01 17:54:19 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 280
ERROR - 2022-09-01 17:54:43 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 280
ERROR - 2022-09-01 17:54:48 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 280
ERROR - 2022-09-01 17:54:50 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 280
ERROR - 2022-09-01 06:24:54 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 06:25:24 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:55:30 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 280
ERROR - 2022-09-01 17:56:11 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 280
ERROR - 2022-09-01 06:28:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 17:58:42 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 280
ERROR - 2022-09-01 18:01:33 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 280
ERROR - 2022-09-01 18:08:20 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 279
ERROR - 2022-09-01 18:08:23 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 279
ERROR - 2022-09-01 18:08:26 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 279
ERROR - 2022-09-01 06:38:36 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 06:38:58 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 18:09:06 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 279
ERROR - 2022-09-01 18:11:13 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 279
ERROR - 2022-09-01 06:41:23 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 06:49:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 18:19:36 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 279
ERROR - 2022-09-01 06:52:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 18:22:54 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 279
ERROR - 2022-09-01 06:54:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 06:56:27 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:26:46 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:32:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 19:02:49 --> Severity: Notice --> Undefined offset: 8 /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 196
ERROR - 2022-09-01 19:02:49 --> Severity: Notice --> Undefined offset: 8 /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 197
ERROR - 2022-09-01 19:03:27 --> Severity: Notice --> Undefined offset: 8 /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 196
ERROR - 2022-09-01 19:03:27 --> Severity: Notice --> Undefined offset: 8 /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 197
ERROR - 2022-09-01 07:33:56 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:34:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:38:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:44:40 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:46:14 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:47:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:51:13 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:53:57 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:54:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:56:02 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:57:48 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 07:58:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 08:00:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 08:03:03 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 08:05:24 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 19:41:46 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 282
ERROR - 2022-09-01 19:41:49 --> Severity: error --> Exception: Call to a member function result() on bool /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 282
ERROR - 2022-09-01 19:41:55 --> Severity: Notice --> Undefined property: stdClass::$taxName /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 237
ERROR - 2022-09-01 19:42:00 --> Severity: Notice --> Undefined property: stdClass::$taxName /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 237
ERROR - 2022-09-01 19:42:05 --> Severity: Notice --> Undefined property: stdClass::$taxName /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/ProductCombo.php 237
ERROR - 2022-09-01 08:15:06 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 08:18:57 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 08:20:59 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 08:22:02 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 08:24:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 08:27:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 08:40:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 08:41:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 22:25:08 --> Query error: Unknown column 'itemmaster.storageUnit' in 'on clause' - Invalid query: SELECT itemmaster.code,itemmaster.itemEngName,unitmaster.unitSName,unitmaster.unitName,itemmaster.itemUnit,itemmaster.itemPrice,itemmaster.isActive
FROM `itemmaster`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`itemmaster`.`storageUnit`
WHERE `itemmaster`.`isDelete` =0 OR `itemmaster`.`isDelete` IS NULL
ORDER BY `itemmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 22:26:42 --> Query error: Unknown column 'itemmaster.storageUnit' in 'field list' - Invalid query: SELECT itemmaster.code,itemmaster.itemEngName,unitmaster.unitSName,unitmaster.unitName,itemmaster.storageUnit,itemmaster.itemPrice,itemmaster.isActive
FROM `itemmaster`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`itemmaster`.`storageUnit`
WHERE `itemmaster`.`isDelete` =0 OR `itemmaster`.`isDelete` IS NULL
ORDER BY `itemmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 10:56:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 10:56:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 22:26:54 --> Query error: Unknown column 'itemmaster.storageUnit' in 'field list' - Invalid query: SELECT itemmaster.code,itemmaster.itemEngName,unitmaster.unitSName,unitmaster.unitName,itemmaster.storageUnit,itemmaster.itemPrice,itemmaster.isActive
FROM `itemmaster`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`itemmaster`.`storageUnit`
WHERE `itemmaster`.`isDelete` =0 OR `itemmaster`.`isDelete` IS NULL
ORDER BY `itemmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 22:29:22 --> Query error: Unknown column 'itemmaster.storageUnit' in 'field list' - Invalid query: SELECT itemmaster.code,itemmaster.itemEngName,unitmaster.unitSName,unitmaster.unitName,itemmaster.storageUnit,itemmaster.itemPrice,itemmaster.isActive
FROM `itemmaster`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`itemmaster`.`storageUnit`
WHERE `itemmaster`.`isDelete` =0 OR `itemmaster`.`isDelete` IS NULL
ORDER BY `itemmaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-01 10:59:31 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:00:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 22:34:08 --> Severity: Notice --> Undefined property: stdClass::$ingredientUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 55
ERROR - 2022-09-01 22:34:08 --> Severity: Notice --> Undefined property: stdClass::$ingredientFactor /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 56
ERROR - 2022-09-01 22:34:08 --> Severity: Notice --> Undefined property: stdClass::$ingredientUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 55
ERROR - 2022-09-01 22:34:08 --> Severity: Notice --> Undefined property: stdClass::$ingredientFactor /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 56
ERROR - 2022-09-01 22:34:08 --> Severity: Notice --> Undefined property: stdClass::$ingredientUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 55
ERROR - 2022-09-01 22:34:08 --> Severity: Notice --> Undefined property: stdClass::$ingredientFactor /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 56
ERROR - 2022-09-01 22:34:08 --> Severity: Notice --> Undefined property: stdClass::$ingredientUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 55
ERROR - 2022-09-01 22:34:08 --> Severity: Notice --> Undefined property: stdClass::$ingredientFactor /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 56
ERROR - 2022-09-01 22:34:08 --> Severity: Notice --> Undefined property: stdClass::$ingredientUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 55
ERROR - 2022-09-01 22:34:08 --> Severity: Notice --> Undefined property: stdClass::$ingredientFactor /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 56
ERROR - 2022-09-01 11:04:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:04:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 22:34:33 --> Severity: Notice --> Undefined property: stdClass::$ingredientUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 55
ERROR - 2022-09-01 22:34:33 --> Severity: Notice --> Undefined property: stdClass::$ingredientFactor /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 56
ERROR - 2022-09-01 22:34:33 --> Severity: Notice --> Undefined property: stdClass::$ingredientUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 55
ERROR - 2022-09-01 22:34:33 --> Severity: Notice --> Undefined property: stdClass::$ingredientFactor /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 56
ERROR - 2022-09-01 22:34:33 --> Severity: Notice --> Undefined property: stdClass::$ingredientUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 55
ERROR - 2022-09-01 22:34:33 --> Severity: Notice --> Undefined property: stdClass::$ingredientFactor /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 56
ERROR - 2022-09-01 22:34:33 --> Severity: Notice --> Undefined property: stdClass::$ingredientUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 55
ERROR - 2022-09-01 22:34:33 --> Severity: Notice --> Undefined property: stdClass::$ingredientFactor /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 56
ERROR - 2022-09-01 22:34:33 --> Severity: Notice --> Undefined property: stdClass::$ingredientUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 55
ERROR - 2022-09-01 22:34:33 --> Severity: Notice --> Undefined property: stdClass::$ingredientFactor /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Item.php 56
ERROR - 2022-09-01 11:06:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:08:26 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 22:39:04 --> Query error: Unknown column 'itemmaster.itemUnit' in 'on clause' - Invalid query: SELECT itemmaster.*, unitmaster.unitSName, unitmaster.unitName
FROM `itemmaster`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`itemmaster`.`itemUnit`
WHERE `itemmaster`.`code` = 'ITEM22_6'
ERROR - 2022-09-01 11:10:00 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:11:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:19:28 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 22:50:24 --> Severity: Notice --> Undefined index: itemUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/inward/edit.php 149
ERROR - 2022-09-01 22:50:24 --> Severity: Notice --> Undefined index: itemUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/inward/edit.php 149
ERROR - 2022-09-01 22:50:24 --> Severity: Notice --> Undefined index: itemUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/inward/edit.php 149
ERROR - 2022-09-01 22:50:24 --> Severity: Notice --> Undefined index: itemUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/views/dashboard/inward/edit.php 149
ERROR - 2022-09-01 11:36:30 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:36:58 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:37:53 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:38:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:38:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:39:01 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:41:13 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:13:11 --> Query error: Unknown column 'inner' in 'from clause' - Invalid query: SELECT inwardlineentries.*, unitmaster.unitName
FROM `inwardlineentries`
JOIN `unitmaster` USING (`inner`)
WHERE `inwardlineentries`.`inwardCode` = 'IN22_6'
ORDER BY `unitmaster`
ERROR - 2022-09-01 23:14:34 --> Query error: Unknown column 'inwardlineentries.itemUnit' in 'on clause' - Invalid query: SELECT inwardlineentries.*, unitmaster.unitName
FROM `inwardlineentries`
INNER JOIN `unitmaster` ON `unitmaster`.`code`=`inwardlineentries`.`itemUnit`
WHERE `inwardlineentries`.`inwardCode` = 'IN22_6'
ERROR - 2022-09-01 11:45:52 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:16:03 --> Severity: Notice --> Undefined variable: date /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Inward.php 234
ERROR - 2022-09-01 11:46:10 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:16:15 --> Severity: Notice --> Undefined variable: date /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Inward.php 234
ERROR - 2022-09-01 11:48:30 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:49:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:49:35 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:51:06 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:53:01 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:55:23 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:25:41 --> Severity: Notice --> Undefined variable: productCode /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Inward.php 194
ERROR - 2022-09-01 23:25:41 --> Severity: Notice --> Undefined variable: productUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Inward.php 194
ERROR - 2022-09-01 23:25:41 --> Severity: Notice --> Undefined variable: productQty /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Inward.php 194
ERROR - 2022-09-01 23:25:41 --> Severity: Notice --> Undefined variable: productCode /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Inward.php 194
ERROR - 2022-09-01 23:25:42 --> Severity: Notice --> Undefined variable: productUnit /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Inward.php 194
ERROR - 2022-09-01 23:25:42 --> Severity: Notice --> Undefined variable: productQty /home4/snhxummy/public_html/subkaemsoftware/development/application/controllers/Inward.php 194
ERROR - 2022-09-01 11:55:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:55:54 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 11:56:06 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:11:26 --> 404 Page Not Found: Productcombo/listRecords
ERROR - 2022-09-01 23:17:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:20:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:22:34 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:23:48 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:27:32 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:28:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:29:58 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:30:38 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:30:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:31:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:31:25 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:37:37 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:40:48 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-01 23:45:51 --> 404 Page Not Found: Assets/admin
