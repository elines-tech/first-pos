<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['register'] = 'register/index';
$route['setup/app'] = 'register/setup';
$route['login'] = 'backend/login/index';
$route['login/reset'] = 'backend/login/reset';
$route['login/recover'] = 'backend/login/recover';
$route['login/resetPassword/(:any)'] = 'backend/login/resetPassword/$1';
$route['login/updatePassword'] = 'backend/login/updatePassword';
$route['Authentication/user_login_process'] = 'backend/Authentication/user_login_process';
$route['Authentication/logout'] = 'backend/Authentication/logout';
$route['dashboard/listRecords'] = 'backend/dashboard/listRecords';
$route['profile/view'] = 'backend/profile/view';
$route['profile/update'] = 'backend/profile/update';
$route['profile/updatePassword'] = 'backend/profile/updatePassword';
$route['profile/passwordUpdate'] = 'backend/profile/passwordUpdate';
$route['packages/view'] = 'backend/packages/view';
$route['packages/update'] = 'backend/packages/update';
$route['packages/edit/(:any)'] = 'backend/packages/edit/$1';
$route['packages/add'] = 'backend/packages/add';
$route['client/listRecords'] = 'backend/client/listRecords';
$route['client/getList'] = 'backend/client/getList';
$route['client/sendEmail'] = 'backend/client/sendEmail';
$route['client/sendSms'] = 'backend/client/sendSms';
$route['client/getSupermarketList'] = 'backend/client/getSupermarketList';
$route['client/view/(:any)'] = 'backend/client/resview/$1';
$route['supermarketclients/listRecords'] = 'backend/client/supermarket';
$route['profile/view'] = 'backend/profile/view';
$route['users/listRecords'] = 'backend/users/listRecords';
$route['users/getList'] = 'backend/users/getList';
$route['users/add'] = 'backend/users/add';
$route['users/save'] = 'backend/users/save';
$route['users/update'] = 'backend/users/update';
$route['users/edit/(:any)'] = 'backend/users/edit/$1';
$route['users/view/(:any)'] = 'backend/users/view/$1';
$route['users/delete'] = 'backend/users/delete';
$route['rolewiserights/listRecords'] = 'backend/RoleWiseRights/listRecords';
$route['rolewiserights/getMenuList'] = 'backend/RoleWiseRights/getMenuList';
$route['rolewiserights/saveMenu'] = 'backend/RoleWiseRights/saveMenu';
$route['payment/listRecords'] = 'backend/payments/listRecords';
$route['payment/getList'] = 'backend/payments/getList';
$route['payment/view/(:any)'] = 'backend/payments/view/$1';
$route['payment/expiryPlan'] = 'backend/payments/expiryPlan';
$route['payment/extraDays'] = 'backend/payments/extraDays';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
