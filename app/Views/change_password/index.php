<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Ganti Password</h1>
                <p class="text-gray-600">Ubah password akun Anda untuk meningkatkan keamanan.</p>
            </div>
        </div>
    </div>

    <!-- Change Password Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="max-w-md mx-auto">
            <!-- Success Message -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                    <div class="flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                    <div class="flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?= base_url('change-password') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 <?= isset($validation) && $validation->hasError('current_password') ? 'border-red-500' : '' ?>"
                               placeholder="Masukkan password saat ini"
                               required>
                        <button type="button" onclick="togglePassword('current_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <?php if (isset($validation) && $validation->hasError('current_password')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('current_password') ?></p>
                    <?php endif; ?>
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 <?= isset($validation) && $validation->hasError('new_password') ? 'border-red-500' : '' ?>"
                               placeholder="Masukkan password baru"
                               required>
                        <button type="button" onclick="togglePassword('new_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <?php if (isset($validation) && $validation->hasError('new_password')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('new_password') ?></p>
                    <?php endif; ?>
                    <p class="mt-1 text-sm text-gray-500">Password minimal 6 karakter</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 <?= isset($validation) && $validation->hasError('confirm_password') ? 'border-red-500' : '' ?>"
                               placeholder="Konfirmasi password baru"
                               required>
                        <button type="button" onclick="togglePassword('confirm_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <?php if (isset($validation) && $validation->hasError('confirm_password')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('confirm_password') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ganti Password
                    </button>
                </div>

                <!-- Back Link -->
                <div class="text-center">
                    <a href="<?= base_url('dashboard') ?>" class="text-sm text-indigo-600 hover:text-indigo-500">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Tips -->
    <div class="bg-blue-50 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Tips Keamanan Password</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol</li>
                        <li>Minimal 8 karakter untuk keamanan yang lebih baik</li>
                        <li>Jangan gunakan informasi personal seperti nama atau tanggal lahir</li>
                        <li>Ganti password secara berkala</li>
                        <li>Jangan bagikan password Anda kepada orang lain</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('svg');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
        `;
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

// Password strength indicator
document.getElementById('new_password').addEventListener('input', function(e) {
    const password = e.target.value;
    let strength = 0;
    let feedback = [];

    // Length check
    if (password.length >= 8) {
        strength += 1;
    } else {
        feedback.push('Minimal 8 karakter');
    }

    // Lowercase check
    if (/[a-z]/.test(password)) {
        strength += 1;
    } else {
        feedback.push('Gunakan huruf kecil');
    }

    // Uppercase check
    if (/[A-Z]/.test(password)) {
        strength += 1;
    } else {
        feedback.push('Gunakan huruf besar');
    }

    // Number check
    if (/\d/.test(password)) {
        strength += 1;
    } else {
        feedback.push('Gunakan angka');
    }

    // Special character check
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
        strength += 1;
    } else {
        feedback.push('Gunakan simbol');
    }

    // Update UI based on strength (optional)
    // You can add visual indicators here
});

// Confirm password validation
document.getElementById('confirm_password').addEventListener('input', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = e.target.value;
    
    if (newPassword !== confirmPassword && confirmPassword.length > 0) {
        e.target.style.borderColor = '#ef4444';
    } else {
        e.target.style.borderColor = '#d1d5db';
    }
});
</script>
<?= $this->endSection() ?>