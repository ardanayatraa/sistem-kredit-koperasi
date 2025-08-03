<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\PencairanModel;

class PencairanSeeder extends Seeder
{
    public function run()
    {
        $pencairanModel = new PencairanModel();

        $pencairanData = [
            [
                'id_kredit' => 1,
                'tanggal_pencairan' => '2024-01-25',
                'jumlah_dicairkan' => 50000000,
                'metode_pencairan' => 'Transfer Bank',
                'id_bunga' => 2,
                'bukti_transfer' => 'transfer_bukti_1.pdf',
                'status_aktif' => 'Aktif'
            ],
            [
                'id_kredit' => 2,
                'tanggal_pencairan' => '2024-02-20',
                'jumlah_dicairkan' => 25000000,
                'metode_pencairan' => 'Transfer Bank',
                'id_bunga' => 1,
                'bukti_transfer' => 'transfer_bukti_2.pdf',
                'status_aktif' => 'Aktif'
            ],
            [
                'id_kredit' => 3,
                'tanggal_pencairan' => '2024-03-15',
                'jumlah_dicairkan' => 100000000,
                'metode_pencairan' => 'Transfer Bank',
                'id_bunga' => 2,
                'bukti_transfer' => 'transfer_bukti_3.pdf',
                'status_aktif' => 'Aktif'
            ],
            [
                'id_kredit' => 5,
                'tanggal_pencairan' => '2024-05-20',
                'jumlah_dicairkan' => 75000000,
                'metode_pencairan' => 'Cek',
                'id_bunga' => 4,
                'bukti_transfer' => 'cek_bukti_1.pdf',
                'status_aktif' => 'Aktif'
            ]
        ];

        foreach ($pencairanData as $data) {
            // Check if pencairan already exists
            $existingPencairan = $pencairanModel->where([
                'id_kredit' => $data['id_kredit'],
                'tanggal_pencairan' => $data['tanggal_pencairan']
            ])->first();
            
            if (!$existingPencairan) {
                $pencairanModel->save($data);
                echo "Pencairan untuk kredit ID {$data['id_kredit']} berhasil dibuat.\n";
            } else {
                echo "Pencairan untuk kredit ID {$data['id_kredit']} dengan tanggal {$data['tanggal_pencairan']} sudah ada.\n";
            }
        }
    }
}