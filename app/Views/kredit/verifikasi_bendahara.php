<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    <i class="bx bx-user-check text-yellow-600 mr-2"></i>
                    Verifikasi Bendahara - Pengajuan Kredit
                </h1>
                <p class="text-gray-600">Verifikasi dokumen dan kelayakan anggota untuk pengajuan kredit</p>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                Step 1: Verifikasi Dokumen
            </span>
        </div>
    </div>

    <!-- Workflow Status -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-blue-800 mb-2">Alur Pengajuan Kredit Koperasi Mitra Sejahtra:</h3>
        <div class="flex items-center space-x-2 text-sm">
            <div class="flex items-center text-green-600">
                <i class="bx bx-check-circle mr-1"></i>
                <span class="font-medium">Anggota</span>
            </div>
            <span class="text-blue-600">→</span>
            <div class="flex items-center text-yellow-600 font-bold">
                <i class="bx bx-user-check mr-1"></i>
                <span>Bendahara (Sedang Proses)</span>
            </div>
            <span class="text-gray-400">→</span>
            <div class="flex items-center text-gray-400">
                <i class="bx bx-search mr-1"></i>
                <span>Appraiser</span>
            </div>
            <span class="text-gray-400">→</span>
            <div class="flex items-center text-gray-400">
                <i class="bx bx-crown mr-1"></i>
                <span>Ketua</span>
            </div>
            <span class="text-gray-400">→</span>
            <div class="flex items-center text-gray-400">
                <i class="bx bx-check-circle mr-1"></i>
                <span>Selesai</span>
            </div>
        </div>
    </div>

    <!-- Data Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Data Anggota -->
        <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="bx bx-user text-blue-600 mr-2"></i>
                    Data Anggota
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Nama:</span>
                        <span class="text-sm text-gray-900"><?= esc($anggota['nama_lengkap'] ?? 'N/A') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">No. Anggota:</span>
                        <span class="text-sm text-gray-900"><?= esc($anggota['no_anggota'] ?? 'N/A') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">NIK:</span>
                        <span class="text-sm text-gray-900"><?= esc($anggota['nik'] ?? 'N/A') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Tempat, Tgl Lahir:</span>
                        <span class="text-sm text-gray-900">
                            <?= esc($anggota['tempat_lahir'] ?? 'N/A') ?>,
                            <?= $anggota['tanggal_lahir'] ? date('d/m/Y', strtotime($anggota['tanggal_lahir'])) : 'N/A' ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Alamat:</span>
                        <span class="text-sm text-gray-900 text-right"><?= esc($anggota['alamat'] ?? 'N/A') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Pekerjaan:</span>
                        <span class="text-sm text-gray-900"><?= esc($anggota['pekerjaan'] ?? 'N/A') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Status Keanggotaan:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <?= esc($anggota['status_keanggotaan'] ?? 'N/A') ?>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Tanggal Pendaftaran:</span>
                        <span class="text-sm text-gray-900">
                            <?= $anggota['tanggal_pendaftaran'] ? date('d/m/Y', strtotime($anggota['tanggal_pendaftaran'])) : 'N/A' ?>
                        </span>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Status Verifikasi:</h4>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-xs text-gray-600">
                            Sebagai Bendahara, Anda perlu memverifikasi kelayakan anggota dan kesesuaian dokumen agunan
                            untuk pengajuan kredit ini sebelum diteruskan ke Appraiser untuk penilaian agunan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Pengajuan Kredit -->
        <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="bx bx-credit-card text-yellow-600 mr-2"></i>
                    Detail Pengajuan Kredit
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Tanggal Pengajuan:</span>
                        <span class="text-sm text-gray-900"><?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'])) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Jumlah Kredit:</span>
                        <span class="text-sm font-bold text-green-600">Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Jangka Waktu:</span>
                        <span class="text-sm text-gray-900"><?= $kredit['jangka_waktu'] ?> bulan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Tujuan Kredit:</span>
                        <span class="text-sm text-gray-900 text-right"><?= esc($kredit['tujuan_kredit']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Jenis Agunan:</span>
                        <span class="text-sm text-gray-900"><?= esc($kredit['jenis_agunan']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <?= esc($kredit['status_kredit']) ?>
                        </span>
                    </div>
                </div>

                <!-- Dokumen Agunan -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Dokumen Agunan:</h4>
                    <?php if (!empty($kredit['dokumen_agunan'])): ?>
                        <a href="/<?= $kredit['dokumen_agunan'] ?>" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm rounded hover:bg-purple-700 transition-colors">
                            <i class="bx bx-file h-4 w-4"></i>
                            Lihat Dokumen Agunan
                        </a>
                    <?php else: ?>
                        <button class="inline-flex items-center gap-2 px-4 py-2 bg-gray-400 text-white text-sm rounded cursor-not-allowed" disabled>
                            <i class="bx bx-x h-4 w-4"></i>
                            Dokumen Agunan Tidak Ada
                        </button>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Verifikasi -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="bx bx-clipboard text-yellow-600 mr-2"></i>
                Form Verifikasi Bendahara
            </h3>
        </div>
        <div class="p-6">
            <form method="POST" class="space-y-6">
                <?= csrf_field() ?>
                
                <div>
                    <label for="catatan_bendahara" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Verifikasi <span class="text-red-500">*</span>
                    </label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 resize-none"
                              id="catatan_bendahara" name="catatan_bendahara" rows="4" required
                              placeholder="Berikan catatan hasil verifikasi dokumen anggota dan kelayakan pengajuan..."></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                        Jelaskan hasil verifikasi kelayakan anggota, kesesuaian dokumen agunan, dan rekomendasi Anda
                    </p>
                </div>

                <div>
                    <label for="keputusan_bendahara" class="block text-sm font-medium text-gray-700 mb-2">
                        Keputusan Verifikasi <span class="text-red-500">*</span>
                    </label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                            id="keputusan_bendahara" name="keputusan_bendahara" required>
                        <option value="">-- Pilih Keputusan --</option>
                        <option value="Diterima">Diterima - Teruskan ke Appraiser</option>
                        <option value="Ditolak">Ditolak - Tidak Memenuhi Syarat</option>
                    </select>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 transition-colors">
                        <i class="bx bx-check h-4 w-4"></i>
                        Simpan Verifikasi
                    </button>
                    <a href="/kredit/pengajuan-untuk-role"
                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded hover:bg-gray-700 transition-colors">
                        <i class="bx bx-arrow-back h-4 w-4"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>