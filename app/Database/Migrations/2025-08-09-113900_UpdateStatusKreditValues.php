<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateStatusKreditValues extends Migration
{
    public function up()
    {
        // Update semua status lama yang mungkin conflict dengan workflow baru
        $db = \Config\Database::connect();
        
        // Mapping status lama ke status baru
        $statusMapping = [
            'Disetujui' => 'Sudah Dicairkan',  // Status lama "Disetujui" diganti jadi "Sudah Dicairkan"
            'Ditolak' => 'Ditolak Final',     // Status lama "Ditolak" diganti jadi "Ditolak Final"
        ];
        
        foreach ($statusMapping as $oldStatus => $newStatus) {
            $db->table('tbl_kredit')
               ->where('status_kredit', $oldStatus)
               ->update(['status_kredit' => $newStatus]);
        }
        
        // Log perubahan untuk audit
        log_message('info', 'Status kredit updated: Disetujui -> Sudah Dicairkan, Ditolak -> Ditolak Final');
    }

    public function down()
    {
        // Kembalikan status ke format lama jika perlu rollback
        $db = \Config\Database::connect();
        
        $db->table('tbl_kredit')
           ->where('status_kredit', 'Sudah Dicairkan')
           ->update(['status_kredit' => 'Disetujui']);
           
        $db->table('tbl_kredit')
           ->where('status_kredit', 'Ditolak Final')
           ->update(['status_kredit' => 'Ditolak']);
    }
}