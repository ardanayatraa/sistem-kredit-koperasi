<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Detail Agunan - <?= esc($kredit['nama_lengkap']) ?></title>
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
            background: white;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 18px;
            color: #374151;
            margin-bottom: 10px;
        }

        .header p {
            color: #6b7280;
            font-size: 11px;
        }

        .section {
            margin-bottom: 25px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-header {
            background: #f3f4f6;
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: bold;
            font-size: 14px;
            color: #374151;
        }

        .section-content {
            padding: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .info-value {
            color: #1f2937;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fef2f2;
            color: #991b1b;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .notes-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            margin-top: 10px;
        }

        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            text-align: center;
        }

        .signature-box {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
            min-height: 100px;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 60px;
            font-size: 11px;
            color: #374151;
        }

        .signature-line {
            border-top: 1px solid #374151;
            margin-top: 10px;
            padding-top: 5px;
            font-size: 10px;
            color: #6b7280;
        }

        .print-info {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .container {
                padding: 0;
                max-width: none;
            }

            .section {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>KOPERASI KREDIT</h1>
            <h2>LAPORAN DETAIL AGUNAN</h2>
            <p>Alamat: Jl. Contoh No. 123, Kota • Telp: (021) 123-4567 • Email: info@koperasi.com</p>
        </div>

        <!-- Informasi Anggota -->
        <div class="section">
            <div class="section-header">INFORMASI ANGGOTA</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value"><?= esc($kredit['nama_lengkap'] ?? 'N/A') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">ID Anggota</div>
                        <div class="info-value"><?= esc($kredit['id_anggota'] ?? 'N/A') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">NIK</div>
                        <div class="info-value"><?= esc($kredit['nik'] ?? 'N/A') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">No. HP</div>
                        <div class="info-value"><?= esc($kredit['no_hp'] ?? 'N/A') ?></div>
                    </div>
                    <div class="info-item full-width">
                        <div class="info-label">Alamat</div>
                        <div class="info-value"><?= esc($kredit['alamat'] ?? 'N/A') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Kredit -->
        <div class="section">
            <div class="section-header">INFORMASI KREDIT</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Jumlah Pengajuan</div>
                        <div class="info-value">Rp <?= number_format($kredit['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tenor</div>
                        <div class="info-value"><?= esc($kredit['tenor'] ?? 'N/A') ?> bulan</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Pengajuan</div>
                        <div class="info-value"><?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'] ?? 'now')) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status Kredit</div>
                        <div class="info-value">
                            <span class="status-badge <?= $kredit['status_aktif'] == 1 ? 'status-active' : 'status-inactive' ?>">
                                <?= $kredit['status_aktif'] == 1 ? 'Aktif' : 'Tidak Aktif' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Agunan -->
        <div class="section">
            <div class="section-header">INFORMASI AGUNAN</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Jenis Agunan</div>
                        <div class="info-value"><?= esc($kredit['jenis_agunan'] ?? 'N/A') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Nilai Taksiran</div>
                        <div class="info-value">
                            <?php if (!empty($kredit['nilai_taksiran_agunan'])): ?>
                                Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?>
                            <?php else: ?>
                                <span style="color: #dc2626;">Belum dinilai</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status Penilaian</div>
                        <div class="info-value">
                            <?php 
                            $status = !empty($kredit['nilai_taksiran_agunan']) ? 'selesai' : 'pending';
                            $statusClass = $status === 'selesai' ? 'status-completed' : 'status-pending';
                            $statusText = $status === 'selesai' ? 'Sudah Dinilai' : 'Menunggu Penilaian';
                            ?>
                            <span class="status-badge <?= $statusClass ?>">
                                <?= $statusText ?>
                            </span>
                        </div>
                    </div>
                    <div class="info-item full-width">
                        <div class="info-label">Deskripsi Agunan</div>
                        <div class="info-value"><?= esc($kredit['deskripsi_agunan'] ?? 'N/A') ?></div>
                    </div>
                </div>

                <?php if (!empty($kredit['catatan_appraiser'])): ?>
                <div style="margin-top: 15px;">
                    <div class="info-label">Catatan Appraiser</div>
                    <div class="notes-box">
                        <?= nl2br(esc($kredit['catatan_appraiser'])) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Ringkasan Finansial -->
        <div class="section">
            <div class="section-header">RINGKASAN FINANSIAL</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Jumlah Kredit Disetujui</div>
                        <div class="info-value">Rp <?= number_format($kredit['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Nilai Agunan</div>
                        <div class="info-value">
                            <?php if (!empty($kredit['nilai_taksiran_agunan'])): ?>
                                Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?>
                            <?php else: ?>
                                <span style="color: #dc2626;">Belum dinilai</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Rasio LTV</div>
                        <div class="info-value">
                            <?php if (!empty($kredit['nilai_taksiran_agunan']) && $kredit['nilai_taksiran_agunan'] > 0): ?>
                                <?= number_format(($kredit['jumlah_pengajuan'] / $kredit['nilai_taksiran_agunan']) * 100, 2) ?>%
                            <?php else: ?>
                                <span style="color: #dc2626;">Tidak dapat dihitung</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Margin Keamanan</div>
                        <div class="info-value">
                            <?php if (!empty($kredit['nilai_taksiran_agunan']) && $kredit['nilai_taksiran_agunan'] > 0): ?>
                                Rp <?= number_format($kredit['nilai_taksiran_agunan'] - $kredit['jumlah_pengajuan'], 0, ',', '.') ?>
                            <?php else: ?>
                                <span style="color: #dc2626;">Tidak dapat dihitung</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Appraiser</div>
                <div class="signature-line">(...........................)</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Ketua Koperasi</div>
                <div class="signature-line">(...........................)</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Anggota</div>
                <div class="signature-line"><?= esc($kredit['nama_lengkap'] ?? '(...........................)') ?></div>
            </div>
        </div>

        <!-- Print Info -->
        <div class="print-info">
            <p>Dokumen ini dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
            <p>ID Kredit: <?= esc($kredit['id_kredit']) ?> | Generated by Sistem Kredit Koperasi</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>
</html>