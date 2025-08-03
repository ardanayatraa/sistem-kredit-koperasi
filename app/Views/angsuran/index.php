<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 mt-4">
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Total Angsuran</p>
                <p class="text-2xl font-bold text-blue-600"><?= count($angsuran ?? []) ?></p>
            </div>
            <div class="p-2 bg-blue-50 rounded-lg flex-shrink-0">
                <i class="bx bx-file-invoice-dollar text-blue-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Angsuran Lunas</p>
                <p class="text-2xl font-bold text-green-600">
                    <?= count(array_filter($angsuran ?? [], function($a) { 
                        return strtolower($a['status_pembayaran']) === 'lunas'; 
                    })) ?>
                </p>
            </div>
            <div class="p-2 bg-green-50 rounded-lg flex-shrink-0">
                <i class="bx bx-check-circle text-green-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Angsuran Tertunggak</p>
                <p class="text-2xl font-bold text-red-600">
                    <?= count(array_filter($angsuran ?? [], function($a) { 
                        return strtolower($a['status_pembayaran']) === 'tertunggak'; 
                    })) ?>
                </p>
            </div>
            <div class="p-2 bg-red-50 rounded-lg flex-shrink-0">
                <i class="bx bx-exclamation-triangle text-red-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Jatuh Tempo Minggu Ini</p>
                <p class="text-2xl font-bold text-yellow-600">
                    <?php
                    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
                    $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
                    echo count(array_filter($angsuran ?? [], function($a) use ($startOfWeek, $endOfWeek) { 
                        $jatuhTempo = $a['tgl_jatuh_tempo'];
                        return $jatuhTempo >= $startOfWeek && $jatuhTempo <= $endOfWeek;
                    }));
                    ?>
                </p>
            </div>
            <div class="p-2 bg-yellow-50 rounded-lg flex-shrink-0">
                <i class="bx bx-clock text-yellow-600 h-6 w-6"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm">
    <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Angsuran</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola data angsuran kredit</p>
            </div>
            <a href="/angsuran/new" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                <i class="bx bx-plus h-4 w-4"></i>
                <span class="hidden sm:inline">Tambah Angsuran</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mt-4">
            <div class="relative max-w-sm">
                <i class="bx bx-search absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="search-input" placeholder="Cari angsuran..." class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <?php if (empty($angsuran)): ?>
            <div class="px-4 sm:px-6 py-12 text-center">
                <i class="bx bx-file-invoice-dollar mx-auto h-12 w-12 text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data angsuran</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan angsuran baru.</p>
                <div class="mt-6">
                    <a href="/angsuran/new" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        <i class="bx bx-plus h-4 w-4"></i>
                        Tambah Angsuran
                    </a>
                </div>
            </div>
        <?php else: ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Kredit</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angsuran Ke</th>
                        <th class="hidden md:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="hidden sm:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="angsuran-table-body">
                    <?php foreach ($angsuran as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors angsuran-row" 
                            data-search="<?= strtolower(esc($row['id_kredit'] . ' ' . $row['angsuran_ke'] . ' ' . $row['jumlah_angsuran'] . ' ' . $row['status_pembayaran'])) ?>">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($row['id_angsuran']) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= esc($row['id_kredit']) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= esc($row['angsuran_ke']) ?></td>
                            <td class="hidden md:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600">Rp <?= number_format($row['jumlah_angsuran'], 0, ',', '.') ?></td>
                            <td class="hidden sm:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= date('d/m/Y', strtotime($row['tgl_jatuh_tempo'])) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusColors = [
                                    'lunas' => 'bg-green-100 text-green-800',
                                    'tertunggak' => 'bg-red-100 text-red-800',
                                    'belum bayar' => 'bg-yellow-100 text-yellow-800',
                                    'sebagian' => 'bg-blue-100 text-blue-800',
                                ];
                                $statusClass = $statusColors[strtolower($row['status_pembayaran'])] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?>">
                                    <?= esc($row['status_pembayaran']) ?>
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1 sm:gap-2">
                                    <a href="/angsuran/show/<?= esc($row['id_angsuran']) ?>" 
                                       class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-900 text-xs sm:text-sm p-1 sm:p-0">
                                        <i class="bx bx-eye h-4 w-4"></i>
                                        <span class="hidden sm:inline">Lihat</span>
                                    </a>
                                    <a href="/angsuran/edit/<?= esc($row['id_angsuran']) ?>" 
                                       class="inline-flex items-center gap-1 text-yellow-600 hover:text-yellow-900 text-xs sm:text-sm p-1 sm:p-0">
                                        <i class="bx bx-edit h-4 w-4"></i>
                                        <span class="hidden sm:inline">Edit</span>
                                    </a>
                                    <form action="/angsuran/delete/<?= esc($row['id_angsuran']) ?>" method="post" class="inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data angsuran ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-900 text-xs sm:text-sm p-1 sm:p-0">
                                            <i class="bx bx-trash-alt h-4 w-4"></i>
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
        const rows = document.querySelectorAll('.angsuran-row');
        
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
