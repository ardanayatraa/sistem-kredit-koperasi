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
        'tanggal_masuk_anggota',
        'status_keanggotaan',
        'dokumen_ktp',
        'dokumen_kk',
        'dokumen_slip_gaji',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $beforeInsert = ['generateMemberNumber'];

    /**
     * Generate nomor anggota otomatis saat registrasi
     *
     * Method ini dipanggil otomatis sebelum data anggota disimpan (beforeInsert).
     * Membuat nomor anggota dengan format KOPL-0001, KOPL-0002, dst.
     *
     * @param array $data Data anggota yang akan disimpan
     * @return array Data dengan nomor anggota yang sudah di-generate
     */
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

    /**
     * Mengecek kelayakan anggota untuk mengajukan kredit (aturan 6 bulan keanggotaan)
     *
     * Method ini menerapkan business rule bahwa anggota harus sudah bergabung
     * minimal 6 bulan sebelum dapat mengajukan kredit. Perhitungan menggunakan
     * tanggal_masuk_anggota sebagai acuan.
     *
     * @param int $id_anggota ID anggota yang akan dicek kelayakannya
     * @return array Data kelayakan: eligible, months_completed, months_remaining, message
     */
    public function checkCreditEligibility($id_anggota)
    {
        $anggota = $this->find($id_anggota);
        
        if (!$anggota) {
            return [
                'eligible' => false,
                'months_completed' => 0,
                'months_remaining' => 6,
                'message' => 'Anggota tidak ditemukan'
            ];
        }

        // Accurate month calculation using DateTime
        $tanggalMasukObj = new \DateTime($anggota['tanggal_masuk_anggota']);
        $sekarangObj = new \DateTime();
        $interval = $tanggalMasukObj->diff($sekarangObj);
        $bulanAktual = ($interval->y * 12) + $interval->m;
        
        $eligible = $bulanAktual >= 6;
        $sisaBulan = $eligible ? 0 : (6 - $bulanAktual);
        
        return [
            'eligible' => $eligible,
            'months_completed' => $bulanAktual,
            'months_remaining' => $sisaBulan,
            'tanggal_masuk' => $anggota['tanggal_masuk_anggota'],
            'message' => $eligible ?
                'Memenuhi syarat untuk mengajukan kredit' :
                "Belum memenuhi syarat. Masih perlu menunggu {$sisaBulan} bulan lagi"
        ];
    }

    /**
     * Mengambil daftar anggota yang layak mengajukan kredit (sudah 6 bulan atau lebih)
     *
     * Method ini mengembalikan semua anggota dengan status aktif yang sudah
     * menjadi anggota selama 6 bulan atau lebih. Digunakan untuk validasi
     * dan dropdown dalam form pengajuan kredit.
     *
     * @return array Daftar anggota yang memenuhi syarat untuk kredit
     */
    public function getEligibleMembersForCredit()
    {
        $sixMonthsAgo = date('Y-m-d', strtotime('-6 months'));
        
        return $this->where('tanggal_masuk_anggota <=', $sixMonthsAgo)
                    ->where('status_keanggotaan', 'Aktif')
                    ->findAll();
    }

    /**
     * Mengambil daftar anggota yang belum layak mengajukan kredit (kurang dari 6 bulan)
     *
     * Method ini mengembalikan anggota aktif yang belum mencapai 6 bulan keanggotaan.
     * Berguna untuk laporan atau notifikasi kapan anggota bisa mengajukan kredit.
     *
     * @return array Daftar anggota yang belum memenuhi syarat untuk kredit
     */
    public function getNotEligibleMembersForCredit()
    {
        $sixMonthsAgo = date('Y-m-d', strtotime('-6 months'));
        
        return $this->where('tanggal_masuk_anggota >', $sixMonthsAgo)
                    ->where('status_keanggotaan', 'Aktif')
                    ->findAll();
    }
}