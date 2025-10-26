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

                    <!-- Upload Bukti Pembayaran -->
                    <div class="lg:col-span-2">
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Bukti Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file"
                                   name="bukti_pembayaran"
                                   id="bukti_pembayaran"
                                   accept=".jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   onchange="previewImage(this)"
                                   required>
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

                        <?php if (isset($pembayaran_angsuran) && !empty($pembayaran_angsuran['bukti_pembayaran'])): ?>
                            <div class="mt-2 p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="bx bx-file text-blue-600 h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Bukti Pembayaran</p>
                                            <p class="text-xs text-gray-500">File saat ini: <?= esc($pembayaran_angsuran['bukti_pembayaran']) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" onclick="previewExistingImage('<?= esc($pembayaran_angsuran['bukti_pembayaran']) ?>', 'pembayaran')" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                            <i class="bx bx-show h-3 w-3"></i>
                                            Preview
                                        </button>
                                        <a href="/pembayaran_angsuran/view-document/<?= esc($pembayaran_angsuran['bukti_pembayaran']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                            <i class="bx bx-download h-3 w-3"></i>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($validation) && $validation->hasError('bukti_pembayaran')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $validation->getError('bukti_pembayaran') ?></p>
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
    document.getElementById('bukti_pembayaran').value = '';
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
    const fileUrl = `/pembayaran_angsuran/view-document/${filename}`;

    previewContent.innerHTML = `<img src="${fileUrl}" alt="${type}" class="max-w-full max-h-full object-contain" onload="this.previousElementSibling?.remove()" onerror="this.parentElement.innerHTML='<div class=\'text-center\'><i class=\'bx bx-error h-12 w-12 text-red-400 mb-4\'></i><p class=\'text-red-600\'>Gagal memuat preview gambar</p></div>'">`;
}

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
