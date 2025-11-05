<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVerificationFieldsToKredit extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_kredit', [
            'tanggal_verifikasi' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Tanggal verifikasi kredit'
            ],
            'verifikator_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'ID user yang melakukan verifikasi'
            ]
        ]);

        // Update existing records to set default status_verifikasi
        $this->db->query("UPDATE tbl_kredit SET status_verifikasi = 'unverified' WHERE status_verifikasi IS NULL OR status_verifikasi = ''");
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_kredit', ['tanggal_verifikasi', 'verifikator_id']);
    }
}
