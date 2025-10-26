<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Koperasi Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <div class="w-full max-w-sm">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-gray-900 rounded-lg mx-auto mb-4 flex items-center justify-center">
                <i class="bx bx-building text-white w-6 h-6 flex items-center justify-center"></i>
            </div>
            <h1 class="text-xl font-medium text-gray-900 mb-1">Masuk</h1>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                    <ul class="space-y-1">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li>• <?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/login" method="post" class="space-y-4">
                <?= csrf_field() ?>
                
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                        Username atau Email
                    </label>
                    <input type="text"
                           id="username"
                           name="username"
                           value="<?= old('username') ?>"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-gray-900 focus:border-gray-900 text-sm"
                           placeholder="Masukkan username atau email">
                    <p class="text-xs text-gray-500 mt-1">Anda dapat login menggunakan username atau email</p>
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
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i id="eye-icon" class="bx bx-eye w-4 h-4 text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember-me" class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900">
                        <span class="ml-2 text-gray-600">Ingat saya</span>
                    </label>
                    <a href="/forgot-password" class="text-gray-900 hover:underline">
                        Lupa password?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full bg-gray-900 text-white py-2 px-4 rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 text-sm font-medium">
                    Masuk
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center text-sm text-gray-600">
                Belum punya akun? 
                <a href="/register" class="text-gray-900 hover:underline font-medium">
                    Daftar di sini
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
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

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

        // Prevent spaces in username/email field
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');

            usernameInput.addEventListener('input', function(e) {
                // Remove any spaces as they are typed
                if (e.target.value.includes(' ')) {
                    e.target.value = e.target.value.replace(/\s/g, '');
                }
            });

            usernameInput.addEventListener('paste', function(e) {
                // Prevent paste with spaces
                setTimeout(() => {
                    if (e.target.value.includes(' ')) {
                        e.target.value = e.target.value.replace(/\s/g, '');
                    }
                }, 0);
            });

            usernameInput.addEventListener('keydown', function(e) {
                // Prevent space key
                if (e.key === ' ') {
                    e.preventDefault();
                }
            });
        });

        // Auto-hide flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
            flashMessages.forEach(message => {
                message.style.transition = 'opacity 0.3s ease';
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 300);
            });
        }, 4000);
    </script>
</body>
</html>
