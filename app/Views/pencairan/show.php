<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Pencairan</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap pencairan kredit</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="bx bx-circle text-xs mr-1.5"></i>
                        Dicairkan
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Data Pencairan -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bx bx-file-invoice-dollar text-blue-600 h-5 w-5"></i>
                    Data Pencairan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="bx bx-hashtag text-blue-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">ID Pencairan</p>
                                <p class="text-lg font-semibold text-gray-900">#<?= esc($pencairan['id_pencairan']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="bx bx-user text-green-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Nama Anggota</p>
                                <p class="text-lg font-semibold text-gray-900"><?= esc($pencairan['nama_anggota'] ?? 'N/A') ?></p>
                                <p class="text-xs text-gray-500">No. <?= esc($pencairan['no_anggota'] ?? '-') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i class="bx bx-calendar-alt text-purple-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tanggal Pencairan</p>
                                <p class="text-lg font-semibold text-gray-900"><?= date('d F Y', strtotime($pencairan['tanggal_pencairan'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <i class="bx bx-money-bill-wave text-orange-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Jumlah Dicairkan</p>
                                <p class="text-lg font-semibold text-gray-900">Rp <?= number_format($pencairan['jumlah_dicairkan'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <i class="bx bx-exchange-alt text-indigo-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Metode Pencairan</p>
                                <p class="text-lg font-semibold text-gray-900"><?= esc($pencairan['metode_pencairan']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="bx bx-percentage text-blue-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Bunga</p>
                                <p class="text-lg font-semibold text-gray-900"><?= esc($pencairan['nama_bunga'] ?? 'N/A') ?></p>
                                <p class="text-xs text-gray-500"><?= isset($pencairan['persentase_bunga']) ? number_format($pencairan['persentase_bunga'], 2) . '%' : '-' ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bukti Transfer -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bx bx-file-invoice text-green-600 h-5 w-5"></i>
                    Bukti Transfer
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="bx bx-file-invoice text-green-600 h-5 w-5"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Bukti Transfer</p>
                            <?php if (!empty($pencairan['bukti_transfer'])): ?>
                                <p class="text-base text-gray-900 mt-1"><?= esc($pencairan['bukti_transfer']) ?></p>
                                <a href="/uploads/pencairan/<?= esc($pencairan['bukti_transfer']) ?>" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm mt-2">
                                    <i class="bx bx-external-link-alt h-4 w-4"></i>
                                    Lihat Dokumen
                                </a>
                            <?php else: ?>
                                <p class="text-base text-gray-500 italic mt-1">Tidak ada bukti transfer</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/pencairan/edit/<?= esc($pencairan['id_pencairan']) ?>" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-yellow-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-yellow-700 transition-colors">
                    <i class="bx bx-edit h-4 w-4"></i>
                    Edit Pencairan
                </a>
                <form action="/pencairan/delete/<?= esc($pencairan['id_pencairan']) ?>" method="post" class="flex-1 sm:flex-none" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pencairan ini? Bukti transfer juga akan dihapus.');">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        <i class="bx bx-trash-alt h-4 w-4"></i>
                        Hapus Pencairan
                    </button>
                </form>
                <a href="/pencairan" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="bx bx-arrow-left h-4 w-4"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
