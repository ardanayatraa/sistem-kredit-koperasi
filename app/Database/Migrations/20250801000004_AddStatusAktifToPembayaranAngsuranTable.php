<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusAktifToPembayaranAngsuranTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_pembayaran_angsuran', [
            'status_aktif' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'Aktif',
                'after' => 'id_bendahara_verifikator'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_pembayaran_angsuran', 'status_aktif');
    }
}