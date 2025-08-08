<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class UpdateKetuaLevelSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();
        
        // Update existing ketua_koperasi user level from 'Ketua Koperasi' to 'Ketua'
        $result = $userModel->where('username', 'ketua_koperasi')
                           ->set('level', 'Ketua')
                           ->update();
                           
        if ($result) {
            echo "Successfully updated ketua_koperasi user level to 'Ketua'\n";
        } else {
            echo "Failed to update user or user not found\n";
        }
        
        // Verify the update
        $user = $userModel->where('username', 'ketua_koperasi')->first();
        if ($user) {
            echo "Current user level: " . $user['level'] . "\n";
        }
    }
}