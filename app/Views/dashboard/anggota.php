<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Dashboard Anggota</h1>
        <p class="text-gray-600">Selamat datang, <?= esc($userData['nama_lengkap'] ?? 'Anggota') ?>!</p>
        <p class="text-sm text-gray-500">Kelola kredit dan pembayaran Anda dari dashboard ini.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Total Kredit Aktif -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kredit Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp <?= number_format($kreditAktif ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <!-- Sisa Angsuran -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sisa Angsuran</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= number_format($sisaAngsuran ?? 0) ?> bulan</p>
                </div>
            </div>
        </div>

        <!-- Total Terbayar -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Terbayar</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp <?= number_format($totalTerbayar ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <!-- Status Pembayaran -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-<?= ($statusPembayaran ?? 'green') === 'lancar' ? 'green' : 'red' ?>-100 rounded-lg">
                    <svg class="h-6 w-6 text-<?= ($statusPembayaran ?? 'green') === 'lancar' ? 'green' : 'red' ?>-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <?php if (($statusPembayaran ?? 'lancar') === 'lancar'): ?>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        <?php else: ?>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z" />
                        <?php endif; ?>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Status</p>
                    <p class="text-lg font-semibold text-<?= ($statusPembayaran ?? 'green') === 'lancar' ? 'green' : 'red' ?>-600">
                        <?= ucfirst($statusPembayaran ?? 'Lancar') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kredit Saya -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Kredit Saya</h3>
                <a href="<?= base_url('riwayat-kredit') ?>" class="text-sm text-indigo-600 hover:text-indigo-900">
                    Lihat Semua →
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kredit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bunga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jangka Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angsuran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($kreditSaya)): ?>
                        <?php foreach ($kreditSaya as $kredit): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= number_format($kredit['bunga'], 2) ?>%
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $kredit['jangka_waktu'] ?> bulan
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp <?= number_format($kredit['jumlah_angsuran'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusColor = '';
                                    $statusText = '';
                                    switch ($kredit['status']) {
                                        case 'aktif':
                                            $statusColor = 'green';
                                            $statusText = 'Aktif';
                                            break;
                                        case 'lunas':
                                            $statusColor = 'blue';
                                            $statusText = 'Lunas';
                                            break;
                                        case 'bermasalah':
                                            $statusColor = 'red';
                                            $statusText = 'Bermasalah';
                                            break;
                                        default:
                                            $statusColor = 'gray';
                                            $statusText = 'Pending';
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    <p>Belum ada kredit aktif</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pembayaran Terakhir -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Pembayaran Terakhir</h3>
                    <a href="<?= base_url('riwayat-pembayaran') ?>" class="text-sm text-indigo-600 hover:text-indigo-900">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="p-6">
                <?php if (!empty($pembayaranTerakhir)): ?>
                    <div class="space-y-4">
                        <?php foreach ($pembayaranTerakhir as $pembayaran): ?>
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        Angsuran ke-<?= $pembayaran['angsuran_ke'] ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?= date('d/m/Y', strtotime($pembayaran['tanggal_pembayaran'])) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">
                                        Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?>
                                    </p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Lunas
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p>Belum ada pembayaran</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Jadwal Pembayaran -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Jadwal Pembayaran</h3>
            </div>
            <div class="p-6">
                <?php if (!empty($jadwalPembayaran)): ?>
                    <div class="space-y-4">
                        <?php foreach ($jadwalPembayaran as $jadwal): ?>
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        Angsuran ke-<?= $jadwal['angsuran_ke'] ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Jatuh tempo: <?= date('d/m/Y', strtotime($jadwal['tanggal_jatuh_tempo'])) ?>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">
                                        Rp <?= number_format($jadwal['jumlah_angsuran'], 0, ',', '.') ?>
                                    </p>
                                    <?php
                                    $today = date('Y-m-d');
                                    $jatuhTempo = $jadwal['tanggal_jatuh_tempo'];
                                    $isOverdue = $today > $jatuhTempo;
                                    ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?= $isOverdue ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?= $isOverdue ? 'Terlambat' : 'Belum Bayar' ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p>Tidak ada jadwal pembayaran</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Payment Action Section -->
    <?php if (!empty($jadwalPembayaran)): ?>
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold mb-2">Ada Angsuran yang Perlu Dibayar!</h3>
                <p class="text-orange-100">
                    Anda memiliki <?= count($jadwalPembayaran) ?> angsuran yang belum dibayar.
                    <?php
                    $nextDue = null;
                    foreach ($jadwalPembayaran as $jadwal) {
                        if (!$nextDue || strtotime($jadwal['tanggal_jatuh_tempo']) < strtotime($nextDue['tanggal_jatuh_tempo'])) {
                            $nextDue = $jadwal;
                        }
                    }
                    if ($nextDue):
                        $daysLeft = (strtotime($nextDue['tanggal_jatuh_tempo']) - strtotime(date('Y-m-d'))) / (60*60*24);
                        if ($daysLeft < 0):
                    ?>
                        <br>Pembayaran terdekat sudah terlambat <?= abs($daysLeft) ?> hari!
                    <?php elseif ($daysLeft == 0): ?>
                        <br>Pembayaran terdekat jatuh tempo hari ini!
                    <?php else: ?>
                        <br>Pembayaran terdekat dalam <?= $daysLeft ?> hari.
                    <?php endif; endif; ?>
                </p>
            </div>
            <div class="flex flex-col gap-2">
                <a href="<?= base_url('angsuran/bayar') ?>" class="bg-white text-orange-600 px-6 py-3 rounded-lg font-semibold hover:bg-orange-50 transition-colors shadow-md">
                    <svg class="inline h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                    Bayar Angsuran
                </a>
                <a href="<?= base_url('riwayat-pembayaran') ?>" class="text-orange-100 hover:text-white text-sm text-center underline">
                    Lihat Riwayat →
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center">
            <svg class="h-8 w-8 text-green-200 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h3 class="text-xl font-bold mb-1">Selamat!</h3>
                <p class="text-green-100">Semua angsuran Anda sudah lunas atau belum ada kredit aktif.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="<?= base_url('angsuran/bayar') ?>" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                <div class="p-2 bg-orange-100 rounded-lg mr-3">
                    <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Bayar Angsuran</p>
                    <p class="text-xs text-gray-500">Lakukan pembayaran</p>
                </div>
            </a>

            <a href="<?= base_url('riwayat-kredit') ?>" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Riwayat Kredit</p>
                    <p class="text-xs text-gray-500">Lihat history kredit</p>
                </div>
            </a>

            <a href="<?= base_url('riwayat-pembayaran') ?>" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Riwayat Pembayaran</p>
                    <p class="text-xs text-gray-500">Lihat history bayar</p>
                </div>
            </a>

            <a href="<?= base_url('simulasi-bunga') ?>" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Simulasi Bunga</p>
                    <p class="text-xs text-gray-500">Hitung simulasi</p>
                </div>
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>