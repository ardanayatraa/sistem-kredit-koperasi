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
     * Check if member is eligible for credit (6 months membership rule)
     *
     * @param int $id_anggota
     * @return array ['eligible' => bool, 'months_completed' => int, 'months_remaining' => int]
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
     * Get eligible members for credit (6 months or more)
     *
     * @return array
     */
    public function getEligibleMembersForCredit()
    {
        $sixMonthsAgo = date('Y-m-d', strtotime('-6 months'));
        
        return $this->where('tanggal_masuk_anggota <=', $sixMonthsAgo)
                    ->where('status_keanggotaan', 'Aktif')
                    ->findAll();
    }

    /**
     * Get members who are not yet eligible for credit
     *
     * @return array
     */
    public function getNotEligibleMembersForCredit()
    {
        $sixMonthsAgo = date('Y-m-d', strtotime('-6 months'));
        
        return $this->where('tanggal_masuk_anggota >', $sixMonthsAgo)
                    ->where('status_keanggotaan', 'Aktif')
                    ->findAll();
    }
}