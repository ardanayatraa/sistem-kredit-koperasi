<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Verifikasi Pembayaran Angsuran</h2>
            <p class="text-sm text-gray-600 mt-1">Verifikasi dan validasi pembayaran angsuran</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="/pembayaran-angsuran/verifikasi" 
               class="inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <i class="bx bx-arrow-left h-4 w-4"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline"><?= session()->getFlashdata('success') ?></span>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline"><?= session()->getFlashdata('error') ?></span>
    </div>
    <?php endif; ?>

    <!-- Status Verifikasi Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Status Verifikasi</h3>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Status Verifikasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Verifikasi</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        <?= $pembayaran['status_verifikasi'] === 'approved' ? 'bg-green-100 text-green-800' : 
                           ($pembayaran['status_verifikasi'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                        <?= ucfirst($pembayaran['status_verifikasi'] ?? 'pending') ?>
                    </span>
                </div>

                <!-- Verifikator -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diverifikasi oleh</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                        <?= !empty($pembayaran['nama_verifikator']) ? esc($pembayaran['nama_verifikator']) : 'Belum diverifikasi' ?>
                    </p>
                </div>

                <!-- Tanggal Verifikasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Verifikasi</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                        <?= !empty($pembayaran['updated_at']) && $pembayaran['status_verifikasi'] !== 'pending' ? 
                            date('d F Y H:i', strtotime($pembayaran['updated_at'])) : 'Belum diverifikasi' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Pembayaran Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detail Pembayaran</h3>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- ID Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Pembayaran</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg"><?= esc($pembayaran['id_pembayaran']) ?></p>
                </div>

                <!-- Tanggal Bayar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                        <?= date('d F Y', strtotime($pembayaran['tanggal_bayar'])) ?>
                    </p>
                </div>

                <!-- Jumlah Bayar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg font-semibold">
                        Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?>
                    </p>
                </div>

                <!-- Metode Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg"><?= esc($pembayaran['metode_pembayaran']) ?></p>
                </div>

                <!-- Denda -->
                <?php if (!empty($pembayaran['denda']) && $pembayaran['denda'] > 0): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Denda</label>
                    <p class="text-sm text-gray-900 bg-red-50 px-3 py-2 rounded-lg font-semibold text-red-700">
                        Rp <?= number_format($pembayaran['denda'], 0, ',', '.') ?>
                    </p>
                </div>
                <?php endif; ?>

                <!-- Bukti Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran</label>
                    <?php if (!empty($pembayaran['bukti_pembayaran'])): ?>
                        <a href="/uploads/pembayaran_angsuran/<?= $pembayaran['bukti_pembayaran'] ?>" 
                           target="_blank" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="bx bx-file mr-1"></i>
                            Lihat Bukti Pembayaran
                        </a>
                    <?php else: ?>
                        <p class="text-sm text-gray-500">Tidak ada bukti</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Angsuran -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Angsuran</h3>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- Nama Anggota -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anggota</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg"><?= esc($pembayaran['nama_lengkap']) ?></p>
                </div>

                <!-- No Anggota -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No Anggota</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg"><?= esc($pembayaran['no_anggota']) ?></p>
                </div>

                <!-- Angsuran Ke -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Angsuran Ke</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg"><?= $pembayaran['angsuran_ke'] ?></p>
                </div>

                <!-- Jumlah Angsuran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Angsuran</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg font-semibold">
                        Rp <?= number_format($pembayaran['jumlah_angsuran'], 0, ',', '.') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons (only show if pending) -->
    <?php if ($pembayaran['status_verifikasi'] === 'pending'): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Aksi Verifikasi</h4>
        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="approvePayment(<?= $pembayaran['id_pembayaran'] ?>)" 
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                <i class="bx bx-check h-4 w-4"></i>
                Setujui Pembayaran
            </button>
            <button onclick="rejectPayment(<?= $pembayaran['id_pembayaran'] ?>)" 
                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                <i class="bx bx-x h-4 w-4"></i>
                Tolak Pembayaran
            </button>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal untuk alasan penolakan -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Alasan Penolakan</h3>
            <textarea id="rejectReason" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                      rows="4" 
                      placeholder="Masukkan alasan penolakan..."></textarea>
            <div class="flex justify-end mt-4 gap-2">
                <button onclick="closeRejectModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Batal
                </button>
                <button onclick="confirmReject()" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    Tolak
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPaymentId = null;

function approvePayment(id) {
    if (confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')) {
        fetch(`/pembayaran-angsuran/verifikasi-approve/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
        });
    }
}

function rejectPayment(id) {
    currentPaymentId = id;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectReason').value = '';
    currentPaymentId = null;
}

function confirmReject() {
    const reason = document.getElementById('rejectReason').value.trim();
    if (!reason) {
        alert('Alasan penolakan harus diisi');
        return;
    }

    fetch(`/pembayaran-angsuran/verifikasi-reject/${currentPaymentId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            alasan: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan sistem');
    });
    
    closeRejectModal();
}
</script>
<?= $this->endSection() ?>