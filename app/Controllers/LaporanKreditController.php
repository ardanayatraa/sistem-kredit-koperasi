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
        $data = [
            'title' => 'Laporan Kredit',
            'headerTitle' => 'Laporan Kredit',
            'kredits' => $this->kreditModel->findAll()
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

        // Hitung total angsuran dibayar
        $totalDibayar = 0;
        foreach ($angsurans as $angsuran) {
            if ($angsuran['status_pembayaran'] === 'lunas') {
                $totalDibayar += $angsuran['jumlah_angsuran'];
            }
        }

        $data = [
            'title' => 'Detail Laporan Kredit',
            'headerTitle' => 'Detail Laporan Kredit',
            'kredit' => $kredit,
            'anggota' => $anggota,
            'angsurans' => $angsurans,
            'totalDibayar' => $totalDibayar,
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
}