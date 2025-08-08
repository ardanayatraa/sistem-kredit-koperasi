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

    public function index()
    {
        // Use filtered method with user-based access control
        $data['pencairan'] = $this->pencairanModel->getFilteredPencairanWithData();
        return view('pencairan/index', $data);
    }

    public function new()
    {
        $kreditModel = new \App\Models\KreditModel();
        $bungaModel = new \App\Models\BungaModel();
        
        // Get approved kredits that haven't been disbursed yet - with filtering
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap';
        $additionalWhere = ['tbl_kredit.status_kredit' => 'Disetujui'];
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
            
            // Auto-generate jadwal angsuran setelah pencairan berhasil
            if ($pencairanId) {
                $angsuranController = new \App\Controllers\AngsuranController();
                $result = $angsuranController->generateAngsuranPertamaInternal($data['id_kredit']);
                
                if ($result && $result['success']) {
                    $message = 'Data pencairan berhasil ditambahkan dan jadwal angsuran telah dibuat otomatis.';
                } else {
                    $message = 'Data pencairan berhasil ditambahkan. Namun, jadwal angsuran perlu dibuat manual.';
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

    public function delete($id = null)
    {
        $pencairan = $this->pencairanModel->find($id);
        if (empty($pencairan)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Pencairan dengan ID ' . $id . ' tidak ditemukan.'); }

        $uploadPath = WRITEPATH . 'uploads/pencairan';
        if (!empty($pencairan['bukti_transfer']) && file_exists($uploadPath . '/' . $pencairan['bukti_transfer'])) { unlink($uploadPath . '/' . $pencairan['bukti_transfer']); }

        $this->pencairanModel->delete($id);
        return redirect()->to('/pencairan')->with('success', 'Data pencairan berhasil dihapus.');
    }

    public function show($id = null)
    {
        // Use access-controlled method
        $data['pencairan'] = $this->pencairanModel->findWithAccess($id);
        if (empty($data['pencairan'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pencairan dengan ID ' . $id . ' tidak ditemukan atau Anda tidak memiliki akses.');
        }
        return view('pencairan/show', $data);
    }

    /**
     * Toggle pencairan status (Aktif/Tidak Aktif)
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
     * View document with access control
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
