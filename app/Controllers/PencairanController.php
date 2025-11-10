<?php

namespace App\Controllers;

use App\Models\PencairanModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Files\UploadedFile;

class PencairanController extends Controller
{
    protected $pencairanModel;

    public function __construct()
    {
        $this->pencairanModel = new PencairanModel();
        helper(['form', 'url', 'data_filter']);
    }

    /**
     * Menampilkan halaman daftar pencairan dengan kontrol akses
     *
     * Method ini menampilkan daftar semua pencairan yang dapat diakses pengguna
     * berdasarkan role mereka. Data pencairan dilengkapi dengan informasi kredit dan anggota.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface Halaman daftar pencairan
     */
    public function index()
    {
        // Use filtered method with user-based access control
        $pencairan = $this->pencairanModel->getFilteredPencairanWithData();
        
        // Sort by created_at DESC (data terbaru di atas)
        usort($pencairan, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        $data['pencairan'] = $pencairan;
        return view('pencairan/index', $data);
    }

    /**
     * Menampilkan form untuk membuat pencairan baru
     *
     * Method ini menyiapkan data untuk form pencairan, termasuk daftar kredit yang
     * sudah disetujui (status: "Siap Dicairkan") dan belum memiliki pencairan,
     * serta daftar tingkat bunga yang aktif.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface Halaman form pencairan baru
     */
    public function new()
    {
        $kreditModel = new \App\Models\KreditModel();
        $bungaModel = new \App\Models\BungaModel();
        
        // Get kredits ready for disbursement that haven't been disbursed yet - with filtering
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap';
        $additionalWhere = ['tbl_kredit.status_kredit' => 'Siap Dicairkan'];
        $approvedKredits = $kreditModel->getFilteredKreditsWithData($additionalWhere, $select);
        
        // Filter out kredits that already have pencairan
        $existingPencairanKredits = $this->pencairanModel->builder()->select('id_kredit')->get()->getResultArray();
        $existingIds = array_column($existingPencairanKredits, 'id_kredit');
        
        $data['kreditOptions'] = array_filter($approvedKredits, function($kredit) use ($existingIds) {
            return !in_array($kredit['id_kredit'], $existingIds);
        });
            
        // Get active interest rates
        $data['bungaOptions'] = $bungaModel->where('status_aktif', 'Aktif')->findAll();
        
        return view('pencairan/form', $data);
    }

    /**
     * Memproses pembuatan pencairan baru
     *
     * Method ini memvalidasi data pencairan, mengupload bukti transfer,
     * menyimpan data pencairan, mengupdate status kredit menjadi "Sudah Dicairkan",
     * dan otomatis membuat angsuran pertama setelah pencairan berhasil.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect dengan status pembuatan pencairan
     */
    public function create()
    {
        $rules = [
            'id_kredit' => 'required|integer',
            'tanggal_pencairan' => 'required|valid_date',
            'jumlah_dicairkan' => 'required|numeric',
            'metode_pencairan' => 'required|max_length[100]',
            'id_bunga' => 'required|integer',
            'bukti_transfer' => 'uploaded[bukti_transfer]|max_size[bukti_transfer,2048]|ext_in[bukti_transfer,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        /** @var UploadedFile $buktiTransfer */
        $buktiTransfer = $this->request->getFile('bukti_transfer');

        $uploadPath = WRITEPATH . 'uploads/pencairan';
        if (!is_dir($uploadPath)) { mkdir($uploadPath, 0777, true); }

        $buktiTransferName = $buktiTransfer->getRandomName();
        $buktiTransfer->move($uploadPath, $buktiTransferName);

        $data = [
            'id_kredit' => $this->request->getPost('id_kredit'),
            'tanggal_pencairan' => $this->request->getPost('tanggal_pencairan'),
            'jumlah_dicairkan' => $this->request->getPost('jumlah_dicairkan'),
            'metode_pencairan' => $this->request->getPost('metode_pencairan'),
            'id_bunga' => $this->request->getPost('id_bunga'),
            'bukti_transfer' => $buktiTransferName,
        ];

        try {
            $pencairanId = $this->pencairanModel->insert($data);
            
            if ($pencairanId) {
                // Update credit status to fully disbursed
                $kreditModel = new \App\Models\KreditModel();
                $kreditModel->update($data['id_kredit'], [
                    'status_kredit' => 'Sudah Dicairkan',
                    'status_pencairan' => 'Sudah Dicairkan'
                ]);
                
                // Auto-generate jadwal angsuran setelah pencairan berhasil
                $angsuranController = new \App\Controllers\AngsuranController();
                $result = $angsuranController->generateAngsuranPertamaInternal($data['id_kredit']);
                
                if ($result && $result['success']) {
                    $message = 'Pencairan berhasil! Status kredit diperbarui dan jadwal angsuran telah dibuat otomatis.';
                } else {
                    $message = 'Pencairan berhasil! Status kredit diperbarui. Namun, jadwal angsuran perlu dibuat manual.';
                }
            } else {
                $message = 'Data pencairan berhasil ditambahkan.';
            }
            
            return redirect()->to('/pencairan')->with('success', $message);
            
        } catch (\Exception $e) {
            log_message('error', 'Error creating pencairan: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan pencairan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form edit pencairan
     *
     * Method ini menyiapkan data untuk form edit pencairan, termasuk data pencairan
     * yang akan diedit, daftar kredit, dan tingkat bunga aktif. Dilengkapi dengan
     * validasi akses untuk memastikan pengguna berhak mengedit pencairan tersebut.
     *
     * @param int $id ID pencairan yang akan diedit
     * @return \CodeIgniter\HTTP\ResponseInterface Halaman form edit pencairan
     */
    public function edit($id = null)
    {
        $kreditModel = new \App\Models\KreditModel();
        $bungaModel = new \App\Models\BungaModel();
        
        // Use filtered method for kredit options
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap';
        $data['kreditOptions'] = $kreditModel->getFilteredKreditsWithData([], $select);
            
        $data['bungaOptions'] = $bungaModel->where('status_aktif', 'Aktif')->findAll();
        
        // Use access-controlled method
        $data['pencairan'] = $this->pencairanModel->findWithAccess($id);
        if (empty($data['pencairan'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pencairan dengan ID ' . $id . ' tidak ditemukan atau Anda tidak memiliki akses.');
        }
        return view('pencairan/form', $data);
    }

    /**
     * Memproses update data pencairan
     *
     * Method ini memvalidasi data perubahan, menangani upload bukti transfer baru
     * (jika ada), dan memperbarui data pencairan. File lama akan dihapus jika
     * ada file baru yang diupload.
     *
     * @param int $id ID pencairan yang akan diupdate
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect dengan status update
     */
    public function update($id = null)
    {
        $pencairan = $this->pencairanModel->find($id);
        if (empty($pencairan)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Pencairan dengan ID ' . $id . ' tidak ditemukan.'); }

        $rules = [
            'id_kredit' => 'required|integer',
            'tanggal_pencairan' => 'required|valid_date',
            'jumlah_dicairkan' => 'required|numeric',
            'metode_pencairan' => 'required|max_length[100]',
            'id_bunga' => 'required|integer',
            'bukti_transfer' => 'max_size[bukti_transfer,2048]|ext_in[bukti_transfer,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) { return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); }

        $data = [
            'id_kredit' => $this->request->getPost('id_kredit'),
            'tanggal_pencairan' => $this->request->getPost('tanggal_pencairan'),
            'jumlah_dicairkan' => $this->request->getPost('jumlah_dicairkan'),
            'metode_pencairan' => $this->request->getPost('metode_pencairan'),
            'id_bunga' => $this->request->getPost('id_bunga'),
        ];

        /** @var UploadedFile $buktiTransfer */
        $buktiTransfer = $this->request->getFile('bukti_transfer');
        $uploadPath = WRITEPATH . 'uploads/pencairan';
        if (!is_dir($uploadPath)) { mkdir($uploadPath, 0777, true); }

        if ($buktiTransfer && $buktiTransfer->isValid() && !$buktiTransfer->hasMoved()) {
            if (!empty($pencairan['bukti_transfer']) && file_exists($uploadPath . '/' . $pencairan['bukti_transfer'])) { unlink($uploadPath . '/' . $pencairan['bukti_transfer']); }
            $buktiTransferName = $buktiTransfer->getRandomName();
            $buktiTransfer->move($uploadPath, $buktiTransferName);
            $data['bukti_transfer'] = $buktiTransferName;
        }

        $this->pencairanModel->update($id, $data);
        return redirect()->to('/pencairan')->with('success', 'Data pencairan berhasil diperbarui.');
    }

    /**
     * Menghapus data pencairan
     *
     * Method ini menghapus data pencairan dari database beserta file bukti transfer
     * yang terkait. File fisik di storage juga akan dihapus untuk menghemat ruang.
     *
     * @param int $id ID pencairan yang akan dihapus
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect dengan status penghapusan
     */
    public function delete($id = null)
    {
        $pencairan = $this->pencairanModel->find($id);
        if (empty($pencairan)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Pencairan dengan ID ' . $id . ' tidak ditemukan.'); }

        $uploadPath = WRITEPATH . 'uploads/pencairan';
        if (!empty($pencairan['bukti_transfer']) && file_exists($uploadPath . '/' . $pencairan['bukti_transfer'])) { unlink($uploadPath . '/' . $pencairan['bukti_transfer']); }

        $this->pencairanModel->delete($id);
        return redirect()->to('/pencairan')->with('success', 'Data pencairan berhasil dihapus.');
    }

    /**
     * Menampilkan detail pencairan
     *
     * Method ini menampilkan informasi lengkap tentang pencairan tertentu
     * dengan validasi akses untuk memastikan pengguna berhak melihat data tersebut.
     *
     * @param int $id ID pencairan yang akan ditampilkan
     * @return \CodeIgniter\HTTP\ResponseInterface Halaman detail pencairan
     */
    public function show($id = null)
    {
        // Use same approach as edit method
        $data['pencairan'] = $this->pencairanModel->findWithAccess($id);
        if (empty($data['pencairan'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pencairan dengan ID ' . $id . ' tidak ditemukan atau Anda tidak memiliki akses.');
        }
        
        // Get additional data with joins
        $builder = $this->pencairanModel->builder();
        $data['pencairan'] = $builder
            ->select('tbl_pencairan.*, 
                     tbl_kredit.id_kredit, tbl_kredit.jumlah_pengajuan, tbl_kredit.jangka_waktu, tbl_kredit.tujuan_kredit,
                     tbl_anggota.no_anggota,
                     tbl_users.nama_lengkap as nama_anggota,
                     tbl_bunga.nama_bunga, tbl_bunga.persentase_bunga')
            ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_pencairan.id_kredit')
            ->join('tbl_anggota', 'tbl_anggota.id_anggota = tbl_kredit.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota', 'left')
            ->join('tbl_bunga', 'tbl_bunga.id_bunga = tbl_pencairan.id_bunga', 'left')
            ->where('tbl_pencairan.id_pencairan', $id)
            ->get()
            ->getRowArray();
        
        return view('pencairan/show', $data);
    }

    /**
     * Mengubah status aktif pencairan (toggle antara Aktif/Tidak Aktif)
     *
     * Method AJAX ini mengubah status_aktif pencairan antara "Aktif" dan "Tidak Aktif".
     * Digunakan untuk menonaktifkan pencairan yang bermasalah tanpa menghapus data.
     * Mengembalikan response JSON untuk handling di frontend.
     *
     * @param int $id ID pencairan yang akan diubah statusnya
     * @return \CodeIgniter\HTTP\ResponseInterface JSON response dengan status toggle
     */
    public function toggleStatus($id = null)
    {
        $pencairan = $this->pencairanModel->find($id);
        if (empty($pencairan)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pencairan tidak ditemukan.'
            ])->setStatusCode(404);
        }

        // Toggle status_aktif between Aktif and Tidak Aktif
        $currentStatus = $pencairan['status_aktif'] ?? 'Aktif';
        $newStatus = ($currentStatus === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $this->pencairanModel->update($id, ['status_aktif' => $newStatus]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status pencairan berhasil diubah menjadi ' . $newStatus,
            'new_status' => $newStatus
        ]);
    }

    /**
     * Menampilkan dokumen bukti transfer dengan kontrol akses
     *
     * Method ini memungkinkan pengguna melihat file bukti transfer pencairan
     * dengan validasi ketat: file harus ada, terkait dengan pencairan yang valid,
     * dan pengguna harus memiliki akses ke data pencairan tersebut.
     *
     * @param string $filename Nama file bukti transfer yang akan ditampilkan
     * @return void Output file langsung ke browser atau throw exception jika tidak ada akses
     */
    public function viewDocument($filename)
    {
        // Remove path info if included and get just filename
        $filename = basename($filename);
        
        // Build file path
        $filePath = WRITEPATH . 'uploads/pencairan/' . $filename;
        
        // Check if file exists
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan.');
        }
        
        // Get pencairan record that owns this file to check access
        $pencairan = $this->pencairanModel->where('bukti_transfer', $filename)->first();
        if (!$pencairan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak dapat diakses.');
        }
        
        // Check if user has access to this pencairan data
        $accessible = $this->pencairanModel->findWithAccess($pencairan['id_pencairan']);
        if (!$accessible) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke file ini.');
        }
        
        // Get file info
        $mimeType = mime_content_type($filePath);
        $fileSize = filesize($filePath);
        
        // Set headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . $fileSize);
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Cache-Control: private, max-age=0, no-cache');
        header('Pragma: no-cache');
        
        // Output file
        readfile($filePath);
        exit;
    }
}
