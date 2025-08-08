<?php


namespace App\Controllers;

use App\Models\KreditModel;
use App\Models\AnggotaModel;
use CodeIgniter\Controller;

class KreditController extends Controller
{
    protected $kreditModel;
    protected $anggotaModel;

    public function __construct()
    {
        $this->kreditModel = new KreditModel();
        $this->anggotaModel = new AnggotaModel();
        helper(['form', 'url', 'permission', 'notification']);
    }

    public function index()
    {
        $data['kredit'] = $this->kreditModel->findAll();
        
        // Get anggota data for each kredit to avoid AJAX calls
        $anggotaData = [];
        foreach ($data['kredit'] as $kredit) {
            if (!isset($anggotaData[$kredit['id_anggota']])) {
                $anggota = $this->anggotaModel->find($kredit['id_anggota']);
                if ($anggota) {
                    // Add kredit-specific data to anggota data
                    $anggotaData[$kredit['id_anggota']] = array_merge($anggota, [
                        'kredit_jumlah' => $kredit['jumlah_pengajuan'],
                        'kredit_jangka_waktu' => $kredit['jangka_waktu'],
                        'kredit_status' => $kredit['status_kredit'],
                    ]);
                }
            }
        }
        $data['anggotaData'] = $anggotaData;
        
        return view('kredit/index', $data);
    }

    public function new()
    {
        $session = session();
        $data = [];
        
        // If user is 'Anggota' level, auto-fill id_anggota from session
        if ($session->get('level') === 'Anggota') {
            $data['userAnggotaId'] = $session->get('id_anggota_ref');
        }
        
        return view('kredit/form', $data);
    }

    public function create()
    {
        $rules = [
            'id_anggota' => 'required|integer',
            'tanggal_pengajuan' => 'required|valid_date',
            'jumlah_pengajuan' => 'required|numeric',
            'jangka_waktu' => 'required|integer',
            'tujuan_kredit' => 'required|max_length[255]',
            'jenis_agunan' => 'required|max_length[100]',
            'nilai_taksiran_agunan' => 'permit_empty|numeric',
            'catatan_bendahara' => 'permit_empty|max_length[255]',
            'catatan_appraiser' => 'permit_empty|max_length[255]',
            'catatan_ketua' => 'permit_empty|max_length[255]',
            'status_kredit' => 'required|max_length[50]',
            'dokumen_agunan' => 'uploaded[dokumen_agunan]|max_size[dokumen_agunan,5120]|ext_in[dokumen_agunan,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // ALUR KOPERASI MITRA SEJAHTRA: Cek syarat keanggotaan minimal 6 bulan
        $anggota = $this->anggotaModel->find($this->request->getPost('id_anggota'));
        if (!$anggota) {
            return redirect()->back()->withInput()->with('errors', ['id_anggota' => 'Anggota tidak ditemukan.']);
        }

        // Cek minimal 6 bulan sejak tanggal pendaftaran
        $tanggalPendaftaran = new \DateTime($anggota['tanggal_pendaftaran']);
        $sekarang = new \DateTime();
        $selisih = $tanggalPendaftaran->diff($sekarang);
        $totalBulan = ($selisih->y * 12) + $selisih->m;

        if ($totalBulan < 6) {
            $sisaBulan = 6 - $totalBulan;
            return redirect()->back()->withInput()->with('errors', [
                'id_anggota' => "Syarat keanggotaan: Minimal 6 bulan sejak tanggal pendaftaran. Anda masih butuh {$sisaBulan} bulan lagi."
            ]);
        }

        // Cek status aktif keanggotaan
        if ($anggota['status_keanggotaan'] !== 'Aktif') {
            return redirect()->back()->withInput()->with('errors', [
                'id_anggota' => 'Status keanggotaan Anda tidak aktif. Hubungi bendahara untuk aktivasi.'
            ]);
        }

        $data = [
            'id_anggota' => $this->request->getPost('id_anggota'),
            'tanggal_pengajuan' => $this->request->getPost('tanggal_pengajuan'),
            'jumlah_pengajuan' => $this->request->getPost('jumlah_pengajuan'),
            'jangka_waktu' => $this->request->getPost('jangka_waktu'),
            'tujuan_kredit' => $this->request->getPost('tujuan_kredit'),
            'jenis_agunan' => $this->request->getPost('jenis_agunan'),
            'nilai_taksiran_agunan' => $this->request->getPost('nilai_taksiran_agunan'),
            'catatan_bendahara' => $this->request->getPost('catatan_bendahara'),
            'catatan_appraiser' => $this->request->getPost('catatan_appraiser'),
            'catatan_ketua' => $this->request->getPost('catatan_ketua'),
            'status_kredit' => 'Diajukan', // ALUR: Auto-set ke Diajukan untuk pengajuan baru
        ];

        // Handle dokumen agunan upload
        $uploadPath = WRITEPATH . 'uploads/dokumen_kredit/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file = $this->request->getFile('dokumen_agunan');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'dokumen_agunan_' . date('YmdHis') . '_' . $file->getRandomName();
            if ($file->move($uploadPath, $newName)) {
                $data['dokumen_agunan'] = 'uploads/dokumen_kredit/' . $newName;
            }
        }

        $this->kreditModel->insert($data);
        
        // ALUR KOPERASI MITRA SEJAHTRA: Log pengajuan baru untuk Bendahara
        log_message('info', 'ALUR KREDIT: Pengajuan baru dari Anggota ID ' . $data['id_anggota'] . ' menunggu verifikasi Bendahara');
        
        return redirect()->to('/kredit')->with('success', 'Pengajuan kredit berhasil disubmit. Menunggu verifikasi Bendahara.');
    }

