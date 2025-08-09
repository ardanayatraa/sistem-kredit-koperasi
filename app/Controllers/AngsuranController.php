<?php

namespace App\Controllers;

use App\Models\AngsuranModel;
use App\Models\KreditModel;
use App\Models\PencairanModel;
use App\Controllers\BaseController;

class AngsuranController extends BaseController
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

            // Ambil data bunga dari pencairan
            $bungaModel = new \App\Models\BungaModel();
            $bunga = $bungaModel->find($pencairan['id_bunga']);
            if (!$bunga) {
                return ['success' => false, 'message' => 'Data bunga tidak ditemukan'];
            }

            // Load InterestCalculator library
            $interestCalculator = new \App\Libraries\InterestCalculator();

            // Parameter kalkulasi
            $principal = $pencairan['jumlah_dicairkan'];
            $rate = $bunga['persentase_bunga'];
            $periods = $kredit['jangka_waktu'];
            $interestType = $bunga['tipe_bunga'];

            // Generate jadwal lengkap dengan bunga otomatis
            $schedule = $interestCalculator->calculateInstallmentSchedule(
                $principal, $rate, $periods, $interestType
            );

            // Generate tanggal jatuh tempo
            $scheduleWithDates = $interestCalculator->generateDueDates(
                $pencairan['tanggal_pencairan'], $periods, $schedule
            );

            // Insert HANYA angsuran ke-1 dari jadwal yang telah dihitung
            if (!empty($scheduleWithDates)) {
                $angsuranPertama = [
                    'id_kredit_ref' => $id_kredit,
                    'angsuran_ke' => $scheduleWithDates[0]['angsuran_ke'],
                    'jumlah_angsuran' => $scheduleWithDates[0]['jumlah_angsuran'],
                    'tgl_jatuh_tempo' => $scheduleWithDates[0]['tgl_jatuh_tempo'],
                    'status_pembayaran' => 'Belum Bayar'
                ];

                // Insert hanya angsuran ke-1
                $this->angsuranModel->insert($angsuranPertama);

                // Hitung total untuk informasi
                $totals = $interestCalculator->calculateTotals($scheduleWithDates);

                return [
                    'success' => true,
                    'message' => "Angsuran ke-1 berhasil dibuat dengan kalkulasi bunga {$interestType}",
                    'data' => [
                        'angsuran_ke' => 1,
                        'angsuran_per_bulan' => $scheduleWithDates[0]['jumlah_angsuran'],
                        'jatuh_tempo' => date('d/m/Y', strtotime($scheduleWithDates[0]['tgl_jatuh_tempo'])),
                        'total_jangka_waktu' => $periods,
                        'tipe_bunga' => $interestType,
                        'rate' => $rate . '%',
                        'total_pembayaran' => $totals['total_pembayaran'],
                        'total_bunga' => $totals['total_bunga']
                    ]
                ];
            } else {
                return ['success' => false, 'message' => 'Gagal membuat jadwal angsuran'];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    /**
     * 🚀 GENERATE SEMUA ANGSURAN SEKALIGUS untuk internal call (dari KreditController)
     * Returns array instead of JSON response
     * USER REQUEST: "ketika bendahra mencairkan kan harusnya otomatis create pencairan dan kalau duah buat angsuran ke 1 nya k dan sterusnya"
     */
    public function generateAllAngsuranSekaligusInternal($id_kredit)
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

            // Ambil data bunga dari pencairan
            $bungaModel = new \App\Models\BungaModel();
            $bunga = $bungaModel->find($pencairan['id_bunga']);
            if (!$bunga) {
                return ['success' => false, 'message' => 'Data bunga tidak ditemukan'];
            }

            // Load InterestCalculator library
            $interestCalculator = new \App\Libraries\InterestCalculator();

            // Parameter kalkulasi
            $principal = $pencairan['jumlah_dicairkan'];
            $rate = $bunga['persentase_bunga'];
            $periods = $kredit['jangka_waktu'];
            $interestType = $bunga['tipe_bunga'];

            // Generate jadwal lengkap dengan bunga otomatis
            $schedule = $interestCalculator->calculateInstallmentSchedule(
                $principal, $rate, $periods, $interestType
            );

            // Generate tanggal jatuh tempo
            $scheduleWithDates = $interestCalculator->generateDueDates(
                $pencairan['tanggal_pencairan'], $periods, $schedule
            );

            // Insert SEMUA angsuran dari jadwal yang telah dihitung
            $insertedCount = 0;
            if (!empty($scheduleWithDates)) {
                foreach ($scheduleWithDates as $item) {
                    $angsuranData = [
                        'id_kredit_ref' => $id_kredit,
                        'angsuran_ke' => $item['angsuran_ke'],
                        'jumlah_angsuran' => $item['jumlah_angsuran'],
                        'tgl_jatuh_tempo' => $item['tgl_jatuh_tempo'],
                        'status_pembayaran' => 'Belum Bayar'
                    ];

                    $this->angsuranModel->insert($angsuranData);
                    $insertedCount++;
                }

                // Hitung total untuk informasi
                $totals = $interestCalculator->calculateTotals($scheduleWithDates);

                return [
                    'success' => true,
                    'message' => "Berhasil membuat {$insertedCount} jadwal angsuran dengan kalkulasi bunga {$interestType}",
                    'data' => [
                        'total_angsuran_dibuat' => $insertedCount,
                        'periode_jangka_waktu' => $periods . ' bulan',
                        'tipe_bunga' => $interestType,
                        'rate' => $rate . '%',
                        'total_pembayaran' => $totals['total_pembayaran'],
                        'total_bunga' => $totals['total_bunga']
                    ]
                ];
            } else {
                return ['success' => false, 'message' => 'Gagal membuat jadwal angsuran'];
            }

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
                session()->setFlashdata('error', 'Data kredit tidak ditemukan');
                return redirect()->back();
            }

            // Ambil pencairan untuk kalkulasi
            $pencairan = $this->pencairanModel->where('id_kredit', $id_kredit)->first();
            if (!$pencairan) {
                session()->setFlashdata('error', 'Data pencairan tidak ditemukan');
                return redirect()->back();
            }

            // Cari angsuran terakhir yang sudah ada
            $angsuranTerakhir = $this->angsuranModel->where('id_kredit_ref', $id_kredit)
                                                   ->orderBy('angsuran_ke', 'DESC')
                                                   ->first();
            
            if (!$angsuranTerakhir) {
                session()->setFlashdata('error', 'Tidak ada angsuran sebelumnya');
                return redirect()->back();
            }

            // Check apakah angsuran terakhir sudah lunas
            $pembayaranModel = new \App\Models\PembayaranAngsuranModel();
            $totalDibayar = $pembayaranModel->selectSum('jumlah_bayar')
                                           ->where('id_angsuran', $angsuranTerakhir['id_angsuran'])
                                           ->first();

            $isLunas = ($totalDibayar['jumlah_bayar'] ?? 0) >= $angsuranTerakhir['jumlah_angsuran'];
            
            if (!$isLunas) {
                session()->setFlashdata('error', 'Angsuran ke-' . $angsuranTerakhir['angsuran_ke'] . ' belum lunas');
                return redirect()->back();
            }

            // Cek apakah masih dalam jangka waktu
            $jangkaWaktu = $kredit['jangka_waktu'];
            $angsuranBerikutnya = $angsuranTerakhir['angsuran_ke'] + 1;
            
            if ($angsuranBerikutnya > $jangkaWaktu) {
                session()->setFlashdata('error', 'Semua angsuran sudah selesai');
                return redirect()->back();
            }

            // Ambil data bunga dari pencairan untuk kalkulasi yang akurat
            $bungaModel = new \App\Models\BungaModel();
            $bunga = $bungaModel->find($pencairan['id_bunga']);
            if (!$bunga) {
                session()->setFlashdata('error', 'Data bunga tidak ditemukan');
                return redirect()->back();
            }

            // Load InterestCalculator library
            $interestCalculator = new \App\Libraries\InterestCalculator();

            // Generate jadwal lengkap untuk mendapatkan angsuran yang tepat
            $schedule = $interestCalculator->calculateInstallmentSchedule(
                $pencairan['jumlah_dicairkan'],
                $bunga['persentase_bunga'],
                $jangkaWaktu,
                $bunga['tipe_bunga']
            );

            // Generate tanggal jatuh tempo
            $scheduleWithDates = $interestCalculator->generateDueDates(
                $pencairan['tanggal_pencairan'], $jangkaWaktu, $schedule
            );

            // Ambil angsuran sesuai urutan yang benar
            $targetSchedule = null;
            foreach ($scheduleWithDates as $item) {
                if ($item['angsuran_ke'] == $angsuranBerikutnya) {
                    $targetSchedule = $item;
                    break;
                }
            }

            if (!$targetSchedule) {
                session()->setFlashdata('error', 'Gagal menghitung angsuran berikutnya');
                return redirect()->back();
            }

            // Buat angsuran berikutnya dengan nilai yang tepat
            $angsuranBaru = [
                'id_kredit_ref' => $id_kredit,
                'angsuran_ke' => $angsuranBerikutnya,
                'jumlah_angsuran' => $targetSchedule['jumlah_angsuran'],
                'tgl_jatuh_tempo' => $targetSchedule['tgl_jatuh_tempo'],
                'status_pembayaran' => 'Belum Bayar'
            ];

            // Insert angsuran baru
            $this->angsuranModel->insert($angsuranBaru);
            
            session()->setFlashdata('success', "Angsuran ke-{$angsuranBerikutnya} berhasil dibuat");
            return redirect()->back();

        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back();
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
            'validation' => session()->getFlashdata('validation'),
            'isOverdue' => strtotime($angsuran['tgl_jatuh_tempo']) < strtotime('now')
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
                $uploadPath = WRITEPATH . 'uploads/pembayaran_angsuran';
                if (!is_dir($uploadPath)) { mkdir($uploadPath, 0777, true); }
                
                $fileName = $file->getRandomName();
                $file->move($uploadPath, $fileName);
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
                'bukti_pembayaran' => $fileName, // Store only filename, not full path
                'status_verifikasi' => 'pending', // Use consistent status with PembayaranAngsuranController
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
