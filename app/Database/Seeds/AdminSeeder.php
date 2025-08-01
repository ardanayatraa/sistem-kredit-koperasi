<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        $data = [
            'nama_lengkap' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@koperasi.com',
            'password' => 'admin123',
            'level' => 'Ketua',
            'no_hp' => '081234567890'
        ];

        $userModel->save($data);
    }
}