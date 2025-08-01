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