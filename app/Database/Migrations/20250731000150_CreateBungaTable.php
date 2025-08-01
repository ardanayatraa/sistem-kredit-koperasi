<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBungaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_bunga' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_bunga' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'persentase_bunga' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'tipe_bunga' => [
                'type' => 'ENUM',
                'constraint' => ['Flat', 'Menurun', 'Efektif'],
                'default' => 'Flat',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_bunga', true);
        $this->forge->createTable('tbl_bunga');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_bunga');
    }
}