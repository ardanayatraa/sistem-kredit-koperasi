<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-900">
                Lengkapi Data Anggota
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Silakan lengkapi data anggota Anda untuk dapat mengakses sistem
            </p>
        </div>

        <form action="/profile/save-anggota-data" method="post" enctype="multipart/form-data" class="w-full p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Informasi Peringatan -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="bx bx-info-circle text-blue-600 mt-0.5 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800">Informasi Penting</h3>
                        <p class="text-sm text-blue-700 mt-1">
                            Sebagai anggota koperasi, Anda wajib melengkapi data anggota untuk dapat menggunakan semua fitur sistem. Pastikan semua data yang Anda masukkan akurat dan dokumen yang diunggah jelas terbaca.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Data Identitas Section -->
            <div class="w-full space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-id-card text-blue-600 h-5 w-5"></i>
                        Data Identitas
                    </h3>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="w-full">
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                            NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nik" 
                               id="nik" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('nik') ?>" 
                               placeholder="Contoh: 1234567890123456"
                               maxlength="16"
                               pattern="[0-9]{16}"
                               required>
                        <p class="text-xs text-gray-500 mt-1">NIK harus terdiri dari 16 digit angka</p>
                        <?php if (session('errors.nik')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.nik') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="tempat_lahir" 
                               id="tempat_lahir" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('tempat_lahir') ?>" 
                               placeholder="Contoh: Jakarta"
                               required>
                        <?php if (session('errors.tempat_lahir')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.tempat_lahir') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_lahir" 
                               id="tanggal_lahir" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('tanggal_lahir') ?>" 
                               required>
                        <?php if (session('errors.tanggal_lahir')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.tanggal_lahir') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="pekerjaan" 
                               id="pekerjaan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('pekerjaan') ?>" 
                               placeholder="Contoh: Pegawai Swasta"
                               required>
                        <?php if (session('errors.pekerjaan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.pekerjaan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" 
                                  id="alamat" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                  placeholder="Masukkan alamat lengkap"
                                  required><?= old('alamat') ?></textarea>
                        <?php if (session('errors.alamat')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.alamat') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="tanggal_pendaftaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="tanggal_pendaftaran"
                               id="tanggal_pendaftaran"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               value="<?= old('tanggal_pendaftaran', date('Y-m-d')) ?>"
                               required>
                        <?php if (session('errors.tanggal_pendaftaran')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.tanggal_pendaftaran') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="tanggal_masuk_anggota" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Masuk Anggota <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="tanggal_masuk_anggota"
                               id="tanggal_masuk_anggota"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               value="<?= old('tanggal_masuk_anggota', date('Y-m-d')) ?>"
                               required>
                        <?php if (session('errors.tanggal_masuk_anggota')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.tanggal_masuk_anggota') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Dokumen Pendukung Section -->
            <div class="w-full space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-file-alt text-green-600 h-5 w-5"></i>
                        Dokumen Pendukung
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Unggah dokumen dalam format PDF, JPG, JPEG, atau PNG (maksimal 2MB per file)
                    </p>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="w-full">
                        <label for="dokumen_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                            Dokumen KTP <span class="text-red-500">*</span>
                        </label>
                        <input type="file"
                               name="dokumen_ktp"
                               id="dokumen_ktp"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept=".pdf,.jpg,.jpeg,.png"
                               onchange="previewImage(this, 'ktp')">
                        <div id="image-preview-ktp" class="mt-3 hidden">
                            <div class="relative inline-block">
                                <img id="preview-img-ktp" src="" alt="Preview KTP" class="max-w-xs max-h-48 border border-gray-300 rounded-lg shadow-sm">
                                <button type="button" onclick="removePreview('ktp')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Preview gambar KTP yang akan diupload</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, JPEG, PNG (Max: 2MB)</p>
                        <?php if (session('errors.dokumen_ktp')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.dokumen_ktp') ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($anggota['dokumen_ktp'])): ?>
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">KTP</p>
                                        <p class="text-xs text-gray-500">File saat ini: <?= esc($anggota['dokumen_ktp']) ?></p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="previewFile('<?= esc($anggota['dokumen_ktp']) ?>', 'ktp')" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                            <i class="bx bx-show h-3 w-3"></i>
                                            Lihat
                                        </button>
                                        <a href="/anggota/view-document/<?= esc($anggota['dokumen_ktp']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                            <i class="bx bx-download h-3 w-3"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="dokumen_kk" class="block text-sm font-medium text-gray-700 mb-2">
                            Dokumen KK <span class="text-red-500">*</span>
                        </label>
                        <input type="file"
                               name="dokumen_kk"
                               id="dokumen_kk"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept=".pdf,.jpg,.jpeg,.png"
                               onchange="previewImage(this, 'kk')">
                        <div id="image-preview-kk" class="mt-3 hidden">
                            <div class="relative inline-block">
                                <img id="preview-img-kk" src="" alt="Preview KK" class="max-w-xs max-h-48 border border-gray-300 rounded-lg shadow-sm">
                                <button type="button" onclick="removePreview('kk')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Preview gambar Kartu Keluarga yang akan diupload</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, JPEG, PNG (Max: 2MB)</p>
                        <?php if (session('errors.dokumen_kk')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.dokumen_kk') ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($anggota['dokumen_kk'])): ?>
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Kartu Keluarga</p>
                                        <p class="text-xs text-gray-500">File saat ini: <?= esc($anggota['dokumen_kk']) ?></p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="previewFile('<?= esc($anggota['dokumen_kk']) ?>', 'kk')" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                            <i class="bx bx-show h-3 w-3"></i>
                                            Lihat
                                        </button>
                                        <a href="/anggota/view-document/<?= esc($anggota['dokumen_kk']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                            <i class="bx bx-download h-3 w-3"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="dokumen_slip_gaji" class="block text-sm font-medium text-gray-700 mb-2">
                            Dokumen Slip Gaji <span class="text-red-500">*</span>
                        </label>
                        <input type="file"
                               name="dokumen_slip_gaji"
                               id="dokumen_slip_gaji"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept=".pdf,.jpg,.jpeg,.png"
                               onchange="previewImage(this, 'slip_gaji')">
                        <div id="image-preview-slip_gaji" class="mt-3 hidden">
                            <div class="relative inline-block">
                                <img id="preview-img-slip_gaji" src="" alt="Preview Slip Gaji" class="max-w-xs max-h-48 border border-gray-300 rounded-lg shadow-sm">
                                <button type="button" onclick="removePreview('slip_gaji')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Preview gambar Slip Gaji yang akan diupload</p>
                        </div>
<script>
function previewImage(input, type) {
    const previewDiv = document.getElementById(`image-preview-${type}`);
    const previewImg = document.getElementById(`preview-img-${type}`);

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileType = file.type.toLowerCase();

        // Check if file is an image
        if (fileType === 'image/jpeg' || fileType === 'image/jpg' || fileType === 'image/png') {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewDiv.classList.remove('hidden');
            };

            reader.readAsDataURL(file);
        } else {
            // For non-image files (like PDF), hide preview
            previewDiv.classList.add('hidden');
        }
    } else {
        // No file selected, hide preview
        previewDiv.classList.add('hidden');
    }
}

function removePreview(type) {
    const previewDiv = document.getElementById(`image-preview-${type}`);
    const fileInput = document.getElementById(`dokumen_${type}`);

    // Hide preview
    previewDiv.classList.add('hidden');

    // Clear file input
    fileInput.value = '';

    // Reset preview image src
    const previewImg = document.getElementById(`preview-img-${type}`);
    previewImg.src = '';
}

function previewFile(filename, type) {
    const modal = document.getElementById('document-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content');

    // Set modal title
    const titles = {
        'ktp': 'Preview Dokumen KTP',
        'kk': 'Preview Dokumen Kartu Keluarga',
        'slip_gaji': 'Preview Dokumen Slip Gaji'
    };
    modalTitle.textContent = titles[type] || 'Preview Dokumen';

    // Clear previous content
    modalContent.innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';

    // Show modal
    modal.classList.remove('hidden');

    // Load content based on file type
    const fileExtension = filename.split('.').pop().toLowerCase();

    if (fileExtension === 'pdf') {
        // For PDF files, show download link
        modalContent.innerHTML = `
            <div class="text-center">
                <i class="bx bx-file text-6xl text-red-500 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 mb-2">File PDF</p>
                <p class="text-gray-600 mb-4">${filename}</p>
                <a href="/anggota/view-document/${filename}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    <i class="bx bx-download"></i>
                    Download PDF
                </a>
            </div>
        `;
    } else if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
        // For image files, show zoomable image
        modalContent.innerHTML = `
            <div class="relative">
                <img id="modal-image" src="/anggota/view-document/${filename}"
                     alt="Preview ${filename}"
                     class="max-w-full max-h-96 mx-auto cursor-zoom-in"
                     onclick="toggleZoom(this)">
                <div class="text-center mt-2 text-sm text-gray-500">
                    Klik gambar untuk zoom in/out
                </div>
            </div>
        `;
    } else {
        // For other file types
        modalContent.innerHTML = `
            <div class="text-center">
                <i class="bx bx-file text-6xl text-gray-500 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 mb-2">File Tidak Dapat Dipreview</p>
                <p class="text-gray-600 mb-4">${filename}</p>
                <a href="/anggota/view-document/${filename}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    <i class="bx bx-download"></i>
                    Download File
                </a>
            </div>
        `;
    }
}

function toggleZoom(img) {
    if (img.classList.contains('zoomed')) {
        img.classList.remove('zoomed');
        img.style.transform = 'scale(1)';
        img.style.cursor = 'zoom-in';
    } else {
        img.classList.add('zoomed');
        img.style.transform = 'scale(1.5)';
        img.style.cursor = 'zoom-out';
    }
}

function closeModal() {
    const modal = document.getElementById('document-modal');
    const modalImage = document.getElementById('modal-image');

    // Reset zoom if image was zoomed
    if (modalImage) {
        modalImage.classList.remove('zoomed');
        modalImage.style.transform = 'scale(1)';
    }

    modal.classList.add('hidden');
}
</script>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, JPEG, PNG (Max: 2MB)</p>
                        <?php if (session('errors.dokumen_slip_gaji')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.dokumen_slip_gaji') ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($anggota['dokumen_slip_gaji'])): ?>
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Slip Gaji</p>
                                        <p class="text-xs text-gray-500">File saat ini: <?= esc($anggota['dokumen_slip_gaji']) ?></p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="previewFile('<?= esc($anggota['dokumen_slip_gaji']) ?>', 'slip_gaji')" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                            <i class="bx bx-show h-3 w-3"></i>
                                            Lihat
                                        </button>
                                        <a href="/anggota/view-document/<?= esc($anggota['dokumen_slip_gaji']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                            <i class="bx bx-download h-3 w-3"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="w-full flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <i class="bx bx-check h-4 w-4"></i>
                    Simpan Data Anggota
                </button>
                <a href="/logout" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                    <i class="bx bx-sign-out-alt h-4 w-4"></i>
                    Keluar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Modal for document preview -->
<div id="document-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title">Preview Dokumen</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        <div class="p-4">
            <div id="modal-content" class="flex justify-center items-center min-h-96">
                <!-- Content will be loaded here -->
            </div>
        </div>
        <div class="flex justify-end gap-3 p-4 border-t">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
