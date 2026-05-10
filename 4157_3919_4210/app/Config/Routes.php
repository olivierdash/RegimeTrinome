<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::registerIdentity');
$routes->post('register', 'Auth::saveIdentity');
$routes->get('register/sante', 'Auth::registerHealth');
$routes->post('register/sante', 'Auth::saveHealth');

$routes->get('dashboard', 'User::dashboard');
$routes->get('dashboard/profil', 'User::profile');
$routes->post('dashboard/profil', 'User::updateProfile');
$routes->get('dashboard/regimes', 'User::regimes');
$routes->post('dashboard/regimes/acheter', 'User::buyRegime');
$routes->get('dashboard/wallet', 'User::wallet');
$routes->post('dashboard/wallet/recharge', 'User::requestRecharge');
$routes->post('dashboard/wallet/gold', 'User::activateGold');
$routes->get('dashboard/suivi', 'User::history');
$routes->get('dashboard/export/(:num)', 'User::exportPurchase/$1');

$routes->get('admin/login', 'Admin::login');
$routes->post('admin/login', 'Admin::attemptLogin');
$routes->get('admin/logout', 'Admin::logout');
$routes->get('admin', 'Admin::dashboard');
$routes->post('admin/recharges/valider/(:num)', 'Admin::validateRecharge/$1');
$routes->post('admin/recharges/refuser/(:num)', 'Admin::rejectRecharge/$1');
$routes->get('admin/regimes', 'Admin::regimes');
$routes->post('admin/regimes', 'Admin::saveRegime');
$routes->post('admin/regimes/delete/(:num)', 'Admin::deleteRegime/$1');
$routes->get('admin/activites', 'Admin::activities');
$routes->post('admin/activites', 'Admin::saveActivity');
$routes->post('admin/activites/delete/(:num)', 'Admin::deleteActivity/$1');
$routes->get('admin/objectifs', 'Admin::objectives');
$routes->post('admin/objectifs', 'Admin::saveObjective');
$routes->post('admin/objectifs/delete/(:num)', 'Admin::deleteObjective/$1');
$routes->get('admin/codes', 'Admin::codes');
$routes->post('admin/codes', 'Admin::saveCode');
$routes->post('admin/codes/delete/(:num)', 'Admin::deleteCode/$1');
