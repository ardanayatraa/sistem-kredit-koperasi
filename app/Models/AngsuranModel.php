<?php

namespace App\Models;

use CodeIgniter\Model;

class AngsuranModel extends Model
{
    protected $table = 'tbl_angsuran';
    protected $primaryKey = 'id_angsuran';
    protected $allowedFields = [
        'id_kredit',
        'angsuran_ke',
        'jumlah_angsuran',
        'tgl_jatuh_tempo',
        'status_pembayaran',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    public function getPembayaranByAngsuran($id_angsuran)
    {
        return $this->db->table('tbl_pembayaran_angsuran')
            ->where('id_angsuran', $id_angsuran)
            ->get()
            ->getRow();
    }
}