<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kredit Koperasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header .subtitle {
            font-size: 14px;
            color: #666;
        }
        .info-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-box h3 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-diajukan { background-color: #fff3cd; color: #856404; }
        .status-disetujui { background-color: #d4edda; color: #155724; }
        .status-ditolak { background-color: #f8d7da; color: #721c24; }
        .status-berjalan { background-color: #cce5ff; color: #004085; }
        .status-selesai { background-color: #e2e3e5; color: #383d41; }
        .summary-section {
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Kredit Koperasi</h1>
        <div class="subtitle">Sistem Koperasi Simpan Pinjam</div>
        <div class="subtitle">Dicetak pada: <?= date('d F Y H:i:s') ?></div>
    </div>

    <!-- Statistik Keseluruhan -->
    <div class="info-box summary-section">
        <h3>Ringkasan Kredit Koperasi</h3>
        <div class="stats-grid">
            <?php
            $totalKredit = count($kredit);
            $totalDiajukan = count(array_filter($kredit, fn($k) => ($k['status_kredit'] ?? '') === 'Diajukan'));
            $totalDisetujui = count(array_filter($kredit, fn($k) => ($k['status_kredit'] ?? '') === 'Disetujui'));
            $totalDitolak = count(array_filter($kredit, fn($k) => ($k['status_kredit'] ?? '') === 'Ditolak'));
            $totalNilai = array_sum(array_column($kredit, 'jumlah_pengajuan'));
            ?>
            <div class="stat-box">
                <div class="stat-label">Total Kredit</div>
                <div class="stat-value"><?= number_format($totalKredit) ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Disetujui</div>
                <div class="stat-value"><?= number_format($totalDisetujui) ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Menunggu</div>
                <div class="stat-value"><?= number_format($totalDiajukan) ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Ditolak</div>
                <div class="stat-value"><?= number_format($totalDitolak) ?></div>
            </div>
        </div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Total Nilai Kredit</div>
                <div class="stat-value">Rp <?= number_format($totalNilai, 0, ',', '.') ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Rata-rata Kredit</div>
                <div class="stat-value">Rp <?= number_format($totalKredit > 0 ? $totalNilai / $totalKredit : 0, 0, ',', '.') ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Tingkat Persetujuan</div>
                <div class="stat-value"><?= $totalKredit > 0 ? number_format(($totalDisetujui / $totalKredit) * 100, 1) : 0 ?>%</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Anggota</div>
                <div class="stat-value"><?= count(array_unique(array_column($kredit, 'no_anggota'))) ?></div>
            </div>
        </div>
    </div>

    <!-- Daftar Semua Kredit -->
    <div class="info-box">
        <h3>Daftar Kredit Koperasi</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Kredit</th>
                    <th>No. Anggota</th>
                    <th>Nama Anggota</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kredit as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($row['id_kredit']) ?></td>
                        <td><?= esc($row['no_anggota'] ?? '-') ?></td>
                        <td><?= esc($row['nama_lengkap'] ?? '-') ?></td>
                        <td>Rp <?= number_format($row['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></td>
                        <td>
                            <?php $currentStatus = $row['status_kredit'] ?? 'Diajukan'; ?>
                            <span class="status-badge status-<?= strtolower($currentStatus) ?>">
                                <?= esc($currentStatus) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($row['created_at'] ?? $row['tanggal_pengajuan'] ?? 'now')) ?></td>
                    </tr>
                <?php endforeach; ?>
                
                <?php if (empty($kredit)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #666; font-style: italic;">
                            Belum ada data kredit tersedia
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Ringkasan Berdasarkan Status -->
    <div class="info-box">
        <h3>Ringkasan Per Status</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Jumlah Kredit</th>
                    <th>Total Nilai</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $statusData = [
                    'Diajukan' => ['count' => $totalDiajukan, 'total' => array_sum(array_map(fn($k) => ($k['status_kredit'] ?? '') === 'Diajukan' ? ($k['jumlah_pengajuan'] ?? 0) : 0, $kredit))],
                    'Disetujui' => ['count' => $totalDisetujui, 'total' => array_sum(array_map(fn($k) => ($k['status_kredit'] ?? '') === 'Disetujui' ? ($k['jumlah_pengajuan'] ?? 0) : 0, $kredit))],
                    'Ditolak' => ['count' => $totalDitolak, 'total' => array_sum(array_map(fn($k) => ($k['status_kredit'] ?? '') === 'Ditolak' ? ($k['jumlah_pengajuan'] ?? 0) : 0, $kredit))]
                ];
                
                foreach ($statusData as $status => $data): 
                ?>
                    <tr>
                        <td>
                            <span class="status-badge status-<?= strtolower($status) ?>">
                                <?= $status ?>
                            </span>
                        </td>
                        <td><?= number_format($data['count']) ?></td>
                        <td>Rp <?= number_format($data['total'], 0, ',', '.') ?></td>
                        <td><?= $totalKredit > 0 ? number_format(($data['count'] / $totalKredit) * 100, 1) : 0 ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan Kredit Koperasi - Halaman 1 - Dicetak pada <?= date('d F Y H:i:s') ?></p>
        <p>Total <?= count($kredit) ?> record kredit</p>
    </div>
</body>
</html>