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

        // Skip permission check for dashboard routes
        $uri = service('uri');
        $currentPath = $uri->getPath();
        
        // Check if current path is a dashboard path
        if (!preg_match('/^\/dashboard-/', $currentPath)) {
            // Check if required permission is provided
            if (empty($arguments)) {
                return;
            }

            $permission = $arguments[0];

            // Check if user has permission
            if (!Roles::can($role, $permission)) {
                $dashboardUrl = $this->getDashboardUrl($role);
                return redirect()->to($dashboardUrl)->with('error', 'Anda tidak memiliki akses ke fitur ini');
            }
        }
    }

    /**
     * Get dashboard URL based on user role
     */
    private function getDashboardUrl($level)
    {
        switch ($level) {
            case 'Bendahara':
                return '/dashboard-bendahara';
            case 'Ketua':
                return '/dashboard-ketua';
            case 'Appraiser':
                return '/dashboard-appraiser';
            case 'Anggota':
                return '/dashboard-anggota';
            default:
                return '/beranda'; // Fallback ke beranda jika level tidak dikenal
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed
    }
}