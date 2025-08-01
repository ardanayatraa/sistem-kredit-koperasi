<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-900">
                <?= isset($kredit) ? 'Edit Kredit' : 'Tambah Kredit Baru' ?>
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                <?= isset($kredit) ? 'Perbarui informasi pengajuan kredit' : 'Lengkapi form untuk menambah pengajuan kredit baru' ?>
            </p>
        </div>

        <form action="<?= isset($kredit) ? '/kredit/update/' . esc($kredit['id_kredit']) : '/kredit/create' ?>" method="post" class="p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Data Pengajuan Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Data Pengajuan
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="id_anggota" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Anggota <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="id_anggota" 
                               id="id_anggota" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('id_anggota', $kredit['id_anggota'] ?? '') ?>" 
                               placeholder="Masukkan ID anggota"
                               required>
                        <?php if (session('errors.id_anggota')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.id_anggota') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="tanggal_pengajuan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pengajuan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_pengajuan" 
                               id="tanggal_pengajuan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('tanggal_pengajuan', $kredit['tanggal_pengajuan'] ?? date('Y-m-d')) ?>" 
                               required>
                        <?php if (session('errors.tanggal_pengajuan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.tanggal_pengajuan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="jumlah_pengajuan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Pengajuan (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="jumlah_pengajuan" 
                               id="jumlah_pengajuan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('jumlah_pengajuan', $kredit['jumlah_pengajuan'] ?? '') ?>" 
                               placeholder="Contoh: 5000000"
                               required>
                        <?php if (session('errors.jumlah_pengajuan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.jumlah_pengajuan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="jangka_waktu" class="block text-sm font-medium text-gray-700 mb-2">
                            Jangka Waktu (bulan) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="jangka_waktu" 
                               id="jangka_waktu" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('jangka_waktu', $kredit['jangka_waktu'] ?? '') ?>" 
                               placeholder="Contoh: 12"
                               required>
                        <?php if (session('errors.jangka_waktu')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.jangka_waktu') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="tujuan_kredit" class="block text-sm font-medium text-gray-700 mb-2">
                        Tujuan Kredit <span class="text-red-500">*</span>
                    </label>
                    <textarea name="tujuan_kredit" 
                              id="tujuan_kredit" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" 
                              placeholder="Jelaskan tujuan pengajuan kredit"
                              required><?= old('tujuan_kredit', $kredit['tujuan_kredit'] ?? '') ?></textarea>
                    <?php if (session('errors.tujuan_kredit')): ?>
                        <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <?= session('errors.tujuan_kredit') ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Data Agunan Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Data Agunan
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="jenis_agunan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Agunan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_agunan" 
                                id="jenis_agunan" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                required>
                            <option value="">Pilih Jenis Agunan</option>
                            <option value="Sertifikat Tanah" <?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') == 'Sertifikat Tanah' ? 'selected' : '' ?>>Sertifikat Tanah</option>
                            <option value="BPKB Kendaraan" <?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') == 'BPKB Kendaraan' ? 'selected' : '' ?>>BPKB Kendaraan</option>
                            <option value="Deposito" <?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') == 'Deposito' ? 'selected' : '' ?>>Deposito</option>
                            <option value="SK Pegawai" <?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') == 'SK Pegawai' ? 'selected' : '' ?>>SK Pegawai</option>
                            <option value="Lainnya" <?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                        <?php if (session('errors.jenis_agunan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.jenis_agunan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="nilai_taksiran_agunan" class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai Taksiran Agunan (Rp)
                        </label>
                        <input type="number" 
                               name="nilai_taksiran_agunan" 
                               id="nilai_taksiran_agunan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('nilai_taksiran_agunan', $kredit['nilai_taksiran_agunan'] ?? '') ?>" 
                               placeholder="Contoh: 50000000">
                        <?php if (session('errors.nilai_taksiran_agunan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.nilai_taksiran_agunan') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Catatan dan Status Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Catatan dan Status
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="catatan_bendahara" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Bendahara
                        </label>
                        <textarea name="catatan_bendahara" 
                                  id="catatan_bendahara" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" 
                                  placeholder="Catatan dari bendahara"><?= old('catatan_bendahara', $kredit['catatan_bendahara'] ?? '') ?></textarea>
                        <?php if (session('errors.catatan_bendahara')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.catatan_bendahara') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="catatan_appraiser" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Appraiser
                        </label>
                        <textarea name="catatan_appraiser" 
                                  id="catatan_appraiser" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" 
                                  placeholder="Catatan dari appraiser"><?= old('catatan_appraiser', $kredit['catatan_appraiser'] ?? '') ?></textarea>
                        <?php if (session('errors.catatan_appraiser')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.catatan_appraiser') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="catatan_ketua" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Ketua
                        </label>
                        <textarea name="catatan_ketua" 
                                  id="catatan_ketua" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" 
                                  placeholder="Catatan dari ketua"><?= old('catatan_ketua', $kredit['catatan_ketua'] ?? '') ?></textarea>
                        <?php if (session('errors.catatan_ketua')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.catatan_ketua') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="status_kredit" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Kredit <span class="text-red-500">*</span>
                        </label>
                        <select name="status_kredit" 
                                id="status_kredit" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                required>
                            <option value="">Pilih Status</option>
                            <option value="Pending" <?= old('status_kredit', $kredit['status_kredit'] ?? '') == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Dalam Proses" <?= old('status_kredit', $kredit['status_kredit'] ?? '') == 'Dalam Proses' ? 'selected' : '' ?>>Dalam Proses</option>
                            <option value="Disetujui" <?= old('status_kredit', $kredit['status_kredit'] ?? '') == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                            <option value="Ditolak" <?= old('status_kredit', $kredit['status_kredit'] ?? '') == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                        <?php if (session('errors.status_kredit')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.status_kredit') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/kredit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <?= isset($kredit) ? 'Perbarui Data' : 'Simpan Data' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Format currency input
document.addEventListener('DOMContentLoaded', function() {
    const formatCurrency = (input) => {
        // Remove non-numeric characters
        let value = input.value.replace(/[^\d]/g, '');
        
        // Format with thousand separator
        if (value.length > 0) {
            input.value = parseInt(value).toLocaleString('id-ID');
        }
    };
    
    // Format on page load
    const currencyInputs = document.querySelectorAll('#jumlah_pengajuan, #nilai_taksiran_agunan');
    currencyInputs.forEach(input => {
        if (input.value) {
            formatCurrency(input);
        }
        
        // Format on input
        input.addEventListener('input', function() {
            formatCurrency(this);
        });
        
        // Before form submit, remove formatting
        input.form.addEventListener('submit', function() {
            input.value = input.value.replace(/[^\d]/g, '');
        });
    });
});
</script>

<?= $this->endSection() ?>
