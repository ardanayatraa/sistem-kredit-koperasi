<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Dashboard Pembayaran</h1>
        <p class="text-gray-600">Kelola pembayaran angsuran dan lihat status kredit Anda</p>
    </div>

    <?php if (empty($dashboard)): ?>
    <!-- No Credit State -->
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <div class="p-3 bg-blue-100 rounded-lg inline-block mb-4">
            <i class="bx bx-info-circle text-blue-600 h-8 w-8"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Kredit Aktif</h3>
        <p class="text-gray-600 mb-6">Anda belum memiliki kredit yang disetujui atau belum dicairkan.</p>
        <a href="/kredit/new" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="bx bx-plus h-4 w-4"></i>
            Ajukan Kredit Baru
        </a>
    </div>
    <?php else: ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <?php
        $totalKredit = count($dashboard);
        $totalAngsuranBelumLunas = 0;
        $angsuranTerdekat = null;
        $minTanggal = null;
        $currentTime = date('Y-m-d');
        
        foreach ($dashboard as $item) {
            $totalAngsuranBelumLunas += $item['total_belum_lunas'];
            if ($item['angsuran_terdekat']) {
                $tglTempo = strtotime($item['angsuran_terdekat']['tgl_jatuh_tempo']);
                if (!$minTanggal || $tglTempo < $minTanggal) {
                    $minTanggal = $tglTempo;
                    $angsuranTerdekat = $item['angsuran_terdekat'];
                }
            }
        }
        
        // Helper function untuk check terlambat
        $isOverdue = function($tanggal) use ($currentTime) {
            return $tanggal < $currentTime;
        };
        ?>
        
        <!-- Total Kredit Aktif -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="bx bx-credit-card text-blue-600 h-6 w-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Kredit Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= $totalKredit ?></p>
                </div>
            </div>
        </div>

        <!-- Angsuran Belum Lunas -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="bx bx-error text-yellow-600 h-6 w-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Angsuran Belum Lunas</p>
                    <p class="text-2xl font-semibold text-gray-900"><?= $totalAngsuranBelumLunas ?></p>
                </div>
            </div>
        </div>

        <!-- Angsuran Terdekat -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-<?= $angsuranTerdekat ? ($isOverdue($angsuranTerdekat['tgl_jatuh_tempo']) ? 'red' : 'green') : 'gray' ?>-100 rounded-lg">
                        <i class="bx bx-calendar text-<?= $angsuranTerdekat ? ($isOverdue($angsuranTerdekat['tgl_jatuh_tempo']) ? 'red' : 'green') : 'gray' ?>-600 h-6 w-6"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-600">Angsuran Terdekat</p>
                        <?php if ($angsuranTerdekat): ?>
                        <p class="text-lg font-semibold text-gray-900">
                            <?= date('d M Y', strtotime($angsuranTerdekat['tgl_jatuh_tempo'])) ?>
                        </p>
                        <p class="text-sm text-gray-600">
                            Rp <?= number_format($angsuranTerdekat['jumlah_angsuran'], 0, ',', '.') ?> - Angsuran ke-<?= $angsuranTerdekat['angsuran_ke'] ?>
                        </p>
                        <?php else: ?>
                        <p class="text-lg font-semibold text-gray-900">Tidak ada</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Setiap Kredit -->
    <?php foreach ($dashboard as $item): ?>
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="bx bx-credit-card text-blue-600 mr-2"></i>
                    Kredit ID: <?= $item['kredit']['id_kredit'] ?> -
                    Rp <?= number_format($item['kredit']['jumlah_kredit'], 0, ',', '.') ?>
                </h3>
                <div class="flex items-center space-x-2">
                    <a href="/angsuran/jadwal/<?= $item['kredit']['id_kredit'] ?>"
                       class="text-sm text-blue-600 hover:text-blue-900 flex items-center">
                        <i class="bx bx-list-ul mr-1"></i>
                        Jadwal Lengkap
                    </a>
                    <a href="/riwayat-pembayaran"
                       class="text-sm text-blue-600 hover:text-blue-900 flex items-center">
                        <i class="bx bx-history mr-1"></i>
                        Riwayat
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Info Kredit -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Jangka Waktu</p>
                            <p class="text-lg font-semibold text-gray-900"><?= $item['kredit']['jangka_waktu'] ?> bulan</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <?= $item['kredit']['status_kredit'] ?>
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Angsuran Belum Lunas</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <?= $item['total_belum_lunas'] ?> angsuran
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Angsuran Terdekat -->
                <div>
                    <?php if ($item['angsuran_terdekat']): ?>
                    <?php $itemOverdue = $isOverdue($item['angsuran_terdekat']['tgl_jatuh_tempo']); ?>
                    <div class="border-l-4 border-<?= $itemOverdue ? 'red' : 'blue' ?>-500 bg-<?= $itemOverdue ? 'red' : 'blue' ?>-50 p-4 rounded-lg">
                        <h4 class="text-sm font-semibold text-<?= $itemOverdue ? 'red' : 'blue' ?>-800 mb-2">
                            <?= $itemOverdue ? 'TERLAMBAT!' : 'Angsuran Terdekat' ?>
                        </h4>
                        <div class="space-y-1 text-sm text-gray-700">
                            <p><span class="font-medium">Tanggal:</span> <?= date('d M Y', strtotime($item['angsuran_terdekat']['tgl_jatuh_tempo'])) ?></p>
                            <p><span class="font-medium">Jumlah:</span> Rp <?= number_format($item['angsuran_terdekat']['jumlah_angsuran'], 0, ',', '.') ?></p>
                            <p><span class="font-medium">Ke:</span> <?= $item['angsuran_terdekat']['angsuran_ke'] ?></p>
                        </div>
                        <a href="/angsuran/bayar/<?= $item['angsuran_terdekat']['id_angsuran'] ?>"
                           class="mt-3 inline-flex items-center gap-1 bg-<?= $itemOverdue ? 'red' : 'green' ?>-600 text-white px-3 py-1.5 rounded text-sm hover:bg-<?= $itemOverdue ? 'red' : 'green' ?>-700 transition-colors">
                            <i class="bx bx-credit-card"></i>
                            Bayar Sekarang
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-lg text-center">
                        <i class="bx bx-check-circle text-green-600 h-8 w-8 mx-auto mb-2"></i>
                        <h4 class="text-sm font-semibold text-green-800">Lunas</h4>
                        <p class="text-xs text-green-700">Semua angsuran sudah dibayar</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="bx bx-wrench text-blue-600 mr-2"></i>
            Aksi Cepat
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="/kredit/new" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <i class="bx bx-plus text-blue-600 h-6 w-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Ajukan Kredit Baru</p>
                    <p class="text-xs text-gray-500">Buat pengajuan baru</p>
                </div>
            </a>

            <a href="/riwayat-kredit" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="p-2 bg-gray-100 rounded-lg mr-3">
                    <i class="bx bx-history text-gray-600 h-6 w-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Riwayat Kredit</p>
                    <p class="text-xs text-gray-500">Lihat history kredit</p>
                </div>
            </a>

            <a href="/riwayat-pembayaran" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="p-2 bg-green-100 rounded-lg mr-3">
                    <i class="bx bx-receipt text-green-600 h-6 w-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Riwayat Pembayaran</p>
                    <p class="text-xs text-gray-500">Lihat history bayar</p>
                </div>
            </a>

            <a href="/simulasi-bunga" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                    <i class="bx bx-calculator text-yellow-600 h-6 w-6"></i>
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