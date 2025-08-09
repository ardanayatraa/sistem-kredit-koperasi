<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StandardizeStatusFields extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // 1. Fix tbl_anggota - update status_keanggotaan to include 'Tidak Aktif'
        // Add 'Tidak Aktif' to ENUM if not exists
        try {
            $db->query("ALTER TABLE tbl_anggota MODIFY COLUMN status_keanggotaan ENUM('Menunggu', 'Aktif', 'Tidak Aktif', 'Ditolak') DEFAULT 'Menunggu'");
            echo "Updated tbl_anggota.status_keanggotaan ENUM values.\n";
        } catch (\Exception $e) {
            echo "Warning: Could not update tbl_anggota.status_keanggotaan: " . $e->getMessage() . "\n";
        }

        // 2. Fix tbl_kredit - change VARCHAR to ENUM for consistency
        try {
            // Update existing data to standard values first
            $db->query("UPDATE tbl_kredit SET status_aktif = 'Aktif' WHERE status_aktif NOT IN ('Aktif', 'Tidak Aktif') OR status_aktif IS NULL");
            
            // Change to ENUM
            $db->query("ALTER TABLE tbl_kredit MODIFY COLUMN status_aktif ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif'");
            echo "Updated tbl_kredit.status_aktif to ENUM.\n";
        } catch (\Exception $e) {
            echo "Warning: Could not update tbl_kredit.status_aktif: " . $e->getMessage() . "\n";
        }

        // 3. Fix tbl_pencairan - change VARCHAR to ENUM for consistency
        try {
            // Update existing data to standard values first
            $db->query("UPDATE tbl_pencairan SET status_aktif = 'Aktif' WHERE status_aktif NOT IN ('Aktif', 'Tidak Aktif') OR status_aktif IS NULL");
            
            // Change to ENUM
            $db->query("ALTER TABLE tbl_pencairan MODIFY COLUMN status_aktif ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif'");
            echo "Updated tbl_pencairan.status_aktif to ENUM.\n";
        } catch (\Exception $e) {
            echo "Warning: Could not update tbl_pencairan.status_aktif: " . $e->getMessage() . "\n";
        }

        // 4. Fix tbl_pembayaran_angsuran - change VARCHAR to ENUM for consistency
        try {
            // Update existing data to standard values first
            $db->query("UPDATE tbl_pembayaran_angsuran SET status_aktif = 'Aktif' WHERE status_aktif NOT IN ('Aktif', 'Tidak Aktif') OR status_aktif IS NULL");
            
            // Change to ENUM
            $db->query("ALTER TABLE tbl_pembayaran_angsuran MODIFY COLUMN status_aktif ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif'");
            echo "Updated tbl_pembayaran_angsuran.status_aktif to ENUM.\n";
        } catch (\Exception $e) {
            echo "Warning: Could not update tbl_pembayaran_angsuran.status_aktif: " . $e->getMessage() . "\n";
        }

        // 5. Fix tbl_users - standardize status field to Indonesian
        try {
            // Update existing data from English to Indonesian
            $db->query("UPDATE tbl_users SET status = 'Aktif' WHERE status = 'active'");
            $db->query("UPDATE tbl_users SET status = 'Tidak Aktif' WHERE status = 'inactive'");
            
            // Change to ENUM
            $db->query("ALTER TABLE tbl_users MODIFY COLUMN status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif'");
            echo "Updated tbl_users.status to Indonesian ENUM.\n";
        } catch (\Exception $e) {
            echo "Warning: Could not update tbl_users.status: " . $e->getMessage() . "\n";
        }

        // 6. tbl_bunga already has correct ENUM format, just verify
        try {
            $db->query("UPDATE tbl_bunga SET status_aktif = 'Aktif' WHERE status_aktif NOT IN ('Aktif', 'Tidak Aktif') OR status_aktif IS NULL");
            echo "Verified tbl_bunga.status_aktif consistency.\n";
        } catch (\Exception $e) {
            echo "Warning: Could not verify tbl_bunga.status_aktif: " . $e->getMessage() . "\n";
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        // Revert changes - this is complex due to ENUM modifications
        // For safety, we'll leave the new structure but could revert values if needed
        
        try {
            // Revert tbl_users status back to English
            $db->query("UPDATE tbl_users SET status = 'active' WHERE status = 'Aktif'");
            $db->query("UPDATE tbl_users SET status = 'inactive' WHERE status = 'Tidak Aktif'");
            $db->query("ALTER TABLE tbl_users MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active'");
            echo "Reverted tbl_users.status to English.\n";
        } catch (\Exception $e) {
            echo "Warning: Could not revert tbl_users.status: " . $e->getMessage() . "\n";
        }
    }
}