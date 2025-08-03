<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusAktifToPencairanTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_pencairan', [
            'status_aktif' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'Aktif',
                'after' => 'bukti_transfer'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_pencairan', 'status_aktif');
    }
}