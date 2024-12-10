<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-09-21 10:40:39 --> 404 Page Not Found: Admin/login
ERROR - 2022-09-21 10:49:07 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 11:55:06 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 11:55:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 11:57:26 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 12:05:01 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Inward.php 240
ERROR - 2022-09-21 12:05:13 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Inward.php 240
ERROR - 2022-09-21 12:05:46 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 12:06:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 12:08:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 13:49:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 13:49:50 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 13:50:45 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 13:53:48 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:02:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:03:12 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:04:05 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:04:44 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:05:27 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:06:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:07:21 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:07:43 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:11:59 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:13:40 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:14:23 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:14:28 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:15:29 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:15:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 14:17:36 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 11:47:36 --> Severity: Notice --> Undefined property: stdClass::$qrCodeImage /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Stock.php 55
ERROR - 2022-09-21 11:47:36 --> Severity: Notice --> Undefined property: stdClass::$qrCodeImage /home4/snhxummy/public_html/subkaemsoftware/supermarket/application/controllers/Stock.php 55
ERROR - 2022-09-21 14:25:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 12:28:21 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.subsubsubcategorymaster' doesn't exist - Invalid query: SELECT subsubcategorymaster.code,subcategorymaster.subcategoryName,subcategorymaster.icon,subcategorymaster.subcategorySName,subcategorymaster.isActive
FROM `subsubsubcategorymaster`
WHERE `subcategorymaster`.`isDelete` =0 OR `subcategorymaster`.`isDelete` IS NULL
ORDER BY `subcategorymaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-21 15:01:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:01:51 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 12:31:52 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.subsubsubcategorymaster' doesn't exist - Invalid query: SELECT subsubcategorymaster.code,categorymaster.categoryName,subcategorymaster.subcategoryName,subcategorymaster.icon,subcategorymaster.subcategorySName,subcategorymaster.isActive
FROM `subsubsubcategorymaster`
INNER JOIN `categorymaster` ON `categorymaster`.`code`=`subcategorymaster`.`catgeoryCode`
WHERE `subcategorymaster`.`isDelete` =0 OR `subcategorymaster`.`isDelete` IS NULL
ORDER BY `subcategorymaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-21 15:03:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 12:33:50 --> Query error: Table 'snhxummy_test_kaemsoftware_supermarket.subsubsubcategorymaster' doesn't exist - Invalid query: SELECT subsubcategorymaster.code,categorymaster.categoryName,subcategorymaster.subcategoryName,subcategorymaster.icon,subcategorymaster.subcategorySName,subcategorymaster.isActive
FROM `subsubsubcategorymaster`
INNER JOIN `categorymaster` ON `categorymaster`.`code`=`subcategorymaster`.`catgeoryCode`
WHERE `subcategorymaster`.`isDelete` =0 OR `subcategorymaster`.`isDelete` IS NULL
ORDER BY `subcategorymaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-21 12:35:11 --> Query error: Unknown column 'subcategorymaster.catgeoryCode' in 'on clause' - Invalid query: SELECT subcategorymaster.code,categorymaster.categoryName,subcategorymaster.subcategoryName,subcategorymaster.icon,subcategorymaster.subcategorySName,subcategorymaster.isActive
FROM `subcategorymaster`
INNER JOIN `categorymaster` ON `categorymaster`.`code`=`subcategorymaster`.`catgeoryCode`
WHERE `subcategorymaster`.`isDelete` =0 OR `subcategorymaster`.`isDelete` IS NULL
ORDER BY `subcategorymaster`.`id` DESC
 LIMIT 10
ERROR - 2022-09-21 15:05:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:05:55 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:07:09 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:08:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:09:49 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 12:40:55 --> Query error: Column 'subcategorySName' cannot be null - Invalid query: INSERT INTO `subcategorymaster` (`subcategoryName`, `subcategorySName`, `description`, `isActive`, `addID`, `addIP`) VALUES ('', NULL, '', '1', 'USR22_1', '106.193.197.133')
ERROR - 2022-09-21 15:12:41 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:15:51 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:17:12 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:20:20 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:23:33 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:25:11 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 15:29:17 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:19:32 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:20:52 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:22:16 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:31:25 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:31:42 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:32:36 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:34:28 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:36:35 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:38:12 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:38:20 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:40:22 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:40:27 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:40:35 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:44:21 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:45:08 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:45:47 --> 404 Page Not Found: Assets/admin
ERROR - 2022-09-21 16:50:36 --> 404 Page Not Found: Assets/admin
