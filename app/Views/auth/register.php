<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Koperasi Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://cdn.jsdelivr.net/gh/JetBrains/JetBrainsMono@2.304/web/JetBrainsMono.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: #fafafa;
        }
    </style>
</head>
<body class="font-sans min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-gray-900 rounded-lg mx-auto mb-4 flex items-center justify-center">
                <i class="bx bx-building text-white w-6 h-6 flex items-center justify-center"></i>
            </div>
            <h1 class="text-xl font-medium text-gray-900 mb-1">Daftar</h1>
            <p class="text-sm text-gray-500">Koperasi Management System</p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                    <ul class="space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li>• <?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/register" method="post" class="space-y-4">
                <?= csrf_field() ?>
                
                <!-- Nama Lengkap Field -->
                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap
                    </label>
                    <input type="text" 
                           id="nama_lengkap" 
                           name="nama_lengkap" 
                           value="<?= old('nama_lengkap') ?>" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 text-sm"
                           placeholder="Masukkan nama lengkap">
                </div>

                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                        Username
                    </label>
                    <input type="text"
                           id="username"
                           name="username"
                           value="<?= old('username') ?>"
                           required
                           pattern="[a-zA-Z0-9_]+"
                           title="Username hanya boleh berisi huruf, angka, dan underscore (tidak boleh ada spasi)"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 text-sm"
                           placeholder="Masukkan username (tanpa spasi)">
                    <p class="text-xs text-gray-500 mt-1">Username hanya boleh berisi huruf, angka, dan underscore (_)</p>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="<?= old('email') ?>" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 text-sm"
                           placeholder="Masukkan email">
                </div>

                <!-- No HP Field -->
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">
                        No HP
                    </label>
                    <input type="text" 
                           id="no_hp" 
                           name="no_hp" 
                           value="<?= old('no_hp') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 text-sm"
                           placeholder="Masukkan nomor HP">
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 text-sm"
                               placeholder="Masukkan password">
                        <button type="button" 
                                onclick="togglePassword('password', 'eye-icon-1')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="eye-icon-1" class="bx bx-eye w-4 h-4 text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               required
                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 text-sm"
                               placeholder="Konfirmasi password">
                        <button type="button" 
                                onclick="togglePassword('confirm_password', 'eye-icon-2')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="eye-icon-2" class="bx bx-eye w-4 h-4 text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" 
                        class="w-full bg-gray-900 text-white py-2 px-4 rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 text-sm font-medium">
                    Daftar
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center text-sm text-gray-600">
                Sudah punya akun? 
                <a href="/login" class="text-gray-900 hover:underline font-medium">
                    Login di sini
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-xs text-gray-400">
                © 2025
            </p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Auto-hide flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('[class*="bg-red-50"]');
            flashMessages.forEach(message => {
                message.style.transition = 'opacity 0.3s ease';
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 300);
            });
        }, 4000);

        // Password match validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.setCustomValidity('Password tidak cocok');
                this.classList.add('border-red-300');
                this.classList.remove('border-gray-300');
            } else {
                this.setCustomValidity('');
                this.classList.remove('border-red-300');
                this.classList.add('border-gray-300');
            }
        });
    </script>
</body>
</html>
