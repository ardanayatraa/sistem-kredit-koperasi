<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-900">
                Profile Saya
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Perbarui informasi pribadi Anda
            </p>
        </div>

        <form action="<?= session()->get('level') === 'Anggota' && isset($anggota) ? '/profile/update-anggota' : '/profile/update' ?>" method="post" <?= session()->get('level') === 'Anggota' && isset($anggota) ? 'enctype="multipart/form-data"' : '' ?> class="w-full p-6 space-y-6">
            <?= csrf_field() ?>

            <?php if (session()->get('level') === 'Anggota' && !isset($anggota)): ?>
                <!-- Warning for incomplete anggota data -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="bx bx-exclamation-triangle text-yellow-600 mt-0.5 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">Data Anggota Belum Lengkap</h3>
                            <p class="text-sm text-yellow-700 mt-1 mb-3">
                                Sebagai anggota koperasi, Anda perlu melengkapi data anggota untuk dapat mengakses semua fitur sistem.
                            </p>
                            <a href="/profile/complete-anggota-data" class="inline-flex items-center gap-2 px-3 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                                <i class="bx bx-plus h-4 w-4"></i>
                                Lengkapi Data Anggota
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Data Pribadi Section -->
            <div class="w-full space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-user text-blue-600 h-5 w-5"></i>
                        Data Pribadi
                    </h3>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="w-full">
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_lengkap" 
                               id="nama_lengkap" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('nama_lengkap', $user['nama_lengkap'] ?? '') ?>" 
                               placeholder="Masukkan nama lengkap"
                               required>
                        <?php if (session('errors.nama_lengkap')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.nama_lengkap') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="username" 
                               id="username" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('username', $user['username'] ?? '') ?>" 
                               placeholder="Masukkan username"
                               required>
                        <?php if (session('errors.username')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.username') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('email', $user['email'] ?? '') ?>" 
                               placeholder="Masukkan email"
                               required>
                        <?php if (session('errors.email')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.email') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="w-full">
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">
                            No. HP
                        </label>
                        <input type="text" 
                               name="no_hp" 
                               id="no_hp" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('no_hp', $user['no_hp'] ?? '') ?>" 
                               placeholder="Contoh: 08123456789">
                        <?php if (session('errors.no_hp')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.no_hp') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Data Akun Section -->
            <div class="w-full space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-shield-alt text-green-600 h-5 w-5"></i>
                        Data Akun
                    </h3>
                </div>

                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="w-full">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru (opsional)
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                        <?php if (session('errors.password')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                <?= session('errors.password') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (session()->get('level') === 'Anggota' && isset($anggota)): ?>
                <!-- Data Anggota Section -->
                <div class="w-full space-y-4">
                    <div class="border-b border-gray-200 pb-2">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                            <i class="bx bx-users text-purple-600 h-5 w-5"></i>
                            Data Anggota Koperasi
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
                                   value="<?= old('nik', $anggota['nik'] ?? '') ?>"
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
                                   value="<?= old('tempat_lahir', $anggota['tempat_lahir'] ?? '') ?>"
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
                                   value="<?= old('tanggal_lahir', $anggota['tanggal_lahir'] ?? '') ?>"
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
                                   value="<?= old('pekerjaan', $anggota['pekerjaan'] ?? '') ?>"
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
                                      required><?= old('alamat', $anggota['alamat'] ?? '') ?></textarea>
                            <?php if (session('errors.alamat')): ?>
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                    <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                    <?= session('errors.alamat') ?>
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
                            Unggah dokumen baru jika ingin mengganti (opsional). Format: PDF, JPG, JPEG, PNG (maksimal 2MB per file)
                        </p>
                    </div>

                    <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="w-full">
                            <label for="dokumen_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                                Dokumen KTP
                            </label>
                            <input type="file"
                                   name="dokumen_ktp"
                                   id="dokumen_ktp"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <p class="text-xs text-gray-500 mt-1">
                                File saat ini: <?= !empty($anggota['dokumen_ktp']) ? $anggota['dokumen_ktp'] : 'Tidak ada' ?>
                            </p>
                            <?php if (session('errors.dokumen_ktp')): ?>
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                    <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                    <?= session('errors.dokumen_ktp') ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="w-full">
                            <label for="dokumen_kk" class="block text-sm font-medium text-gray-700 mb-2">
                                Dokumen KK
                            </label>
                            <input type="file"
                                   name="dokumen_kk"
                                   id="dokumen_kk"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <p class="text-xs text-gray-500 mt-1">
                                File saat ini: <?= !empty($anggota['dokumen_kk']) ? $anggota['dokumen_kk'] : 'Tidak ada' ?>
                            </p>
                            <?php if (session('errors.dokumen_kk')): ?>
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                    <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                    <?= session('errors.dokumen_kk') ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="w-full">
                            <label for="dokumen_slip_gaji" class="block text-sm font-medium text-gray-700 mb-2">
                                Dokumen Slip Gaji
                            </label>
                            <input type="file"
                                   name="dokumen_slip_gaji"
                                   id="dokumen_slip_gaji"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <p class="text-xs text-gray-500 mt-1">
                                File saat ini: <?= !empty($anggota['dokumen_slip_gaji']) ? $anggota['dokumen_slip_gaji'] : 'Tidak ada' ?>
                            </p>
                            <?php if (session('errors.dokumen_slip_gaji')): ?>
                                <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                    <i class="bx bx-exclamation-circle text-red-600 h-4 w-4"></i>
                                    <?= session('errors.dokumen_slip_gaji') ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="w-full flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <i class="bx bx-check h-4 w-4"></i>
                    Perbarui Profile
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
