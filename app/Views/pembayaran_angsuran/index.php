<?= $this->extend('layouts/dashboard_template') ?>
<?php
use App\Config\Roles;
$currentUserLevel = session()->get('level');
?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Kelola Pembayaran Angsuran</h1>
                <p class="text-gray-600">Verifikasi dan kelola pembayaran angsuran dari anggota.</p>
            </div>
            <div class="flex space-x-3">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="bx bx-money-bill-wave text-blue-600 h-6 w-6 mr-3"></i>
                            <div>
                                <p class="text-xs text-blue-600">Total</p>
                                <p class="text-lg font-bold text-blue-900"><?= $stats['total_pembayaran'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="<?= current_url() ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status_verifikasi" class="block text-sm font-medium text-gray-700 mb-2">Status Verifikasi</label>
                <select id="status_verifikasi" name="status_verifikasi" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= (request()->getGet('status_verifikasi') === 'pending') ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                    <option value="approved" <?= (request()->getGet('status_verifikasi') === 'approved') ? 'selected' : '' ?>>Sudah Disetujui</option>
                    <option value="rejected" <?= (request()->getGet('status_verifikasi') === 'rejected') ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Anggota</label>
                <input type="text" id="search" name="search" value="<?= request()->getGet('search') ?>" placeholder="Nama atau nomor anggota..." class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bayar</label>
                <input type="date" id="tanggal" name="tanggal" value="<?= request()->getGet('tanggal') ?>" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex items-end space-x-3">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    Filter
                </button>
                <a href="<?= current_url() ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
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
                        <?php $no = 1; ?>
                        <?php foreach ($pembayaran_angsuran as $pembayaran): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $no++ ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= esc($pembayaran['nama_lengkap'] ?? 'N/A') ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= esc($pembayaran['no_anggota'] ?? '') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-medium"><?= esc($pembayaran['angsuran_ke']) ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($pembayaran['tanggal_bayar'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusVerifikasi = $pembayaran['status_verifikasi'] ?? 'pending';
                                    $statusDisplay = match($statusVerifikasi) {
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => 'Pending'
                                    };
                                    $statusColor = match($statusVerifikasi) {
                                        'approved' => 'green',
                                        'rejected' => 'red',
                                        default => 'yellow'
                                    };
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                        <i class="bx <?= match($statusVerifikasi) { 'approved' => 'bx-check-circle', 'rejected' => 'bx-x-circle', default => 'bx-time-five' } ?> mr-1"></i>
                                        <?= esc($statusDisplay) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- Bukti Pembayaran -->
                                        <?php if (!empty($pembayaran['bukti_pembayaran'])): ?>
                                            <a href="/writable/uploads/pembayaran_angsuran/<?= esc($pembayaran['bukti_pembayaran']) ?>"
                                               target="_blank"
                                               class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                                                <i class="bx bx-file-blank mr-1"></i>
                                                Bukti
                                            </a>
                                        <?php endif; ?>
                                        
                                        <!-- Verifikasi Actions untuk Bendahara/Ketua -->
                                        <?php if (($pembayaran['status_verifikasi'] ?? 'pending') === 'pending' && $currentUserLevel && in_array($currentUserLevel, ['Bendahara', 'Ketua'])): ?>
                                            <form action="/pembayaran-angsuran/verifikasi-pembayaran/<?= esc($pembayaran['id_pembayaran']) ?>" method="POST" style="display:inline;">
                                                <?= csrf_field() ?>
                                                <button type="submit"
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                                                <i class="bx bx-check-circle mr-1"></i>
                                                Setujui
                                            </button>
                                            <form action="/pembayaran-angsuran/tolak-pembayaran/<?= esc($pembayaran['id_pembayaran']) ?>" method="POST" style="display:inline;">
                                                <?= csrf_field() ?>
                                                <button type="button" onclick="openTolakModal(<?= esc($pembayaran['id_pembayaran']) ?>)"
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
                                                <i class="bx bx-x-circle mr-1"></i>
                                                Tolak
                                            </button>
                                        <?php else: ?>
                                            <!-- Status untuk yang sudah diverifikasi -->
                                            <?php if ($pembayaran['status_verifikasi'] === 'approved'): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="bx bx-check-double mr-1"></i>
                                                    Disetujui
                                                </span>
                                            <?php elseif ($pembayaran['status_verifikasi'] === 'rejected'): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="bx bx-x mr-1"></i>
                                                    Ditolak
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <!-- Detail Button -->
                                        <a href="/pembayaran-angsuran/show/<?= esc($pembayaran['id_pembayaran']) ?>"
                                           class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors">
                                            <i class="bx bx-show mr-1"></i>
                                            Detail
                                        </a>
                                    </div>
                                    
                                    <!-- Hidden form for rejection -->
                                    <?php foreach ($pembayaran_angsuran as $pembayaran): ?>
                                        <?php if (($pembayaran['status_verifikasi'] ?? 'pending') === 'pending'): ?>
                                            <form id="tolak-form-<?= $pembayaran['id_pembayaran'] ?>" action="/pembayaran-angsuran/tolak-pembayaran/<?= $pembayaran['id_pembayaran'] ?>" method="POST" style="display:none;">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="alasan" id="alasan-tolak-input">
                                            </form>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="bx bx-money-bill-wave h-12 w-12 text-gray-400 mb-4"></i>
                                    <p>Belum ada data pembayaran angsuran</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (isset($pager)): ?>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-lg shadow">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>


    // Modal functions
    let currentPaymentId = null;
    
    function openTolakModal(id) {
        currentPaymentId = id;
        document.getElementById('tolak-modal').classList.remove('hidden');
    }
    
    function closeTolakModal() {
        document.getElementById('tolak-modal').classList.add('hidden');
        document.getElementById('alasan-tolak').value = '';
    }
    
    function submitTolakan() {
        if (currentPaymentId) {
            // Set alasan in hidden form
            document.getElementById('alasan-tolak-input').value =
                document.getElementById('alasan-tolak-text').value;
            
            // Submit form
            document.getElementById('tolak-form-' + currentPaymentId).submit();
        }
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

<!-- Tolak Modal -->
<div id="tolak-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Alasan Penolakan</h3>
            <div class="mt-2 px-7 py-3">
                <textarea id="alasan-tolak-text" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan alasan penolakan..."></textarea>
            </div>
            <div class="flex justify-center gap-3 px-4 py-3">
                <button onclick="closeTolakModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
                <button type="button" onclick="submitTolakan()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
