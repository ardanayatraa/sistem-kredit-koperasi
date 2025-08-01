<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use App\Config\Roles;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Services::session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Get current user role
        $role = $session->get('level');

        // Check if required permission is provided
        if (empty($arguments)) {
            return;
        }

        $permission = $arguments[0];

        // Check if user has permission
        if (!Roles::can($role, $permission)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke fitur ini');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}