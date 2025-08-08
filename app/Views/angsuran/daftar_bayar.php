<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Angsuran</h1>
        <p class="text-gray-600">Pilih angsuran yang ingin Anda bayar</p>
    </div>

    <?php if (!empty($angsuran_list)): ?>
    <!-- Angsuran List -->
    <div class="grid gap-6">
        <?php foreach ($angsuran_list as $angsuran): ?>
        <div class="bg-white rounded-lg shadow-md border-l-4 <?= (strtotime($angsuran['tgl_jatuh_tempo']) < strtotime(date('Y-m-d'))) ? 'border-red-500' : 'border-orange-500' ?>">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Angsuran ke-<?= $angsuran['angsuran_ke'] ?>
                        </h3>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><span class="font-medium">Jumlah Kredit:</span> Rp <?= number_format($angsuran['jumlah_pengajuan'], 0, ',', '.') ?></p>
                            <p><span class="font-medium">Jangka Waktu:</span> <?= $angsuran['jangka_waktu'] ?> bulan</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <?php
                        $isOverdue = strtotime($angsuran['tgl_jatuh_tempo']) < strtotime(date('Y-m-d'));
                        $daysUntilDue = ceil((strtotime($angsuran['tgl_jatuh_tempo']) - strtotime(date('Y-m-d'))) / 86400);
                        ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $isOverdue ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' ?>">
                            <?php if ($isOverdue): ?>
                                Terlambat <?= abs($daysUntilDue) ?> hari
                            <?php elseif ($daysUntilDue == 0): ?>
                                Jatuh tempo hari ini
                            <?php else: ?>
                                <?= $daysUntilDue ?> hari lagi
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600">Jumlah Angsuran</p>
                        <p class="text-lg font-bold text-gray-900">Rp <?= number_format($angsuran['jumlah_angsuran'], 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600">Sudah Dibayar</p>
                        <p class="text-lg font-bold text-green-600">Rp <?= number_format($angsuran['total_dibayar'], 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600">Sisa</p>
                        <p class="text-lg font-bold text-red-600">Rp <?= number_format($angsuran['jumlah_angsuran'] - $angsuran['total_dibayar'], 0, ',', '.') ?></p>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-600">
                        <svg class="inline h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Jatuh Tempo: <?= date('d/m/Y', strtotime($angsuran['tgl_jatuh_tempo'])) ?>
                    </p>
                    <a href="<?= base_url('bayar-angsuran/' . $angsuran['id_angsuran']) ?>"
                       class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        Bayar Sekarang
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <div class="flex flex-col items-center">
            <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Selamat!</h3>
            <p class="text-gray-600 mb-4">Semua angsuran Anda sudah lunas atau belum ada kredit aktif yang perlu dibayar.</p>
            <div class="space-x-4">
                <a href="<?= base_url('dashboard') ?>" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                    Kembali ke Dashboard
                </a>
                <a href="<?= base_url('riwayat-pembayaran') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    Lihat Riwayat Pembayaran
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <svg class="h-5 w-5 text-blue-400 mt-0.5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <h4 class="text-sm font-medium text-blue-900 mb-1">Informasi Pembayaran</h4>
                <div class="text-sm text-blue-700 space-y-1">
                    <p>• Upload bukti pembayaran berupa foto atau screenshot</p>
                    <p>• Pembayaran akan diverifikasi oleh admin dalam 1-2 hari kerja</p>
                    <p>• Anda bisa bayar sebagian atau penuh sesuai kemampuan</p>
                    <p>• Hubungi admin jika ada kendala pembayaran</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>