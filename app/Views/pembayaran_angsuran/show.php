<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Detail Pembayaran Angsuran</h2>
            <p class="text-sm text-gray-600 mt-1">Informasi lengkap pembayaran angsuran</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="/pembayaran-angsuran"
               class="inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <i class="bx bx-arrow-left h-4 w-4"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- ID Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Pembayaran</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg"><?= esc($pembayaran_angsuran['id_pembayaran']) ?></p>
                </div>

                <!-- Tanggal Bayar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                        <?= date('d F Y', strtotime($pembayaran_angsuran['tanggal_bayar'])) ?>
                    </p>
                </div>

                <!-- Jumlah Bayar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg font-semibold">
                        Rp <?= number_format($pembayaran_angsuran['jumlah_bayar'], 0, ',', '.') ?>
                    </p>
                </div>

                <!-- Status Verifikasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Verifikasi</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        <?= ($pembayaran_angsuran['status_verifikasi'] ?? 'pending') === 'approved' ? 'bg-green-100 text-green-800' :
                           (($pembayaran_angsuran['status_verifikasi'] ?? 'pending') === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                        <?= ucfirst($pembayaran_angsuran['status_verifikasi'] ?? 'pending') ?>
                    </span>
                </div>

                <!-- Verifikator -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diverifikasi oleh</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                        <?= !empty($pembayaran_angsuran['nama_verifikator']) ? esc($pembayaran_angsuran['nama_verifikator']) : 'Belum diverifikasi' ?>
                    </p>
                </div>

                <!-- Keterangan -->
                <?php if (!empty($pembayaran_angsuran['keterangan'])): ?>
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg"><?= esc($pembayaran_angsuran['keterangan']) ?></p>
                </div>
                <?php endif; ?>
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
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg"><?= esc($angsuran['nama_anggota']) ?></p>
                </div>

                <!-- Angsuran Ke -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Angsuran Ke</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg"><?= $angsuran['angsuran_ke'] ?></p>
                </div>

                <!-- Jumlah Angsuran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Angsuran</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg font-semibold">
                        Rp <?= number_format($angsuran['jumlah_angsuran'], 0, ',', '.') ?>
                    </p>
                </div>

                <!-- Tanggal Jatuh Tempo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Jatuh Tempo</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                        <?= date('d F Y', strtotime($angsuran['tgl_jatuh_tempo'])) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Kredit -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Kredit</h3>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- Jumlah Kredit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Kredit</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg font-semibold">
                        Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?>
                    </p>
                </div>

                <!-- Jangka Waktu -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jangka Waktu</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg"><?= $kredit['jangka_waktu'] ?> bulan</p>
                </div>

                <!-- Tanggal Kredit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kredit</label>
                    <p class="text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">
                        <?= date('d F Y', strtotime($kredit['tanggal_pengajuan'])) ?>
                    </p>
                </div>

                <!-- Status Kredit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kredit</label>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        <?= $kredit['status_kredit'] === 'AKTIF' ? 'bg-green-100 text-green-800' :
                           ($kredit['status_kredit'] === 'LUNAS' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') ?>">
                        <?= esc($kredit['status_kredit']) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 pt-4">
        <button onclick="confirmDelete()" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
            <i class="bx bx-trash h-4 w-4"></i>
            Hapus
        </button>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus pembayaran angsuran ini?')) {
        window.location.href = '/pembayaran-angsuran/delete/<?= $pembayaran_angsuran['id_pembayaran'] ?>';
    }
}

// Print styles
const printStyles = `
    @media print {
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        .print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
`;

const styleSheet = document.createElement("style");
styleSheet.type = "text/css";
styleSheet.innerText = printStyles;
document.head.appendChild(styleSheet);
</script>
<?= $this->endSection() ?>
