<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-900">
                <?= isset($angsuran) ? 'Edit Angsuran' : 'Tambah Angsuran Baru' ?>
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                <?= isset($angsuran) ? 'Perbarui informasi angsuran' : 'Lengkapi form untuk menambah angsuran baru' ?>
            </p>
        </div>

        <form action="<?= isset($angsuran) ? '/angsuran/update/' . esc($angsuran['id_angsuran']) : '/angsuran/create' ?>" method="post" class="w-full p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Data Angsuran Section -->
            <div class="w-full space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-file-invoice-dollar text-blue-600 h-5 w-5"></i>
                        Data Angsuran
                    </h3>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="w-full">
                        <label for="id_kredit" class="block text-sm font-medium text-gray-700 mb-2">
                            Kredit <span class="text-red-500">*</span>
                        </label>
                        <select name="id_kredit" 
                                id="id_kredit" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                required>
                            <option value="">Pilih Kredit</option>
                            <?php if (!empty($kredit_list)): ?>
                                <?php foreach ($kredit_list as $kredit): ?>
                                    <option value="<?= esc($kredit['id_kredit']) ?>" <?= old('id_kredit', $angsuran['id_kredit'] ?? '') == $kredit['id_kredit'] ? 'selected' : '' ?>>
                                        ID: <?= esc($kredit['id_kredit']) ?> - <?= esc($kredit['nama_anggota']) ?> (Rp <?= number_format($kredit['jumlah_kredit'], 0, ',', '.') ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (session('errors.id_kredit')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                                <?= session('errors.id_kredit') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="angsuran_ke" class="block text-sm font-medium text-gray-700 mb-2">
                            Angsuran Ke <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="angsuran_ke" 
                               id="angsuran_ke" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('angsuran_ke', $angsuran['angsuran_ke'] ?? '') ?>" 
                               placeholder="Contoh: 1"
                               required>
                        <?php if (session('errors.angsuran_ke')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                                <?= session('errors.angsuran_ke') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="jumlah_angsuran" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Angsuran (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="jumlah_angsuran" 
                               id="jumlah_angsuran" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('jumlah_angsuran', $angsuran['jumlah_angsuran'] ?? '') ?>" 
                               placeholder="Contoh: 500000"
                               required>
                        <?php if (session('errors.jumlah_angsuran')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                                <?= session('errors.jumlah_angsuran') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="tgl_jatuh_tempo" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Jatuh Tempo <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="tgl_jatuh_tempo" 
                               id="tgl_jatuh_tempo" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('tgl_jatuh_tempo', $angsuran['tgl_jatuh_tempo'] ?? '') ?>" 
                               required>
                        <?php if (session('errors.tgl_jatuh_tempo')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                                <?= session('errors.tgl_jatuh_tempo') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full md:col-span-2">
                        <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="status_pembayaran" 
                                id="status_pembayaran" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                required>
                            <option value="">Pilih Status</option>
                            <option value="Belum Bayar" <?= old('status_pembayaran', $angsuran['status_pembayaran'] ?? '') == 'Belum Bayar' ? 'selected' : '' ?>>Belum Bayar</option>
                            <option value="Sebagian" <?= old('status_pembayaran', $angsuran['status_pembayaran'] ?? '') == 'Sebagian' ? 'selected' : '' ?>>Sebagian</option>
                            <option value="Lunas" <?= old('status_pembayaran', $angsuran['status_pembayaran'] ?? '') == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                            <option value="Tertunggak" <?= old('status_pembayaran', $angsuran['status_pembayaran'] ?? '') == 'Tertunggak' ? 'selected' : '' ?>>Tertunggak</option>
                        </select>
                        <?php if (session('errors.status_pembayaran')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                                <?= session('errors.status_pembayaran') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="w-full flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/angsuran" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="bx bx-times h-4 w-4"></i>
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <i class="bx bx-check h-4 w-4"></i>
                    <?= isset($angsuran) ? 'Perbarui Data' : 'Simpan Data' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Format currency input
document.addEventListener('DOMContentLoaded', function() {
    const formatCurrency = (input) => {
        // Remove non-numeric characters
        let value = input.value.replace(/[^\d]/g, '');
        
        // Format with thousand separator
        if (value.length > 0) {
            input.value = parseInt(value).toLocaleString('id-ID');
        }
    };
    
    // Format on page load
    const currencyInputs = document.querySelectorAll('#jumlah_angsuran');
    currencyInputs.forEach(input => {
        if (input.value) {
            formatCurrency(input);
        }
        
        // Format on input
        input.addEventListener('input', function() {
            formatCurrency(this);
        });
        
        // Before form submit, remove formatting
        input.form.addEventListener('submit', function() {
            input.value = input.value.replace(/[^\d]/g, '');
        });
    });
});
</script>

<?= $this->endSection() ?>
