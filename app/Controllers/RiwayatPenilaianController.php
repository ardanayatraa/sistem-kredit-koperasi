<?php

namespace App\Controllers;

use App\Models\KreditModel;
use App\Models\AnggotaModel;

class RiwayatPenilaianController extends BaseController
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
        $userLevel = session()->get('level');
        $data = [
            'title' => 'Riwayat Penilaian',
            'headerTitle' => 'Riwayat Penilaian'
        ];

        // Filter berdasarkan role
        if ($userLevel === 'Appraiser') {
            // Appraiser melihat semua penilaian yang sudah dilakukan
            $riwayat = $this->kreditModel
                ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik')
                ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
                ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
                ->where('tbl_kredit.catatan_appraiser IS NOT NULL')
                ->orderBy('tbl_kredit.updated_at', 'DESC')
                ->paginate(10);
        } else {
            // Bendahara melihat semua riwayat penilaian
            $riwayat = $this->kreditModel
                ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik')
                ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
                ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
                ->where('tbl_kredit.catatan_appraiser IS NOT NULL')
                ->orderBy('tbl_kredit.updated_at', 'DESC')
                ->paginate(10);
        }

        $data['riwayat'] = $riwayat;
        $data['pager'] = $this->kreditModel->pager;

        return view('riwayat_penilaian/index', $data);
    }

    public function show($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik, tbl_anggota.id_anggota, tbl_anggota.alamat, tbl_users.no_hp, tbl_anggota.pekerjaan')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/riwayat-penilaian');
        }

        $data = [
            'title' => 'Detail Riwayat Penilaian',
            'headerTitle' => 'Detail Riwayat Penilaian',
            'kredit' => $kredit
        ];

        return view('riwayat_penilaian/show', $data);
    }

    public function print($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.nik, tbl_anggota.id_anggota, tbl_anggota.alamat, tbl_users.no_hp, tbl_anggota.pekerjaan')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/riwayat-penilaian');
        }

        $data = [
            'title' => 'Print Riwayat Penilaian',
            'kredit' => $kredit
        ];

        return view('riwayat_penilaian/print', $data);
    }
}