<?= $this->extend('layouts/dashboard_template') ?>
<?php
use App\Config\Roles;
$currentUserLevel = session()->get('level');
?>

<?= $this->section('content') ?>
<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 mt-4">
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Total Pencairan</p>
                <p class="text-2xl font-bold text-blue-600"><?= count($pencairan ?? []) ?></p>
            </div>
            <div class="p-2 bg-blue-50 rounded-lg flex-shrink-0">
                <i class="bx bx-file-invoice-dollar text-blue-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Total Dicairkan</p>
                <p class="text-2xl font-bold text-green-600">
                    <?php
                    $totalDicairkan = array_sum(array_column($pencairan ?? [], 'jumlah_dicairkan'));
                    echo 'Rp ' . number_format($totalDicairkan, 0, ',', '.');
                    ?>
                </p>
            </div>
            <div class="p-2 bg-green-50 rounded-lg flex-shrink-0">
                <i class="bx bx-money-bill-wave text-green-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Pencairan Bulan Ini</p>
                <p class="text-2xl font-bold text-purple-600">
                    <?= count(array_filter($pencairan ?? [], function($p) { 
                        return date('Y-m', strtotime($p['tanggal_pencairan'])) === date('Y-m'); 
                    })) ?>
                </p>
            </div>
            <div class="p-2 bg-purple-50 rounded-lg flex-shrink-0">
                <i class="bx bx-calendar-alt text-purple-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Rata-rata Pencairan</p>
                <p class="text-2xl font-bold text-orange-600">
                    <?php
                    $avgDicairkan = count($pencairan ?? []) > 0 ? $totalDicairkan / count($pencairan) : 0;
                    echo 'Rp ' . number_format($avgDicairkan, 0, ',', '.');
                    ?>
                </p>
            </div>
            <div class="p-2 bg-orange-50 rounded-lg flex-shrink-0">
                <i class="bx bx-chart-bar text-orange-600 h-6 w-6"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm">
    <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Daftar Pencairan</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola data pencairan kredit</p>
            </div>
            <a href="/pencairan/new" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                <i class="bx bx-plus h-4 w-4"></i>
                <span class="hidden sm:inline">Tambah Pencairan</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mt-4">
            <div class="relative max-w-sm">
                <i class="bx bx-search absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="search-input" placeholder="Cari pencairan..." class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <?php if (empty($pencairan)): ?>
            <div class="px-4 sm:px-6 py-12 text-center">
                <i class="bx bx-file-invoice-dollar mx-auto h-12 w-12 text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data pencairan</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan pencairan baru.</p>
                <div class="mt-6">
                    <a href="/pencairan/new" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        <i class="bx bx-plus h-4 w-4"></i>
                        Tambah Pencairan
                    </a>
                </div>
            </div>
        <?php else: ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Kredit</th>
                        <th class="hidden md:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="hidden sm:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                        <th class="hidden lg:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Bunga</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aktif/Nonaktif</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="pencairan-table-body">
                    <?php foreach ($pencairan as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors pencairan-row" 
                            data-search="<?= strtolower(esc($row['id_kredit'] . ' ' . $row['tanggal_pencairan'] . ' ' . $row['jumlah_dicairkan'] . ' ' . $row['metode_pencairan'])) ?>">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($row['id_pencairan']) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= esc($row['id_kredit']) ?></div>
                                <div class="md:hidden text-xs text-gray-500"><?= date('d/m/Y', strtotime($row['tanggal_pencairan'])) ?></div>
                            </td>
                            <td class="hidden md:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= date('d/m/Y', strtotime($row['tanggal_pencairan'])) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Rp <?= number_format($row['jumlah_dicairkan'], 0, ',', '.') ?></div>
                                <div class="sm:hidden text-xs text-gray-500"><?= esc($row['metode_pencairan']) ?></div>
                            </td>
                            <td class="hidden sm:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= esc($row['metode_pencairan']) ?></td>
                            <td class="hidden lg:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= esc($row['id_bunga']) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                <?php if ($currentUserLevel && Roles::can($currentUserLevel, 'manage_pencairan')): ?>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                               class="sr-only peer"
                                               id="toggle-<?= esc($row['id_pencairan']) ?>"
                                               <?= ($row['status_aktif'] ?? 'Aktif') === 'Aktif' ? 'checked' : '' ?>
                                               onchange="togglePencairanStatus(<?= esc($row['id_pencairan']) ?>, this)">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <?= esc($row['status_aktif'] ?? 'Aktif') === 'Aktif' ? 'Aktif' : 'Tidak Aktif' ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1 sm:gap-2">
                                    <a href="/pencairan/show/<?= esc($row['id_pencairan']) ?>" 
                                       class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-900 text-xs sm:text-sm p-1 sm:p-0">
                                        <i class="bx bx-eye h-4 w-4"></i>
                                        <span class="hidden sm:inline">Lihat</span>
                                    </a>
                                    <a href="/pencairan/edit/<?= esc($row['id_pencairan']) ?>" 
                                       class="inline-flex items-center gap-1 text-yellow-600 hover:text-yellow-900 text-xs sm:text-sm p-1 sm:p-0">
                                        <i class="bx bx-edit h-4 w-4"></i>
                                        <span class="hidden sm:inline">Edit</span>
                                    </a>
                                    <form action="/pencairan/delete/<?= esc($row['id_pencairan']) ?>" method="post" class="inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pencairan ini?');">
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
        const rows = document.querySelectorAll('.pencairan-row');
        
        rows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Toggle pencairan status
    function togglePencairanStatus(id, element) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/pencairan/toggle-status/' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showNotification(data.message, 'success');
                
                // Update the toggle state
                element.checked = data.new_status === 'Aktif';
            } else {
                showNotification(data.message || 'Gagal mengubah status', 'error');
                // Revert toggle state
                element.checked = !element.checked;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat mengubah status', 'error');
            // Revert toggle state
            element.checked = !element.checked;
        });
    }

    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} w-5 h-5 mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
</script>

<?= $this->endSection() ?>
