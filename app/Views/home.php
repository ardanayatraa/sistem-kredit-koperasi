<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<!-- Role-Based Header -->
<div class="bg-gray-800 p-6 rounded-lg shadow-xl mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2"><?= esc($config['title']) ?></h1>
            <p class="text-gray-300"><?= esc($config['description']) ?></p>
        </div>
        <div class="text-right">
            <p class="text-lg font-semibold text-white">Selamat datang,</p>
            <p class="text-xl font-bold text-white"><?= esc($namaLengkap) ?></p>
            <p class="text-gray-300 text-sm"><?= esc($userLevel) ?></p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <?php foreach ($config['stats'] as $index => $stat): ?>
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-600 truncate"><?= esc($stat['title']) ?></p>
                    <p class="text-2xl font-bold text-<?= $stat['color'] ?>-600"><?= $stat['value'] ?? '0' ?></p>
                </div>
                <div class="p-2 bg-<?= $stat['color'] ?>-50 rounded-lg flex-shrink-0">
                    <i class="<?= $stat['icon'] ?> text-<?= $stat['color'] ?>-600 h-6 w-6"></i>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php foreach ($config['quick_actions'] as $action): ?>
            <a href="<?= $action['url'] ?>"
               class="group <?= $accent ?> <?= $accentHover ?> text-white font-semibold py-3 px-4 rounded-lg text-center transition-all duration-200 transform hover:scale-105 hover:shadow-lg">
                <div class="flex flex-col items-center">
                    <i class="<?= $action['icon'] ?> h-6 w-6 mb-2 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm"><?= esc($action['title']) ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
