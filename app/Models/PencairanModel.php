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
        'bukti_transfer',
        'status_aktif',
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
     * Get filtered pencairan based on user scope
     */
    public function getFilteredPencairan($additionalWhere = [])
    {
        $builder = $this->builder();
        
        // Join with kredit to apply user-based filtering
        $builder->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_pencairan.id_kredit');
        
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
        
        // Select only pencairan fields to avoid conflicts
        $builder->select('tbl_pencairan.*');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get filtered pencairan with complete data
     */
    public function getFilteredPencairanWithData($additionalWhere = [], $select = null)
    {
        $builder = $this->builder();
        
        // Default select if not provided
        if (!$select) {
            $select = 'tbl_pencairan.*, tbl_kredit.jumlah_pengajuan, tbl_users.nama_lengkap,
                      tbl_anggota.no_anggota, tbl_bunga.nama_bunga, tbl_bunga.persentase_bunga';
        }
        
        $builder->select($select)
                ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_pencairan.id_kredit')
                ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
                ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
                ->join('tbl_bunga', 'tbl_bunga.id_bunga = tbl_pencairan.id_bunga', 'left');
        
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
     * Find pencairan with access control
     */
    public function findWithAccess($id)
    {
        $builder = $this->builder();
        $builder->select('tbl_pencairan.*, tbl_kredit.id_anggota')
                ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_pencairan.id_kredit')
                ->where('tbl_pencairan.id_pencairan', $id);
        
        $data = $builder->get()->getRowArray();
        
        if ($data && !canAccessData($data)) {
            return null; // Return null if user doesn't have access
        }
        
        return $data;
    }

    /**
     * Get pencairan for specific anggota
     */
    public function getPencairanByAnggota($id_anggota, $additionalWhere = [])
    {
        $builder = $this->builder();
        $builder->select('tbl_pencairan.*, tbl_kredit.jumlah_pengajuan')
                ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_pencairan.id_kredit')
                ->where('tbl_kredit.id_anggota', $id_anggota);
        
        // Apply additional conditions
        if (!empty($additionalWhere)) {
            foreach ($additionalWhere as $field => $value) {
                $builder->where($field, $value);
            }
        }
        
        return $builder->get()->getResultArray();
    }

    public function getKredit($id_kredit)
    {
        return $this->db->table('tbl_kredit')
            ->where('id_kredit', $id_kredit)
            ->get()
            ->getRow();
    }
}