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
        helper(['form', 'url', 'permission', 'notification', 'data_filter']);
    }

    /**
     * Menampilkan daftar semua pengajuan kredit
     *
     * Method ini mengambil semua data kredit dengan informasi anggota terkait
     * menggunakan sistem filtering berdasarkan akses pengguna (data scope)
     *
     * @return string View daftar kredit
     */
    public function index()
    {
        // Use filtered method with user-based access control
        $kredit = $this->kreditModel->getFilteredKreditsWithData();

        // Sort by created_at DESC (data terbaru di atas)
        usort($kredit, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        $data['kredit'] = $kredit;

        return view('kredit/index', $data);
    }

    /**
     * Menampilkan form pengajuan kredit baru
     *
     * Untuk role Anggota, form akan otomatis terisi dengan ID anggota dari session.
     * Untuk role lain, dapat memilih anggota yang akan mengajukan kredit.
     *
     * @return string View form kredit baru
     */
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

    /**
     * Memproses pengajuan kredit baru
     *
     * Workflow yang dilakukan:
     * 1. Validasi data input termasuk upload dokumen agunan
     * 2. Cek syarat keanggotaan minimal 6 bulan (business rule koperasi)
     * 3. Cek status keanggotaan harus aktif
     * 4. Simpan data kredit dengan status awal "Diajukan"
     * 5. Upload dokumen agunan ke storage
     *
     * @return RedirectResponse Redirect ke daftar kredit dengan pesan sukses/error
     */
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

        // 🔍 CHECK 6 MONTHS MEMBERSHIP RULE - Using new field tanggal_masuk_anggota
        $eligibility = $this->anggotaModel->checkCreditEligibility($anggota['id_anggota']);
        
        if (!$eligibility['eligible']) {
            return redirect()->back()->withInput()->with('errors', [
                'id_anggota' =>
                    '❌ TIDAK MEMENUHI SYARAT KEANGGOTAAN' . "\n" .
                    '📅 Tanggal Masuk: ' . date('d/m/Y', strtotime($eligibility['tanggal_masuk'])) . "\n" .
                    '⏳ Sudah anggota: ' . $eligibility['months_completed'] . ' bulan' . "\n" .
                    '⚠️ Masih perlu menunggu: ' . $eligibility['months_remaining'] . ' bulan lagi' . "\n" .
                    'Syarat: Minimal 6 bulan menjadi anggota untuk dapat mengajukan kredit'
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
                $data['dokumen_agunan'] = 'dokumen_kredit/' . $newName;
            }
        }

        $this->kreditModel->insert($data);
        
        // ALUR KOPERASI MITRA SEJAHTRA: Log pengajuan baru untuk Bendahara
        log_message('info', 'ALUR KREDIT: Pengajuan baru dari Anggota ID ' . $data['id_anggota'] . ' menunggu verifikasi Bendahara');
        
        return redirect()->to('/kredit')->with('success', 'Pengajuan kredit berhasil disubmit. Menunggu verifikasi Bendahara.');
    }

    /**
     * Menampilkan form edit data kredit
     *
     * Method ini menggunakan sistem akses kontrol untuk memastikan pengguna
     * hanya dapat mengakses data kredit sesuai dengan hak aksesnya
     *
     * @param int $id ID kredit yang akan diedit
     * @return string View form edit kredit
     * @throws PageNotFoundException Jika kredit tidak ditemukan atau tidak ada akses
     */
    public function edit($id = null)
    {
        // Use access-controlled method
        $data['kredit'] = $this->kreditModel->findWithAccess($id);
        if (empty($data['kredit'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kredit dengan ID ' . $id . ' tidak ditemukan atau Anda tidak memiliki akses.');
        }
        return view('kredit/form', $data);
    }

    /**
     * Memproses update data kredit
     *
     * Method ini memperbarui data kredit yang sudah ada, termasuk:
     * - Data umum kredit (jumlah, jangka waktu, dll)
     * - Catatan dari berbagai role (bendahara, appraiser, ketua)
     * - Dokumen agunan (jika ada file baru)
     * - Status kredit
     *
     * @param int $id ID kredit yang akan diupdate
     * @return RedirectResponse Redirect dengan pesan sukses/error
     * @throws PageNotFoundException Jika kredit tidak ditemukan atau tidak ada akses
     */
    public function update($id = null)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id);
        if (empty($kredit)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kredit dengan ID ' . $id . ' tidak ditemukan atau Anda tidak memiliki akses.');
        }

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
                $data['dokumen_agunan'] = 'dokumen_kredit/' . $newName;
            }
        }

        log_message('debug', 'KreditController Update - Data yang akan disimpan: ' . json_encode($data));

        $result = $this->kreditModel->update($id, $data);
        log_message('debug', 'KreditController Update - Result: ' . ($result ? 'SUCCESS' : 'FAILED'));

        return redirect()->to('/kredit')->with('success', 'Data kredit berhasil diperbarui.');
    }

    /**
     * Menghapus data kredit beserta data terkait
     *
     * Method ini menghapus secara cascade:
     * 1. Data pembayaran angsuran (jika ada)
     * 2. Data angsuran (jika ada)
     * 3. Data pencairan (jika ada)
     * 4. File dokumen agunan
     * 5. Data kredit utama
     *
     * @param int $id ID kredit yang akan dihapus
     * @return RedirectResponse Redirect dengan pesan sukses/error
     * @throws PageNotFoundException Jika kredit tidak ditemukan atau tidak ada akses
     */
    public function delete($id = null)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id);
        if (empty($kredit)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kredit dengan ID ' . $id . ' tidak ditemukan atau Anda tidak memiliki akses.');
        }

        try {
            // Check for related data that needs to be handled
            $db = \Config\Database::connect();
            
            // Check if there's pencairan (disbursement) data
            $pencairanModel = new \App\Models\PencairanModel();
            $pencairan = $pencairanModel->where('id_kredit', $id)->first();
            
            if ($pencairan) {
                // If there's pencairan, check for angsuran and pembayaran
                $angsuranModel = new \App\Models\AngsuranModel();
                $pembayaranModel = new \App\Models\PembayaranAngsuranModel();
                
                // Get all angsuran for this kredit
                $angsuranList = $angsuranModel->where('id_kredit_ref', $id)->findAll();
                
                if (!empty($angsuranList)) {
                    // Delete all pembayaran angsuran first
                    foreach ($angsuranList as $angsuran) {
                        $pembayaranModel->where('id_angsuran', $angsuran['id_angsuran'])->delete();
                    }
                    
                    // Delete all angsuran
                    $angsuranModel->where('id_kredit_ref', $id)->delete();
                }
                
                // Delete pencairan data
                $pencairanModel->delete($pencairan['id_pencairan']);
            }
            
            // Delete dokumen file if exists
            if (!empty($kredit['dokumen_agunan'])) {
                $filePath = WRITEPATH . 'uploads/' . $kredit['dokumen_agunan'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            // Finally delete the kredit record
            $this->kreditModel->delete($id);
            
            return redirect()->to('/kredit')->with('success', 'Data kredit dan semua data terkait berhasil dihapus.');
            
        } catch (\Exception $e) {
            log_message('error', 'Error deleting kredit ID ' . $id . ': ' . $e->getMessage());
            return redirect()->to('/kredit')->with('error', 'Gagal menghapus data kredit: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail data kredit
     *
     * Method ini menampilkan semua informasi lengkap tentang pengajuan kredit
     * termasuk data anggota, status workflow, dan dokumen terkait
     *
     * @param int $id ID kredit yang akan ditampilkan
     * @return string View detail kredit
     * @throws PageNotFoundException Jika kredit tidak ditemukan atau tidak ada akses
     */
    public function show($id = null)
    {
        $data['kredit'] = $this->kreditModel->findWithAccess($id);
        if (empty($data['kredit'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kredit dengan ID ' . $id . ' tidak ditemukan atau Anda tidak memiliki akses.');
        }
        return view('kredit/show', $data);
    }

    /**
     * Mengubah status aktif/non-aktif kredit via AJAX
     *
     * Method ini memungkinkan admin untuk mengaktifkan atau menonaktifkan
     * kredit secara cepat melalui interface dashboard
     *
     * @param int $id ID kredit yang akan diubah statusnya
     * @return ResponseInterface JSON response dengan status operasi
     */
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

    /**
     * Verifikasi agunan oleh Appraiser via AJAX
     *
     * Method ini memungkinkan Appraiser untuk menyimpan catatan penilaian agunan
     * secara langsung tanpa harus melalui form terpisah
     *
     * @return ResponseInterface JSON response dengan status operasi
     */
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

        // Use filtered method with pagination
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota, tbl_anggota.alamat';
        $data['kredit'] = $this->kreditModel->getFilteredKreditsWithData([], $select);
        
        // For pagination, we'll need to modify this if needed
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
    /**
     * Verifikasi dokumen kredit oleh Bendahara (Step 1 Workflow)
     *
     * Workflow Koperasi Mitra Sejahtera:
     * Anggota → BENDAHARA → Appraiser → Ketua → Bendahara (pencairan)
     *
     * Fungsi ini memproses:
     * 1. Verifikasi kelengkapan dokumen pengajuan
     * 2. Input catatan bendahara
     * 3. Keputusan: Diterima (lanjut ke Appraiser) atau Ditolak
     *
     * @param int $id ID kredit yang akan diverifikasi
     * @return string|RedirectResponse View verifikasi atau redirect setelah submit
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
                'keputusan_bendahara' => 'required|in_list[Diterima,Ditolak]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $keputusanBendahara = trim($this->request->getPost('keputusan_bendahara'));
            $catatanBendahara = $this->request->getPost('catatan_bendahara');

            // Debug logging
            log_message('debug', 'VERIFIKASI BENDAHARA - ID: ' . $id . ', Keputusan: [' . $keputusanBendahara . '], Catatan: ' . $catatanBendahara);

            $data = [
                'catatan_bendahara' => $catatanBendahara,
                'tanggal_verifikasi_bendahara' => date('Y-m-d H:i:s')
            ];

            // Fix logic dengan debugging yang lebih jelas
            if ($keputusanBendahara === 'Diterima') {
                $data['status_kredit'] = 'Verifikasi Bendahara';
                log_message('info', 'ALUR KREDIT: Bendahara MENERIMA pengajuan ID ' . $id . ', diteruskan ke Appraiser. Status akan menjadi: Verifikasi Bendahara');
                $successMsg = 'Pengajuan kredit berhasil diverifikasi dan DITERIMA. Diteruskan ke Appraiser untuk penilaian agunan.';
            } elseif ($keputusanBendahara === 'Ditolak') {
                $data['status_kredit'] = 'Ditolak Bendahara';
                log_message('info', 'ALUR KREDIT: Bendahara MENOLAK pengajuan ID ' . $id . '. Status akan menjadi: Ditolak Bendahara');
                $successMsg = 'Pengajuan kredit ditolak. Anggota akan mendapat notifikasi.';
            } else {
                log_message('error', 'VERIFIKASI BENDAHARA ERROR - Keputusan tidak dikenali: [' . $keputusanBendahara . ']');
                return redirect()->back()->withInput()->with('errors', ['keputusan_bendahara' => 'Keputusan bendahara tidak valid.']);
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
    /**
     * Penilaian agunan oleh Appraiser (Step 2 Workflow)
     *
     * Workflow Koperasi Mitra Sejahtera:
     * Anggota → Bendahara → APPRAISER → Ketua → Bendahara (pencairan)
     *
     * Fungsi ini memproses:
     * 1. Penilaian nilai taksiran agunan
     * 2. Input catatan appraiser tentang kondisi agunan
     * 3. Rekomendasi: Disetujui (lanjut ke Ketua) atau Ditolak
     *
     * @param int $id ID kredit yang akan dinilai
     * @return string|RedirectResponse View penilaian atau redirect setelah submit
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
                // ALUR FIX: Langsung teruskan ke Ketua setelah Appraiser setuju
                $data['status_kredit'] = 'Siap Persetujuan';
                log_message('info', 'ALUR KREDIT: Appraiser menyetujui pengajuan ID ' . $id . ', langsung diteruskan ke Ketua untuk persetujuan final');
                $successMsg = 'Penilaian agunan DISETUJUI. Pengajuan langsung diteruskan ke Ketua untuk persetujuan final.';
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
    /**
     * Menampilkan daftar pengajuan sesuai role pengguna
     *
     * Method ini menyaring pengajuan kredit berdasarkan workflow dan role:
     * - Bendahara: Pengajuan baru ("Diajukan") dan siap dicairkan ("Disetujui Ketua")
     * - Appraiser: Pengajuan yang sudah diverifikasi Bendahara ("Verifikasi Bendahara")
     * - Ketua: Pengajuan siap persetujuan final ("Siap Persetujuan")
     * - Anggota: Pengajuan milik sendiri (semua status)
     *
     * @return string View daftar pengajuan per role
     */
    public function pengajuanUntukRole()
    {
        $currentRole = session('level');
        
        // Custom select with alias untuk nama_anggota
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap as nama_anggota, tbl_anggota.no_anggota, tbl_anggota.alamat';
        
        switch ($currentRole) {
            case 'Bendahara':
                // Bendahara melihat pengajuan dengan status "Diajukan" DAN "Disetujui Ketua" (untuk pencairan)
                $pengajuan = $this->kreditModel->getFilteredKreditsWithData([
                    'tbl_kredit.status_kredit' => ['Diajukan', 'Disetujui Ketua']
                ], $select);
                $title = 'Tugas Bendahara - Verifikasi Awal & Proses Pencairan';
                break;
                
            case 'Appraiser':
                // Appraiser melihat pengajuan dengan status "Verifikasi Bendahara"
                $pengajuan = $this->kreditModel->getFilteredKreditsWithData([
                    'tbl_kredit.status_kredit' => 'Verifikasi Bendahara'
                ], $select);
                $title = 'Pengajuan Kredit Menunggu Penilaian Appraiser';
                break;
                
            case 'Ketua':
                // Ketua melihat pengajuan dengan status "Siap Persetujuan"
                $pengajuan = $this->kreditModel->getFilteredKreditsWithData([
                    'tbl_kredit.status_kredit' => 'Siap Persetujuan'
                ], $select);
                $title = 'Pengajuan Kredit Menunggu Persetujuan Final';
                break;
                
            default:
                // Anggota melihat pengajuan mereka sendiri (akan difilter otomatis)
                $pengajuan = $this->kreditModel->getFilteredKreditsWithData([], $select);
                $title = 'Pengajuan Kredit Saya';
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
    /**
     * Persetujuan final oleh Ketua Koperasi (Step 3 Workflow)
     *
     * Workflow Koperasi Mitra Sejahtera:
     * Anggota → Bendahara → Appraiser → KETUA → Bendahara (pencairan)
     *
     * Fungsi ini memproses:
     * 1. Review hasil verifikasi Bendahara dan penilaian Appraiser
     * 2. Input catatan ketua sebagai pertimbangan keputusan
     * 3. Keputusan final: Disetujui (lanjut pencairan) atau Ditolak
     *
     * @param int $id ID kredit yang akan diputuskan
     * @return string|RedirectResponse View persetujuan atau redirect setelah submit
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
                $data['status_kredit'] = 'Disetujui Ketua';
                $data['status_pencairan'] = 'Menunggu';
                $data['tanggal_persetujuan_ketua'] = date('Y-m-d H:i:s');
                log_message('info', 'ALUR KREDIT: Ketua menyetujui pengajuan ID ' . $id . ', dikembalikan ke Bendahara untuk pencairan');
                $successMsg = 'Pengajuan kredit disetujui. Dikembalikan ke Bendahara untuk proses pencairan.';
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

    /**
     * ALUR KOPERASI MITRA SEJAHTRA: Method untuk Bendahara proses pencairan setelah persetujuan Ketua
     * Step: Ketua setuju → Bendahara → Pencairan
     */
    /**
     * Proses pencairan dana oleh Bendahara (Step 4 Final Workflow)
     *
     * Workflow Koperasi Mitra Sejahtera:
     * Anggota → Bendahara → Appraiser → Ketua → BENDAHARA (pencairan)
     *
     * Fungsi ini memproses:
     * 1. Review persetujuan final dari Ketua
     * 2. Input catatan pencairan bendahara
     * 3. Keputusan pencairan: Siap Dicairkan atau Perlu Review
     * 4. Auto-create record pencairan dan generate angsuran pertama
     *
     * @param int $id ID kredit yang akan dicairkan
     * @return string|RedirectResponse View pencairan atau redirect setelah submit
     */
    public function prosesPencairan($id = null)
    {
        // Cek akses role Bendahara
        if (!hasPermission('bendahara')) {
            return redirect()->to('/')->with('error', 'Akses ditolak. Hanya Bendahara yang dapat memproses pencairan.');
        }

        $kredit = $this->kreditModel->find($id);
        if (!$kredit) {
            return redirect()->to('/kredit')->with('error', 'Data kredit tidak ditemukan.');
        }

        // Cek status harus "Disetujui Ketua"
        if ($kredit['status_kredit'] !== 'Disetujui Ketua') {
            return redirect()->to('/kredit')->with('error', 'Status kredit tidak dapat diproses. Status saat ini: ' . $kredit['status_kredit']);
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'catatan_pencairan_bendahara' => 'required|max_length[255]',
                'keputusan_pencairan' => 'required|in_list[Siap Dicairkan,Perlu Review]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $keputusanPencairan = $this->request->getPost('keputusan_pencairan');
            $catatanPencairan = $this->request->getPost('catatan_pencairan_bendahara');

            $data = [
                'catatan_pencairan_bendahara' => $catatanPencairan,
                'tanggal_proses_pencairan' => date('Y-m-d H:i:s')
            ];

            if ($keputusanPencairan === 'Siap Dicairkan') {
                $data['status_kredit'] = 'Dicairkan';
                $data['status_pencairan'] = 'Dicairkan';
                $data['status_verifikasi'] = 'verified'; // 🔧 FIX: Update status_verifikasi hanya saat sudah dicairkan
                
                // 🚀 AUTO-CREATE PENCAIRAN RECORD DAN GENERATE SEMUA ANGSURAN
                try {
                    // 1. Create pencairan record otomatis
                    $pencairanModel = new \App\Models\PencairanModel();
                    $existingPencairan = $pencairanModel->where('id_kredit', $id)->first();
                    
                    if (!$existingPencairan) {
                        // Get bunga default (bisa disesuaikan logic-nya)
                        $bungaModel = new \App\Models\BungaModel();
                        $defaultBunga = $bungaModel->where('status_aktif', 'Aktif')->first();
                        
                        if (!$defaultBunga) {
                            return redirect()->back()->with('error', 'Data bunga tidak ditemukan. Hubungi administrator.');
                        }
                        
                        $pencairanData = [
                            'id_kredit' => $id,
                            'id_bunga' => $defaultBunga['id_bunga'],
                            'jumlah_dicairkan' => $kredit['jumlah_pengajuan'],
                            'tanggal_pencairan' => date('Y-m-d'),
                            'catatan_pencairan' => 'Pencairan otomatis oleh Bendahara - ' . $catatanPencairan,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        
                        $pencairanModel->insert($pencairanData);
                        log_message('info', 'AUTO-PENCAIRAN: Record pencairan dibuat untuk kredit ID ' . $id);
                    }
                    
                    // 2. Generate HANYA angsuran ke-1 (user request: bertahap, bukan sekaligus)
                    $angsuranController = new \App\Controllers\AngsuranController();
                    $result = $angsuranController->generateAngsuranPertamaInternal($id);
                    
                    if ($result['success']) {
                        log_message('info', 'ALUR KREDIT: Bendahara berhasil mencairkan ID ' . $id . ', angsuran ke-1 dibuat otomatis');
                        $successMsg = 'Kredit berhasil dicairkan! ' . $result['message'] . ' Angsuran akan dibuat bertahap setelah pembayaran lunas.';
                    } else {
                        log_message('error', 'AUTO-ANGSURAN ERROR: ' . $result['message']);
                        $successMsg = 'Kredit dicairkan, namun ada masalah dengan pembuatan angsuran: ' . $result['message'];
                    }
                    
                } catch (\Exception $e) {
                    log_message('error', 'AUTO-PENCAIRAN ERROR: ' . $e->getMessage());
                    $successMsg = 'Kredit dicairkan, namun terjadi masalah: ' . $e->getMessage();
                }
                
            } else {
                $data['status_kredit'] = 'Perlu Review Bendahara';
                $data['status_pencairan'] = 'Menunggu';
                log_message('info', 'ALUR KREDIT: Bendahara memerlukan review lebih lanjut untuk ID ' . $id);
                $successMsg = 'Kredit memerlukan review lebih lanjut sebelum pencairan.';
            }

            $this->kreditModel->update($id, $data);
            return redirect()->to('/kredit/pengajuan-untuk-role')->with('success', $successMsg);
        }

        // Load anggota data with user info
        $anggota = $this->anggotaModel
            ->select('tbl_anggota.*, tbl_users.nama_lengkap')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota', 'left')
            ->find($kredit['id_anggota']);
        
        // Load view untuk proses pencairan
        $data = [
            'title' => 'Proses Pencairan - Bendahara',
            'kredit' => $kredit,
            'anggota' => $anggota
        ];

        return view('kredit/proses_pencairan', $data);
    }

    /**
     * View document with access control
     */
    /**
     * Menampilkan dokumen agunan dengan kontrol akses
     *
     * Method ini memvalidasi akses pengguna terhadap dokumen sebelum menampilkan.
     * Hanya pengguna yang memiliki akses ke data kredit terkait yang dapat
     * melihat dokumen agunannya.
     *
     * @param string $filename Nama file dokumen yang akan ditampilkan
     * @return ResponseInterface File content dengan header yang sesuai
     * @throws PageNotFoundException Jika dokumen tidak ditemukan atau tidak ada akses
     */
    public function viewDocument($filename)
    {
        // Cari kredit yang memiliki dokumen ini
        $kredit = $this->kreditModel->where('dokumen_agunan LIKE', '%' . $filename)->first();
        
        if (!$kredit) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Dokumen tidak ditemukan.');
        }

        // Cek akses menggunakan sistem filtering yang sudah ada
        if (!canAccessData($kredit)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke dokumen ini.');
        }

        // Path ke file
        $filePath = WRITEPATH . 'uploads/dokumen_kredit/' . $filename;
        
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan.');
        }

        // Serve file dengan content type yang tepat
        $mime = mime_content_type($filePath);
        
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody(file_get_contents($filePath));
    }

}
