<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\KreditModel;

class KreditSeeder extends Seeder
{
    public function run()
    {
        $kreditModel = new KreditModel();

        $kreditData = [
            [
                'id_anggota' => 1,
                'tanggal_pengajuan' => '2024-01-20',
                'jumlah_pengajuan' => 50000000,
                'jangka_waktu' => 24,
                'tujuan_kredit' => 'Modal usaha warung kelontong',
                'jenis_agunan' => 'Sertifikat Rumah',
                'nilai_taksiran_agunan' => 75000000,
                'catatan_bendahara' => 'Dokumen lengkap, riwayat keuangan baik',
                'catatan_appraiser' => 'Agunan sesuai nilai, lokasi strategis',
                'catatan_ketua' => 'Disetujui berdasarkan pertimbangan tim',
                'status_kredit' => 'Disetujui',
                'status_aktif' => 'Aktif'
            ],
            [
                'id_anggota' => 2,
                'tanggal_pengajuan' => '2024-02-15',
                'jumlah_pengajuan' => 25000000,
                'jangka_waktu' => 12,
                'tujuan_kredit' => 'Renovasi rumah',
                'jenis_agunan' => 'BPKB Motor',
                'nilai_taksiran_agunan' => 30000000,
                'catatan_bendahara' => 'Penghasilan stabil, dokumen valid',
                'catatan_appraiser' => 'Kondisi kendaraan baik, tahun 2020',
                'catatan_ketua' => 'Disetujui dengan catatan pembayaran tepat waktu',
                'status_kredit' => 'Disetujui',
                'status_aktif' => 'Aktif'
            ],
            [
                'id_anggota' => 3,
                'tanggal_pengajuan' => '2024-03-10',
                'jumlah_pengajuan' => 100000000,
                'jangka_waktu' => 36,
                'tujuan_kredit' => 'Ekspansi usaha catering',
                'jenis_agunan' => 'Sertifikat Tanah',
                'nilai_taksiran_agunan' => 150000000,
                'catatan_bendahara' => 'Cashflow usaha positif, track record baik',
                'catatan_appraiser' => 'Lokasi tanah strategis, nilai sesuai pasar',
                'catatan_ketua' => 'Disetujui untuk mendukung UMKM lokal',
                'status_kredit' => 'Disetujui',
                'status_aktif' => 'Aktif'
            ],
            [
                'id_anggota' => 4,
                'tanggal_pengajuan' => '2024-04-05',
                'jumlah_pengajuan' => 15000000,
                'jangka_waktu' => 6,
                'tujuan_kredit' => 'Biaya pendidikan anak',
                'jenis_agunan' => 'Deposito',
                'nilai_taksiran_agunan' => 20000000,
                'catatan_bendahara' => 'Riwayat pembayaran kurang konsisten',
                'catatan_appraiser' => 'Agunan berupa deposito, sangat aman',
                'catatan_ketua' => 'Ditolak karena riwayat pembayaran bermasalah',
                'status_kredit' => 'Ditolak',
                'status_aktif' => 'Tidak Aktif'
            ],
            [
                'id_anggota' => 5,
                'tanggal_pengajuan' => '2024-05-12',
                'jumlah_pengajuan' => 75000000,
                'jangka_waktu' => 30,
                'tujuan_kredit' => 'Pembelian alat medis untuk praktek',
                'jenis_agunan' => 'Sertifikat Ruko',
                'nilai_taksiran_agunan' => 120000000,
                'catatan_bendahara' => 'Penghasilan tinggi dan stabil',
                'catatan_appraiser' => 'Sedang dalam proses penilaian agunan',
                'catatan_ketua' => 'Menunggu hasil penilaian final',
                'status_kredit' => 'Dalam Proses',
                'status_aktif' => 'Aktif'
            ]
        ];

        foreach ($kreditData as $data) {
            // Check if credit already exists
            $existingKredit = $kreditModel->where([
                'id_anggota' => $data['id_anggota'],
                'tanggal_pengajuan' => $data['tanggal_pengajuan']
            ])->first();
            
            if (!$existingKredit) {
                $kreditModel->save($data);
                echo "Kredit untuk anggota ID {$data['id_anggota']} berhasil dibuat.\n";
            } else {
                echo "Kredit untuk anggota ID {$data['id_anggota']} dengan tanggal {$data['tanggal_pengajuan']} sudah ada.\n";
            }
        }
    }
}