<?php
require_once 'vendor/autoload.php';

// Initialize CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

// Get database connection
$db = \Config\Database::connect();

echo "Testing status_verifikasi field...\n\n";

// Check if field exists in table structure
try {
    $fields = $db->getFieldData('tbl_pembayaran_angsuran');
    $hasStatusVerifikasi = false;
    
    echo "Fields in tbl_pembayaran_angsuran:\n";
    foreach ($fields as $field) {
        echo "- {$field->name} ({$field->type})\n";
        if ($field->name === 'status_verifikasi') {
            $hasStatusVerifikasi = true;
        }
    }
    
    if ($hasStatusVerifikasi) {
        echo "\n✅ Field status_verifikasi berhasil ditambahkan!\n";
        
        // Test insert with new field
        $testData = [
            'id_angsuran' => 1, // pastikan ID ini exist
            'tanggal_bayar' => date('Y-m-d'),
            'jumlah_bayar' => 100000,
            'metode_pembayaran' => 'transfer',
            'denda' => 0,
            'id_bendahara_verifikator' => 1,
            'status_verifikasi' => 'pending'
        ];
        
        echo "\nTest data would be: \n";
        print_r($testData);
        echo "\n(Data tidak akan di-insert untuk keamanan)\n";
        
    } else {
        echo "\n❌ Field status_verifikasi tidak ditemukan!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}