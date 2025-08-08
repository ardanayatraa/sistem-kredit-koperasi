<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusVerifikasiToPembayaranAngsuranTable extends Migration
{
    public function up()
    {
        $fields = [
            'status_verifikasi' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'pending',
                'null'       => false,
                'after'      => 'id_bendahara_verifikator'
            ]
        ];

        $this->forge->addColumn('tbl_pembayaran_angsuran', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_pembayaran_angsuran', 'status_verifikasi');
    }
}