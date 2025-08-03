<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranAngsuranModel extends Model
{
    protected $table = 'tbl_pembayaran_angsuran';
    protected $primaryKey = 'id_pembayaran';
    protected $allowedFields = [
        'id_angsuran',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        'denda',
        'id_bendahara_verifikator',
        'status_aktif',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    public function getAngsuran($id_angsuran)
    {
        return $this->db->table('tbl_angsuran')
            ->where('id_angsuran', $id_angsuran)
            ->get()
            ->getRow();
    }
}