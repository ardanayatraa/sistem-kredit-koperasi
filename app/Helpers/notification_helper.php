<?php

use App\Models\KreditModel;

/**
 * ALUR KOPERASI MITRA SEJAHTRA: Notification Helper
 * Menghitung notifikasi untuk setiap role sesuai workflow
 */

if (!function_exists('getNotificationCount')) {
    /**
     * Mendapatkan jumlah notifikasi untuk role tertentu
     * @param string $role Role yang dicek
     * @return int Jumlah notifikasi
     */
    function getNotificationCount(string $role): int
    {
        $kreditModel = new KreditModel();
        
        switch (strtolower($role)) {
            case 'bendahara':
                // Hitung pengajuan dengan status "Diajukan" DAN "Hasil Penilaian Appraiser"
                $count1 = $kreditModel->where('status_kredit', 'Diajukan')->countAllResults();
                $count2 = $kreditModel->where('status_kredit', 'Hasil Penilaian Appraiser')->countAllResults();
                return $count1 + $count2;
                
            case 'appraiser':
                // Hitung pengajuan dengan status "Verifikasi Bendahara"
                return $kreditModel->where('status_kredit', 'Verifikasi Bendahara')->countAllResults();
                
            case 'ketua':
                // Hitung pengajuan dengan status "Siap Persetujuan"
                return $kreditModel->where('status_kredit', 'Siap Persetujuan')->countAllResults();
                
            default:
                return 0;
        }
    }
}

if (!function_exists('getNotificationMessage')) {
    /**
     * Mendapatkan pesan notifikasi untuk role tertentu
     * @param string $role Role yang dicek
     * @return string Pesan notifikasi
     */
    function getNotificationMessage(string $role): string
    {
        $count = getNotificationCount($role);
        
        if ($count === 0) {
            return 'Tidak ada pengajuan kredit yang menunggu';
        }
        
        switch (strtolower($role)) {
            case 'bendahara':
                return $count . ' tugas menunggu (verifikasi awal + teruskan hasil appraiser)';
                
            case 'appraiser':
                return $count . ' pengajuan kredit menunggu penilaian agunan';
                
            case 'ketua':
                return $count . ' pengajuan kredit menunggu persetujuan final';
                
            default:
                return 'Tidak ada notifikasi';
        }
    }
}

if (!function_exists('getNotificationBadge')) {
    /**
     * Mendapatkan class badge untuk notifikasi
     * @param string $role Role yang dicek
     * @return string CSS class untuk badge
     */
    function getNotificationBadge(string $role): string
    {
        $count = getNotificationCount($role);
        
        if ($count === 0) {
            return 'badge-secondary';
        } elseif ($count <= 5) {
            return 'badge-warning';
        } else {
            return 'badge-danger';
        }
    }
}

if (!function_exists('getWorkflowProgress')) {
    /**
     * Mendapatkan progress workflow untuk anggota
     * @param int $idAnggota ID anggota
     * @return array Data progress workflow
     */
    function getWorkflowProgress(int $idAnggota): array
    {
        $kreditModel = new KreditModel();
        $pengajuan = $kreditModel->where('id_anggota', $idAnggota)->orderBy('created_at', 'DESC')->findAll();
        
        $progress = [
            'total_pengajuan' => count($pengajuan),
            'status_counts' => [
                'Diajukan' => 0,
                'Verifikasi Bendahara' => 0,
                'Penilaian Appraiser' => 0,
                'Disetujui' => 0,
                'Ditolak' => 0
            ],
            'latest_status' => '',
            'latest_date' => ''
        ];
        
        foreach ($pengajuan as $item) {
            $status = $item['status_kredit'];
            
            // Kategorisasi status ditolak
            if (in_array($status, ['Ditolak Bendahara', 'Ditolak Appraiser', 'Ditolak Final'])) {
                $progress['status_counts']['Ditolak']++;
            } elseif (isset($progress['status_counts'][$status])) {
                $progress['status_counts'][$status]++;
            }
        }
        
        if (!empty($pengajuan)) {
            $progress['latest_status'] = $pengajuan[0]['status_kredit'];
            $progress['latest_date'] = $pengajuan[0]['created_at'];
        }
        
        return $progress;
    }
}