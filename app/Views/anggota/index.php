<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 mt-4">
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Total Anggota</p>
                <p class="text-2xl font-bold text-blue-600"><?= count($anggota ?? []) ?></p>
            </div>
            <div class="p-2 bg-blue-50 rounded-lg flex-shrink-0">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Anggota Baru Bulan Ini</p>
                <p class="text-2xl font-bold text-green-600">
                    <?= count(array_filter($anggota ?? [], function($member) { 
                        return date('Y-m', strtotime($member['tanggal_pendaftaran'])) === date('Y-m'); 
                    })) ?>
                </p>
            </div>
            <div class="p-2 bg-green-50 rounded-lg flex-shrink-0">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Anggota Aktif</p>
                <p class="text-2xl font-bold text-purple-600"><?= count($anggota ?? []) ?></p>
            </div>
            <div class="p-2 bg-purple-50 rounded-lg flex-shrink-0">
                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Rata-rata per Bulan</p>
                <p class="text-2xl font-bold text-orange-600">
                    <?= count($anggota ?? []) > 0 ? ceil(count($anggota) / max(1, (time() - strtotime(min(array_column($anggota ?? [], 'tanggal_pendaftaran')))) / (30 * 24 * 3600))) : 0 ?>
                </p>
            </div>
            <div class="p-2 bg-orange-50 rounded-lg flex-shrink-0">
                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm">
    <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Anggota</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola data anggota koperasi</p>
            </div>
            <a href="/anggota/new" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Tambah Anggota</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mt-4">
            <div class="relative max-w-sm">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="search-input" placeholder="Cari anggota..." class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <?php if (empty($anggota)): ?>
            <div class="px-4 sm:px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data anggota</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan anggota baru.</p>
                <div class="mt-6">
                    <a href="/anggota/new" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Anggota
                    </a>
                </div>
            </div>
        <?php else: ?>
            <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
            <th class="hidden md:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat, Tgl Lahir</th>
            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
            <th class="hidden lg:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan</th>
            <th class="hidden sm:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200" id="anggota-table-body">
        <?php foreach ($anggota as $row): ?>
            <tr class="hover:bg-gray-50 transition-colors anggota-row" 
                data-search="<?= strtolower(esc($row['nik'] . ' ' . $row['tempat_lahir'] . ' ' . $row['alamat'] . ' ' . $row['pekerjaan'])) ?>">
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($row['id_anggota']) ?></td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-mono text-gray-900"><?= esc($row['nik']) ?></div>
                    <div class="md:hidden text-xs text-gray-500"><?= esc($row['tempat_lahir']) ?>, <?= date('d/m/Y', strtotime($row['tanggal_lahir'])) ?></div>
                </td>
                <td class="hidden md:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <?= esc($row['tempat_lahir']) ?>, <?= date('d/m/Y', strtotime($row['tanggal_lahir'])) ?>
                </td>
                <td class="px-4 sm:px-6 py-4">
                    <div class="text-sm text-gray-900 truncate max-w-[150px] lg:max-w-[200px]"><?= esc($row['alamat']) ?></div>
                    <div class="lg:hidden text-xs text-gray-500 truncate max-w-[150px]"><?= esc($row['pekerjaan']) ?></div>
                </td>
                <td class="hidden lg:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= esc($row['pekerjaan']) ?></td>
                <td class="hidden sm:table-cell px-4 sm:px-6 py-4 whitespace-nowrap">
                    <?php
                    $statusColors = [
                        'Aktif' => 'bg-green-100 text-green-800',
                        'Tidak Aktif' => 'bg-red-100 text-red-800',
                        'Pending' => 'bg-yellow-100 text-yellow-800'
                    ];
                    $statusClass = $statusColors[$row['status_keanggotaan']] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                        <?= esc($row['status_keanggotaan']) ?>
                    </span>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-1 sm:gap-2">
                        <a href="/anggota/show/<?= esc($row['id_anggota']) ?>" 
                           class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-900 text-xs sm:text-sm p-1 sm:p-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="hidden sm:inline">Lihat</span>
                        </a>
                        <a href="/anggota/edit/<?= esc($row['id_anggota']) ?>" 
                           class="inline-flex items-center gap-1 text-yellow-600 hover:text-yellow-900 text-xs sm:text-sm p-1 sm:p-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                        <form action="/anggota/delete/<?= esc($row['id_anggota']) ?>" method="post" class="inline" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-900 text-xs sm:text-sm p-1 sm:p-0">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="hidden sm:inline">Hapus</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        <?php endif; ?>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('search-input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.anggota-row');
        
        rows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<?= $this->endSection() ?>
