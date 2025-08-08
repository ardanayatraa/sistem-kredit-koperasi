<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= $headerTitle ?? 'Riwayat Kredit' ?></h1>
                <p class="text-gray-600">Lihat riwayat pengajuan dan status kredit Anda.</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('simulasi-bunga') ?>" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Simulasi Bunga
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="<?= current_url() ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status_kredit" class="block text-sm font-medium text-gray-700 mb-2">Status Kredit</label>
                <select id="status_kredit" name="status_kredit" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                    <option value="">Semua Status</option>
                    <option value="Diajukan" <?= (request()->getGet('status_kredit') === 'Diajukan') ? 'selected' : '' ?>>Diajukan</option>
                    <option value="Disetujui" <?= (request()->getGet('status_kredit') === 'Disetujui') ? 'selected' : '' ?>>Disetujui</option>
                    <option value="Ditolak" <?= (request()->getGet('status_kredit') === 'Ditolak') ? 'selected' : '' ?>>Ditolak</option>
                    <option value="Aktif" <?= (request()->getGet('status_kredit') === 'Aktif') ? 'selected' : '' ?>>Aktif</option>
                    <option value="Lunas" <?= (request()->getGet('status_kredit') === 'Lunas') ? 'selected' : '' ?>>Lunas</option>
                </select>
            </div>
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select id="tahun" name="tahun" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                    <option value="">Semua Tahun</option>
                    <?php for ($year = date('Y'); $year >= (date('Y') - 5); $year--): ?>
                        <option value="<?= $year ?>" <?= (request()->getGet('tahun') == $year) ? 'selected' : '' ?>><?= $year ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label for="jumlah_min" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Minimum</label>
                <input type="number" id="jumlah_min" name="jumlah_min" value="<?= request()->getGet('jumlah_min') ?>" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="0">
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

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pengajuan</p>
                    <p class="text-2xl font-semibold text-blue-600"><?= count($riwayat ?? []) ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Disetujui</p>
                    <p class="text-2xl font-semibold text-green-600">
                        <?= count(array_filter($riwayat ?? [], function($item) { 
                            return in_array($item['status_kredit'], ['Disetujui', 'Aktif', 'Lunas']); 
                        })) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aktif</p>
                    <p class="text-2xl font-semibold text-yellow-600">
                        <?= count(array_filter($riwayat ?? [], function($item) { 
                            return $item['status_kredit'] === 'Aktif'; 
                        })) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-gray-100 rounded-lg">
                    <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Kredit</p>
                    <p class="text-2xl font-semibold text-gray-600">
                        Rp <?= number_format(array_sum(array_column($riwayat ?? [], 'jumlah_pengajuan')), 0, ',', '.') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kredit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jangka Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($riwayat)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($riwayat as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $no++ ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp <?= number_format($item['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $item['jangka_waktu'] ?? '0' ?> bulan
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= esc($item['tujuan_kredit'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $status = $item['status_kredit'] ?? 'Pending';
                                    $statusColor = '';
                                    switch ($status) {
                                        case 'Disetujui':
                                        case 'Aktif':
                                            $statusColor = 'green';
                                            break;
                                        case 'Lunas':
                                            $statusColor = 'blue';
                                            break;
                                        case 'Ditolak':
                                            $statusColor = 'red';
                                            break;
                                        case 'Diajukan':
                                            $statusColor = 'yellow';
                                            break;
                                        default:
                                            $statusColor = 'gray';
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                        <?= esc($status) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($item['tanggal_pengajuan'] ?? 'now')) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="<?= base_url('riwayat-kredit/' . $item['id_kredit']) ?>" class="text-indigo-600 hover:text-indigo-900">
                                        Detail
                                    </a>
                                    <?php if ($item['status_kredit'] === 'Aktif'): ?>
                                        <a href="<?= base_url('riwayat-pembayaran?kredit=' . $item['id_kredit']) ?>" class="text-green-600 hover:text-green-900">
                                            Pembayaran
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('riwayat-kredit/print/' . $item['id_kredit']) ?>" class="text-purple-600 hover:text-purple-900" target="_blank">
                                        Print
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    <p>Belum ada riwayat kredit</p>
                                    <a href="<?= base_url('kredit/new') ?>" class="mt-2 text-indigo-600 hover:text-indigo-900">
                                        Ajukan kredit pertama Anda →
                                    </a>
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

    <!-- Help Section -->
    <div class="bg-blue-50 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Informasi Status Kredit</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Diajukan:</strong> Kredit sedang dalam proses review</li>
                        <li><strong>Disetujui:</strong> Kredit telah disetujui dan menunggu pencairan</li>
                        <li><strong>Aktif:</strong> Kredit sedang berjalan, pembayaran angsuran berlangsung</li>
                        <li><strong>Lunas:</strong> Kredit telah diselesaikan dengan sempurna</li>
                        <li><strong>Ditolak:</strong> Pengajuan kredit tidak dapat diproses</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>