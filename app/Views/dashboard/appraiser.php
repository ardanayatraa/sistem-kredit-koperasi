<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-lg p-6 text-white">
        <h1 class="text-2xl font-bold mb-2">🔍 Dashboard Appraiser</h1>
        <p class="text-green-100">Nilai agunan kredit dengan teliti dan akurat</p>
    </div>

    <!-- 🔥 WORKFLOW VERIFICATION TABLE -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Penilaian Agunan Kredit</h3>
                </div>
                <div class="text-sm text-gray-500">Anggota → Bendahara → <span class="text-green-600 font-semibold">APPRAISER</span> → Ketua → Bendahara</div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Anggota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Kredit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Agunan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Alur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tugas Anda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($kreditSiapDinilai)): ?>
                        <?php foreach ($kreditSiapDinilai as $kredit): ?>
                        <tr class="hover:bg-green-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= esc($kredit['nama_lengkap']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?= esc($kredit['jenis_agunan'] ?? 'Belum ditentukan') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex items-center space-x-1">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <span class="text-xs text-green-600">Anggota</span>
                                        <svg class="w-3 h-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <span class="text-xs text-green-600">Bendahara</span>
                                        <svg class="w-3 h-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-xs text-green-600 font-semibold">APPRAISER</span>
                                        <svg class="w-3 h-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                                        <span class="text-xs text-gray-400">Ketua</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Penilaian Agunan
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= base_url('kredit/penilaian-appraiser/' . $kredit['id_kredit']) ?>"
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    NILAI AGUNAN
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-green-800">✅ Semua Penilaian Selesai!</h3>
                                <p>Tidak ada agunan yang menunggu penilaian.</p>
                                <p class="text-sm text-gray-400 mt-1">Gunakan menu sidebar untuk akses cepat ke tools penilaian.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Statistics (No Duplication) -->
    <?php if (!empty($kreditSiapDinilai)): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">📊 Ringkasan Penilaian Hari Ini</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-900">Penilaian Agunan</p>
                        <p class="text-2xl font-bold text-blue-700"><?= count($kreditSiapDinilai) ?></p>
                        <p class="text-xs text-blue-600">agunan menunggu penilaian</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Action -->
            <div class="mt-4 text-center">
                <a href="<?= base_url('kredit/pengajuan-untuk-role') ?>"
                   class="inline-flex items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Lihat Semua Agunan
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Info: No Tasks -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
        <svg class="mx-auto h-12 w-12 text-green-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-semibold text-green-800">✅ Semua Penilaian Selesai!</h3>
        <p class="text-green-700 mt-2">Tidak ada agunan yang menunggu penilaian.</p>
        <p class="text-sm text-green-600 mt-1">Gunakan menu sidebar untuk akses cepat ke tools penilaian.</p>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>