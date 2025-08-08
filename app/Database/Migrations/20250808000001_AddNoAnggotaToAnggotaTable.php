<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNoAnggotaToAnggotaTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_anggota', [
            'no_anggota' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
                'after' => 'id_anggota'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_anggota', 'no_anggota');
    }
}