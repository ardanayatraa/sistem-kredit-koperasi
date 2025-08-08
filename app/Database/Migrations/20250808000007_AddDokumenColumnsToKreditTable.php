<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDokumenColumnsToKreditTable extends Migration
{
    public function up()
    {
        $fields = [
            'dokumen_agunan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Path file dokumen agunan'
            ],
        ];

        $this->forge->addColumn('tbl_kredit', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_kredit', ['dokumen_agunan']);
    }
}