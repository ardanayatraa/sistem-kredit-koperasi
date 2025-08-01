<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<!-- Stats Cards -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700 hover:border-blue-500 transition duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-300">Total Anggota</h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2v11a2 2 0 002 2zM9 20h2a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v11a2 2 0 002 2zM5 20h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z" />
            </svg>
        </div>
        <p class="text-5xl font-bold text-white">1,234</p>
        <p class="text-sm text-gray-400 mt-2">Anggota aktif</p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700 hover:border-green-500 transition duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-300">Kredit Aktif</h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <p class="text-5xl font-bold text-white">567</p>
        <p class="text-sm text-gray-400 mt-2">Pengajuan disetujui</p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700 hover:border-purple-500 transition duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-300">Total Angsuran</h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        <p class="text-5xl font-bold text-white">8,901</p>
        <p class="text-sm text-gray-400 mt-2">Angsuran terbayar</p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700 hover:border-red-500 transition duration-300">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-300">Pencairan Hari Ini</h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <p class="text-5xl font-bold text-white">Rp 12.5 Jt</p>
        <p class="text-sm text-gray-400 mt-2">Total pencairan</p>
    </div>
</section>

<!-- Charts Section -->
<section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
        <h3 class="text-xl font-semibold text-gray-300 mb-4">Statistik Kredit Bulanan</h3>
        <img src="/placeholder.svg?height=300&width=600" alt="Monthly Credit Statistics Chart" class="w-full h-auto rounded-md">
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
        <h3 class="text-xl font-semibold text-gray-300 mb-4">Status Angsuran</h3>
        <img src="/placeholder.svg?height=300&width=600" alt="Installment Status Pie Chart" class="w-full h-auto rounded-md">
    </div>
</section>

<!-- Recent Activities / Quick Actions -->
<section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
        <h3 class="text-xl font-semibold text-gray-300 mb-4">Aktivitas Terbaru</h3>
        <ul class="space-y-4">
            <li class="flex items-center bg-gray-700 p-3 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-300 text-sm">Pengajuan kredit baru dari <span class="font-semibold text-white">Budi Santoso</span> disetujui.</p>
            </li>
            <li class="flex items-center bg-gray-700 p-3 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-300 text-sm">Pembayaran angsuran diterima dari <span class="font-semibold text-white">Siti Aminah</span>.</p>
            </li>
            <li class="flex items-center bg-gray-700 p-3 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-gray-300 text-sm">Dokumen KTP <span class="font-semibold text-white">Agus Wijaya</span> perlu diverifikasi.</p>
            </li>
        </ul>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
        <h3 class="text-xl font-semibold text-gray-300 mb-4">Tindakan Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="/kredit/new" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md text-center text-sm transition duration-200 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Ajukan Kredit</span>
            </a>
            <a href="/anggota/new" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-md text-center text-sm transition duration-200 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Tambah Anggota</span>
            </a>
            <a href="/pembayaran-angsuran/new" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-md text-center text-sm transition duration-200 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h10m-9 4h8m-10 4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-2.586a1 1 0 00-.707.293l-2.414 2.414A1 1 0 0112.586 22H7a2 2 0 01-2-2z" />
                </svg>
                <span>Bayar Angsuran</span>
            </a>
            <a href="/pencairan/new" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-md text-center text-sm transition duration-200 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Cairkan Dana</span>
            </a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
