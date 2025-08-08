<?php

namespace App\Controllers;

use App\Models\AngsuranModel;
use App\Models\KreditModel;
use App\Models\PencairanModel;
use CodeIgniter\Controller;

class AngsuranController extends Controller
{
    protected $angsuranModel;
    protected $kreditModel;
    protected $pencairanModel;

    public function __construct()
    {
        $this->angsuranModel = new AngsuranModel();
        $this->kreditModel = new KreditModel();
        $this->pencairanModel = new PencairanModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['angsuran'] = $this->angsuranModel->findAll();
        return view('angsuran/index', $data);
    }

    /**
     * Generate jadwal angsuran otomatis setelah pencairan
     */
    public function generateJadwalAngsuran($id_kredit)
    {
        try {
            // Ambil data kredit
            $kredit = $this->kreditModel->find($id_kredit);
            if (!$kredit) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data kredit tidak ditemukan'
                ])->setStatusCode(404);
            }

            // Cek apakah sudah ada pencairan
            $pencairan = $this->pencairanModel->where('id_kredit', $id_kredit)->first();
            if (!$pencairan) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Kredit belum dicairkan'
                ])->setStatusCode(400);
            }

            // Cek apakah jadwal sudah dibuat
            $existingAngsuran = $this->angsuranModel->where('id_kredit', $id_kredit)->countAllResults();
            if ($existingAngsuran > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Jadwal angsuran sudah dibuat sebelumnya'
                ])->setStatusCode(400);
            }

            // Hitung jadwal angsuran
            $jangkaWaktu = $kredit['jangka_waktu']; // dalam bulan
            $jumlahKredit = $pencairan['jumlah_dicairkan'];
            $bunga = 0.02; // 2% per bulan (bisa diambil dari tabel bunga)

            // Rumus angsuran bulanan = (Pokok + Bunga) / Jangka Waktu
            $bungaTotal = $jumlahKredit * $bunga * $jangkaWaktu;
            $totalKembali = $jumlahKredit + $bungaTotal;
            $angsuranPerBulan = $totalKembali / $jangkaWaktu;

            // Generate jadwal untuk setiap bulan
            $tanggalPencairan = new \DateTime($pencairan['tanggal_pencairan']);
            $jadwalAngsuran = [];

            for ($i = 1; $i <= $jangkaWaktu; $i++) {
                $tanggalJatuhTempo = clone $tanggalPencairan;
                $tanggalJatuhTempo->add(new \DateInterval("P{$i}M"));

                $jadwalAngsuran[] = [
                    'id_kredit_ref' => $id_kredit,
                    'angsuran_ke' => $i,
                    'jumlah_angsuran' => round($angsuranPerBulan, 0),
                    'tgl_jatuh_tempo' => $tanggalJatuhTempo->format('Y-m-d'),
                    'status_pembayaran' => 'Belum Bayar'
                ];
            }

            // Insert jadwal angsuran
            $this->angsuranModel->insertBatch($jadwalAngsuran);

            return $this->response->setJSON([
                'success' => true,
                'message' => "Jadwal angsuran berhasil dibuat untuk {$jangkaWaktu} bulan",
                'data' => [
                    'jumlah_angsuran' => count($jadwalAngsuran),
                    'angsuran_per_bulan' => round($angsuranPerBulan, 0),
                    'total_kembali' => round($totalKembali, 0)
                ]
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Lihat jadwal angsuran per kredit
     */
    public function lihatJadwal($id_kredit)
    {
        $data['kredit'] = $this->kreditModel->find($id_kredit);
        if (!$data['kredit']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kredit tidak ditemukan.');
        }

        // Ambil jadwal angsuran dengan status pembayaran
        $data['jadwal_angsuran'] = $this->angsuranModel
            ->select('tbl_angsuran.*,
                     COALESCE(SUM(tbl_pembayaran_angsuran.jumlah_bayar), 0) as total_dibayar,
                     CASE
                        WHEN COALESCE(SUM(tbl_pembayaran_angsuran.jumlah_bayar), 0) >= tbl_angsuran.jumlah_angsuran THEN "Lunas"
                        WHEN tbl_angsuran.tgl_jatuh_tempo < CURDATE() AND COALESCE(SUM(tbl_pembayaran_angsuran.jumlah_bayar), 0) < tbl_angsuran.jumlah_angsuran THEN "Terlambat"
                        ELSE "Belum Bayar"
                     END as status_realtime')
            ->join('tbl_pembayaran_angsuran', 'tbl_pembayaran_angsuran.id_angsuran = tbl_angsuran.id_angsuran', 'left')
            ->where('tbl_angsuran.id_kredit_ref', $id_kredit)
            ->groupBy('tbl_angsuran.id_angsuran')
            ->orderBy('tbl_angsuran.angsuran_ke', 'ASC')
            ->findAll();

        return view('angsuran/jadwal', $data);
    }

    /**
     * Dashboard pembayaran untuk anggota
     */
    public function dashboardPembayaran($id_anggota)
    {
        // Ambil kredit yang aktif untuk anggota
        $kreditAktif = $this->kreditModel
            ->where('id_anggota', $id_anggota)
            ->where('status_kredit', 'Disetujui')
            ->findAll();

        $data['dashboard'] = [];
        foreach ($kreditAktif as $kredit) {
            // Ambil angsuran yang belum lunas
            $angsuranBelumLunas = $this->angsuranModel
                ->select('tbl_angsuran.*,
                         COALESCE(SUM(tbl_pembayaran_angsuran.jumlah_bayar), 0) as total_dibayar')
                ->join('tbl_pembayaran_angsuran', 'tbl_pembayaran_angsuran.id_angsuran = tbl_angsuran.id_angsuran', 'left')
                ->where('tbl_angsuran.id_kredit_ref', $kredit['id_kredit'])
                ->groupBy('tbl_angsuran.id_angsuran')
                ->having('total_dibayar < tbl_angsuran.jumlah_angsuran OR total_dibayar IS NULL')
                ->orderBy('tbl_angsuran.tgl_jatuh_tempo', 'ASC')
                ->findAll();

            $data['dashboard'][] = [
                'kredit' => $kredit,
                'angsuran_terdekat' => $angsuranBelumLunas[0] ?? null,
                'total_belum_lunas' => count($angsuranBelumLunas)
            ];
        }

        $data['id_anggota'] = $id_anggota;
        return view('angsuran/dashboard_pembayaran', $data);
    }

    /**
     * Proses pembayaran angsuran (redirect ke PembayaranAngsuranController)
     */
    public function bayarAngsuran($id_angsuran)
    {
        $angsuran = $this->angsuranModel->find($id_angsuran);
        if (!$angsuran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Angsuran tidak ditemukan.');
        }

        // Redirect ke form pembayaran
        return redirect()->to("/pembayaran-angsuran/new?id_angsuran={$id_angsuran}");
    }
}
