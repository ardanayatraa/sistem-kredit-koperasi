<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Angsuran</h1>
                <p class="text-gray-600">Lakukan pembayaran angsuran kredit Anda dengan mengupload bukti pembayaran.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li><a href="/home" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
                    <li class="text-gray-400">/</li>
                    <li><a href="/bayar-angsuran" class="text-gray-500 hover:text-gray-700">Daftar Angsuran</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-900">Bayar Angsuran</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <i class="bx bx-check-circle h-5 w-5 mr-2"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <i class="bx bx-error-circle h-5 w-5 mr-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Detail Angsuran -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="bx bx-info-circle text-blue-600 mr-2"></i>
            Detail Angsuran
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Angsuran Ke:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <?= $angsuran['angsuran_ke'] ?>
                    </span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Jumlah Angsuran:</span>
                    <span class="text-sm font-bold text-gray-900">Rp <?= number_format($angsuran['jumlah_angsuran'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Tanggal Jatuh Tempo:</span>
                    <?php
                    $tanggalTempo = date('d M Y', strtotime($angsuran['tgl_jatuh_tempo']));
                    $isOverdue = $isOverdue ?? false;
                    ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $isOverdue ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' ?>">
                        <?= $tanggalTempo ?> 
                        <?= $isOverdue ? '(TERLAMBAT)' : '' ?>
                    </span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Belum Lunas
                    </span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Sudah Dibayar:</span>
                    <span class="text-sm text-gray-900">Rp <?= number_format($total_dibayar ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Sisa Tagihan:</span>
                    <span class="text-sm font-bold text-red-600">Rp <?= number_format($angsuran['jumlah_angsuran'] - ($total_dibayar ?? 0), 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pembayaran -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-900">
                <i class="bx bx-money-bill-wave text-green-600 mr-2"></i>
                Form Pembayaran
            </h2>
        </div>
        <div class="p-6">
            <form action="/bayar-angsuran/proses/<?= $angsuran['id_angsuran'] ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jumlah Bayar -->
                    <div>
                        <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Bayar <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">Rp</span>
                            </div>
                            <input type="text" 
                                   class="block w-full pl-12 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 <?= isset($validation) && $validation->hasError('jumlah_bayar') ? 'border-red-300' : '' ?>"
                                   id="jumlah_bayar" 
                                   name="jumlah_bayar" 
                                   value="<?= number_format($angsuran['jumlah_angsuran'] - ($total_dibayar ?? 0), 0, ',', '.') ?>"
                                   placeholder="Masukkan jumlah pembayaran" 
                                   required>
                        </div>
                        <?php if (isset($validation) && $validation->hasError('jumlah_bayar')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('jumlah_bayar') ?></p>
                        <?php endif; ?>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="bx bx-info-circle mr-1"></i>
                            Disarankan bayar penuh untuk menghindari denda keterlambatan
                        </p>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 <?= isset($validation) && $validation->hasError('metode_pembayaran') ? 'border-red-300' : '' ?>"
                                id="metode_pembayaran" 
                                name="metode_pembayaran" 
                                required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="transfer" <?= old('metode_pembayaran') == 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                            <option value="tunai" <?= old('metode_pembayaran') == 'tunai' ? 'selected' : '' ?>>Tunai</option>
                            <option value="debit" <?= old('metode_pembayaran') == 'debit' ? 'selected' : '' ?>>Kartu Debit</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('metode_pembayaran')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('metode_pembayaran') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Bukti Pembayaran -->
                    <div>
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Bukti Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <input type="file" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 <?= isset($validation) && $validation->hasError('bukti_pembayaran') ? 'border-red-300' : '' ?>"
                               id="bukti_pembayaran" 
                               name="bukti_pembayaran" 
                               accept="image/*"
                               required>
                        <?php if (isset($validation) && $validation->hasError('bukti_pembayaran')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('bukti_pembayaran') ?></p>
                        <?php endif; ?>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="bx bx-info-circle mr-1"></i>
                            Upload foto struk/bukti transfer (JPG/PNG, max 2MB)
                        </p>
                    </div>

                </div>

                <!-- Warning Box -->
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <i class="bx bx-info-circle text-yellow-600 h-5 w-5 mt-0.5 mr-3"></i>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">Perhatian!</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Pastikan bukti pembayaran yang diupload jelas dan dapat dibaca</li>
                                    <li>Pembayaran akan diverifikasi oleh bendahara dalam 1x24 jam</li>
                                    <li>Simpan bukti pembayaran asli sampai angsuran selesai</li>
                                    <?php if ($isOverdue): ?>
                                    <li class="font-medium text-red-700">Pembayaran Anda sudah terlambat. Mungkin dikenakan denda.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="/bayar-angsuran" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        <i class="bx bx-arrow-back mr-2"></i>
                        Kembali ke Daftar
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        <i class="bx bx-paper-plane mr-2"></i>
                        Kirim Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Format currency input
document.getElementById('jumlah_bayar').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d]/g, '');
    if (value) {
        e.target.value = new Intl.NumberFormat('id-ID').format(value);
    }
});

// Remove formatting before form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const jumlahBayar = document.getElementById('jumlah_bayar');
    jumlahBayar.value = jumlahBayar.value.replace(/[^\d]/g, '');
});

// Auto-select full payment amount button
function bayarPenuh() {
    const sisaTagihan = <?= $angsuran['jumlah_angsuran'] - ($total_dibayar ?? 0) ?>;
    document.getElementById('jumlah_bayar').value = new Intl.NumberFormat('id-ID').format(sisaTagihan);
}
</script>
<?= $this->endSection() ?>