<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusAktifToBungaTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_bunga', [
            'status_aktif' => [
                'type' => 'ENUM',
                'constraint' => ['Aktif', 'Tidak Aktif'],
                'default' => 'Aktif',
                'after' => 'keterangan'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_bunga', 'status_aktif');
    }
}