<?php

namespace App\Controllers;

use App\Models\BungaModel;
use CodeIgniter\Controller;

class BungaController extends Controller
{
    protected $bungaModel;

    public function __construct()
    {
        $this->bungaModel = new BungaModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['bunga'] = $this->bungaModel->findAll();
        return view('bunga/index', $data);
    }

    public function new()
    {
        return view('bunga/form');
    }

    public function create()
    {
        $rules = [
            'nama_bunga' => 'required|max_length[255]',
            'persentase_bunga' => 'required|numeric',
            'tipe_bunga' => 'required|max_length[50]',
            'keterangan' => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_bunga' => $this->request->getPost('nama_bunga'),
            'persentase_bunga' => $this->request->getPost('persentase_bunga'),
            'tipe_bunga' => $this->request->getPost('tipe_bunga'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->bungaModel->insert($data);
        return redirect()->to('/bunga')->with('success', 'Data bunga berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $data['bunga'] = $this->bungaModel->find($id);
        if (empty($data['bunga'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Bunga dengan ID ' . $id . ' tidak ditemukan.'); }
        return view('bunga/form', $data);
    }

    public function update($id = null)
    {
        $bunga = $this->bungaModel->find($id);
        if (empty($bunga)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Bunga dengan ID ' . $id . ' tidak ditemukan.'); }

        $rules = [
            'nama_bunga' => 'required|max_length[255]',
            'persentase_bunga' => 'required|numeric',
            'tipe_bunga' => 'required|max_length[50]',
            'keterangan' => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) { return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); }

        $data = [
            'nama_bunga' => $this->request->getPost('nama_bunga'),
            'persentase_bunga' => $this->request->getPost('persentase_bunga'),
            'tipe_bunga' => $this->request->getPost('tipe_bunga'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->bungaModel->update($id, $data);
        return redirect()->to('/bunga')->with('success', 'Data bunga berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $bunga = $this->bungaModel->find($id);
        if (empty($bunga)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Bunga dengan ID ' . $id . ' tidak ditemukan.'); }

        $this->bungaModel->delete($id);
        return redirect()->to('/bunga')->with('success', 'Data bunga berhasil dihapus.');
    }

    public function show($id = null)
    {
        $data['bunga'] = $this->bungaModel->find($id);
        if (empty($data['bunga'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('Bunga dengan ID ' . $id . ' tidak ditemukan.'); }
        return view('bunga/show', $data);
    }
}
