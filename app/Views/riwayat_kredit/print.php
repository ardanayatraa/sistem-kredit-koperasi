<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Print Riwayat Kredit' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        .header h2 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }
        
        .header p {
            font-size: 10px;
            color: #666;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
            text-transform: uppercase;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 40%;
            padding: 6px 0;
            font-weight: bold;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            width: 10%;
            padding: 6px 0;
            text-align: center;
            vertical-align: top;
        }
        
        .info-data {
            display: table-cell;
            width: 50%;
            padding: 6px 0;
            vertical-align: top;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-disetujui, .status-aktif {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-lunas {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status-diajukan {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        .status-default {
            background-color: #f8f9fa;
            color: #6c757d;
            border: 1px solid #e9ecef;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .header {
                margin-bottom: 20px;
            }
            
            .section {
                margin-bottom: 20px;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Sistem Kredit Koperasi</h1>
        <h2>Riwayat Kredit</h2>
        <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
    </div>

    <!-- Credit Information -->
    <div class="section">
        <div class="section-title">Informasi Kredit</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">No. Anggota</div>
                <div class="info-value">:</div>
                <div class="info-data"><?= esc($kredit['no_anggota'] ?? 'N/A') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">:</div>
                <div class="info-data"><?= esc($kredit['nama_lengkap'] ?? 'N/A') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Jumlah Kredit</div>
                <div class="info-value">:</div>
                <div class="info-data">Rp <?= number_format($kredit['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Jangka Waktu</div>
                <div class="info-value">:</div>
                <div class="info-data"><?= $kredit['jangka_waktu'] ?? '0' ?> bulan</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tujuan Kredit</div>
                <div class="info-value">:</div>
                <div class="info-data"><?= esc($kredit['tujuan_kredit'] ?? 'N/A') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Kredit</div>
                <div class="info-value">:</div>
                <div class="info-data">
                    <?php 
                    $status = $kredit['status_kredit'] ?? 'Pending';
                    $statusClass = '';
                    switch ($status) {
                        case 'Disetujui':
                        case 'Aktif':
                            $statusClass = 'status-disetujui';
                            break;
                        case 'Lunas':
                            $statusClass = 'status-lunas';
                            break;
                        case 'Ditolak':
                            $statusClass = 'status-ditolak';
                            break;
                        case 'Diajukan':
                            $statusClass = 'status-diajukan';
                            break;
                        default:
                            $statusClass = 'status-default';
                    }
                    ?>
                    <span class="status-badge <?= $statusClass ?>"><?= esc($status) ?></span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Pengajuan</div>
                <div class="info-value">:</div>
                <div class="info-data"><?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'] ?? 'now')) ?></div>
            </div>
            <?php if (!empty($kredit['tanggal_persetujuan'])): ?>
            <div class="info-row">
                <div class="info-label">Tanggal Persetujuan</div>
                <div class="info-value">:</div>
                <div class="info-data"><?= date('d/m/Y', strtotime($kredit['tanggal_persetujuan'])) ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="section">
        <div class="section-title">Informasi Kontak</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Alamat</div>
                <div class="info-value">:</div>
                <div class="info-data"><?= esc($kredit['alamat'] ?? 'N/A') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">No. HP</div>
                <div class="info-value">:</div>
                <div class="info-data"><?= esc($kredit['no_hp'] ?? 'N/A') ?></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Kredit Koperasi</p>
        <p>© <?= date('Y') ?> Sistem Kredit Koperasi - Semua hak dilindungi</p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>