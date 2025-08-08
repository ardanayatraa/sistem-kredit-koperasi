<?php

// Simple database verification script
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sistem_kredit_koperasi';

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== DATABASE VERIFICATION ===\n";

echo "1. Checking tbl_kredit:\n";
$result = $db->query("SELECT COUNT(*) as total FROM tbl_kredit");
$count = $result->getRow()->total;
echo "   Total kredit records: {$count}\n";

echo "\n2. Checking tbl_angsuran:\n";
$result = $db->query("SELECT COUNT(*) as total FROM tbl_angsuran");
$count = $result->getRow()->total;
echo "   Total angsuran records: {$count}\n";

echo "\n3. Checking tbl_pencairan:\n";
$result = $db->query("SELECT COUNT(*) as total FROM tbl_pencairan");
$count = $result->getRow()->total;
echo "   Total pencairan records: {$count}\n";

echo "\n4. Checking tbl_pembayaran_angsuran:\n";
$result = $db->query("SELECT COUNT(*) as total FROM tbl_pembayaran_angsuran");
$count = $result->getRow()->total;
echo "   Total pembayaran records: {$count}\n";

echo "\n5. Checking tbl_users:\n";
$result = $db->query("SELECT COUNT(*) as total FROM tbl_users");
$count = $result->getRow()->total;
echo "   Total user records: {$count}\n";

echo "\n6. Checking tbl_anggota:\n";
$result = $db->query("SELECT COUNT(*) as total FROM tbl_anggota");
$count = $result->getRow()->total;
echo "   Total anggota records: {$count}\n";

echo "\n7. Checking specific anggota data (dokumen fields):\n";
$result = $db->query("SELECT no_anggota, dokumen_ktp, dokumen_kk, dokumen_slip_gaji FROM tbl_anggota LIMIT 3");
foreach ($result->getResult() as $row) {
    echo "   {$row->no_anggota}: KTP={$row->dokumen_ktp}, KK={$row->dokumen_kk}, Slip={$row->dokumen_slip_gaji}\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";