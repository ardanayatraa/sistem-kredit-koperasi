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
    }

    public function index()
    {
        $userId = session()->get('id_user');
        $anggotaId = session()->get('id_anggota_ref');

        $data = [
            'title' => 'Riwayat Kredit',
            'headerTitle' => 'Riwayat Kredit Saya'
        ];

        if (!$anggotaId) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan. Silakan lengkapi profil Anda terlebih dahulu.');
            return redirect()->to('/profile/complete-anggota-data');
        }

        // Menampilkan riwayat kredit anggota yang login
        $riwayat = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->where('tbl_kredit.id_anggota', $anggotaId)
            ->orderBy('tbl_kredit.created_at', 'DESC')
            ->paginate(10);

        $data['riwayat'] = $riwayat;
        $data['pager'] = $this->kreditModel->pager;

        return view('riwayat_kredit/index', $data);
    }

    public function show($id)
    {
        $userId = session()->get('id_user');
        $anggotaId = session()->get('id_anggota_ref');

        if (!$anggotaId) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan.');
            return redirect()->to('/riwayat-kredit');
        }

        $kredit = $this->kreditModel
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota, tbl_anggota.alamat, tbl_anggota.no_hp')
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
            'title' => 'Detail Riwayat Kredit',
            'headerTitle' => 'Detail Riwayat Kredit',
            'kredit' => $kredit
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
            ->select('tbl_kredit.*, tbl_users.nama_lengkap, tbl_anggota.no_anggota, tbl_anggota.alamat, tbl_anggota.no_hp')
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
}