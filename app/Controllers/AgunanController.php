<?php

namespace App\Controllers;

use App\Models\KreditModel;
use App\Models\AnggotaModel;

class AgunanController extends BaseController
{
    protected $kreditModel;
    protected $anggotaModel;

    public function __construct()
    {
        $this->kreditModel = new KreditModel();
        $this->anggotaModel = new AnggotaModel();
        helper('data_filter');
    }

    public function index()
    {
        $data = [
            'title' => 'Data Agunan',
            'headerTitle' => 'Data Agunan'
        ];

        // Use filtered method to get kredits with agunan
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik';
        $additionalWhere = ['tbl_kredit.jenis_agunan IS NOT NULL' => null];
        
        $data['agunan'] = $this->kreditModel->getFilteredKreditsWithData($additionalWhere, $select);
        $data['pager'] = $this->kreditModel->pager;

        return view('agunan/index', $data);
    }

    public function daftarAgunan()
    {
        $data = [
            'title' => 'Daftar Agunan',
            'headerTitle' => 'Daftar Agunan - Appraiser'
        ];

        // Use filtered method (without pagination untuk sementara)
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik';
        $additionalWhere = ['tbl_kredit.jenis_agunan IS NOT NULL' => null];
        
        $data['agunan'] = $this->kreditModel->getFilteredKreditsWithData($additionalWhere, $select);
        
        // Create dummy pager untuk menghindari error
        $data['pager'] = null;

        return view('agunan/daftar_agunan', $data);
    }

    public function verifikasi()
    {
        $data = [
            'title' => 'Verifikasi Agunan',
            'headerTitle' => 'Verifikasi Agunan'
        ];

        // Use filtered method for agunan verification (exclude already approved)
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik, tbl_anggota.no_anggota';
        $additionalWhere = [
            'tbl_kredit.jenis_agunan IS NOT NULL' => null,
            'tbl_kredit.jenis_agunan !=' => '',
            'tbl_kredit.status_kredit !=' => 'Disetujui'
        ];
        
        $agunan = $this->kreditModel->getFilteredKreditsWithData($additionalWhere, $select);

        // Simple debug - log count hasil
        log_message('debug', 'Verifikasi Agunan - Total found: ' . count($agunan));

        $data['agunan'] = $agunan;
        $data['pager'] = $this->kreditModel->pager;

        return view('verifikasi_agunan/index', $data);
    }

