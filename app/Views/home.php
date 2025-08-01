<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
    <div class="user-info mb-8">
        <p class="text-lg mb-2">Anda login sebagai: <strong class="text-blue-400"><?= esc(session()->get('nama_lengkap')) ?></strong></p>
        <p class="text-md text-gray-400">Username: <strong class="font-medium"><?= esc(session()->get('username')) ?></strong></p>
        <p class="text-md text-gray-400">Email: <strong class="font-medium"><?= esc(session()->get('email')) ?></strong></p>
        <p class="text-md text-gray-400">Level: <strong class="font-medium"><?= esc(session()->get('level')) ?></strong></p>
    </div>

    <div class="nav-links">
        <h2 class="text-xl font-semibold mb-4 text-gray-300">Navigasi Cepat:</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="/anggota" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md text-center transition duration-200">Kelola Anggota</a>
            <a href="/bunga" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md text-center transition duration-200">Kelola Bunga</a>
            <a href="/kredit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md text-center transition duration-200">Kelola Kredit</a>
            <a href="/angsuran" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md text-center transition duration-200">Kelola Angsuran</a>
            <a href="/pembayaran-angsuran" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md text-center transition duration-200">Kelola Pembayaran Angsuran</a>
            <a href="/pencairan" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md text-center transition duration-200">Kelola Pencairan</a>
            <a href="/user" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md text-center transition duration-200">Kelola User</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
