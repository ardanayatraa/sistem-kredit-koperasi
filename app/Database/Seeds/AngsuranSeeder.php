<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AngsuranModel;

class AngsuranSeeder extends Seeder
{
    public function run()
    {
        $angsuranModel = new AngsuranModel();

        $angsuranData = [
            [
                'id_kredit' => 1,
                'angsuran_ke' => 1,
                'tanggal_jatuh_tempo' => '2024-02-25',
                'jumlah_angsuran' => 2500000,
                'status_pembayaran' => 'Lunas'
            ],
            [
                'id_kredit' => 1,
                'angsuran_ke' => 2,
                'tanggal_jatuh_tempo' => '2024-03-25',
                'jumlah_angsuran' => 2500000,
                'status_pembayaran' => 'Lunas'
            ],
            [
                'id_kredit' => 1,
                'angsuran_ke' => 3,
                'tanggal_jatuh_tempo' => '2024-04-25',
                'jumlah_angsuran' => 2500000,
                'status_pembayaran' => 'Belum Lunas'
            ],
            [
                'id_kredit' => 2,
                'angsuran_ke' => 1,
                'tanggal_jatuh_tempo' => '2024-03-20',
                'jumlah_angsuran' => 2300000,
                'status_pembayaran' => 'Lunas'
            ],
            [
                'id_kredit' => 2,
                'angsuran_ke' => 2,
                'tanggal_jatuh_tempo' => '2024-04-20',
                'jumlah_angsuran' => 2300000,
                'status_pembayaran' => 'Belum Lunas'
            ],
            [
                'id_kredit' => 3,
                'angsuran_ke' => 1,
                'tanggal_jatuh_tempo' => '2024-04-15',
                'jumlah_angsuran' => 3200000,
                'status_pembayaran' => 'Lunas'
            ],
            [
                'id_kredit' => 3,
                'angsuran_ke' => 2,
                'tanggal_jatuh_tempo' => '2024-05-15',
                'jumlah_angsuran' => 3200000,
                'status_pembayaran' => 'Belum Lunas'
            ],
            [
                'id_kredit' => 5,
                'angsuran_ke' => 1,
                'tanggal_jatuh_tempo' => '2024-06-20',
                'jumlah_angsuran' => 2800000,
                'status_pembayaran' => 'Belum Lunas'
            ]
        ];

        foreach ($angsuranData as $data) {
            // Check if angsuran already exists - menggunakan kolom yang benar
            $existingAngsuran = $angsuranModel->where([
                'id_kredit_ref' => $data['id_kredit'],
                'angsuran_ke' => $data['angsuran_ke']
            ])->first();

            if (!$existingAngsuran) {
                // Map id_kredit ke id_kredit_ref sesuai dengan struktur tabel
                $insertData = $data;
                $insertData['id_kredit_ref'] = $data['id_kredit'];
                unset($insertData['id_kredit']);

                $angsuranModel->save($insertData);
                echo "Angsuran ke-{$data['angsuran_ke']} untuk kredit ID {$data['id_kredit']} berhasil dibuat.\n";
            } else {
                echo "Angsuran ke-{$data['angsuran_ke']} untuk kredit ID {$data['id_kredit']} sudah ada.\n";
            }
        }
    }
}