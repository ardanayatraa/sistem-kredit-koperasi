<?php

namespace App\Models;

use CodeIgniter\Model;

class BungaModel extends Model
{
    protected $table = 'tbl_bunga';
    protected $primaryKey = 'id_bunga';
    protected $allowedFields = [
        'nama_bunga',
        'persentase_bunga',
        'tipe_bunga',
        'keterangan',
        'status_aktif',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    public function getByTipe($tipe)
    {
        return $this->where('tipe_bunga', $tipe)->findAll();
    }
}