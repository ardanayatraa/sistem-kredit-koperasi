<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKreditTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kredit' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_anggota' => [
                'type' => 'INT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'tanggal_pengajuan' => [
                'type' => 'DATE',
            ],
            'jumlah_pengajuan' => [
                'type' => 'INT',
                'constraint' => 20,
            ],
            'jangka_waktu' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'tujuan_kredit' => [
                'type' => 'TEXT',
            ],
            'jenis_agunan' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'nilai_taksiran_agunan' => [
                'type' => 'INT',
                'constraint' => 20,
            ],
            'catatan_bendahara' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'catatan_appraiser' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'catatan_ketua' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status_kredit' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'Diajukan',
            ],
        ]);
        $this->forge->addKey('id_kredit', true);
        $this->forge->addForeignKey('id_anggota', 'tbl_anggota', 'id_anggota', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_kredit');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_kredit');
    }
}