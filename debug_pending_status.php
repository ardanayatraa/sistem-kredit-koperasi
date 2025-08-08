<?php

// Bootstrap CodeIgniter application
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once FCPATH . 'vendor/autoload.php';

// Load CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

// Load models
$pembayaranModel = new \App\Models\PembayaranAngsuranModel();

echo "=== DEBUG PENDING PAYMENTS STATUS ===\n\n";

// Check all payments and their statuses
$allPayments = $pembayaranModel->findAll();
echo "Total payments in database: " . count($allPayments) . "\n\n";

// Count by status
$statusCounts = [];
foreach ($allPayments as $payment) {
    $status = $payment['status_verifikasi'] ?? 'NULL';
    $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
}

echo "Status breakdown:\n";
foreach ($statusCounts as $status => $count) {
    echo "- $status: $count payments\n";
}

// Check for old 'Menunggu' status and convert to 'pending'
$menunggुPayments = $pembayaranModel->where('status_verifikasi', 'Menunggu')->findAll();
if (!empty($menunggुPayments)) {
    echo "\n*** FOUND " . count($menunggुPayments) . " PAYMENTS WITH OLD 'Menunggu' STATUS ***\n";
    echo "Converting to 'pending'...\n";
    
    foreach ($menunggुPayments as $payment) {
        $pembayaranModel->update($payment['id_pembayaran'], ['status_verifikasi' => 'pending']);
        echo "- Updated payment ID {$payment['id_pembayaran']} from 'Menunggu' to 'pending'\n";
    }
    
    echo "Conversion completed!\n";
}

// Show pending payments that should appear in verification page
echo "\n=== PENDING PAYMENTS FOR VERIFICATION ===\n";
$pendingPayments = $pembayaranModel->getFilteredPembayaranWithData(
    ['tbl_pembayaran_angsuran.status_verifikasi' => 'pending'],
    'tbl_pembayaran_angsuran.*, tbl_angsuran.angsuran_ke, tbl_angsuran.jumlah_angsuran,
     tbl_kredit.id_kredit, tbl_users.nama_lengkap, tbl_anggota.no_anggota'
);

if (empty($pendingPayments)) {
    echo "No pending payments found for verification.\n";
} else {
    echo "Found " . count($pendingPayments) . " pending payments:\n\n";
    foreach ($pendingPayments as $payment) {
        echo "ID: {$payment['id_pembayaran']} | ";
        echo "Anggota: {$payment['nama_lengkap']} | ";
        echo "Angsuran Ke: {$payment['angsuran_ke']} | ";
        echo "Jumlah: Rp " . number_format($payment['jumlah_bayar'], 0, ',', '.') . " | ";
        echo "Tanggal: {$payment['tanggal_bayar']}\n";
    }
}

echo "\nDebug completed!\n";