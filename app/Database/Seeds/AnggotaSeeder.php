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
                'nik' => '3201012345678901',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1985-05-15',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'pekerjaan' => 'Pegawai Swasta',
                'tanggal_pendaftaran' => '2024-01-15',
                'status_keanggotaan' => 'Aktif',
                'dokumen_ktp' => 'ktp_sample_1.jpg',
                'dokumen_kk' => 'kk_sample_1.jpg',
                'dokumen_slip_gaji' => 'slip_gaji_sample_1.jpg'
            ],
            [
                'nik' => '3201012345678902',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1990-08-20',
                'alamat' => 'Jl. Asia Afrika No. 456, Bandung',
                'pekerjaan' => 'Wiraswasta',
                'tanggal_pendaftaran' => '2024-02-10',
                'status_keanggotaan' => 'Aktif',
                'dokumen_ktp' => 'ktp_sample_2.jpg',
                'dokumen_kk' => 'kk_sample_2.jpg',
                'dokumen_slip_gaji' => 'slip_gaji_sample_2.jpg'
            ],
            [
                'nik' => '3201012345678903',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1987-12-03',
                'alamat' => 'Jl. Tunjungan No. 789, Surabaya',
                'pekerjaan' => 'PNS',
                'tanggal_pendaftaran' => '2024-03-05',
                'status_keanggotaan' => 'Aktif',
                'dokumen_ktp' => 'ktp_sample_3.jpg',
                'dokumen_kk' => 'kk_sample_3.jpg',
                'dokumen_slip_gaji' => 'slip_gaji_sample_3.jpg'
            ],
            [
                'nik' => '3201012345678904',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '1992-04-18',
                'alamat' => 'Jl. Imam Bonjol No. 321, Medan',
                'pekerjaan' => 'Guru',
                'tanggal_pendaftaran' => '2024-04-12',
                'status_keanggotaan' => 'Tidak Aktif',
                'dokumen_ktp' => 'ktp_sample_4.jpg',
                'dokumen_kk' => 'kk_sample_4.jpg',
                'dokumen_slip_gaji' => 'slip_gaji_sample_4.jpg'
            ],
            [
                'nik' => '3201012345678905',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '1988-11-25',
                'alamat' => 'Jl. Malioboro No. 654, Yogyakarta',
                'pekerjaan' => 'Dokter',
                'tanggal_pendaftaran' => '2024-05-08',
                'status_keanggotaan' => 'Aktif',
                'dokumen_ktp' => 'ktp_sample_5.jpg',
                'dokumen_kk' => 'kk_sample_5.jpg',
                'dokumen_slip_gaji' => 'slip_gaji_sample_5.jpg'
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