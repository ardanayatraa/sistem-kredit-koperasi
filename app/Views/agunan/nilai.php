<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Penilaian Nilai Agunan
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Form penilaian dan taksiran nilai agunan
                    </p>
                </div>
                <a href="<?= base_url('agunan') ?>" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="bx bx-arrow-back"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Informasi Anggota dan Kredit -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kredit</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nama Anggota</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['nama_lengkap'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">NIK</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['nik'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jumlah Kredit</label>
                    <p class="mt-1 text-sm text-gray-900">Rp <?= number_format($kredit['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Jenis Agunan</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['jenis_agunan'] ?? 'N/A') ?></p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500">Tujuan Kredit</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['tujuan_kredit'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>

        <form action="<?= base_url('agunan/simpan-nilai/' . $kredit['id_kredit']) ?>" 
              method="post" class="p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="bx bx-check-circle text-green-600 text-lg mt-0.5 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-green-800">Berhasil</h4>
                        <p class="text-sm text-green-700 mt-1">
                            <?= session()->getFlashdata('success') ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="bx bx-error text-red-600 text-lg mt-0.5 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-red-800">Error</h4>
                        <p class="text-sm text-red-700 mt-1">
                            <?= session()->getFlashdata('error') ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Form Penilaian -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    Form Penilaian Agunan
                </h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="nilai_taksiran" class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai Taksiran Agunan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                            <input type="number"
                                   name="nilai_taksiran"
                                   id="nilai_taksiran"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                   value="<?= old('nilai_taksiran', $kredit['nilai_taksiran_agunan'] ?? '') ?>"
                                   placeholder="0"
                                   min="0"
                                   step="1000"
                                   required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Nilai taksiran berdasarkan penilaian appraiser</p>
                    </div>
                </div>
                
                <div>
                    <label for="catatan_penilaian" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Appraiser
                    </label>
                    <textarea name="catatan_penilaian"
                              id="catatan_penilaian"
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Masukkan catatan penilaian, kondisi agunan, dan pertimbangan lainnya..."><?= old('catatan_penilaian', $kredit['catatan_appraiser'] ?? '') ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">Jelaskan dasar penilaian, kondisi agunan, dan hal penting lainnya</p>
                </div>

                <!-- Info Penilaian Sebelumnya -->
                <?php if (!empty($kredit['nilai_taksiran_agunan']) || !empty($kredit['catatan_appraiser'])): ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="bx bx-info-circle text-yellow-600 text-lg mt-0.5 mr-3"></i>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-yellow-800">Penilaian Sebelumnya</h4>
                            <div class="text-sm text-yellow-700 mt-2 space-y-1">
                                <?php if (!empty($kredit['nilai_taksiran_agunan'])): ?>
                                <p><strong>Nilai Taksiran:</strong> Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></p>
                                <?php endif; ?>
                                <?php if (!empty($kredit['catatan_appraiser'])): ?>
                                <p><strong>Catatan:</strong> <?= nl2br(esc($kredit['catatan_appraiser'])) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($kredit['updated_at'])): ?>
                                <p><strong>Terakhir diubah:</strong> <?= date('d/m/Y H:i', strtotime($kredit['updated_at'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-4 pt-6 border-t border-gray-200">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="bx bx-info-circle"></i>
                    <span>Pastikan semua data sudah benar sebelum menyimpan</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <button type="button" 
                            onclick="window.history.back()"
                            class="px-6 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="bx bx-save mr-2"></i>
                        Simpan Penilaian
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript untuk format currency -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nilaiTaksiranInput = document.getElementById('nilai_taksiran');
    
    // Format number input
    function formatNumber(input) {
        input.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            this.value = value;
        });
    }
    
    formatNumber(nilaiTaksiranInput);
});
</script>
<?= $this->endSection() ?>