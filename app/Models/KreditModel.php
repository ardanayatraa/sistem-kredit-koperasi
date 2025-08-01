<?php

namespace App\Models;

use CodeIgniter\Model;

class KreditModel extends Model
{
    protected $table = 'tbl_kredit';
    protected $primaryKey = 'id_kredit';
    protected $allowedFields = [
        'id_anggota',
        'tanggal_pengajuan',
        'jumlah_pengajuan',
        'jangka_waktu',
        'tujuan_kredit',
        'jenis_agunan',
        'nilai_taksiran_agunan',
        'catatan_bendahara',
        'catatan_appraiser',
        'catatan_ketua',
        'status_kredit'
    ];

    protected $useTimestamps = false;

    public function getAngsuranByKredit($id_kredit)
    {
        return $this->db->table('tbl_angsuran')
            ->where('id_kredit', $id_kredit)
            ->get()
            ->getResult();
    }
}