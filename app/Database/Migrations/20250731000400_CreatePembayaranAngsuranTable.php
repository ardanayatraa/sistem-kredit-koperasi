<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembayaranAngsuranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pembayaran' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_angsuran' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'tanggal_bayar' => [
                'type' => 'DATE',
            ],
            'jumlah_bayar' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'metode_pembayaran' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'bukti_pembayaran' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'denda' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'id_bendahara_verifikator' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
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
        $this->forge->addKey('id_pembayaran', true);
        $this->forge->addForeignKey('id_angsuran', 'tbl_angsuran', 'id_angsuran', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_bendahara_verifikator', 'tbl_users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_pembayaran_angsuran');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_pembayaran_angsuran');
    }
}