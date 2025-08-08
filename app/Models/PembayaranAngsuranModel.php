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
        'keterangan',
        'status_verifikasi',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    public function __construct()
    {
        parent::__construct();
        helper('data_filter');
    }

    /**
     * Get filtered pembayaran angsuran based on user scope
     */
    public function getFilteredPembayaran($additionalWhere = [])
    {
        $builder = $this->builder();
        
        // Join with angsuran and kredit to apply user-based filtering
        $builder->join('tbl_angsuran', 'tbl_angsuran.id_angsuran = tbl_pembayaran_angsuran.id_angsuran')
                ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref');
        
        // Apply user-based filtering through kredit table
        $builder = applyDataScopeToQuery($builder, 'tbl_kredit');
        
        // Apply additional where conditions
        if (!empty($additionalWhere)) {
            foreach ($additionalWhere as $field => $value) {
                if (is_array($value)) {
                    $builder->whereIn($field, $value);
                } else {
                    $builder->where($field, $value);
                }
            }
        }
        
        // Select only pembayaran fields to avoid conflicts
        $builder->select('tbl_pembayaran_angsuran.*');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get filtered pembayaran with complete data
     */
    public function getFilteredPembayaranWithData($additionalWhere = [], $select = null)
    {
        $builder = $this->builder();
        
        // Default select if not provided
        if (!$select) {
            $select = 'tbl_pembayaran_angsuran.*, tbl_angsuran.angsuran_ke, tbl_angsuran.jumlah_angsuran,
                      tbl_kredit.id_kredit, tbl_users.nama_lengkap, tbl_anggota.nik, tbl_anggota.no_anggota';
        }
        
        $builder->select($select)
                ->join('tbl_angsuran', 'tbl_angsuran.id_angsuran = tbl_pembayaran_angsuran.id_angsuran')
                ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref')
                ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
                ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota');
        
        // Apply user-based filtering
        $builder = applyDataScopeToQuery($builder, 'tbl_kredit');
        
        // Apply additional where conditions
        if (!empty($additionalWhere)) {
            foreach ($additionalWhere as $field => $value) {
                if (is_array($value)) {
                    $builder->whereIn($field, $value);
                } else {
                    $builder->where($field, $value);
                }
            }
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Find pembayaran with access control
     */
    public function findWithAccess($id)
    {
        $builder = $this->builder();
        $builder->select('tbl_pembayaran_angsuran.*, tbl_kredit.id_anggota')
                ->join('tbl_angsuran', 'tbl_angsuran.id_angsuran = tbl_pembayaran_angsuran.id_angsuran')
                ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref')
                ->where('tbl_pembayaran_angsuran.id_pembayaran', $id);
        
        $data = $builder->get()->getRowArray();
        
        if ($data && !canAccessData($data)) {
            return null; // Return null if user doesn't have access
        }
        
        return $data;
    }

    /**
     * Get pembayaran for specific anggota
     */
    public function getPembayaranByAnggota($id_anggota, $additionalWhere = [])
    {
        $builder = $this->builder();
        $builder->select('tbl_pembayaran_angsuran.*, tbl_angsuran.angsuran_ke,
                         tbl_angsuran.jumlah_angsuran, tbl_kredit.id_kredit')
                ->join('tbl_angsuran', 'tbl_angsuran.id_angsuran = tbl_pembayaran_angsuran.id_angsuran')
                ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref')
                ->where('tbl_kredit.id_anggota', $id_anggota);
        
        // Apply additional conditions
        if (!empty($additionalWhere)) {
            foreach ($additionalWhere as $field => $value) {
                $builder->where($field, $value);
            }
        }
        
        return $builder->get()->getResultArray();
    }

    public function getAngsuran($id_angsuran)
    {
        return $this->db->table('tbl_angsuran')
            ->where('id_angsuran', $id_angsuran)
            ->get()
            ->getRow();
    }
}