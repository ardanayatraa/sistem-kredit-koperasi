<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Laporan Kredit</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap detail kredit dan angsuran</p>
                </div>
                <div class="flex gap-2">
                    <a href="/laporan-kredit/generate-pdf/<?= esc($kredit['id_kredit']) ?>" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download PDF
                    </a>
                    <a href="/laporan-kredit" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Informasi Kredit -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Informasi Kredit
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">ID Kredit</label>
                        <p class="text-sm text-gray-900 font-medium"><?= esc($kredit['id_kredit']) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Tanggal Pengajuan</label>
                        <p class="text-sm text-gray-900"><?= date('d F Y H:i', strtotime($kredit['tanggal_pengajuan'])) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Jumlah Pinjaman</label>
                        <p class="text-sm text-gray-900 font-semibold">Rp <?= number_format($kredit['jumlah_pinjaman'], 0, ',', '.') ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <?php 
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'disetujui' => 'bg-green-100 text-green-800',
                            'ditolak' => 'bg-red-100 text-red-800',
                            'berjalan' => 'bg-blue-100 text-blue-800',
                            'selesai' => 'bg-gray-100 text-gray-800'
                        ];
                        $statusTexts = [
                            'pending' => 'Pending',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                            'berjalan' => 'Berjalan',
                            'selesai' => 'Selesai'
                        ];
                        $statusClass = $statusColors[$kredit['status']] ?? 'bg-gray-100 text-gray-800';
                        ?>
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                            <?= $statusTexts[$kredit['status']] ?? $kredit['status'] ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Anggota -->
            <div class="space-y-4 pt-6 border-t border-gray-200">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informasi Anggota
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Nama Anggota</label>
                        <p class="text-sm text-gray-900 font-semibold"><?= esc($anggota['nama_lengkap'] ?? '-') ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">No. HP</label>
                        <p class="text-sm text-gray-900"><?= esc($anggota['no_hp'] ?? '-') ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-sm text-gray-900"><?= esc($anggota['email'] ?? '-') ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Alamat</label>
                        <p class="text-sm text-gray-900"><?= esc($anggota['alamat'] ?? '-') ?></p>
                    </div>
                </div>
            </div>

            <!-- Statistik Angsuran -->
            <div class="space-y-4 pt-6 border-t border-gray-200">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Statistik Angsuran
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-blue-600">Total Angsuran</p>
                        <p class="text-2xl font-bold text-blue-900"><?= count($angsurans) ?></p>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-green-600">Lunas</p>
                        <p class="text-2xl font-bold text-green-900"><?= $angsuranLunas ?></p>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-yellow-600">Belum Lunas</p>
                        <p class="text-2xl font-bold text-yellow-900"><?= count($angsurans) - $angsuranLunas ?></p>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-purple-600">Total Dibayar</p>
                        <p class="text-2xl font-bold text-purple-900">Rp <?= number_format($totalDibayar, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <!-- Daftar Angsuran -->
            <div class="space-y-4 pt-6 border-t border-gray-200">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        Daftar Angsuran
                    </h3>
                </div>

                <?php if (empty($angsurans)): ?>
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Belum ada data angsuran</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Noth>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($angsurans as $index => $angsuran): ?>
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $index + 1 ?></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d M Y', strtotime($angsuran['tanggal_pembayaran'])) ?></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?= number_format($angsuran['jumlah_angsuran'], 0, ',', '.') ?></td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <?php 
                                            $angsuranStatusColors = [
                                                'lunas' => 'bg-green-100 text-green-800',
                                                'belum_lunas' => 'bg-red-100 text-red-800',
                                                'terlambat' => 'bg-yellow-100 text-yellow-800'
                                            ];
                                            $angsuranStatusTexts = [
                                                'lunas' => 'Lunas',
                                                'belum_lunas' => 'Belum Lunas',
                                                'terlambat' => 'Terlambat'
                                            ];
                                            $angsuranStatusClass = $angsuranStatusColors[$angsuran['status_pembayaran']] ?? 'bg-gray-100 text-gray-800';
                                            ?>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $angsuranStatusClass ?>">
                                                <?= $angsuranStatusTexts[$angsuran['status_pembayaran']] ?? $angsuran['status_pembayaran'] ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-500"><?= esc($angsuran['keterangan'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>