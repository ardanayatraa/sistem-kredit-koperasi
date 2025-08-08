<?= $this->extend('layouts/dashboard_template') ?>
<?php
use App\Config\Roles;
$currentUserLevel = session()->get('level');
?>

<?= $this->section('content') ?>
<div class="w-full space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pembayaran Angsuran</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola data pembayaran angsuran</p>
        </div>
        <a href="/pembayaran-angsuran/new" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
            <i class="bx bx-plus h-4 w-4"></i>
            Tambah Pembayaran
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['total_pembayaran'] ?? 0 ?></p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="bx bx-money-bill-wave h-6 w-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pembayaran Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $stats['pembayaran_hari_ini'] ?? 0 ?></p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="bx bx-calendar-day h-6 w-6 text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Nominal</p>
                    <p class="text-2xl font-bold text-gray-900">Rp <?= number_format($stats['total_nominal'] ?? 0, 0, ',', '.') ?></p>
                </div>
                <div class="h-12 w-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="bx bx-chart-line h-6 w-6 text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Rata-rata Pembayaran</p>
                    <p class="text-2xl font-bold text-gray-900">Rp <?= number_format($stats['rata_rata'] ?? 0, 0, ',', '.') ?></p>
                </div>
                <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="bx bx-calculator h-6 w-6 text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       placeholder="Cari berdasarkan ID angsuran, metode pembayaran..." 
                       value="<?= esc($search ?? '') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    Cari
                </button>
                <a href="/pembayaran-angsuran" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angsuran Ke</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Verifikasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($pembayaran_angsuran)): ?>
                        <?php foreach ($pembayaran_angsuran as $pembayaran): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= esc($pembayaran['id_pembayaran']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= esc($pembayaran['nama_lengkap'] ?? 'N/A') ?><br>
                                <small class="text-gray-500"><?= esc($pembayaran['no_anggota'] ?? '') ?></small>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="font-medium"><?= esc($pembayaran['angsuran_ke']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= date('d/m/Y', strtotime($pembayaran['tanggal_bayar'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php
                                $statusVerifikasi = $pembayaran['status_verifikasi'] ?? 'Menunggu';
                                $badgeClass = match($statusVerifikasi) {
                                    'Terverifikasi' => 'bg-green-100 text-green-800',
                                    'Ditolak' => 'bg-red-100 text-red-800',
                                    default => 'bg-yellow-100 text-yellow-800'
                                };
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badgeClass ?>">
                                    <?= esc($statusVerifikasi) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Bukti Pembayaran -->
                                    <?php if (!empty($pembayaran['bukti_pembayaran'])): ?>
                                        <a href="/uploads/bukti_pembayaran/<?= esc($pembayaran['bukti_pembayaran']) ?>"
                                           target="_blank"
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="bx bx-image h-3 w-3 mr-1"></i>
                                            Bukti
                                        </a>
                                    <?php endif; ?>
                                    
                                    <!-- Verifikasi Actions -->
                                    <?php if (($pembayaran['status_verifikasi'] ?? 'Menunggu') === 'Menunggu' && $currentUserLevel && Roles::can($currentUserLevel, 'manage_pembayaran_angsuran')): ?>
                                        <button onclick="verifikasiPembayaran(<?= esc($pembayaran['id_pembayaran']) ?>)"
                                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="bx bx-check h-3 w-3 mr-1"></i>
                                            Terima
                                        </button>
                                        <button onclick="tolakPembayaran(<?= esc($pembayaran['id_pembayaran']) ?>)"
                                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <i class="bx bx-x h-3 w-3 mr-1"></i>
                                            Tolak
                                        </button>
                                    <?php endif; ?>
                                    
                                    <!-- Detail -->
                                    <a href="/pembayaran-angsuran/show/<?= esc($pembayaran['id_pembayaran']) ?>"
                                       class="text-blue-600 hover:text-blue-900">Detail</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="bx bx-file-invoice h-12 w-12 text-gray-400"></i>
                                    <p>Belum ada data pembayaran angsuran</p>
                                    <a href="/pembayaran-angsuran/new" class="text-blue-600 hover:text-blue-900 font-medium">Tambah pembayaran pertama</a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager)): ?>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?= $pager->hasPrevious() ? '<a href="' . $pager->getPrevious() . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>' : '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100">Previous</span>' ?>
                    <?= $pager->hasNext() ? '<a href="' . $pager->getNext() . '" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>' : '<span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100">Next</span>' ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium"><?= $pager->getCurrentPageNumber() ?></span> of <span class="font-medium"><?= $pager->getPageCount() ?></span> pages
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <?= $pager->links() ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Verifikasi pembayaran
    function verifikasiPembayaran(id) {
        if (confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/pembayaran-angsuran/verifikasi/' + id, {
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
                    showNotification(data.message, 'success');
                    // Refresh page after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showNotification(data.message || 'Gagal memverifikasi pembayaran', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat memverifikasi pembayaran', 'error');
            });
        }
    }

    // Tolak pembayaran
    function tolakPembayaran(id) {
        const alasan = prompt('Masukkan alasan penolakan:', '');
        
        if (alasan !== null && alasan.trim() !== '') {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/pembayaran-angsuran/tolak/' + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ alasan: alasan })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Refresh page after 2 seconds
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showNotification(data.message || 'Gagal menolak pembayaran', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat menolak pembayaran', 'error');
            });
        }
    }

    // Toggle pembayaran angsuran status
    function togglePembayaranAngsuranStatus(id, element) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/pembayaran-angsuran/toggle-status/' + id, {
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
                <i class="bx ${type === 'success' ? 'bx-check-circle' : type === 'error' ? 'bx-times-circle' : 'bx-info-circle'} h-5 w-5 mr-2"></i>
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
