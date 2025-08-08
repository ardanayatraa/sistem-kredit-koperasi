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
    }

    public function index()
    {
        $data = [
            'title' => 'Data Agunan',
            'headerTitle' => 'Data Agunan'
        ];

        // Menampilkan semua kredit yang memiliki agunan
        $agunan = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_anggota.id_anggota = tbl_users.id_anggota_ref')
            ->where('tbl_kredit.jenis_agunan IS NOT NULL')
            ->orderBy('tbl_kredit.created_at', 'DESC')
            ->paginate(10);

        $data['agunan'] = $agunan;
        $data['pager'] = $this->kreditModel->pager;

        return view('agunan/index', $data);
    }

    public function daftarAgunan()
    {
        $data = [
            'title' => 'Daftar Agunan',
            'headerTitle' => 'Daftar Agunan - Appraiser'
        ];

        // Untuk Appraiser - menampilkan agunan yang perlu diverifikasi
        $agunan = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_anggota.id_anggota = tbl_users.id_anggota_ref')
            ->where('tbl_kredit.jenis_agunan IS NOT NULL')
            ->orderBy('tbl_kredit.created_at', 'DESC')
            ->paginate(10);

        $data['agunan'] = $agunan;
        $data['pager'] = $this->kreditModel->pager;

        return view('agunan/daftar_agunan', $data);
    }

    public function verifikasi()
    {
        $data = [
            'title' => 'Verifikasi Agunan',
            'headerTitle' => 'Verifikasi Agunan'
        ];

        // Tampilkan semua KECUALI yang sudah "Disetujui"
        $agunan = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik, tbl_anggota.no_anggota')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_anggota.id_anggota = tbl_users.id_anggota_ref')
            ->where('tbl_kredit.jenis_agunan IS NOT NULL') // Hanya yang memiliki agunan
            ->where('tbl_kredit.jenis_agunan !=', '') // Pastikan jenis agunan tidak kosong
            ->where('tbl_kredit.status_kredit !=', 'Disetujui') // Kecuali yang sudah disetujui
            ->orderBy('tbl_kredit.created_at', 'DESC')
            ->paginate(10);

        // Simple debug - log count hasil
        log_message('debug', 'Verifikasi Agunan - Total found: ' . count($agunan));

        $data['agunan'] = $agunan;
        $data['pager'] = $this->kreditModel->pager;

        return view('verifikasi_agunan/index', $data);
    }

    public function show($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik, tbl_anggota.alamat, tbl_users.no_hp')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_anggota.id_anggota = tbl_users.id_anggota_ref')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $data = [
            'title' => 'Detail Agunan',
            'headerTitle' => 'Detail Agunan',
            'kredit' => $kredit
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
        $kredit = $this->kreditModel->find($id);
        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/verifikasi-agunan');
        }

        $catatanAppraiser = $this->request->getPost('catatan_appraiser');

        // Debug log
        log_message('debug', 'Approve Agunan - ID: ' . $id);
        log_message('debug', 'Status sebelum: ' . ($kredit['status_kredit'] ?? 'NULL'));
        log_message('debug', 'Catatan Appraiser: ' . ($catatanAppraiser ?? 'NULL'));

        $updateData = [
            'status_kredit' => 'Disetujui', // Ubah status ke Disetujui
            'catatan_appraiser' => $catatanAppraiser, // Simpan catatan appraiser
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = $this->kreditModel->update($id, $updateData);
        log_message('debug', 'Update result: ' . ($result ? 'SUCCESS' : 'FAILED'));

        if ($result) {
            // Verify update worked
            $updatedKredit = $this->kreditModel->find($id);
            log_message('debug', 'Status setelah: ' . ($updatedKredit['status_kredit'] ?? 'NULL'));
            log_message('debug', 'Catatan tersimpan: ' . ($updatedKredit['catatan_appraiser'] ?? 'NULL'));
            
            session()->setFlashdata('success', 'Agunan berhasil disetujui dengan catatan');
        } else {
            session()->setFlashdata('error', 'Gagal menyetujui agunan');
        }

        return redirect()->to('/verifikasi-agunan');
    }

    public function reject($id)
    {
        $kredit = $this->kreditModel->find($id);
        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/verifikasi-agunan');
        }

        $catatan = $this->request->getPost('catatan_appraiser');

        $updateData = [
            'catatan_appraiser' => $catatan,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->kreditModel->update($id, $updateData)) {
            session()->setFlashdata('success', 'Agunan ditolak');
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
        log_message('debug', 'Proses Verifikasi - ID: ' . $kreditId . ', Status: ' . $statusVerifikasi);

        $kredit = $this->kreditModel->find($kreditId);
        if (!$kredit) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data kredit tidak ditemukan'
            ])->setStatusCode(404);
        }

        // Debug - status sebelum update
        log_message('debug', 'Status sebelum: ' . ($kredit['status_kredit'] ?? 'NULL'));

        $updateData = [
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Hanya ubah ke "Disetujui" jika verified, kalau rejected tetap status lama
        if ($statusVerifikasi === 'verified') {
            $updateData['status_kredit'] = 'Disetujui'; // Langsung disetujui
            log_message('debug', 'Will update status to: Disetujui');
        } else {
            log_message('debug', 'Status will remain unchanged');
        }

        $result = $this->kreditModel->update($kreditId, $updateData);
        log_message('debug', 'Update result: ' . ($result ? 'SUCCESS' : 'FAILED'));

        if ($result) {
            // Verify update worked
            $updatedKredit = $this->kreditModel->find($kreditId);
            log_message('debug', 'Status setelah: ' . ($updatedKredit['status_kredit'] ?? 'NULL'));
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Verifikasi berhasil disimpan'
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
        $kredit = $this->kreditModel->find($id);
        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
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
}