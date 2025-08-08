<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        <?= isset($kredit) ? 'Edit Data Agunan' : 'Tambah Data Agunan' ?>
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        <?= isset($kredit) ? 'Perbarui informasi agunan' : 'Tambah informasi agunan baru' ?>
                    </p>
                </div>
                <a href="<?= base_url('agunan') ?>" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="bx bx-arrow-back"></i>
                    Kembali
                </a>
            </div>
        </div>

        <form action="<?= isset($kredit) ? base_url('agunan/update/' . $kredit['id_kredit']) : base_url('agunan/create') ?>" 
              method="post" class="p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Info Notice -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="bx bx-info-circle text-blue-600 text-lg mt-0.5 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800">Informasi</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Fitur form agunan saat ini dalam tahap pengembangan. 
                            Data agunan dikelola melalui proses pengajuan kredit dari anggota.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Data Kredit Section (Read-only if editing) -->
            <?php if (isset($kredit)): ?>
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Data Kredit</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Anggota</label>
                        <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['nama_lengkap'] ?? '') ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jumlah Kredit</label>
                        <p class="mt-1 text-sm text-gray-900">Rp <?= number_format($kredit['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Data Agunan Section -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Data Agunan</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="jenis_agunan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Agunan
                        </label>
                        <input type="text" 
                               name="jenis_agunan" 
                               id="jenis_agunan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') ?>" 
                               placeholder="Contoh: Sertifikat Tanah, BPKB Motor, dll"
                               readonly>
                        <p class="text-xs text-gray-500 mt-1">Data ini dikelola melalui pengajuan kredit</p>
                    </div>
                    
                    <div>
                        <label for="nilai_taksiran_agunan" class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai Taksiran (Rp)
                        </label>
                        <input type="number" 
                               name="nilai_taksiran_agunan" 
                               id="nilai_taksiran_agunan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('nilai_taksiran_agunan', $kredit['nilai_taksiran_agunan'] ?? '') ?>" 
                               placeholder="Masukkan nilai taksiran"
                               readonly>
                        <p class="text-xs text-gray-500 mt-1">Diisi oleh appraiser saat verifikasi</p>
                    </div>
                </div>
                
                <div>
                    <label for="deskripsi_agunan" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Agunan
                    </label>
                    <textarea name="deskripsi_agunan" 
                              id="deskripsi_agunan" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Deskripsi detail agunan"
                              readonly><?= old('deskripsi_agunan', $kredit['deskripsi_agunan'] ?? '') ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">Deskripsi dikelola melalui pengajuan kredit</p>
                </div>
                
                <?php if (!empty($kredit['catatan_appraiser'])): ?>
                <div>
                    <label for="catatan_appraiser" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Appraiser
                    </label>
                    <textarea name="catatan_appraiser" 
                              id="catatan_appraiser" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50"
                              readonly><?= esc($kredit['catatan_appraiser']) ?></textarea>
                </div>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <!-- New Agunan Form (Development Notice) -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Form Agunan Baru</h3>
                
                <div class="text-center py-8">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="bx bx-cog text-2xl text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Fitur Dalam Pengembangan</h4>
                    <p class="text-gray-500 mb-4">
                        Form untuk menambah agunan baru sedang dalam tahap pengembangan.
                    </p>
                    <p class="text-sm text-gray-400">
                        Saat ini, data agunan dikelola melalui proses pengajuan kredit dari anggota.
                    </p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button type="button" 
                        onclick="window.history.back()"
                        class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                
                <?php if (isset($kredit)): ?>
                <button type="button" 
                        class="px-6 py-2 bg-gray-400 text-white text-sm font-medium rounded-lg cursor-not-allowed"
                        disabled>
                    <i class="bx bx-save mr-2"></i>
                    Fitur Dalam Pengembangan
                </button>
                <?php else: ?>
                <button type="button" 
                        class="px-6 py-2 bg-gray-400 text-white text-sm font-medium rounded-lg cursor-not-allowed"
                        disabled>
                    <i class="bx bx-plus mr-2"></i>
                    Fitur Dalam Pengembangan
                </button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>