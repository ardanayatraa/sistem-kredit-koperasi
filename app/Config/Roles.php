<?php

namespace App\Config;

class Roles
{
    public const PERMISSIONS = [
        'Bendahara' => [
            'manage_anggota',
            'manage_kredit',
            'manage_pencairan',
            'manage_pembayaran_angsuran',
            'view_laporan_kredit'
        ],
        'Ketua Koperasi' => [
            'manage_users',
            'manage_anggota',
            'manage_bunga',
            'manage_pencairan',
            'view_laporan_kredit'
        ],
        'Anggota' => [
            'view_profile',
            'manage_kredit',
            'view_laporan_kredit'
        ],
        'Appraiser' => [
            'verify_agunan',
            'assess_agunan',
            'manage_kredit',
            'view_laporan_kredit'
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