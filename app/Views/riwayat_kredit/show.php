<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= $headerTitle ?? 'Detail Riwayat Kredit' ?></h1>
                <p class="text-gray-600">Detail informasi kredit dan status pembayaran.</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('riwayat-kredit') ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
                <a href="<?= base_url('riwayat-kredit/print/' . $kredit['id_kredit']) ?>" target="_blank" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </a>
            </div>
        </div>
    </div>

    <!-- Credit Information -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Kredit</h3>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">No. Anggota</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= esc($kredit['no_anggota'] ?? 'N/A') ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= esc($kredit['nama_lengkap'] ?? 'N/A') ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Jumlah Kredit</dt>
                    <dd class="mt-1 text-sm text-gray-900">Rp <?= number_format($kredit['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Jangka Waktu</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= $kredit['jangka_waktu'] ?? '0' ?> bulan</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tujuan Kredit</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= esc($kredit['tujuan_kredit'] ?? 'N/A') ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status Kredit</dt>
                    <dd class="mt-1">
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                            <?= esc($status) ?>
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Pengajuan</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'] ?? 'now')) ?></dd>
                </div>
                <?php if (!empty($kredit['tanggal_persetujuan'])): ?>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tanggal Persetujuan</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= date('d/m/Y', strtotime($kredit['tanggal_persetujuan'])) ?></dd>
                </div>
                <?php endif; ?>
            </dl>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Kontak</h3>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">NIK</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= esc($kredit['nik'] ?? 'N/A') ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">No. HP</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?= esc($kredit['no_hp'] ?? 'N/A') ?></dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>
        <div class="flex flex-wrap gap-3">
            <?php if ($kredit['status_kredit'] === 'Aktif'): ?>
                <a href="<?= base_url('riwayat-pembayaran?kredit=' . $kredit['id_kredit']) ?>" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                    Lihat Riwayat Pembayaran
                </a>
            <?php endif; ?>
            
            <a href="<?= base_url('simulasi-bunga') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Simulasi Bunga Baru
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>