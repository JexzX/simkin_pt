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
$routes->get('/skp/edit/(:num)', 'Skp::edit/$1', ['filter' => 'auth']);
$routes->post('/skp/update/(:num)', 'Skp::update/$1', ['filter' => 'auth']);
$routes->post('/skp/submit/(:num)', 'Skp::submit/$1', ['filter' => 'auth']);
$routes->get('/skp/delete/(:num)', 'Skp::delete/$1', ['filter' => 'auth']);

// RHK routes
$routes->get('/rhk/create/(:num)', 'Rhk::create/$1', ['filter' => 'auth']);
$routes->post('/rhk/store', 'Rhk::store', ['filter' => 'auth']);
$routes->get('/rhk/edit/(:num)', 'Rhk::edit/$1', ['filter' => 'auth']);
$routes->post('/rhk/update/(:num)', 'Rhk::update/$1', ['filter' => 'auth']);
$routes->get('/rhk/delete/(:num)', 'Rhk::delete/$1', ['filter' => 'auth']);

// RHK Indikator routes
$routes->get('/rhk/indikator/create/(:num)', 'Rhk::indikatorCreate/$1', ['filter' => 'auth']);
$routes->post('/rhk/indikator/store', 'Rhk::indikatorStore', ['filter' => 'auth']);
$routes->get('/rhk/indikator/delete/(:num)', 'Rhk::indikatorDelete/$1', ['filter' => 'auth']);

// Realisasi routes
$routes->get('/realisasi', 'Realisasi::index', ['filter' => 'auth']);
$routes->get('/realisasi/create/(:num)', 'Realisasi::create/$1', ['filter' => 'auth']);
$routes->post('/realisasi/store', 'Realisasi::store', ['filter' => 'auth']);
$routes->get('/realisasi/submit/(:num)', 'Realisasi::submit/$1', ['filter' => 'auth']);
$routes->post('/realisasi/approve/(:num)', 'Realisasi::approve/$1', ['filter' => 'auth']);
$routes->post('/realisasi/reject/(:num)', 'Realisasi::reject/$1', ['filter' => 'auth']);

// Realisasi approval by atasan
$routes->get('/approval/realisasi', 'Realisasi::approvalList', ['filter' => 'role:rektor,dekan,kaprodi,super_admin']);

// Approval routes (hanya untuk atasan: rektor, dekan, kaprodi)
$routes->get('/approval/skp', 'Approval::skpList', ['filter' => 'role:rektor,dekan,kaprodi,super_admin']);
$routes->post('/approval/skp/approve/(:num)', 'Approval::approveSkp/$1', ['filter' => 'role:rektor,dekan,kaprodi,super_admin']);
$routes->post('/approval/skp/reject/(:num)', 'Approval::rejectSkp/$1', ['filter' => 'role:rektor,dekan,kaprodi,super_admin']);

// Penilaian routes (atasan menilai bawahan)
$routes->get('/penilaian', 'Penilaian::index', ['filter' => 'role:rektor,dekan,kaprodi,super_admin']);
$routes->get('/penilaian/create/(:num)', 'Penilaian::create/$1', ['filter' => 'role:rektor,dekan,kaprodi,super_admin']);
$routes->post('/penilaian/store', 'Penilaian::store', ['filter' => 'role:rektor,dekan,kaprodi,super_admin']);

// Laporan routes
$routes->get('/laporan/skp', 'Laporan::skp', ['filter' => 'auth']);
$routes->get('/laporan/realisasi', 'Laporan::realisasi', ['filter' => 'auth']);
$routes->get('/laporan/export/(:any)', 'Laporan::export/$1', ['filter' => 'auth']);

// Profile routes
$routes->get('/profil', 'Profile::index', ['filter' => 'auth']);
$routes->post('/profil/update', 'Profile::update', ['filter' => 'auth']);
$routes->post('/profil/change-password', 'Profile::changePassword', ['filter' => 'auth']);

// Periode management routes (admin_perencana & super_admin)
$routes->group('periode', ['filter' => 'role:admin_perencana,super_admin'], function($routes) {
    $routes->get('/', 'Periode::index');
    $routes->get('create', 'Periode::create');
    $routes->post('store', 'Periode::store');
    $routes->get('edit/(:num)', 'Periode::edit/$1');
    $routes->post('update/(:num)', 'Periode::update/$1');
    $routes->get('delete/(:num)', 'Periode::delete/$1');
    $routes->get('toggle-active/(:num)', 'Periode::toggleActive/$1');
});

// User management routes (super admin only with RoleFilter)
$routes->group('user', ['filter' => 'role:super_admin'], function($routes) {
    $routes->get('/', 'UserManagement::index');
    $routes->get('create', 'UserManagement::create');
    $routes->post('store', 'UserManagement::store');
    $routes->get('edit/(:num)', 'UserManagement::edit/$1');
    $routes->post('update/(:num)', 'UserManagement::update/$1');
    $routes->get('delete/(:num)', 'UserManagement::delete/$1');
    $routes->get('reset-password/(:num)', 'UserManagement::resetPassword/$1');
});