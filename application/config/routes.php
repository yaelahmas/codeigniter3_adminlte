<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['logout'] = 'login/logout';
$route['reset-password/(:any)'] = 'reset_password/index/$1';
// $route['login']['GET'] = 'login/index';
// $route['login']['POST'] = 'login/submit';

// $route['register']['GET'] = 'register/index';
// $route['register']['POST'] = 'register/submit';

// $route['verify/(:any)']['GET'] = 'verify/index';
