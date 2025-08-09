<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-white">
        <h1 class="text-2xl font-bold mb-2">💼 Dashboard Bendahara</h1>
        <p class="text-blue-100">Kelola verifikasi dokumen dan pencairan kredit</p>
    </div>

    <!-- 🔥 WORKFLOW VERIFICATION TABLE -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Alur Verifikasi Kredit</h3>
                </div>
                <div class="text-sm text-gray-500">Anggota → <span class="text-blue-600 font-semibold">BENDAHARA</span> → Appraiser → Ketua → Bendahara</div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Anggota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Kredit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pengajuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Alur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tugas Anda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Dokumen Verification Tasks -->
                    <?php if (!empty($pengajuanBaruBendahara)): ?>
                        <?php foreach ($pengajuanBaruBendahara as $pengajuan): ?>
                        <tr class="hover:bg-blue-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= esc($pengajuan['nama_lengkap']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp <?= number_format($pengajuan['jumlah_pengajuan'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y', strtotime($pengajuan['tanggal_pengajuan'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex items-center space-x-1">
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <span class="text-xs text-green-600">Anggota</span>
                                        <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                                        <span class="text-xs text-blue-600 font-semibold">BENDAHARA</span>
                                        <svg class="w-3 h-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                                        <span class="text-xs text-gray-400">Appraiser</span>
                                        <svg class="w-3 h-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                                        <span class="text-xs text-gray-400">Ketua</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Verifikasi Dokumen
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= base_url('kredit/verifikasi-bendahara/' . $pengajuan['id_kredit']) ?>"
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    VERIFIKASI
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <!-- Disbursement Tasks -->
                    <?php if (!empty($kreditSiapCair)): ?>
                        <?php foreach ($kreditSiapCair as $kredit): ?>
                        <tr class="hover:bg-green-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= esc($kredit['nama_lengkap']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'])) ?>
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
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <span class="text-xs text-green-600">Appraiser</span>
                                        <svg class="w-3 h-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        <span class="text-xs text-green-600">Ketua</span>
                                        <svg class="w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                                        <span class="text-xs text-blue-600 font-semibold">BENDAHARA</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Pencairan Kredit
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= base_url('kredit/proses-pencairan/' . $kredit['id_kredit']) ?>"
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                    </svg>
                                    CAIRKAN
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <!-- Empty state handled below table -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Statistics (No Duplication) -->
    <?php if (!empty($pengajuanBaruBendahara) || !empty($kreditSiapCair)): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">📊 Ringkasan Tugas Hari Ini</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Verifikasi Dokumen Summary -->
                <?php if (!empty($pengajuanBaruBendahara)): ?>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-orange-900">Verifikasi Dokumen</p>
                            <p class="text-2xl font-bold text-orange-700"><?= count($pengajuanBaruBendahara) ?></p>
                            <p class="text-xs text-orange-600">pengajuan menunggu</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Pencairan Summary -->
                <?php if (!empty($kreditSiapCair)): ?>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-900">Pencairan Kredit</p>
                            <p class="text-2xl font-bold text-green-700"><?= count($kreditSiapCair) ?></p>
                            <p class="text-xs text-green-600">kredit siap dicairkan</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Quick Action -->
            <div class="mt-4 text-center">
                <a href="<?= base_url('kredit/pengajuan-untuk-role') ?>"
                   class="inline-flex items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Lihat Semua Pengajuan
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
        <h3 class="text-lg font-semibold text-green-800">✅ Semua Tugas Selesai!</h3>
        <p class="text-green-700 mt-2">Tidak ada verifikasi dokumen atau pencairan yang menunggu.</p>
        <p class="text-sm text-green-600 mt-1">Gunakan menu sidebar untuk mengelola data lainnya.</p>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>