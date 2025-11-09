<?php
require 'vendor/autoload.php';

$db = \Config\Database::connect();
$query = $db->query("SELECT id_kredit, dokumen_agunan, updated_at FROM tbl_kredit WHERE id_kredit = 10");
$result = $query->getRow();

if ($result) {
    echo "ID Kredit: " . $result->id_kredit . "\n";
    echo "Dokumen Agunan: " . $result->dokumen_agunan . "\n";
    echo "Updated At: " . $result->updated_at . "\n";
    
    $filePath = WRITEPATH . 'uploads/' . $result->dokumen_agunan;
    echo "\nFile Path: " . $filePath . "\n";
    echo "File Exists: " . (file_exists($filePath) ? 'YES' : 'NO') . "\n";
    
    if (file_exists($filePath)) {
        echo "File Size: " . filesize($filePath) . " bytes\n";
        echo "File Modified: " . date('Y-m-d H:i:s', filemtime($filePath)) . "\n";
    }
} else {
    echo "Kredit ID 10 tidak ditemukan\n";
}
