<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\PembayaranAngsuranModel;

class PembayaranAngsuranSeeder extends Seeder
{
    public function run()
    {
        $pembayaranAngsuranModel = new PembayaranAngsuranModel();

        $pembayaranData = [
            [
                'id_angsuran' => 1,
                'tanggal_bayar' => '2024-02-20',
                'jumlah_bayar' => 2500000.00,
                'metode_pembayaran' => 'Transfer Bank',
                'bukti_pembayaran' => 'bukti_pembayaran_1.jpg',
                'denda' => 0.00,
                'id_bendahara_verifikator' => 2,
                'status_aktif' => 'Aktif'
            ],
            [
                'id_angsuran' => 2,
                'tanggal_bayar' => '2024-03-22',
                'jumlah_bayar' => 2500000.00,
                'metode_pembayaran' => 'Cash',
                'bukti_pembayaran' => 'bukti_pembayaran_2.jpg',
                'denda' => 0.00,
                'id_bendahara_verifikator' => 2,
                'status_aktif' => 'Aktif'
            ],
            [
                'id_angsuran' => 4,
                'tanggal_bayar' => '2024-03-15',
                'jumlah_bayar' => 2300000.00,
                'metode_pembayaran' => 'Transfer Bank',
                'bukti_pembayaran' => 'bukti_pembayaran_3.jpg',
                'denda' => 0.00,
                'id_bendahara_verifikator' => 2,
                'status_aktif' => 'Aktif'
            ],
            [
                'id_angsuran' => 6,
                'tanggal_bayar' => '2024-04-10',
                'jumlah_bayar' => 3250000.00,
                'metode_pembayaran' => 'Transfer Bank',
                'bukti_pembayaran' => 'bukti_pembayaran_4.jpg',
                'denda' => 50000.00,
                'id_bendahara_verifikator' => 2,
                'status_aktif' => 'Aktif'
            ]
        ];

        foreach ($pembayaranData as $data) {
            // Check if pembayaran already exists
            $existingPembayaran = $pembayaranAngsuranModel->where([
                'id_angsuran' => $data['id_angsuran'],
                'tanggal_bayar' => $data['tanggal_bayar']
            ])->first();
            
            if (!$existingPembayaran) {
                $pembayaranAngsuranModel->save($data);
                echo "Pembayaran angsuran ID {$data['id_angsuran']} berhasil dibuat.\n";
            } else {
                echo "Pembayaran angsuran ID {$data['id_angsuran']} dengan tanggal {$data['tanggal_bayar']} sudah ada.\n";
            }
        }
    }
}