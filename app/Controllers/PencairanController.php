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
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['pencairan'] = $this->pencairanModel->findAll();
        return view('pencairan/index', $data);
    }

    public function new()
    {
        $kreditModel = new \App\Models\KreditModel();
        $data['kreditOptions'] = $kreditModel->findAll();
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

        $this->pencairanModel->insert($data);
        return redirect()->to('/pencairan')->with('success', 'Data pencairan berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $kreditModel = new \App\Models\KreditModel();
        $data['kreditOptions'] = $kreditModel->findAll();
        
        $data['pencairan'] = $this->pencairanModel->find($id);
        if (empty($data['pencairan'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Pencairan dengan ID ' . $id . ' tidak ditemukan.'); }
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
        $data['pencairan'] = $this->pencairanModel->find($id);
        if (empty($data['pencairan'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Pencairan dengan ID ' . $id . ' tidak ditemukan.'); }
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
}
