<?php
$currentUserLevel = session()->get('level');
$canEditBendaharaFields = $currentUserLevel === 'Bendahara';
$canEditAppraiserFields = $currentUserLevel === 'Appraiser';
$canEditKetuaFields = $currentUserLevel === 'Ketua Koperasi';
$canEditAllFields = $currentUserLevel === 'Admin';

// Jika sedang lihat, ambil data anggota dari $anggota yang dikirim controller
$anggotaData = isset($anggota) ? $anggota : null;

?>

<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Kredit</h2>
                    <p class="text-sm text-gray-600 mt-1">Melihat informasi lengkap pengajuan kredit</p>
                </div>
                <div class="flex gap-2">
                    <a href="/kredit/edit/<?= esc($kredit['id_kredit']) ?>" class="inline-flex items-center gap-2 rounded-lg btn-primary px-4 py-2 text-sm font-medium transition-colors">
                        <i class="bx bx-edit h-4 w-4"></i>
                        Edit
                    </a>
                    <a href="/kredit" class="inline-flex items-center gap-2 rounded-lg btn-secondary px-4 py-2 text-sm font-medium transition-colors">
                        <i class="bx bx-arrow-back h-4 w-4"></i>
                        Kembali
                    </a>
                </div>
            </div>
            <?php if ($currentUserLevel && $currentUserLevel !== 'Admin'): ?>
                <p class="text-xs text-gray-500 mt-2">
                    Anda login sebagai: <span class="font-medium"><?= $currentUserLevel ?></span>
                </p>
            <?php endif; ?>
        </div>

        <div class="p-6 space-y-6">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ID Anggota
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed"
                               value="<?= esc($kredit['id_anggota']) ?>"
                               readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pengajuan
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed"
                               value="<?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'])) ?>"
                               readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Pengajuan (Rp)
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed"
                               value="Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?>"
                               readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jangka Waktu (bulan)
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed"
                               value="<?= $kredit['jangka_waktu'] ?> bulan"
                               readonly>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tujuan Kredit
                    </label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed resize-none"
                              rows="3"
                              readonly><?= esc($kredit['tujuan_kredit']) ?></textarea>
                </div>

                <!-- Tampilkan info anggota jika ada -->
                <?php if ($anggotaData): ?>
                    <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <i class="bx bx-user text-blue-600 h-4 w-4"></i>
                            <span class="text-sm font-medium text-blue-900">Data Anggota:</span>
                        </div>
                        <div class="mt-1 text-sm text-blue-800">
                            <p><strong>Nama:</strong> <?= esc($anggotaData['nama_lengkap'] ?? 'N/A') ?></p>
                            <p><strong>No. Anggota:</strong> <?= esc($anggotaData['no_anggota'] ?? 'N/A') ?></p>
                            <p><strong>Alamat:</strong> <?= esc($anggotaData['alamat'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Data Agunan Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-shield-alt text-green-600 h-5 w-5"></i>
                        Data Agunan
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Agunan
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed"
                               value="<?= esc($kredit['jenis_agunan']) ?>"
                               readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai Taksiran Agunan (Rp)
                        </label>
                        <input type="text"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed"
                               value="<?= $kredit['nilai_taksiran_agunan'] ? 'Rp ' . number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') : '-' ?>"
                               readonly>
                    </div>

                    <!-- Dokumen Agunan -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Dokumen Agunan
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                            <?php if (!empty($kredit['dokumen_agunan'])): ?>
                                <div class="text-center">
                                    <div class="relative inline-block">
                                        <img src="/kredit/view-document/<?= esc($kredit['dokumen_agunan']) ?>" alt="Dokumen Agunan" class="max-w-full max-h-64 mx-auto rounded-lg shadow-sm border border-gray-200" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                        <div class="text-center text-gray-500 text-sm" style="display: none;">
                                            <i class="bx bx-error text-2xl mb-2"></i>
                                            <p>Gagal memuat preview gambar</p>
                                            <p class="text-xs text-gray-400 mt-1">File: <?= esc($kredit['dokumen_agunan']) ?></p>
                                            <div class="flex gap-2 justify-center mt-2">
                                                <a href="/kredit/view-document/<?= esc($kredit['dokumen_agunan']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                                    <i class="bx bx-download h-3 w-3"></i>
                                                    Download
                                                </a>
                                                <a href="/kredit/view-document/<?= esc($kredit['dokumen_agunan']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                                    <i class="bx bx-eye h-3 w-3"></i>
                                                    Lihat
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-2">File: <?= esc($kredit['dokumen_agunan']) ?></p>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-gray-500">
                                    <i class="bx bx-file-blank text-4xl mb-2"></i>
                                    <p>Tidak ada dokumen agunan</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen Anggota Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Dokumen Anggota
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">Tersedia</span>
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Dokumen KTP dan slip gaji diambil dari data anggota yang sudah ada di sistem</p>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- KTP Info -->
                        <div class="bg-white border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">📄 KTP</p>
                                    <p class="text-sm text-gray-500">Dokumen pengaju kredit</p>
                                </div>
                                <div class="ml-auto flex gap-2">
                                    <?php if ($anggotaData && $anggotaData['id_anggota']): ?>
                                        <button type="button" onclick="previewAnggotaDocument('ktp', '<?= esc($anggotaData['id_anggota']) ?>')" class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                            <i class="bx bx-show h-3 w-3"></i>
                                            Lihat
                                        </button>
                                        <a href="/anggota/view-document/<?= esc($anggotaData['id_anggota']) ?>/ktp" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                            <i class="bx bx-download h-3 w-3"></i>
                                            Download
                                        </a>
                                    <?php endif; ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✓ Tersedia
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Slip Gaji Info -->
                        <div class="bg-white border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">💰 Slip Gaji</p>
                                    <p class="text-sm text-gray-500">Dokumen pengaju kredit</p>
                                </div>
                                <div class="ml-auto flex gap-2">
                                    <?php if ($anggotaData && $anggotaData['id_anggota']): ?>
                                        <button type="button" onclick="previewAnggotaDocument('slip_gaji', '<?= esc($anggotaData['id_anggota']) ?>')" class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                            <i class="bx bx-show h-3 w-3"></i>
                                            Lihat
                                        </button>
                                        <a href="/anggota/view-document/<?= esc($anggotaData['id_anggota']) ?>/slip_gaji" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                            <i class="bx bx-download h-3 w-3"></i>
                                            Download
                                        </a>
                                    <?php endif; ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✓ Tersedia
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Informasi:</strong> Dokumen KTP dan slip gaji diambil dari data anggota yang mengajukan kredit.
                                    Pastikan data profil anggota sudah lengkap dan terkini.
                                </p>
                            </div>
                        </div>
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Bendahara
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed resize-none"
                                  rows="3"
                                  readonly><?= esc($kredit['catatan_bendahara'] ?? '') ?></textarea>
                    </div>

                    <?php if (!empty($kredit['catatan_appraiser'])): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Penilai (Appraiser)
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed resize-none"
                                  rows="3"
                                  readonly><?= esc($kredit['catatan_appraiser']) ?></textarea>
                    </div>
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Ketua
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed resize-none"
                                  rows="3"
                                  readonly><?= esc($kredit['catatan_ketua'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status Kredit
                        </label>
                        <?php
                        $statusColors = [
                            'disetujui' => 'bg-green-100 text-green-800',
                            'ditolak' => 'bg-red-100 text-red-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'dalam proses' => 'bg-blue-100 text-blue-800',
                            'aktif' => 'bg-green-100 text-green-800',
                            'tidak aktif' => 'bg-red-100 text-red-800',
                        ];
                        $statusClass = $statusColors[strtolower($kredit['status_kredit'])] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <div class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 cursor-not-allowed">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                <?= esc($kredit['status_kredit']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal for document preview -->
<div id="document-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title">Preview Dokumen</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        <div class="p-4">
            <div id="modal-content" class="flex justify-center items-center min-h-96">
                <!-- Content will be loaded here -->
            </div>
        </div>
        <div class="flex justify-end gap-3 p-4 border-t">
            <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
// Preview anggota document function (same as profile)
function previewAnggotaDocument(type, idAnggota) {
    if (!idAnggota) {
        alert('ID Anggota tidak ditemukan. Pastikan Anda sudah login sebagai anggota.');
        return;
    }

    const modal = document.getElementById('document-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content');

    // Set modal title
    const titles = {
        'ktp': 'Preview Dokumen KTP',
        'kk': 'Preview Dokumen Kartu Keluarga',
        'slip_gaji': 'Preview Dokumen Slip Gaji'
    };
    modalTitle.textContent = titles[type] || 'Preview Dokumen';

    // Clear previous content and show loading
    modalContent.innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';

    // Show modal
    modal.classList.remove('hidden');

    // Load content based on file type (simulate getting filename from server)
    // For now, we'll try to load the document directly
    const fileUrl = `/anggota/view-document/${idAnggota}/${type}`;

    // Try to load as image first
    const img = new Image();
    img.onload = function() {
        modalContent.innerHTML = `
            <div class="relative">
                <img id="modal-image" src="${fileUrl}"
                     alt="Preview ${type}"
                     class="max-w-full max-h-96 mx-auto cursor-zoom-in"
                     onclick="toggleZoom(this)">
                <div class="text-center mt-2 text-sm text-gray-500">
                    Klik gambar untuk zoom in/out
                </div>
            </div>
        `;
    };
    img.onerror = function() {
        // If not an image, try as PDF or show download link
        modalContent.innerHTML = `
            <div class="text-center">
                <i class="bx bx-file text-6xl text-gray-500 mb-4"></i>
                <p class="text-lg font-medium text-gray-900 mb-2">File Tidak Dapat Dipreview</p>
                <p class="text-gray-600 mb-4">Dokumen ${type.toUpperCase().replace('_', ' ')}</p>
                <a href="${fileUrl}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    <i class="bx bx-download"></i>
                    Download File
                </a>
            </div>
        `;
    };
    img.src = fileUrl;
}

// Helper functions for modal
function toggleZoom(img) {
    if (img.classList.contains('zoomed')) {
        img.classList.remove('zoomed');
        img.style.transform = 'scale(1)';
        img.style.cursor = 'zoom-in';
    } else {
        img.classList.add('zoomed');
        img.style.transform = 'scale(1.5)';
        img.style.cursor = 'zoom-out';
    }
}

function closeModal() {
    const modal = document.getElementById('document-modal');
    const modalImage = document.querySelector('#modal-image');

    // Reset zoom if image was zoomed
    if (modalImage) {
        modalImage.classList.remove('zoomed');
        modalImage.style.transform = 'scale(1)';
    }

    modal.classList.add('hidden');
}
</script>

<?= $this->endSection() ?>
