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
        ];

        if (!$this->validate($rules)) { return redirect()->back()->withInput()->with('errors', $this->validator->getErrors()); }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'level' => $this->request->getPost('level'),
            'no_hp' => $this->request->getPost('no_hp'),
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

    public function profile()
    {
        // No permission check needed for profile
        // Get the current user's ID from session
        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);
        $data['user'] = $user;
        
        // Load anggota data if user is anggota and has completed anggota data
        if (session()->get('level') === 'Anggota' && !empty($user['id_anggota_ref'])) {
            $anggotaModel = new \App\Models\AnggotaModel();
            $data['anggota'] = $anggotaModel->find($user['id_anggota_ref']);
        }
        
        return view('profile/index', $data);
    }

    public function updateProfile()
    {
        // No permission check needed for profile update
        // Get the current user's ID from session
        $userId = session()->get('id_user');
        $user = $this->userModel->find($userId);
        if (empty($user)) { throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan.'); }

        $rules = [
            'nama_lengkap' => 'required|max_length[255]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[tbl_users.username,id_user,' . $userId . ']',
            'email' => 'required|valid_email|is_unique[tbl_users.email,id_user,' . $userId . ']',
            'password' => 'permit_empty|min_length[6]',
            'no_hp' => 'permit_empty|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
        ];

        // Update password only if provided
        if ($password = $this->request->getPost('password')) {
            $data['password'] = $password;
        }

        $this->userModel->update($userId, $data);
        return redirect()->to('/profile')->with('success', 'Profile berhasil diperbarui.');
    }

    /**
     * Show form to complete anggota data for users with level 'Anggota'
     */
    public function completeAnggotaData()
    {
        $userId = session()->get('id_user');
        $userLevel = session()->get('level');
        
        // Only allow for users with level 'Anggota'
        if ($userLevel !== 'Anggota') {
            return redirect()->to('/home')->with('error', 'Akses ditolak.');
        }
        
        $data['user'] = $this->userModel->find($userId);
        return view('profile/complete_anggota_data', $data);
    }

    /**
     * Save anggota data and link with user
     */
    public function saveAnggotaData()
    {
        $userId = session()->get('id_user');
        $userLevel = session()->get('level');
        
        // Only allow for users with level 'Anggota'
        if ($userLevel !== 'Anggota') {
            return redirect()->to('/home')->with('error', 'Akses ditolak.');
        }

        $rules = [
            'nik' => 'required|min_length[16]|max_length[16]|is_unique[tbl_anggota.nik]',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|valid_date',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'tanggal_pendaftaran' => 'required|valid_date',
            'dokumen_ktp' => 'uploaded[dokumen_ktp]|max_size[dokumen_ktp,2048]|ext_in[dokumen_ktp,pdf,jpg,jpeg,png]',
            'dokumen_kk' => 'uploaded[dokumen_kk]|max_size[dokumen_kk,2048]|ext_in[dokumen_kk,pdf,jpg,jpeg,png]',
            'dokumen_slip_gaji' => 'uploaded[dokumen_slip_gaji]|max_size[dokumen_slip_gaji,2048]|ext_in[dokumen_slip_gaji,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle file uploads
        $uploadPath = WRITEPATH . 'uploads/anggota';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $ktpFile = $this->request->getFile('dokumen_ktp');
        $kkFile = $this->request->getFile('dokumen_kk');
        $slipGajiFile = $this->request->getFile('dokumen_slip_gaji');

        $ktpName = $ktpFile->getRandomName();
        $kkName = $kkFile->getRandomName();
        $slipGajiName = $slipGajiFile->getRandomName();

        $ktpFile->move($uploadPath, $ktpName);
        $kkFile->move($uploadPath, $kkName);
        $slipGajiFile->move($uploadPath, $slipGajiName);

        // Create anggota data
        $anggotaModel = new \App\Models\AnggotaModel();
        $anggotaData = [
            'nik' => $this->request->getPost('nik'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'alamat' => $this->request->getPost('alamat'),
            'pekerjaan' => $this->request->getPost('pekerjaan'),
            'tanggal_pendaftaran' => $this->request->getPost('tanggal_pendaftaran'),
            'status_keanggotaan' => 'Aktif',
            'dokumen_ktp' => $ktpName,
            'dokumen_kk' => $kkName,
            'dokumen_slip_gaji' => $slipGajiName,
        ];

        $anggotaModel->save($anggotaData);
        $anggotaId = $anggotaModel->getInsertID();

        // Update user to reference anggota
        $this->userModel->update($userId, ['id_anggota_ref' => $anggotaId]);

        // Update session with new id_anggota_ref
        session()->set('id_anggota_ref', $anggotaId);

        return redirect()->to('/home')->with('success', 'Data anggota berhasil dilengkapi. Selamat datang di sistem koperasi!');
    }

    /**
     * Update profile for anggota (includes both user and anggota data)
     */
    public function updateProfileAnggota()
    {
        $userId = session()->get('id_user');
        $userLevel = session()->get('level');
        
        // Only allow for users with level 'Anggota'
        if ($userLevel !== 'Anggota') {
            return redirect()->to('/home')->with('error', 'Akses ditolak.');
        }

        $user = $this->userModel->find($userId);
        if (!$user || !$user['id_anggota_ref']) {
            return redirect()->to('/profile/complete-anggota-data');
        }

        $rules = [
            'nama_lengkap' => 'required|max_length[255]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[tbl_users.username,id_user,' . $userId . ']',
            'email' => 'required|valid_email|is_unique[tbl_users.email,id_user,' . $userId . ']',
            'password' => 'permit_empty|min_length[6]',
            'no_hp' => 'permit_empty|max_length[20]',
            'nik' => 'required|min_length[16]|max_length[16]|is_unique[tbl_anggota.nik,id_anggota,' . $user['id_anggota_ref'] . ']',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|valid_date',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'dokumen_ktp' => 'max_size[dokumen_ktp,2048]|ext_in[dokumen_ktp,pdf,jpg,jpeg,png]',
            'dokumen_kk' => 'max_size[dokumen_kk,2048]|ext_in[dokumen_kk,pdf,jpg,jpeg,png]',
            'dokumen_slip_gaji' => 'max_size[dokumen_slip_gaji,2048]|ext_in[dokumen_slip_gaji,pdf,jpg,jpeg,png]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Update user data
        $userData = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
        ];

        if ($password = $this->request->getPost('password')) {
            $userData['password'] = $password;
        }

        $this->userModel->update($userId, $userData);

        // Update anggota data
        $anggotaModel = new \App\Models\AnggotaModel();
        $anggotaData = [
            'nik' => $this->request->getPost('nik'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'alamat' => $this->request->getPost('alamat'),
            'pekerjaan' => $this->request->getPost('pekerjaan'),
        ];

        // Handle file uploads if provided
        $uploadPath = WRITEPATH . 'uploads/anggota';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $anggota = $anggotaModel->find($user['id_anggota_ref']);

        if ($ktpFile = $this->request->getFile('dokumen_ktp')) {
            if ($ktpFile->isValid() && !$ktpFile->hasMoved()) {
                if (!empty($anggota['dokumen_ktp']) && file_exists($uploadPath . '/' . $anggota['dokumen_ktp'])) {
                    unlink($uploadPath . '/' . $anggota['dokumen_ktp']);
                }
                $ktpName = $ktpFile->getRandomName();
                $ktpFile->move($uploadPath, $ktpName);
                $anggotaData['dokumen_ktp'] = $ktpName;
            }
        }

        if ($kkFile = $this->request->getFile('dokumen_kk')) {
            if ($kkFile->isValid() && !$kkFile->hasMoved()) {
                if (!empty($anggota['dokumen_kk']) && file_exists($uploadPath . '/' . $anggota['dokumen_kk'])) {
                    unlink($uploadPath . '/' . $anggota['dokumen_kk']);
                }
                $kkName = $kkFile->getRandomName();
                $kkFile->move($uploadPath, $kkName);
                $anggotaData['dokumen_kk'] = $kkName;
            }
        }

        if ($slipGajiFile = $this->request->getFile('dokumen_slip_gaji')) {
            if ($slipGajiFile->isValid() && !$slipGajiFile->hasMoved()) {
                if (!empty($anggota['dokumen_slip_gaji']) && file_exists($uploadPath . '/' . $anggota['dokumen_slip_gaji'])) {
                    unlink($uploadPath . '/' . $anggota['dokumen_slip_gaji']);
                }
                $slipGajiName = $slipGajiFile->getRandomName();
                $slipGajiFile->move($uploadPath, $slipGajiName);
                $anggotaData['dokumen_slip_gaji'] = $slipGajiName;
            }
        }

        $anggotaModel->update($user['id_anggota_ref'], $anggotaData);

        return redirect()->to('/profile')->with('success', 'Profile dan data anggota berhasil diperbarui.');
    }
}
