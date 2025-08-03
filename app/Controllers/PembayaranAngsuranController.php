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
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['pembayaran_angsuran'] = $this->pembayaranAngsuranModel->findAll();
        return view('pembayaran_angsuran/index', $data);
    }

    public function new()
    {
        return view('pembayaran_angsuran/form');
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
            'denda' => $this->request->getPost('denda'),
            'id_bendahara_verifikator' => $this->request->getPost('id_bendahara_verifikator'),
        ];

        $this->pembayaranAngsuranModel->insert($data);
        return redirect()->to('/pembayaran-angsuran')->with('success', 'Data pembayaran angsuran berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $data['pembayaran_angsuran'] = $this->pembayaranAngsuranModel->find($id);
        if (empty($data['pembayaran_angsuran'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Pembayaran Angsuran dengan ID ' . $id . ' tidak ditemukan.'); }
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
        $data['pembayaran_angsuran'] = $this->pembayaranAngsuranModel->find($id);
        if (empty($data['pembayaran_angsuran'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Pembayaran Angsuran dengan ID ' . $id . ' tidak ditemukan.'); }
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
}
