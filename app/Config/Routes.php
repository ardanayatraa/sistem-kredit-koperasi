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

// Dashboard routes for each role
$routes->get('/home', 'Home::index', ['filter' => 'auth']); // General dashboard
$routes->get('/dashboard-bendahara', 'Home::dashboardBendahara', ['filter' => 'auth']);
$routes->get('/dashboard-ketua', 'Home::dashboardKetua', ['filter' => 'auth']);
$routes->get('/dashboard-appraiser', 'Home::dashboardAppraiser', ['filter' => 'auth']);
$routes->get('/dashboard-anggota', 'Home::dashboardAnggota', ['filter' => 'auth']);

// Beranda route
$routes->get('/beranda', 'Home::beranda', ['filter' => 'role:view_beranda']);

// Riwayat Penilaian routes
$routes->group('riwayat-penilaian', ['filter' => 'role:view_riwayat_penilaian'], function($routes) {
    $routes->get('/', 'RiwayatPenilaianController::index');
    $routes->get('(:num)', 'RiwayatPenilaianController::show/$1'); // Direct access with ID
    $routes->get('show/(:num)', 'RiwayatPenilaianController::show/$1');
    $routes->get('print/(:num)', 'RiwayatPenilaianController::print/$1');
});

// Data Agunan routes
$routes->group('agunan', ['filter' => 'role:manage_agunan'], function($routes) {
    $routes->get('/', 'AgunanController::index');
    $routes->get('new', 'AgunanController::new');
    $routes->post('create', 'AgunanController::create');
    $routes->get('edit/(:num)', 'AgunanController::edit/$1');
    $routes->post('update/(:num)', 'AgunanController::update/$1');
    $routes->get('delete/(:num)', 'AgunanController::delete/$1');
    $routes->get('show/(:num)', 'AgunanController::show/$1');
    $routes->get('nilai/(:num)', 'AgunanController::nilai/$1');
    $routes->post('simpan-nilai/(:num)', 'AgunanController::simpanNilai/$1');
    $routes->get('print/(:num)', 'AgunanController::print/$1');
});

// Daftar Agunan untuk Appraiser
$routes->group('daftar-agunan', ['filter' => 'role:view_daftar_agunan'], function($routes) {
    $routes->get('/', 'AgunanController::daftarAgunan');
    $routes->get('show/(:num)', 'AgunanController::show/$1');
});

// Verifikasi Agunan routes
$routes->group('verifikasi-agunan', ['filter' => 'role:verify_agunan'], function($routes) {
    $routes->get('/', 'AgunanController::verifikasi');
    $routes->post('proses', 'AgunanController::prosesVerifikasi');
    $routes->post('approve/(:num)', 'AgunanController::approve/$1');
    $routes->post('reject/(:num)', 'AgunanController::reject/$1');
    $routes->get('detail/(:num)', 'AgunanController::detailVerifikasi/$1');
    $routes->get('dokumen/(:num)', 'AgunanController::dokumen/$1');
});

// Detail Kredit Anggota untuk Ketua
$routes->group('detail-kredit-anggota', ['filter' => 'role:view_detail_kredit_anggota'], function($routes) {
    $routes->get('/', 'KreditController::detailKreditAnggota');
    $routes->get('show/(:num)', 'KreditController::showDetailAnggota/$1');
});

// Laporan Kredit Koperasi untuk Ketua
$routes->group('laporan-kredit-koperasi', ['filter' => 'role:view_laporan_kredit_koperasi'], function($routes) {
    $routes->get('/', 'LaporanKreditController::koperasi');
    $routes->get('generate-pdf', 'LaporanKreditController::generatePdfKoperasi');
    $routes->get('export-excel', 'LaporanKreditController::exportExcelKoperasi');
});

// Riwayat Persetujuan untuk Ketua
$routes->group('riwayat-persetujuan', ['filter' => 'role:view_riwayat_persetujuan'], function($routes) {
    $routes->get('/', 'RiwayatPersetujuanController::index');
    $routes->get('show/(:num)', 'RiwayatPersetujuanController::show/$1');
});

// Riwayat Kredit untuk Anggota
$routes->group('riwayat-kredit', ['filter' => 'role:view_riwayat_kredit'], function($routes) {
    $routes->get('/', 'RiwayatKreditController::index');
    $routes->get('(:num)', 'RiwayatKreditController::show/$1'); // Direct access with ID
    $routes->get('show/(:num)', 'RiwayatKreditController::show/$1');
    $routes->get('print/(:num)', 'RiwayatKreditController::print/$1');
    
    // Routes untuk Surat Persetujuan
    $routes->get('surat-persetujuan/(:num)', 'RiwayatKreditController::suratPersetujuan/$1');
    $routes->get('download-surat/(:num)', 'RiwayatKreditController::downloadSuratPersetujuan/$1');
    $routes->get('cek-status/(:num)', 'RiwayatKreditController::cekStatusPersetujuan/$1');
});

