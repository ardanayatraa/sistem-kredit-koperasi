<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Agunan</h2>
                <p class="text-sm text-gray-600 mt-1">Semua data agunan untuk verifikasi</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kredit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Agunan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($agunan)): ?>
                    <?php foreach ($agunan as $index => $item): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= $pager ? ($index + 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage())) : ($index + 1) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= esc($item['nama_lengkap']) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= esc($item['nik']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp <?= number_format($item['jumlah_pengajuan'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= esc($item['jenis_agunan'] ?? '-') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusClass = '';
                                $statusText = '';
                                if (!empty($item['catatan_appraiser'])) {
                                    if (!empty($item['nilai_taksiran_agunan'])) {
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusText = 'Disetujui';
                                    } else {
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusText = 'Ditolak';
                                    }
                                } else {
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                    $statusText = 'Menunggu';
                                }
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="<?= base_url('daftar-agunan/show/' . $item['id_kredit']) ?>" 
                                   class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                    <i class="bx bx-show text-sm"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="text-gray-500">Tidak ada data agunan</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($agunan) && $pager): ?>
        <div class="border-t border-gray-200 px-6 py-4">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>