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
    }

    /**
     * Menampilkan daftar laporan kredit
     */
    public function index()
    {
        // Get kredit data with anggota information
        $kredits = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap as nama_anggota, tbl_anggota.nik')
            ->join('tbl_anggota', 'tbl_anggota.id_anggota = tbl_kredit.id_anggota', 'left')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota', 'left')
            ->findAll();

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
        $kredit = $this->kreditModel->find($id_kredit);
        
        if (!$kredit) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $angsurans = $this->angsuranModel->where('id_kredit', $id_kredit)->findAll();
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
        $kredit = $this->kreditModel->find($id_kredit);
        
        if (!$kredit) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $angsurans = $this->angsuranModel->where('id_kredit', $id_kredit)->findAll();
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
            'angsuranLunas' => $angsuranLunas,
            'totalAngsuran' => count($angsurans)
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

        // Get summary statistics
        $totalKredit = $this->kreditModel->countAll();
        $kreditDisetujui = $this->kreditModel->where('status_kredit', 'Disetujui')->countAllResults();
        $kreditDitolak = $this->kreditModel->where('status_kredit', 'Ditolak')->countAllResults();
        $kreditMenunggu = $this->kreditModel->where('status_kredit', 'Diajukan')->countAllResults();

        // Get recent applications
        $recentKredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->orderBy('tbl_kredit.created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

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

        // Get all credit data with member info
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->orderBy('tbl_kredit.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $data['kredit'] = $kredit;

        return view('laporan_kredit/pdf_koperasi', $data);
    }

    /**
     * Export Excel untuk laporan koperasi
     */
    public function exportExcelKoperasi()
    {
        session()->setFlashdata('info', 'Fitur export Excel dalam pengembangan');
        return redirect()->to('/laporan-kredit-koperasi');
    }
}