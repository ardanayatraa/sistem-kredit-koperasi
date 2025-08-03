<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\BungaModel;

class BungaSeeder extends Seeder
{
    public function run()
    {
        $bungaModel = new BungaModel();

        $bungaData = [
            [
                'nama_bunga' => 'Bunga Kredit Konsumtif',
                'persentase_bunga' => 12.00,
                'jenis_bunga' => 'Tahunan',
                'deskripsi' => 'Bunga untuk kredit konsumtif dengan tenor maksimal 3 tahun'
            ],
            [
                'nama_bunga' => 'Bunga Kredit Produktif',
                'persentase_bunga' => 10.00,
                'jenis_bunga' => 'Tahunan',
                'deskripsi' => 'Bunga untuk kredit produktif/usaha dengan tenor maksimal 5 tahun'
            ],
            [
                'nama_bunga' => 'Bunga Kredit Mikro',
                'persentase_bunga' => 15.00,
                'jenis_bunga' => 'Tahunan',
                'deskripsi' => 'Bunga untuk kredit mikro dengan tenor maksimal 1 tahun'
            ],
            [
                'nama_bunga' => 'Bunga Kredit Pendidikan',
                'persentase_bunga' => 8.00,
                'jenis_bunga' => 'Tahunan',
                'deskripsi' => 'Bunga khusus untuk kredit pendidikan dengan tenor maksimal 4 tahun'
            ]
        ];

        foreach ($bungaData as $data) {
            // Check if bunga already exists
            $existingBunga = $bungaModel->where('nama_bunga', $data['nama_bunga'])->first();
            if (!$existingBunga) {
                $bungaModel->save($data);
                echo "Bunga {$data['nama_bunga']} berhasil dibuat.\n";
            } else {
                echo "Bunga {$data['nama_bunga']} sudah ada.\n";
            }
        }
    }
}