<?php

namespace App\Models;

use CodeIgniter\Model;

class AngsuranModel extends Model
{
    protected $table = 'tbl_angsuran';
    protected $primaryKey = 'id_angsuran';
    protected $allowedFields = [
        'id_kredit_ref',
        'angsuran_ke',
        'jumlah_angsuran',
        'tgl_jatuh_tempo',
        'status_pembayaran',
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
     * Get filtered angsuran based on user scope
     */
    public function getFilteredAngsuran($additionalWhere = [])
    {
        $builder = $this->builder();
        
        // Join with kredit to apply user-based filtering
        $builder->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref');
        
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
        
        // Select only angsuran fields to avoid conflicts
        $builder->select('tbl_angsuran.*');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get filtered angsuran with kredit and anggota data
     */
    public function getFilteredAngsuranWithData($additionalWhere = [], $select = null)
    {
        $builder = $this->builder();
        
        // Default select if not provided
        if (!$select) {
            $select = 'tbl_angsuran.*, tbl_kredit.jumlah_pengajuan, tbl_users.nama_lengkap, tbl_anggota.no_anggota';
        }
        
        $builder->select($select)
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
     * Find angsuran with access control
     */
    public function findWithAccess($id)
    {
        $builder = $this->builder();
        $builder->select('tbl_angsuran.*, tbl_kredit.id_anggota')
                ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref')
                ->where('tbl_angsuran.id_angsuran', $id);
        
        $data = $builder->get()->getRowArray();
        
        if ($data && !canAccessData($data)) {
            return null; // Return null if user doesn't have access
        }
        
        return $data;
    }

    /**
     * Get angsuran for specific anggota only
     */
    public function getAngsuranByAnggota($id_anggota, $additionalWhere = [])
    {
        $builder = $this->builder();
        $builder->select('tbl_angsuran.*')
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

    public function getPembayaranByAngsuran($id_angsuran)
    {
        return $this->db->table('tbl_pembayaran_angsuran')
            ->where('id_angsuran', $id_angsuran)
            ->get()
            ->getRow();
    }
}