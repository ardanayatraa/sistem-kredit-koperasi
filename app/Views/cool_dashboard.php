<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<!-- Stats Cards -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700 hover:border-blue-500 transition duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-300">Total Anggota</h3>
            <i class="bx bx-users text-blue-400 text-2xl"></i>
        </div>
        <p class="text-5xl font-bold text-white">1,234</p>
        <p class="text-sm text-gray-400 mt-2">Anggota aktif</p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700 hover:border-green-500 transition duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-300">Kredit Aktif</h3>
            <i class="bx bx-hand-holding-usd text-green-400 text-2xl"></i>
        </div>
        <p class="text-5xl font-bold text-white">567</p>
        <p class="text-sm text-gray-400 mt-2">Pengajuan disetujui</p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700 hover:border-purple-500 transition duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-300">Total Angsuran</h3>
            <i class="bx bx-file-invoice-dollar text-purple-400 text-2xl"></i>
        </div>
        <p class="text-5xl font-bold text-white">8,901</p>
        <p class="text-sm text-gray-400 mt-2">Angsuran terbayar</p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700 hover:border-red-500 transition duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-300">Pencairan Hari Ini</h3>
            <i class="bx bx-money-bill-wave text-red-400 text-2xl"></i>
        </div>
        <p class="text-5xl font-bold text-white">Rp 12.5 Jt</p>
        <p class="text-sm text-gray-400 mt-2">Total pencairan</p>
    </div>
</section>

<!-- Charts Section -->
<section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
        <h3 class="text-xl font-semibold text-gray-300 mb-4">Statistik Kredit Bulanan</h3>
        <!-- Placeholder for chart -->
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
        <h3 class="text-xl font-semibold text-gray-300 mb-4">Status Angsuran</h3>
        <!-- Placeholder for chart -->
    </div>
</section>

<!-- Recent Activities / Quick Actions -->
<section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
        <h3 class="text-xl font-semibold text-gray-300 mb-4">Aktivitas Terbaru</h3>
        <ul class="space-y-4">
            <li class="flex items-center bg-gray-700 p-3 rounded-md">
                <i class="bx bx-check-circle text-green-400 mr-3"></i>
                <p class="text-gray-300 text-sm">Pengajuan kredit baru dari <span class="font-semibold text-white">Budi Santoso</span> disetujui.</p>
            </li>
            <li class="flex items-center bg-gray-700 p-3 rounded-md">
                <i class="bx bx-clock text-blue-400 mr-3"></i>
                <p class="text-gray-300 text-sm">Pembayaran angsuran diterima dari <span class="font-semibold text-white">Siti Aminah</span>.</p>
            </li>
            <li class="flex items-center bg-gray-700 p-3 rounded-md">
                <i class="bx bx-exclamation-triangle text-yellow-400 mr-3"></i>
                <p class="text-gray-300 text-sm">Dokumen KTP <span class="font-semibold text-white">Agus Wijaya</span> perlu diverifikasi.</p>
            </li>
        </ul>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
        <h3 class="text-xl font-semibold text-gray-300 mb-4">Tindakan Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="/kredit/new" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md text-center text-sm transition duration-200 flex items-center justify-center">
                <i class="bx bx-plus mr-2"></i>
                <span>Ajukan Kredit</span>
            </a>
            <a href="/anggota/new" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-md text-center text-sm transition duration-200 flex items-center justify-center">
                <i class="bx bx-user-plus mr-2"></i>
                <span>Tambah Anggota</span>
            </a>
            <a href="/pembayaran-angsuran/new" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-md text-center text-sm transition duration-200 flex items-center justify-center">
                <i class="bx bx-money-bill mr-2"></i>
                <span>Bayar Angsuran</span>
            </a>
            <a href="/pencairan/new" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-md text-center text-sm transition duration-200 flex items-center justify-center">
                <i class="bx bx-wallet mr-2"></i>
                <span>Cairkan Dana</span>
            </a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
