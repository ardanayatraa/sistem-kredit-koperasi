<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-900">
                <?= isset($pencairan) ? 'Edit Pencairan' : 'Tambah Pencairan Baru' ?>
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                <?= isset($pencairan) ? 'Perbarui informasi pencairan kredit' : 'Lengkapi form untuk menambah pencairan kredit baru' ?>
            </p>
        </div>

        <form action="<?= isset($pencairan) ? '/pencairan/update/' . esc($pencairan['id_pencairan']) : '/pencairan/create' ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Data Pencairan Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Data Pencairan
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="id_kredit" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Kredit <span class="text-red-500">*</span>
                        </label>
                        <select name="id_kredit"
                                id="id_kredit"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                            <option value="">Pilih Kredit</option>
                            <?php foreach ($kreditOptions as $kredit):
                                $selected = '';
                                $oldValue = old('id_kredit', $pencairan['id_kredit'] ?? '');
                                
                                // Pastikan perbandingan tipe data sama
                                if ((string)$oldValue === (string)$kredit['id_kredit']) {
                                    $selected = 'selected';
                                }
                            ?>
                                <option value="<?= $kredit['id_kredit'] ?>" <?= $selected ?>>
                                    <?= 'Kredit #' . $kredit['id_kredit'] . ' - Anggota: ' . $kredit['id_anggota'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (session('errors.id_kredit')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.id_kredit') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="tanggal_pencairan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pencairan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_pencairan" 
                               id="tanggal_pencairan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('tanggal_pencairan', $pencairan['tanggal_pencairan'] ?? date('Y-m-d')) ?>" 
                               required>
                        <?php if (session('errors.tanggal_pencairan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.tanggal_pencairan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="jumlah_dicairkan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Dicairkan (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="jumlah_dicairkan" 
                               id="jumlah_dicairkan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('jumlah_dicairkan', $pencairan['jumlah_dicairkan'] ?? '') ?>" 
                               placeholder="Contoh: 5000000"
                               required>
                        <?php if (session('errors.jumlah_dicairkan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.jumlah_dicairkan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="metode_pencairan" class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pencairan <span class="text-red-500">*</span>
                        </label>
                        <select name="metode_pencairan" 
                                id="metode_pencairan" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                required>
                            <option value="">Pilih Metode</option>
                            <option value="Transfer Bank" <?= old('metode_pencairan', $pencairan['metode_pencairan'] ?? '') == 'Transfer Bank' ? 'selected' : '' ?>>Transfer Bank</option>
                            <option value="Tunai" <?= old('metode_pencairan', $pencairan['metode_pencairan'] ?? '') == 'Tunai' ? 'selected' : '' ?>>Tunai</option>
                            <option value="Cek" <?= old('metode_pencairan', $pencairan['metode_pencairan'] ?? '') == 'Cek' ? 'selected' : '' ?>>Cek</option>
                            <option value="Lainnya" <?= old('metode_pencairan', $pencairan['metode_pencairan'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                        <?php if (session('errors.metode_pencairan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.metode_pencairan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="id_bunga" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Bunga <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="id_bunga" 
                               id="id_bunga" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('id_bunga', $pencairan['id_bunga'] ?? '') ?>" 
                               placeholder="Masukkan ID bunga"
                               required>
                        <?php if (session('errors.id_bunga')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.id_bunga') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Upload Bukti Transfer Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Bukti Transfer
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Format yang didukung: PDF, JPG, JPEG, PNG (Maksimal 2MB)</p>
                </div>

                <div>
                    <label for="bukti_transfer" class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Transfer <?= !isset($pencairan) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <div class="relative">
                        <input type="file" 
                               name="bukti_transfer" 
                               id="bukti_transfer" 
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               <?= !isset($pencairan) ? 'required' : '' ?>>
                    </div>
                    <?php if (isset($pencairan) && !empty($pencairan['bukti_transfer'])): ?>
                        <p class="text-sm text-green-600 mt-1">File saat ini: <?= esc($pencairan['bukti_transfer']) ?></p>
                    <?php endif; ?>
                    <?php if (session('errors.bukti_transfer')): ?>
                        <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <?= session('errors.bukti_transfer') ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/pencairan" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <?= isset($pencairan) ? 'Perbarui Data' : 'Simpan Data' ?>
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
    const currencyInputs = document.querySelectorAll('#jumlah_dicairkan');
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
    
    // File upload validation
    const fileInput = document.getElementById('bukti_transfer');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    e.target.value = '';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan PDF, JPG, JPEG, atau PNG.');
                    e.target.value = '';
                    return;
                }
            }
        });
    }
});
</script>

<?= $this->endSection() ?>
