<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table = 'tbl_anggota';
    protected $primaryKey = 'id_anggota';
    protected $allowedFields = [
        'no_anggota',
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
    protected $beforeInsert = ['generateMemberNumber'];

    protected function generateMemberNumber(array $data)
    {
        // If no_anggota is not provided, generate it automatically
        if (empty($data['data']['no_anggota'])) {
            // Get the last member ID to generate the next member number
            $lastMember = $this->orderBy('id_anggota', 'DESC')->first();
            $nextId = $lastMember ? ($lastMember['id_anggota'] + 1) : 1;
            $data['data']['no_anggota'] = 'KOPL-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }
        return $data;
    }
}