<?php

namespace App\Controllers;

use App\Models\KreditModel;
use App\Models\AnggotaModel;

class RiwayatPersetujuanController extends BaseController
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
            'title' => 'Riwayat Persetujuan',
            'headerTitle' => 'Riwayat Persetujuan - Ketua Koperasi'
        ];

        // Menampilkan semua kredit yang sudah diproses (disetujui atau ditolak)
        $riwayat = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->where('tbl_kredit.catatan_ketua IS NOT NULL')
            ->orderBy('tbl_kredit.updated_at', 'DESC')
            ->paginate(10);

        $data['riwayat'] = $riwayat;
        $data['pager'] = $this->kreditModel->pager;

        return view('riwayat_persetujuan/index', $data);
    }

    public function show($id)
    {
        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota, tbl_anggota.alamat, tbl_anggota.no_hp')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->find($id);

        if (!$kredit) {
            session()->setFlashdata('error', 'Data tidak ditemukan');
            return redirect()->to('/riwayat-persetujuan');
        }

        $data = [
            'title' => 'Detail Riwayat Persetujuan',
            'headerTitle' => 'Detail Riwayat Persetujuan',
            'kredit' => $kredit
        ];

        return view('riwayat_persetujuan/show', $data);
    }
}