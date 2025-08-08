<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Riwayat Penilaian - <?= esc($kredit['nama_lengkap'] ?? 'N/A') ?></title>
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
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 12px;
            color: #888;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 8px 12px;
            border-left: 4px solid #007bff;
            margin-bottom: 15px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }
        
        .info-value {
            color: #333;
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .notes-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .note-item {
            margin-bottom: 12px;
            padding: 10px;
            border-left: 3px solid #ddd;
            background-color: white;
        }
        
        .note-item.bendahara { border-left-color: #007bff; }
        .note-item.appraiser { border-left-color: #28a745; }
        .note-item.ketua { border-left-color: #6f42c1; }
        
        .note-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .timeline {
            border-left: 2px solid #ddd;
            padding-left: 20px;
            margin-left: 10px;
        }
        
        .timeline-item {
            margin-bottom: 20px;
            position: relative;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -25px;
            top: 5px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #007bff;
        }
        
        .timeline-date {
            font-size: 11px;
            color: #888;
            margin-bottom: 3px;
        }
        
        .timeline-content {
            font-weight: bold;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-active { background-color: #d1ecf1; color: #0c5460; }
        
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #888;
        }
        
        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin: 50px 0 10px 0;
        }
        
        @media print {
            body { font-size: 11px; }
            .header { margin-bottom: 20px; }
            .section { margin-bottom: 20px; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>KOPERASI SIMPAN PINJAM</h1>
        <h2>LAPORAN RIWAYAT PENILAIAN AGUNAN</h2>
        <p>Jl. Alamat Koperasi No. 123, Kota, Provinsi | Telp: (021) 123-4567</p>
    </div>

    <?php if (!empty($kredit)): ?>
    <!-- Informasi Anggota -->
    <div class="section">
        <div class="section-title">INFORMASI ANGGOTA</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nama Lengkap:</span>
                <span class="info-value"><?= esc($kredit['nama_lengkap'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">ID Anggota:</span>
                <span class="info-value"><?= esc($kredit['id_anggota'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">No. HP:</span>
                <span class="info-value"><?= esc($kredit['no_hp'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Pekerjaan:</span>
                <span class="info-value"><?= esc($kredit['pekerjaan'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item full-width">
                <span class="info-label">Alamat:</span>
                <span class="info-value"><?= esc($kredit['alamat'] ?? 'N/A') ?></span>
            </div>
        </div>
    </div>

    <!-- Informasi Kredit -->
    <div class="section">
        <div class="section-title">INFORMASI KREDIT</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Jumlah Pengajuan:</span>
                <span class="info-value">Rp <?= number_format($kredit['jumlah_pengajuan'] ?? 0, 0, ',', '.') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Jangka Waktu:</span>
                <span class="info-value"><?= $kredit['jangka_waktu'] ?? '0' ?> bulan</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Pengajuan:</span>
                <span class="info-value"><?= date('d F Y', strtotime($kredit['tanggal_pengajuan'] ?? 'now')) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Status Kredit:</span>
                <?php 
                $status = $kredit['status_kredit'] ?? 'Pending';
                $statusClass = '';
                switch ($status) {
                    case 'Disetujui':
                    case 'Aktif':
                        $statusClass = 'status-approved';
                        break;
                    case 'Lunas':
                        $statusClass = 'status-active';
                        break;
                    case 'Ditolak':
                        $statusClass = 'status-rejected';
                        break;
                    default:
                        $statusClass = 'status-pending';
                }
                ?>
                <span class="status-badge <?= $statusClass ?>"><?= esc($status) ?></span>
            </div>
            <div class="info-item full-width">
                <span class="info-label">Tujuan Kredit:</span>
                <span class="info-value"><?= esc($kredit['tujuan_kredit'] ?? 'N/A') ?></span>
            </div>
        </div>
    </div>

    <!-- Detail Agunan dan Penilaian -->
    <div class="section">
        <div class="section-title">DETAIL AGUNAN DAN PENILAIAN</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Jenis Agunan:</span>
                <span class="info-value"><?= esc($kredit['jenis_agunan'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Nilai Taksiran:</span>
                <?php if (!empty($kredit['nilai_taksiran_agunan'])): ?>
                    <span class="info-value">Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></span>
                <?php else: ?>
                    <span class="info-value" style="color: #888;">Belum dinilai</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Catatan Penilaian -->
        <?php if (!empty($kredit['catatan_bendahara']) || !empty($kredit['catatan_appraiser']) || !empty($kredit['catatan_ketua'])): ?>
        <div class="notes-section">
            <h4 style="margin-bottom: 10px; font-size: 13px;">Catatan Penilaian:</h4>
            
            <?php if (!empty($kredit['catatan_bendahara'])): ?>
            <div class="note-item bendahara">
                <div class="note-title">Catatan Bendahara:</div>
                <div><?= esc($kredit['catatan_bendahara']) ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($kredit['catatan_appraiser'])): ?>
            <div class="note-item appraiser">
                <div class="note-title">Catatan Appraiser/Penilai:</div>
                <div><?= esc($kredit['catatan_appraiser']) ?></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($kredit['catatan_ketua'])): ?>
            <div class="note-item ketua">
                <div class="note-title">Catatan Ketua Koperasi:</div>
                <div><?= esc($kredit['catatan_ketua']) ?></div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Timeline Penilaian -->
    <div class="section">
        <div class="section-title">TIMELINE PENILAIAN</div>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-date"><?= date('d F Y', strtotime($kredit['tanggal_pengajuan'] ?? 'now')) ?></div>
                <div class="timeline-content">Kredit diajukan oleh anggota</div>
            </div>

            <?php if (!empty($kredit['catatan_appraiser'])): ?>
            <div class="timeline-item">
                <div class="timeline-date"><?= date('d F Y', strtotime($kredit['updated_at'] ?? 'now')) ?></div>
                <div class="timeline-content">Agunan dinilai oleh Appraiser</div>
            </div>
            <?php endif; ?>

            <?php if (!empty($kredit['catatan_ketua'])): ?>
            <div class="timeline-item">
                <div class="timeline-date"><?= date('d F Y', strtotime($kredit['updated_at'] ?? 'now')) ?></div>
                <div class="timeline-content">Keputusan: <?= esc($kredit['status_kredit'] ?? 'Pending') ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Mengetahui,</div>
            <div style="font-weight: bold;">Ketua Koperasi</div>
            <div class="signature-line"></div>
            <div>(...........................)</div>
        </div>
        <div class="signature-box">
            <div><?= date('d F Y') ?></div>
            <div style="font-weight: bold;">Appraiser</div>
            <div class="signature-line"></div>
            <div>(...........................)</div>
        </div>
    </div>

    <?php else: ?>
    <!-- Data Not Found -->
    <div class="section" style="text-align: center; padding: 50px 0;">
        <h3>Data Tidak Ditemukan</h3>
        <p>Riwayat penilaian yang diminta tidak dapat ditemukan.</p>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Kredit Koperasi</p>
        <p>Tanggal cetak: <?= date('d F Y H:i:s') ?></p>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>