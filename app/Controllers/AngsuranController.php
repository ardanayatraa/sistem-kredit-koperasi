<?php

namespace App\Controllers;

use App\Models\AngsuranModel;
use CodeIgniter\Controller;

class AngsuranController extends Controller
{
    protected $angsuranModel;

    public function __construct()
    {
        $this->angsuranModel = new AngsuranModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['angsuran'] = $this->angsuranModel->findAll();
        return view('angsuran/index', $data);
    }

    public function new()
    {
        return view('angsuran/form');
    }

    public function create()
    {
        $rules = [
            'id_kredit' => 'required|integer',
            'angsuran_ke' => 'required|integer',
            'jumlah_angsuran' => 'required|numeric',
            'tgl_jatuh_tempo' => 'required|valid_date',
            'status_pembayaran' => 'required|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_kredit' => $this->request->getPost('id_kredit'),
            'angsuran_ke' => $this->request->getPost('angsuran_ke'),
            'jumlah_angsuran' => $this->request->getPost('jumlah_angsuran'),
            'tgl_jatuh_tempo' => $this->request->getPost('tgl_jatuh_tempo'),
            'status_pembayaran' => $this->request->getPost('status_pembayaran'),
        ];

        $this->angsuranModel->insert($data);
        return redirect()->to('/angsuran')->with('success', 'Data angsuran berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $data['angsuran'] = $this->angsuranModel->find($id);
        if (empty($data['angsuran'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Angsuran dengan ID ' . $id . ' tidak ditemukan.'); }
        return view('angsuran/form', $data);
    }

    public function update($id = null)
    {
        $angsuran = $this->angsuranModel->find($id);
        if (empty($angsuran)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Angsuran dengan ID ' . $id . ' tidak ditemukan.'); }

        $rules = [
            'id_kredit' => 'required|integer',
            'angsuran_ke' => 'required|integer',
            'jumlah_angsuran' => 'required|numeric',
            'tgl_jatuh_tempo' => 'required|valid_date',
            'status_pembayaran' => 'required|max_length[50]',
        ];

        if (!$this->validate($rules)) { return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); }

        $data = [
            'id_kredit' => $this->request->getPost('id_kredit'),
            'angsuran_ke' => $this->request->getPost('angsuran_ke'),
            'jumlah_angsuran' => $this->request->getPost('jumlah_angsuran'),
            'tgl_jatuh_tempo' => $this->request->getPost('tgl_jatuh_tempo'),
            'status_pembayaran' => $this->request->getPost('status_pembayaran'),
        ];

        $this->angsuranModel->update($id, $data);
        return redirect()->to('/angsuran')->with('success', 'Data angsuran berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $angsuran = $this->angsuranModel->find($id);
        if (empty($angsuran)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Angsuran dengan ID ' . $id . ' tidak ditemukan.'); }

        $this->angsuranModel->delete($id);
        return redirect()->to('/angsuran')->with('success', 'Data angsuran berhasil dihapus.');
    }

    public function show($id = null)
    {
        $data['angsuran'] = $this->angsuranModel->find($id);
        if (empty($data['angsuran'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Angsuran dengan ID ' . $id . ' tidak ditemukan.'); }
        return view('angsuran/show', $data);
    }
}
