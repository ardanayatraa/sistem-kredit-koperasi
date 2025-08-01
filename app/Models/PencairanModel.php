<?php

namespace App\Models;

use CodeIgniter\Model;

class PencairanModel extends Model
{
    protected $table = 'tbl_pencairan';
    protected $primaryKey = 'id_pencairan';
    protected $allowedFields = [
        'id_kredit',
        'tanggal_pencairan',
        'jumlah_dicairkan',
        'metode_pencairan',
        'id_bunga',
        'bukti_transfer'
    ];

    protected $useTimestamps = false;

    public function getKredit($id_kredit)
    {
        return $this->db->table('tbl_kredit')
            ->where('id_kredit', $id_kredit)
            ->get()
            ->getRow();
    }
}