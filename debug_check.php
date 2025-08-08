<?php

require 'vendor/autoload.php';

use App\Models\UserModel;
use App\Models\KreditModel;
use App\Models\PencairanModel;
use App\Models\AngsuranModel;

$config = new \Config\Database();
$db = \Config\Database::connect();

echo "=== DEBUG CHECK DATA ===\n\n";

// Check users table
$users = $db->query("SELECT user_id, username, nama_lengkap, id_anggota_ref FROM users WHERE username = 'anggota_demo'")->getRowArray();
echo "User anggota_demo: ";
print_r($users);
echo "\n";

if ($users && $users['id_anggota_ref']) {
    $idAnggota = $users['id_anggota_ref'];
    
    // Check kredit for this anggota
    $kredit = $db->query("SELECT * FROM kredit WHERE id_anggota = ?", [$idAnggota])->getResultArray();
    echo "Kredit data for anggota ID $idAnggota:\n";
    print_r($kredit);
    echo "\n";
    
    if ($kredit) {
        foreach ($kredit as $k) {
            $idKredit = $k['id_kredit'];
            
            // Check pencairan for this kredit
            $pencairan = $db->query("SELECT * FROM pencairan WHERE id_kredit = ?", [$idKredit])->getResultArray();
            echo "Pencairan data for kredit ID $idKredit:\n";
            print_r($pencairan);
            echo "\n";
            
            // Check angsuran for this kredit
            $angsuran = $db->query("SELECT * FROM angsuran WHERE id_kredit = ?", [$idKredit])->getResultArray();
            echo "Angsuran data for kredit ID $idKredit:\n";
            print_r($angsuran);
            echo "\n";
        }
    }
    
    // Check angsuran by anggota ID directly
    $angsuranByAnggota = $db->query("SELECT * FROM angsuran WHERE id_anggota = ?", [$idAnggota])->getResultArray();
    echo "Angsuran data by anggota ID $idAnggota:\n";
    print_r($angsuranByAnggota);
    echo "\n";
}

echo "=== END DEBUG ===\n";