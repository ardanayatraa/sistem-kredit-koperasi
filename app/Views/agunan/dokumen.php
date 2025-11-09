<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="<?= base_url('verifikasi-agunan') ?>"
                       class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="bx bx-arrow-back text-lg"></i>
                        Kembali
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Dokumen Agunan</h1>
                </div>
                <p class="text-gray-600">Lihat dokumen agunan yang diajukan anggota</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">ID Kredit</p>
                <p class="text-xl font-semibold text-gray-900">#<?= $kredit['id_kredit'] ?></p>
            </div>
        </div>
    </div>

    <!-- Detail Anggota -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bx bx-user text-xl text-blue-600"></i>
            Informasi Anggota
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                <p class="text-gray-900 font-medium"><?= esc($kredit['nama_lengkap']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">NIK</label>
                <p class="text-gray-900 font-medium"><?= esc($kredit['nik']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Jumlah Pengajuan</label>
                <p class="text-gray-900 font-medium">Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></p>
            </div>
        </div>
    </div>

    <!-- Informasi Agunan -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bx bx-home text-xl text-green-600"></i>
            Detail Agunan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Agunan</label>
                <p class="text-gray-900 font-medium"><?= esc($kredit['jenis_agunan'] ?? 'Tidak Diisi') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Agunan</label>
                <p class="text-gray-900"><?= esc($kredit['alamat_agunan'] ?? 'Tidak Diisi') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Luas Agunan</label>
                <p class="text-gray-900"><?= esc($kredit['luas_agunan'] ?? 'Tidak Diisi') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Status Kepemilikan</label>
                <p class="text-gray-900"><?= esc($kredit['status_kepemilikan_agunan'] ?? 'Tidak Diisi') ?></p>
            </div>
        </div>
        
        <?php if (!empty($kredit['keterangan_agunan'])): ?>
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-500 mb-1">Keterangan Agunan</label>
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-gray-900"><?= nl2br(esc($kredit['keterangan_agunan'])) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Dokumen -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bx bx-file text-xl text-purple-600"></i>
            Dokumen Agunan
        </h3>
        
        <div class="space-y-4">
            <!-- Dokumen Agunan -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="bx bx-home-alt text-2xl text-purple-600"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Dokumen Agunan</h4>
                            <p class="text-sm text-gray-500">Sertifikat/dokumen kepemilikan agunan</p>
                        </div>
                    </div>
                    <?php if (!empty($kredit['dokumen_agunan'])): ?>
                        <a href="<?= base_url('writable/uploads/dokumen_kredit/' . basename($kredit['dokumen_agunan'])) ?>"
                           target="_blank"
                           class="inline-flex items-center gap-1 px-3 py-1 bg-purple-600 text-white text-sm font-medium rounded hover:bg-purple-700 transition-colors">
                            <i class="bx bx-show text-sm"></i>
                            Lihat
                        </a>
                    <?php else: ?>
                        <span class="text-sm text-gray-500">Tidak ada file</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (empty($kredit['dokumen_agunan'])): ?>
        <div class="text-center py-8">
            <i class="bx bx-file-blank text-6xl text-gray-400 mb-4"></i>
            <p class="text-gray-500">Belum ada dokumen yang diunggah</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Status dan Catatan -->
    <?php if (!empty($kredit['catatan_appraiser'])): ?>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <i class="bx bx-note text-xl text-orange-600"></i>
            Catatan Verifikasi
        </h3>
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <p class="text-gray-900"><?= nl2br(esc($kredit['catatan_appraiser'])) ?></p>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>