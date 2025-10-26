<?php

namespace App\Controllers;

use App\Models\KreditModel;
use App\Models\AnggotaModel;

class RiwayatKreditController extends BaseController
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
        $anggotaId = getCurrentUserAnggotaId();

        $data = [
            'title' => 'Riwayat Kredit',
            'headerTitle' => 'Riwayat Kredit Saya'
        ];

        if (!$anggotaId) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan. Silakan lengkapi profil Anda terlebih dahulu.');
            return redirect()->to('/profile/complete-anggota-data');
        }

        // Use filtered method - will automatically filter based on user scope
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota';
        $riwayat = $this->kreditModel->getFilteredKreditsWithData([], $select);

        // Sort by created_at DESC (data terbaru di atas)
        usort($riwayat, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        $data['riwayat'] = $riwayat;
        $data['pager'] = $this->kreditModel->pager;

        return view('riwayat_kredit/index', $data);
    }

    public function show($id)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/riwayat-kredit');
        }

        // Get additional anggota data
        $anggota = $this->anggotaModel
            ->select('tbl_anggota.*, tbl_users.nama_lengkap, tbl_users.no_hp')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->find($kredit['id_anggota']);

        $data = [
            'title' => 'Detail Riwayat Kredit',
            'headerTitle' => 'Detail Riwayat Kredit',
            'kredit' => array_merge($kredit, $anggota ? $anggota : [])
        ];

        return view('riwayat_kredit/show', $data);
    }

    public function print($id)
    {
        $userId = session()->get('id_user');
        $anggotaId = session()->get('id_anggota_ref');

        if (!$anggotaId) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan.');
            return redirect()->to('/riwayat-kredit');
        }

        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_users.no_hp, tbl_anggota.no_anggota, tbl_anggota.nik')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->where('tbl_kredit.id_kredit', $id)
            ->where('tbl_kredit.id_anggota', $anggotaId)
            ->first();

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/riwayat-kredit');
        }

        $data = [
            'title' => 'Print Riwayat Kredit',
            'kredit' => $kredit
        ];

        return view('riwayat_kredit/print', $data);
    }

    /**
     * Download/View Surat Persetujuan Kredit
     * (Khusus untuk kredit yang sudah disetujui)
     */
    public function suratPersetujuan($id)
    {
        $userId = session()->get('id_user');
        $anggotaId = session()->get('id_anggota_ref');

        if (!$anggotaId) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan.');
            return redirect()->to('/riwayat-kredit');
        }

        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_users.no_hp,
                     tbl_anggota.no_anggota, tbl_anggota.nik')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->where('tbl_kredit.id_kredit', $id)
            ->where('tbl_kredit.id_anggota', $anggotaId)
            ->first();

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/riwayat-kredit');
        }

        // Hanya kredit yang sudah disetujui yang bisa download surat persetujuan
        if ($kredit['status_kredit'] !== 'Disetujui') {
            session()->setFlashdata('error', 'Surat persetujuan hanya tersedia untuk kredit yang sudah disetujui');
            return redirect()->to('/riwayat-kredit');
        }

        $data = [
            'title' => 'Surat Persetujuan Kredit',
            'kredit' => $kredit,
            'tanggal_surat' => date('d F Y'),
            'nomor_surat' => 'SP/' . str_pad($kredit['id_kredit'], 4, '0', STR_PAD_LEFT) . '/' . date('Y')
        ];

        return view('riwayat_kredit/surat_persetujuan', $data);
    }

    /**
     * Download PDF Surat Persetujuan
     */
    public function downloadSuratPersetujuan($id)
    {
        $userId = session()->get('id_user');
        $anggotaId = session()->get('id_anggota_ref');

        if (!$anggotaId) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan.');
            return redirect()->to('/riwayat-kredit');
        }

        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_users.no_hp,
                     tbl_anggota.no_anggota, tbl_anggota.nik')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->where('tbl_kredit.id_kredit', $id)
            ->where('tbl_kredit.id_anggota', $anggotaId)
            ->first();

        if (!$kredit || $kredit['status_kredit'] !== 'Disetujui') {
            session()->setFlashdata('error', 'Surat persetujuan tidak tersedia');
            return redirect()->to('/riwayat-kredit');
        }

        $data = [
            'title' => 'Surat Persetujuan Kredit',
            'kredit' => $kredit,
            'tanggal_surat' => date('d F Y'),
            'nomor_surat' => 'SP/' . str_pad($kredit['id_kredit'], 4, '0', STR_PAD_LEFT) . '/' . date('Y')
        ];

        try {
            $dompdf = new \Dompdf\Dompdf();
            $html = view('riwayat_kredit/pdf_surat_persetujuan', $data);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'Surat_Persetujuan_' . $kredit['no_anggota'] . '_' . date('Y-m-d') . '.pdf';
            $dompdf->stream($filename, ['Attachment' => true]);
        } catch (\Exception $e) {
            log_message('error', 'PDF generation failed: ' . $e->getMessage());
            session()->setFlashdata('info', 'Download surat persetujuan berhasil (menggunakan tampilan HTML)');
            return $this->suratPersetujuan($id);
        }
    }

    /**
     * Cek status persetujuan kredit (untuk AJAX)
     */
    public function cekStatusPersetujuan($id)
    {
        // Use access-controlled method for JSON response
        $kredit = $this->kreditModel->findWithAccess($id);

        if (!$kredit) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data kredit tidak ditemukan atau Anda tidak memiliki akses'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'status' => $kredit['status_kredit'],
            'updated_at' => $kredit['updated_at'],
            'surat_tersedia' => ($kredit['status_kredit'] === 'Disetujui')
        ]);
    }

    /**
     * Index untuk Bendahara - Kelola Riwayat Kredit
     */
    public function indexBendahara()
    {
        $data = [
            'title' => 'Kelola Riwayat Kredit',
            'headerTitle' => 'Kelola Riwayat Kredit'
        ];

        // Get all kredit data for bendahara (no filtering by user)
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota';
        $riwayat = $this->kreditModel->getFilteredKreditsWithData([], $select);

        // Sort by created_at DESC (data terbaru di atas)
        usort($riwayat, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        $data['riwayat'] = $riwayat;
        $data['pager'] = $this->kreditModel->pager;

        return view('riwayat_kredit/index_bendahara', $data);
    }

    /**
     * Show detail kredit untuk Bendahara
     */
    public function showBendahara($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_users.no_hp, tbl_anggota.no_anggota, tbl_anggota.nik')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data kredit tidak ditemukan');
            return redirect()->to('/bendahara/riwayat-kredit');
        }

        $data = [
            'title' => 'Detail Riwayat Kredit',
            'headerTitle' => 'Detail Riwayat Kredit',
            'kredit' => $kredit
        ];

        return view('riwayat_kredit/show_bendahara', $data);
    }

    /**
     * Print detail kredit untuk Bendahara
     */
    public function printBendahara($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_users.no_hp, tbl_anggota.no_anggota, tbl_anggota.nik')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data kredit tidak ditemukan');
            return redirect()->to('/bendahara/riwayat-kredit');
        }

        $data = [
            'title' => 'Print Riwayat Kredit',
            'kredit' => $kredit
        ];

        return view('riwayat_kredit/print_bendahara', $data);
    }
}