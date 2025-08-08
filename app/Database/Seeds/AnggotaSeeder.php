<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AnggotaModel;

class AnggotaSeeder extends Seeder
{
    public function run()
    {
        $anggotaModel = new AnggotaModel();

        $anggotaData = [
            [
                'no_anggota' => 'KOPL-0001',
                'nik' => '3201012345678901',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1985-05-15',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'pekerjaan' => 'Pegawai Swasta',
                'tanggal_pendaftaran' => '2023-07-15', // 6+ bulan yang lalu untuk syarat minimal
                'status_keanggotaan' => 'Aktif'
                // Dokumen akan diupload langsung oleh anggota saat diperlukan
            ],
            [
                'no_anggota' => 'KOPL-0002',
                'nik' => '3201012345678902',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1990-08-20',
                'alamat' => 'Jl. Asia Afrika No. 456, Bandung',
                'pekerjaan' => 'Wiraswasta',
                'tanggal_pendaftaran' => '2023-08-10', // 6+ bulan yang lalu
                'status_keanggotaan' => 'Aktif'
            ],
            [
                'no_anggota' => 'KOPL-0003',
                'nik' => '3201012345678903',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1987-12-03',
                'alamat' => 'Jl. Tunjungan No. 789, Surabaya',
                'pekerjaan' => 'PNS',
                'tanggal_pendaftaran' => '2023-09-05', // 6+ bulan yang lalu
                'status_keanggotaan' => 'Aktif'
            ],
            [
                'no_anggota' => 'KOPL-0004',
                'nik' => '3201012345678904',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '1992-04-18',
                'alamat' => 'Jl. Imam Bonjol No. 321, Medan',
                'pekerjaan' => 'Guru',
                'tanggal_pendaftaran' => '2024-04-12', // Baru 4 bulan (tidak memenuhi syarat kredit)
                'status_keanggotaan' => 'Aktif'
            ],
            [
                'no_anggota' => 'KOPL-0005',
                'nik' => '3201012345678905',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '1988-11-25',
                'alamat' => 'Jl. Malioboro No. 654, Yogyakarta',
                'pekerjaan' => 'Dokter',
                'tanggal_pendaftaran' => '2023-11-08', // 6+ bulan yang lalu
                'status_keanggotaan' => 'Aktif'
            ]
        ];

        foreach ($anggotaData as $data) {
            // Check if anggota already exists by NIK
            $existingAnggota = $anggotaModel->where('nik', $data['nik'])->first();
            if (!$existingAnggota) {
                $anggotaModel->save($data);
                echo "Anggota dengan NIK {$data['nik']} berhasil dibuat.\n";
            } else {
                echo "Anggota dengan NIK {$data['nik']} sudah ada.\n";
            }
        }
    }
}