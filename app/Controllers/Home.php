<?php

namespace App\Controllers;

use App\Models\KreditModel;
use App\Models\AnggotaModel;
use App\Models\PencairanModel;
use App\Models\AngsuranModel;
use App\Models\PembayaranAngsuranModel;

class Home extends BaseController
{
    protected $kreditModel;
    protected $anggotaModel;
    protected $pencairanModel;
    protected $angsuranModel;
    protected $pembayaranAngsuranModel;

    public function __construct()
    {
        $this->kreditModel = new KreditModel();
        $this->anggotaModel = new AnggotaModel();
        $this->pencairanModel = new PencairanModel();
        $this->angsuranModel = new AngsuranModel();
        $this->pembayaranAngsuranModel = new PembayaranAngsuranModel();
    }

    public function index()
    {
        $userLevel = session()->get('level');
        $namaLengkap = session()->get('nama_lengkap');

        // Get dynamic statistics based on user role
        $stats = $this->getRoleBasedStats($userLevel);

        // Role-specific configurations
        $roleConfigs = [
            'Bendahara' => [
                'theme' => 'blue',
                'accent' => 'bg-blue-600',
                'accent_hover' => 'hover:bg-blue-700',
                'text_accent' => 'text-blue-400',
                'border_accent' => 'border-blue-500',
                'card_bg' => 'bg-gradient-to-br from-blue-50 to-blue-100',
                'sidebar_accent' => 'from-blue-600 to-blue-800',
                'title' => 'Bendahara - Dashboard Keuangan',
                'description' => 'Kelola keuangan koperasi dan transaksi',
                'stats' => $stats['bendahara'],
                'quick_actions' => [
                    ['title' => 'Kredit', 'url' => '/kredit', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    ['title' => 'Pencairan', 'url' => '/pencairan', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z'],
                    ['title' => 'Angsuran', 'url' => '/angsuran', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['title' => 'Pembayaran', 'url' => '/pembayaran-angsuran', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1']
                ]
            ],
            'Ketua Koperasi' => [
                'theme' => 'purple',
                'accent' => 'bg-purple-600',
                'accent_hover' => 'hover:bg-purple-700',
                'text_accent' => 'text-purple-400',
                'border_accent' => 'border-purple-500',
                'card_bg' => 'bg-gradient-to-br from-purple-50 to-purple-100',
                'sidebar_accent' => 'from-purple-600 to-purple-800',
                'title' => 'Ketua Koperasi - Dashboard Manajemen',
                'description' => 'Panduan strategis dan pengambilan keputusan',
                'stats' => $stats['ketua'],
                'quick_actions' => [
                    ['title' => 'Anggota', 'url' => '/anggota', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z'],
                    ['title' => 'Kredit', 'url' => '/kredit', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    ['title' => 'Laporan', 'url' => '/laporan-kredit', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['title' => 'Bunga', 'url' => '/bunga', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6']
                ]
            ],
            'Appraiser' => [
                'theme' => 'green',
                'accent' => 'bg-green-600',
                'accent_hover' => 'hover:bg-green-700',
                'text_accent' => 'text-green-400',
                'border_accent' => 'border-green-500',
                'card_bg' => 'bg-gradient-to-br from-green-50 to-green-100',
                'sidebar_accent' => 'from-green-600 to-green-800',
                'title' => 'Appraiser - Dashboard Verifikasi',
                'description' => 'Verifikasi dan penilaian agunan kredit',
                'stats' => $stats['appraiser'],
                'quick_actions' => [
                    ['title' => 'Kredit', 'url' => '/kredit', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    ['title' => 'Dokumen', 'url' => '#', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['title' => 'Verifikasi', 'url' => '#', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['title' => 'Laporan', 'url' => '/laporan-kredit', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z']
                ]
            ],
            'Anggota' => [
                'theme' => 'orange',
                'accent' => 'bg-orange-600',
                'accent_hover' => 'hover:bg-orange-700',
                'text_accent' => 'text-orange-400',
                'border_accent' => 'border-orange-500',
                'card_bg' => 'bg-gradient-to-br from-orange-50 to-orange-100',
                'sidebar_accent' => 'from-orange-600 to-orange-800',
                'title' => 'Anggota - Dashboard Pribadi',
                'description' => 'Kelola pengajuan kredit dan pembayaran Anda',
                'stats' => $stats['anggota'],
                'quick_actions' => [
                    ['title' => 'Ajukan Kredit', 'url' => '/kredit/new', 'icon' => 'M12 4v16m8-8H4'],
                    ['title' => 'Pembayaran', 'url' => '/pembayaran-angsuran', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'],
                    ['title' => 'Riwayat', 'url' => '/angsuran', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['title' => 'Profile', 'url' => '/profile', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z']
                ]
            ]
        ];

        // Get current role config or default to Bendahara
        $config = $roleConfigs[$userLevel] ?? $roleConfigs['Bendahara'];
        $colorScheme = $config['theme'];
        $accent = $config['accent'];
        $accentHover = $config['accent_hover'];
        $textAccent = $config['text_accent'];
        $borderAccent = $config['border_accent'];
        $cardBg = $config['card_bg'];
        $sidebarAccent = $config['sidebar_accent'];

        $data = [
            'config' => $config,
            'userLevel' => $userLevel,
            'namaLengkap' => $namaLengkap,
            'colorScheme' => $colorScheme,
            'accent' => $accent,
            'accentHover' => $accentHover,
            'textAccent' => $textAccent,
            'borderAccent' => $borderAccent,
            'cardBg' => $cardBg,
            'sidebarAccent' => $sidebarAccent
        ];

        return view('home', $data);
    }

    public function landingPage(): string
    {
        return view('landing_page');
    }

    private function getRoleBasedStats($userLevel)
    {
        // Helper function to count records
        $count = function($model, $conditions = []) {
            $result = $model->findAll();
            if (!empty($conditions)) {
                return array_filter($result, function($item) use ($conditions) {
                    foreach ($conditions as $key => $value) {
                        if (isset($item[$key]) && $item[$key] !== $value) {
                            return false;
                        }
                    }
                    return true;
                });
            }
            return count($result);
        };

        // Helper function for date-based conditions
        $countWithDate = function($model, $dateConditions = []) {
            $result = $model->findAll();
            return array_filter($result, function($item) use ($dateConditions) {
                foreach ($dateConditions as $key => $condition) {
                    if (isset($item[$key])) {
                        if ($condition === 'today' && date('Y-m-d', strtotime($item[$key])) !== date('Y-m-d')) {
                            return false;
                        } elseif ($condition === 'past' && strtotime($item[$key]) >= strtotime(date('Y-m-d'))) {
                            return false;
                        } elseif ($condition === 'next_7_days' && strtotime($item[$key]) > strtotime(date('Y-m-d', strtotime('+7 days')))) {
                            return false;
                        } elseif ($condition === 'current_month' && date('n', strtotime($item[$key])) !== date('n')) {
                            return false;
                        }
                    }
                }
                return true;
            });
        };

        $stats = [
            'bendahara' => [
                ['title' => 'Total Kredit Aktif', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'color' => 'blue', 'value' => count($this->kreditModel->where('status_aktif', 'Aktif')->findAll())],
                ['title' => 'Total Pencairan', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z', 'color' => 'green', 'value' => count($this->pencairanModel->where('status_aktif', 'Aktif')->findAll())],
                ['title' => 'Pembayaran Hari Ini', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'yellow', 'value' => count($countWithDate($this->pembayaranAngsuranModel, ['tanggal_pembayaran' => 'today']))],
                ['title' => 'Angsuran Terlambat', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'red', 'value' => count(array_filter($this->angsuranModel->findAll(), function($item) {
                    return isset($item['tanggal_jatuh_tempo']) && strtotime($item['tanggal_jatuh_tempo']) < strtotime(date('Y-m-d')) &&
                           isset($item['status_pembayaran']) && $item['status_pembayaran'] === 'Belum Dibayar';
                }))]
            ],
            'ketua' => [
                ['title' => 'Total Anggota', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z', 'color' => 'purple', 'value' => count(array_filter($this->anggotaModel->findAll(), function($item) {
                    return isset($item['status_keanggotaan']) && $item['status_keanggotaan'] === 'Aktif';
                }))],
                ['title' => 'Kredit Disetujui', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green', 'value' => count(array_filter($this->kreditModel->findAll(), function($item) {
                    return isset($item['status_kredit']) && $item['status_kredit'] === 'disetujui';
                }))],
                ['title' => 'Pending Review', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'yellow', 'value' => count(array_filter($this->kreditModel->findAll(), function($item) {
                    return isset($item['status_kredit']) && $item['status_kredit'] === 'pending';
                }))],
                ['title' => 'Laporan Bulanan', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'blue', 'value' => count(array_filter($this->kreditModel->findAll(), function($item) {
                    return isset($item['tanggal_pengajuan']) && date('n', strtotime($item['tanggal_pengajuan'])) === date('n');
                }))]
            ],
            'appraiser' => [
                ['title' => 'Verifikasi Hari Ini', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green', 'value' => count(array_filter($this->kreditModel->findAll(), function($item) {
                    return isset($item['status_kredit']) && $item['status_kredit'] === 'pending';
                }))],
                ['title' => 'Menunggu Verifikasi', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'yellow', 'value' => count(array_filter($this->kreditModel->findAll(), function($item) {
                    return isset($item['status_kredit']) && $item['status_kredit'] === 'dalam proses';
                }))],
                ['title' => 'Terverifikasi', 'icon' => 'M5 13l4 4L19 7', 'color' => 'blue', 'value' => count(array_filter($this->kreditModel->findAll(), function($item) {
                    return isset($item['catatan_appraiser']) && !empty($item['catatan_appraiser']);
                }))],
                ['title' => 'Perlu Review', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'red', 'value' => count(array_filter($this->kreditModel->findAll(), function($item) {
                    return isset($item['status_kredit']) && $item['status_kredit'] === 'ditolak';
                }))]
            ],
            'anggota' => [
                ['title' => 'Kredit Aktif', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'color' => 'blue', 'value' => count(array_filter($this->kreditModel->findAll(), function($item) {
                    return isset($item['id_anggota']) && $item['id_anggota'] == session()->get('id_anggota_ref') &&
                           isset($item['status_aktif']) && $item['status_aktif'] === 'Aktif';
                }))],
                ['title' => 'Angsuran Bulan Ini', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'green', 'value' => count(array_filter($this->angsuranModel->findAll(), function($item) {
                    return isset($item['id_anggota']) && $item['id_anggota'] == session()->get('id_anggota_ref') &&
                           isset($item['tanggal_jatuh_tempo']) && date('n', strtotime($item['tanggal_jatuh_tempo'])) === date('n');
                }))],
                ['title' => 'Sisa Pokok', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1', 'color' => 'yellow', 'value' => 'Rp 0'], // Will be calculated with proper logic
                ['title' => 'Pembayaran Terdekat', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'red', 'value' => count(array_filter($this->angsuranModel->findAll(), function($item) {
                    return isset($item['id_anggota']) && $item['id_anggota'] == session()->get('id_anggota_ref') &&
                           isset($item['tanggal_jatuh_tempo']) && strtotime($item['tanggal_jatuh_tempo']) <= strtotime(date('Y-m-d', strtotime('+7 days')));
                }))]
            ]
        ];

        return $stats;
    }
}
