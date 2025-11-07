<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Pencairan</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap pencairan kredit</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="bx bx-circle text-xs mr-1.5"></i>
                        Dicairkan
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Data Pencairan Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-file-invoice-dollar text-blue-600 h-5 w-5"></i>
                        Data Pencairan
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kredit
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                               value="Kredit #<?= esc($pencairan['id_kredit']) ?> - <?= esc($pencairan['nama_anggota'] ?? 'N/A') ?> (Rp <?= number_format($pencairan['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?>)"
                               disabled>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pencairan
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700" 
                               value="<?= date('d F Y', strtotime($pencairan['tanggal_pencairan'])) ?>" 
                               disabled>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Dicairkan
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                               value="Rp <?= number_format($pencairan['jumlah_dicairkan'], 0, ',', '.') ?>"
                               disabled>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pencairan
                        </label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700" 
                               value="<?= esc($pencairan['metode_pencairan']) ?>" 
                               disabled>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Suku Bunga
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                               value="<?= esc($pencairan['nama_bunga'] ?? 'N/A') ?> - <?= isset($pencairan['persentase_bunga']) ? number_format($pencairan['persentase_bunga'], 2) . '%' : '-' ?>"
                               disabled>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Anggota
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                               value="<?= esc($pencairan['no_anggota'] ?? '-') ?>"
                               disabled>
                    </div>
                </div>
            </div>

            <!-- Informasi Kredit -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-credit-card text-purple-600 h-5 w-5"></i>
                        Informasi Kredit
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Pengajuan
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                               value="Rp <?= number_format($pencairan['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?>"
                               disabled>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jangka Waktu
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700"
                               value="<?= esc($pencairan['jangka_waktu'] ?? '-') ?> Bulan"
                               disabled>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tujuan Kredit
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700" 
                                  rows="2" 
                                  disabled><?= esc($pencairan['tujuan_kredit'] ?? '-') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Bukti Transfer Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-file-invoice text-green-600 h-5 w-5"></i>
                        Bukti Transfer
                    </h3>
                </div>

                <?php if (!empty($pencairan['bukti_transfer'])): ?>
                    <div class="p-3 bg-gray-50 rounded-lg border">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="bx bx-file text-blue-600 h-5 w-5"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Bukti Transfer</p>
                                    <p class="text-xs text-gray-500"><?= esc($pencairan['bukti_transfer']) ?></p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" onclick="previewExistingImage('<?= esc($pencairan['bukti_transfer']) ?>', 'pencairan')" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                    <i class="bx bx-show h-3 w-3"></i>
                                    Preview
                                </button>
                                <a href="/pencairan/view-document/<?= esc($pencairan['bukti_transfer']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                    <i class="bx bx-download h-3 w-3"></i>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        <p class="text-sm text-gray-500 text-center italic">Tidak ada bukti transfer</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/pencairan/edit/<?= esc($pencairan['id_pencairan']) ?>" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-yellow-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-yellow-700 transition-colors">
                    <i class="bx bx-edit h-4 w-4"></i>
                    Edit Pencairan
                </a>
                <form action="/pencairan/delete/<?= esc($pencairan['id_pencairan']) ?>" method="post" class="flex-1 sm:flex-none" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pencairan ini? Bukti transfer juga akan dihapus.');">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        <i class="bx bx-trash-alt h-4 w-4"></i>
                        Hapus Pencairan
                    </button>
                </form>
                <a href="/pencairan" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="bx bx-arrow-left h-4 w-4"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function previewExistingImage(filename, type) {
    // Create modal for preview
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg max-w-4xl max-h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Preview ${type.toUpperCase()}</h3>
                <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x h-6 w-6"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="w-full h-[70vh] flex items-center justify-center bg-gray-100 rounded">
                    <div id="preview-content" class="text-center">
                        <i class="bx bx-loader-alt bx-spin h-12 w-12 text-gray-400 mb-4"></i>
                        <p class="text-gray-600">Loading preview...</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    // Load image
    const previewContent = modal.querySelector('#preview-content');
    const fileUrl = `/pencairan/view-document/${filename}`;

    previewContent.innerHTML = `<img src="${fileUrl}" alt="${type}" class="max-w-full max-h-full object-contain" onload="this.previousElementSibling?.remove()" onerror="this.parentElement.innerHTML='<div class=\'text-center\'><i class=\'bx bx-error h-12 w-12 text-red-400 mb-4\'></i><p class=\'text-red-600\'>Gagal memuat preview gambar</p></div>'">`;
}
</script>

<?= $this->endSection() ?>
