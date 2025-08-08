<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Kelola Agunan</h1>
                <p class="text-gray-600">Kelola penilaian dan verifikasi agunan kredit anggota.</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('riwayat-penilaian') ?>" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Riwayat Penilaian
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="<?= current_url() ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Penilaian</label>
                <select id="status" name="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= (request()->getGet('status') === 'pending') ? 'selected' : '' ?>>Menunggu Penilaian</option>
                    <option value="selesai" <?= (request()->getGet('status') === 'selesai') ? 'selected' : '' ?>>Sudah Dinilai</option>
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
                    <option value="Lainnya" <?= (request()->getGet('jenis_agunan') === 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Anggota</label>
                <input type="text" id="search" name="search" value="<?= request()->getGet('search') ?>" placeholder="Nama atau NIK anggota..." class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Taksir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php if (!empty($item['nilai_taksiran_agunan'])): ?>
                                        Rp <?= number_format($item['nilai_taksiran_agunan'], 0, ',', '.') ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">Belum dinilai</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $status = !empty($item['nilai_taksiran_agunan']) ? 'selesai' : 'pending';
                                    $statusColor = $status === 'selesai' ? 'green' : 'yellow';
                                    $statusText = $status === 'selesai' ? 'Sudah Dinilai' : 'Menunggu Penilaian';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                        <?= $statusText ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($item['tanggal_pengajuan'] ?? 'now')) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?= base_url('agunan/show/' . $item['id_kredit']) ?>" class="text-blue-600 hover:text-blue-900">
                                            Detail
                                        </a>
                                        <a href="<?= base_url('agunan/edit/' . $item['id_kredit']) ?>" class="text-indigo-600 hover:text-indigo-900">
                                            Edit
                                        </a>
                                        <?php if (empty($item['nilai_taksiran_agunan'])): ?>
                                            <a href="<?= base_url('agunan/nilai/' . $item['id_kredit']) ?>" class="text-green-600 hover:text-green-900">
                                                Nilai
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= base_url('agunan/delete/' . $item['id_kredit']) ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus data agunan ini?')">
                                            Hapus
                                        </a>
                                        <a href="<?= base_url('agunan/print/' . $item['id_kredit']) ?>" class="text-purple-600 hover:text-purple-900" target="_blank">
                                            Print
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <p>Belum ada agunan yang perlu dinilai</p>
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

<!-- Modal for Quick Evaluation -->
<div id="evaluateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Penilaian Cepat Agunan</h3>
            <form id="evaluateForm">
                <input type="hidden" id="evaluate_id_kredit" name="id_kredit">
                <div class="mb-4">
                    <label for="nilai_taksir" class="block text-sm font-medium text-gray-700 mb-2">Nilai Taksiran</label>
                    <input type="number" id="nilai_taksir" name="nilai_taksir" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="0" required>
                </div>
                <div class="mb-4">
                    <label for="catatan_appraiser" class="block text-sm font-medium text-gray-700 mb-2">Catatan Appraiser</label>
                    <textarea id="catatan_appraiser" name="catatan_appraiser" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Masukkan catatan penilaian..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEvaluateModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        Simpan Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEvaluateModal(idKredit) {
    document.getElementById('evaluate_id_kredit').value = idKredit;
    document.getElementById('evaluateModal').classList.remove('hidden');
}

function closeEvaluateModal() {
    document.getElementById('evaluateModal').classList.add('hidden');
    document.getElementById('evaluateForm').reset();
}

document.getElementById('evaluateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        id_kredit: formData.get('id_kredit'),
        nilai_taksir: formData.get('nilai_taksir'),
        catatan_appraiser: formData.get('catatan_appraiser')
    };
    
    fetch('<?= base_url('agunan/evaluate') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Penilaian berhasil disimpan!');
            location.reload();
        } else {
            alert('Gagal menyimpan penilaian: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan penilaian');
    });
});
</script>
<?= $this->endSection() ?>