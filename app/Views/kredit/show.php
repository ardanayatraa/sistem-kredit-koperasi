<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Kredit</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap pengajuan kredit</p>
                </div>
                <div class="flex items-center gap-2">
                    <?php
                    $statusColors = [
                        'disetujui' => 'bg-green-100 text-green-800',
                        'ditolak' => 'bg-red-100 text-red-800',
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'dalam proses' => 'bg-blue-100 text-blue-800',
                    ];
                    $statusClass = $statusColors[strtolower($kredit['status_kredit'])] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $statusClass ?>">
                        <i class="bx bx-circle text-xs mr-1.5"></i>
                        <?= esc($kredit['status_kredit']) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Data Pengajuan -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bx bx-file-invoice-dollar text-blue-600 h-5 w-5"></i>
                    Data Pengajuan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="bx bx-id-card text-blue-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">ID Kredit</p>
                                <p class="text-lg font-semibold text-gray-900">#<?= esc($kredit['id_kredit']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="bx bx-user text-green-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">ID Anggota</p>
                                <p class="text-lg font-semibold text-gray-900"><?= esc($kredit['id_anggota']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i class="bx bx-calendar-alt text-purple-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tanggal Pengajuan</p>
                                <p class="text-lg font-semibold text-gray-900"><?= date('d F Y', strtotime($kredit['tanggal_pengajuan'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <i class="bx bx-money-bill-wave text-orange-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Jumlah Pengajuan</p>
                                <p class="text-lg font-semibold text-gray-900">Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <i class="bx bx-clock text-indigo-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Jangka Waktu</p>
                                <p class="text-lg font-semibold text-gray-900"><?= esc($kredit['jangka_waktu']) ?> bulan</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="bx bx-bullseye text-blue-600 h-5 w-5"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600">Tujuan Kredit</p>
                                <p class="text-lg font-semibold text-gray-900 leading-relaxed"><?= esc($kredit['tujuan_kredit']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Agunan -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bx bx-shield-alt text-green-600 h-5 w-5"></i>
                    Data Agunan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="bx bx-file-contract text-green-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Jenis Agunan</p>
                                <p class="text-lg font-semibold text-gray-900"><?= esc($kredit['jenis_agunan']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="bx bx-coins text-blue-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Nilai Taksiran Agunan</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    <?= !empty($kredit['nilai_taksiran_agunan']) ? 'Rp ' . number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') : 'Belum ditaksir' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan dan Status -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bx bx-sticky-note text-purple-600 h-5 w-5"></i>
                    Catatan dan Status
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i class="bx bx-user-tie text-purple-600 h-5 w-5"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600">Catatan Bendahara</p>
                                <p class="text-base text-gray-900 leading-relaxed mt-1">
                                    <?= !empty($kredit['catatan_bendahara']) ? esc($kredit['catatan_bendahara']) : '<span class="text-gray-500 italic">Tidak ada catatan</span>' ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <i class="bx bx-user-tie text-orange-600 h-5 w-5"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600">Catatan Appraiser</p>
                                <p class="text-base text-gray-900 leading-relaxed mt-1">
                                    <?= !empty($kredit['catatan_appraiser']) ? esc($kredit['catatan_appraiser']) : '<span class="text-gray-500 italic">Tidak ada catatan</span>' ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="bx bx-user-tie text-blue-600 h-5 w-5"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600">Catatan Ketua</p>
                                <p class="text-base text-gray-900 leading-relaxed mt-1">
                                    <?= !empty($kredit['catatan_ketua']) ? esc($kredit['catatan_ketua']) : '<span class="text-gray-500 italic">Tidak ada catatan</span>' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/kredit/edit/<?= esc($kredit['id_kredit']) ?>" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-yellow-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-yellow-700 transition-colors">
                    <i class="bx bx-edit h-4 w-4"></i>
                    Edit Kredit
                </a>
                <form action="/kredit/delete/<?= esc($kredit['id_kredit']) ?>" method="post" class="flex-1 sm:flex-none" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kredit ini?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Kredit
                    </button>
                </form>
                <a href="/kredit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="bx bx-arrow-left h-4 w-4"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
