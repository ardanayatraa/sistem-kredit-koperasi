<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rute default diubah ke landing_page
$routes->get('/', 'Home::landingPage'); // Mengarahkan root URL ke method landingPage di Home Controller

// Rute Autentikasi
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::attemptRegister');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/home', 'Home::index', ['filter' => 'auth']); // Only require authentication

// Rute untuk Anggota CRUD
$routes->group('anggota', ['filter' => 'role:manage_anggota'], function($routes) {
    $routes->get('/', 'AnggotaController::index');
    $routes->get('new', 'AnggotaController::new');
    $routes->post('create', 'AnggotaController::create');
    $routes->get('edit/(:num)', 'AnggotaController::edit/$1');
    $routes->post('update/(:num)', 'AnggotaController::update/$1');
    $routes->get('delete/(:num)', 'AnggotaController::delete/$1');
    $routes->post('delete/(:num)', 'AnggotaController::delete/$1');
    $routes->get('show/(:num)', 'AnggotaController::show/$1');
    $routes->post('toggle-status/(:num)', 'AnggotaController::toggleStatus/$1');
});

// Rute untuk Bunga CRUD
$routes->group('bunga', ['filter' => 'role:manage_bunga'], function($routes) {
    $routes->get('/', 'BungaController::index');
    $routes->get('new', 'BungaController::new');
    $routes->post('create', 'BungaController::create');
    $routes->get('edit/(:num)', 'BungaController::edit/$1');
    $routes->post('update/(:num)', 'BungaController::update/$1');
    $routes->get('delete/(:num)', 'BungaController::delete/$1');
    $routes->post('delete/(:num)', 'BungaController::delete/$1'); // Fixed controller name
    $routes->get('show/(:num)', 'BungaController::show/$1');
});

// Rute untuk Pencairan CRUD
$routes->group('pencairan', ['filter' => 'role:manage_pencairan'], function($routes) {
    $routes->get('/', 'PencairanController::index');
    $routes->get('new', 'PencairanController::new');
    $routes->post('create', 'PencairanController::create');
    $routes->get('edit/(:num)', 'PencairanController::edit/$1');
    $routes->post('update/(:num)', 'PencairanController::update/$1');
    $routes->get('delete/(:num)', 'PencairanController::delete/$1');
    $routes->get('show/(:num)', 'PencairanController::show/$1');
    $routes->post('toggle-status/(:num)', 'PencairanController::toggleStatus/$1');
});

// Rute untuk User CRUD
$routes->group('user', ['filter' => 'role:manage_users'], function($routes) {
    $routes->get('/', 'UserController::index');
    $routes->get('new', 'UserController::new');
    $routes->post('create', 'UserController::create');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('delete/(:num)', 'UserController::delete/$1');
    $routes->get('show/(:num)', 'UserController::show/$1');
});

// Rute untuk Angsuran CRUD
$routes->group('angsuran', ['filter' => 'role:manage_angsuran'], function($routes) {
    $routes->get('/', 'AngsuranController::index');
    $routes->get('new', 'AngsuranController::new');
    $routes->post('create', 'AngsuranController::create');
    $routes->get('edit/(:num)', 'AngsuranController::edit/$1');
    $routes->post('update/(:num)', 'AngsuranController::update/$1');
    $routes->get('delete/(:num)', 'AngsuranController::delete/$1');
    $routes->get('show/(:num)', 'AngsuranController::show/$1');
});

// Rute untuk Pembayaran Angsuran CRUD
$routes->group('pembayaran-angsuran', ['filter' => 'role:manage_pembayaran_angsuran'], function($routes) {
    $routes->get('/', 'PembayaranAngsuranController::index');
    $routes->get('new', 'PembayaranAngsuranController::new');
    $routes->post('create', 'PembayaranAngsuranController::create');
    $routes->get('edit/(:num)', 'PembayaranAngsuranController::edit/$1');
    $routes->post('update/(:num)', 'PembayaranAngsuranController::update/$1');
    $routes->get('delete/(:num)', 'PembayaranAngsuranController::delete/$1');
    $routes->get('show/(:num)', 'PembayaranAngsuranController::show/$1');
    $routes->post('toggle-status/(:num)', 'PembayaranAngsuranController::toggleStatus/$1');
});

// Rute untuk Kredit CRUD
$routes->group('kredit', ['filter' => 'role:manage_kredit'], function($routes) {
    $routes->get('/', 'KreditController::index');
    $routes->get('new', 'KreditController::new');
    $routes->post('create', 'KreditController::create');
    $routes->get('edit/(:num)', 'KreditController::edit/$1');
    $routes->post('update/(:num)', 'KreditController::update/$1');
    $routes->get('delete/(:num)', 'KreditController::delete/$1');
    $routes->get('show/(:num)', 'KreditController::show/$1');
    $routes->post('toggle-status/(:num)', 'KreditController::toggleStatus/$1');
    $routes->post('verify-agunan', 'KreditController::verifyAgunan');
});

// Rute untuk Laporan Kredit
$routes->group('laporan-kredit', ['filter' => 'role:view_laporan_kredit'], function($routes) {
    $routes->get('/', 'LaporanKreditController::index');
    $routes->get('show/(:num)', 'LaporanKreditController::show/$1');
    $routes->get('generate-pdf/(:num)', 'LaporanKreditController::generatePdf/$1');
});

// Rute untuk Profile
$routes->group('profile', ['filter' => 'role:view_profile'], function($routes) {
    $routes->get('/', 'UserController::profile');
    $routes->post('update', 'UserController::updateProfile');
    $routes->get('complete-anggota-data', 'UserController::completeAnggotaData');
    $routes->post('save-anggota-data', 'UserController::saveAnggotaData');
    $routes->post('update-anggota', 'UserController::updateProfileAnggota');
});

// Rute untuk melayani file yang diunggah dari writable/uploads (tetap sama)
$routes->get('writable/uploads/(:segment)/(:any)', function($folder, $filename) {
    $path = WRITEPATH . 'uploads/' . $folder . '/' . $filename;
    if (file_exists($path)) {
        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        readfile($path);
        exit();
    }
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
});