// Riwayat Pembayaran untuk Anggota
$routes->group('riwayat-pembayaran', ['filter' => 'role:view_riwayat_pembayaran'], function($routes) {
    $routes->get('/', 'RiwayatPembayaranController::index');
    $routes->get('show/(:num)', 'RiwayatPembayaranController::show/$1');
    $routes->get('print/(:num)', 'RiwayatPembayaranController::print/$1');
});

// Simulasi Bunga untuk Anggota
$routes->group('simulasi-bunga', ['filter' => 'role:view_simulasi_bunga'], function($routes) {
    $routes->get('/', 'SimulasiBungaController::index');
    $routes->post('calculate', 'SimulasiBungaController::calculate');
});

// Ganti Password route untuk semua user
$routes->group('change-password', ['filter' => 'role:change_password'], function($routes) {
    $routes->get('/', 'UserController::changePassword');
    $routes->post('update', 'UserController::updatePassword');
});

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

// Rute untuk Angsuran CRUD (hanya untuk admin/bendahara)
$routes->group('angsuran', ['filter' => 'role:manage_angsuran'], function($routes) {
    $routes->get('/', 'AngsuranController::index');
    $routes->get('new', 'AngsuranController::new');
    $routes->post('create', 'AngsuranController::create');
    $routes->get('edit/(:num)', 'AngsuranController::edit/$1');
    $routes->post('update/(:num)', 'AngsuranController::update/$1');
    $routes->get('delete/(:num)', 'AngsuranController::delete/$1');
    $routes->get('show/(:num)', 'AngsuranController::show/$1');
    
    // Routes untuk Flow Pembayaran Kredit (admin only)
    $routes->post('generate-jadwal/(:num)', 'AngsuranController::generateJadwalAngsuran/$1');
    $routes->get('jadwal/(:num)', 'AngsuranController::lihatJadwal/$1');
});

// Dashboard Pembayaran untuk Anggota
$routes->group('dashboard-pembayaran', ['filter' => 'role:view_riwayat_pembayaran'], function($routes) {
    $routes->get('(:num)', 'AngsuranController::dashboardPembayaran/$1');
});

// Routes Pembayaran Khusus untuk Anggota
$routes->group('pembayaran', ['filter' => 'role:view_riwayat_pembayaran'], function($routes) {
    $routes->get('jadwal/(:num)', 'AngsuranController::lihatJadwal/$1'); // Lihat jadwal angsuran
    $routes->get('bayar/(:num)', 'AngsuranController::bayarAngsuran/$1'); // Form pembayaran angsuran
    $routes->post('proses-bayar/(:num)', 'AngsuranController::prosesBayar/$1'); // Proses pembayaran
});

// Routes khusus untuk anggota - pembayaran angsuran
$routes->group('bayar-angsuran', ['filter' => 'role:view_riwayat_pembayaran'], function($routes) {
    $routes->get('/', 'AngsuranController::bayarAngsuran'); // Daftar angsuran untuk dibayar
    $routes->get('(:num)', 'AngsuranController::bayarAngsuran/$1'); // Form pembayaran spesifik
    $routes->post('proses/(:num)', 'AngsuranController::prosesBayar/$1'); // Proses pembayaran
});

// Debug route (temporary)
$routes->get('debug/check-anggota', 'DebugController::checkAnggotaData');

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
    
    // Routes untuk halaman dan proses verifikasi pembayaran (untuk Bendahara/Admin)
    $routes->get('verifikasi', 'PembayaranAngsuranController::verifikasi');
    $routes->get('verifikasi-detail/(:num)', 'PembayaranAngsuranController::verifikasiDetail/$1');
    $routes->post('verifikasi-approve/(:num)', 'PembayaranAngsuranController::verifikasiApprove/$1');
    $routes->post('verifikasi-reject/(:num)', 'PembayaranAngsuranController::verifikasiReject/$1');
    $routes->post('verifikasi-pembayaran/(:num)', 'PembayaranAngsuranController::verifikasiPembayaran/$1');
    $routes->post('tolak-pembayaran/(:num)', 'PembayaranAngsuranController::tolakPembayaran/$1');
    
    // Route untuk AJAX get angsuran by anggota (untuk Bendahara)
    $routes->post('get-angsuran-by-anggota', 'PembayaranAngsuranController::getAngsuranByAnggota');
    $routes->post('get-detail-angsuran', 'PembayaranAngsuranController::getDetailAngsuran');
    
    // Routes untuk fitur bukti pembayaran (untuk Anggota)
    $routes->get('cetak-bukti/(:num)', 'PembayaranAngsuranController::cetakBukti/$1');
    $routes->get('download-bukti/(:num)', 'PembayaranAngsuranController::downloadBukti/$1');
    $routes->get('riwayat/(:num)', 'PembayaranAngsuranController::riwayatPembayaran/$1');
    $routes->get('riwayat', 'PembayaranAngsuranController::riwayatPembayaran'); // Auto-detect from session
});

