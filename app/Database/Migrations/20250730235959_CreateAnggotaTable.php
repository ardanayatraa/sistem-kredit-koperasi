<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnggotaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_anggota' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nik' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'tempat_lahir' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
            ],
            'alamat' => [
                'type' => 'TEXT',
            ],
            'pekerjaan' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'tanggal_pendaftaran' => [
                'type' => 'TIMESTAMP',
            ],
            'status_keanggotaan' => [
                'type' => 'ENUM',
                'constraint' => ['Menunggu', 'Aktif', 'Ditolak'],
                'default' => 'Menunggu',
            ],
            'dokumen_ktp' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'dokumen_kk' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'dokumen_slip_gaji' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addKey('id_anggota', true);
        $this->forge->createTable('tbl_anggota');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_anggota');
    }
}