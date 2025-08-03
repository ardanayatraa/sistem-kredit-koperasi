<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">
                <?= isset($pembayaran_angsuran) ? 'Edit Pembayaran Angsuran' : 'Tambah Pembayaran Angsuran' ?>
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                <?= isset($pembayaran_angsuran) ? 'Perbarui data pembayaran angsuran' : 'Tambahkan pembayaran angsuran baru' ?>
            </p>
        </div>
        <a href="/pembayaran_angsuran" class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors w-fit">
            <i class="bx bx-arrow-left h-4 w-4"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 sm:p-6">
            <form action="<?= isset($pembayaran_angsuran) ? '/pembayaran_angsuran/update/' . $pembayaran_angsuran['id_pembayaran_angsuran'] : '/pembayaran_angsuran/store' ?>" method="post" class="space-y-4 sm:space-y-6">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Angsuran -->
                    <div class="lg:col-span-2">
                        <label for="id_angsuran" class="block text-sm font-medium text-gray-700 mb-2">
                            Angsuran <span class="text-red-500">*</span>
                        </label>
                        <select name="id_angsuran" id="id_angsuran" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">Pilih Angsuran</option>
                            <?php if (isset($angsuran_list)): ?>
                                <?php foreach ($angsuran_list as $angsuran): ?>
                                    <option value="<?= $angsuran['id_angsuran'] ?>" 
                                            <?= (isset($pembayaran_angsuran) && $pembayaran_angsuran['id_angsuran'] == $angsuran['id_angsuran']) ? 'selected' : '' ?>>
                                        <?= esc($angsuran['nama_anggota']) ?> - Angsuran ke-<?= $angsuran['angsuran_ke'] ?> (<?= number_format($angsuran['jumlah_angsuran'], 0, ',', '.') ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('id_angsuran')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $validation->getError('id_angsuran') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Tanggal Bayar -->
                    <div>
                        <label for="tanggal_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Bayar <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_bayar" id="tanggal_bayar" required
                               value="<?= isset($pembayaran_angsuran) ? $pembayaran_angsuran['tanggal_bayar'] : date('Y-m-d') ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <?php if (isset($validation) && $validation->hasError('tanggal_bayar')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $validation->getError('tanggal_bayar') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Jumlah Bayar -->
                    <div>
                        <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Bayar <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input type="text" name="jumlah_bayar" id="jumlah_bayar" required
                                   value="<?= isset($pembayaran_angsuran) ? number_format($pembayaran_angsuran['jumlah_bayar'], 0, ',', '.') : '' ?>"
                                   placeholder="0"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <?php if (isset($validation) && $validation->hasError('jumlah_bayar')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $validation->getError('jumlah_bayar') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Keterangan -->
                    <div class="lg:col-span-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                                  placeholder="Masukkan keterangan pembayaran (opsional)"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm resize-none"><?= isset($pembayaran_angsuran) ? esc($pembayaran_angsuran['keterangan']) : '' ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('keterangan')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $validation->getError('keterangan') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="bx bx-check h-4 w-4"></i>
                        <?= isset($pembayaran_angsuran) ? 'Update' : 'Simpan' ?>
                    </button>
                    <a href="/pembayaran_angsuran" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="bx bx-times h-4 w-4"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Format currency input
document.getElementById('jumlah_bayar').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d]/g, '');
    if (value) {
        e.target.value = new Intl.NumberFormat('id-ID').format(value);
    }
});

// Remove formatting before form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const jumlahBayar = document.getElementById('jumlah_bayar');
    jumlahBayar.value = jumlahBayar.value.replace(/[^\d]/g, '');
});
</script>
<?= $this->endSection() ?>
