<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UserController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['users'] = $this->userModel->findAll();
        return view('user/index', $data);
    }

    public function new()
    {
        return view('user/form');
    }

    public function create()
    {
        $rules = [
            'nama_lengkap' => 'required|max_length[255]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[tbl_users.username]',
            'email' => 'required|valid_email|is_unique[tbl_users.email]',
            'password' => 'required|min_length[6]',
            'level' => 'required|max_length[50]',
            'no_hp' => 'permit_empty|max_length[20]',
            'id_anggota_ref' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'), // Password akan di-hash oleh model
            'level' => $this->request->getPost('level'),
            'no_hp' => $this->request->getPost('no_hp'),
            'id_anggota_ref' => $this->request->getPost('id_anggota_ref'),
        ];

        $this->userModel->insert($data);
        return redirect()->to('/user')->with('success', 'Data user berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $data['user'] = $this->userModel->find($id);
        if (empty($data['user'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('User dengan ID ' . $id . ' tidak ditemukan.'); }
        return view('user/form', $data);
    }

    public function update($id = null)
    {
        $user = $this->userModel->find($id);
        if (empty($user)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('User dengan ID ' . $id . ' tidak ditemukan.'); }

        $rules = [
            'nama_lengkap' => 'required|max_length[255]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[tbl_users.username,id_user,' . $id . ']',
            'email' => 'required|valid_email|is_unique[tbl_users.email,id_user,' . $id . ']',
            'password' => 'permit_empty|min_length[6]', // Password opsional saat update
            'level' => 'required|max_length[50]',
            'no_hp' => 'permit_empty|max_length[20]',
            'id_anggota_ref' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) { return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'level' => $this->request->getPost('level'),
            'no_hp' => $this->request->getPost('no_hp'),
            'id_anggota_ref' => $this->request->getPost('id_anggota_ref'),
        ];

        // Hanya update password jika ada input baru
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        $this->userModel->update($id, $data);
        return redirect()->to('/user')->with('success', 'Data user berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $user = $this->userModel->find($id);
        if (empty($user)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('User dengan ID ' . $id . ' tidak ditemukan.'); }

        $this->userModel->delete($id);
        return redirect()->to('/user')->with('success', 'Data user berhasil dihapus.');
    }

    public function show($id = null)
    {
        $data['user'] = $this->userModel->find($id);
        if (empty($data['user'])) { throw new \CodeIgniter\Exceptions\PageNotFoundException('User dengan ID ' . $id . ' tidak ditemukan.'); }
        return view('user/show', $data);
    }
}
