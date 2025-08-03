<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusAktifToKreditTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_kredit', [
            'status_aktif' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'Aktif',
                'after' => 'status_kredit'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_kredit', 'status_aktif');
    }
}