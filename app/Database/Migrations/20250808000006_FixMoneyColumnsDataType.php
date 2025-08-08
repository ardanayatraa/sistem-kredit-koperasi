<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixMoneyColumnsDataType extends Migration
{
    public function up()
    {
        // Fix jumlah_pengajuan column to handle larger amounts (up to 18 quintillion)
        $this->forge->modifyColumn('tbl_kredit', [
            'jumlah_pengajuan' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ],
            'nilai_taksiran_agunan' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'null' => true,
            ]
        ]);

        // Fix angsuran table money columns
        $this->forge->modifyColumn('tbl_angsuran', [
            'jumlah_angsuran' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ]
        ]);

        // Fix pembayaran table money columns
        $this->forge->modifyColumn('tbl_pembayaran_angsuran', [
            'jumlah_bayar' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ]
        ]);

        // Fix pencairan table money columns
        $this->forge->modifyColumn('tbl_pencairan', [
            'jumlah_dicairkan' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
            ]
        ]);
    }

    public function down()
    {
        // Revert back to original types (not recommended for production)
        $this->forge->modifyColumn('tbl_kredit', [
            'jumlah_pengajuan' => [
                'type' => 'INT',
                'constraint' => 20,
            ],
            'nilai_taksiran_agunan' => [
                'type' => 'INT',
                'constraint' => 20,
            ]
        ]);

        $this->forge->modifyColumn('tbl_angsuran', [
            'jumlah_angsuran' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ]
        ]);

        $this->forge->modifyColumn('tbl_pembayaran_angsuran', [
            'jumlah_bayar' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ]
        ]);

        $this->forge->modifyColumn('tbl_pencairan', [
            'jumlah_dicairkan' => [
                'type' => 'INT',
                'constraint' => 20,
            ]
        ]);
    }
}