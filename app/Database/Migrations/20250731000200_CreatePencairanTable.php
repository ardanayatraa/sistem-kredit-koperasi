<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePencairanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pencairan' => [
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
            'tanggal_pencairan' => [
                'type' => 'DATE',
            ],
            'jumlah_dicairkan' => [
                'type' => 'INT',
                'constraint' => 20,
            ],
            'metode_pencairan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'id_bunga' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'bukti_transfer' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_pencairan', true);
        $this->forge->addForeignKey('id_kredit', 'tbl_kredit', 'id_kredit', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_bunga', 'tbl_bunga', 'id_bunga', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_pencairan');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_pencairan');
    }
}