<?= $this->extend('layouts/dashboard_template') ?>
<?php
use App\Config\Roles;
$currentUserLevel = session()->get('level');
?>

<?= $this->section('content') ?>
<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6 mt-4">
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Total Users</p>
                <p class="text-2xl font-bold text-blue-600"><?= count($users ?? []) ?></p>
            </div>
            <div class="p-2 bg-blue-50 rounded-lg flex-shrink-0">
                <i class="bx bx-users text-blue-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">User Aktif</p>
                <p class="text-2xl font-bold text-green-600">
                    <?= count(array_filter($users ?? [], function($user) { return ($user['status'] ?? 'Aktif') === 'Aktif'; })) ?>
                </p>
            </div>
            <div class="p-2 bg-green-50 rounded-lg flex-shrink-0">
                <i class="bx bx-user-check text-green-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Admins</p>
                <p class="text-2xl font-bold text-purple-600">
                    <?= count(array_filter($users ?? [], function($user) { return strtolower($user['level'] ?? '') === 'admin'; })) ?>
                </p>
            </div>
            <div class="p-2 bg-purple-50 rounded-lg flex-shrink-0">
                <i class="bx bx-user-shield text-purple-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Staff</p>
                <p class="text-2xl font-bold text-orange-600">
                    <?= count(array_filter($users ?? [], function($user) { return strtolower($user['level'] ?? '') === 'staff'; })) ?>
                </p>
            </div>
            <div class="p-2 bg-orange-50 rounded-lg flex-shrink-0">
                <i class="bx bx-user-tie text-orange-600 h-6 w-6"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-600 truncate">Regular Users</p>
                <p class="text-2xl font-bold text-gray-600">
                    <?= count(array_filter($users ?? [], function($user) { return strtolower($user['level'] ?? '') === 'user'; })) ?>
                </p>
            </div>
            <div class="p-2 bg-gray-50 rounded-lg flex-shrink-0">
                <i class="bx bx-user text-gray-600 h-6 w-6"></i>
            </div>
        </div>
    </div>
</div>

<!-- User Table Card -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm">
    <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-900">Daftar User</h2>
            <?php if ($currentUserLevel && Roles::can($currentUserLevel, 'manage_users')): ?>
                <a href="/user/new" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <i class="bx bx-plus h-4 w-4"></i>
                    <span class="hidden sm:inline">Tambah User</span>
                    <span class="sm:hidden">Tambah</span>
                </a>
            <?php endif; ?>
        </div>

        <!-- Search Bar -->
        <div class="mt-4">
            <div class="relative max-w-sm">
                <i class="bx bx-search absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="search-input" placeholder="Cari user..." class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <?php if (empty($users)): ?>
            <div class="px-4 sm:px-6 py-12 text-center">
                <i class="bx bx-user-slash mx-auto h-12 w-12 text-gray-400"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data user</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan user baru.</p>
                <div class="mt-6">
                    <a href="/user/new" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        <i class="bx bx-plus h-4 w-4"></i>
                        Tambah User
                    </a>
                </div>
            </div>
        <?php else: ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="hidden md:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                        <th class="hidden sm:table-cell px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="user-table-body">
                    <?php foreach ($users as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors user-row" 
                            data-search="<?= strtolower(esc($row['username'] . ' ' . $row['email'] . ' ' . $row['nama_lengkap'])) ?>">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($row['id_user']) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono text-gray-900 truncate max-w-[100px] sm:max-w-none"><?= esc($row['username']) ?></div>
                                <div class="md:hidden text-xs text-gray-500 truncate max-w-[100px]"><?= esc($row['email']) ?></div>
                            </td>
                            <td class="hidden md:table-cell px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-600 max-w-[200px] truncate"><?= esc($row['email']) ?></td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 truncate max-w-[120px] sm:max-w-[200px]"><?= esc($row['nama_lengkap']) ?></div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <?php
                                $levelColors = [
                                    'admin' => 'bg-red-100 text-red-800',
                                    'manager' => 'bg-blue-100 text-blue-800',
                                    'staff' => 'bg-yellow-100 text-yellow-800',
                                    'user' => 'bg-gray-100 text-gray-800'
                                ];
                                $levelClass = $levelColors[strtolower($row['level'])] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $levelClass ?>">
                                    <?= esc($row['level']) ?>
                                </span>
                            </td>
                            <td class="hidden sm:table-cell px-4 sm:px-6 py-4 whitespace-nowrap">
                                <?php $status = $row['status'] ?? 'Aktif'; ?>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <?= $status ?>
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-1 sm:gap-2">
                                    <a href="/user/show/<?= esc($row['id_user']) ?>" 
                                       class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-900 text-xs sm:text-sm p-1 sm:p-0">
                                        <i class="bx bx-eye h-4 w-4"></i>
                                        <span class="hidden sm:inline">Lihat</span>
                                    </a>
                                    <?php if ($currentUserLevel && Roles::can($currentUserLevel, 'manage_users')): ?>
                                        <a href="/user/edit/<?= esc($row['id_user']) ?>"
                                           class="inline-flex items-center gap-1 text-yellow-600 hover:text-yellow-900 text-xs sm:text-sm p-1 sm:p-0">
                                           <i class="bx bx-edit h-4 w-4"></i>
                                           <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        <form action="/user/delete/<?= esc($row['id_user']) ?>" method="post" class="inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="inline-flex items-center gap-1 text-red-600 hover:text-red-900 text-xs sm:text-sm p-1 sm:p-0">
                                                <i class="bx bx-trash-alt h-4 w-4"></i>
                                                <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>
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
        const rows = document.querySelectorAll('.user-row');
        
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
