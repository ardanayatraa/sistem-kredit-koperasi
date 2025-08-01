<?php

namespace App\Controllers;

use App\Models\AnggotaModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Files\UploadedFile;

class AnggotaController extends Controller
{
    protected $anggotaModel;

    public function __construct()
    {
        $this->anggotaModel = new AnggotaModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['anggota'] = $this->anggotaModel->findAll();
        return view('anggota/index', $data);
    }

    public function new()
    {
        return view('anggota/form');
    }

    public function create()
    {
        $rules = [
            'nik' => 'required|min_length[16]|max_length[16]|is_unique[tbl_anggota.nik]',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|valid_date',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'tanggal_pendaftaran' => 'required|valid_date',
            'status_keanggotaan' => 'required',
            'dokumen_ktp' => 'uploaded[dokumen_ktp]|max_size[dokumen_ktp,2048]|ext_in[dokumen_ktp,pdf,jpg,jpeg,png]',
            'dokumen_kk' => 'uploaded[dokumen_kk]|max_size[dokumen_kk,2048]|ext_in[dokumen_kk,pdf,jpg,jpeg,png]',
            'dokumen_slip_gaji' => 'uploaded[dokumen_slip_gaji]|max_size[dokumen_slip_gaji,2048]|ext_in[dokumen_slip_gaji,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        /** @var UploadedFile $ktp */
        $ktp = $this->request->getFile('dokumen_ktp');
        /** @var UploadedFile $kk */
        $kk = $this->request->getFile('dokumen_kk');
        /** @var UploadedFile $slipGaji */
        $slipGaji = $this->request->getFile('dokumen_slip_gaji');

        $uploadPath = WRITEPATH . 'uploads/anggota';
        if (!is_dir($uploadPath)) { mkdir($uploadPath, 0777, true); }

        $ktpName = $ktp->getRandomName();
        $kkName = $kk->getRandomName();
        $slipGajiName = $slipGaji->getRandomName();

        $ktp->move($uploadPath, $ktpName);
        $kk->move($uploadPath, $kkName);
        $slipGaji->move($uploadPath, $slipGajiName);

        $data = [
            'nik' => $this->request->getPost('nik'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'alamat' => $this->request->getPost('alamat'),
            'pekerjaan' => $this->request->getPost('pekerjaan'),
            'tanggal_pendaftaran' => $this->request->getPost('tanggal_pendaftaran'),
            'status_keanggotaan' => $this->request->getPost('status_keanggotaan'),
            'dokumen_ktp' => $ktpName,
            'dokumen_kk' => $kkName,
            'dokumen_slip_gaji' => $slipGajiName,
        ];

        $this->anggotaModel->insert($data);
        return redirect()->to('/anggota')->with('success', 'Data anggota berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $data['anggota'] = $this->anggotaModel->find($id);
        if (empty($data['anggota'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Anggota dengan ID ' . $id . ' tidak ditemukan.'); }
        return view('anggota/form', $data);
    }

    public function update($id = null)
    {
        $anggota = $this->anggotaModel->find($id);
        if (empty($anggota)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Anggota dengan ID ' . $id . ' tidak ditemukan.'); }

        $rules = [
            'nik' => 'required|min_length[16]|max_length[16]|is_unique[tbl_anggota.nik,id_anggota,' . $id . ']',
            'tempat_lahir' => 'required', 'tanggal_lahir' => 'required|valid_date', 'alamat' => 'required',
            'pekerjaan' => 'required', 'tanggal_pendaftaran' => 'required|valid_date', 'status_keanggotaan' => 'required',
            'dokumen_ktp' => 'max_size[dokumen_ktp,2048]|ext_in[dokumen_ktp,pdf,jpg,jpeg,png]',
            'dokumen_kk' => 'max_size[dokumen_kk,2048]|ext_in[dokumen_kk,pdf,jpg,jpeg,png]',
            'dokumen_slip_gaji' => 'max_size[dokumen_slip_gaji,2048]|ext_in[dokumen_slip_gaji,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) { return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); }

        $data = [
            'nik' => $this->request->getPost('nik'), 'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'), 'alamat' => $this->request->getPost('alamat'),
            'pekerjaan' => $this->request->getPost('pekerjaan'), 'tanggal_pendaftaran' => $this->request->getPost('tanggal_pendaftaran'),
            'status_keanggotaan' => $this->request->getPost('status_keanggotaan'),
        ];

        $uploadPath = WRITEPATH . 'uploads/anggota';
        if (!is_dir($uploadPath)) { mkdir($uploadPath, 0777, true); }

        /** @var UploadedFile $ktp */
        $ktp = $this->request->getFile('dokumen_ktp');
        if ($ktp && $ktp->isValid() && !$ktp->hasMoved()) {
            if (!empty($anggota['dokumen_ktp']) && file_exists($uploadPath . '/' . $anggota['dokumen_ktp'])) { unlink($uploadPath . '/' . $anggota['dokumen_ktp']); }
            $ktpName = $ktp->getRandomName(); $ktp->move($uploadPath, $ktpName); $data['dokumen_ktp'] = $ktpName;
        }
        /** @var UploadedFile $kk */
        $kk = $this->request->getFile('dokumen_kk');
        if ($kk && $kk->isValid() && !$kk->hasMoved()) {
            if (!empty($anggota['dokumen_kk']) && file_exists($uploadPath . '/' . $anggota['dokumen_kk'])) { unlink($uploadPath . '/' . $anggota['dokumen_kk']); }
            $kkName = $kk->getRandomName(); $kk->move($uploadPath, $kkName); $data['dokumen_kk'] = $kkName;
        }
        /** @var UploadedFile $slipGaji */
        $slipGaji = $this->request->getFile('dokumen_slip_gaji');
        if ($slipGaji && $slipGaji->isValid() && !$slipGaji->hasMoved()) {
            if (!empty($anggota['dokumen_slip_gaji']) && file_exists($uploadPath . '/' . $anggota['dokumen_slip_gaji'])) { unlink($uploadPath . '/' . $anggota['dokumen_slip_gaji']); }
            $slipGajiName = $slipGaji->getRandomName(); $slipGaji->move($uploadPath, $slipGajiName); $data['dokumen_slip_gaji'] = $slipGajiName;
        }

        $this->anggotaModel->update($id, $data);
        return redirect()->to('/anggota')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $anggota = $this->anggotaModel->find($id);
        if (empty($anggota)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Anggota dengan ID ' . $id . ' tidak ditemukan.'); }

        $uploadPath = WRITEPATH . 'uploads/anggota';
        if (!empty($anggota['dokumen_ktp']) && file_exists($uploadPath . '/' . $anggota['dokumen_ktp'])) { unlink($uploadPath . '/' . $anggota['dokumen_ktp']); }
        if (!empty($anggota['dokumen_kk']) && file_exists($uploadPath . '/' . $anggota['dokumen_kk'])) { unlink($uploadPath . '/' . $anggota['dokumen_kk']); }
        if (!empty($anggota['dokumen_slip_gaji']) && file_exists($uploadPath . '/' . $anggota['dokumen_slip_gaji'])) { unlink($uploadPath . '/' . $anggota['dokumen_slip_gaji']); }

        $this->anggotaModel->delete($id);
        return redirect()->to('/anggota')->with('success', 'Data anggota berhasil dihapus.');
    }

    public function show($id = null)
    {
        $data['anggota'] = $this->anggotaModel->find($id);
        if (empty($data['anggota'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Anggota dengan ID ' . $id . ' tidak ditemukan.'); }
        return view('anggota/show', $data);
    }

    /**
     * Toggle anggota status (Aktif/Tidak Aktif)
     */
    public function toggleStatus($id = null)
    {
        $anggota = $this->anggotaModel->find($id);
        if (empty($anggota)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anggota tidak ditemukan.'
            ])->setStatusCode(404);
        }

        // Toggle status between Aktif and Tidak Aktif
        $currentStatus = $anggota['status_keanggotaan'];
        $newStatus = ($currentStatus === 'Aktif') ? 'Tidak Aktif' : 'Aktif';

        $this->anggotaModel->update($id, ['status_keanggotaan' => $newStatus]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status anggota berhasil diubah menjadi ' . $newStatus,
            'new_status' => $newStatus
        ]);
    }
}
