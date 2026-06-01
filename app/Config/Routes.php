<?php

namespace Config;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Auth routes
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// Dashboard (protected by filter)
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// SKP routes
$routes->get('/skp', 'Skp::index', ['filter' => 'auth']);
$routes->get('/skp/create', 'Skp::create', ['filter' => 'auth']);
$routes->post('/skp/store', 'Skp::store', ['filter' => 'auth']);
$routes->get('/skp/detail/(:num)', 'Skp::detail/$1', ['filter' => 'auth']);
$routes->post('/skp/submit/(:num)', 'Skp::submit/$1', ['filter' => 'auth']);
$routes->get('/skp/delete/(:num)', 'Skp::delete/$1', ['filter' => 'auth']);

// RHK routes
$routes->get('/rhk/create/(:num)', 'Rhk::create/$1', ['filter' => 'auth']);
$routes->post('/rhk/store', 'Rhk::store', ['filter' => 'auth']);
$routes->get('/rhk/edit/(:num)', 'Rhk::edit/$1', ['filter' => 'auth']);
$routes->post('/rhk/update/(:num)', 'Rhk::update/$1', ['filter' => 'auth']);
$routes->get('/rhk/delete/(:num)', 'Rhk::delete/$1', ['filter' => 'auth']);

// Realisasi routes
$routes->get('/realisasi', 'Realisasi::index', ['filter' => 'auth']);
$routes->get('/realisasi/create/(:num)', 'Realisasi::create/$1', ['filter' => 'auth']);
$routes->post('/realisasi/store', 'Realisasi::store', ['filter' => 'auth']);
$routes->get('/realisasi/submit/(:num)', 'Realisasi::submit/$1', ['filter' => 'auth']);

// Approval routes
$routes->get('/approval/skp', 'Approval::skpList', ['filter' => 'auth']);
$routes->post('/approval/skp/approve/(:num)', 'Approval::approveSkp/$1', ['filter' => 'auth']);
$routes->post('/approval/skp/reject/(:num)', 'Approval::rejectSkp/$1', ['filter' => 'auth']);

// Master data routes (admin only)
$routes->group('master', ['filter' => 'auth'], function($routes) {
    $routes->get('sp', 'Master::sp');
    $routes->post('sp/store', 'Master::storeSp');
    $routes->post('sp/delete/(:num)', 'Master::deleteSp/$1');
    
    $routes->get('sk', 'Master::sk');
    $routes->post('sk/store', 'Master::storeSk');
    $routes->post('sk/delete/(:num)', 'Master::deleteSk/$1');
    
    $routes->get('iksk', 'Master::iksk');
    $routes->post('iksk/store', 'Master::storeIksk');
    $routes->post('iksk/delete/(:num)', 'Master::deleteIksk/$1');
});

// User management routes (super admin only)
// PERHATIKAN: Tidak ada garis miring di depan (cukup 'create', bukan '/create')
$routes->group('user', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'UserManagement::index');
    $routes->get('create', 'UserManagement::create');
    $routes->post('store', 'UserManagement::store');
    $routes->get('edit/(:num)', 'UserManagement::edit/$1');
    $routes->post('update/(:num)', 'UserManagement::update/$1');
    $routes->get('delete/(:num)', 'UserManagement::delete/$1');
    $routes->get('reset-password/(:num)', 'UserManagement::resetPassword/$1');
});