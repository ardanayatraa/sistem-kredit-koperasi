<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAngsuranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_angsuran' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_kredit' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'angsuran_ke' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'jumlah_angsuran' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'tgl_jatuh_tempo' => [
                'type' => 'DATE',
            ],
            'status_pembayaran' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'Belum Bayar',
            ],
        ]);
        $this->forge->addKey('id_angsuran', true);
        $this->forge->addForeignKey('id_kredit', 'tbl_kredit', 'id_kredit', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_angsuran');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_angsuran');
    }
}