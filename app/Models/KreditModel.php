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
        'status_verifikasi',
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
     * Mengambil data kredit berdasarkan akses pengguna
     *
     * Method ini menerapkan sistem filtering berdasarkan role pengguna.
     * Anggota hanya dapat melihat kredit miliknya, sementara staff dapat melihat semua.
     *
     * @param array $additionalWhere Kondisi WHERE tambahan untuk filter data
     * @return array Daftar data kredit yang sudah difilter
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
     * Mengambil data kredit dengan informasi anggota (menggunakan JOIN)
     *
     * Method ini mengambil data kredit lengkap dengan informasi anggota dan user
     * melalui JOIN table. Data tetap difilter berdasarkan hak akses pengguna.
     *
     * @param array $additionalWhere Kondisi WHERE tambahan
     * @param string $select Custom SELECT clause (opsional)
     * @return array Data kredit dengan informasi anggota
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
     * Mencari data kredit berdasarkan ID dengan kontrol akses
     *
     * Method ini memastikan pengguna hanya dapat mengakses data kredit
     * sesuai dengan hak aksesnya (anggota hanya bisa akses kredit sendiri)
     *
     * @param int $id ID kredit yang dicari
     * @return array|null Data kredit jika ada akses, null jika tidak ada akses
     */
    public function findWithAccess($id)
    {
        try {
            log_message('debug', 'KreditModel::findWithAccess called with ID: ' . $id);

            // First check if the record exists at all
            $data = $this->find($id);
            log_message('debug', 'KreditModel::findWithAccess - Raw find result: ' . ($data ? 'FOUND' : 'NOT FOUND'));

            if (!$data) {
                log_message('debug', 'KreditModel::findWithAccess - Kredit ID ' . $id . ' does not exist in database');
                return null;
            }

            // Check access permissions
            $hasAccess = canAccessData($data);
            log_message('debug', 'KreditModel::findWithAccess - Access check result: ' . ($hasAccess ? 'GRANTED' : 'DENIED'));

            if (!$hasAccess) {
                log_message('debug', 'KreditModel::findWithAccess - Access denied for kredit ID ' . $id);
                return null;
            }

            log_message('debug', 'KreditModel::findWithAccess - Successfully returned kredit ID ' . $id);
            return $data;

        } catch (\Exception $e) {
            log_message('error', 'KreditModel::findWithAccess - Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengambil data kredit dengan pagination dan filter akses
     *
     * Method ini menggabungkan sistem filtering akses pengguna dengan
     * pagination untuk menampilkan data dalam bentuk halaman
     *
     * @param int $perPage Jumlah data per halaman (default: 10)
     * @param array $additionalWhere Kondisi WHERE tambahan
     * @return array Data kredit yang sudah dipaginasi
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

    /**
     * Mengambil semua data angsuran berdasarkan ID kredit
     *
     * Method ini mengambil daftar angsuran yang terkait dengan kredit tertentu
     * untuk keperluan menampilkan jadwal pembayaran atau riwayat angsuran
     *
     * @param int $id_kredit ID kredit yang akan dicari angsurannya
     * @return array Daftar angsuran untuk kredit tersebut
     */
    public function getAngsuranByKredit($id_kredit)
    {
        return $this->db->table('tbl_angsuran')
            ->where('id_kredit', $id_kredit)
            ->get()
            ->getResult();
    }
}