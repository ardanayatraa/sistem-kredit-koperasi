<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Detail Riwayat Penilaian</h1>
                <p class="text-gray-600">Detail lengkap penilaian agunan kredit anggota.</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('riwayat-penilaian/print/' . ($kredit['id_kredit'] ?? 0)) ?>" target="_blank" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </a>
                <a href="<?= base_url('riwayat-penilaian') ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <?php if (!empty($kredit)): ?>
    <!-- Detail Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informasi Anggota -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Anggota</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nama Lengkap</label>
                        <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['nama_lengkap'] ?? 'N/A') ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">ID Anggota</label>
                        <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['id_anggota'] ?? 'N/A') ?></p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Alamat</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['alamat'] ?? 'N/A') ?></p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">No. HP</label>
                        <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['no_hp'] ?? 'N/A') ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Pekerjaan</label>
                        <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['pekerjaan'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Kredit -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Kredit</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jumlah Pengajuan</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">Rp <?= number_format($kredit['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jangka Waktu</label>
                        <p class="mt-1 text-sm text-gray-900"><?= $kredit['jangka_waktu'] ?? '0' ?> bulan</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tujuan Kredit</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['tujuan_kredit'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Tanggal Pengajuan</label>
                    <p class="mt-1 text-sm text-gray-900"><?= date('d F Y', strtotime($kredit['tanggal_pengajuan'] ?? 'now')) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Status Kredit</label>
                    <?php 
                    $status = $kredit['status_kredit'] ?? 'Pending';
                    $statusColor = '';
                    switch ($status) {
                        case 'Disetujui':
                        case 'Aktif':
                            $statusColor = 'green';
                            break;
                        case 'Lunas':
                            $statusColor = 'blue';
                            break;
                        case 'Ditolak':
                            $statusColor = 'red';
                            break;
                        case 'Diajukan':
                            $statusColor = 'yellow';
                            break;
                        default:
                            $statusColor = 'gray';
                    }
                    ?>
                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                        <?= esc($status) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Agunan dan Penilaian -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Agunan dan Penilaian</h3>
        </div>
        <div class="p-6 space-y-6">
            <!-- Informasi Agunan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Jenis Agunan</label>
                    <p class="text-sm text-gray-900"><?= esc($kredit['jenis_agunan'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Nilai Taksiran Agunan</label>
                    <?php if (!empty($kredit['nilai_taksiran_agunan'])): ?>
                        <p class="text-lg font-semibold text-green-600">Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></p>
                    <?php else: ?>
                        <p class="text-sm text-gray-400">Belum dinilai</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Catatan Penilaian -->
            <div class="grid grid-cols-1 gap-6">
                <!-- Catatan Bendahara -->
                <?php if (!empty($kredit['catatan_bendahara'])): ?>
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Catatan Bendahara</h4>
                    <p class="text-sm text-blue-700"><?= esc($kredit['catatan_bendahara']) ?></p>
                </div>
                <?php endif; ?>

                <!-- Catatan Appraiser -->
                <?php if (!empty($kredit['catatan_appraiser'])): ?>
                <div class="bg-green-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-green-800 mb-2">Catatan Appraiser/Penilai</h4>
                    <p class="text-sm text-green-700"><?= esc($kredit['catatan_appraiser']) ?></p>
                </div>
                <?php endif; ?>

                <!-- Catatan Ketua -->
                <?php if (!empty($kredit['catatan_ketua'])): ?>
                <div class="bg-purple-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-purple-800 mb-2">Catatan Ketua Koperasi</h4>
                    <p class="text-sm text-purple-700"><?= esc($kredit['catatan_ketua']) ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Timeline Penilaian -->
            <div class="border-t pt-6">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Timeline Penilaian</h4>
                <div class="flow-root">
                    <ul class="-mb-8">
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="bg-blue-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Kredit diajukan</p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time><?= date('d M Y', strtotime($kredit['tanggal_pengajuan'] ?? 'now')) ?></time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <?php if (!empty($kredit['catatan_appraiser'])): ?>
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="bg-green-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Agunan dinilai oleh Appraiser</p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time><?= date('d M Y', strtotime($kredit['updated_at'] ?? 'now')) ?></time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>

                        <li>
                            <div class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <?php if ($kredit['status_kredit'] === 'Disetujui'): ?>
                                            <span class="bg-green-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                        <?php elseif ($kredit['status_kredit'] === 'Ditolak'): ?>
                                            <span class="bg-red-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </span>
                                        <?php else: ?>
                                            <span class="bg-yellow-500 h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Status: <?= esc($kredit['status_kredit'] ?? 'Pending') ?></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time><?= date('d M Y', strtotime($kredit['updated_at'] ?? 'now')) ?></time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- Data Not Found -->
    <div class="bg-white rounded-lg shadow p-12">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Data tidak ditemukan</h3>
            <p class="mt-1 text-sm text-gray-500">Riwayat penilaian yang Anda cari tidak ditemukan atau telah dihapus.</p>
            <div class="mt-6">
                <a href="<?= base_url('riwayat-penilaian') ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Kembali ke Riwayat Penilaian
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>