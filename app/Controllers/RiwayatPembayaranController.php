<?php

namespace App\Controllers;

use App\Models\PembayaranAngsuranModel;
use App\Models\AngsuranModel;
use App\Models\KreditModel;
use App\Models\AnggotaModel;

class RiwayatPembayaranController extends BaseController
{
    protected $pembayaranModel;
    protected $angsuranModel;
    protected $kreditModel;
    protected $anggotaModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranAngsuranModel();
        $this->angsuranModel = new AngsuranModel();
        $this->kreditModel = new KreditModel();
        $this->anggotaModel = new AnggotaModel();
    }

    public function index()
    {
        $userId = session()->get('id_user');
        $anggotaId = session()->get('id_anggota_ref');

        $data = [
            'title' => 'Riwayat Pembayaran',
            'headerTitle' => 'Riwayat Pembayaran Saya'
        ];

        if (!$anggotaId) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan. Silakan lengkapi profil Anda terlebih dahulu.');
            return redirect()->to('/profile/complete-anggota-data');
        }

        // Menampilkan riwayat pembayaran anggota yang login
        $riwayat = $this->pembayaranModel
            ->select('tbl_pembayaran_angsuran.*, tbl_angsuran.angsuran_ke, tbl_kredit.jumlah_pengajuan, tbl_users.nama_lengkap')
            ->join('tbl_angsuran', 'tbl_pembayaran_angsuran.id_angsuran = tbl_angsuran.id_angsuran')
            ->join('tbl_kredit', 'tbl_angsuran.id_kredit_ref = tbl_kredit.id_kredit')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->where('tbl_anggota.id_anggota', $anggotaId)
            ->orderBy('tbl_pembayaran_angsuran.tanggal_bayar', 'DESC')
            ->paginate(10);

        $data['riwayat'] = $riwayat;
        $data['pager'] = $this->pembayaranModel->pager;

        return view('riwayat_pembayaran/index', $data);
    }

    public function show($id)
    {
        $userId = session()->get('id_user');
        $anggotaId = session()->get('id_anggota_ref');

        if (!$anggotaId) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan.');
            return redirect()->to('/riwayat-pembayaran');
        }

        $pembayaran = $this->pembayaranModel
            ->select('tbl_pembayaran_angsuran.*, tbl_angsuran.angsuran_ke, tbl_angsuran.jumlah_angsuran, tbl_kredit.jumlah_pengajuan, tbl_users.nama_lengkap, tbl_anggota.no_anggota')
            ->join('tbl_angsuran', 'tbl_pembayaran_angsuran.id_angsuran = tbl_angsuran.id_angsuran')
            ->join('tbl_kredit', 'tbl_angsuran.id_kredit_ref = tbl_kredit.id_kredit')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->where('tbl_pembayaran_angsuran.id_pembayaran', $id)
            ->where('tbl_anggota.id_anggota', $anggotaId)
            ->first();

        if (!$pembayaran) {
            session()->setFlashdata('error', 'Data tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/riwayat-pembayaran');
        }

        $data = [
            'title' => 'Detail Riwayat Pembayaran',
            'headerTitle' => 'Detail Riwayat Pembayaran',
            'pembayaran' => $pembayaran
        ];

        return view('riwayat_pembayaran/show', $data);
    }

    public function print($id)
    {
        $userId = session()->get('id_user');
        $anggotaId = session()->get('id_anggota_ref');

        if (!$anggotaId) {
            session()->setFlashdata('error', 'Data anggota tidak ditemukan.');
            return redirect()->to('/riwayat-pembayaran');
        }

        $pembayaran = $this->pembayaranModel
            ->select('tbl_pembayaran_angsuran.*, tbl_angsuran.angsuran_ke, tbl_angsuran.jumlah_angsuran, tbl_kredit.jumlah_pengajuan, tbl_users.nama_lengkap, tbl_anggota.no_anggota')
            ->join('tbl_angsuran', 'tbl_pembayaran_angsuran.id_angsuran = tbl_angsuran.id_angsuran')
            ->join('tbl_kredit', 'tbl_angsuran.id_kredit_ref = tbl_kredit.id_kredit')
            ->join('tbl_anggota', 'tbl_kredit.id_anggota = tbl_anggota.id_anggota')
            ->join('tbl_users', 'tbl_users.id_anggota_ref = tbl_anggota.id_anggota')
            ->where('tbl_pembayaran_angsuran.id_pembayaran', $id)
            ->where('tbl_anggota.id_anggota', $anggotaId)
            ->first();

        if (!$pembayaran) {
            session()->setFlashdata('error', 'Data tidak ditemukan atau Anda tidak memiliki akses');
            return redirect()->to('/riwayat-pembayaran');
        }

        $data = [
            'title' => 'Print Bukti Pembayaran',
            'pembayaran' => $pembayaran
        ];

        return view('riwayat_pembayaran/print', $data);
    }
}