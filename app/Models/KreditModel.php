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
        'status_kredit',
        'status_aktif',
        'status_pencairan',
        'catatan_pencairan_bendahara',
        'tanggal_persetujuan_ketua',
        'dokumen_agunan',
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
     * Get filtered kredits based on user scope
     */
    public function getFilteredKredits($additionalWhere = [])
    {
        $builder = $this->builder();
        
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
     * Get filtered kredits with joins
     */
    public function getFilteredKreditsWithData($additionalWhere = [], $select = null)
    {
        $builder = $this->builder();
        
        // Default select if not provided - menggunakan alias nama_anggota untuk konsistensi
        if (!$select) {
            $select = 'tbl_kredit.*, tbl_users.nama_lengkap as nama_anggota, tbl_anggota.no_anggota, tbl_anggota.alamat';
        }
        
        $builder->select($select)
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
     * Find with access control
     */
    public function findWithAccess($id)
    {
        $data = $this->find($id);
        
        if ($data && !canAccessData($data)) {
            return null; // Return null if user doesn't have access
        }
        
        return $data;
    }

    /**
     * Get paginated filtered results
     */
    public function getPaginatedFiltered($perPage = 10, $additionalWhere = [])
    {
        $builder = $this->builder();
        
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
        
        return $this->paginate($perPage);
    }

    public function getAngsuranByKredit($id_kredit)
    {
        return $this->db->table('tbl_angsuran')
            ->where('id_kredit', $id_kredit)
            ->get()
            ->getResult();
    }
}