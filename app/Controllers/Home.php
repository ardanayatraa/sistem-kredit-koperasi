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
    protected $userModel;

    public function __construct()
    {
        $this->kreditModel = new KreditModel();
        $this->anggotaModel = new AnggotaModel();
        $this->pencairanModel = new PencairanModel();
        $this->angsuranModel = new AngsuranModel();
        $this->pembayaranAngsuranModel = new PembayaranAngsuranModel();
        $this->userModel = new \App\Models\UserModel();
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
            'Ketua' => [
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

    // Specific dashboard methods for each role
    public function dashboardBendahara()
    {
        // 🔥 WORKFLOW FOCUS: Only get essential tasks for Bendahara
        // 1. Pengajuan baru perlu verifikasi dokumen (status: "Diajukan")
        $pengajuanBaruBendahara = $this->kreditModel
            ->where('status_kredit', 'Diajukan')
            ->orderBy('tanggal_pengajuan', 'ASC')
            ->findAll();
        
        // 2. Kredit siap dicairkan (status: "Disetujui Ketua")
        $kreditSiapCair = $this->kreditModel
            ->where('status_kredit', 'Disetujui Ketua')
            ->orderBy('tanggal_persetujuan_ketua', 'ASC')
            ->findAll();
        
        // Add user names efficiently
        foreach ($pengajuanBaruBendahara as &$pengajuan) {
            $anggota = $this->anggotaModel->find($pengajuan['id_anggota']);
            if ($anggota) {
                $user = $this->userModel->where('id_anggota_ref', $anggota['id_anggota'])->first();
                $pengajuan['nama_lengkap'] = $user['nama_lengkap'] ?? 'Unknown';
            }
        }
        
        foreach ($kreditSiapCair as &$kredit) {
            $anggota = $this->anggotaModel->find($kredit['id_anggota']);
            if ($anggota) {
                $user = $this->userModel->where('id_anggota_ref', $anggota['id_anggota'])->first();
                $kredit['nama_lengkap'] = $user['nama_lengkap'] ?? 'Unknown';
            }
        }
            
        $data = [
            'title' => 'Dashboard Bendahara',
            'pengajuanBaruBendahara' => $pengajuanBaruBendahara,
            'kreditSiapCair' => $kreditSiapCair
        ];

        return view('dashboard/bendahara', $data);
    }

    public function dashboardKetua()
    {
        // 🔥 WORKFLOW FOCUS: Only get essential tasks for Ketua
        // Kredit yang siap untuk persetujuan final (status: "Siap Persetujuan")
        $kreditSiapDisetujui = $this->kreditModel
            ->where('status_kredit', 'Siap Persetujuan')
            ->orderBy('tanggal_pengajuan', 'ASC')
            ->findAll();
            
        // Add user names efficiently
        foreach ($kreditSiapDisetujui as &$kredit) {
            $anggota = $this->anggotaModel->find($kredit['id_anggota']);
            if ($anggota) {
                $user = $this->userModel->where('id_anggota_ref', $anggota['id_anggota'])->first();
                $kredit['nama_lengkap'] = $user['nama_lengkap'] ?? 'Unknown';
            }
        }
        
        $data = [
            'title' => 'Dashboard Ketua',
            'kreditSiapDisetujui' => $kreditSiapDisetujui
        ];

        return view('dashboard/ketua', $data);
    }

    public function dashboardAppraiser()
    {
        // 🔥 WORKFLOW FOCUS: Only get essential tasks for Appraiser
        // Kredit yang siap dinilai agunannya (status: "Verifikasi Bendahara")
        $kreditSiapDinilai = $this->kreditModel
            ->where('status_kredit', 'Verifikasi Bendahara')
            ->orderBy('tanggal_pengajuan', 'ASC')
            ->findAll();
            
        // Add user names efficiently
        foreach ($kreditSiapDinilai as &$kredit) {
            $anggota = $this->anggotaModel->find($kredit['id_anggota']);
            if ($anggota) {
                $user = $this->userModel->where('id_anggota_ref', $anggota['id_anggota'])->first();
                $kredit['nama_lengkap'] = $user['nama_lengkap'] ?? 'Unknown';
            }
        }
            
        $data = [
            'title' => 'Dashboard Appraiser',
            'kreditSiapDinilai' => $kreditSiapDinilai
        ];

        return view('dashboard/appraiser', $data);
    }

    public function dashboardAnggota()
    {
        $userId = session()->get('id_user');
        
        // Get anggota ID from users table
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        $idAnggota = $user['id_anggota_ref'] ?? null;
        
        if (!$idAnggota) {
            // If no anggota reference, set default empty values
            $data = [
                'title' => 'Dashboard Anggota',
                'headerTitle' => 'Dashboard Anggota',
                'userLevel' => 'Anggota',
                'userData' => $user,
                'kreditAktif' => 0,
                'sisaAngsuran' => 0,
                'totalTerbayar' => 0,
                'statusPembayaran' => 'lancar',
                'kreditSaya' => [],
                'pembayaranTerakhir' => [],
                'jadwalPembayaran' => []
            ];
            return view('dashboard/anggota', $data);
        }
        
        // Get active credits for this anggota
        $kreditAktif = $this->kreditModel
            ->where('id_anggota', $idAnggota)
            ->where('status_aktif', 'Aktif')
            ->findAll();
            
        // Calculate total active credit amount
        $totalKreditAktif = 0;
        foreach ($kreditAktif as $kredit) {
            $totalKreditAktif += $kredit['jumlah_pengajuan'];
        }
        
        // Get credit history for table display with additional calculations
        $kreditSaya = $this->kreditModel
            ->where('id_anggota', $idAnggota)
            ->orderBy('tanggal_pengajuan', 'DESC')
            ->limit(5)
            ->findAll();
            
        // Add bunga and jumlah_angsuran data to each credit record
        foreach ($kreditSaya as &$kredit) {
            // Get default interest rate (you can modify this logic based on your business rules)
            $bungaModel = new \App\Models\BungaModel();
            $defaultBunga = $bungaModel->where('status_aktif', 'Aktif')->first();
            
            // Set default values
            $kredit['bunga'] = $defaultBunga['persentase_bunga'] ?? 12; // Default 12% if no active interest found
            
            // Calculate monthly installment (simple calculation)
            if ($kredit['jangka_waktu'] > 0) {
                $principal = $kredit['jumlah_pengajuan'];
                $interest = $kredit['bunga'] / 100;
                $months = $kredit['jangka_waktu'];
                
                // Simple interest calculation (you may want to use compound interest)
                $totalAmount = $principal + ($principal * $interest * ($months / 12));
                $kredit['jumlah_angsuran'] = $totalAmount / $months;
            } else {
                $kredit['jumlah_angsuran'] = 0;
            }
        }
            
        // Get payment schedule using existing model method (unpaid installments)
        $allAngsuranData = $this->angsuranModel->getAngsuranByAnggota($idAnggota, ['tbl_angsuran.status_pembayaran' => 'Belum Dibayar']);
        usort($allAngsuranData, function($a, $b) {
            return strtotime($a['tgl_jatuh_tempo']) - strtotime($b['tgl_jatuh_tempo']);
        });
        $jadwalPembayaran = array_slice($allAngsuranData, 0, 5);
            
        // Get recent payments using model method
        $pembayaranTerakhir = $this->pembayaranAngsuranModel
            ->select('tbl_pembayaran_angsuran.*, tbl_angsuran.angsuran_ke')
            ->join('tbl_angsuran', 'tbl_angsuran.id_angsuran = tbl_pembayaran_angsuran.id_angsuran')
            ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref')
            ->where('tbl_kredit.id_anggota', $idAnggota)
            ->orderBy('tbl_pembayaran_angsuran.tanggal_bayar', 'DESC')
            ->limit(5)
            ->findAll();
            
        // Calculate total paid from ALL payments
        $allPembayaran = $this->pembayaranAngsuranModel
            ->select('tbl_pembayaran_angsuran.jumlah_bayar')
            ->join('tbl_angsuran', 'tbl_angsuran.id_angsuran = tbl_pembayaran_angsuran.id_angsuran')
            ->join('tbl_kredit', 'tbl_kredit.id_kredit = tbl_angsuran.id_kredit_ref')
            ->where('tbl_kredit.id_anggota', $idAnggota)
            ->findAll();
            
        $totalTerbayar = 0;
        foreach ($allPembayaran as $pembayaran) {
            $totalTerbayar += $pembayaran['jumlah_bayar'];
        }
        
        // Count remaining installments (unpaid installments)
        // Contoh: Jika kredit 12 bulan dan sudah bayar 2, maka sisa angsuran = 10
        $allUnpaidInstallments = $this->angsuranModel->getAngsuranByAnggota($idAnggota, ['tbl_angsuran.status_pembayaran' => 'Belum Dibayar']);
        $sisaAngsuran = count($allUnpaidInstallments);
            
        // Get total installments and paid installments for progress calculation
        $allAngsuran = $this->angsuranModel->getAngsuranByAnggota($idAnggota);
        $totalAngsuran = count($allAngsuran);
        $angsuranTerbayar = count($this->angsuranModel->getAngsuranByAnggota($idAnggota, ['tbl_angsuran.status_pembayaran' => 'Lunas']));
            
        // Determine payment status (check overdue)
        $overdueAngsuran = $this->angsuranModel->getAngsuranByAnggota($idAnggota, [
            'tbl_angsuran.status_pembayaran' => 'Belum Dibayar'
        ]);
        $overdueCount = 0;
        foreach ($overdueAngsuran as $angsuran) {
            if (strtotime($angsuran['tgl_jatuh_tempo']) < strtotime(date('Y-m-d'))) {
                $overdueCount++;
            }
        }
            
        $statusPembayaran = $overdueCount > 0 ? 'terlambat' : 'lancar';

        $data = [
            'title' => 'Dashboard Anggota',
            'headerTitle' => 'Dashboard Anggota',
            'userLevel' => 'Anggota',
            'userData' => $user,
            'kreditAktif' => $totalKreditAktif,
            'sisaAngsuran' => $sisaAngsuran,
            'totalTerbayar' => $totalTerbayar,
            'statusPembayaran' => $statusPembayaran,
            'totalAngsuran' => $totalAngsuran,
            'angsuranTerbayar' => $angsuranTerbayar,
            'kreditSaya' => $kreditSaya,
            'pembayaranTerakhir' => $pembayaranTerakhir,
            'jadwalPembayaran' => $jadwalPembayaran
        ];

        return view('dashboard/anggota', $data);
    }

    public function beranda()
    {
        $data = [
            'title' => 'Beranda',
            'headerTitle' => 'Beranda',
            'userLevel' => session()->get('level')
        ];

        return view('beranda/index', $data);
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
                ['title' => 'Kredit Aktif', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'color' => 'blue', 'value' => 'Rp 0'],
                ['title' => 'Angsuran Bulan Ini', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'green', 'value' => '0'],
                ['title' => 'Sisa Pokok', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1', 'color' => 'yellow', 'value' => 'Rp 0'],
                ['title' => 'Pembayaran Terdekat', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'red', 'value' => '0 hari']
            ]
        ];

        return $stats;
    }
}
