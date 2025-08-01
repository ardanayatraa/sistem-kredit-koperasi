<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table = 'tbl_anggota';
    protected $primaryKey = 'id_anggota';
    protected $allowedFields = [
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'pekerjaan',
        'tanggal_pendaftaran',
        'status_keanggotaan',
        'dokumen_ktp',
        'dokumen_kk',
        'dokumen_slip_gaji',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

}