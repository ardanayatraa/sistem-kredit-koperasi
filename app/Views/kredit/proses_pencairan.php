<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-blue-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h1 class="text-2xl font-bold text-gray-900"><?= $title ?? 'Proses Pencairan' ?></h1>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                Step 4: Proses Pencairan
            </span>
        </div>
    </div>

    <!-- Status Workflow -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-800">Status Workflow</h3>
                <p class="text-sm text-blue-700 mt-1">
                    <strong>Kredit telah disetujui oleh Ketua Koperasi</strong><br>
                    Silakan proses persiapan pencairan dana kepada anggota.
                </p>
            </div>
        </div>
    </div>

    <!-- Data Summary Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Data Anggota -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Nama Anggota</h3>
                        <p class="text-xl font-bold text-gray-900"><?= esc($anggota['nama_lengkap'] ?? '-') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jumlah Kredit -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Jumlah Kredit</h3>
                        <p class="text-xl font-bold text-green-600">Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Kredit -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Pengajuan Kredit</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Pengajuan</dt>
                        <dd class="text-sm text-gray-900 col-span-2"><?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'])) ?></dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Jangka Waktu</dt>
                        <dd class="text-sm text-gray-900 col-span-2"><?= $kredit['jangka_waktu'] ?> bulan</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tujuan Kredit</dt>
                        <dd class="text-sm text-gray-900 col-span-2"><?= esc($kredit['tujuan_kredit']) ?></dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Status Saat Ini</dt>
                        <dd class="col-span-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <?= esc($kredit['status_kredit']) ?>
                            </span>
                        </dd>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Jenis Agunan</dt>
                        <dd class="text-sm text-gray-900 col-span-2"><?= esc($kredit['jenis_agunan']) ?></dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Nilai Taksiran</dt>
                        <dd class="text-sm text-gray-900 col-span-2">Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <dt class="text-sm font-medium text-gray-500">Tanggal Persetujuan Ketua</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            <?= $kredit['tanggal_persetujuan_ketua'] ? date('d/m/Y H:i', strtotime($kredit['tanggal_persetujuan_ketua'])) : '-' ?>
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Proses -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Proses Approval</h3>
        </div>
        <div class="p-6">
            <div class="flow-root">
                <ul class="-mb-8">
                    <!-- Bendahara -->
                    <li>
                        <div class="relative pb-8">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Bendahara</p>
                                        <p class="text-sm text-gray-500"><?= esc($kredit['catatan_bendahara'] ?? 'Tidak ada catatan') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Appraiser -->
                    <li>
                        <div class="relative pb-8">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Appraiser</p>
                                        <p class="text-sm text-gray-500"><?= esc($kredit['catatan_appraiser'] ?? 'Tidak ada catatan') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Ketua -->
                    <li>
                        <div class="relative">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Ketua</p>
                                        <p class="text-sm text-gray-500"><?= esc($kredit['catatan_ketua'] ?? 'Tidak ada catatan') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form Proses Pencairan -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Proses Pencairan</h3>
            </div>
        </div>
        <div class="p-6">
            <form action="/kredit/proses-pencairan/<?= $kredit['id_kredit'] ?>" method="post" enctype="multipart/form-data" class="space-y-6">
                <?= csrf_field() ?>
                
                <div>
                    <label for="catatan_pencairan_bendahara" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Proses Pencairan <span class="text-red-500">*</span>
                    </label>
                    <textarea class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                              name="catatan_pencairan_bendahara" 
                              id="catatan_pencairan_bendahara" 
                              rows="4" 
                              required 
                              placeholder="Masukkan catatan untuk proses pencairan (verifikasi rekening, konfirmasi data, dll)"><?= old('catatan_pencairan_bendahara') ?></textarea>
                    <?php if (isset($errors['catatan_pencairan_bendahara'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['catatan_pencairan_bendahara'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="metode_pencairan" class="block text-sm font-medium text-gray-700 mb-2">
                        Metode Pencairan <span class="text-red-500">*</span>
                    </label>
                    <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                            name="metode_pencairan" 
                            id="metode_pencairan" 
                            required>
                        <option value="">-- Pilih Metode Pencairan --</option>
                        <option value="Transfer Bank" <?= old('metode_pencairan') === 'Transfer Bank' ? 'selected' : '' ?>>Transfer Bank</option>
                        <option value="Tunai" <?= old('metode_pencairan') === 'Tunai' ? 'selected' : '' ?>>Tunai</option>
                        <option value="Cek" <?= old('metode_pencairan') === 'Cek' ? 'selected' : '' ?>>Cek</option>
                    </select>
                    <?php if (isset($errors['metode_pencairan'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['metode_pencairan'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="bukti_pencairan" class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Pencairan <span class="text-gray-500">(Opsional)</span>
                    </label>
                    <input type="file" 
                           name="bukti_pencairan" 
                           id="bukti_pencairan"
                           accept=".pdf,.jpg,.jpeg,.png"
                           class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    <p class="mt-1 text-xs text-gray-500">Format: PDF, JPG, JPEG, PNG (Max: 5MB)</p>
                    <?php if (isset($errors['bukti_pencairan'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['bukti_pencairan'] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <label for="keputusan_pencairan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keputusan Pencairan <span class="text-red-500">*</span>
                    </label>
                    <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                            name="keputusan_pencairan" 
                            id="keputusan_pencairan" 
                            required>
                        <option value="">-- Pilih Keputusan --</option>
                        <option value="Siap Dicairkan" <?= old('keputusan_pencairan') === 'Siap Dicairkan' ? 'selected' : '' ?>>
                            Siap Dicairkan - Lanjutkan ke proses pencairan dana
                        </option>
                        <option value="Perlu Review" <?= old('keputusan_pencairan') === 'Perlu Review' ? 'selected' : '' ?>>
                            Perlu Review - Memerlukan klarifikasi lebih lanjut
                        </option>
                    </select>
                    <?php if (isset($errors['keputusan_pencairan'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['keputusan_pencairan'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            id="submit-btn"
                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Proses Pencairan
                    </button>
                    <a href="/kredit/pengajuan-untuk-role" 
                       class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:ring-4 focus:ring-gray-500 focus:ring-opacity-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Update button text and style based on decision
document.getElementById('keputusan_pencairan').addEventListener('change', function() {
    const decision = this.value;
    const submitBtn = document.getElementById('submit-btn');
    
    if (decision === 'Siap Dicairkan') {
        submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Siapkan untuk Pencairan';
        submitBtn.className = 'flex-1 inline-flex justify-center items-center px-6 py-3 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-500 focus:ring-opacity-50 transition-colors';
    } else if (decision === 'Perlu Review') {
        submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/></svg>Tandai Perlu Review';
        submitBtn.className = 'flex-1 inline-flex justify-center items-center px-6 py-3 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-500 focus:ring-opacity-50 transition-colors';
    } else {
        submitBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>Proses Pencairan';
        submitBtn.className = 'flex-1 inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50 transition-colors';
    }
});
</script>

<?= $this->endSection() ?>