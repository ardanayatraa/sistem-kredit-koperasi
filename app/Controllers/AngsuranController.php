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
        helper(['form', 'url', 'data_filter']);
    }

    public function index()
    {
        // Use filtered method with user-based access control
        $data['angsuran'] = $this->angsuranModel->getFilteredAngsuranWithData();
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

            // Hitung angsuran bulanan
            $jangkaWaktu = $kredit['jangka_waktu']; // dalam bulan
            $jumlahKredit = $pencairan['jumlah_dicairkan'];
            $bunga = 0.02; // 2% per bulan (bisa diambil dari tabel bunga)

            // Rumus angsuran bulanan = (Pokok + Bunga) / Jangka Waktu
            $bungaTotal = $jumlahKredit * $bunga * $jangkaWaktu;
            $totalKembali = $jumlahKredit + $bungaTotal;
            $angsuranPerBulan = $totalKembali / $jangkaWaktu;

            // Buat HANYA angsuran ke-1 setelah pencairan
            $tanggalPencairan = new \DateTime($pencairan['tanggal_pencairan']);
            $tanggalJatuhTempo = clone $tanggalPencairan;
            $tanggalJatuhTempo->add(new \DateInterval("P1M")); // 1 bulan setelah pencairan

            $angsuranPertama = [
                'id_kredit_ref' => $id_kredit,
                'angsuran_ke' => 1,
                'jumlah_angsuran' => round($angsuranPerBulan, 0),
                'tgl_jatuh_tempo' => $tanggalJatuhTempo->format('Y-m-d'),
                'status_pembayaran' => 'Belum Bayar'
            ];

            // Insert hanya angsuran ke-1
            $this->angsuranModel->insert($angsuranPertama);

            return $this->response->setJSON([
                'success' => true,
                'message' => "Angsuran ke-1 berhasil dibuat setelah pencairan",
                'data' => [
                    'angsuran_ke' => 1,
                    'angsuran_per_bulan' => round($angsuranPerBulan, 0),
                    'jatuh_tempo' => $tanggalJatuhTempo->format('d/m/Y'),
                    'total_jangka_waktu' => $jangkaWaktu
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
     * Generate angsuran pertama untuk internal call (dari PencairanController)
     * Returns array instead of JSON response
     */
    public function generateAngsuranPertamaInternal($id_kredit)
    {
        try {
            // Ambil data kredit
            $kredit = $this->kreditModel->find($id_kredit);
            if (!$kredit) {
                return ['success' => false, 'message' => 'Data kredit tidak ditemukan'];
            }

            // Cek apakah sudah ada pencairan
            $pencairan = $this->pencairanModel->where('id_kredit', $id_kredit)->first();
            if (!$pencairan) {
                return ['success' => false, 'message' => 'Kredit belum dicairkan'];
            }

            // Cek apakah jadwal sudah dibuat
            $existingAngsuran = $this->angsuranModel->where('id_kredit_ref', $id_kredit)->countAllResults();
            if ($existingAngsuran > 0) {
                return ['success' => false, 'message' => 'Jadwal angsuran sudah dibuat sebelumnya'];
            }

            // Hitung angsuran bulanan
            $jangkaWaktu = $kredit['jangka_waktu']; // dalam bulan
            $jumlahKredit = $pencairan['jumlah_dicairkan'];
            $bunga = 0.02; // 2% per bulan (bisa diambil dari tabel bunga)

            // Rumus angsuran bulanan = (Pokok + Bunga) / Jangka Waktu
            $bungaTotal = $jumlahKredit * $bunga * $jangkaWaktu;
            $totalKembali = $jumlahKredit + $bungaTotal;
            $angsuranPerBulan = $totalKembali / $jangkaWaktu;

            // Buat HANYA angsuran ke-1 setelah pencairan
            $tanggalPencairan = new \DateTime($pencairan['tanggal_pencairan']);
            $tanggalJatuhTempo = clone $tanggalPencairan;
            $tanggalJatuhTempo->add(new \DateInterval("P1M")); // 1 bulan setelah pencairan

            $angsuranPertama = [
                'id_kredit_ref' => $id_kredit,
                'angsuran_ke' => 1,
                'jumlah_angsuran' => round($angsuranPerBulan, 0),
                'tgl_jatuh_tempo' => $tanggalJatuhTempo->format('Y-m-d'),
                'status_pembayaran' => 'Belum Bayar'
            ];

            // Insert hanya angsuran ke-1
            $this->angsuranModel->insert($angsuranPertama);

            return [
                'success' => true,
                'message' => "Angsuran ke-1 berhasil dibuat setelah pencairan",
                'data' => [
                    'angsuran_ke' => 1,
                    'angsuran_per_bulan' => round($angsuranPerBulan, 0),
                    'jatuh_tempo' => $tanggalJatuhTempo->format('d/m/Y'),
                    'total_jangka_waktu' => $jangkaWaktu
                ]
            ];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    /**
     * Generate angsuran berikutnya setelah pembayaran lunas
     */
    public function generateAngsuranBerikutnya($id_kredit)
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

            // Ambil pencairan untuk kalkulasi
            $pencairan = $this->pencairanModel->where('id_kredit', $id_kredit)->first();
            if (!$pencairan) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data pencairan tidak ditemukan'
                ])->setStatusCode(404);
            }

            // Cari angsuran terakhir yang sudah ada
            $angsuranTerakhir = $this->angsuranModel->where('id_kredit_ref', $id_kredit)
                                                   ->orderBy('angsuran_ke', 'DESC')
                                                   ->first();
            
            if (!$angsuranTerakhir) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tidak ada angsuran sebelumnya'
                ])->setStatusCode(400);
            }

            // Check apakah angsuran terakhir sudah lunas
            $pembayaranModel = new \App\Models\PembayaranAngsuranModel();
            $totalDibayar = $pembayaranModel->selectSum('jumlah_bayar')
                                           ->where('id_angsuran', $angsuranTerakhir['id_angsuran'])
                                           ->first();

            $isLunas = ($totalDibayar['jumlah_bayar'] ?? 0) >= $angsuranTerakhir['jumlah_angsuran'];
            
            if (!$isLunas) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Angsuran ke-' . $angsuranTerakhir['angsuran_ke'] . ' belum lunas'
                ])->setStatusCode(400);
            }

            // Cek apakah masih dalam jangka waktu
            $jangkaWaktu = $kredit['jangka_waktu'];
            $angsuranBerikutnya = $angsuranTerakhir['angsuran_ke'] + 1;
            
            if ($angsuranBerikutnya > $jangkaWaktu) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Semua angsuran sudah selesai'
                ])->setStatusCode(400);
            }

            // Hitung angsuran bulanan (sama seperti sebelumnya)
            $jumlahKredit = $pencairan['jumlah_dicairkan'];
            $bunga = 0.02; // 2% per bulan
            $bungaTotal = $jumlahKredit * $bunga * $jangkaWaktu;
            $totalKembali = $jumlahKredit + $bungaTotal;
            $angsuranPerBulan = $totalKembali / $jangkaWaktu;

            // Hitung tanggal jatuh tempo (1 bulan setelah angsuran sebelumnya)
            $tanggalTerakhir = new \DateTime($angsuranTerakhir['tgl_jatuh_tempo']);
            $tanggalBerikutnya = clone $tanggalTerakhir;
            $tanggalBerikutnya->add(new \DateInterval("P1M"));

            // Buat angsuran berikutnya
            $angsuranBaru = [
                'id_kredit_ref' => $id_kredit,
                'angsuran_ke' => $angsuranBerikutnya,
                'jumlah_angsuran' => round($angsuranPerBulan, 0),
                'tgl_jatuh_tempo' => $tanggalBerikutnya->format('Y-m-d'),
                'status_pembayaran' => 'Belum Bayar'
            ];

            // Insert angsuran baru
            $this->angsuranModel->insert($angsuranBaru);

            return $this->response->setJSON([
                'success' => true,
                'message' => "Angsuran ke-{$angsuranBerikutnya} berhasil dibuat",
                'data' => [
                    'angsuran_ke' => $angsuranBerikutnya,
                    'jumlah_angsuran' => round($angsuranPerBulan, 0),
                    'jatuh_tempo' => $tanggalBerikutnya->format('d/m/Y'),
                    'sisa_angsuran' => $jangkaWaktu - $angsuranBerikutnya + 1
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
        // Check if user has access to this kredit
        $data['kredit'] = $this->kreditModel->findWithAccess($id_kredit);
        if (!$data['kredit']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kredit tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Use filtered method to get angsuran with payment status
        $additionalWhere = ['tbl_angsuran.id_kredit_ref' => $id_kredit];
        $select = 'tbl_angsuran.*,
                  COALESCE(SUM(tbl_pembayaran_angsuran.jumlah_bayar), 0) as total_dibayar,
                  CASE
                     WHEN COALESCE(SUM(tbl_pembayaran_angsuran.jumlah_bayar), 0) >= tbl_angsuran.jumlah_angsuran THEN "Lunas"
                     WHEN tbl_angsuran.tgl_jatuh_tempo < CURDATE() AND COALESCE(SUM(tbl_pembayaran_angsuran.jumlah_bayar), 0) < tbl_angsuran.jumlah_angsuran THEN "Terlambat"
                     ELSE "Belum Bayar"
                  END as status_realtime';

        // Manual query with filtering for complex join
        $builder = $this->angsuranModel->builder();
        $builder->select($select)
                ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref')
                ->join('tbl_pembayaran_angsuran', 'tbl_pembayaran_angsuran.id_angsuran = tbl_angsuran.id_angsuran', 'left')
                ->where('tbl_angsuran.id_kredit_ref', $id_kredit);

        // Apply user-based filtering
        $builder = applyDataScopeToQuery($builder, 'tbl_kredit');
        
        $data['jadwal_angsuran'] = $builder->groupBy('tbl_angsuran.id_angsuran')
                                          ->orderBy('tbl_angsuran.angsuran_ke', 'ASC')
                                          ->get()->getResultArray();

        return view('angsuran/jadwal', $data);
    }

    /**
     * Dashboard pembayaran untuk anggota
     */
    public function dashboardPembayaran($id_anggota = null)
    {
        // If no ID provided and user is Anggota, get from session
        if (!$id_anggota && isCurrentUserLevel('Anggota')) {
            $id_anggota = getCurrentUserAnggotaId();
        }

        if (!$id_anggota) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan');
            return redirect()->to('/dashboard');
        }

        // Use filtered method to get active kredits
        $kreditAktif = $this->kreditModel->getFilteredKredits([
            'id_anggota' => $id_anggota,
            'status_kredit' => 'Disetujui'
        ]);

        $data['dashboard'] = [];
        foreach ($kreditAktif as $kredit) {
            // Get unpaid angsuran using filtered method
            $angsuranBelumLunas = $this->angsuranModel->getAngsuranByAnggota($id_anggota, [
                'tbl_angsuran.id_kredit_ref' => $kredit['id_kredit'],
                'tbl_angsuran.status_pembayaran' => 'Belum Bayar'
            ]);

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
     * Form pembayaran angsuran untuk anggota
     */
    /**
     * Form pembayaran untuk anggota - tanpa parameter (auto-detect anggota)
     */
    public function bayarAngsuran($id_angsuran = null)
    {
        // Jika tidak ada ID angsuran, tampilkan daftar angsuran yang bisa dibayar
        if (!$id_angsuran) {
            return $this->daftarAngsuranBayar();
        }

        // Use filtered method with access control
        $angsuran = $this->angsuranModel->findWithAccess($id_angsuran);
        if (!$angsuran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Angsuran tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Ambil total yang sudah dibayar
        $pembayaranModel = new \App\Models\PembayaranAngsuranModel();
        $totalDibayar = $pembayaranModel
            ->selectSum('jumlah_bayar')
            ->where('id_angsuran', $id_angsuran)
            ->first();

        $data = [
            'angsuran' => $angsuran,
            'total_dibayar' => $totalDibayar['jumlah_bayar'] ?? 0,
            'validation' => session()->getFlashdata('validation')
        ];

        return view('angsuran/form_bayar', $data);
    }

    /**
     * Daftar angsuran yang bisa dibayar untuk anggota login
     */
    public function daftarAngsuranBayar()
    {
        $idAnggota = getCurrentUserAnggotaId();

        if (!$idAnggota) {
            return redirect()->to('/dashboard')->with('error', 'Anda belum terdaftar sebagai anggota aktif.');
        }

        // Simple query without problematic JOIN for now
        $builder = $this->angsuranModel->builder();
        $angsuranBelumLunas = $builder->select('tbl_angsuran.*, tbl_kredit.jumlah_pengajuan, tbl_kredit.jangka_waktu')
                                     ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref')
                                     ->where('tbl_kredit.id_anggota', $idAnggota)
                                     ->where('tbl_angsuran.status_pembayaran', 'Belum Bayar')
                                     ->orderBy('tbl_angsuran.tgl_jatuh_tempo', 'ASC')
                                     ->get()->getResultArray();

        // Manually calculate total_dibayar for each angsuran
        $pembayaranModel = new \App\Models\PembayaranAngsuranModel();
        foreach ($angsuranBelumLunas as &$angsuran) {
            // Get total payments for this angsuran using existing model methods
            $totalPembayaran = $pembayaranModel->where('id_angsuran', $angsuran['id_angsuran'])
                                              ->findAll();
            
            $angsuran['total_dibayar'] = 0;
            foreach ($totalPembayaran as $bayar) {
                $angsuran['total_dibayar'] += $bayar['jumlah_bayar'] ?? 0;
            }
        }

        // Get user data
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find(session()->get('id_user'));

        $data = [
            'angsuran_list' => $angsuranBelumLunas,
            'user_data' => $user
        ];

        return view('angsuran/daftar_bayar', $data);
    }

    /**
     * Proses pembayaran untuk anggota
     */
    public function prosesBayar($id_angsuran)
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'jumlah_bayar' => 'required|numeric|greater_than[0]',
            'metode_pembayaran' => 'required|in_list[transfer,tunai,debit]',
            'bukti_pembayaran' => 'uploaded[bukti_pembayaran]|is_image[bukti_pembayaran]|max_size[bukti_pembayaran,2048]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        try {
            $angsuran = $this->angsuranModel->find($id_angsuran);
            if (!$angsuran) {
                return redirect()->back()->with('error', 'Data angsuran tidak ditemukan');
            }

            // Upload file bukti pembayaran
            $file = $this->request->getFile('bukti_pembayaran');
            if ($file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(WRITEPATH . '../public/uploads/bukti_pembayaran', $fileName);
            } else {
                return redirect()->back()->with('error', 'Gagal mengupload bukti pembayaran');
            }

            // Simpan data pembayaran langsung ke model
            $pembayaranModel = new \App\Models\PembayaranAngsuranModel();
            
            $dataPembayaran = [
                'id_angsuran' => $id_angsuran,
                'jumlah_bayar' => $this->request->getPost('jumlah_bayar'),
                'tanggal_bayar' => date('Y-m-d H:i:s'),
                'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
                'bukti_pembayaran' => 'uploads/bukti_pembayaran/' . $fileName,
                'keterangan' => $this->request->getPost('keterangan') ?? 'Pembayaran melalui portal anggota',
                'status_verifikasi' => 'Menunggu',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $result = $pembayaranModel->insert($dataPembayaran);
            
            if ($result) {
                // Log pembayaran
                log_message('info', 'Pembayaran baru dari anggota - ID Angsuran: ' . $id_angsuran . ', Jumlah: ' . $this->request->getPost('jumlah_bayar'));
                
                return redirect()->to("/bayar-angsuran")
                               ->with('success', 'Pembayaran berhasil diproses. Menunggu verifikasi bendahara.');
            } else {
                return redirect()->back()->with('error', 'Gagal menyimpan data pembayaran');
            }

        } catch (\Exception $e) {
            log_message('error', 'Error proses bayar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
