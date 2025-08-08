<?php

namespace App\Config;

class Roles
{
    public const PERMISSIONS = [
        'Bendahara' => [
            'dashboard_bendahara',
            'view_beranda',
            'manage_anggota', // Data Anggota
            'manage_kredit', // Pengajuan Kredit
            'view_riwayat_penilaian', // Riwayat Penilaian
            'manage_pencairan', // Pencairan Kredit
            'manage_pembayaran_angsuran', // Pembayaran Kredit / Angsuran
            'manage_agunan', // Data Agunan
            'view_laporan_kredit', // Laporan Kredit
            'change_password', // Ganti Password
            'view_profile'
        ],
        'Ketua' => [
            'dashboard_ketua', // Dashboard access
            'view_laporan_kredit_koperasi', // Laporan Kredit Koperasi
            'change_password' // Ganti Password
            // Logout doesn't require permission - handled by AuthController
        ],
        'Anggota' => [
            'dashboard_anggota',
            'manage_kredit', // Pengajuan Kredit (untuk anggota)
            'view_riwayat_kredit', // Riwayat Kredit
            'view_riwayat_pembayaran', // Riwayat Pembayaran
            'view_simulasi_bunga', // Simulasi Bunga
            'change_password', // Ganti Password
            'view_profile'
        ],
        'Appraiser' => [
            'dashboard_appraiser',
            'view_daftar_agunan', // Daftar Agunan
            'verify_agunan', // Verifikasi Agunan
            'assess_agunan', // Assessment agunan
            'view_riwayat_penilaian' // Riwayat Penilaian
        ]
    ];

    public static function can(string $role, string $permission): bool
    {
        if (!array_key_exists($role, self::PERMISSIONS)) {
            return false;
        }

        return in_array($permission, self::PERMISSIONS[$role]);
    }
}