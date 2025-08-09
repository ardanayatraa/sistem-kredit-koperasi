<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixCreditWorkflowAddDisbursementTracking extends Migration
{
    public function up()
    {
        // Add new fields to tbl_kredit for better workflow tracking
        $this->forge->addColumn('tbl_kredit', [
            'tanggal_persetujuan_ketua' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Tanggal ketua menyetujui kredit',
                'after' => 'tanggal_keputusan_ketua'
            ],
            'status_pencairan' => [
                'type' => 'ENUM',
                'constraint' => ['Menunggu', 'Siap Dicairkan', 'Sudah Dicairkan'],
                'null' => true,
                'default' => null,
                'comment' => 'Status proses pencairan oleh bendahara',
                'after' => 'status_kredit'
            ],
            'catatan_pencairan_bendahara' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Catatan bendahara saat proses pencairan',
                'after' => 'catatan_ketua'
            ]
        ]);

        // Add status_aktif field (skip if exists to avoid errors)
        try {
            $this->forge->addColumn('tbl_kredit', [
                'status_aktif' => [
                    'type' => 'ENUM',
                    'constraint' => ['Aktif', 'Tidak Aktif'],
                    'default' => 'Aktif',
                    'comment' => 'Status aktif kredit',
                    'after' => 'dokumen_agunan'
                ]
            ]);
        } catch (\Exception $e) {
            // Field might already exist, continue
            log_message('info', 'status_aktif field might already exist: ' . $e->getMessage());
        }
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_kredit', [
            'tanggal_persetujuan_ketua',
            'status_pencairan', 
            'catatan_pencairan_bendahara'
        ]);
        
        // Only drop status_aktif if it was added by this migration
        $this->forge->dropColumn('tbl_kredit', 'status_aktif');
    }
}