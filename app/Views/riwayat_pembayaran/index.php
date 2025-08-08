<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Riwayat Pembayaran</h2>
                <p class="text-sm text-gray-600 mt-1">Riwayat pembayaran angsuran kredit Anda</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?= base_url('bayar-angsuran') ?>"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                    Bayar Angsuran
                </a>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angsuran Ke</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Bayar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($riwayat)): ?>
                    <?php foreach ($riwayat as $index => $item): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= $index + 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage()) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= esc($item['angsuran_ke']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp <?= number_format($item['jumlah_bayar'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= date('d/m/Y', strtotime($item['tanggal_bayar'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusVerifikasi = $item['status_verifikasi'] ?? 'pending';
                                $statusDisplay = match($statusVerifikasi) {
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => 'Pending'
                                };
                                $statusColor = match($statusVerifikasi) {
                                    'approved' => 'green',
                                    'rejected' => 'red',
                                    default => 'yellow'
                                };
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                    <i class="bx <?= match($statusVerifikasi) { 'approved' => 'bx-check-circle', 'rejected' => 'bx-x-circle', default => 'bx-time-five' } ?> mr-1"></i>
                                    <?= esc($statusDisplay) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="<?= base_url('riwayat-pembayaran/show/' . $item['id_pembayaran']) ?>" 
                                   class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                    <i class="bx bx-show text-sm"></i>
                                    Detail
                                </a>
                                <a href="<?= base_url('riwayat-pembayaran/print/' . $item['id_pembayaran']) ?>" 
                                   class="inline-flex items-center gap-1 px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors"
                                   target="_blank">
                                    <i class="bx bx-printer text-sm"></i>
                                    Print
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-gray-500">Belum ada riwayat pembayaran</p>
                                <p class="text-gray-400 text-sm mt-1">Riwayat pembayaran Anda akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($riwayat)): ?>
        <div class="border-t border-gray-200 px-6 py-4">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>