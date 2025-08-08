<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full mx-auto mt-4">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
        <!-- Total Kredit -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Kredit</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= number_format($summary['total_kredit'] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <!-- Kredit Disetujui -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kredit Disetujui</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= number_format($summary['kredit_disetujui'] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <!-- Kredit Menunggu -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kredit Menunggu</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= number_format($summary['kredit_menunggu'] ?? 0) ?></p>
                </div>
            </div>
        </div>

        <!-- Kredit Ditolak -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kredit Ditolak</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= number_format($summary['kredit_ditolak'] ?? 0) ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Pengajuan Kredit Terbaru</h2>
                    <p class="text-sm text-gray-600 mt-1">Daftar 10 pengajuan kredit terbaru</p>
                </div>
                <div class="flex gap-2">
                    <a href="/laporan-kredit-koperasi/generate-pdf" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707L16.414 6.293A1 1 0 0015.586 6H7a2 2 0 00-2 2v11a2 2 0 002 2z" />
                        </svg>
                        Cetak PDF
                    </a>
                    <a href="/laporan-kredit-koperasi/export-excel" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <?php if (empty($recent_kredit)): ?>
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data kredit</h3>
                    <p class="mt-1 text-sm text-gray-500">Belum ada pengajuan kredit yang masuk ke sistem.</p>
                </div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Kredit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Anggota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($recent_kredit as $row): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($row['id_kredit']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($row['no_anggota'] ?? '-') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($row['nama_lengkap'] ?? '-') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?= number_format($row['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $statusColors = [
                                        'Diajukan' => 'bg-yellow-100 text-yellow-800',
                                        'Disetujui' => 'bg-green-100 text-green-800',
                                        'Ditolak' => 'bg-red-100 text-red-800',
                                        'Berjalan' => 'bg-blue-100 text-blue-800',
                                        'Selesai' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $currentStatus = $row['status_kredit'] ?? 'Diajukan';
                                    $statusClass = $statusColors[$currentStatus] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                        <?= esc($currentStatus) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('d M Y', strtotime($row['created_at'] ?? $row['tanggal_pengajuan'] ?? 'now')) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>