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
                        <i class="bx bx-file-invoice-dollar text-blue-600 h-5 w-5"></i>
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
                                    <?= 'Kredit #' . $kredit['id_kredit'] . ' - ' . esc($kredit['nama_lengkap']) . ' (Rp ' . number_format($kredit['jumlah_pengajuan'], 0, ',', '.') . ')' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (session('errors.id_kredit')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
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
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
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
                               placeholder="5000000"
                               min="100000"
                               max="999999999999"
                               step="1000"
                               required>
                        <?php if (session('errors.jumlah_dicairkan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
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
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                                <?= session('errors.metode_pencairan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="id_bunga" class="block text-sm font-medium text-gray-700 mb-2">
                            Suku Bunga <span class="text-red-500">*</span>
                        </label>
                        <select name="id_bunga"
                                id="id_bunga"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                            <option value="">Pilih Suku Bunga</option>
                            <?php if (isset($bungaOptions) && !empty($bungaOptions)): ?>
                                <?php foreach ($bungaOptions as $bunga):
                                    $selected = '';
                                    $oldValue = old('id_bunga', $pencairan['id_bunga'] ?? '');
                                    
                                    if ((string)$oldValue === (string)$bunga['id_bunga']) {
                                        $selected = 'selected';
                                    }
                                ?>
                                    <option value="<?= $bunga['id_bunga'] ?>" <?= $selected ?>>
                                        <?= esc($bunga['nama_bunga']) . ' - ' . $bunga['persentase_bunga'] . '% (' . esc($bunga['tipe_bunga']) . ')' ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Tidak ada data bunga tersedia</option>
                            <?php endif; ?>
                        </select>
                        <?php if (session('errors.id_bunga')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                                <?= session('errors.id_bunga') ?>
                            </p>
                        <?php endif; ?>
                        
                        <!-- Debug info -->
                        <?php if (ENVIRONMENT === 'development'): ?>
                        <small class="text-gray-500">
                            Debug: <?= isset($bungaOptions) ? count($bungaOptions) . ' bunga tersedia' : 'bungaOptions tidak tersedia' ?>
                        </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Upload Bukti Transfer Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-file-invoice text-green-600 h-5 w-5"></i>
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
                               accept=".jpg,.jpeg,.png"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               onchange="previewImage(this)"
                               <?= !isset($pencairan) ? 'required' : '' ?>>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG (Maksimal 2MB)</p>

                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-3 hidden">
                        <div class="relative inline-block">
                            <img id="preview-img" src="" alt="Preview" class="max-w-xs max-h-48 border border-gray-300 rounded-lg shadow-sm">
                            <button type="button" onclick="removePreview()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                    </div>

                    <?php if (isset($pencairan) && !empty($pencairan['bukti_transfer'])): ?>
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg border">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="bx bx-file text-blue-600 h-5 w-5"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Bukti Transfer</p>
                                        <p class="text-xs text-gray-500">File saat ini: <?= esc($pencairan['bukti_transfer']) ?></p>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" onclick="previewExistingImage('<?= esc($pencairan['bukti_transfer']) ?>', 'pencairan')" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        <i class="bx bx-show h-3 w-3"></i>
                                        Preview
                                    </button>
                                    <a href="/pencairan/view-document/<?= esc($pencairan['bukti_transfer']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                        <i class="bx bx-download h-3 w-3"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (session('errors.bukti_transfer')): ?>
                        <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <i class="bx bx-exclamation-circle text-red-500 h-4 w-4"></i>
                            <?= session('errors.bukti_transfer') ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/pencairan" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="bx bx-times h-4 w-4"></i>
                    Batal
                </a>
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <i class="bx bx-check h-4 w-4"></i>
                    <?= isset($pencairan) ? 'Perbarui Data' : 'Simpan Data' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Image preview function
function previewImage(input) {
    const file = input.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            input.value = '';
            preview.classList.add('hidden');
            return;
        }

        // Validate file type (only images)
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
            input.value = '';
            preview.classList.add('hidden');
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}

function removePreview() {
    document.getElementById('bukti_transfer').value = '';
    document.getElementById('image-preview').classList.add('hidden');
}

function previewExistingImage(filename, type) {
    // Create modal for preview
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg max-w-4xl max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Preview ${type.toUpperCase()}</h3>
                <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x h-6 w-6"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="w-full h-[70vh] flex items-center justify-center bg-gray-100 rounded">
                    <div id="preview-content" class="text-center">
                        <i class="bx bx-loader-alt bx-spin h-12 w-12 text-gray-400 mb-4"></i>
                        <p class="text-gray-600">Loading preview...</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Load image
    const previewContent = modal.querySelector('#preview-content');
    const fileUrl = `/pencairan/view-document/${filename}`;

    previewContent.innerHTML = `<img src="${fileUrl}" alt="${type}" class="max-w-full max-h-full object-contain" onload="this.previousElementSibling?.remove()" onerror="this.parentElement.innerHTML='<div class=\'text-center\'><i class=\'bx bx-error h-12 w-12 text-red-400 mb-4\'></i><p class=\'text-red-600\'>Gagal memuat preview gambar</p></div>'">`;
}

document.addEventListener('DOMContentLoaded', function() {
    // Simple amount validation
    const amountInput = document.getElementById('jumlah_dicairkan');
    if (amountInput) {
        amountInput.addEventListener('blur', function(e) {
            const value = parseInt(e.target.value);
            if (value && value < 100000) {
                alert('Jumlah dicairkan minimal Rp 100.000');
                e.target.focus();
            }
        });
    }
});
</script>

<?= $this->endSection() ?>
