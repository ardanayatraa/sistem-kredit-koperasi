<?php

/**
 * Helper for filtering data based on logged-in user's role and permissions
 */

if (!function_exists('getUserDataScope')) {
    /**
     * Get data scope filtering conditions based on user level
     * 
     * @return array Contains user info and filtering conditions
     */
    function getUserDataScope()
    {
        $session = session();
        $userLevel = $session->get('level');
        $userId = $session->get('id_user');
        $idAnggotaRef = $session->get('id_anggota_ref');

        $scope = [
            'level' => $userLevel,
            'user_id' => $userId,
            'id_anggota_ref' => $idAnggotaRef,
            'can_see_all' => false,
            'filter_conditions' => []
        ];

        switch ($userLevel) {
            case 'Admin':
                // Admin dapat melihat semua data
                $scope['can_see_all'] = true;
                break;

            case 'Anggota':
                // Anggota hanya melihat data milik mereka sendiri
                if ($idAnggotaRef) {
                    $scope['filter_conditions']['id_anggota'] = $idAnggotaRef;
                }
                break;

            case 'Bendahara':
            case 'Ketua':
            case 'Appraiser':
                // Role administratif bisa akses semua data untuk sementara
                // Bisa dikustomisasi lebih lanjut jika diperlukan pembatasan spesifik
                $scope['can_see_all'] = true;
                break;

            default:
                // Default: tidak ada akses
                $scope['filter_conditions']['id_anggota'] = 0; // Tidak akan ada yang match
                break;
        }

        return $scope;
    }
}

if (!function_exists('applyDataScopeToQuery')) {
    /**
     * Apply data scope filtering to CodeIgniter query builder
     *
     * @param \CodeIgniter\Database\BaseBuilder $builder
     * @param string $tableAlias Optional table alias for joins
     * @return \CodeIgniter\Database\BaseBuilder
     */
    function applyDataScopeToQuery($builder, $tableAlias = '')
    {
        $scope = getUserDataScope();
        
        // Jika user dapat melihat semua data, tidak perlu filtering
        if ($scope['can_see_all']) {
            return $builder;
        }

        // Apply filtering conditions hanya untuk user yang dibatasi (biasanya Anggota)
        foreach ($scope['filter_conditions'] as $field => $value) {
            $fieldName = $tableAlias ? $tableAlias . '.' . $field : $field;
            $builder->where($fieldName, $value);
        }

        return $builder;
    }
}

if (!function_exists('applyRoleSpecificFilter')) {
    /**
     * Apply role-specific filtering
     *
     * @param \CodeIgniter\Database\BaseBuilder $builder
     * @param string $role
     * @param string $tableAlias
     * @return \CodeIgniter\Database\BaseBuilder
     */
    function applyRoleSpecificFilter($builder, $role, $tableAlias = '')
    {
        switch ($role) {
            case 'bendahara':
                // Bendahara hanya bisa lihat data yang memerlukan verifikasi atau proses pembayaran
                // Misalnya: kredit dengan status tertentu, atau data pembayaran
                // Untuk sekarang, batasi akses - bisa disesuaikan kebutuhan
                $statusField = $tableAlias ? $tableAlias . '.status_kredit' : 'status_kredit';
                $builder->whereIn($statusField, ['Diajukan', 'Hasil Penilaian Appraiser', 'Siap Persetujuan']);
                break;
                
            case 'ketua':
                // Ketua hanya bisa lihat data yang perlu persetujuan final
                $statusField = $tableAlias ? $tableAlias . '.status_kredit' : 'status_kredit';
                $builder->where($statusField, 'Siap Persetujuan');
                break;
                
            case 'appraiser':
                // Appraiser hanya bisa lihat data yang perlu penilaian
                $statusField = $tableAlias ? $tableAlias . '.status_kredit' : 'status_kredit';
                $builder->where($statusField, 'Verifikasi Bendahara');
                break;
                
            default:
                // Jika role tidak dikenal, blokir semua akses
                $builder->where('1', '0'); // Kondisi yang tidak akan pernah terpenuhi
                break;
        }
        
        return $builder;
    }
}

if (!function_exists('canAccessData')) {
    /**
     * Check if current user can access specific data record
     *
     * @param array $data The data record to check
     * @param string $ownerField Field name that contains owner ID (default: 'id_anggota')
     * @return bool
     */
    function canAccessData($data, $ownerField = 'id_anggota')
    {
        try {
            log_message('debug', 'canAccessData called for ownerField: ' . $ownerField);

            $scope = getUserDataScope();
            log_message('debug', 'canAccessData - User scope: ' . json_encode($scope));

            // Users with full access can see everything
            if ($scope['can_see_all']) {
                log_message('debug', 'canAccessData - User has full access, granting permission');
                return true;
            }

            // Check ownership for limited access users
            if (isset($scope['filter_conditions'][$ownerField])) {
                $userOwnerId = $scope['filter_conditions'][$ownerField];
                $dataOwnerId = $data[$ownerField] ?? null;

                log_message('debug', 'canAccessData - Checking ownership: user owns ' . $userOwnerId . ', data owned by ' . $dataOwnerId);

                $hasAccess = isset($data[$ownerField]) &&
                           $data[$ownerField] == $scope['filter_conditions'][$ownerField];

                log_message('debug', 'canAccessData - Access result: ' . ($hasAccess ? 'GRANTED' : 'DENIED'));
                return $hasAccess;
            }

            log_message('debug', 'canAccessData - No ownership filter found, denying access');
            return false;

        } catch (\Exception $e) {
            log_message('error', 'canAccessData - Error: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('getCurrentUserAnggotaId')) {
    /**
     * Get current logged-in user's anggota ID
     * 
     * @return int|null
     */
    function getCurrentUserAnggotaId()
    {
        return session()->get('id_anggota_ref');
    }
}

if (!function_exists('isCurrentUserLevel')) {
    /**
     * Check if current user has specific level
     * 
     * @param string|array $levels Level(s) to check
     * @return bool
     */
    function isCurrentUserLevel($levels)
    {
        $currentLevel = session()->get('level');
        
        if (is_array($levels)) {
            return in_array($currentLevel, $levels);
        }
        
        return $currentLevel === $levels;
    }
}