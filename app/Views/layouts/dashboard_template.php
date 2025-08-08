<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= $title ?? 'Dashboard Koperasi' ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="/globals.css" rel="stylesheet">
    <script>
        // Apply role-based theme based on user level
        document.addEventListener('DOMContentLoaded', function() {
            const userLevel = '<?php echo session()->get('level'); ?>';
            const body = document.body;
            
            // Remove existing theme classes
            body.classList.remove('theme-blue', 'theme-purple', 'theme-green', 'theme-orange');
            
            // Apply theme based on user level
            switch(userLevel) {
                case 'Bendahara':
                    body.classList.add('theme-blue');
                    break;
                case 'Ketua Koperasi':
                    body.classList.add('theme-purple');
                    break;
                case 'Appraiser':
                    body.classList.add('theme-green');
                    break;
                case 'Anggota':
                    body.classList.add('theme-orange');
                    break;
                default:
                    body.classList.add('theme-blue'); // Default theme
            }
        });
    </script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sidebar: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    screens: {
                        'xs': '475px',
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.5);
        }
        
        /* Ensure full height */
        html, body {
            height: 100%;
            overflow-x: hidden;
        }
        
        /* Sidebar transitions */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        
        /* Mobile sidebar positioning */
        @media (max-width: 1023px) {
            .mobile-sidebar {
                transform: translateX(-100%);
            }
            .mobile-sidebar.show {
                transform: translateX(0);
            }
        }
        
        /* Responsive table */
        @media (max-width: 768px) {
            .responsive-table {
                font-size: 0.875rem;
            }
            .responsive-table th,
            .responsive-table td {
                padding: 0.5rem 0.25rem;
            }
        }
        
        /* Card responsive */
        .responsive-card {
            min-height: 120px;
        }
        
        @media (max-width: 640px) {
            .responsive-card {
                min-height: 100px;
            }
        }
        
        /* Form responsive */
        @media (max-width: 640px) {
            .form-container {
                padding: 1rem;
            }
        }

        body {
            font-family: 'JetBrains Mono', monospace;
        }
        
        /* Role-based theme colors */
        .theme-blue {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #dbeafe;
            --primary-text: #1e40af;
        }
        
        .theme-purple {
            --primary-color: #7c3aed;
            --primary-hover: #6d28d9;
            --primary-light: #ede9fe;
            --primary-text: #5b21b6;
        }
        
        .theme-green {
            --primary-color: #059669;
            --primary-hover: #047857;
            --primary-light: #d1fae5;
            --primary-text: #065f46;
        }
        
        .theme-orange {
            --primary-color: #ea580c;
            --primary-hover: #c2410c;
            --primary-light: #fed7aa;
            --primary-text: #9a3412;
        }
        
        /* Apply theme colors to UI components */
        
        
        .theme-blue .accent-hover:hover { background-color: #1d4ed8; }
        .theme-purple .accent-hover:hover { background-color: #6d28d9; }
        .theme-green .accent-hover:hover { background-color: #047857; }
        .theme-orange .accent-hover:hover { background-color: #c2410c; }
        
        .theme-blue .text-accent { color: #60a5fa; }
        .theme-purple .text-accent { color: #a78bfa; }
        .theme-green .text-accent { color: #34d399; }
        .theme-orange .text-accent { color: #fb923c; }
        
        .theme-blue .border-accent { border-color: #3b82f6; }
        .theme-purple .border-accent { border-color: #8b5cf6; }
        .theme-green .border-accent { border-color: #10b981; }
        .theme-orange .border-accent { border-color: #f97316; }
    </style>
</head>
<body class="bg-gray-50 font-sans h-full <?php
$userLevel = session()->get('level');
$themeClasses = [
    'Bendahara' => 'theme-blue',
    'Ketua' => 'theme-purple',
    'Appraiser' => 'theme-green',
    'Anggota' => 'theme-orange'
];
echo $themeClasses[$userLevel] ?? 'theme-blue';
?>">
    <?php
    $userLevel = session()->get('level');
    $roleConfigs = [
        'Bendahara' => [
            'theme' => 'blue',
            'sidebar' => 'from-blue-600 to-blue-800',
            'accent' => 'bg-blue-600',
            'accent_hover' => 'hover:bg-blue-700',
            'text_accent' => 'text-blue-400',
            'border_accent' => 'border-blue-500'
        ],
        'Ketua' => [
            'theme' => 'purple',
            'sidebar' => 'from-purple-600 to-purple-800',
            'accent' => 'bg-purple-600',
            'accent_hover' => 'hover:bg-purple-700',
            'text_accent' => 'text-purple-400',
            'border_accent' => 'border-purple-500'
        ],
        'Appraiser' => [
            'theme' => 'green',
            'sidebar' => 'from-green-600 to-green-800',
            'accent' => 'bg-green-600',
            'accent_hover' => 'hover:bg-green-700',
            'text_accent' => 'text-green-400',
            'border_accent' => 'border-green-500'
        ],
        'Anggota' => [
            'theme' => 'orange',
            'sidebar' => 'from-orange-600 to-orange-800',
            'accent' => 'bg-orange-600',
            'accent_hover' => 'hover:bg-orange-700',
            'text_accent' => 'text-orange-400',
            'border_accent' => 'border-orange-500'
        ]
    ];
    
    $config = $roleConfigs[$userLevel] ?? $roleConfigs['Bendahara'];
    $sidebarClass = $config['sidebar'];
    $accentClass = $config['accent'];
    $accentHoverClass = $config['accent_hover'];
    $textAccentClass = $config['text_accent'];
    $borderAccentClass = $config['border_accent'];
    ?>
    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>
    
    <!-- Layout Container -->
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 sm:w-72 lg:w-64 bg-gray-800 shadow-xl sidebar-transition mobile-sidebar lg:translate-x-0">
            <div class="flex h-full flex-col">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between border-b border-gray-700 p-3 sm:p-4">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <div class="flex h-7 w-7 sm:h-8 sm:w-8 items-center justify-center rounded-lg bg-blue-600 flex-shrink-0">
                            <i class="bx bx-university text-white text-sm sm:text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-base sm:text-lg font-bold text-white truncate">Koperasi</h2>
                            <p class="text-xs text-gray-300 truncate">Management System</p>
                        </div>
                    </div>
                    <!-- Close button for mobile -->
                    <button id="close-sidebar" class="lg:hidden text-gray-300 hover:text-white p-1 rounded">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto p-3 sm:p-4">
                    <ul class="space-y-1">
                        <?php
                        use App\Config\Roles;
                        $currentUri = service('uri')->getSegment(1);
                        $currentUserLevel = session()->get('level');
                        
                        // Define menu structure for each role
                        $roleMenus = [
                            'Bendahara' => [
                                ['url' => '/dashboard-bendahara', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l7 7m-2 2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard Bendahara', 'segment' => 'dashboard-bendahara'],
                                ['url' => '/beranda', 'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10.5 6L12 4l1.5 2M21 3H3v18h18V3z', 'label' => 'Beranda', 'segment' => 'beranda'],
                                ['url' => '/kredit/pengajuan-untuk-role', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => '📋 TUGAS VERIFIKASI', 'segment' => 'kredit'],
                                ['url' => '/anggota', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z', 'label' => 'Data Anggota', 'segment' => 'anggota'],
                                ['url' => '/kredit', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'label' => 'Pengajuan Kredit', 'segment' => 'kredit'],
                                ['url' => '/riwayat-penilaian', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'label' => 'Riwayat Penilaian', 'segment' => 'riwayat-penilaian'],
                                ['url' => '/pencairan', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Pencairan Kredit', 'segment' => 'pencairan'],
                                ['url' => '/pembayaran-angsuran', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1', 'label' => 'Pembayaran Kredit / Angsuran', 'segment' => 'pembayaran-angsuran'],
                                ['url' => '/agunan', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Data Agunan', 'segment' => 'agunan'],
                                ['url' => '/laporan-kredit', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Laporan Kredit', 'segment' => 'laporan-kredit'],
                                ['url' => '/change-password', 'icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v-2H7v-2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1221 9z', 'label' => 'Ganti Password', 'segment' => 'change-password']
                            ],
                            'Ketua' => [
                                ['url' => '/dashboard-ketua', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l7 7m-2 2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard Ketua', 'segment' => 'dashboard-ketua'],
                                ['url' => '/laporan-kredit-koperasi', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Laporan Kredit Koperasi', 'segment' => 'laporan-kredit-koperasi'],
                                ['url' => '/change-password', 'icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v-2H7v-2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z', 'label' => 'Ganti Password', 'segment' => 'change-password']
                            ],
                            'Appraiser' => [
                                ['url' => '/dashboard-appraiser', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l7 7m-2 2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard Appraiser', 'segment' => 'dashboard-appraiser'],
                                ['url' => '/verifikasi-agunan', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Verifikasi Agunan', 'segment' => 'verifikasi-agunan'],
                                ['url' => '/riwayat-penilaian', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Riwayat Penilaian', 'segment' => 'riwayat-penilaian']
                            ],
                            'Anggota' => [
                                ['url' => '/dashboard-anggota', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l7 7m-2 2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard', 'segment' => 'dashboard-anggota'],
                                ['url' => '/profile', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Profile Saya', 'segment' => 'profile'],
                                ['url' => '/kredit', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'label' => 'Pengajuan Kredit', 'segment' => 'kredit'],
                                ['url' => '/riwayat-kredit', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Riwayat Kredit', 'segment' => 'riwayat-kredit'],
                                ['url' => '/riwayat-pembayaran', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'label' => 'Riwayat Pembayaran', 'segment' => 'riwayat-pembayaran'],
                                ['url' => '/simulasi-bunga', 'icon' => 'M9 7h6l-6 10h6', 'label' => 'Simulasi Bunga', 'segment' => 'simulasi-bunga'],
                                ['url' => '/change-password', 'icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v-2H7v-2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z', 'label' => 'Ganti Password', 'segment' => 'change-password']
                            ]
                        ];
                        
                        // Get nav links for current user role
                        $navLinks = $roleMenus[$currentUserLevel] ?? [];
                        ?>
                        <?php foreach ($navLinks as $link): ?>
                            <li>
                                <a href="<?= $link['url'] ?>"
                                   class="flex items-center gap-2 sm:gap-3 rounded-lg px-2 sm:px-3 py-2 text-xs sm:text-sm font-medium transition-colors <?= $currentUri === $link['segment'] ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                                    <svg class="h-3 w-3 sm:h-4 sm:w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $link['icon'] ?>" />
                                    </svg>
                                    <span class="truncate"><?= $link['label'] ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <!-- Logout Button -->
                <div class="border-t border-gray-700 p-3 sm:p-4">
                    <a href="/logout" class="flex items-center gap-2 sm:gap-3 rounded-lg bg-red-600 px-2 sm:px-3 py-2 text-xs sm:text-sm font-medium text-white transition-colors hover:bg-red-700">
                        <svg class="h-3 w-3 sm:h-4 sm:w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="truncate">Logout</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col min-h-0 lg:ml-0">
            <!-- Header -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200 px-3 sm:px-4 lg:px-6 py-3 sm:py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 sm:gap-4 min-w-0 flex-1">
                        <!-- Mobile menu button -->
                        <button id="mobile-menu-btn" class="lg:hidden rounded-md p-1.5 sm:p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 flex-shrink-0">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 truncate"><?= $headerTitle ?? 'Dashboard Koperasi' ?></h1>
                            <p class="text-xs sm:text-sm text-gray-600 hidden sm:block truncate">Kelola sistem Anda</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                        <div class="text-right hidden md:block">
                            <p class="text-xs sm:text-sm font-semibold text-gray-900 truncate max-w-32 lg:max-w-none"><?= esc(session()->get('nama_lengkap') ?? 'DEWI') ?></p>
                            <p class="text-xs text-gray-500"><?= esc(session()->get('level') ?? 'ADMIN') ?></p>
                        </div>
                        <div class="h-7 w-7 sm:h-8 sm:w-8 lg:h-10 lg:w-10 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-semibold text-xs sm:text-sm">
                                <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'DW', 0, 2)) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div class="px-3 sm:px-4 lg:px-6 pt-3 sm:pt-4">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3 sm:p-4">
                        <div class="flex">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-green-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-2 sm:ml-3">
                                <p class="text-xs sm:text-sm font-medium text-green-800"><?= session()->getFlashdata('success') ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 sm:p-4">
                        <div class="flex">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-2 sm:ml-3">
                                <p class="text-xs sm:text-sm font-medium text-red-800"><?= session()->getFlashdata('error') ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 sm:p-4">
                        <div class="flex">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-2 sm:ml-3">
                                <ul class="text-xs sm:text-sm text-red-800 list-disc list-inside space-y-1">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-auto px-3 sm:px-4 lg:px-6 pb-4 sm:pb-6">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebar = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-overlay');

        function openMobileMenu() {
            sidebar.classList.add('show');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            sidebar.classList.remove('show');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMobileMenu);
        if (closeSidebar) closeSidebar.addEventListener('click', closeMobileMenu);
        if (overlay) overlay.addEventListener('click', closeMobileMenu);

        // Close mobile menu on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeMobileMenu();
            }
        });

        // Close mobile menu when clicking on navigation links (mobile only)
        document.querySelectorAll('#sidebar a[href]').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    closeMobileMenu();
                }
            });
        });

        // Handle escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && window.innerWidth < 1024) {
                closeMobileMenu();
            }
        });
    </script>
</body>
</html>
