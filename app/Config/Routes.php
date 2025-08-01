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
$routes->get('/home', 'Home::index', ['filter' => 'auth']); // Halaman dashboard setelah login, bisa diganti ke AuthController::dashboard jika ada

// Rute untuk Anggota CRUD (tetap sama seperti sebelumnya)
$routes->group('anggota', ['filter' => 'auth'], function($routes) { // Tambahkan filter 'auth'
    $routes->get('/', 'AnggotaController::index');
    $routes->get('new', 'AnggotaController::new');
    $routes->post('create', 'AnggotaController::create');
    $routes->get('edit/(:num)', 'AnggotaController::edit/$1');
    $routes->post('update/(:num)', 'AnggotaController::update/$1');
    $routes->get('delete/(:num)', 'AnggotaController::delete/$1');
    $routes->post('delete/(:num)', 'AnggotaController::delete/$1'); // Tambahkan ini

    $routes->get('show/(:num)', 'AnggotaController::show/$1');
});

// Rute untuk Bunga CRUD (tetap sama seperti sebelumnya)
$routes->group('bunga', ['filter' => 'auth'], function($routes) { // Tambahkan filter 'auth'
    $routes->get('/', 'BungaController::index');
    $routes->get('new', 'BungaController::new');
    $routes->post('create', 'BungaController::create');
    $routes->get('edit/(:num)', 'BungaController::edit/$1');
    $routes->post('update/(:num)', 'BungaController::update/$1');
    $routes->get('delete/(:num)', 'BungaController::delete/$1');
     $routes->post('delete/(:num)', 'AnggotaController::delete/$1');
    $routes->get('show/(:num)', 'BungaController::show/$1');
});

// Rute untuk Pencairan CRUD (tetap sama seperti sebelumnya)
$routes->group('pencairan', ['filter' => 'auth'], function($routes) { // Tambahkan filter 'auth'
    $routes->get('/', 'PencairanController::index');
    $routes->get('new', 'PencairanController::new');
    $routes->post('create', 'PencairanController::create');
    $routes->get('edit/(:num)', 'PencairanController::edit/$1');
    $routes->post('update/(:num)', 'PencairanController::update/$1');
    $routes->get('delete/(:num)', 'PencairanController::delete/$1');
    $routes->get('show/(:num)', 'PencairanController::show/$1');
});

// Rute untuk User CRUD (tetap sama seperti sebelumnya)
$routes->group('user', ['filter' => 'auth'], function($routes) { // Tambahkan filter 'auth'
    $routes->get('/', 'UserController::index');
    $routes->get('new', 'UserController::new');
    $routes->post('create', 'UserController::create');
    $routes->get('edit/(:num)', 'UserController::edit/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('delete/(:num)', 'UserController::delete/$1');
    $routes->get('show/(:num)', 'UserController::show/$1');
});

// Rute untuk Angsuran CRUD (tetap sama seperti sebelumnya)
$routes->group('angsuran', ['filter' => 'auth'], function($routes) { // Tambahkan filter 'auth'
    $routes->get('/', 'AngsuranController::index');
    $routes->get('new', 'AngsuranController::new');
    $routes->post('create', 'AngsuranController::create');
    $routes->get('edit/(:num)', 'AngsuranController::edit/$1');
    $routes->post('update/(:num)', 'AngsuranController::update/$1');
    $routes->get('delete/(:num)', 'AngsuranController::delete/$1');
    $routes->get('show/(:num)', 'AngsuranController::show/$1');
});

// Rute untuk Pembayaran Angsuran CRUD (tetap sama seperti sebelumnya)
$routes->group('pembayaran-angsuran', ['filter' => 'auth'], function($routes) { // Tambahkan filter 'auth'
    $routes->get('/', 'PembayaranAngsuranController::index');
    $routes->get('new', 'PembayaranAngsuranController::new');
    $routes->post('create', 'PembayaranAngsuranController::create');
    $routes->get('edit/(:num)', 'PembayaranAngsuranController::edit/$1');
    $routes->post('update/(:num)', 'PembayaranAngsuranController::update/$1');
    $routes->get('delete/(:num)', 'PembayaranAngsuranController::delete/$1');
    $routes->get('show/(:num)', 'PembayaranAngsuranController::show/$1');
});

// Rute untuk Kredit CRUD (tetap sama seperti sebelumnya)
$routes->group('kredit', ['filter' => 'auth'], function($routes) { // Tambahkan filter 'auth'
    $routes->get('/', 'KreditController::index');
    $routes->get('new', 'KreditController::new');
    $routes->post('create', 'KreditController::create');
    $routes->get('edit/(:num)', 'KreditController::edit/$1');
    $routes->post('update/(:num)', 'KreditController::update/$1');
    $routes->get('delete/(:num)', 'KreditController::delete/$1');
    $routes->get('show/(:num)', 'KreditController::show/$1');
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
