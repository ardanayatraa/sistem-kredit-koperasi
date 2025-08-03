<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 mt-4">
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Total Jenis Bunga</p>
                <p class="text-2xl font-bold text-blue-600"><?= count($bunga ?? []) ?></p>
            </div>
            <div class="p-2 bg-blue-50 rounded-lg flex-shrink-0">
                <i class="bx bx-percentage text-blue-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Rata-rata Bunga</p>
                <p class="text-2xl font-bold text-green-600">
                    <?= count($bunga ?? []) > 0 ? number_format(array_sum(array_column($bunga, 'persentase')) / count($bunga), 2) : 0 ?>%
                </p>
            </div>
            <div class="p-2 bg-green-50 rounded-lg flex-shrink-0">
                <i class="bx bx-chart-line text-green-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Bunga Tertinggi</p>
                <p class="text-2xl font-bold text-red-600">
                    <?= count($bunga ?? []) > 0 ? max(array_column($bunga, 'persentase')) : 0 ?>%
                </p>
            </div>
            <div class="p-2 bg-red-50 rounded-lg flex-shrink-0">
                <i class="bx bx-arrow-up text-red-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Bunga Terendah</p>
                <p class="text-2xl font-bold text-purple-600">
                    <?= count($bunga ?? []) > 0 ? min(array_column($bunga, 'persentase')) : 0 ?>%
                </p>
            </div>
            <div class="p-2 bg-purple-50 rounded-lg flex-shrink-0">
                <i class="bx bx-arrow-down text-purple-600 h-6 w-6"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm">
    <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Bunga</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola tingkat bunga koperasi</p>
            </div>
            <a href="/bunga/new" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                <i class="bx bx-plus h-4 w-4"></i>
                <span class="hidden sm:inline">Tambah Bunga</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mt-4">
            <div class="relative max-w-sm">
                <i class="bx bx-search absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="search-input" placeholder="Cari bunga..." class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <?php if (empty($bunga)): ?>
            <div class="px-4 sm:px-6 py-12 text-center">
                <i class="bx bx-percentage mx-auto h-12 w-12 text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data bunga</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan tingkat bunga baru.</p>
                <div class="mt-6">
                    <a href="/bunga/new" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        <i class="bx bx-plus h-4 w-4"></i>
                        Tambah Bunga
                    </a>
                </div>
            </div>
        <?php else: ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Bunga</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                        <th class="hidden sm:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Berlaku</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="bunga-table-body">
                    <?php foreach ($bunga as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors bunga-row" 
                            data-search="<?= strtolower(esc($row['jenis_bunga'] . ' ' . $row['persentase'])) ?>">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($row['id']) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= esc($row['jenis_bunga']) ?></div>
                                <div class="sm:hidden text-xs text-gray-500"><?= date('d/m/Y', strtotime($row['tanggal_berlaku'])) ?></div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?= esc($row['persentase']) ?>%
                                </span>
                            </td>
                            <td class="hidden sm:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= date('d/m/Y', strtotime($row['tanggal_berlaku'])) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1 sm:gap-2">
                                    <a href="/bunga/show/<?= esc($row['id']) ?>" 
                                       class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-900 text-xs sm:text-sm p-1 sm:p-0">
                                        <i class="bx bx-eye h-4 w-4"></i>
                                        <span class="hidden sm:inline">Lihat</span>
                                    </a>
                                    <a href="/bunga/edit/<?= esc($row['id']) ?>" 
                                       class="inline-flex items-center gap-1 text-yellow-600 hover:text-yellow-900 text-xs sm:text-sm p-1 sm:p-0">
                                        <i class="bx bx-edit h-4 w-4"></i>
                                        <span class="hidden sm:inline">Edit</span>
                                    </a>
                                    <form action="/bunga/delete/<?= esc($row['id']) ?>" method="post" class="inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data bunga ini?');">
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
        const rows = document.querySelectorAll('.bunga-row');
        
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
