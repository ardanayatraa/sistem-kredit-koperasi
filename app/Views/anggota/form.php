<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-900">
                <?= isset($anggota) ? 'Edit Anggota' : 'Tambah Anggota Baru' ?>
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                <?= isset($anggota) ? 'Perbarui informasi anggota' : 'Lengkapi form untuk menambah anggota baru' ?>
            </p>
        </div>

        <form action="<?= isset($anggota) ? '/anggota/update/' . esc($anggota['id_anggota']) : '/anggota/create' ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Data Pribadi Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-user text-blue-600 h-5 w-5"></i>
                        Data Pribadi
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nik" 
                               id="nik" 
                               maxlength="16"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('nik', $anggota['nik'] ?? '') ?>" 
                               placeholder="Masukkan NIK 16 digit"
                               required>
                        <?php if (session('errors.nik')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle h-4 w-4"></i>
                                <?= session('errors.nik') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="tempat_lahir" 
                               id="tempat_lahir" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('tempat_lahir', $anggota['tempat_lahir'] ?? '') ?>" 
                               placeholder="Contoh: Jakarta"
                               required>
                        <?php if (session('errors.tempat_lahir')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle h-4 w-4"></i>
                                <?= session('errors.tempat_lahir') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_lahir" 
                               id="tanggal_lahir" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('tanggal_lahir', $anggota['tanggal_lahir'] ?? '') ?>" 
                               required>
                        <?php if (session('errors.tanggal_lahir')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle h-4 w-4"></i>
                                <?= session('errors.tanggal_lahir') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="pekerjaan" 
                               id="pekerjaan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('pekerjaan', $anggota['pekerjaan'] ?? '') ?>" 
                               placeholder="Contoh: Pegawai Swasta"
                               required>
                        <?php if (session('errors.pekerjaan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle h-4 w-4"></i>
                                <?= session('errors.pekerjaan') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" 
                              id="alamat" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" 
                              placeholder="Masukkan alamat lengkap"
                              required><?= old('alamat', $anggota['alamat'] ?? '') ?></textarea>
                    <?php if (session('errors.alamat')): ?>
                        <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <i class="bx bx-exclamation-circle h-4 w-4"></i>
                            <?= session('errors.alamat') ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Data Keanggotaan Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-user-check text-green-600 h-5 w-5"></i>
                        Data Keanggotaan
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="status_keanggotaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Keanggotaan <span class="text-red-500">*</span>
                        </label>
                        <select name="status_keanggotaan"
                                id="status_keanggotaan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                required>
                            <option value="">Pilih Status</option>
                            <option value="Aktif" <?= old('status_keanggotaan', $anggota['status_keanggotaan'] ?? '') == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Tidak Aktif" <?= old('status_keanggotaan', $anggota['status_keanggotaan'] ?? '') == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                            <option value="Pending" <?= old('status_keanggotaan', $anggota['status_keanggotaan'] ?? '') == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        </select>
                        <?php if (session('errors.status_keanggotaan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle h-4 w-4"></i>
                                <?= session('errors.status_keanggotaan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="tanggal_masuk_anggota" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Masuk Anggota <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="tanggal_masuk_anggota"
                               id="tanggal_masuk_anggota"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               value="<?= old('tanggal_masuk_anggota', $anggota['tanggal_masuk_anggota'] ?? '') ?>"
                               required>
                        <?php if (session('errors.tanggal_masuk_anggota')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle h-4 w-4"></i>
                                <?= session('errors.tanggal_masuk_anggota') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Upload Dokumen Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-file-upload text-purple-600 h-5 w-5"></i>
                        Upload Dokumen
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Format yang didukung: PDF, JPG, JPEG, PNG (Maksimal 2MB per file)</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="dokumen_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                            Dokumen KTP <?= !isset($anggota) ? '<span class="text-red-500">*</span>' : '' ?>
                        </label>
                        <div class="relative">
                            <input type="file"
                                   name="dokumen_ktp"
                                   id="dokumen_ktp"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   onchange="previewImage(this, 'ktp')"
                                   <?= !isset($anggota) ? 'required' : '' ?>>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG (Maksimal 2MB)</p>

                        <!-- Image Preview -->
                        <div id="image-preview-ktp" class="mt-3 hidden">
                            <div class="relative inline-block">
                                <img id="preview-img-ktp" src="" alt="Preview KTP" class="max-w-xs max-h-48 border border-gray-300 rounded-lg shadow-sm">
                                <button type="button" onclick="removePreview('ktp')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </div>

                        <?php if (isset($anggota) && !empty($anggota['dokumen_ktp'])): ?>
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="bx bx-file text-blue-600 h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">KTP</p>
                                            <p class="text-xs text-gray-500">File saat ini: <?= esc($anggota['dokumen_ktp']) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="previewFile\('<?= esc($anggota['dokumen_ktp']) ?>', 'ktp')" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                            <i class="bx bx-show h-3 w-3"></i>
                                            Preview
                                        </button>
                                        <a href="/anggota/view-document/<?= esc($anggota['dokumen_ktp']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                            <i class="bx bx-download h-3 w-3"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (session('errors.dokumen_ktp')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle h-4 w-4"></i>
                                <?= session('errors.dokumen_ktp') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="dokumen_kk" class="block text-sm font-medium text-gray-700 mb-2">
                            Dokumen KK <?= !isset($anggota) ? '<span class="text-red-500">*</span>' : '' ?>
                        </label>
                        <div class="relative">
                            <input type="file"
                                   name="dokumen_kk"
                                   id="dokumen_kk"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   onchange="previewImage(this, 'kk')"
                                   <?= !isset($anggota) ? 'required' : '' ?>>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG (Maksimal 2MB)</p>

                        <!-- Image Preview -->
                        <div id="image-preview-kk" class="mt-3 hidden">
                            <div class="relative inline-block">
                                <img id="preview-img-kk" src="" alt="Preview KK" class="max-w-xs max-h-48 border border-gray-300 rounded-lg shadow-sm">
                                <button type="button" onclick="removePreview('kk')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </div>

                        <?php if (isset($anggota) && !empty($anggota['dokumen_kk'])): ?>
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="bx bx-file text-blue-600 h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Kartu Keluarga</p>
                                            <p class="text-xs text-gray-500">File saat ini: <?= esc($anggota['dokumen_kk']) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="previewFile\('<?= esc($anggota['dokumen_kk']) ?>', 'kk')" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                            <i class="bx bx-show h-3 w-3"></i>
                                            Preview
                                        </button>
                                        <a href="/anggota/view-document/<?= esc($anggota['dokumen_kk']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                            <i class="bx bx-download h-3 w-3"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (session('errors.dokumen_kk')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle h-4 w-4"></i>
                                <?= session('errors.dokumen_kk') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="dokumen_slip_gaji" class="block text-sm font-medium text-gray-700 mb-2">
                            Dokumen Slip Gaji <?= !isset($anggota) ? '<span class="text-red-500">*</span>' : '' ?>
                        </label>
                        <div class="relative">
                            <input type="file"
                                   name="dokumen_slip_gaji"
                                   id="dokumen_slip_gaji"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   onchange="previewImage(this, 'slip_gaji')"
                                   <?= !isset($anggota) ? 'required' : '' ?>>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG (Maksimal 2MB)</p>

                        <!-- Image Preview -->
                        <div id="image-preview-slip_gaji" class="mt-3 hidden">
                            <div class="relative inline-block">
                                <img id="preview-img-slip_gaji" src="" alt="Preview Slip Gaji" class="max-w-xs max-h-48 border border-gray-300 rounded-lg shadow-sm">
                                <button type="button" onclick="removePreview('slip_gaji')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </div>

                        <?php if (isset($anggota) && !empty($anggota['dokumen_slip_gaji'])): ?>
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="bx bx-file text-blue-600 h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Slip Gaji</p>
                                            <p class="text-xs text-gray-500">File saat ini: <?= esc($anggota['dokumen_slip_gaji']) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="previewFile\('<?= esc($anggota['dokumen_slip_gaji']) ?>', 'slip_gaji')" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                            <i class="bx bx-show h-3 w-3"></i>
                                            Preview
                                        </button>
                                        <a href="/anggota/view-document/<?= esc($anggota['dokumen_slip_gaji']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                            <i class="bx bx-download h-3 w-3"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (session('errors.dokumen_slip_gaji')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle h-4 w-4"></i>
                                <?= session('errors.dokumen_slip_gaji') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/anggota" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="bx bx-times h-4 w-4"></i>
                    Batal
                </a>
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <i class="bx bx-save h-4 w-4"></i>
                    <?= isset($anggota) ? 'Perbarui Data' : 'Simpan Data' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// File upload preview and validation
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = ['dokumen_ktp', 'dokumen_kk', 'dokumen_slip_gaji'];

    fileInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function(e) {
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

    // NIK validation
    const nikInput = document.getElementById('nik');
    if (nikInput) {
        nikInput.addEventListener('input', function(e) {
            // Only allow numbers
            e.target.value = e.target.value.replace(/[^0-9]/g, '');

            // Limit to 16 characters
            if (e.target.value.length > 16) {
                e.target.value = e.target.value.slice(0, 16);
            }
        });
    }
});

// File preview function
function previewFile(filename, type) {
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

    // Load file content
    const previewContent = modal.querySelector('#preview-content');
    const fileUrl = `/anggota/view-document/${filename}`;

    // Check if it's an image
    if (filename.toLowerCase().match(/\.(jpg|jpeg|png)$/)) {
        previewContent.innerHTML = `<img src="${fileUrl}" alt="${type}" class="max-w-full max-h-full object-contain" onload="this.previousElementSibling?.remove()" onerror="this.parentElement.innerHTML='<div class=\'text-center\'><i class=\'bx bx-error h-12 w-12 text-red-400 mb-4\'></i><p class=\'text-red-600\'>Gagal memuat preview gambar</p></div>'">`;
    } else if (filename.toLowerCase().endsWith('.pdf')) {
        previewContent.innerHTML = `
            <div class="text-center">
                <i class="bx bx-file-pdf h-12 w-12 text-red-400 mb-4"></i>
                <p class="text-gray-600 mb-4">File PDF - Klik tombol download untuk melihat</p>
                <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    <i class="bx bx-download h-4 w-4"></i>
                    Download PDF
                </a>
            </div>
        `;
    } else {
        previewContent.innerHTML = `
            <div class="text-center">
                <i class="bx bx-file h-12 w-12 text-gray-400 mb-4"></i>
                <p class="text-gray-600 mb-4">File tidak dapat dipreview</p>
                <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    <i class="bx bx-download h-4 w-4"></i>
                    Download File
                </a>
            </div>
        `;
    }

}

function previewImage(input, type) {
    const file = input.files[0];
    const previewDiv = document.getElementById('image-preview-' + type);
    const previewImg = document.getElementById('preview-img-' + type);

    if (!file) {
        previewDiv.classList.add('hidden');
        return;
    }

    // Check if image
    const allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (allowedImageTypes.includes(file.type)) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewDiv.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        // Not an image, hide preview
        previewDiv.classList.add('hidden');
    }
}

function removePreview(type) {
    const previewDiv = document.getElementById('image-preview-' + type);
    const previewImg = document.getElementById('preview-img-' + type);
    const input = document.getElementById('dokumen_' + type);

    previewDiv.classList.add('hidden');
    previewImg.src = '';
    input.value = '';
}
</script>
<?= $this->endSection() ?>
