<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PopulateStatusAktifBungaField extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // Update all existing bunga records
        // Set the first one (Konsumtif) to 'Aktif' as default, others to 'Tidak Aktif'
        $db->query("UPDATE tbl_bunga SET status_aktif = 'Aktif' WHERE nama_bunga = 'Bunga Kredit Konsumtif'");
        $db->query("UPDATE tbl_bunga SET status_aktif = 'Tidak Aktif' WHERE nama_bunga != 'Bunga Kredit Konsumtif'");
        
        echo "Updated status_aktif field for existing bunga records.\n";
    }

    public function down()
    {
        // Set status_aktif back to default for all records
        $db = \Config\Database::connect();
        $db->query("UPDATE tbl_bunga SET status_aktif = 'Aktif'");
    }
}