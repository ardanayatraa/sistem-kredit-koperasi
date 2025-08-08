<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWorkflowDatesToKreditTable extends Migration
{
    public function up()
    {
        $fields = [
            'tanggal_verifikasi_bendahara' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Tanggal verifikasi oleh bendahara'
            ],
            'tanggal_penilaian_appraiser' => [
                'type' => 'DATETIME', 
                'null' => true,
                'comment' => 'Tanggal penilaian agunan oleh appraiser'
            ],
            'tanggal_keputusan_ketua' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Tanggal keputusan final oleh ketua'
            ]
        ];
        
        $this->forge->addColumn('tbl_kredit', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tbl_kredit', [
            'tanggal_verifikasi_bendahara',
            'tanggal_penilaian_appraiser', 
            'tanggal_keputusan_ketua'
        ]);
    }
}