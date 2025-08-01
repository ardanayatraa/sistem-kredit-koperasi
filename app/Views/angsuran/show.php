<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Angsuran</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap angsuran</p>
                </div>
                <div class="flex gap-2">
                    <a href="/angsuran/edit/<?= esc($angsuran['id_angsuran']) ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <a href="/angsuran" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Data Angsuran -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Informasi Angsuran
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">ID Angsuran</label>
                        <p class="text-sm text-gray-900 font-medium">#<?= esc($angsuran['id_angsuran']) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">ID Kredit</label>
                        <p class="text-sm text-gray-900"><?= esc($angsuran['id_kredit']) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Angsuran Ke</label>
                        <p class="text-sm text-gray-900"><?= esc($angsuran['angsuran_ke']) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Jumlah Angsuran</label>
                        <p class="text-sm text-gray-900 font-semibold">Rp <?= number_format($angsuran['jumlah_angsuran'], 0, ',', '.') ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Tanggal Jatuh Tempo</label>
                        <p class="text-sm text-gray-900"><?= date('d F Y', strtotime($angsuran['tgl_jatuh_tempo'])) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Status Pembayaran</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            <?php 
                            switch($angsuran['status_pembayaran']) {
                                case 'Lunas':
                                    echo 'bg-green-100 text-green-800';
                                    break;
                                case 'Sebagian':
                                    echo 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'Tertunggak':
                                    echo 'bg-red-100 text-red-800';
                                    break;
                                default:
                                    echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <?= esc($angsuran['status_pembayaran']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="space-y-4 pt-6 border-t border-gray-200">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi Sistem
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Dibuat Pada</label>
                        <p class="text-sm text-gray-900"><?= isset($angsuran['created_at']) ? date('d F Y H:i', strtotime($angsuran['created_at'])) : '-' ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Terakhir Diupdate</label>
                        <p class="text-sm text-gray-900"><?= isset($angsuran['updated_at']) ? date('d F Y H:i', strtotime($angsuran['updated_at'])) : '-' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