    public function edit($id = null)
    {
        $data['kredit'] = $this->kreditModel->find($id);
        if (empty($data['kredit'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Kredit dengan ID ' . $id . ' tidak ditemukan.'); }
        return view('kredit/form', $data);
    }

    public function update($id = null)
    {
        $kredit = $this->kreditModel->find($id);
        if (empty($kredit)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Kredit dengan ID ' . $id . ' tidak ditemukan.'); }

        // Debug - log catatan appraiser yang diterima
        $catatanAppraiser = $this->request->getPost('catatan_appraiser');
        log_message('debug', 'KreditController Update - ID: ' . $id . ', Catatan Appraiser: ' . ($catatanAppraiser ?? 'NULL'));

        $rules = [
            'id_anggota' => 'required|integer',
            'tanggal_pengajuan' => 'required|valid_date',
            'jumlah_pengajuan' => 'required|numeric',
            'jangka_waktu' => 'required|integer',
            'tujuan_kredit' => 'required|max_length[255]',
            'jenis_agunan' => 'required|max_length[100]',
            'nilai_taksiran_agunan' => 'permit_empty|numeric',
            'catatan_bendahara' => 'permit_empty|max_length[255]',
            'catatan_appraiser' => 'permit_empty|max_length[255]',
            'catatan_ketua' => 'permit_empty|max_length[255]',
            'status_kredit' => 'required|max_length[50]',
            'dokumen_agunan' => 'permit_empty|max_size[dokumen_agunan,5120]|ext_in[dokumen_agunan,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) { return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); }

        $data = [
            'id_anggota' => $this->request->getPost('id_anggota'),
            'tanggal_pengajuan' => $this->request->getPost('tanggal_pengajuan'),
            'jumlah_pengajuan' => $this->request->getPost('jumlah_pengajuan'),
            'jangka_waktu' => $this->request->getPost('jangka_waktu'),
            'tujuan_kredit' => $this->request->getPost('tujuan_kredit'),
            'jenis_agunan' => $this->request->getPost('jenis_agunan'),
            'nilai_taksiran_agunan' => $this->request->getPost('nilai_taksiran_agunan'),
            'catatan_bendahara' => $this->request->getPost('catatan_bendahara'),
            'catatan_appraiser' => $catatanAppraiser,
            'catatan_ketua' => $this->request->getPost('catatan_ketua'),
            'status_kredit' => $this->request->getPost('status_kredit'),
        ];

        // Handle dokumen agunan update (optional)
        $uploadPath = WRITEPATH . 'uploads/dokumen_kredit/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file = $this->request->getFile('dokumen_agunan');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old file if exists
            if (!empty($kredit['dokumen_agunan']) && file_exists(WRITEPATH . $kredit['dokumen_agunan'])) {
                unlink(WRITEPATH . $kredit['dokumen_agunan']);
            }
            
            $newName = 'dokumen_agunan_' . date('YmdHis') . '_' . $file->getRandomName();
            if ($file->move($uploadPath, $newName)) {
                $data['dokumen_agunan'] = 'uploads/dokumen_kredit/' . $newName;
            }
        }

        log_message('debug', 'KreditController Update - Data yang akan disimpan: ' . json_encode($data));

        $result = $this->kreditModel->update($id, $data);
        log_message('debug', 'KreditController Update - Result: ' . ($result ? 'SUCCESS' : 'FAILED'));

        return redirect()->to('/kredit')->with('success', 'Data kredit berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $kredit = $this->kreditModel->find($id);
        if (empty($kredit)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Kredit dengan ID ' . $id . ' tidak ditemukan.'); }

        $this->kreditModel->delete($id);
        return redirect()->to('/kredit')->with('success', 'Data kredit berhasil dihapus.');
    }

    public function show($id = null)
    {
        $data['kredit'] = $this->kreditModel->find($id);
        if (empty($data['kredit'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Kredit dengan ID ' . $id . ' tidak ditemukan.'); }
        return view('kredit/show', $data);
    }

    public function toggleStatus($id = null)
    {
        $kredit = $this->kreditModel->find($id);
        if (empty($kredit)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data kredit tidak ditemukan.'
            ])->setStatusCode(404);
        }

        // Toggle status_aktif between Aktif and Tidak Aktif
        $currentStatus = $kredit['status_aktif'] ?? 'Aktif';
        $newStatus = ($currentStatus === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $this->kreditModel->update($id, ['status_aktif' => $newStatus]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status kredit berhasil diubah menjadi ' . $newStatus,
            'new_status' => $newStatus
        ]);
    }

    public function verifyAgunan()
    {
        // Only allow Appraiser role to access this method
        $currentUserLevel = session()->get('level');
        if ($currentUserLevel !== 'Appraiser') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hanya Appraiser yang dapat melakukan verifikasi agunan.'
            ])->setStatusCode(403);
        }

        // Get JSON data from request
        $jsonData = $this->request->getJSON();
        
        if (!$jsonData) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak valid.'
            ])->setStatusCode(400);
        }

        $idKredit = $jsonData->id_kredit;
        $catatanAppraiser = $jsonData->catatan_appraiser;

        if (!$idKredit) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID kredit tidak ditemukan.'
            ])->setStatusCode(400);
        }

        // Find the kredit record
        $kredit = $this->kreditModel->find($idKredit);
        if (!$kredit) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data kredit tidak ditemukan.'
            ])->setStatusCode(404);
        }

        // Update the catatan_appraiser field
        $updateData = [
            'catatan_appraiser' => $catatanAppraiser
        ];

        if ($this->kreditModel->update($idKredit, $updateData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Verifikasi agunan berhasil disimpan. Catatan penilai telah diperbarui.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan verifikasi agunan.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Detail Kredit Anggota untuk Ketua Koperasi
     */
    public function detailKreditAnggota()
    {
        $data = [
            'title' => 'Detail Kredit Anggota',
            'headerTitle' => 'Detail Kredit Anggota'
        ];

        // Menampilkan semua kredit anggota dengan detail lengkap
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota, tbl_anggota.alamat')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->orderBy('tbl_kredit.created_at', 'DESC')
            ->paginate(10);

        $data['kredit'] = $kredit;
        $data['pager'] = $this->kreditModel->pager;

        return view('kredit/detail_kredit_anggota', $data);
    }

    /**
     * Show Detail Anggota untuk Ketua Koperasi
     */
    public function showDetailAnggota($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota, tbl_anggota.alamat, tbl_anggota.no_hp, tbl_anggota.pekerjaan')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/detail-kredit-anggota');
        }

        $data = [
            'title' => 'Detail Kredit Anggota',
            'headerTitle' => 'Detail Kredit Anggota',
            'kredit' => $kredit
        ];

        return view('kredit/show_detail_anggota', $data);
    }

    /**
     * ALUR KOPERASI MITRA SEJAHTRA: Method khusus untuk Bendahara verifikasi dokumen
     * Workflow: Anggota → Bendahara → Appraiser → Anggota
     */
    public function verifikasiBendahara($id = null)
    {
        // Cek akses role Bendahara
        if (!hasPermission('bendahara')) {
            return redirect()->to('/')->with('error', 'Akses ditolak. Hanya Bendahara yang dapat memverifikasi.');
        }

        $kredit = $this->kreditModel->find($id);
        if (!$kredit) {
            return redirect()->to('/kredit')->with('error', 'Data kredit tidak ditemukan.');
        }

        // Cek status harus "Diajukan"
        if ($kredit['status_kredit'] !== 'Diajukan') {
            return redirect()->to('/kredit')->with('error', 'Status kredit tidak dapat diverifikasi. Status saat ini: ' . $kredit['status_kredit']);
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'catatan_bendahara' => 'required|max_length[255]',
                'status_verifikasi' => 'required|in_list[Diterima,Ditolak]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $statusVerifikasi = trim($this->request->getPost('status_verifikasi'));
            $catatanBendahara = $this->request->getPost('catatan_bendahara');

            // Debug logging
            log_message('debug', 'VERIFIKASI BENDAHARA - ID: ' . $id . ', Status: [' . $statusVerifikasi . '], Catatan: ' . $catatanBendahara);

            $data = [
                'catatan_bendahara' => $catatanBendahara,
                'tanggal_verifikasi_bendahara' => date('Y-m-d H:i:s')
            ];

            // Fix logic dengan debugging yang lebih jelas
            if ($statusVerifikasi === 'Diterima') {
                $data['status_kredit'] = 'Verifikasi Bendahara';
                log_message('info', 'ALUR KREDIT: Bendahara MENERIMA pengajuan ID ' . $id . ', diteruskan ke Appraiser. Status akan menjadi: Verifikasi Bendahara');
                $successMsg = 'Pengajuan kredit berhasil diverifikasi dan DITERIMA. Diteruskan ke Appraiser untuk penilaian agunan.';
            } elseif ($statusVerifikasi === 'Ditolak') {
                $data['status_kredit'] = 'Ditolak Bendahara';
                log_message('info', 'ALUR KREDIT: Bendahara MENOLAK pengajuan ID ' . $id . '. Status akan menjadi: Ditolak Bendahara');
                $successMsg = 'Pengajuan kredit ditolak. Anggota akan mendapat notifikasi.';
            } else {
                log_message('error', 'VERIFIKASI BENDAHARA ERROR - Status tidak dikenali: [' . $statusVerifikasi . ']');
                return redirect()->back()->withInput()->with('errors', ['status_verifikasi' => 'Status verifikasi tidak valid.']);
            }

            log_message('debug', 'VERIFIKASI BENDAHARA - Data yang akan disimpan: ' . json_encode($data));
            
            $result = $this->kreditModel->update($id, $data);
            log_message('debug', 'VERIFIKASI BENDAHARA - Update result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            
            return redirect()->to('/kredit/pengajuan-untuk-role')->with('success', $successMsg);
        }

        // Load anggota data with user info
        $anggota = $this->anggotaModel
            ->select('tbl_anggota.*, tbl_users.nama_lengkap')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota', 'left')
            ->find($kredit['id_anggota']);
        
        // Load view verifikasi bendahara
        $data = [
            'title' => 'Verifikasi Bendahara - Pengajuan Kredit',
            'kredit' => $kredit,
            'anggota' => $anggota
        ];

        return view('kredit/verifikasi_bendahara', $data);
    }

    /**
     * ALUR KOPERASI MITRA SEJAHTRA: Method khusus untuk Appraiser penilaian agunan
     * Workflow: Anggota → Bendahara → Appraiser → Anggota
     */
    public function penilaianAppraiser($id = null)
    {
        // Cek akses role Appraiser
        if (!hasPermission('appraiser')) {
            return redirect()->to('/')->with('error', 'Akses ditolak. Hanya Appraiser yang dapat menilai agunan.');
        }

        $kredit = $this->kreditModel->find($id);
        if (!$kredit) {
            return redirect()->to('/kredit')->with('error', 'Data kredit tidak ditemukan.');
        }

        // Cek status harus "Verifikasi Bendahara"
        if ($kredit['status_kredit'] !== 'Verifikasi Bendahara') {
            return redirect()->to('/kredit')->with('error', 'Status kredit belum siap untuk dinilai. Status saat ini: ' . $kredit['status_kredit']);
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nilai_taksiran_agunan' => 'required|numeric|greater_than[0]',
                'catatan_appraiser' => 'required|max_length[255]',
                'rekomendasi_appraiser' => 'required|in_list[Disetujui,Ditolak]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $rekomendasiAppraiser = $this->request->getPost('rekomendasi_appraiser');
            $nilaiTaksiranAgunan = $this->request->getPost('nilai_taksiran_agunan');
            $catatanAppraiser = $this->request->getPost('catatan_appraiser');

            $data = [
                'nilai_taksiran_agunan' => $nilaiTaksiranAgunan,
                'catatan_appraiser' => $catatanAppraiser,
                'tanggal_penilaian_appraiser' => date('Y-m-d H:i:s')
            ];

            if ($rekomendasiAppraiser === 'Disetujui') {
                $data['status_kredit'] = 'Hasil Penilaian Appraiser';
                log_message('info', 'ALUR KREDIT: Appraiser menyetujui pengajuan ID ' . $id . ', dikembalikan ke Bendahara untuk diteruskan');
                $successMsg = 'Penilaian agunan selesai. Hasil dikembalikan ke Bendahara untuk diteruskan ke tahap persetujuan.';
            } else {
                $data['status_kredit'] = 'Ditolak Appraiser';
                log_message('info', 'ALUR KREDIT: Appraiser menolak pengajuan ID ' . $id);
                $successMsg = 'Pengajuan kredit ditolak oleh Appraiser. Anggota akan mendapat notifikasi.';
            }

            $this->kreditModel->update($id, $data);
            return redirect()->to('/kredit/pengajuan-untuk-role')->with('success', $successMsg);
        }

        // Load anggota data with user info
        $anggota = $this->anggotaModel
            ->select('tbl_anggota.*, tbl_users.nama_lengkap')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota', 'left')
            ->find($kredit['id_anggota']);
        
        // Load view penilaian appraiser
        $data = [
            'title' => 'Penilaian Appraiser - Agunan Kredit',
            'kredit' => $kredit,
            'anggota' => $anggota
        ];

        return view('kredit/penilaian_appraiser', $data);
    }

    /**
     * ALUR KOPERASI MITRA SEJAHTRA: Method untuk mendapat list pengajuan sesuai role
     */
    public function pengajuanUntukRole()
    {
        $currentRole = session('level');
        
        switch ($currentRole) {
            case 'Bendahara':
                // Bendahara melihat pengajuan dengan status "Diajukan" DAN "Hasil Penilaian Appraiser"
                $pengajuan1 = $this->kreditModel->where('status_kredit', 'Diajukan')->findAll();
                $pengajuan2 = $this->kreditModel->where('status_kredit', 'Hasil Penilaian Appraiser')->findAll();
                $pengajuan = array_merge($pengajuan1, $pengajuan2);
                $title = 'Tugas Bendahara - Verifikasi & Teruskan Hasil Appraiser';
                break;
                
            case 'Appraiser':
                // Appraiser melihat pengajuan dengan status "Verifikasi Bendahara"
                $pengajuan = $this->kreditModel->where('status_kredit', 'Verifikasi Bendahara')->findAll();
                $title = 'Pengajuan Kredit Menunggu Penilaian Appraiser';
                break;
                
            case 'Ketua':
                // Ketua melihat pengajuan dengan status "Siap Persetujuan"
                $pengajuan = $this->kreditModel->where('status_kredit', 'Siap Persetujuan')->findAll();
                $title = 'Pengajuan Kredit Menunggu Persetujuan Final';
                break;
                
            default:
                // Anggota melihat pengajuan mereka sendiri
                $userId = session('user_id');
                $anggota = $this->anggotaModel->where('id_user', $userId)->first();
                if ($anggota) {
                    $pengajuan = $this->kreditModel->where('id_anggota', $anggota['id'])->findAll();
                } else {
                    $pengajuan = [];
                }
                $title = 'Pengajuan Kredit Saya';
        }

        // Load nama anggota untuk setiap pengajuan
        foreach ($pengajuan as &$item) {
            $anggota = $this->anggotaModel
                ->select('tbl_anggota.*, tbl_users.nama_lengkap')
                ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota', 'left')
                ->find($item['id_anggota']);
            $item['nama_anggota'] = $anggota['nama_lengkap'] ?? 'N/A';
        }

        $data = [
            'title' => $title,
            'pengajuan' => $pengajuan,
            'currentRole' => $currentRole
        ];

        return view('kredit/pengajuan_per_role', $data);
    }

    /**
     * ALUR KOPERASI MITRA SEJAHTRA: Method untuk persetujuan final oleh Ketua
     */
    public function persetujuanFinal($id = null)
    {
        // Cek akses role Ketua
        if (!hasPermission('ketua')) {
            return redirect()->to('/')->with('error', 'Akses ditolak. Hanya Ketua Koperasi yang dapat memberikan persetujuan final.');
        }

        $kredit = $this->kreditModel->find($id);
        if (!$kredit) {
            return redirect()->to('/kredit')->with('error', 'Data kredit tidak ditemukan.');
        }

        // Cek status harus "Siap Persetujuan"
        if ($kredit['status_kredit'] !== 'Siap Persetujuan') {
            return redirect()->to('/kredit')->with('error', 'Status kredit belum siap untuk persetujuan final. Status saat ini: ' . $kredit['status_kredit']);
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'catatan_ketua' => 'required|max_length[255]',
                'keputusan_final' => 'required|in_list[Disetujui,Ditolak]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $keputusanFinal = $this->request->getPost('keputusan_final');
            $catatanKetua = $this->request->getPost('catatan_ketua');

            $data = [
                'catatan_ketua' => $catatanKetua,
                'tanggal_keputusan_ketua' => date('Y-m-d H:i:s')
            ];

            if ($keputusanFinal === 'Disetujui') {
                $data['status_kredit'] = 'Disetujui';
                log_message('info', 'ALUR KREDIT: Ketua menyetujui pengajuan ID ' . $id . ', kredit siap dicairkan');
                $successMsg = 'Pengajuan kredit disetujui. Anggota dapat melakukan pencairan kredit.';
            } else {
                $data['status_kredit'] = 'Ditolak Final';
                log_message('info', 'ALUR KREDIT: Ketua menolak pengajuan ID ' . $id);
                $successMsg = 'Pengajuan kredit ditolak oleh Ketua Koperasi. Anggota akan mendapat notifikasi.';
            }

            $this->kreditModel->update($id, $data);
            return redirect()->to('/kredit/pengajuan-untuk-role')->with('success', $successMsg);
        }

        // Load anggota data with user info
        $anggota = $this->anggotaModel
            ->select('tbl_anggota.*, tbl_users.nama_lengkap')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota', 'left')
            ->find($kredit['id_anggota']);
        
        // Load view persetujuan final ketua
        $data = [
            'title' => 'Persetujuan Final - Ketua Koperasi',
            'kredit' => $kredit,
            'anggota' => $anggota
        ];

        return view('kredit/persetujuan_final', $data);
    }

    /**
     * ALUR KOPERASI MITRA SEJAHTRA: Method untuk Bendahara meneruskan hasil Appraiser
     * Step: Appraiser selesai → Bendahara → Ketua
     */
    public function teruskanHasilAppraiser($id = null)
    {
        // Cek akses role Bendahara
        if (!hasPermission('bendahara')) {
            return redirect()->to('/')->with('error', 'Akses ditolak. Hanya Bendahara yang dapat meneruskan hasil.');
        }

        $kredit = $this->kreditModel->find($id);
        if (!$kredit) {
            return redirect()->to('/kredit')->with('error', 'Data kredit tidak ditemukan.');
        }

        // Cek status harus "Hasil Penilaian Appraiser"
        if ($kredit['status_kredit'] !== 'Hasil Penilaian Appraiser') {
            return redirect()->to('/kredit')->with('error', 'Status kredit tidak dapat diteruskan. Status saat ini: ' . $kredit['status_kredit']);
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'catatan_bendahara_final' => 'required|max_length[255]',
                'keputusan_teruskan' => 'required|in_list[Teruskan,Kembalikan]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $keputusanTeruskan = $this->request->getPost('keputusan_teruskan');
            $catatanBendaharaFinal = $this->request->getPost('catatan_bendahara_final');

            $data = [
                'catatan_bendahara' => ($kredit['catatan_bendahara'] ?? '') . ' | FINAL: ' . $catatanBendaharaFinal,
                'tanggal_teruskan_bendahara' => date('Y-m-d H:i:s')
            ];

            if ($keputusanTeruskan === 'Teruskan') {
                $data['status_kredit'] = 'Siap Persetujuan';
                log_message('info', 'ALUR KREDIT: Bendahara meneruskan hasil Appraiser ID ' . $id . ' ke Ketua untuk persetujuan final');
                $successMsg = 'Hasil penilaian Appraiser berhasil diteruskan ke Ketua untuk persetujuan final.';
            } else {
                $data['status_kredit'] = 'Verifikasi Bendahara'; // Kembalikan ke Appraiser
                log_message('info', 'ALUR KREDIT: Bendahara mengembalikan pengajuan ID ' . $id . ' ke Appraiser');
                $successMsg = 'Pengajuan dikembalikan ke Appraiser untuk penilaian ulang.';
            }

            $this->kreditModel->update($id, $data);
            return redirect()->to('/kredit/pengajuan-untuk-role')->with('success', $successMsg);
        }

        // Load anggota data with user info
        $anggota = $this->anggotaModel
            ->select('tbl_anggota.*, tbl_users.nama_lengkap')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota', 'left')
            ->find($kredit['id_anggota']);
        
        // Load view untuk meneruskan hasil appraiser
        $data = [
            'title' => 'Teruskan Hasil Appraiser - Bendahara',
            'kredit' => $kredit,
            'anggota' => $anggota
        ];

        return view('kredit/teruskan_hasil_appraiser', $data);
    }

}
