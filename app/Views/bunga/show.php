<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Bunga</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap bunga</p>
                </div>
                <div class="flex gap-2">
                    <a href="/bunga/edit/<?= esc($bunga['id']) ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        <i class="bx bx-edit h-4 w-4"></i>
                        Edit
                    </a>
                    <a href="/bunga" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="bx bx-arrow-left h-4 w-4"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Data Bunga -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-percentage text-blue-600 h-5 w-5"></i>
                        Informasi Bunga
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">ID Bunga</label>
                        <p class="text-sm text-gray-900 font-medium"><?= esc($bunga['id']) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Nama Bunga</label>
                        <p class="text-sm text-gray-900 font-semibold"><?= esc($bunga['nama_bunga']) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Persentase Bunga</label>
                        <p class="text-sm text-gray-900 font-semibold text-green-600"><?= esc($bunga['persentase_bunga']) ?>%</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Tipe Bunga</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <?= esc($bunga['tipe_bunga']) ?>
                        </span>
                    </div>

                    <?php if (!empty($bunga['keterangan'])): ?>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Keterangan</label>
                        <p class="text-sm text-gray-900"><?= esc($bunga['keterangan']) ?></p>
                    </div>
                    <?php endif; ?>
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
                        <p class="text-sm text-gray-900"><?= isset($bunga['created_at']) ? date('d F Y H:i', strtotime($bunga['created_at'])) : '-' ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Terakhir Diupdate</label>
                        <p class="text-sm text-gray-900"><?= isset($bunga['updated_at']) ? date('d F Y H:i', strtotime($bunga['updated_at'])) : '-' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