    public function show($id)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->back();
        }

        // Get additional anggota data if needed
        $anggota = $this->anggotaModel
            ->select('tbl_anggota.*, tbl_users.nama_lengkap, tbl_users.no_hp')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->find($kredit['id_anggota']);

        $data = [
            'title' => 'Detail Agunan',
            'headerTitle' => 'Detail Agunan',
            'kredit' => array_merge($kredit, $anggota ? $anggota : [])
        ];

        return view('agunan/show', $data);
    }

    public function detailVerifikasi($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik, tbl_anggota.alamat, tbl_users.no_hp')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_anggota.id_anggota = tbl_users.id_anggota_ref')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/verifikasi-agunan');
        }

        $data = [
            'title' => 'Detail Verifikasi Agunan',
            'headerTitle' => 'Detail Verifikasi Agunan',
            'kredit' => $kredit
        ];

        return view('agunan/detail_verifikasi', $data);
    }

    public function approve($id)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id);
        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/verifikasi-agunan');
        }

        $catatanAppraiser = $this->request->getPost('catatan_appraiser');
        $nilaiAgunan = $this->request->getPost('nilai_agunan'); // Process nilai_agunan from form

        // Debug log
        log_message('debug', 'Approve Agunan - ID: ' . $id);
        log_message('debug', 'Status sebelum: ' . ($kredit['status_kredit'] ?? 'NULL'));
        log_message('debug', 'Catatan Appraiser: ' . ($catatanAppraiser ?? 'NULL'));
        log_message('debug', 'Nilai Agunan: ' . ($nilaiAgunan ?? 'NULL'));

        $updateData = [
            'status_kredit' => 'Disetujui', // Set status to Disetujui when approved
            'catatan_appraiser' => $catatanAppraiser, // Save appraiser notes
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Add nilai_taksiran_agunan if provided
        if (!empty($nilaiAgunan)) {
            $updateData['nilai_taksiran_agunan'] = $nilaiAgunan;
            log_message('debug', 'Adding nilai_taksiran_agunan: ' . $nilaiAgunan);
        }

        $result = $this->kreditModel->update($id, $updateData);
        log_message('debug', 'Update result: ' . ($result ? 'SUCCESS' : 'FAILED'));

        if ($result) {
            // Verify update worked
            $updatedKredit = $this->kreditModel->find($id);
            log_message('debug', 'Status setelah: ' . ($updatedKredit['status_kredit'] ?? 'NULL'));
            log_message('debug', 'Catatan tersimpan: ' . ($updatedKredit['catatan_appraiser'] ?? 'NULL'));
            log_message('debug', 'Nilai tersimpan: ' . ($updatedKredit['nilai_taksiran_agunan'] ?? 'NULL'));
            
            session()->setFlashdata('success', 'Agunan berhasil disetujui dengan nilai taksiran dan catatan');
        } else {
            session()->setFlashdata('error', 'Gagal menyetujui agunan');
        }

        return redirect()->to('/verifikasi-agunan');
    }

    public function reject($id)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id);
        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/verifikasi-agunan');
        }

        $catatan = $this->request->getPost('catatan_appraiser');

        // Debug log
        log_message('debug', 'Reject Agunan - ID: ' . $id);
        log_message('debug', 'Status sebelum: ' . ($kredit['status_kredit'] ?? 'NULL'));
        log_message('debug', 'Catatan Appraiser: ' . ($catatan ?? 'NULL'));

        $updateData = [
            'status_kredit' => 'Ditolak', // Set status to Ditolak when rejected
            'catatan_appraiser' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = $this->kreditModel->update($id, $updateData);
        log_message('debug', 'Update result: ' . ($result ? 'SUCCESS' : 'FAILED'));

        if ($result) {
            // Verify update worked
            $updatedKredit = $this->kreditModel->find($id);
            log_message('debug', 'Status setelah: ' . ($updatedKredit['status_kredit'] ?? 'NULL'));
            
            session()->setFlashdata('success', 'Agunan berhasil ditolak');
        } else {
            session()->setFlashdata('error', 'Gagal menolak agunan');
        }

        return redirect()->to('/verifikasi-agunan');
    }

    public function prosesVerifikasi()
    {
        $json = $this->request->getJSON();
        
        if (!$json) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request format'
            ])->setStatusCode(400);
        }

        $kreditId = $json->id_kredit;
        $statusVerifikasi = $json->status_verifikasi;

        // Debug log
        log_message('debug', 'Proses Verifikasi JSON - ID: ' . $kreditId . ', Status: ' . $statusVerifikasi);

        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($kreditId);
        if (!$kredit) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data kredit tidak ditemukan atau Anda tidak memiliki akses'
            ])->setStatusCode(404);
        }

        // Debug - status sebelum update
        log_message('debug', 'Status sebelum: ' . ($kredit['status_kredit'] ?? 'NULL'));

        $updateData = [
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle status changes properly
        if ($statusVerifikasi === 'verified') {
            $updateData['status_kredit'] = 'Disetujui'; // Set to Disetujui when verified
            log_message('debug', 'Will update status to: Disetujui');
        } elseif ($statusVerifikasi === 'rejected') {
            $updateData['status_kredit'] = 'Ditolak'; // Set to Ditolak when rejected
            log_message('debug', 'Will update status to: Ditolak');
        } else {
            log_message('debug', 'Status will remain unchanged for: ' . $statusVerifikasi);
        }

        $result = $this->kreditModel->update($kreditId, $updateData);
        log_message('debug', 'Update result: ' . ($result ? 'SUCCESS' : 'FAILED'));

        if ($result) {
            // Verify update worked
            $updatedKredit = $this->kreditModel->find($kreditId);
            log_message('debug', 'Status setelah: ' . ($updatedKredit['status_kredit'] ?? 'NULL'));
            
            $message = $statusVerifikasi === 'verified' ? 'Agunan berhasil disetujui' :
                      ($statusVerifikasi === 'rejected' ? 'Agunan berhasil ditolak' : 'Verifikasi berhasil disimpan');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => $message
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan verifikasi'
            ])->setStatusCode(500);
        }
    }

    public function dokumen($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_anggota.id_anggota = tbl_users.id_anggota_ref')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/verifikasi-agunan');
        }

        $data = [
            'title' => 'Dokumen Agunan',
            'headerTitle' => 'Dokumen Agunan',
            'kredit' => $kredit
        ];

        // For now, return a simple view showing available documents
        // This can be enhanced later to show actual uploaded documents
        return view('agunan/dokumen', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Tambah Data Agunan',
            'headerTitle' => 'Tambah Data Agunan'
        ];

        return view('agunan/form', $data);
    }

    public function create()
    {
        // Implementation for creating new agunan data if needed
        session()->setFlashdata('info', 'Fitur dalam pengembangan');
        return redirect()->to('/agunan');
    }

    public function edit($id)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id);
        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/agunan');
        }

        $data = [
            'title' => 'Edit Data Agunan',
            'headerTitle' => 'Edit Data Agunan',
            'kredit' => $kredit
        ];

        return view('agunan/form', $data);
    }

    public function update($id)
    {
        // Implementation for updating agunan data if needed
        session()->setFlashdata('info', 'Fitur dalam pengembangan');
        return redirect()->to('/agunan');
    }

    public function delete($id)
    {
        // Implementation for deleting agunan data if needed
        session()->setFlashdata('info', 'Fitur dalam pengembangan');
        return redirect()->to('/agunan');
    }

    public function print($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik, tbl_anggota.alamat, tbl_users.no_hp, tbl_anggota.id_anggota')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_anggota.id_anggota = tbl_users.id_anggota_ref')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $data = [
            'title' => 'Print Agunan',
            'kredit' => $kredit
        ];

        return view('agunan/print', $data);
    }

    public function nilai($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik, tbl_anggota.alamat, tbl_users.no_hp')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_anggota.id_anggota = tbl_users.id_anggota_ref')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/agunan');
        }

        $data = [
            'title' => 'Penilaian Nilai Agunan',
            'headerTitle' => 'Penilaian Nilai Agunan',
            'kredit' => $kredit
        ];

        return view('agunan/nilai', $data);
    }

    public function simpanNilai($id)
    {
        $kredit = $this->kreditModel->find($id);
        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/agunan');
        }

        $nilaiTaksiran = $this->request->getPost('nilai_taksiran');
        $catatanPenilaian = $this->request->getPost('catatan_penilaian');

        // Validasi input
        if (empty($nilaiTaksiran)) {
            session()->setFlashdata('error', 'Nilai taksiran harus diisi');
            return redirect()->back()->withInput();
        }

        $updateData = [
            'nilai_taksiran_agunan' => $nilaiTaksiran,
            'catatan_appraiser' => $catatanPenilaian,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->kreditModel->update($id, $updateData)) {
            session()->setFlashdata('success', 'Penilaian nilai agunan berhasil disimpan');
        } else {
            session()->setFlashdata('error', 'Gagal menyimpan penilaian nilai agunan');
        }

        return redirect()->to('/agunan');
    }

    /**
     * View document with access control
     */
    public function viewDocument($filename)
    {
        try {
            log_message('debug', 'AgunanController::viewDocument called with filename: ' . $filename);

            // Extract id_kredit from filename pattern: dokumen_agunan_YYYYMMDDHHMMSS_timestamp_hash.ext
            // Cari kredit yang memiliki dokumen ini
            $kredit = $this->kreditModel->like('dokumen_agunan', $filename)->first();
            log_message('debug', 'AgunanController::viewDocument - kredit result: ' . ($kredit ? 'FOUND (ID: ' . $kredit['id_kredit'] . ')' : 'NOT FOUND'));

            if (!$kredit) {
                log_message('error', 'AgunanController::viewDocument - Dokumen tidak ditemukan di database: ' . $filename);
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
            }

            log_message('debug', 'AgunanController::viewDocument - Kredit ditemukan: ' . json_encode($kredit));

            // Cek akses menggunakan sistem filtering yang sudah ada
            if (!canAccessData($kredit)) {
                log_message('error', 'AgunanController::viewDocument - Akses ditolak untuk user ID: ' . session()->get('id_user') . ', Level: ' . session()->get('level'));
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Anda tidak memiliki akses ke dokumen ini.');
            }

            log_message('debug', 'AgunanController::viewDocument - Akses disetujui');

            // Path ke file - dokumen agunan ada di root uploads atau subfolder dokumen_kredit
            $possiblePaths = [
                WRITEPATH . 'uploads/' . $filename,
                WRITEPATH . 'uploads/dokumen_kredit/' . $filename,
                WRITEPATH . 'uploads/agunan/' . $filename
            ];

            $filePath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $filePath = $path;
                    break;
                }
            }

            if (!$filePath) {
                log_message('error', 'AgunanController::viewDocument - File tidak ditemukan di semua path yang dicoba');
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan.');
            }

            log_message('debug', 'AgunanController::viewDocument - File path: ' . $filePath);

            // Serve file dengan content type yang tepat
            $mime = mime_content_type($filePath);
            log_message('debug', 'AgunanController::viewDocument - Serving file with MIME: ' . $mime . ', Size: ' . filesize($filePath) . ' bytes');

            return $this->response
                ->setHeader('Content-Type', $mime)
                ->setHeader('Content-Disposition', 'inline; filename="' . basename($filename) . '"')
                ->setBody(file_get_contents($filePath));

        } catch (\CodeIgniter\Exceptions\PageNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            log_message('error', 'AgunanController::viewDocument - Unexpected error: ' . $e->getMessage());
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Terjadi kesalahan saat mengakses dokumen.');
        }
    }

}
