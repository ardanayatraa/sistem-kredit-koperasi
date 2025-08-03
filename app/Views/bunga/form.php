<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-900">
                <?= isset($bunga) ? 'Edit Bunga' : 'Tambah Bunga Baru' ?>
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                <?= isset($bunga) ? 'Perbarui informasi bunga' : 'Lengkapi form untuk menambah bunga baru' ?>
            </p>
        </div>

        <form action="<?= isset($bunga) ? '/bunga/update/' . esc($bunga['id']) : '/bunga/create' ?>" method="post" class="w-full p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Data Bunga Section -->
            <div class="w-full space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-percentage text-blue-600 h-5 w-5"></i>
                        Data Bunga
                    </h3>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="w-full">
                        <label for="nama_bunga" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Bunga <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_bunga" 
                               id="nama_bunga" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('nama_bunga', $bunga['nama_bunga'] ?? '') ?>" 
                               placeholder="Contoh: Bunga Kredit Konsumtif"
                               required>
                        <?php if (session('errors.nama_bunga')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                                <?= session('errors.nama_bunga') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="persentase_bunga" class="block text-sm font-medium text-gray-700 mb-2">
                            Persentase Bunga (%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="persentase_bunga" 
                               id="persentase_bunga" 
                               step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('persentase_bunga', $bunga['persentase_bunga'] ?? '') ?>" 
                               placeholder="Contoh: 12.5"
                               required>
                        <?php if (session('errors.persentase_bunga')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                                <?= session('errors.persentase_bunga') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="tipe_bunga" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Bunga <span class="text-red-500">*</span>
                        </label>
                        <select name="tipe_bunga" 
                                id="tipe_bunga" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                required>
                            <option value="">Pilih Tipe Bunga</option>
                            <option value="Tetap" <?= old('tipe_bunga', $bunga['tipe_bunga'] ?? '') == 'Tetap' ? 'selected' : '' ?>>Tetap</option>
                            <option value="Mengambang" <?= old('tipe_bunga', $bunga['tipe_bunga'] ?? '') == 'Mengambang' ? 'selected' : '' ?>>Mengambang</option>
                            <option value="Efektif" <?= old('tipe_bunga', $bunga['tipe_bunga'] ?? '') == 'Efektif' ? 'selected' : '' ?>>Efektif</option>
                            <option value="Flat" <?= old('tipe_bunga', $bunga['tipe_bunga'] ?? '') == 'Flat' ? 'selected' : '' ?>>Flat</option>
                        </select>
                        <?php if (session('errors.tipe_bunga')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                                <?= session('errors.tipe_bunga') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="w-full">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" 
                              id="keterangan" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" 
                              placeholder="Masukkan keterangan tambahan"><?= old('keterangan', $bunga['keterangan'] ?? '') ?></textarea>
                    <?php if (session('errors.keterangan')): ?>
                        <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                            <?= session('errors.keterangan') ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="w-full flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/bunga" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="bx bx-times h-4 w-4"></i>
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <i class="bx bx-check h-4 w-4"></i>
                    <?= isset($bunga) ? 'Perbarui Data' : 'Simpan Data' ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
