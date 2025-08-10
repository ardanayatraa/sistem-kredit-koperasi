<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-purple-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                </svg>
                <h1 class="text-2xl font-bold text-gray-900">Persetujuan Final - Ketua Koperasi</h1>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                Step 3: Persetujuan Final
            </span>
        </div>
    </div>

    <!-- Workflow Guide -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-800 mb-2">Alur Pengajuan Kredit Koperasi Mitra Sejahtra:</h3>
        <div class="flex items-center space-x-2 text-sm text-blue-700">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-green-600 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-green-600">Anggota ✓</span>
            </div>
            <span>→</span>
            <div class="flex items-center">
                <svg class="w-4 h-4 text-green-600 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-green-600">Bendahara ✓</span>
            </div>
            <span>→</span>
            <div class="flex items-center">
                <svg class="w-4 h-4 text-green-600 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="text-green-600">Appraiser ✓</span>
            </div>
            <span>→</span>
            <div class="flex items-center">
                <svg class="w-4 h-4 text-purple-600 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                </svg>
                <span class="text-purple-600 font-semibold">Ketua (Sedang Proses)</span>
            </div>
            <span>→</span>
            <div class="flex items-center">
                <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-gray-400">Selesai</span>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

    <!-- Ringkasan Pengajuan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Data Anggota -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Data Anggota</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Nama</dt>
                        <dd class="text-sm text-gray-900"><?= esc($anggota['nama_lengkap']) ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">No. Anggota</dt>
                        <dd class="text-sm text-gray-900"><?= esc($anggota['no_anggota']) ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Pekerjaan</dt>
                        <dd class="text-sm text-gray-900"><?= esc($anggota['pekerjaan']) ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <?= esc($anggota['status_keanggotaan']) ?>
                            </span>
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Pengajuan -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Pengajuan</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                        <dd class="text-sm text-gray-900"><?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'])) ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Jumlah</dt>
                        <dd class="text-sm font-semibold text-green-600">Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Jangka Waktu</dt>
                        <dd class="text-sm text-gray-900"><?= $kredit['jangka_waktu'] ?> bulan</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Tujuan</dt>
                        <dd class="text-sm text-gray-900"><?= esc($kredit['tujuan_kredit']) ?></dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penilaian Agunan -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Penilaian Agunan</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Jenis</dt>
                        <dd class="text-sm text-gray-900"><?= esc($kredit['jenis_agunan']) ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Nilai Taksir</dt>
                        <dd class="text-sm font-semibold text-blue-600">Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">LTV Ratio</dt>
                        <dd>
                            <?php 
                            $ltvRatio = ($kredit['jumlah_pengajuan'] / $kredit['nilai_taksiran_agunan']) * 100;
                            $ltvClass = $ltvRatio <= 80 ? 'text-green-600' : ($ltvRatio <= 90 ? 'text-yellow-600' : 'text-red-600');
                            ?>
                            <span class="text-sm font-semibold <?= $ltvClass ?>"><?= number_format($ltvRatio, 2) ?>%</span>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?= esc($kredit['status_kredit']) ?>
                            </span>
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Proses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Hasil Verifikasi Bendahara -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Hasil Verifikasi Bendahara</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-green-800">
                        <strong>Status:</strong> Diverifikasi dan Diterima<br>
                        <strong>Tanggal:</strong> 
                        <?php if (!empty($kredit['tanggal_verifikasi_bendahara'])): ?>
                            <?= date('d/m/Y H:i', strtotime($kredit['tanggal_verifikasi_bendahara'])) ?>
                        <?php else: ?>
                            -
                        <?php endif ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Bendahara:</label>
                    <p class="text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <?= esc($kredit['catatan_bendahara'] ?? 'Tidak ada catatan') ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Hasil Penilaian Appraiser -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Hasil Penilaian Appraiser</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-blue-800">
                        <strong>Status:</strong> Dinilai dan Direkomendasikan<br>
                        <strong>Tanggal:</strong> 
                        <?php if (!empty($kredit['tanggal_penilaian_appraiser'])): ?>
                            <?= date('d/m/Y H:i', strtotime($kredit['tanggal_penilaian_appraiser'])) ?>
                        <?php else: ?>
                            -
                        <?php endif ?>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Appraiser:</label>
                    <p class="text-sm text-gray-900 bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <?= esc($kredit['catatan_appraiser'] ?? 'Tidak ada catatan') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dokumen Pendukung -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Dokumen Pendukung</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <?php if (!empty($anggota['dokumen_ktp'])): ?>
                        <a href="/uploads/anggota/<?= $anggota['dokumen_ktp'] ?>" target="_blank"
                           class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                            Lihat KTP
                        </a>
                    <?php else: ?>
                        <button disabled class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            KTP N/A
                        </button>
                    <?php endif ?>
                </div>
                <div>
                    <?php if (!empty($anggota['dokumen_slip_gaji'])): ?>
                        <a href="/uploads/anggota/<?= $anggota['dokumen_slip_gaji'] ?>" target="_blank"
                           class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                            </svg>
                            Lihat Slip Gaji
                        </a>
                    <?php else: ?>
                        <button disabled class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Slip Gaji N/A
                        </button>
                    <?php endif ?>
                </div>
                <div>
                    <?php if (!empty($kredit['dokumen_agunan'])): ?>
                        <a href="/<?= $kredit['dokumen_agunan'] ?>" target="_blank" 
                           class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Lihat Dokumen Agunan
                        </a>
                    <?php else: ?>
                        <button disabled class="inline-flex items-center justify-center w-full px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Dokumen Agunan N/A
                        </button>
                    <?php endif ?>
                </div>
                <div>
                    <button onclick="window.print()" 
                            class="inline-flex items-center justify-center w-full px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1z"/>
                        </svg>
                        Print Ringkasan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Persetujuan Final -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.5-7.5l2.5 2.5L21 7l-4-4z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Form Keputusan Final Ketua Koperasi</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-800">
                            <strong>Perhatian!</strong> Keputusan ini bersifat final dan tidak dapat diubah setelah disimpan.
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" class="space-y-6">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="keputusan_final" class="block text-sm font-medium text-gray-700 mb-2">
                            Keputusan Final <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" 
                                id="keputusan_final" 
                                name="keputusan_final" 
                                required>
                            <option value="">-- Pilih Keputusan Final --</option>
                            <option value="Disetujui" class="text-green-700">✓ DISETUJUI - Kredit Siap Dicairkan</option>
                            <option value="Ditolak" class="text-red-700">✗ DITOLAK - Tidak Memenuhi Kebijakan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ringkasan Analisis</label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="text-sm text-gray-700 space-y-1">
                                <p><strong>Jumlah Kredit:</strong> Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></p>
                                <p><strong>Nilai Agunan:</strong> Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></p>
                                <p class="flex items-center">
                                    <strong>LTV Ratio:</strong> 
                                    <span class="ml-2 <?= $ltvClass ?>"><?= number_format($ltvRatio, 2) ?>%</span>
                                    <?php if ($ltvRatio <= 80): ?>
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Rendah</span>
                                    <?php elseif ($ltvRatio <= 90): ?>
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Sedang</span>
                                    <?php else: ?>
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Tinggi</span>
                                    <?php endif ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="catatan_ketua" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan dan Alasan Keputusan <span class="text-red-500">*</span>
                    </label>
                    <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                              id="catatan_ketua"
                              name="catatan_ketua"
                              rows="5"
                              required
                              placeholder="Berikan alasan dan pertimbangan keputusan final..."></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                        Jelaskan pertimbangan dalam mengambil keputusan berdasarkan verifikasi bendahara, penilaian appraiser, dan kebijakan koperasi
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit"
                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:ring-4 focus:ring-purple-500 focus:ring-opacity-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.5-7.5l2.5 2.5L21 7l-4-4z"/>
                        </svg>
                        Simpan Keputusan Final
                    </button>
                    <a href="/kredit/pengajuan-untuk-role"
                       class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:ring-4 focus:ring-gray-500 focus:ring-opacity-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>