// Rute untuk Kredit CRUD
$routes->group('kredit', ['filter' => 'role:manage_kredit'], function($routes) {
    $routes->get('/', 'KreditController::index');
    $routes->get('new', 'KreditController::new');
    $routes->post('create', 'KreditController::create');
    $routes->get('edit/(:num)', 'KreditController::edit/$1');
    $routes->post('update/(:num)', 'KreditController::update/$1');
    $routes->get('delete/(:num)', 'KreditController::delete/$1');
    $routes->post('delete/(:num)', 'KreditController::delete/$1');
    $routes->get('show/(:num)', 'KreditController::show/$1');
    $routes->post('toggle-status/(:num)', 'KreditController::toggleStatus/$1');
    $routes->post('verify-agunan', 'KreditController::verifyAgunan');
});

// 🔥 WORKFLOW ROUTES - NO FILTER RESTRICTIONS (Role checking in controller)
$routes->group('kredit', ['filter' => 'auth'], function($routes) {
    // ALUR KOPERASI MITRA SEJAHTRA: Workflow Management Routes
    $routes->get('pengajuan-untuk-role', 'KreditController::pengajuanUntukRole');
    $routes->get('verifikasi-bendahara/(:num)', 'KreditController::verifikasiBendahara/$1');
    $routes->post('verifikasi-bendahara/(:num)', 'KreditController::verifikasiBendahara/$1');
    $routes->get('penilaian-appraiser/(:num)', 'KreditController::penilaianAppraiser/$1');
    $routes->post('penilaian-appraiser/(:num)', 'KreditController::penilaianAppraiser/$1');
    $routes->get('teruskan-hasil-appraiser/(:num)', 'KreditController::teruskanHasilAppraiser/$1');
    $routes->post('teruskan-hasil-appraiser/(:num)', 'KreditController::teruskanHasilAppraiser/$1');
    $routes->get('persetujuan-final/(:num)', 'KreditController::persetujuanFinal/$1');
    $routes->post('persetujuan-final/(:num)', 'KreditController::persetujuanFinal/$1');
    $routes->get('proses-pencairan/(:num)', 'KreditController::prosesPencairan/$1');
    $routes->post('proses-pencairan/(:num)', 'KreditController::prosesPencairan/$1');
    
    // Missing workflow routes that might be needed
    $routes->get('pengajuan-per-role', 'KreditController::pengajuanUntukRole'); // Alias untuk backward compatibility
    $routes->get('verifikasi-dokumen/(:num)', 'KreditController::verifikasiBendahara/$1'); // Alias untuk verifikasi
    $routes->post('verifikasi-dokumen/(:num)', 'KreditController::verifikasiBendahara/$1'); // POST untuk verifikasi
});
// Dashboard anggota route (create if missing)
$routes->group('dashboard-anggota', ['filter' => 'role:view_riwayat_kredit'], function($routes) {
    $routes->get('/', 'Home::dashboardAnggota');
});

// Rute untuk Laporan Kredit (Full CRUD untuk Bendahara)
$routes->group('laporan-kredit', ['filter' => 'role:view_laporan_kredit'], function($routes) {
    $routes->get('/', 'LaporanKreditController::index');
    $routes->get('new', 'LaporanKreditController::new');
    $routes->post('create', 'LaporanKreditController::create');
    $routes->get('edit/(:num)', 'LaporanKreditController::edit/$1');
    $routes->post('update/(:num)', 'LaporanKreditController::update/$1');
    $routes->get('delete/(:num)', 'LaporanKreditController::delete/$1');
    $routes->post('delete/(:num)', 'LaporanKreditController::delete/$1');
    $routes->get('show/(:num)', 'LaporanKreditController::show/$1');
    $routes->post('toggle-status/(:num)', 'LaporanKreditController::toggleStatus/$1');
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

// Route untuk akses dokumen kredit dengan access control
$routes->get('dokumen_kredit/(:any)', 'KreditController::viewDocument/$1', ['filter' => 'auth']);

// Route untuk akses file pencairan dengan access control
$routes->get('uploads/pencairan/(:any)', 'PencairanController::viewDocument/$1', ['filter' => 'auth']);

// Route untuk akses dokumen anggota dengan access control
$routes->get('uploads/anggota/(:any)', 'AnggotaController::viewDocument/$1', ['filter' => 'auth']);

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
