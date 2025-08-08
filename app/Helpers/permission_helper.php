<?php

use App\Config\Roles;
use Config\Services;

function check_permission(string $permission)
{
    $session = Services::session();
    $role = $session->get('level');
    
    if (!Roles::can($role, $permission)) {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke fitur ini');
    }
}

/**
 * ALUR KOPERASI MITRA SEJAHTRA: Function untuk cek permission berdasarkan role
 * @param string $role Role yang dicek (bendahara, appraiser, ketua, anggota)
 * @return bool
 */
function hasPermission(string $role): bool
{
    $session = Services::session();
    $currentRole = strtolower($session->get('level'));
    $requiredRole = strtolower($role);
    
    // Mapping role sesuai alur koperasi
    $roleMapping = [
        'bendahara' => 'bendahara',
        'appraiser' => 'appraiser',
        'ketua' => 'ketua',
        'anggota' => 'anggota'
    ];
    
    return $currentRole === $roleMapping[$requiredRole] ?? false;
}