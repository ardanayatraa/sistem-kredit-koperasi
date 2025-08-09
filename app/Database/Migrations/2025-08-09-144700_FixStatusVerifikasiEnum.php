<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixStatusVerifikasiEnum extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        try {
            // Update existing data from Indonesian to English standardized values
            $db->query("UPDATE tbl_kredit SET status_verifikasi = 'verified' WHERE status_verifikasi = 'Diterima'");
            $db->query("UPDATE tbl_kredit SET status_verifikasi = 'rejected' WHERE status_verifikasi = 'Ditolak'");
            
            // Update ENUM to include all possible values for consistency across system
            $db->query("ALTER TABLE tbl_kredit MODIFY COLUMN status_verifikasi ENUM('pending', 'verified', 'rejected', 'approved') DEFAULT NULL");
            
            echo "Updated status_verifikasi ENUM values for consistency.\n";
        } catch (\Exception $e) {
            echo "Warning: Could not update status_verifikasi ENUM: " . $e->getMessage() . "\n";
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        try {
            // Revert to original ENUM
            $db->query("ALTER TABLE tbl_kredit MODIFY COLUMN status_verifikasi ENUM('pending', 'verified', 'rejected') DEFAULT NULL");
            echo "Reverted status_verifikasi ENUM to original values.\n";
        } catch (\Exception $e) {
            echo "Warning: Could not revert status_verifikasi ENUM: " . $e->getMessage() . "\n";
        }
    }
}