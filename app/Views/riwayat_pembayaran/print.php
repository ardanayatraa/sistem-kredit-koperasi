<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran - <?= esc($pembayaran['nama_lengkap']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 11px;
            color: #666;
        }
        .content {
            margin-bottom: 20px;
        }
        .row {
            display: flex;
            margin-bottom: 8px;
        }
        .col-label {
            width: 150px;
            font-weight: bold;
            color: #333;
        }
        .col-value {
            flex: 1;
            color: #555;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .amount {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
        }
        .status {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .print-date {
            text-align: right;
            font-size: 10px;
            color: #666;
            margin-bottom: 20px;
        }
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="print-date">
        Dicetak pada: <?= date('d F Y H:i:s') ?>
    </div>

    <div class="header">
        <h1>SISTEM KREDIT KOPERASI</h1>
        <p>Bukti Pembayaran Angsuran Kredit</p>
    </div>

    <div class="content">
        <!-- Data Anggota -->
        <div class="section">
            <div class="section-title">Data Anggota</div>
            <div class="row">
                <div class="col-label">No. Anggota</div>
                <div class="col-value">: <?= esc($pembayaran['no_anggota']) ?></div>
            </div>
            <div class="row">
                <div class="col-label">Nama Lengkap</div>
                <div class="col-value">: <?= esc($pembayaran['nama_lengkap']) ?></div>
            </div>
        </div>

        <!-- Data Pembayaran -->
        <div class="section">
            <div class="section-title">Detail Pembayaran</div>
            <div class="row">
                <div class="col-label">Angsuran Ke</div>
                <div class="col-value">: <?= esc($pembayaran['angsuran_ke']) ?></div>
            </div>
            <div class="row">
                <div class="col-label">Jumlah Angsuran</div>
                <div class="col-value">: Rp <?= number_format($pembayaran['jumlah_angsuran'], 0, ',', '.') ?></div>
            </div>
            <div class="row">
                <div class="col-label">Jumlah Dibayar</div>
                <div class="col-value">: <span class="amount">Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></span></div>
            </div>
            <div class="row">
                <div class="col-label">Tanggal Pembayaran</div>
                <div class="col-value">: <?= date('d F Y', strtotime($pembayaran['tanggal_pembayaran'])) ?></div>
            </div>
            <div class="row">
                <div class="col-label">Metode Pembayaran</div>
                <div class="col-value">: <?= esc($pembayaran['metode_pembayaran'] ?? 'Tunai') ?></div>
            </div>
            <div class="row">
                <div class="col-label">Status</div>
                <div class="col-value">: <span class="status">LUNAS</span></div>
            </div>
        </div>

        <!-- Data Kredit -->
        <div class="section">
            <div class="section-title">Informasi Kredit</div>
            <div class="row">
                <div class="col-label">Total Kredit</div>
                <div class="col-value">: Rp <?= number_format($pembayaran['jumlah_pengajuan'], 0, ',', '.') ?></div>
            </div>
        </div>

        <!-- Catatan -->
        <?php if (!empty($pembayaran['catatan'])): ?>
        <div class="section">
            <div class="section-title">Catatan</div>
            <p><?= esc($pembayaran['catatan']) ?></p>
        </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari Sistem Kredit Koperasi</p>
        <p>Untuk verifikasi, silakan hubungi petugas koperasi</p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>