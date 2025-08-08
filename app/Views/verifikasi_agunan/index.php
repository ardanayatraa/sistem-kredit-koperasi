<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Verifikasi Agunan</h1>
                <p class="text-gray-600">Verifikasi dokumen dan keabsahan agunan yang diajukan anggota.</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('agunan') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Kelola Agunan
                </a>
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
                    <option value="verified" <?= (request()->getGet('status_verifikasi') === 'verified') ? 'selected' : '' ?>>Terverifikasi</option>
                    <option value="rejected" <?= (request()->getGet('status_verifikasi') === 'rejected') ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>
            <div>
                <label for="jenis_agunan" class="block text-sm font-medium text-gray-700 mb-2">Jenis Agunan</label>
                <select id="jenis_agunan" name="jenis_agunan" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                    <option value="">Semua Jenis</option>
                    <option value="Rumah" <?= (request()->getGet('jenis_agunan') === 'Rumah') ? 'selected' : '' ?>>Rumah</option>
                    <option value="Tanah" <?= (request()->getGet('jenis_agunan') === 'Tanah') ? 'selected' : '' ?>>Tanah</option>
                    <option value="Kendaraan" <?= (request()->getGet('jenis_agunan') === 'Kendaraan') ? 'selected' : '' ?>>Kendaraan</option>
                    <option value="Emas" <?= (request()->getGet('jenis_agunan') === 'Emas') ? 'selected' : '' ?>>Emas</option>
                </select>
            </div>
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="<?= request()->getGet('tanggal_mulai') ?>" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Agunan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kredit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Verifikasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($agunan)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($agunan as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $no++ ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= esc($item['nama_lengkap'] ?? 'N/A') ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= esc($item['no_anggota'] ?? '') ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= esc($item['jenis_agunan'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp <?= number_format($item['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusKredit = $item['status_kredit'] ?? 'Diajukan';
                                    $statusColor = '';
                                    
                                    switch ($statusKredit) {
                                        case 'Disetujui':
                                            $statusColor = 'green';
                                            break;
                                        case 'Ditolak':
                                            $statusColor = 'red';
                                            break;
                                        case 'Pending':
                                            $statusColor = 'blue';
                                            break;
                                        default:
                                            $statusColor = 'yellow';
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                        <?= $statusKredit ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($item['tanggal_pengajuan'] ?? 'now')) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?= base_url('verifikasi-agunan/detail/' . $item['id_kredit']) ?>" class="text-blue-600 hover:text-blue-900">
                                            Detail
                                        </a>
                                        <?php if ($statusKredit !== 'Disetujui'): ?>
                                            <button onclick="openApprovalModal(<?= $item['id_kredit'] ?>)" class="text-green-600 hover:text-green-900">
                                                Setujui
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?= base_url('verifikasi-agunan/dokumen/' . $item['id_kredit']) ?>" class="text-purple-600 hover:text-purple-900">
                                            Dokumen
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p>Belum ada agunan yang perlu diverifikasi</p>
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

<!-- Modal Persetujuan -->
<div id="approvalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Persetujuan Agunan</h3>
            <form id="approvalForm" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" id="approval_id_kredit" name="id_kredit">
                
                <div class="mb-4">
                    <label for="catatan_appraiser" class="block text-sm font-medium text-gray-700 mb-2">Catatan Appraiser</label>
                    <textarea id="catatan_appraiser" name="catatan_appraiser" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500" placeholder="Masukkan catatan hasil verifikasi agunan..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApprovalModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md">
                        Batal
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                        Setujui Agunan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openApprovalModal(idKredit) {
    document.getElementById('approval_id_kredit').value = idKredit;
    document.getElementById('approvalForm').action = '<?= base_url('verifikasi-agunan/approve/') ?>' + idKredit;
    document.getElementById('approvalModal').classList.remove('hidden');
}

function closeApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
    document.getElementById('approvalForm').reset();
}
</script>

<?= $this->endSection() ?>