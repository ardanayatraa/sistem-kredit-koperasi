<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusVerifikasiToKreditTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tbl_kredit', [
            'status_verifikasi' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'verified', 'rejected'],
                'null'       => true,
                'default'    => null,
                'after'      => 'status_kredit',
                'comment'    => 'Status verifikasi agunan oleh Appraiser'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_kredit', 'status_verifikasi');
    }
}
