<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        echo "=== Menjalankan Database Seeder Sistem Kredit Koperasi ===\n\n";
        
        // 1. Users (Admin, Bendahara, Ketua Koperasi, Appraiser, Anggota)
        echo "1. Membuat data Users dan Roles...\n";
        $this->call('AdminSeeder');
        echo "\n";
        
        // 2. Anggota
        echo "2. Membuat data Anggota...\n";
        $this->call('AnggotaSeeder');
        echo "\n";
        
        // 3. Bunga
        echo "3. Membuat data Bunga...\n";
        $this->call('BungaSeeder');
        echo "\n";
        
        // 4. Kredit (depend on Anggota)
        echo "4. Membuat data Kredit...\n";
        $this->call('KreditSeeder');
        echo "\n";
        
        // 5. Pencairan (depend on Kredit & Bunga)
        echo "5. Membuat data Pencairan...\n";
        $this->call('PencairanSeeder');
        echo "\n";
        
        // 6. Angsuran (depend on Kredit)
        echo "6. Membuat data Angsuran...\n";
        $this->call('AngsuranSeeder');
        echo "\n";
        
        // 7. Pembayaran Angsuran (depend on Angsuran & Users)
        echo "7. Membuat data Pembayaran Angsuran...\n";
        $this->call('PembayaranAngsuranSeeder');
        echo "\n";
        
        // 8. Link User Anggota with Anggota Data
        echo "8. Menghubungkan User Anggota dengan Data Anggota...\n";
        $this->linkUserAnggotaWithAnggotaData();
        echo "\n";
        
        echo "=== Seeder Selesai! ===\n";
        echo "Data demo telah berhasil dibuat untuk semua role:\n";
        echo "- Ketua Koperasi: username 'ketua_koperasi', password 'ketua123'\n";
        echo "- Bendahara: username 'bendahara', password 'bendahara123'\n";
        echo "- Appraiser: username 'appraiser', password 'appraiser123'\n";
        echo "- Anggota: username 'anggota_demo', password 'anggota123'\n\n";
        echo "Silakan login dengan salah satu akun di atas untuk menguji sistem.\n";
        echo "Semua data sudah dilengkapi dengan status aktif/nonaktif yang dapat di-toggle sesuai role.\n";
        echo "User dengan level 'Anggota' sudah dikaitkan dengan data anggota yang sesuai.\n";
    }
    
    private function linkUserAnggotaWithAnggotaData()
    {
        $userModel = new \App\Models\UserModel();
        $anggotaModel = new \App\Models\AnggotaModel();
        
        // Get user with level 'Anggota'
        $anggotaUser = $userModel->where('level', 'Anggota')->first();
        if ($anggotaUser) {
            // Get first anggota data to link with user
            $anggotaData = $anggotaModel->where('id_anggota', 1)->first();
            if ($anggotaData) {
                // Update user to reference anggota
                $userModel->update($anggotaUser['id_user'], [
                    'id_anggota_ref' => $anggotaData['id_anggota']
                ]);
                echo "User anggota (ID: {$anggotaUser['id_user']}) berhasil dikaitkan dengan data anggota (ID: {$anggotaData['id_anggota']}).\n";
            }
        }
    }
}