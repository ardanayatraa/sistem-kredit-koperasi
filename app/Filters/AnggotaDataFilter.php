<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AnggotaDataFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userLevel = $session->get('level');
        $userId = $session->get('id_user');
        
        // Only check for users with level 'Anggota'
        if ($userLevel === 'Anggota' && $userId) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($userId);
            
            // If user doesn't have id_anggota_ref, redirect to complete profile
            if (!$user || !$user['id_anggota_ref']) {
                // Allow access to profile completion route and logout
                $currentUri = uri_string();
                $allowedRoutes = [
                    'profile/complete-anggota-data',
                    'profile/save-anggota-data',
                    'logout',
                    'profile'
                ];
                
                $isAllowed = false;
                foreach ($allowedRoutes as $route) {
                    if (strpos($currentUri, $route) !== false) {
                        $isAllowed = true;
                        break;
                    }
                }
                
                if (!$isAllowed) {
                    return redirect()->to('/profile/complete-anggota-data')
                        ->with('warning', 'Silakan lengkapi data anggota terlebih dahulu untuk melanjutkan.');
                }
            }
        }
        
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}