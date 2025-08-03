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
                               required>
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, JPEG, PNG (Max: 2MB)</p>
                        <?php if (session('errors.dokumen_ktp')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.dokumen_ktp') ?>
                            </p>
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
                               required>
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, JPEG, PNG (Max: 2MB)</p>
                        <?php if (session('errors.dokumen_kk')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.dokumen_kk') ?>
                            </p>
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
                               required>
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, JPG, JPEG, PNG (Max: 2MB)</p>
                        <?php if (session('errors.dokumen_slip_gaji')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.dokumen_slip_gaji') ?>
                            </p>
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
<?= $this->endSection() ?>
