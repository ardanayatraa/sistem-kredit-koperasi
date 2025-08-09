<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTanggalMasukAnggota extends Migration
{
    public function up()
    {
        // Add tanggal_masuk_anggota field to tbl_anggota table
        $fields = [
            'tanggal_masuk_anggota' => [
                'type' => 'DATE',
                'null' => false,
                'default' => date('Y-m-d'), // Default to today for existing records
                'after' => 'tanggal_pendaftaran'
            ]
        ];

        $this->forge->addColumn('tbl_anggota', $fields);
        
        // Update existing records to set tanggal_masuk_anggota = tanggal_pendaftaran for existing members
        $this->db->query("UPDATE tbl_anggota SET tanggal_masuk_anggota = tanggal_pendaftaran WHERE tanggal_masuk_anggota IS NULL");
    }

    public function down()
    {
        // Remove tanggal_masuk_anggota field
        $this->forge->dropColumn('tbl_anggota', 'tanggal_masuk_anggota');
    }
}