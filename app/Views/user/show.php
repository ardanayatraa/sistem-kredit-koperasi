<?= $this->extend('layouts/dashboard_template') ?>
<?php
use App\Config\Roles;
$currentUserLevel = session()->get('level');
?>

<?= $this->section('content') ?>
<div class="w-full w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Detail User</h2>
                    <p class="text-sm text-gray-600 mt-1">Informasi lengkap user</p>
                </div>
                <div class="flex gap-2">
                    <?php if ($currentUserLevel && Roles::can($currentUserLevel, 'manage_users')): ?>
                        <a href="/user/edit/<?= esc($user['id_user']) ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                            <i class="bx bx-edit h-4 w-4"></i>
                            Edit
                        </a>
                    <?php endif; ?>
                    <a href="/user" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <i class="bx bx-arrow-left h-4 w-4"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Data Pribadi -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-user text-blue-600 h-5 w-5"></i>
                        Data Pribadi
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">ID User</label>
                        <p class="text-sm text-gray-900 font-medium"><?= esc($user['id_user']) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                        <p class="text-sm text-gray-900 font-semibold"><?= esc($user['nama_lengkap']) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Username</label>
                        <p class="text-sm text-gray-900"><?= esc($user['username']) ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-sm text-gray-900"><?= esc($user['email']) ?></p>
                    </div>

                    <?php if (!empty($user['no_hp'])): ?>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">No. HP</label>
                        <p class="text-sm text-gray-900"><?= esc($user['no_hp']) ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Level</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            <?php 
                            switch($user['level']) {
                                case 'Admin':
                                    echo 'bg-red-100 text-red-800';
                                    break;
                                case 'Manager':
                                    echo 'bg-purple-100 text-purple-800';
                                    break;
                                case 'Staff':
                                    echo 'bg-blue-100 text-blue-800';
                                    break;
                                default:
                                    echo 'bg-gray-100 text-gray-800';
                            }
                            ?>">
                            <?= esc($user['level']) ?>
                        </span>
                    </div>

                </div>
            </div>

            <!-- Metadata -->
            <div class="space-y-4 pt-6 border-t border-gray-200">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-info-circle text-gray-600 h-5 w-5"></i>
                        Informasi Sistem
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Dibuat Pada</label>
                        <p class="text-sm text-gray-900"><?= isset($user['created_at']) ? date('d F Y H:i', strtotime($user['created_at'])) : '-' ?></p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-500">Terakhir Diupdate</label>
                        <p class="text-sm text-gray-900"><?= isset($user['updated_at']) ? date('d F Y H:i', strtotime($user['updated_at'])) : '-' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
