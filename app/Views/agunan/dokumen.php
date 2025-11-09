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
                        <?php 
                        // Build correct URL - dokumen_agunan already contains folder path
                        $dokumenUrl = base_url('writable/uploads/' . $kredit['dokumen_agunan']);
                        ?>
                        <button type="button" 
                                onclick="showDokumenModal('<?= $dokumenUrl ?>')"
                                class="inline-flex items-center gap-1 px-3 py-1 bg-purple-600 text-white text-sm font-medium rounded hover:bg-purple-700 transition-colors">
                            <i class="bx bx-show text-sm"></i>
                            Lihat
                        </button>
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


<!-- Modal untuk preview dokumen -->
<div id="dokumenModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Preview Dokumen Agunan</h3>
            <button type="button" onclick="closeDokumenModal()" class="text-gray-400 hover:text-gray-600">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        <div class="p-4 overflow-auto max-h-[calc(90vh-8rem)]">
            <img id="dokumenImage" src="" alt="Dokumen Agunan" class="w-full h-auto">
        </div>
        <div class="flex items-center justify-end gap-2 p-4 border-t">
            <a id="dokumenDownload" href="" download class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <i class="bx bx-download"></i>
                Download
            </a>
            <button type="button" onclick="closeDokumenModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function showDokumenModal(url) {
    const modal = document.getElementById('dokumenModal');
    const image = document.getElementById('dokumenImage');
    const download = document.getElementById('dokumenDownload');
    
    image.src = url;
    download.href = url;
    modal.classList.remove('hidden');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeDokumenModal() {
    const modal = document.getElementById('dokumenModal');
    modal.classList.add('hidden');
    
    // Restore body scroll
    document.body.style.overflow = '';
}

// Close modal when clicking outside
document.getElementById('dokumenModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDokumenModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDokumenModal();
    }
});
</script>

<?= $this->endSection() ?>
