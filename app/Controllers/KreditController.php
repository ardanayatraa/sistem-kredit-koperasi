<?php


namespace App\Controllers;

use App\Models\KreditModel;
use App\Models\AnggotaModel;
use CodeIgniter\Controller;

class KreditController extends Controller
{
    protected $kreditModel;
    protected $anggotaModel;

    public function __construct()
    {
        $this->kreditModel = new KreditModel();
        $this->anggotaModel = new AnggotaModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['kredit'] = $this->kreditModel->findAll();
        
        // Get anggota data for each kredit to avoid AJAX calls
        $anggotaData = [];
        foreach ($data['kredit'] as $kredit) {
            if (!isset($anggotaData[$kredit['id_anggota']])) {
                $anggota = $this->anggotaModel->find($kredit['id_anggota']);
                if ($anggota) {
                    // Add kredit-specific data to anggota data
                    $anggotaData[$kredit['id_anggota']] = array_merge($anggota, [
                        'kredit_jumlah' => $kredit['jumlah_pengajuan'],
                        'kredit_jangka_waktu' => $kredit['jangka_waktu'],
                        'kredit_status' => $kredit['status_kredit'],
                    ]);
                }
            }
        }
        $data['anggotaData'] = $anggotaData;
        
        return view('kredit/index', $data);
    }

    public function new()
    {
        $session = session();
        $data = [];
        
        // If user is 'Anggota' level, auto-fill id_anggota from session
        if ($session->get('level') === 'Anggota') {
            $data['userAnggotaId'] = $session->get('id_anggota_ref');
        }
        
        return view('kredit/form', $data);
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

    public function toggleStatus($id = null)
    {
        $kredit = $this->kreditModel->find($id);
        if (empty($kredit)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data kredit tidak ditemukan.'
            ])->setStatusCode(404);
        }

        // Toggle status_aktif between Aktif and Tidak Aktif
        $currentStatus = $kredit['status_aktif'] ?? 'Aktif';
        $newStatus = ($currentStatus === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        $this->kreditModel->update($id, ['status_aktif' => $newStatus]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status kredit berhasil diubah menjadi ' . $newStatus,
            'new_status' => $newStatus
        ]);
    }

    public function verifyAgunan()
    {
        // Only allow Appraiser role to access this method
        $currentUserLevel = session()->get('level');
        if ($currentUserLevel !== 'Appraiser') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hanya Appraiser yang dapat melakukan verifikasi agunan.'
            ])->setStatusCode(403);
        }

        // Get JSON data from request
        $jsonData = $this->request->getJSON();
        
        if (!$jsonData) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak valid.'
            ])->setStatusCode(400);
        }

        $idKredit = $jsonData->id_kredit;
        $catatanAppraiser = $jsonData->catatan_appraiser;

        if (!$idKredit) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID kredit tidak ditemukan.'
            ])->setStatusCode(400);
        }

        // Find the kredit record
        $kredit = $this->kreditModel->find($idKredit);
        if (!$kredit) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data kredit tidak ditemukan.'
            ])->setStatusCode(404);
        }

        // Update the catatan_appraiser field
        $updateData = [
            'catatan_appraiser' => $catatanAppraiser
        ];

        if ($this->kreditModel->update($idKredit, $updateData)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Verifikasi agunan berhasil disimpan. Catatan penilai telah diperbarui.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan verifikasi agunan.'
            ])->setStatusCode(500);
        }
    }

}
