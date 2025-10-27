<?php

namespace App\Controllers;

use App\Models\PembayaranAngsuranModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Files\UploadedFile;

class PembayaranAngsuranController extends Controller
{
    protected $pembayaranAngsuranModel;

    public function __construct()
    {
        $this->pembayaranAngsuranModel = new PembayaranAngsuranModel();
        helper(['form', 'url', 'data_filter']);
    }

    public function index()
    {
        // Use filtered method with user-based access control
        $pembayaranAngsuran = $this->pembayaranAngsuranModel->getFilteredPembayaranWithData();

        // Sort by created_at DESC (data terbaru di atas)
        usort($pembayaranAngsuran, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        // Calculate statistics
        $totalPembayaran = count($pembayaranAngsuran);
        $pembayaranHariIni = count(array_filter($pembayaranAngsuran, function($item) {
            return date('Y-m-d', strtotime($item['tanggal_bayar'])) === date('Y-m-d');
        }));
        $totalNominal = array_sum(array_column($pembayaranAngsuran, 'jumlah_bayar'));
        $rataRata = $totalPembayaran > 0 ? $totalNominal / $totalPembayaran : 0;

        $data = [
            'pembayaran_angsuran' => $pembayaranAngsuran,
            'stats' => [
                'total_pembayaran' => $totalPembayaran,
                'pembayaran_hari_ini' => $pembayaranHariIni,
                'total_nominal' => $totalNominal,
                'rata_rata' => $rataRata
            ]
        ];

        return view('pembayaran_angsuran/index', $data);
    }

    /**
     * Dashboard verifikasi pembayaran untuk Bendahara
     */
    public function verifikasi()
    {
        $currentUserLevel = session()->get('level');
        
        if (!in_array($currentUserLevel, ['Bendahara', 'Ketua'])) {
            session()->setFlashdata('error', 'Akses ditolak. Hanya Bendahara yang dapat mengakses halaman ini.');
            return redirect()->to('/dashboard');
        }

        // Get pembayaran yang pending verifikasi
        $pembayaranPending = $this->pembayaranAngsuranModel->getFilteredPembayaranWithData(
            ['tbl_pembayaran_angsuran.status_verifikasi' => 'pending'],
            'tbl_pembayaran_angsuran.*, tbl_angsuran.angsuran_ke, tbl_angsuran.jumlah_angsuran,
             tbl_kredit.id_kredit, tbl_users.nama_lengkap, tbl_anggota.no_anggota'
        );

        $data = [
            'title' => 'Verifikasi Pembayaran Angsuran',
            'headerTitle' => 'Verifikasi Pembayaran Angsuran',
            'pembayaran_pending' => $pembayaranPending
        ];

        return view('pembayaran_angsuran/verifikasi', $data);
    }

    public function new()
    {
        // Method ini untuk BENDAHARA - perlu pilih anggota dan angsuran
        $currentUserLevel = session()->get('level');
        
        if ($currentUserLevel === 'Anggota') {
            // Anggota tidak boleh akses form bendahara, redirect ke form mereka
            return redirect()->to('/angsuran/bayar');
        }

        // Load data untuk dropdown
        $anggotaModel = new \App\Models\AnggotaModel();
        $userModel = new \App\Models\UserModel();
        
        // Get all active anggota untuk dropdown
        $anggotaList = $anggotaModel->where('status_keanggotaan', 'Aktif')->findAll();
        
        // Join dengan users untuk dapat nama lengkap
        $anggotaOptions = [];
        foreach ($anggotaList as $anggota) {
            $user = $userModel->where('id_anggota_ref', $anggota['id_anggota'])->first();
            if ($user) {
                $anggotaOptions[] = [
                    'id' => $anggota['id_anggota'],
                    'nomor_anggota' => $anggota['no_anggota'],
                    'nama' => $user['nama_lengkap'],
                    'nik' => $anggota['nik']
                ];
            }
        }

        $data = [
            'title' => 'Input Pembayaran Angsuran (Bendahara)',
            'headerTitle' => 'Input Pembayaran Angsuran',
            'anggota_list' => $anggotaOptions,
            'is_bendahara_form' => true
        ];

        return view('pembayaran_angsuran/form_bendahara', $data);
    }

    /**
     * Get angsuran list for selected anggota (AJAX for bendahara)
     */
    public function getAngsuranByAnggota()
    {
        $currentUserLevel = session()->get('level');
        
        if ($currentUserLevel === 'Anggota') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak'
            ])->setStatusCode(403);
        }

        $anggotaId = $this->request->getPost('anggota_id');
        if (!$anggotaId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID anggota harus diisi'
            ])->setStatusCode(400);
        }

        $angsuranModel = new \App\Models\AngsuranModel();
        
        // Get unpaid/partially paid angsuran for the selected anggota
        $angsuranList = $angsuranModel
            ->select('tbl_angsuran.id_angsuran as id, tbl_angsuran.angsuran_ke, tbl_angsuran.jumlah_angsuran,
                     tbl_angsuran.tgl_jatuh_tempo, tbl_angsuran.status_pembayaran as status,
                     tbl_kredit.id_kredit')
            ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref')
            ->where('tbl_kredit.id_anggota', $anggotaId)
            ->whereIn('tbl_angsuran.status_pembayaran', ['Belum Bayar', 'Bayar Sebagian'])
            ->orderBy('tbl_angsuran.angsuran_ke', 'ASC')
            ->findAll();

        $options = [];
        foreach ($angsuranList as $angsuran) {
            // Hitung denda berdasarkan keterlambatan pembayaran
            $tglJatuhTempo = strtotime($angsuran['tgl_jatuh_tempo']);
            $tglSekarang = strtotime(date('Y-m-d'));
            $dendaAmount = 0;
            
            if ($tglSekarang > $tglJatuhTempo) {
                $selisihHari = ceil(($tglSekarang - $tglJatuhTempo) / (60 * 60 * 24));
                $dendaAmount = $selisihHari * 1000; // Denda 1000 per hari
            }
            
            $options[] = [
                'id' => $angsuran['id'],
                'angsuran_ke' => $angsuran['angsuran_ke'],
                'jumlah_angsuran' => $angsuran['jumlah_angsuran'],
                'tanggal_jatuh_tempo' => $angsuran['tgl_jatuh_tempo'],
                'status' => $angsuran['status'] === 'Belum Bayar' ? 'belum_bayar' : 'sebagian',
                'id_kredit' => $angsuran['id_kredit'],
                'denda' => $dendaAmount
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $options
        ]);
    }

    /**
     * Get detail angsuran by ID (AJAX for bendahara)
     */
    public function getDetailAngsuran()
    {
        $currentUserLevel = session()->get('level');
        
        if ($currentUserLevel === 'Anggota') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak'
            ])->setStatusCode(403);
        }

        $angsuranId = $this->request->getPost('angsuran_id');
        if (!$angsuranId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID angsuran harus diisi'
            ])->setStatusCode(400);
        }

        $angsuranModel = new \App\Models\AngsuranModel();
        
        // Get angsuran detail with kredit info
        $angsuran = $angsuranModel
            ->select('tbl_angsuran.*, tbl_kredit.id_kredit')
            ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref')
            ->find($angsuranId);

        if (!$angsuran) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data angsuran tidak ditemukan'
            ])->setStatusCode(404);
        }

        // Hitung denda berdasarkan keterlambatan pembayaran
        $tglJatuhTempo = strtotime($angsuran['tgl_jatuh_tempo']);
        $tglSekarang = strtotime(date('Y-m-d'));
        $dendaAmount = 0;
        
        if ($tglSekarang > $tglJatuhTempo) {
            $selisihHari = ceil(($tglSekarang - $tglJatuhTempo) / (60 * 60 * 24));
            $dendaAmount = $selisihHari * 1000; // Denda 1000 per hari
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'id_kredit' => $angsuran['id_kredit'],
                'angsuran_ke' => $angsuran['angsuran_ke'],
                'tanggal_jatuh_tempo' => $angsuran['tgl_jatuh_tempo'],
                'jumlah_angsuran' => $angsuran['jumlah_angsuran'],
                'denda' => $dendaAmount
            ]
        ]);
    }

    public function create()
    {
        $rules = [
            'id_angsuran' => 'required|integer',
            'tanggal_bayar' => 'required|valid_date',
            'jumlah_bayar' => 'required|numeric',
            'metode_pembayaran' => 'required|max_length[100]',
            'bukti_pembayaran' => 'uploaded[bukti_pembayaran]|max_size[bukti_pembayaran,2048]|ext_in[bukti_pembayaran,pdf,jpg,jpeg,png]',
            'denda' => 'permit_empty|numeric',
            'id_bendahara_verifikator' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        /** @var UploadedFile $buktiPembayaran */
        $buktiPembayaran = $this->request->getFile('bukti_pembayaran');

        $uploadPath = WRITEPATH . 'uploads/pembayaran_angsuran';
        if (!is_dir($uploadPath)) { mkdir($uploadPath, 0777, true); }

        $buktiPembayaranName = $buktiPembayaran->getRandomName();
        $buktiPembayaran->move($uploadPath, $buktiPembayaranName);

        $data = [
            'id_angsuran' => $this->request->getPost('id_angsuran'),
            'tanggal_bayar' => $this->request->getPost('tanggal_bayar'),
            'jumlah_bayar' => $this->request->getPost('jumlah_bayar'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'bukti_pembayaran' => $buktiPembayaranName,
            'denda' => $this->request->getPost('denda') ?: 0,
            'status_verifikasi' => 'pending', // Default ke pending untuk verifikasi manual
            // id_bendahara_verifikator akan diisi saat verifikasi (nullable)
        ];

        $this->pembayaranAngsuranModel->insert($data);
        return redirect()->to('/pembayaran-angsuran')->with('success', 'Data pembayaran angsuran berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $data['pembayaran_angsuran'] = $this->pembayaranAngsuranModel->findWithAccess($id);
        if (empty($data['pembayaran_angsuran'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pembayaran Angsuran dengan ID ' . $id . ' tidak ditemukan atau Anda tidak memiliki akses.');
        }
        return view('pembayaran_angsuran/form', $data);
    }

    public function update($id = null)
    {
        $pembayaranAngsuran = $this->pembayaranAngsuranModel->find($id);
        if (empty($pembayaranAngsuran)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Pembayaran Angsuran dengan ID ' . $id . ' tidak ditemukan.'); }

        $rules = [
            'id_angsuran' => 'required|integer',
            'tanggal_bayar' => 'required|valid_date',
            'jumlah_bayar' => 'required|numeric',
            'metode_pembayaran' => 'required|max_length[100]',
            'bukti_pembayaran' => 'max_size[bukti_pembayaran,2048]|ext_in[bukti_pembayaran,pdf,jpg,jpeg,png]',
            'denda' => 'permit_empty|numeric',
            'id_bendahara_verifikator' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) { return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); }

        $data = [
            'id_angsuran' => $this->request->getPost('id_angsuran'),
            'tanggal_bayar' => $this->request->getPost('tanggal_bayar'),
            'jumlah_bayar' => $this->request->getPost('jumlah_bayar'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'denda' => $this->request->getPost('denda'),
            'id_bendahara_verifikator' => $this->request->getPost('id_bendahara_verifikator'),
        ];

        /** @var UploadedFile $buktiPembayaran */
        $buktiPembayaran = $this->request->getFile('bukti_pembayaran');
        $uploadPath = WRITEPATH . 'uploads/pembayaran_angsuran';
        if (!is_dir($uploadPath)) { mkdir($uploadPath, 0777, true); }

        if ($buktiPembayaran && $buktiPembayaran->isValid() && !$buktiPembayaran->hasMoved()) {
            if (!empty($pembayaranAngsuran['bukti_pembayaran']) && file_exists($uploadPath . '/' . $pembayaranAngsuran['bukti_pembayaran'])) { unlink($uploadPath . '/' . $pembayaranAngsuran['bukti_pembayaran']); }
            $buktiPembayaranName = $buktiPembayaran->getRandomName();
            $buktiPembayaran->move($uploadPath, $buktiPembayaranName);
            $data['bukti_pembayaran'] = $buktiPembayaranName;
        }

        $this->pembayaranAngsuranModel->update($id, $data);
        return redirect()->to('/pembayaran-angsuran')->with('success', 'Data pembayaran angsuran berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $pembayaranAngsuran = $this->pembayaranAngsuranModel->find($id);
        if (empty($pembayaranAngsuran)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Pembayaran Angsuran dengan ID ' . $id . ' tidak ditemukan.'); }

        $uploadPath = WRITEPATH . 'uploads/pembayaran_angsuran';
        if (!empty($pembayaranAngsuran['bukti_pembayaran']) && file_exists($uploadPath . '/' . $pembayaranAngsuran['bukti_pembayaran'])) { unlink($uploadPath . '/' . $pembayaranAngsuran['bukti_pembayaran']); }

        $this->pembayaranAngsuranModel->delete($id);
        return redirect()->to('/pembayaran-angsuran')->with('success', 'Data pembayaran angsuran berhasil dihapus.');
    }

    public function show($id = null)
    {
        // Get pembayaran with complete joined data including verifikator info
        $pembayaranData = $this->pembayaranAngsuranModel->getFilteredPembayaranWithData(
            ['tbl_pembayaran_angsuran.id_pembayaran' => $id],
            'tbl_pembayaran_angsuran.*, tbl_angsuran.*, tbl_kredit.*, tbl_users.nama_lengkap as nama_anggota,
             tbl_anggota.no_anggota, tbl_anggota.nik, verifikator.nama_lengkap as nama_verifikator'
        );
        
        if (empty($pembayaranData)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pembayaran Angsuran dengan ID ' . $id . ' tidak ditemukan atau Anda tidak memiliki akses.');
        }
        
        $pembayaran = $pembayaranData[0]; // Get first result
        
        $data = [
            'pembayaran_angsuran' => $pembayaran,
            'angsuran' => $pembayaran, // Contains all angsuran fields
            'kredit' => $pembayaran     // Contains all kredit fields
        ];
        
        return view('pembayaran_angsuran/show', $data);
    }

    /**
     * Toggle pembayaran angsuran status (Aktif/Tidak Aktif)
     */
    public function toggleStatus($id = null)
    {
        $pembayaranAngsuran = $this->pembayaranAngsuranModel->find($id);
        if (empty($pembayaranAngsuran)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pembayaran angsuran tidak ditemukan.'
            ])->setStatusCode(404);
        }

        // Toggle status_aktif between Aktif and Tidak Aktif
        $currentStatus = $pembayaranAngsuran['status_aktif'] ?? 'Aktif';
        $newStatus = ($currentStatus === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $this->pembayaranAngsuranModel->update($id, ['status_aktif' => $newStatus]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status pembayaran angsuran berhasil diubah menjadi ' . $newStatus,
            'new_status' => $newStatus
        ]);
    }

    /**
     * Cetak bukti pembayaran angsuran
     * (Untuk Anggota)
     */
    public function cetakBukti($id = null)
    {
        $pembayaran = $this->pembayaranAngsuranModel
            ->select('tbl_pembayaran_angsuran.*, tbl_angsuran.id_kredit, tbl_angsuran.angsuran_ke,
                     tbl_angsuran.jumlah_angsuran, tbl_kredit.id_anggota, tbl_users.nama_lengkap,
                     tbl_anggota.nik, tbl_anggota.no_anggota')
            ->join('tbl_angsuran', 'tbl_angsuran.id_angsuran = tbl_pembayaran_angsuran.id_angsuran')
            ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit')
            ->join('tbl_anggota', 'tbl_anggota.id_anggota = tbl_kredit.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->find($id);

        if (empty($pembayaran)) {
            session()->setFlashdata('error', 'Data pembayaran tidak ditemukan');
            return redirect()->back();
        }

        $data = [
            'pembayaran' => $pembayaran,
            'title' => 'Bukti Pembayaran Angsuran',
            'tanggal_cetak' => date('d/m/Y H:i:s')
        ];

        return view('pembayaran_angsuran/bukti_pembayaran', $data);
    }

    /**
     * Download PDF bukti pembayaran
     * (Untuk Anggota)
     */
    public function downloadBukti($id = null)
    {
        $pembayaran = $this->pembayaranAngsuranModel
            ->select('tbl_pembayaran_angsuran.*, tbl_angsuran.id_kredit, tbl_angsuran.angsuran_ke,
                     tbl_angsuran.jumlah_angsuran, tbl_kredit.id_anggota, tbl_users.nama_lengkap,
                     tbl_anggota.nik, tbl_anggota.no_anggota')
            ->join('tbl_angsuran', 'tbl_angsuran.id_angsuran = tbl_pembayaran_angsuran.id_angsuran')
            ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit')
            ->join('tbl_anggota', 'tbl_anggota.id_anggota = tbl_kredit.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->find($id);

        if (empty($pembayaran)) {
            session()->setFlashdata('error', 'Data pembayaran tidak ditemukan');
            return redirect()->back();
        }

        $data = [
            'pembayaran' => $pembayaran,
            'title' => 'Bukti Pembayaran Angsuran',
            'tanggal_cetak' => date('d/m/Y H:i:s')
        ];

        // Load library PDF (if available)
        try {
            $dompdf = new \Dompdf\Dompdf();
            $html = view('pembayaran_angsuran/pdf_bukti', $data);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'Bukti_Pembayaran_' . $pembayaran['no_anggota'] . '_' . date('Y-m-d') . '.pdf';
            $dompdf->stream($filename, ['Attachment' => true]);
        } catch (\Exception $e) {
            // Fallback jika PDF library tidak tersedia
            log_message('error', 'PDF generation failed: ' . $e->getMessage());
            session()->setFlashdata('info', 'Cetak bukti pembayaran berhasil (PDF library tidak tersedia, menggunakan tampilan HTML)');
            return $this->cetakBukti($id);
        }
    }

    /**
     * Riwayat pembayaran untuk anggota
     * (Untuk Anggota)
     */
    public function riwayatPembayaran($id_anggota = null)
    {
        // Jika tidak ada ID anggota, ambil dari session untuk user Anggota
        if (!$id_anggota && isCurrentUserLevel('Anggota')) {
            $id_anggota = getCurrentUserAnggotaId();
        }

        if (!$id_anggota) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan');
            return redirect()->to('/dashboard-anggota');
        }

        // Use filtered method - akan otomatis difilter berdasarkan user yang login
        $additionalWhere = [];
        if ($id_anggota) {
            $additionalWhere['tbl_kredit.id_anggota'] = $id_anggota;
        }

        $pembayaranList = $this->pembayaranAngsuranModel->getFilteredPembayaranWithData(
            $additionalWhere,
            'tbl_pembayaran_angsuran.*, tbl_angsuran.angsuran_ke, tbl_angsuran.jumlah_angsuran, tbl_kredit.id_kredit'
        );

        // Order by tanggal_bayar DESC
        usort($pembayaranList, function($a, $b) {
            return strtotime($b['tanggal_bayar']) - strtotime($a['tanggal_bayar']);
        });

        $data = [
            'title' => 'Riwayat Pembayaran',
            'headerTitle' => 'Riwayat Pembayaran Angsuran',
            'pembayaran_list' => $pembayaranList
        ];

        return view('pembayaran_angsuran/riwayat_anggota', $data);
    }

    /**
     * View document with access control
     */
    public function viewDocument($filename)
    {
        try {
            log_message('debug', 'PembayaranAngsuranController::viewDocument called with filename: ' . $filename);

            // Cari pembayaran yang memiliki dokumen ini
            $pembayaran = $this->pembayaranAngsuranModel->where('bukti_pembayaran', $filename)->first();
            log_message('debug', 'PembayaranAngsuranController::viewDocument - pembayaran result: ' . ($pembayaran ? 'FOUND (ID: ' . $pembayaran['id_pembayaran'] . ')' : 'NOT FOUND'));

            if (!$pembayaran) {
                log_message('error', 'PembayaranAngsuranController::viewDocument - Dokumen tidak ditemukan di database: ' . $filename);
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Dokumen tidak ditemukan.');
            }

            log_message('debug', 'PembayaranAngsuranController::viewDocument - Pembayaran ditemukan: ' . json_encode($pembayaran));

            // Cek akses menggunakan sistem filtering yang sudah ada
            if (!canAccessData($pembayaran, 'id_angsuran')) {
                log_message('error', 'PembayaranAngsuranController::viewDocument - Akses ditolak untuk user ID: ' . session()->get('id_user') . ', Level: ' . session()->get('level'));
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke dokumen ini.');
            }

            log_message('debug', 'PembayaranAngsuranController::viewDocument - Akses disetujui');

            // Path ke file
            $filePath = WRITEPATH . 'uploads/pembayaran_angsuran/' . $filename;
            log_message('debug', 'PembayaranAngsuranController::viewDocument - File path: ' . $filePath);

            if (!file_exists($filePath)) {
                log_message('error', 'PembayaranAngsuranController::viewDocument - File tidak ditemukan di path: ' . $filePath);
                throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan.');
            }

            // Serve file dengan content type yang tepat
            $mime = mime_content_type($filePath);
            log_message('debug', 'PembayaranAngsuranController::viewDocument - Serving file with MIME: ' . $mime . ', Size: ' . filesize($filePath) . ' bytes');

            return $this->response
                ->setHeader('Content-Type', $mime)
                ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                ->setBody(file_get_contents($filePath));

        } catch (\CodeIgniter\Exceptions\PageNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            log_message('error', 'PembayaranAngsuranController::viewDocument - Unexpected error: ' . $e->getMessage());
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Terjadi kesalahan saat mengakses dokumen.');
        }
    }

    /**
     * Verifikasi pembayaran angsuran (untuk Bendahara/Admin)
     */
    public function verifikasiPembayaran($id = null)
    {
        $pembayaran = $this->pembayaranAngsuranModel->find($id);
        if (empty($pembayaran)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan'
            ])->setStatusCode(404);
        }

        try {
            // Update status verifikasi menjadi approved
            $this->pembayaranAngsuranModel->update($id, [
                'status_verifikasi' => 'approved',
                'id_bendahara_verifikator' => session()->get('id_user')
            ]);

            // Ambil data angsuran untuk cek apakah sudah lunas
            $angsuranModel = new \App\Models\AngsuranModel();
            $angsuran = $angsuranModel->find($pembayaran['id_angsuran']);
            
            if ($angsuran) {
                // Hitung total yang sudah dibayar dan approved untuk angsuran ini
                $totalDibayar = $this->pembayaranAngsuranModel
                    ->selectSum('jumlah_bayar')
                    ->where('id_angsuran', $pembayaran['id_angsuran'])
                    ->where('status_verifikasi', 'approved')
                    ->first();

                $jumlahTerbayar = $totalDibayar['jumlah_bayar'] ?? 0;
                
                // Jika angsuran sudah lunas, update status dan coba generate angsuran berikutnya
                if ($jumlahTerbayar >= $angsuran['jumlah_angsuran']) {
                    // Update status angsuran menjadi lunas
                    $angsuranModel->update($angsuran['id_angsuran'], [
                        'status_pembayaran' => 'Lunas'
                    ]);

                    // Auto-generate angsuran berikutnya
                    $angsuranController = new \App\Controllers\AngsuranController();
                    $angsuranController->initController($this->request, $this->response, \Config\Services::logger());
                    $response = $angsuranController->generateAngsuranBerikutnya($angsuran['id_kredit_ref']);
                    
                    $responseData = json_decode($response->getBody(), true);
                    if ($responseData && $responseData['success']) {
                        $message = 'Pembayaran berhasil diverifikasi. Angsuran sudah lunas dan angsuran berikutnya telah dibuat otomatis.';
                    } else {
                        $message = 'Pembayaran berhasil diverifikasi. Angsuran sudah lunas.';
                        // Log jika gagal generate angsuran berikutnya
                        if ($responseData && isset($responseData['message'])) {
                            log_message('info', 'Auto-generate angsuran gagal: ' . $responseData['message']);
                        }
                    }
                } else {
                    $sisaBayar = $angsuran['jumlah_angsuran'] - $jumlahTerbayar;
                    $message = "Pembayaran berhasil diverifikasi. Sisa pembayaran: Rp " . number_format($sisaBayar, 0, ',', '.');
                }
            } else {
                $message = 'Pembayaran berhasil diverifikasi.';
            }

            session()->setFlashdata('success', $message);
            return redirect()->back();

        } catch (\Exception $e) {
            log_message('error', 'Error verifying payment: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Tolak pembayaran angsuran (untuk Bendahara/Admin)
     */
    public function tolakPembayaran($id = null)
    {
        $pembayaran = $this->pembayaranAngsuranModel->find($id);
        if (empty($pembayaran)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan'
            ])->setStatusCode(404);
        }

        try {
            // Update status verifikasi menjadi rejected
            $alasan = $this->request->getPost('alasan') ?? 'Tidak ada alasan yang diberikan';
            
            $this->pembayaranAngsuranModel->update($id, [
                'status_verifikasi' => 'rejected',
                'id_bendahara_verifikator' => session()->get('id_user')
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pembayaran berhasil ditolak dengan alasan: ' . $alasan
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error rejecting payment: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Tampilkan detail pembayaran untuk verifikasi
     */
    public function verifikasiDetail($id = null)
    {
        $currentUserLevel = session()->get('level');
        
        if (!in_array($currentUserLevel, ['Bendahara', 'Ketua'])) {
            session()->setFlashdata('error', 'Akses ditolak. Hanya Bendahara atau Ketua yang dapat mengakses halaman ini.');
            return redirect()->to('/dashboard');
        }

        // Get pembayaran with complete data including verifikator info
        $pembayaranData = $this->pembayaranAngsuranModel->getFilteredPembayaranWithData(
            ['tbl_pembayaran_angsuran.id_pembayaran' => $id],
            'tbl_pembayaran_angsuran.*, tbl_angsuran.angsuran_ke, tbl_angsuran.jumlah_angsuran,
             tbl_kredit.id_kredit, tbl_users.nama_lengkap, tbl_anggota.no_anggota, tbl_anggota.nik,
             verifikator.nama_lengkap as nama_verifikator'
        );
        
        if (empty($pembayaranData)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pembayaran Angsuran dengan ID ' . $id . ' tidak ditemukan atau Anda tidak memiliki akses.');
        }
        
        $pembayaran = $pembayaranData[0];
        
        $data = [
            'title' => 'Verifikasi Pembayaran Angsuran',
            'headerTitle' => 'Verifikasi Pembayaran Angsuran',
            'pembayaran' => $pembayaran
        ];
        
        return view('pembayaran_angsuran/verifikasi_detail', $data);
    }

    /**
     * Approve pembayaran angsuran
     */
    public function verifikasiApprove($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/pembayaran-angsuran');
        }

        $currentUserLevel = session()->get('level');
        
        if (!in_array($currentUserLevel, ['Bendahara', 'Ketua'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak'
            ])->setStatusCode(403);
        }

        $pembayaran = $this->pembayaranAngsuranModel->find($id);
        if (empty($pembayaran)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan'
            ])->setStatusCode(404);
        }

        if ($pembayaran['status_verifikasi'] !== 'pending') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pembayaran sudah diverifikasi sebelumnya'
            ])->setStatusCode(400);
        }

        try {
            // Update status verifikasi menjadi approved
            $this->pembayaranAngsuranModel->update($id, [
                'status_verifikasi' => 'approved',
                'id_bendahara_verifikator' => session()->get('id_user')
            ]);

            // Update status pembayaran angsuran
            $angsuranModel = new \App\Models\AngsuranModel();
            $angsuran = $angsuranModel->find($pembayaran['id_angsuran']);
            
            if ($angsuran) {
                // Hitung total yang sudah dibayar dan approved untuk angsuran ini
                $totalDibayar = $this->pembayaranAngsuranModel
                    ->selectSum('jumlah_bayar')
                    ->where('id_angsuran', $pembayaran['id_angsuran'])
                    ->where('status_verifikasi', 'approved')
                    ->first();

                $jumlahTerbayar = $totalDibayar['jumlah_bayar'] ?? 0;
                
                // Update status angsuran berdasarkan total pembayaran
                if ($jumlahTerbayar >= $angsuran['jumlah_angsuran']) {
                    $angsuranModel->update($angsuran['id_angsuran'], [
                        'status_pembayaran' => 'Lunas'
                    ]);
                    $message = 'Pembayaran berhasil diverifikasi. Angsuran sudah lunas.';
                } else {
                    $angsuranModel->update($angsuran['id_angsuran'], [
                        'status_pembayaran' => 'Bayar Sebagian'
                    ]);
                    $sisaBayar = $angsuran['jumlah_angsuran'] - $jumlahTerbayar;
                    $message = "Pembayaran berhasil diverifikasi. Sisa pembayaran: Rp " . number_format($sisaBayar, 0, ',', '.');
                }
            } else {
                $message = 'Pembayaran berhasil diverifikasi.';
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error approving payment: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Reject pembayaran angsuran
     */
    public function verifikasiReject($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/pembayaran-angsuran');
        }

        $currentUserLevel = session()->get('level');
        
        if (!in_array($currentUserLevel, ['Bendahara', 'Ketua'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Akses ditolak'
            ])->setStatusCode(403);
        }

        $pembayaran = $this->pembayaranAngsuranModel->find($id);
        if (empty($pembayaran)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan'
            ])->setStatusCode(404);
        }

        if ($pembayaran['status_verifikasi'] !== 'pending') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pembayaran sudah diverifikasi sebelumnya'
            ])->setStatusCode(400);
        }

        $jsonInput = json_decode($this->request->getBody(), true);
        $alasan = $jsonInput['alasan'] ?? 'Tidak ada alasan yang diberikan';

        try {
            // Update status verifikasi menjadi rejected
            $this->pembayaranAngsuranModel->update($id, [
                'status_verifikasi' => 'rejected',
                'id_bendahara_verifikator' => session()->get('id_user'),
                'keterangan' => $alasan
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pembayaran berhasil ditolak dengan alasan: ' . $alasan
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error rejecting payment: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
