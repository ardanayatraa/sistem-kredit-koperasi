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
                'tipe_bunga' => 'Flat',
                'keterangan' => 'Bunga untuk kredit konsumtif dengan tenor maksimal 3 tahun',
                'status_aktif' => 'Aktif'
            ],
            [
                'nama_bunga' => 'Bunga Kredit Produktif',
                'persentase_bunga' => 10.00,
                'tipe_bunga' => 'Efektif',
                'keterangan' => 'Bunga untuk kredit produktif/usaha dengan tenor maksimal 5 tahun',
                'status_aktif' => 'Tidak Aktif'
            ],
            [
                'nama_bunga' => 'Bunga Kredit Mikro',
                'persentase_bunga' => 15.00,
                'tipe_bunga' => 'Flat',
                'keterangan' => 'Bunga untuk kredit mikro dengan tenor maksimal 1 tahun',
                'status_aktif' => 'Tidak Aktif'
            ],
            [
                'nama_bunga' => 'Bunga Kredit Pendidikan',
                'persentase_bunga' => 8.00,
                'tipe_bunga' => 'Menurun',
                'keterangan' => 'Bunga khusus untuk kredit pendidikan dengan tenor maksimal 4 tahun',
                'status_aktif' => 'Tidak Aktif'
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