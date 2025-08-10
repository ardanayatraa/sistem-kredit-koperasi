<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="max-w-6xl mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail Anggota</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap anggota koperasi</p>
                </div>
                <div class="flex items-center gap-2">
                    <?php
                    $statusColors = [
                        'Aktif' => 'bg-green-100 text-green-800',
                        'Tidak Aktif' => 'bg-red-100 text-red-800',
                        'Pending' => 'bg-yellow-100 text-yellow-800'
                    ];
                    $statusClass = $statusColors[$anggota['status_keanggotaan']] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $statusClass ?>">
                        <i class="bx bx-circle text-xs mr-1.5"></i>
                        <?= esc($anggota['status_keanggotaan']) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Data Pribadi -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bx bx-user text-blue-600 h-5 w-5"></i>
                    Data Pribadi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="bx bx-id-card text-blue-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">ID Anggota</p>
                                <p class="text-lg font-semibold text-gray-900">#<?= esc($anggota['id_anggota']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="bx bx-id-card text-green-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">NIK</p>
                                <p class="text-lg font-semibold text-gray-900 font-mono"><?= esc($anggota['nik']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i class="bx bx-map-marker-alt text-purple-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tempat, Tanggal Lahir</p>
                                <p class="text-lg font-semibold text-gray-900"><?= esc($anggota['tempat_lahir']) ?>, <?= date('d F Y', strtotime($anggota['tanggal_lahir'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-orange-100 rounded-lg">
                                <i class="bx bx-briefcase text-orange-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pekerjaan</p>
                                <p class="text-lg font-semibold text-gray-900"><?= esc($anggota['pekerjaan']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-indigo-100 rounded-lg">
                                <i class="bx bx-home text-indigo-600 h-5 w-5"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-600">Alamat</p>
                                <p class="text-lg font-semibold text-gray-900 leading-relaxed"><?= esc($anggota['alamat']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Keanggotaan -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bx bx-user-check text-green-600 h-5 w-5"></i>
                    Data Keanggotaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="bx bx-calendar-check text-green-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tanggal Pendaftaran</p>
                                <p class="text-lg font-semibold text-gray-900"><?= date('d F Y', strtotime($anggota['tanggal_pendaftaran'])) ?></p>
                                <p class="text-sm text-gray-500"><?= floor((time() - strtotime($anggota['tanggal_pendaftaran'])) / (365*24*3600)) ?> tahun yang lalu</p>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($anggota['tanggal_masuk_anggota'])): ?>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-teal-100 rounded-lg">
                                <i class="bx bx-calendar-plus text-teal-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Tanggal Masuk Anggota</p>
                                <p class="text-lg font-semibold text-gray-900"><?= date('d F Y', strtotime($anggota['tanggal_masuk_anggota'])) ?></p>
                                <p class="text-sm text-gray-500"><?= floor((time() - strtotime($anggota['tanggal_masuk_anggota'])) / (365*24*3600)) ?> tahun sebagai anggota</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="bx bx-info-circle text-blue-600 h-5 w-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Status Keanggotaan</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium <?= $statusClass ?> mt-1">
                                    <?= esc($anggota['status_keanggotaan']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <i class="bx bx-file-alt text-purple-600 h-5 w-5"></i>
                    Dokumen
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-700">Dokumen KTP</h4>
                            <i class="bx bx-id-card text-blue-600 h-5 w-5"></i>
                        </div>
                        <?php if (!empty($anggota['dokumen_ktp'])): ?>
                            <p class="text-sm text-gray-600 mb-2"><?= esc($anggota['dokumen_ktp']) ?></p>
                            <a href="/uploads/anggota/<?= esc($anggota['dokumen_ktp']) ?>" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm">
                                <i class="bx bx-external-link-alt h-4 w-4"></i>
                                Lihat Dokumen
                            </a>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Tidak ada dokumen</p>
                        <?php endif; ?>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-700">Dokumen KK</h4>
                            <i class="bx bx-id-card text-green-600 h-5 w-5"></i>
                        </div>
                        <?php if (!empty($anggota['dokumen_kk'])): ?>
                            <p class="text-sm text-gray-600 mb-2"><?= esc($anggota['dokumen_kk']) ?></p>
                            <a href="/uploads/anggota/<?= esc($anggota['dokumen_kk']) ?>" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm">
                                <i class="bx bx-external-link-alt h-4 w-4"></i>
                                Lihat Dokumen
                            </a>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Tidak ada dokumen</p>
                        <?php endif; ?>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-700">Dokumen Slip Gaji</h4>
                            <i class="bx bx-file-invoice-dollar text-orange-600 h-5 w-5"></i>
                        </div>
                        <?php if (!empty($anggota['dokumen_slip_gaji'])): ?>
                            <p class="text-sm text-gray-600 mb-2"><?= esc($anggota['dokumen_slip_gaji']) ?></p>
                            <a href="/uploads/anggota/<?= esc($anggota['dokumen_slip_gaji']) ?>" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm">
                                <i class="bx bx-external-link-alt h-4 w-4"></i>
                                Lihat Dokumen
                            </a>
                        <?php else: ?>
                            <p class="text-sm text-gray-500">Tidak ada dokumen</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/anggota/edit/<?= esc($anggota['id_anggota']) ?>" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 bg-yellow-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-yellow-700 transition-colors">
                    <i class="bx bx-edit h-4 w-4"></i>
                    Edit Anggota
                </a>
                <form action="/anggota/delete/<?= esc($anggota['id_anggota']) ?>" method="post" class="flex-1 sm:flex-none" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini? Semua dokumen akan ikut terhapus.');">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        <i class="bx bx-trash h-4 w-4"></i>
                        Hapus Anggota
                    </button>
                </form>
                <a href="/anggota" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="bx bx-arrow-left h-4 w-4"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
