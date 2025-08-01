<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
    }

    /**
     * Menampilkan form registrasi.
     * URL: /register
     */
    public function register()
    {
        return view('auth/register');
    }

    /**
     * Memproses data registrasi.
     * URL: /register (POST)
     */
    public function attemptRegister()
    {
        $rules = [
            'nama_lengkap' => 'required|max_length[255]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[tbl_users.username]',
            'email' => 'required|valid_email|is_unique[tbl_users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required_with[password]|matches[password]',
            'no_hp' => 'permit_empty|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'), // Password akan di-hash oleh UserModel
            'level' =>'anggota',
            'no_hp' => $this->request->getPost('no_hp'),
            'no_hp' => $this->request->getPost('no_hp'),

        ];

        $this->userModel->insert($data);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    /**
     * Menampilkan form login.
     * URL: /login
     */
    public function login()
    {
        // Jika user sudah login, redirect ke dashboard
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/home');
        }
        return view('auth/login');
    }

    /**
     * Memproses data login.
     * URL: /login (POST)
     */
    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->getByUsername($username);

        // Handle non-existent user
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Akun tidak ditemukan. Silakan periksa kembali username/email Anda atau daftar akun baru.');
        }

        // Handle invalid password
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password salah. Silakan coba lagi.');
        }

        // Set session data
        $userData = [
            'id_user' => $user['id_user'],
            'nama_lengkap' => $user['nama_lengkap'],
            'username' => $user['username'],
            'email' => $user['email'],
            'level' => $user['level'],
            'isLoggedIn' => true,
        ];
        $this->session->set($userData);

        return redirect()->to('/home')->with('success', 'Selamat datang, ' . $user['nama_lengkap'] . '!');
    }

    /**
     * Logout user.
     * URL: /logout
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
