<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        // Data untuk semua role
        $users = [
            [
                'nama_lengkap' => 'Ketua Koperasi Admin',
                'username' => 'ketua_koperasi',
                'email' => 'ketua@koperasi.com',
                'password' => 'ketua123',
                'level' => 'Ketua Koperasi',
                'no_hp' => '081234567890',
                'status' => 'active'
            ],
            [
                'nama_lengkap' => 'Bendahara Koperasi',
                'username' => 'bendahara',
                'email' => 'bendahara@koperasi.com',
                'password' => 'bendahara123',
                'level' => 'Bendahara',
                'no_hp' => '081234567891',
                'status' => 'active'
            ],
            [
                'nama_lengkap' => 'Appraiser Kredit',
                'username' => 'appraiser',
                'email' => 'appraiser@koperasi.com',
                'password' => 'appraiser123',
                'level' => 'Appraiser',
                'no_hp' => '081234567892',
                'status' => 'active'
            ],
            [
                'nama_lengkap' => 'Anggota Demo',
                'username' => 'anggota_demo',
                'email' => 'anggota@koperasi.com',
                'password' => 'anggota123',
                'level' => 'Anggota',
                'no_hp' => '081234567893',
                'id_anggota_ref' => null, // Will be updated after AnggotaSeeder runs
                'status' => 'active'
            ]
        ];

        foreach ($users as $userData) {
            // Check if user already exists
            $existingUser = $userModel->where('username', $userData['username'])->first();
            if (!$existingUser) {
                $userModel->save($userData);
                echo "User {$userData['username']} ({$userData['level']}) berhasil dibuat.\n";
            } else {
                echo "User {$userData['username']} sudah ada.\n";
            }
        }
    }
}