<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Riwayat Pembayaran</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap pembayaran angsuran</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?= base_url('riwayat-pembayaran/print/' . $pembayaran['id_pembayaran']) ?>" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors"
                       target="_blank">
                        <i class="bx bx-printer"></i>
                        Print Bukti
                    </a>
                    <a href="<?= base_url('riwayat-pembayaran') ?>" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="bx bx-arrow-back"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Data Pembayaran -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Data Pembayaran</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">No. Anggota</label>
                            <p class="mt-1 text-sm text-gray-900"><?= esc($pembayaran['no_anggota']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Anggota</label>
                            <p class="mt-1 text-sm text-gray-900"><?= esc($pembayaran['nama_lengkap']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Angsuran Ke</label>
                            <p class="mt-1 text-sm text-gray-900"><?= esc($pembayaran['angsuran_ke']) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Jumlah Angsuran</label>
                            <p class="mt-1 text-sm text-gray-900">Rp <?= number_format($pembayaran['jumlah_angsuran'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Detail Transaksi</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Jumlah Dibayar</label>
                            <p class="mt-1 text-lg font-semibold text-green-600">Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tanggal Pembayaran</label>
                            <p class="mt-1 text-sm text-gray-900"><?= date('d F Y', strtotime($pembayaran['tanggal_bayar'])) ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Metode Pembayaran</label>
                            <p class="mt-1 text-sm text-gray-900"><?= esc($pembayaran['metode_pembayaran'] ?? 'Tunai') ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status Verifikasi</label>
                            <?php
                            $statusVerifikasi = $pembayaran['status_verifikasi'] ?? 'pending';
                            $statusDisplay = match($statusVerifikasi) {
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                default => 'Menunggu Verifikasi'
                            };
                            $statusColor = match($statusVerifikasi) {
                                'approved' => 'green',
                                'rejected' => 'red',
                                default => 'yellow'
                            };
                            $statusIcon = match($statusVerifikasi) {
                                'approved' => 'bx-check-circle',
                                'rejected' => 'bx-x-circle',
                                default => 'bx-time-five'
                            };
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                <i class="bx <?= $statusIcon ?> mr-1"></i>
                                <?= esc($statusDisplay) ?>
                            </span>
                        </div>
                        <?php if (!empty($pembayaran['denda']) && $pembayaran['denda'] > 0): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Denda</label>
                            <p class="mt-1 text-sm text-red-600">Rp <?= number_format($pembayaran['denda'], 0, ',', '.') ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Data Kredit -->
            <div class="space-y-4 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Data Kredit</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jumlah Kredit</label>
                        <p class="mt-1 text-sm text-gray-900">Rp <?= number_format($pembayaran['jumlah_pengajuan'], 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <?php if (!empty($pembayaran['catatan'])): ?>
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Catatan</h3>
                <p class="text-sm text-gray-900"><?= esc($pembayaran['catatan']) ?></p>
            </div>
            <?php endif; ?>

            <!-- Info Tambahan -->
            <div class="mt-8 bg-blue-50 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="bx bx-info-circle text-blue-600 text-lg mt-0.5 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800">Informasi</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Bukti pembayaran ini dapat digunakan sebagai referensi transaksi. 
                            Simpan dokumen ini dengan baik untuk keperluan administrasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>