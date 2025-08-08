<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PopulateNoAnggotaField extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // Get all anggota records that don't have no_anggota populated
        $query = $db->query("SELECT id_anggota FROM tbl_anggota WHERE no_anggota IS NULL OR no_anggota = ''");
        $results = $query->getResultArray();
        
        foreach ($results as $row) {
            $id = $row['id_anggota'];
            // Generate member number format: KOPL-XXXX (KOPL = Koperasi + Kredit, padded to 4 digits)
            $memberNumber = 'KOPL-' . str_pad($id, 4, '0', STR_PAD_LEFT);
            
            // Update the record
            $db->query("UPDATE tbl_anggota SET no_anggota = ? WHERE id_anggota = ?", [$memberNumber, $id]);
        }
        
        echo "Populated no_anggota field for " . count($results) . " records.\n";
    }

    public function down()
    {
        // Set no_anggota back to NULL for all records
        $db = \Config\Database::connect();
        $db->query("UPDATE tbl_anggota SET no_anggota = NULL");
    }
}