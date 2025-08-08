<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Detail Agunan</h1>
                <p class="text-gray-600">Informasi lengkap tentang agunan kredit anggota.</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('agunan') ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <a href="<?= base_url('agunan/print/' . $kredit['id_kredit']) ?>" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out" target="_blank">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </a>
            </div>
        </div>
    </div>

    <!-- Informasi Anggota -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Anggota</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['nama_lengkap'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">ID Anggota</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['id_anggota'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">NIK</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['nik'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. HP</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['no_hp'] ?? 'N/A') ?></p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['alamat'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Kredit -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Kredit</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah Pengajuan</label>
                    <p class="mt-1 text-sm text-gray-900">Rp <?= number_format($kredit['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tenor</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['tenor'] ?? 'N/A') ?> bulan</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan</label>
                    <p class="mt-1 text-sm text-gray-900"><?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'] ?? 'now')) ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <?php 
                    $statusColor = 'gray';
                    if ($kredit['status_aktif'] == 1) $statusColor = 'green';
                    ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800 mt-1">
                        <?= $kredit['status_aktif'] == 1 ? 'Aktif' : 'Tidak Aktif' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Agunan -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Agunan</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Agunan</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['jenis_agunan'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Agunan</label>
                    <p class="mt-1 text-sm text-gray-900"><?= esc($kredit['deskripsi_agunan'] ?? 'N/A') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nilai Taksiran</label>
                    <?php if (!empty($kredit['nilai_taksiran_agunan'])): ?>
                        <p class="mt-1 text-sm text-gray-900">Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></p>
                    <?php else: ?>
                        <p class="mt-1 text-sm text-red-600">Belum dinilai</p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Penilaian</label>
                    <?php 
                    $status = !empty($kredit['nilai_taksiran_agunan']) ? 'selesai' : 'pending';
                    $statusColor = $status === 'selesai' ? 'green' : 'yellow';
                    $statusText = $status === 'selesai' ? 'Sudah Dinilai' : 'Menunggu Penilaian';
                    ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800 mt-1">
                        <?= $statusText ?>
                    </span>
                </div>
            </div>

            <?php if (!empty($kredit['catatan_appraiser'])): ?>
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700">Catatan Appraiser</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        <p class="text-sm text-gray-900"><?= nl2br(esc($kredit['catatan_appraiser'])) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Foto Agunan jika ada -->
    <?php if (!empty($kredit['foto_agunan'])): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Foto Agunan</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php 
                $photos = is_array($kredit['foto_agunan']) ? $kredit['foto_agunan'] : explode(',', $kredit['foto_agunan']);
                foreach ($photos as $photo): 
                    if (!empty(trim($photo))):
                ?>
                    <div class="relative">
                        <img src="<?= base_url('writable/uploads/agunan/' . trim($photo)) ?>" alt="Foto Agunan" class="w-full h-48 object-cover rounded-md">
                        <a href="<?= base_url('writable/uploads/agunan/' . trim($photo)) ?>" target="_blank" class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-30 transition-all duration-200 rounded-md flex items-center justify-center">
                            <svg class="h-8 w-8 text-white opacity-0 hover:opacity-100 transition-opacity duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </a>
                    </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>