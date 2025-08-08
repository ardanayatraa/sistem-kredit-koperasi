<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;
use App\Models\KreditModel;
use App\Models\PencairanModel;
use App\Models\AngsuranModel;

class FixAnggotaDemoSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();
        $kreditModel = new KreditModel();
        $pencairanModel = new PencairanModel();
        $angsuranModel = new AngsuranModel();

        // 1. Link anggota_demo ke anggota ID 1 (yang sudah punya kredit disetujui)
        $anggotaDemo = $userModel->where('username', 'anggota_demo')->first();
        if ($anggotaDemo && is_null($anggotaDemo['id_anggota_ref'])) {
            $userModel->update($anggotaDemo['id_user'], ['id_anggota_ref' => 1]);
            echo "User anggota_demo berhasil dilink ke anggota ID 1.\n";
        }

        // 2. Tambah kredit khusus untuk anggota demo (ID 1) jika belum ada yang bisa dibayar
        $existingKredit = $kreditModel->where([
            'id_anggota' => 1,
            'status_kredit' => 'Disetujui'
        ])->first();

        if ($existingKredit) {
            $kreditId = $existingKredit['id_kredit'];
            echo "Menggunakan kredit existing ID: $kreditId untuk anggota_demo.\n";

            // Cek apakah sudah ada pencairan untuk kredit ini
            $existingPencairan = $pencairanModel->where('id_kredit', $kreditId)->first();
            
            if (!$existingPencairan) {
                try {
                    // Cek apakah bunga ID 1 ada
                    $bungaId = $this->db->table('tbl_bunga')->where('id_bunga', 1)->get()->getRow();
                    if (!$bungaId) {
                        echo "Bunga ID 1 tidak ditemukan, menggunakan bunga ID pertama yang tersedia.\n";
                        $firstBunga = $this->db->table('tbl_bunga')->orderBy('id_bunga', 'ASC')->get()->getRow();
                        $bungaIdToUse = $firstBunga ? $firstBunga->id_bunga : 1;
                    } else {
                        $bungaIdToUse = 1;
                    }

                    // Buat pencairan baru
                    $pencairanData = [
                        'id_kredit' => $kreditId,
                        'tanggal_pencairan' => date('Y-m-d'),
                        'jumlah_dicairkan' => $existingKredit['jumlah_pengajuan'],
                        'metode_pencairan' => 'Transfer Bank',
                        'id_bunga' => $bungaIdToUse,
                        'bukti_transfer' => 'demo_transfer.pdf'
                    ];
                    
                    if ($pencairanModel->save($pencairanData)) {
                        echo "Pencairan untuk kredit ID $kreditId berhasil dibuat.\n";
                    } else {
                        echo "Error saat menyimpan pencairan: " . implode(', ', $pencairanModel->errors()) . "\n";
                    }
                } catch (\Exception $e) {
                    echo "Exception saat membuat pencairan: " . $e->getMessage() . "\n";
                }
            } else {
                echo "Pencairan untuk kredit ID $kreditId sudah ada.\n";
            }

            // Cek apakah sudah ada angsuran
            $existingAngsuran = $angsuranModel->where('id_kredit_ref', $kreditId)->countAllResults();
            
            if ($existingAngsuran == 0) {
                // Buat jadwal angsuran demo
                $jangkaWaktu = $existingKredit['jangka_waktu']; // bulan
                $jumlahKredit = $existingKredit['jumlah_pengajuan'];
                $bunga = 0.02; // 2% per bulan
                
                // Hitung angsuran per bulan
                $bungaTotal = $jumlahKredit * $bunga * $jangkaWaktu;
                $totalKembali = $jumlahKredit + $bungaTotal;
                $angsuranPerBulan = $totalKembali / $jangkaWaktu;
                
                // Generate jadwal untuk semua bulan (supaya lengkap)
                $angsuranData = [];
                for ($i = 1; $i <= $jangkaWaktu; $i++) {
                    $tglJatuhTempo = date('Y-m-d', strtotime("+$i month"));
                    
                    $angsuranData[] = [
                        'id_kredit_ref' => $kreditId,
                        'angsuran_ke' => $i,
                        'jumlah_angsuran' => round($angsuranPerBulan, 0),
                        'tgl_jatuh_tempo' => $tglJatuhTempo,
                        'status_pembayaran' => 'Belum Bayar'
                    ];
                }
                
                $angsuranModel->insertBatch($angsuranData);
                echo "Jadwal angsuran untuk kredit ID $kreditId berhasil dibuat ($jangkaWaktu bulan).\n";
            } else {
                echo "Jadwal angsuran untuk kredit ID $kreditId sudah ada ($existingAngsuran records).\n";
            }

            // Update status kredit menjadi Aktif
            if ($existingKredit['status_aktif'] != 'Aktif') {
                $kreditModel->update($kreditId, ['status_aktif' => 'Aktif']);
                echo "Status kredit ID $kreditId diubah menjadi Aktif.\n";
            }
        } else {
            echo "Tidak ada kredit disetujui untuk anggota ID 1.\n";
        }

        echo "Fix anggota_demo selesai!\n";
    }
}