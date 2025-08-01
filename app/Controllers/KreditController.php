<?php

namespace App\Controllers;

use App\Models\KreditModel;
use CodeIgniter\Controller;

class KreditController extends Controller
{
    protected $kreditModel;

    public function __construct()
    {
        $this->kreditModel = new KreditModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['kredit'] = $this->kreditModel->findAll();
        return view('kredit/index', $data);
    }

    public function new()
    {
        return view('kredit/form');
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
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
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
            'status_kredit' => $this->request->getPost('status_kredit'),
        ];

        $this->kreditModel->insert($data);
        return redirect()->to('/kredit')->with('success', 'Data kredit berhasil ditambahkan.');
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
            'catatan_appraiser' => $this->request->getPost('catatan_appraiser'),
            'catatan_ketua' => $this->request->getPost('catatan_ketua'),
            'status_kredit' => $this->request->getPost('status_kredit'),
        ];

        $this->kreditModel->update($id, $data);
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
}
