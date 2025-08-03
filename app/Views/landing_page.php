<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kredit Koperasi - Solusi Keuangan Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

    <!-- Header/Navbar -->
    <header class="bg-white shadow-sm py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <a href="/" class="text-2xl font-bold text-blue-700">Sistem Kredit Koperasi</a>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="/" class="text-gray-700 hover:text-blue-600 transition duration-300">Beranda</a></li>
                    <li><a href="/login" class="text-gray-700 hover:text-blue-600 transition duration-300">Login</a></li>
                    <li><a href="/register" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300">Daftar Sekarang</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-blue-700 text-white py-20 px-4 text-center">
        <div class="container mx-auto">
            <h1 class="text-5xl font-extrabold leading-tight mb-6">Solusi Kredit Koperasi Modern untuk Kesejahteraan Anda</h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto">Permudah pengelolaan kredit dan angsuran Anda dengan sistem yang efisien, transparan, dan aman. Bergabunglah dengan koperasi kami hari ini!</p>
            <div class="space-x-4">
                <a href="/register" class="bg-white text-blue-700 hover:bg-gray-100 font-bold py-3 px-8 rounded-full text-lg transition duration-300 shadow-lg">Mulai Sekarang</a>
                <a href="/login" class="border-2 border-white text-white hover:bg-white hover:text-blue-700 font-bold py-3 px-8 rounded-full text-lg transition duration-300">Login Anggota</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 px-4 bg-white">
        <div class="container mx-auto text-center">
            <h2 class="text-4xl font-bold mb-12 text-gray-800">Fitur Unggulan Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 p-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="bx bx-hand-holding-usd text-blue-600 text-4xl mx-auto mb-4"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Pengelolaan Kredit Mudah</h3>
                    <p class="text-gray-600">Ajukan, lacak, dan kelola pinjaman Anda dengan antarmuka yang intuitif dan sederhana.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-gray-50 p-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="bx bx-shield-alt text-blue-600 text-4xl mx-auto mb-4"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Keamanan Data Terjamin</h3>
                    <p class="text-gray-600">Data pribadi dan transaksi Anda dilindungi dengan enkripsi dan standar keamanan terkini.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-gray-50 p-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <div class="text-blue-600 mb-4">
                        <i class="bx bx-chart-line text-blue-600 text-4xl mx-auto mb-4"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Laporan Transaksi Akurat</h3>
                    <p class="text-gray-600">Dapatkan laporan angsuran dan pembayaran yang detail dan akurat kapan saja Anda butuhkan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="bg-blue-600 text-white py-16 px-4 text-center">
        <div class="container mx-auto">
            <h2 class="text-4xl font-bold mb-6">Siap untuk Kemudahan Finansial?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Daftar sekarang dan rasakan pengalaman mengelola keuangan koperasi yang lebih baik.</p>
            <a href="/register" class="bg-white text-blue-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-full text-lg transition duration-300 shadow-lg">Daftar Gratis</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-8 px-4">
        <div class="container mx-auto text-center">
            <p>&copy; <?= date('Y') ?> Sistem Kredit Koperasi. Hak Cipta Dilindungi.</p>
            <p class="mt-2 text-sm">Dibuat dengan ❤️ untuk kemajuan koperasi.</p>
        </div>
    </footer>

</body>
</html>
