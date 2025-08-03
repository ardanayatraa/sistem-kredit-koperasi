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
                        <i class="bx bx-edit h-4 w-4"></i>
                        Edit
                    </a>
                    <a href="/angsuran" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="bx bx-arrow-left h-4 w-4"></i>
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
                        <i class="bx bx-file-invoice-dollar text-blue-600 h-5 w-5"></i>
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
                        <i class="bx bx-info-circle text-gray-600 h-5 w-5"></i>
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
