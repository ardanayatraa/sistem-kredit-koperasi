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
                <p class="text-sm font-medium text-gray-600 truncate">Total Kredit</p>
                <p class="text-2xl font-bold text-blue-600"><?= count($kredit ?? []) ?></p>
            </div>
            <div class="p-2 bg-blue-50 rounded-lg flex-shrink-0">
                <i class="bx bx-file-invoice-dollar text-blue-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Kredit Disetujui</p>
                <p class="text-2xl font-bold text-green-600">
                    <?= count(array_filter($kredit ?? [], function($k) { 
                        return strtolower($k['status_kredit']) === 'disetujui'; 
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
                <p class="text-sm font-medium text-gray-600 truncate">Kredit Ditolak</p>
                <p class="text-2xl font-bold text-red-600">
                    <?= count(array_filter($kredit ?? [], function($k) { 
                        return strtolower($k['status_kredit']) === 'ditolak'; 
                    })) ?>
                </p>
            </div>
            <div class="p-2 bg-red-50 rounded-lg flex-shrink-0">
                <i class="bx bx-times-circle text-red-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Kredit Pending</p>
                <p class="text-2xl font-bold text-yellow-600">
                    <?= count(array_filter($kredit ?? [], function($k) { 
                        return strtolower($k['status_kredit']) === 'pending'; 
                    })) ?>
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
                <h2 class="text-xl font-semibold text-gray-900">Daftar Kredit</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola data pengajuan kredit anggota</p>
            </div>
            <a href="/kredit/new" class="inline-flex items-center justify-center gap-2 rounded-lg btn-primary px-4 py-2 text-sm font-medium transition-colors">
                <i class="bx bx-plus h-4 w-4"></i>
                <span class="hidden sm:inline">Tambah Kredit</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mt-4">
            <div class="relative max-w-sm">
                <i class="bx bx-search absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="search-input" placeholder="Cari kredit..." class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <?php if (empty($kredit)): ?>
            <div class="px-4 sm:px-6 py-12 text-center">
                <i class="bx bx-file-invoice-dollar mx-auto h-12 w-12 text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data kredit</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan pengajuan kredit baru.</p>
                <div class="mt-6">
                    <a href="/kredit/new" class="inline-flex items-center gap-2 rounded-lg btn-primary px-4 py-2 text-sm font-medium">
                        <i class="bx bx-plus h-4 w-4"></i>
                        Tambah Kredit
                    </a>
                </div>
            </div>
        <?php else: ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Anggota</th>
                        <th class="hidden md:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="hidden sm:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jangka Waktu</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aktif/Nonaktif</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Verifikasi</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="kredit-table-body">
                    <?php foreach ($kredit as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors kredit-row" 
                            data-search="<?= strtolower(esc($row['id_anggota'] . ' ' . $row['tanggal_pengajuan'] . ' ' . $row['jumlah_pengajuan'] . ' ' . $row['status_kredit'])) ?>">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($row['id_kredit']) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= esc($row['id_anggota']) ?></div>
                                <div class="md:hidden text-xs text-gray-500"><?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])) ?></div>
                            </td>
                            <td class="hidden md:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Rp <?= number_format($row['jumlah_pengajuan'], 0, ',', '.') ?></div>
                                <div class="sm:hidden text-xs text-gray-500"><?= $row['jangka_waktu'] ?> bulan</div>
                            </td>
                            <td class="hidden sm:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= $row['jangka_waktu'] ?> bulan</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusColors = [
                                    'disetujui' => 'bg-green-100 text-green-800',
                                    'ditolak' => 'bg-red-100 text-red-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'dalam proses' => 'bg-blue-100 text-blue-800',
                                    'aktif' => 'bg-green-100 text-green-800',
                                    'tidak aktif' => 'bg-red-100 text-red-800',
                                ];
                                $statusClass = $statusColors[strtolower($row['status_kredit'])] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $statusClass ?> status-badge">
                                    <?= esc($row['status_kredit']) ?>
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                <?php if ($currentUserLevel && Roles::can($currentUserLevel, 'manage_kredit')): ?>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                               class="sr-only peer"
                                               id="toggle-<?= esc($row['id_kredit']) ?>"
                                               <?= ($row['status_aktif'] ?? 'Aktif') === 'Aktif' ? 'checked' : '' ?>
                                               onchange="toggleKreditStatus(<?= esc($row['id_kredit']) ?>, this)">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <?= esc($row['status_aktif'] ?? 'Aktif') === 'Aktif' ? 'Aktif' : 'Tidak Aktif' ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                <?php if ($currentUserLevel && $currentUserLevel === 'Appraiser'): ?>
                                    <?php
                                    // Check if already verified
                                    $isVerified = !empty($row['catatan_appraiser']) && strpos($row['catatan_appraiser'], 'VERIFIKASI AGUNAN') !== false;
                                    $kreditId = esc($row['id_kredit']);
                                    $anggotaId = esc($row['id_anggota']);
                                    ?>
                                    <?php if (!$isVerified): ?>
                                        <button
                                            onclick="verifyAgunan(<?= $kreditId ?>, '<?= $anggotaId ?>')"
                                            class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-lg btn-primary transition-colors">
                                            <i class="bx bx-check h-3 w-3"></i>
                                            Verifikasi
                                        </button>
                                    <?php else: ?>
                                        <button
                                            disabled
                                            class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-lg bg-gray-400 text-white cursor-not-allowed">
                                            <i class="bx bx-check-double h-3 w-3"></i>
                                            Terverifikasi
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <?php
                                        $isVerified = !empty($row['catatan_appraiser']) && strpos($row['catatan_appraiser'], 'VERIFIKASI AGUNAN') !== false;
                                        echo $isVerified ? 'Terverifikasi' : 'Belum Diverifikasi';
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1 sm:gap-2">
                                    <a href="/kredit/show/<?= esc($row['id_kredit']) ?>" 
                                       class="inline-flex items-center gap-1 text-primary hover:text-primary-dark text-xs sm:text-sm p-1 sm:p-0">
                                        <i class="bx bx-eye h-4 w-4"></i>
                                        <span class="hidden sm:inline">Lihat</span>
                                    </a>
                                    <a href="/kredit/edit/<?= esc($row['id_kredit']) ?>" 
                                       class="inline-flex items-center gap-1 text-yellow-600 hover:text-yellow-700 text-xs sm:text-sm p-1 sm:p-0">
                                        <i class="bx bx-edit h-4 w-4"></i>
                                        <span class="hidden sm:inline">Edit</span>
                                    </a>
                                    <form action="/kredit/delete/<?= esc($row['id_kredit']) ?>" method="post" class="inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kredit ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-700 text-xs sm:text-sm p-1 sm:p-0">
                                            <i class="bx bx-trash h-4 w-4"></i>
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
    // Server-side data passed from PHP
    const anggotaDataMap = <?php echo json_encode($anggotaData ?? []); ?>;
    const currentUserLevel = '<?php echo $currentUserLevel; ?>';
    
    // Search functionality
    document.getElementById('search-input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.kredit-row');
        
        rows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Toggle kredit status
    function toggleKreditStatus(id, element) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/kredit/toggle-status/' + id, {
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

    // View agunan documents using server-side data
    function viewAgunanDocuments(idKredit, idAnggota) {
        // Get anggota data from server-side passed data
        const anggotaData = anggotaDataMap[idAnggota];
        
        if (!anggotaData) {
            showNotification('Data anggota tidak ditemukan', 'error');
            return;
        }

        // Create modal HTML with agunan documents
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Dokumen Agunan</h3>
                            <p class="text-sm text-gray-600 mt-1">Lihat dokumen agunan kredit</p>
                        </div>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded hover:bg-gray-100 transition-colors">
                            <i class="bx bx-times w-5 h-5"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Kredit Application Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h4 class="font-medium text-blue-900 mb-2">Informasi Pengajuan Kredit</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-blue-700">ID Kredit:</span>
                                <span class="font-medium text-blue-900 ml-2">${idKredit}</span>
                            </div>
                            <div>
                                <span class="text-blue-700">ID Anggota:</span>
                                <span class="font-medium text-blue-900 ml-2">${idAnggota}</span>
                            </div>
                            <div>
                                <span class="text-blue-700">Jenis Agunan:</span>
                                <span class="font-medium text-blue-900 ml-2">${anggotaData.jenis_agunan || '-'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Agunan Documents -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="bx bx-file-invoice-dollar text-blue-600 h-5 w-5"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Dokumen Agunan</p>
                                    <p class="text-xs text-gray-500">Dokumen jaminan kredit</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <?php if (!empty($anggotaData['dokumen_agunan'])): ?>
                                    <a href="/kredit/view-document/<?= $anggotaData['dokumen_agunan'] ?>" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat Dokumen
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        Belum diupload
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="bx bx-image text-green-600 h-5 w-5"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Foto Agunan</p>
                                    <p class="text-xs text-gray-500">Foto jaminan kredit</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <?php if (!empty($anggotaData['foto_agunan'])): ?>
                                    <a href="/kredit/view-document/<?= $anggotaData['foto_agunan'] ?>" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                        <i class="bx bx-external-link-alt w-3 h-3 mr-1"></i>
                                        Lihat Foto
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                        Belum diupload
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                        <button onclick="closeModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    
    // Close modal function
    function closeModal() {
        const modal = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50');
        if (modal) {
            modal.remove();
        }
    }

    // Tab switching functionality
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active state from all tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-blue-500', 'text-blue-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-content').classList.remove('hidden');
        
        // Set active state for selected tab button
        const activeButton = document.getElementById('tab-' + tabName);
        activeButton.classList.add('active', 'border-blue-500', 'text-blue-600');
        activeButton.classList.remove('border-transparent', 'text-gray-500');
    }

    // Verify agunan - opens verification modal with agunan data and notes input
    function verifyAgunan(idKredit, idAnggota) {
        // Get anggota data from server-side passed data
        const anggotaData = anggotaDataMap[idAnggota];
        
        if (!anggotaData) {
            showNotification('Data anggota tidak ditemukan', 'error');
            return;
        }

        // Create verification modal HTML with agunan data and notes input
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Verifikasi Agunan</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="bx bx-times w-6 h-6"></i>
                        </button>
                    </div>
                    
                    <!-- Credit Application Information -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-3">Informasi Pengajuan Kredit</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="font-medium">ID Kredit:</span> ${idKredit}</div>
                            <div><span class="font-medium">ID Anggota:</span> ${anggotaData.id_anggota}</div>
                            <div><span class="font-medium">Jumlah Pengajuan:</span> Rp ${anggotaData.kredit_jumlah ? anggotaData.kredit_jumlah.toLocaleString('id-ID') : '-'}</div>
                            <div><span class="font-medium">Jangka Waktu:</span> ${anggotaData.kredit_jangka_waktu ? anggotaData.kredit_jangka_waktu + ' bulan' : '-'}</div>
                            <div><span class="font-medium">Tujuan Kredit:</span> ${anggotaData.tujuan_kredit || '-'}</div>
                            <div><span class="font-medium">Status Kredit:</span> ${anggotaData.kredit_status || '-'}</div>
                        </div>
                    </div>
                    
                    <!-- Agunan Data -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Data Agunan</h4>
                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div><span class="font-medium">Jenis Agunan:</span> ${anggotaData.jenis_agunan || '-'}</div>
                                <div><span class="font-medium">Nilai Taksiran:</span> ${anggotaData.nilai_taksiran_agunan ? 'Rp ' + parseInt(anggotaData.nilai_taksiran_agunan).toLocaleString('id-ID') : '-'}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Verification Notes -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Catatan Verifikasi</h4>
                        <textarea
                            id="verification-notes"
                            placeholder="Masukkan catatan verifikasi agunan..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                            rows="4"
                            maxlength="500"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button onclick="closeModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button onclick="processVerification(${idKredit}, this)" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="bx bx-check w-4 h-4 mr-2 inline"></i>
                            Verifikasi Agunan
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    // Process verification from modal
    function processVerification(idKredit, button) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const notesTextarea = document.getElementById('verification-notes');
        const additionalNotes = notesTextarea ? notesTextarea.value.trim() : '';
        
        // Show confirmation dialog
        if (confirm('Apakah Anda yakin ingin melakukan verifikasi agunan? Setelah diverifikasi, catatan Anda akan disimpan dan status kredit akan diperbarui.')) {
            // Show loading state
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memverifikasi...
            `;
            
            // Build verification notes
            let verificationNotes = `=== VERIFIKASI AGUNAN ===\nTanggal Verifikasi: ${new Date().toLocaleString('id-ID')}\nDiverifikasi oleh: Appraiser\nStatus: Agunan telah diverifikasi`;
            
            // Add additional notes if provided
            if (additionalNotes) {
                verificationNotes += `\n\n=== CATATAN TAMBAHAN ===\n${additionalNotes}`;
            }
            
            // Send verification request
            fetch('/kredit/verify-agunan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id_kredit: idKredit,
                    catatan_appraiser: verificationNotes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success notification
                    showNotification(data.message, 'success');
                    
                    // Update button state
                    button.innerHTML = `
                        <i class="bx bx-check-double w-4 h-4 mr-2"></i>
                        Terverifikasi
                    `;
                    button.disabled = true;
                    button.classList.remove('bg-green-600', 'hover:bg-green-700');
                    button.classList.add('bg-gray-400', 'cursor-not-allowed');
                } else {
                    showNotification(data.message || 'Gagal melakukan verifikasi', 'error');
                    // Reset button state
                    button.disabled = false;
                    button.innerHTML = `
                        <i class="bx bx-check w-4 h-4 mr-2"></i>
                        Verifikasi Agunan
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat melakukan verifikasi', 'error');
                // Reset button state
                button.disabled = false;
                button.innerHTML = `
                    <i class="bx bx-check w-4 h-4 mr-2"></i>
                    Verifikasi Agunan
                `;
            });
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
