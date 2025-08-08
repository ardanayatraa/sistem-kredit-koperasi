<?php

namespace App\Controllers;

use App\Models\BungaModel;

class SimulasiBungaController extends BaseController
{
    protected $bungaModel;

    public function __construct()
    {
        $this->bungaModel = new BungaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Simulasi Bunga',
            'headerTitle' => 'Simulasi Bunga Kredit'
        ];

        // Get active bunga rate
        $bunga = $this->bungaModel->where('status_aktif', 'Aktif')->first();
        $data['bunga'] = $bunga;

        return view('simulasi_bunga/index', $data);
    }

    public function calculate()
    {
        $jumlahKredit = $this->request->getPost('jumlah_kredit');
        $tenorBulan = $this->request->getPost('tenor_bulan');
        $jenisBunga = $this->request->getPost('jenis_bunga') ?? 'flat';

        // Get active bunga rate
        $bunga = $this->bungaModel->where('status_aktif', 'Aktif')->first();
        
        if (!$bunga) {
            session()->setFlashdata('error', 'Suku bunga belum ditetapkan');
            return redirect()->to('/simulasi-bunga');
        }

        $persentaseBunga = $bunga['persentase_bunga'];

        // Calculate based on bunga type
        if ($jenisBunga === 'flat') {
            // Flat rate calculation
            $bungaPerBulan = ($jumlahKredit * $persentaseBunga / 100);
            $pokokPerBulan = $jumlahKredit / $tenorBulan;
            $angsuranPerBulan = $pokokPerBulan + $bungaPerBulan;
            $totalBunga = $bungaPerBulan * $tenorBulan;
            $totalBayar = $jumlahKredit + $totalBunga;
        } else {
            // Annuity calculation (efektif)
            $bungaBulanan = $persentaseBunga / 100 / 12;
            $angsuranPerBulan = $jumlahKredit * ($bungaBulanan * pow(1 + $bungaBulanan, $tenorBulan)) / (pow(1 + $bungaBulanan, $tenorBulan) - 1);
            $totalBayar = $angsuranPerBulan * $tenorBulan;
            $totalBunga = $totalBayar - $jumlahKredit;
        }

        // Create payment schedule
        $jadwalPembayaran = [];
        $sisaPokok = $jumlahKredit;

        for ($i = 1; $i <= $tenorBulan; $i++) {
            if ($jenisBunga === 'flat') {
                $bungaBulanIni = $bungaPerBulan;
                $pokokBulanIni = $pokokPerBulan;
                $sisaPokok -= $pokokBulanIni;
            } else {
                // Efektif calculation for each month
                $bungaBulanIni = $sisaPokok * $bungaBulanan;
                $pokokBulanIni = $angsuranPerBulan - $bungaBulanIni;
                $sisaPokok -= $pokokBulanIni;
            }

            $jadwalPembayaran[] = [
                'ke' => $i,
                'pokok' => $pokokBulanIni,
                'bunga' => $bungaBulanIni,
                'angsuran' => $angsuranPerBulan,
                'sisa_pokok' => max(0, $sisaPokok)
            ];
        }

        $hasil = [
            'jumlah_kredit' => $jumlahKredit,
            'tenor_bulan' => $tenorBulan,
            'persentase_bunga' => $persentaseBunga,
            'jenis_bunga' => $jenisBunga,
            'angsuran_per_bulan' => $angsuranPerBulan,
            'total_bunga' => $totalBunga,
            'total_bayar' => $totalBayar,
            'jadwal_pembayaran' => $jadwalPembayaran
        ];

        $data = [
            'title' => 'Hasil Simulasi Bunga',
            'headerTitle' => 'Hasil Simulasi Bunga Kredit',
            'hasil' => $hasil,
            'bunga' => $bunga
        ];

        return view('simulasi_bunga/hasil', $data);
    }
}