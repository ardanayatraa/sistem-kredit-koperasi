<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
            
            <?php if ($currentRole === 'Anggota'): ?>
                <a href="/kredit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <i class="bx bx-plus h-4 w-4"></i>
                    Ajukan Kredit Baru
                </a>
            <?php endif; ?>
        </div>
        
        <div class="flex items-center gap-4">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <?php
                switch ($currentRole) {
                    case 'Bendahara':
                        echo 'Step 1: Verifikasi Dokumen';
                        break;
                    case 'Appraiser':
                        echo 'Step 2: Penilaian Agunan';
                        break;
                    case 'Ketua':
                        echo 'Step 3: Persetujuan Final';
                        break;
                    default:
                        echo 'Status Pengajuan';
                }
                ?>
            </span>
            
            <?php if (in_array($currentRole, ['Bendahara', 'Appraiser', 'Ketua'])): ?>
                <?php
                helper('notification');
                $notifCount = getNotificationCount($currentRole);
                ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                    <?= $notifCount ?> Tugas
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Notification Alert -->
    <?php if (in_array($currentRole, ['Bendahara', 'Appraiser', 'Ketua'])): ?>
        <?php
        $notifMessage = getNotificationMessage($currentRole);
        ?>
        <div class="bg-<?= $notifCount > 0 ? 'yellow' : 'green' ?>-50 border border-<?= $notifCount > 0 ? 'yellow' : 'green' ?>-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="bx bx-<?= $notifCount > 0 ? 'bell' : 'check-circle' ?> text-<?= $notifCount > 0 ? 'yellow' : 'green' ?>-400 h-5 w-5"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-<?= $notifCount > 0 ? 'yellow' : 'green' ?>-800">
                        Notifikasi Sistem Koperasi Mitra Sejahtra
                    </h3>
                    <div class="mt-2 text-sm text-<?= $notifCount > 0 ? 'yellow' : 'green' ?>-700">
                        <?= $notifMessage ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Workflow Guide -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-800 mb-2">Alur Pengajuan Kredit Koperasi Mitra Sejahtra:</h3>
        <div class="flex items-center space-x-2 text-sm text-blue-700">
            <div class="flex items-center">
                <i class="bx bx-user text-blue-600 mr-1"></i>
                <span>Anggota</span>
            </div>
            <span>→</span>
            <div class="flex items-center">
                <i class="bx bx-user-check text-yellow-600 mr-1"></i>
                <span>Bendahara</span>
            </div>
            <span>→</span>
            <div class="flex items-center">
                <i class="bx bx-search text-green-600 mr-1"></i>
                <span>Appraiser</span>
            </div>
            <span>→</span>
            <div class="flex items-center">
                <i class="bx bx-crown text-purple-600 mr-1"></i>
                <span>Ketua</span>
            </div>
            <span>→</span>
            <div class="flex items-center">
                <i class="bx bx-check-circle text-green-600 mr-1"></i>
                <span>Selesai</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow">
        <?php if (empty($pengajuan)): ?>
            <div class="px-6 py-12 text-center">
                <i class="bx bx-inbox text-gray-400 h-12 w-12 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    <?= $currentRole === 'Anggota' ? 'Belum Ada Pengajuan Kredit' : 'Tidak Ada Tugas' ?>
                </h3>
                <p class="text-gray-600 mb-4">
                    <?php
                    switch ($currentRole) {
                        case 'Bendahara':
                            echo 'Tidak ada pengajuan kredit yang menunggu verifikasi.';
                            break;
                        case 'Appraiser':
                            echo 'Tidak ada pengajuan kredit yang menunggu penilaian agunan.';
                            break;
                        case 'Ketua':
                            echo 'Tidak ada pengajuan kredit yang menunggu persetujuan final.';
                            break;
                        case 'Anggota':
                            echo 'Anda belum memiliki pengajuan kredit. Mulai ajukan kredit untuk keperluan Anda.';
                            break;
                        default:
                            echo 'Anda belum memiliki pengajuan kredit.';
                    }
                    ?>
                </p>
                
                <?php if ($currentRole === 'Anggota'): ?>
                    <a href="/kredit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        <i class="bx bx-plus h-4 w-4"></i>
                        Ajukan Kredit Sekarang
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kredit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jangka Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($pengajuan as $index => $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $index + 1 ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($item['nama_anggota']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= date('d/m/Y', strtotime($item['tanggal_pengajuan'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp <?= number_format($item['jumlah_pengajuan'], 0, ',', '.') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= $item['jangka_waktu'] ?> bulan</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusColors = [
                                        'Diajukan' => 'bg-yellow-100 text-yellow-800',
                                        'Verifikasi Bendahara' => 'bg-blue-100 text-blue-800',
                                        'Siap Persetujuan' => 'bg-indigo-100 text-indigo-800',
                                        'Disetujui Ketua' => 'bg-purple-100 text-purple-800',
                                        'Siap Dicairkan' => 'bg-green-100 text-green-800',
                                        'Sudah Dicairkan' => 'bg-emerald-100 text-emerald-800',
                                        'Ditolak Bendahara' => 'bg-red-100 text-red-800',
                                        'Ditolak Appraiser' => 'bg-red-100 text-red-800',
                                        'Ditolak Final' => 'bg-red-100 text-red-800',
                                        'Perlu Review Bendahara' => 'bg-orange-100 text-orange-800'
                                    ];
                                    $statusClass = $statusColors[$item['status_kredit']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                        <?= esc($item['status_kredit']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <?php if ($currentRole === 'Bendahara' && $item['status_kredit'] === 'Diajukan'): ?>
                                            <a href="/kredit/verifikasi-bendahara/<?= $item['id_kredit'] ?>"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-600 text-white text-xs font-medium rounded hover:bg-yellow-700 transition-colors">
                                                <i class="bx bx-check h-3 w-3"></i>
                                                VERIFIKASI DOKUMEN
                                            </a>
                                        <?php elseif ($currentRole === 'Bendahara' && $item['status_kredit'] === 'Disetujui Ketua'): ?>
                                            <a href="/kredit/proses-pencairan/<?= $item['id_kredit'] ?>"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                                                <i class="bx bx-money h-3 w-3"></i>
                                                PROSES PENCAIRAN
                                            </a>
                                        <?php elseif ($currentRole === 'Appraiser' && $item['status_kredit'] === 'Verifikasi Bendahara'): ?>
                                            <a href="/kredit/penilaian-appraiser/<?= $item['id_kredit'] ?>"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                                <i class="bx bx-search h-3 w-3"></i>
                                                NILAI AGUNAN
                                            </a>
                                        <?php elseif ($currentRole === 'Ketua' && $item['status_kredit'] === 'Siap Persetujuan'): ?>
                                            <a href="/kredit/persetujuan-final/<?= $item['id_kredit'] ?>"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-600 text-white text-xs font-medium rounded hover:bg-purple-700 transition-colors">
                                                <i class="bx bx-crown h-3 w-3"></i>
                                                PERSETUJUAN FINAL
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="/kredit/show/<?= $item['id_kredit'] ?>"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700 transition-colors">
                                            <i class="bx bx-eye h-3 w-3"></i>
                                            Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>