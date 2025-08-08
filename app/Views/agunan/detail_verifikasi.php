<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Verifikasi Agunan</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap agunan untuk verifikasi</p>
                </div>
                <a href="<?= base_url('verifikasi-agunan') ?>" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="bx bx-arrow-back"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Data Anggota -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Data Anggota</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Lengkap</label>
                            <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['nama_lengkap']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">NIK</label>
                            <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['nik']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Alamat</label>
                            <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['alamat']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">No. HP</label>
                            <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['no_hp']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Data Kredit</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Jumlah Pengajuan</label>
                            <p class="mt-1 text-sm text-gray-900">Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tujuan Kredit</label>
                            <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['tujuan_kredit'] ?? '-') ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Jangka Waktu</label>
                            <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['jangka_waktu'] ?? '-') ?> bulan</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tanggal Pengajuan</label>
                            <p class="mt-1 text-sm text-gray-900"><?= date('d/m/Y H:i', strtotime($kredit['created_at'])) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Agunan -->
            <div class="space-y-4 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Data Agunan</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jenis Agunan</label>
                        <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['jenis_agunan'] ?? '-') ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Deskripsi Agunan</label>
                        <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['deskripsi_agunan'] ?? '-') ?></p>
                    </div>
                </div>
                
                <?php if (!empty($kredit['nilai_taksiran_agunan'])): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Nilai Taksiran</label>
                    <p class="mt-1 text-sm text-gray-900">Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($kredit['catatan_appraiser'])): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Catatan Appraiser</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['catatan_appraiser']) ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Form Verifikasi -->
            <?php if (empty($kredit['catatan_appraiser'])): ?>
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Verifikasi Agunan</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Form Setujui -->
                    <form action="<?= base_url('verifikasi-agunan/approve/' . $kredit['id_kredit']) ?>" method="post" class="space-y-4">
                        <?= csrf_field() ?>
                        
                        <div>
                            <label for="nilai_agunan" class="block text-sm font-medium text-gray-700">Nilai Taksiran Agunan</label>
                            <input type="number" 
                                   name="nilai_agunan" 
                                   id="nilai_agunan" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   placeholder="Masukkan nilai taksiran"
                                   required>
                        </div>
                        
                        <div>
                            <label for="catatan_setuju" class="block text-sm font-medium text-gray-700">Catatan Persetujuan</label>
                            <textarea name="catatan_appraiser" 
                                      id="catatan_setuju" 
                                      rows="3" 
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                      placeholder="Masukkan catatan persetujuan"
                                      required></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="bx bx-check"></i>
                            Setujui Agunan
                        </button>
                    </form>

                    <!-- Form Tolak -->
                    <form action="<?= base_url('verifikasi-agunan/reject/' . $kredit['id_kredit']) ?>" method="post" class="space-y-4">
                        <?= csrf_field() ?>
                        
                        <div>
                            <label for="catatan_tolak" class="block text-sm font-medium text-gray-700">Catatan Penolakan</label>
                            <textarea name="catatan_appraiser" 
                                      id="catatan_tolak" 
                                      rows="5" 
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                      placeholder="Masukkan alasan penolakan"
                                      required></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <i class="bx bx-x"></i>
                            Tolak Agunan
                        </button>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="border-t border-gray-200 pt-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-center text-gray-600">
                        <i class="bx bx-info-circle text-lg mr-2"></i>
                        Agunan ini sudah diverifikasi
                    </p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>