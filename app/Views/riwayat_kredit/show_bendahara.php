<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Riwayat Kredit</h2>
                    <p class="text-sm text-gray-600 mt-1">Detail lengkap pengajuan kredit #<?= esc($kredit['id_kredit']) ?></p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="/bendahara/riwayat-kredit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                    <a href="/bendahara/riwayat-kredit/print/<?= esc($kredit['id_kredit']) ?>" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                <?= session()->getFlashdata('success') ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                <?= session()->getFlashdata('error') ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Status Badge -->
            <div class="mb-6">
                <?php
                $statusClasses = [
                    'Diajukan' => 'bg-yellow-100 text-yellow-800',
                    'Pending' => 'bg-orange-100 text-orange-800',
                    'Disetujui' => 'bg-green-100 text-green-800',
                    'Ditolak' => 'bg-red-100 text-red-800',
                    'Berjalan' => 'bg-blue-100 text-blue-800',
                    'Selesai' => 'bg-gray-100 text-gray-800'
                ];
                $statusClass = $statusClasses[$kredit['status_kredit']] ?? 'bg-gray-100 text-gray-800';
                ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $statusClass ?>">
                    Status: <?= esc($kredit['status_kredit']) ?>
                </span>
            </div>

            <!-- Detail Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Data Pengajuan -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Data Pengajuan</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID Kredit</label>
                            <p class="text-sm text-gray-900">#<?= esc($kredit['id_kredit']) ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Anggota</label>
                            <p class="text-sm text-gray-900"><?= esc($kredit['nama_lengkap']) ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">No Anggota</label>
                            <p class="text-sm text-gray-900"><?= esc($kredit['no_anggota'] ?? '-') ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan</label>
                            <p class="text-sm text-gray-900"><?= date('d F Y', strtotime($kredit['tanggal_pengajuan'])) ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Pengajuan</label>
                            <p class="text-sm text-gray-900 font-semibold">Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jangka Waktu</label>
                            <p class="text-sm text-gray-900"><?= esc($kredit['jangka_waktu']) ?> bulan</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tujuan Kredit</label>
                            <p class="text-sm text-gray-900"><?= esc($kredit['tujuan_kredit']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Data Agunan -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Data Agunan</h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Agunan</label>
                            <p class="text-sm text-gray-900"><?= esc($kredit['jenis_agunan']) ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nilai Taksiran Agunan</label>
                            <p class="text-sm text-gray-900 font-semibold">Rp <?= number_format($kredit['nilai_taksiran_agunan'] ?? 0, 0, ',', '.') ?></p>
                        </div>

                        <?php if (!empty($kredit['dokumen_agunan'])): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dokumen Agunan</label>
                            <div class="mt-1">
                                <?php
                                $fileName = basename($kredit['dokumen_agunan']);
                                $isImage = in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']);
                                ?>
                                <?php if ($isImage): ?>
                                    <div class="w-48 h-32 bg-gray-200 rounded-lg overflow-hidden border">
                                        <img src="/kredit/view-document/<?= esc($kredit['dokumen_agunan']) ?>" alt="Dokumen Agunan" class="w-full h-full object-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="bx bx-file text-blue-600 h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Dokumen Agunan</p>
                                            <p class="text-xs text-gray-500"><?= $fileName ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="mt-2 flex gap-2">
                                    <button type="button" onclick="previewFile('<?= esc($kredit['dokumen_agunan']) ?>', 'agunan')" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        <i class="bx bx-show h-3 w-3"></i>
                                        Preview
                                    </button>
                                    <a href="/kredit/view-document/<?= esc($kredit['dokumen_agunan']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                        <i class="bx bx-download h-3 w-3"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Catatan Section -->
            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Catatan & Persetujuan</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Catatan Bendahara -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Catatan Bendahara</h4>
                        <p class="text-sm text-blue-800">
                            <?= !empty($kredit['catatan_bendahara']) ? esc($kredit['catatan_bendahara']) : '<em class="text-blue-600">Belum ada catatan</em>' ?>
                        </p>
                    </div>

                    <!-- Catatan Appraiser -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-green-900 mb-2">Catatan Penilai (Appraiser)</h4>
                        <p class="text-sm text-green-800">
                            <?= !empty($kredit['catatan_appraiser']) ? esc($kredit['catatan_appraiser']) : '<em class="text-green-600">Belum ada catatan</em>' ?>
                        </p>
                    </div>

                    <!-- Catatan Ketua -->
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-purple-900 mb-2">Catatan Ketua</h4>
                        <p class="text-sm text-purple-800">
                            <?= !empty($kredit['catatan_ketua']) ? esc($kredit['catatan_ketua']) : '<em class="text-purple-600">Belum ada catatan</em>' ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-4">Timeline Proses</h3>

                <div class="space-y-4">
                    <?php
                    $timeline = [
                        ['date' => $kredit['tanggal_pengajuan'], 'title' => 'Pengajuan Dibuat', 'description' => 'Anggota mengajukan kredit baru', 'status' => 'completed'],
                        ['date' => $kredit['tanggal_verifikasi_bendahara'] ?? null, 'title' => 'Verifikasi Bendahara', 'description' => 'Dokumen diverifikasi oleh bendahara', 'status' => !empty($kredit['tanggal_verifikasi_bendahara']) ? 'completed' : 'pending'],
                        ['date' => $kredit['tanggal_penilaian_appraiser'] ?? null, 'title' => 'Penilaian Appraiser', 'description' => 'Agunan dinilai oleh appraiser', 'status' => !empty($kredit['tanggal_penilaian_appraiser']) ? 'completed' : 'pending'],
                        ['date' => $kredit['tanggal_persetujuan_ketua'] ?? null, 'title' => 'Persetujuan Ketua', 'description' => 'Kredit diputuskan oleh ketua', 'status' => !empty($kredit['tanggal_persetujuan_ketua']) ? 'completed' : 'pending'],
                        ['date' => $kredit['tanggal_pencairan'] ?? null, 'title' => 'Pencairan Dana', 'description' => 'Dana kredit dicairkan', 'status' => !empty($kredit['tanggal_pencairan']) ? 'completed' : 'pending'],
                    ];
                    ?>

                    <?php foreach ($timeline as $item): ?>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <?php if ($item['status'] === 'completed'): ?>
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            <?php else: ?>
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-medium text-gray-900"><?= esc($item['title']) ?></h4>
                                <?php if ($item['date']): ?>
                                    <span class="text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($item['date'])) ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-600 mt-1"><?= esc($item['description']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// File preview function
function previewFile(filename, type) {
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

    // Load file content
    const previewContent = modal.querySelector('#preview-content');
    const fileUrl = `/kredit/view-document/${filename}`;

    // Check if it's an image
    if (filename.toLowerCase().match(/\.(jpg|jpeg|png)$/)) {
        previewContent.innerHTML = `<img src="${fileUrl}" alt="${type}" class="max-w-full max-h-full object-contain" onload="this.previousElementSibling?.remove()" onerror="this.parentElement.innerHTML='<div class=\'text-center\'><i class=\'bx bx-error h-12 w-12 text-red-400 mb-4\'></i><p class=\'text-red-600\'>Gagal memuat preview gambar</p></div>'">`;
    } else if (filename.toLowerCase().endsWith('.pdf')) {
        previewContent.innerHTML = `
            <div class="text-center">
                <i class="bx bx-file-pdf h-12 w-12 text-red-400 mb-4"></i>
                <p class="text-gray-600 mb-4">File PDF - Klik tombol download untuk melihat</p>
                <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    <i class="bx bx-download h-4 w-4"></i>
                    Download PDF
                </a>
            </div>
        `;
    } else {
        previewContent.innerHTML = `
            <div class="text-center">
                <i class="bx bx-file h-12 w-12 text-gray-400 mb-4"></i>
                <p class="text-gray-600 mb-4">File tidak dapat dipreview</p>
                <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    <i class="bx bx-download h-4 w-4"></i>
                    Download File
                </a>
            </div>
        `;
    }
}
</script>

<?= $this->endSection() ?>