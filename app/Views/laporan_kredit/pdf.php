<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kredit</title>
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
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #333;
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
            font-size: 11px;
            font-weight: bold;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-disetujui { background-color: #d4edda; color: #155724; }
        .status-ditolak { background-color: #f8d7da; color: #721c24; }
        .status-berjalan { background-color: #cce5ff; color: #004085; }
        .status-selesai { background-color: #e2e3e5; color: #383d41; }
        .status-lunas { background-color: #d4edda; color: #155724; }
        .status-belum-lunas { background-color: #f8d7da; color: #721c24; }
        .status-terlambat { background-color: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Kredit</h1>
        <div class="subtitle">Sistem Koperasi Simpan Pinjam</div>
        <div class="subtitle">Dicetak pada: <?= date('d F Y H:i:s') ?></div>
    </div>

    <!-- Informasi Kredit -->
    <div class="info-box">
        <h3>Informasi Kredit</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">ID Kredit:</span>
                <span class="info-value"><?= esc($kredit['id_kredit']) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Pengajuan:</span>
                <span class="info-value"><?= date('d F Y H:i', strtotime($kredit['tanggal_pengajuan'])) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Jumlah Pinjaman:</span>
                <span class="info-value">Rp <?= number_format($kredit['jumlah_pinjaman'], 0, ',', '.') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span class="status-badge status-<?= $kredit['status'] ?>">
                        <?= ucfirst($kredit['status']) ?>
                    </span>
                </span>
            </div>
        </div>
    </div>

    <!-- Informasi Anggota -->
    <div class="info-box">
        <h3>Informasi Anggota</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nama Anggota:</span>
                <span class="info-value"><?= esc($anggota['nama_lengkap'] ?? '-') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">No. HP:</span>
                <span class="info-value"><?= esc($anggota['no_hp'] ?? '-') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <span class="info-value"><?= esc($anggota['email'] ?? '-') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Alamat:</span>
                <span class="info-value"><?= esc($anggota['alamat'] ?? '-') ?></span>
            </div>
        </div>
    </div>

    <!-- Statistik Angsuran -->
    <div class="info-box">
        <h3>Statistik Angsuran</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Total Angsuran</div>
                <div class="stat-value"><?= count($angsurans) ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Lunas</div>
                <div class="stat-value"><?= $angsuranLunas ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Belum Lunas</div>
                <div class="stat-value"><?= count($angsurans) - $angsuranLunas ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Dibayar</div>
                <div class="stat-value">Rp <?= number_format($totalDibayar, 0, ',', '.') ?></div>
            </div>
        </div>
    </div>

    <!-- Daftar Angsuran -->
    <div class="info-box">
        <h3>Daftar Angsuran</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($angsurans as $index => $angsuran): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= date('d M Y', strtotime($angsuran['tanggal_pembayaran'])) ?></td>
                        <td>Rp <?= number_format($angsuran['jumlah_angsuran'], 0, ',', '.') ?></td>
                        <td>
                            <span class="status-badge status-<?= $angsuran['status_pembayaran'] ?>">
                                <?= ucfirst($angsuran['status_pembayaran']) ?>
                            </span>
                        </td>
                        <td><?= esc($angsuran['keterangan'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Halaman 1</p>
    </div>
</body>
</html>