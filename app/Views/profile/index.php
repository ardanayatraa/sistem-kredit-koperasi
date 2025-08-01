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

        <form action="/profile/update" method="post" class="w-full p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Data Pribadi Section -->
            <div class="w-full space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
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
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
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
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
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
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.password') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="w-full flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Perbarui Profile
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>