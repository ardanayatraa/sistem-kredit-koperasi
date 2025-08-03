<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_users', [
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'active',
                'after' => 'id_anggota_ref'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_users', 'status');
    }
}