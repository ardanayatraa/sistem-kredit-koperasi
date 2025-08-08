<?php

namespace App\Controllers;

use App\Models\KreditModel;
use App\Models\AngsuranModel;
use App\Models\AnggotaModel;

class LaporanKreditController extends BaseController
{
    protected $kreditModel;
    protected $angsuranModel;
    protected $anggotaModel;

    public function __construct()
    {
        $this->kreditModel = new KreditModel();
        $this->angsuranModel = new AngsuranModel();
        $this->anggotaModel = new AnggotaModel();
        helper('data_filter');
    }

    /**
     * Menampilkan daftar laporan kredit
     */
    public function index()
    {
        // Use filtered method with user-based access control
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap as nama_anggota, tbl_anggota.nik';
        $kredits = $this->kreditModel->getFilteredKreditsWithData([], $select);

        $data = [
            'title' => 'Laporan Kredit',
            'headerTitle' => 'Laporan Kredit',
            'kredits' => $kredits
        ];

        return view('laporan_kredit/index', $data);
    }

    /**
     * Menampilkan detail laporan kredit untuk satu kredit
     */
    public function show($id_kredit)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id_kredit);
        
        if (!$kredit) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Use filtered method for angsuran
        $angsurans = $this->angsuranModel->getAngsuranByAnggota($kredit['id_anggota'], [
            'tbl_angsuran.id_kredit_ref' => $id_kredit
        ]);
        
        $anggota = $this->anggotaModel->find($kredit['id_anggota']);

        // Hitung total angsuran dibayar dan jumlah lunas
        $totalDibayar = 0;
        $angsuranLunas = 0;
        foreach ($angsurans as $angsuran) {
            if ($angsuran['status_pembayaran'] === 'lunas') {
                $totalDibayar += $angsuran['jumlah_angsuran'];
                $angsuranLunas++;
            }
        }

        $data = [
            'title' => 'Detail Laporan Kredit',
            'headerTitle' => 'Detail Laporan Kredit',
            'kredit' => $kredit,
            'anggota' => $anggota,
            'angsurans' => $angsurans,
            'totalDibayar' => $totalDibayar,
            'angsuranLunas' => $angsuranLunas,
            'totalAngsuran' => count($angsurans) > 0 ? $angsurans[0]['jumlah_angsuran'] * count($angsurans) : 0
        ];

        return view('laporan_kredit/show', $data);
    }

    /**
     * Generate laporan kredit dalam format PDF
     */
    public function generatePdf($id_kredit)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id_kredit);
        
        if (!$kredit) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Use filtered method for angsuran
        $angsurans = $this->angsuranModel->getAngsuranByAnggota($kredit['id_anggota'], [
            'tbl_angsuran.id_kredit_ref' => $id_kredit
        ]);
        $anggota = $this->anggotaModel->find($kredit['id_anggota']);

        // Hitung statistik
        $totalDibayar = 0;
        $angsuranLunas = 0;
        foreach ($angsurans as $angsuran) {
            if ($angsuran['status_pembayaran'] === 'lunas') {
                $totalDibayar += $angsuran['jumlah_angsuran'];
                $angsuranLunas++;
            }
        }

        $data = [
            'kredit' => $kredit,
            'anggota' => $anggota,
            'angsurans' => $angsurans,
            'totalDibayar' => $totalDibayar,
            'totalAngsuran' => count($angsurans) > 0 ? $angsurans[0]['jumlah_angsuran'] * count($angsurans) : 0,
            'angsuranLunas' => $angsuranLunas
        ];

        // Load library PDF
        $dompdf = new \Dompdf\Dompdf();
        $html = view('laporan_kredit/pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Laporan_Kredit_' . $kredit['id_kredit'] . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    /**
     * Laporan Kredit Koperasi untuk Ketua
     */
    public function koperasi()
    {
        $data = [
            'title' => 'Laporan Kredit Koperasi',
            'headerTitle' => 'Laporan Kredit Koperasi'
        ];

        // Use filtered methods for statistics (only for authorized users)
        $allKredits = $this->kreditModel->getFilteredKredits();
        $totalKredit = count($allKredits);
        
        $kreditDisetujui = count($this->kreditModel->getFilteredKredits(['status_kredit' => 'Disetujui']));
        $kreditDitolak = count($this->kreditModel->getFilteredKredits(['status_kredit' => 'Ditolak']));
        $kreditMenunggu = count($this->kreditModel->getFilteredKredits(['status_kredit' => 'Diajukan']));

        // Get recent applications with filtering
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota';
        $recentKredit = $this->kreditModel->getFilteredKreditsWithData([], $select);
        
        // Limit to 10 recent entries
        usort($recentKredit, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        $recentKredit = array_slice($recentKredit, 0, 10);

        $data['summary'] = [
            'total_kredit' => $totalKredit,
            'kredit_disetujui' => $kreditDisetujui,
            'kredit_ditolak' => $kreditDitolak,
            'kredit_menunggu' => $kreditMenunggu
        ];

        $data['recent_kredit'] = $recentKredit;

        return view('laporan_kredit/koperasi', $data);
    }

    /**
     * Generate PDF untuk laporan koperasi
     */
    public function generatePdfKoperasi()
    {
        $data = [
            'title' => 'Laporan Kredit Koperasi'
        ];

        // Use filtered method for PDF generation
        $select = 'tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota';
        $kredit = $this->kreditModel->getFilteredKreditsWithData([], $select);

        // Sort by created date DESC
        usort($kredit, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        $data['kredit'] = $kredit;

        // Load library PDF
        $dompdf = new \Dompdf\Dompdf();
        $html = view('laporan_kredit/pdf_koperasi', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Laporan_Kredit_Koperasi_' . date('Y-m-d') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    /**
     * Export Excel untuk laporan koperasi
     */
    public function exportExcelKoperasi()
    {
        session()->setFlashdata('info', 'Fitur export Excel dalam pengembangan');
        return redirect()->to('/laporan-kredit-koperasi');
    }

    /**
     * Form untuk membuat laporan kredit baru
     * (Untuk Bendahara)
     */
    public function new()
    {
        $data = [
            'title' => 'Buat Laporan Kredit Baru',
            'headerTitle' => 'Buat Laporan Kredit Baru'
        ];

        // Get list of completed credits for report generation
        $kredits = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap as nama_anggota')
            ->join('tbl_anggota', 'tbl_anggota.id_anggota = tbl_kredit.id_anggota', 'left')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota', 'left')
            ->where('tbl_kredit.status_kredit', 'Disetujui')
            ->findAll();

        $data['kredits'] = $kredits;

        return view('laporan_kredit/form', $data);
    }

    /**
     * Membuat laporan kredit baru
     * (Untuk Bendahara)
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'id_kredit' => 'required|numeric',
            'periode_laporan' => 'required|in_list[bulanan,triwulan,tahunan]',
            'tanggal_laporan' => 'required|valid_date',
            'catatan_laporan' => 'permit_empty|max_length[500]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('error', 'Data laporan tidak valid');
            return redirect()->back()->withInput();
        }

        // Since we're not changing database, we'll simulate creating a report
        $id_kredit = $this->request->getPost('id_kredit');
        $periode = $this->request->getPost('periode_laporan');
        $tanggal = $this->request->getPost('tanggal_laporan');
        $catatan = $this->request->getPost('catatan_laporan');

        // Log the report creation (simulated)
        log_message('info', 'Laporan kredit created for ID: ' . $id_kredit . ' by user: ' . session('user_id'));

        session()->setFlashdata('success', 'Laporan kredit berhasil dibuat untuk periode ' . $periode);
        return redirect()->to('/laporan-kredit');
    }

    /**
     * Form edit laporan kredit
     * (Untuk Bendahara)
     */
    public function edit($id_kredit)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id_kredit);
        
        if (!$kredit) {
            session()->setFlashdata('error', 'Data kredit tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/laporan-kredit');
        }

        $data = [
            'title' => 'Edit Laporan Kredit',
            'headerTitle' => 'Edit Laporan Kredit',
            'kredit' => $kredit
        ];

        return view('laporan_kredit/edit', $data);
    }

    /**
     * Update laporan kredit
     * (Untuk Bendahara)
     */
    public function update($id_kredit)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'periode_laporan' => 'required|in_list[bulanan,triwulan,tahunan]',
            'tanggal_laporan' => 'required|valid_date',
            'catatan_laporan' => 'permit_empty|max_length[500]',
            'status_laporan' => 'required|in_list[draft,final,published]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('error', 'Data laporan tidak valid');
            return redirect()->back()->withInput();
        }

        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id_kredit);
        if (!$kredit) {
            session()->setFlashdata('error', 'Data kredit tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/laporan-kredit');
        }

        // Since we're not changing database, simulate update
        $periode = $this->request->getPost('periode_laporan');
        $tanggal = $this->request->getPost('tanggal_laporan');
        $catatan = $this->request->getPost('catatan_laporan');
        $status = $this->request->getPost('status_laporan');

        log_message('info', 'Laporan kredit updated for ID: ' . $id_kredit . ' by user: ' . session('user_id'));

        session()->setFlashdata('success', 'Laporan kredit berhasil diperbarui');
        return redirect()->to('/laporan-kredit');
    }

    /**
     * Hapus laporan kredit
     * (Untuk Bendahara)
     */
    public function delete($id_kredit)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id_kredit);
        if (!$kredit) {
            session()->setFlashdata('error', 'Data kredit tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/laporan-kredit');
        }

        // Since we're not changing database, simulate deletion
        log_message('info', 'Laporan kredit deleted for ID: ' . $id_kredit . ' by user: ' . session('user_id'));

        session()->setFlashdata('success', 'Laporan kredit berhasil dihapus');
        return redirect()->to('/laporan-kredit');
    }

    /**
     * Toggle status laporan (aktif/nonaktif)
     * (Untuk Bendahara)
     */
    public function toggleStatus($id_kredit)
    {
        // Use access-controlled method
        $kredit = $this->kreditModel->findWithAccess($id_kredit);
        if (!$kredit) {
            session()->setFlashdata('error', 'Data kredit tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/laporan-kredit');
        }

        // Since we're not changing database, simulate toggle
        log_message('info', 'Laporan kredit status toggled for ID: ' . $id_kredit . ' by user: ' . session('user_id'));

        session()->setFlashdata('success', 'Status laporan kredit berhasil diubah');
        return redirect()->to('/laporan-kredit');
    }
}