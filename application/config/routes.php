<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['default_controller'] = 'Auth/login';
$route['register']='Auth/register';
$route["register_save"]='Auth/register_save';
$route['login']='Auth/login';
$route['login_check']='Auth/login_check';
$route["logout"]='Auth/logout';


$route['forgot']        = 'Auth/forgot';
$route['auth/send_otp'] = 'Auth/send_otp';
$route['verify_otp']    = 'Auth/verify_otp';
$route['auth/check_otp']= 'Auth/check_otp';
$route['new_password'] = 'Auth/new_password';
$route['auth/save_new_password'] = 'Auth/save_new_password';

$route['staff']='Staff';
$route['user']='User';
$route['admin']='Admin';

// $route['']='';
// $route['']='';


$route['admin/profile'] = 'Profile/admin';
$route['profile/update'] = 'Profile/update';
$route['profile/remove_photo'] = 'Profile/remove_photo';


$route['parking'] = 'Parking/index';
$route['parking/add'] = 'Parking/add';
$route['parking/store'] = 'Parking/store';
$route['parking/edit/(:num)'] = 'Parking/edit/$1';
$route['parking/update/(:num)'] = 'Parking/update/$1';
$route['parking/delete/(:num)'] = 'Parking/delete/$1';

$route['test_location'] = 'Parking/test_location';

$route['parking_login']='Parking/parking_login';
$route['parkingList']='Parking/get_all_parking';
$route['parking/get/(:num)'] = 'Parking/get_parking/$1';
$route['parking/update-ajax/(:num)'] = 'Parking/update_ajax/$1';
$route['parking/update-status'] = 'Parking/update_status';
$route['parking/search_location'] = 'Parking/search_location';
$route['parking/reverse_location'] = 'Parking/reverse_location';
$route['api/register'] = 'RegisterApi/register';

$route['api/users'] = 'RegisterApi/getUsers';

$route['watchman/list'] = 'Watchman/index';
$route['watchman/add']='Watchman/add';
$route['watchman/store'] = 'Watchman/store';
$route['watchman/edit/(:any)']='Watchman/edit/$1';
$route['watchman/update'] = 'Watchman/update';
$route['Watchman/delete/(:any)']='Watchman/delete';
$route['watchman/update_status'] = 'Watchman/update_status';
$route['watchman/login'] = 'Watchman/watchman_login';
$route['watchman/todays_bookings/(:num)'] = 'Watchman/todays_bookings/$1';



