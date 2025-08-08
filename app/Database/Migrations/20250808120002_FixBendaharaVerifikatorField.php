<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixBendaharaVerifikatorField extends Migration
{
    public function up()
    {
        // Modify id_bendahara_verifikator to be nullable
        $fields = [
            'id_bendahara_verifikator' => [
                'type'       => 'INT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,  // Make it nullable
                'comment'    => 'Diisi setelah pembayaran diverifikasi'
            ]
        ];

        $this->forge->modifyColumn('tbl_pembayaran_angsuran', $fields);
    }

    public function down()
    {
        // Revert back to not null
        $fields = [
            'id_bendahara_verifikator' => [
                'type'       => 'INT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => false
            ]
        ];

        $this->forge->modifyColumn('tbl_pembayaran_angsuran', $fields);
    }
}