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
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
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
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <?= session('errors.alamat') ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Data Keanggotaan Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Data Keanggotaan
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_pendaftaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_pendaftaran" 
                               id="tanggal_pendaftaran" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('tanggal_pendaftaran', $anggota['tanggal_pendaftaran'] ?? date('Y-m-d')) ?>" 
                               required>
                        <?php if (session('errors.tanggal_pendaftaran')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.tanggal_pendaftaran') ?>
                            </p>
                        <?php endif; ?>
                    </div>

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
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.status_keanggotaan') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Upload Dokumen Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
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
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   <?= !isset($anggota) ? 'required' : '' ?>>
                        </div>
                        <?php if (isset($anggota) && !empty($anggota['dokumen_ktp'])): ?>
                            <p class="text-sm text-green-600 mt-1">File saat ini: <?= esc($anggota['dokumen_ktp']) ?></p>
                        <?php endif; ?>
                        <?php if (session('errors.dokumen_ktp')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   <?= !isset($anggota) ? 'required' : '' ?>>
                        </div>
                        <?php if (isset($anggota) && !empty($anggota['dokumen_kk'])): ?>
                            <p class="text-sm text-green-600 mt-1">File saat ini: <?= esc($anggota['dokumen_kk']) ?></p>
                        <?php endif; ?>
                        <?php if (session('errors.dokumen_kk')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   <?= !isset($anggota) ? 'required' : '' ?>>
                        </div>
                        <?php if (isset($anggota) && !empty($anggota['dokumen_slip_gaji'])): ?>
                            <p class="text-sm text-green-600 mt-1">File saat ini: <?= esc($anggota['dokumen_slip_gaji']) ?></p>
                        <?php endif; ?>
                        <?php if (session('errors.dokumen_slip_gaji')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.dokumen_slip_gaji') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/anggota" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
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
</script>

<?= $this->endSection() ?>
