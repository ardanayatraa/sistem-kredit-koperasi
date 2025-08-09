<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h1 class="text-2xl font-bold text-gray-900">Penilaian Appraiser - Agunan Kredit</h1>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                Step 2: Penilaian Agunan
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
                <span class="text-green-600 font-semibold">Appraiser (Sedang Proses)</span>
            </div>
            <span>→</span>
            <div class="flex items-center">
                <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                </svg>
                <span class="text-gray-400">Ketua</span>
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

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div class="space-y-6">
            <!-- Data Anggota & Pengajuan -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Data Anggota & Pengajuan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="text-sm text-gray-900 col-span-2"><?= esc($anggota['nama_lengkap']) ?></dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">No. Anggota</dt>
                            <dd class="text-sm text-gray-900 col-span-2"><?= esc($anggota['no_anggota']) ?></dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Pekerjaan</dt>
                            <dd class="text-sm text-gray-900 col-span-2"><?= esc($anggota['pekerjaan']) ?></dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Pengajuan</dt>
                            <dd class="text-sm text-gray-900 col-span-2"><?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'])) ?></dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Jumlah Kredit</dt>
                            <dd class="text-sm font-semibold text-green-600 col-span-2">Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Jangka Waktu</dt>
                            <dd class="text-sm text-gray-900 col-span-2"><?= $kredit['jangka_waktu'] ?> bulan</dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Tujuan Kredit</dt>
                            <dd class="text-sm text-gray-900 col-span-2"><?= esc($kredit['tujuan_kredit']) ?></dd>
                        </div>
                    </div>
                </div>
            </div>

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
                            <strong>Status:</strong> Diverifikasi Bendahara<br>
                            <strong>Tanggal Verifikasi:</strong> 
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
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Data Agunan -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Data Agunan</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4 mb-6">
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Jenis Agunan</dt>
                            <dd class="text-sm text-gray-900 col-span-2"><?= esc($kredit['jenis_agunan']) ?></dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Nilai Taksiran Saat Ini</dt>
                            <dd class="text-sm col-span-2">
                                <?php if (!empty($kredit['nilai_taksiran_agunan'])): ?>
                                    <span class="font-semibold text-blue-600">Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-gray-500">Belum dinilai</span>
                                <?php endif ?>
                            </dd>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?= esc($kredit['status_kredit']) ?>
                                </span>
                            </dd>
                        </div>
                    </div>

                    <!-- Dokumen Agunan -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Agunan:</label>
                        <?php if (!empty($kredit['dokumen_agunan'])): ?>
                            <a href="/<?= $kredit['dokumen_agunan'] ?>" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors w-full justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Lihat Dokumen Agunan
                            </a>
                        <?php else: ?>
                            <button disabled class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded-lg w-full justify-center cursor-not-allowed">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Dokumen Agunan Tidak Ada
                            </button>
                        <?php endif ?>
                    </div>

                    <!-- LTV Ratio Calculator -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kalkulator LTV Ratio:</label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="text-sm text-gray-700">
                                <p class="font-medium mb-2">Loan to Value (LTV):</p>
                                <div class="space-y-1">
                                    <p>Jumlah Kredit: <span id="jumlah-kredit" class="font-medium">Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></span></p>
                                    <p>Nilai Agunan: <span id="nilai-agunan" class="font-medium">Rp 0</span></p>
                                    <p class="font-semibold">LTV Ratio: <span id="ltv-ratio" class="text-blue-600">0%</span></p>
                                    <p class="text-xs text-gray-500">Rekomendasi LTV maksimal 80%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Penilaian Appraiser -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Form Penilaian Agunan</h3>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" class="space-y-6">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="nilai_taksiran_agunan" class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai Taksiran Agunan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">Rp</span>
                            </div>
                            <input type="number" 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" 
                                   id="nilai_taksiran_agunan" 
                                   name="nilai_taksiran_agunan" 
                                   required 
                                   min="1" 
                                   placeholder="0" 
                                   onkeyup="calculateLTV()">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Masukkan hasil penilaian agunan berdasarkan survey lapangan
                        </p>
                    </div>
                    <div>
                        <label for="rekomendasi_appraiser" class="block text-sm font-medium text-gray-700 mb-2">
                            Rekomendasi Appraiser <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" 
                                id="rekomendasi_appraiser" 
                                name="rekomendasi_appraiser" 
                                required>
                            <option value="">-- Pilih Rekomendasi --</option>
                            <option value="Disetujui">Disetujui - Teruskan ke Ketua</option>
                            <option value="Ditolak">Ditolak - Agunan Tidak Memenuhi Syarat</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="catatan_appraiser" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Penilaian Agunan <span class="text-red-500">*</span>
                    </label>
                    <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" 
                              id="catatan_appraiser" 
                              name="catatan_appraiser" 
                              rows="5" 
                              required 
                              placeholder="Berikan detail penilaian agunan, kondisi fisik, lokasi, nilai pasar, dan rekomendasi..."></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                        Jelaskan secara detail hasil survey agunan, kondisi fisik, lokasi, nilai pasar saat ini, dan alasan rekomendasi
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Penilaian Agunan
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

<script>
function calculateLTV() {
    const jumlahKredit = <?= $kredit['jumlah_pengajuan'] ?>;
    const nilaiAgunan = parseFloat(document.getElementById('nilai_taksiran_agunan').value) || 0;
    
    if (nilaiAgunan > 0) {
        const ltvRatio = (jumlahKredit / nilaiAgunan * 100).toFixed(2);
        document.getElementById('nilai-agunan').textContent = 'Rp ' + nilaiAgunan.toLocaleString('id-ID');
        document.getElementById('ltv-ratio').textContent = ltvRatio + '%';
        
        // Color coding for LTV ratio
        const ltvElement = document.getElementById('ltv-ratio');
        if (ltvRatio <= 80) {
            ltvElement.className = 'text-green-600 font-semibold';
        } else if (ltvRatio <= 90) {
            ltvElement.className = 'text-yellow-600 font-semibold';
        } else {
            ltvElement.className = 'text-red-600 font-semibold';
        }
    } else {
        document.getElementById('nilai-agunan').textContent = 'Rp 0';
        document.getElementById('ltv-ratio').textContent = '0%';
        document.getElementById('ltv-ratio').className = 'text-blue-600 font-semibold';
    }
}
</script>

<?= $this->endSection() ?>