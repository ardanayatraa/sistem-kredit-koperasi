<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Riwayat Kredit - <?= esc($kredit['nama_lengkap']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
            .page-break { page-break-before: always; }
        }
        body { font-family: 'Times New Roman', serif; }
    </style>
</head>
<body class="bg-white text-gray-900">
    <!-- Print Controls -->
    <div class="no-print fixed top-4 right-4 z-50">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            🖨️ Print
        </button>
        <button onclick="window.close()" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 ml-2">
            ✕ Tutup
        </button>
    </div>

    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold mb-2">KOPERASI MITRA SEJAHTRA</h1>
        <h2 class="text-xl font-semibold mb-4">LAPORAN RIWAYAT KREDIT</h2>
        <div class="border-b-2 border-gray-300 mb-6"></div>
    </div>

    <!-- Kredit Information -->
    <div class="mb-6">
        <h3 class="text-lg font-bold mb-4 border-b border-gray-300 pb-2">INFORMASI KREDIT</h3>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <table class="w-full text-sm">
                    <tr>
                        <td class="font-semibold w-32">ID Kredit:</td>
                        <td>#<?= esc($kredit['id_kredit']) ?></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Nama Anggota:</td>
                        <td><?= esc($kredit['nama_lengkap']) ?></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">No Anggota:</td>
                        <td><?= esc($kredit['no_anggota'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Tanggal Pengajuan:</td>
                        <td><?= date('d F Y', strtotime($kredit['tanggal_pengajuan'])) ?></td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="w-full text-sm">
                    <tr>
                        <td class="font-semibold w-32">Jumlah Pengajuan:</td>
                        <td>Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Jangka Waktu:</td>
                        <td><?= esc($kredit['jangka_waktu']) ?> bulan</td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Status Kredit:</td>
                        <td>
                            <span class="px-2 py-1 text-xs font-semibold rounded
                                <?php
                                $statusClasses = [
                                    'Diajukan' => 'bg-yellow-100 text-yellow-800',
                                    'Pending' => 'bg-orange-100 text-orange-800',
                                    'Disetujui' => 'bg-green-100 text-green-800',
                                    'Ditolak' => 'bg-red-100 text-red-800',
                                    'Berjalan' => 'bg-blue-100 text-blue-800',
                                    'Selesai' => 'bg-gray-100 text-gray-800'
                                ];
                                echo $statusClasses[$kredit['status_kredit']] ?? 'bg-gray-100 text-gray-800';
                                ?>">
                                <?= esc($kredit['status_kredit']) ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mb-4">
            <strong>Tujuan Kredit:</strong>
            <p class="mt-1 text-sm"><?= esc($kredit['tujuan_kredit']) ?></p>
        </div>
    </div>

    <!-- Agunan Information -->
    <div class="mb-6">
        <h3 class="text-lg font-bold mb-4 border-b border-gray-300 pb-2">DATA AGUNAN</h3>

        <table class="w-full text-sm">
            <tr>
                <td class="font-semibold w-32">Jenis Agunan:</td>
                <td><?= esc($kredit['jenis_agunan']) ?></td>
            </tr>
            <tr>
                <td class="font-semibold">Nilai Taksiran:</td>
                <td>Rp <?= number_format($kredit['nilai_taksiran_agunan'] ?? 0, 0, ',', '.') ?></td>
            </tr>
            <?php if (!empty($kredit['dokumen_agunan'])): ?>
            <tr>
                <td class="font-semibold">Dokumen:</td>
                <td><?= basename($kredit['dokumen_agunan']) ?> ✓</td>
            </tr>
            <?php endif; ?>
        </table>
    </div>

    <!-- Catatan Section -->
    <div class="mb-6">
        <h3 class="text-lg font-bold mb-4 border-b border-gray-300 pb-2">CATATAN & PERSETUJUAN</h3>

        <div class="grid grid-cols-1 gap-4">
            <div class="border border-gray-300 rounded p-3">
                <strong class="text-sm">Catatan Bendahara:</strong>
                <p class="mt-1 text-sm">
                    <?= !empty($kredit['catatan_bendahara']) ? esc($kredit['catatan_bendahara']) : 'Belum ada catatan' ?>
                </p>
            </div>

            <div class="border border-gray-300 rounded p-3">
                <strong class="text-sm">Catatan Penilai (Appraiser):</strong>
                <p class="mt-1 text-sm">
                    <?= !empty($kredit['catatan_appraiser']) ? esc($kredit['catatan_appraiser']) : 'Belum ada catatan' ?>
                </p>
            </div>

            <div class="border border-gray-300 rounded p-3">
                <strong class="text-sm">Catatan Ketua:</strong>
                <p class="mt-1 text-sm">
                    <?= !empty($kredit['catatan_ketua']) ? esc($kredit['catatan_ketua']) : 'Belum ada catatan' ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="mb-6">
        <h3 class="text-lg font-bold mb-4 border-b border-gray-300 pb-2">TIMELINE PROSES</h3>

        <div class="space-y-2">
            <?php
            $timeline = [
                ['date' => $kredit['tanggal_pengajuan'], 'title' => 'Pengajuan Dibuat', 'description' => 'Anggota mengajukan kredit baru'],
                ['date' => $kredit['tanggal_verifikasi_bendahara'] ?? null, 'title' => 'Verifikasi Bendahara', 'description' => 'Dokumen diverifikasi oleh bendahara'],
                ['date' => $kredit['tanggal_penilaian_appraiser'] ?? null, 'title' => 'Penilaian Appraiser', 'description' => 'Agunan dinilai oleh appraiser'],
                ['date' => $kredit['tanggal_persetujuan_ketua'] ?? null, 'title' => 'Persetujuan Ketua', 'description' => 'Kredit diputuskan oleh ketua'],
                ['date' => $kredit['tanggal_pencairan'] ?? null, 'title' => 'Pencairan Dana', 'description' => 'Dana kredit dicairkan'],
            ];
            ?>

            <?php foreach ($timeline as $index => $item): ?>
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-3">
                    <?php if ($item['date']): ?>
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            ✓
                        </div>
                    <?php else: ?>
                        <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 text-xs">
                            ○
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <strong class="text-sm"><?= esc($item['title']) ?></strong>
                        <?php if ($item['date']): ?>
                            <span class="text-xs text-gray-600"><?= date('d/m/Y H:i', strtotime($item['date'])) ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-gray-600 mt-1"><?= esc($item['description']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-12 pt-6 border-t border-gray-300">
        <div class="text-center text-xs text-gray-600">
            <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>
            <p>Sistem Manajemen Koperasi Mitra Sejahtera</p>
        </div>
    </div>

    <!-- Print Script -->
    <script>
        // Auto print when loaded
        window.onload = function() {
            // Uncomment this line if you want auto-print
            // window.print();
        };
    </script>
</body>
</html>