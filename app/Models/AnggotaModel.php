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
        'dokumen_slip_gaji'
    ];

    protected $useTimestamps = false;

    public function getUserByAnggotaId($id_anggota)
    {
        return $this->db->table('tbl_users')
            ->where('id_anggota_ref', $id_anggota)
            ->get()
            ->getRow();
    }
}