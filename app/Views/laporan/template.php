<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Laporan Koperasi' ?></title>
    <style>
        /* CSS untuk kop surat */
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 2px 0;
            font-size: 14px;
        }
        .kop-surat .alamat {
            font-size: 12px;
        }
        
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .signature {
            margin-top: 60px;
        }
        
        /* Content styles */
        .content-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .content-header h2 {
            margin: 0;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h1>SERBA USAHA MITRA SEJAHTERA</h1>
        <p class="alamat">Jl. Contoh Alamat No. 123, Jakarta</p>
        <p>Telp: (021) 12345678  Email: info@sumasejahtera.co.id</p>
    </div>
    
    <?= $this->renderSection('content') ?>
    
    <div class="footer">
        <p>Jakarta, <?= date('d F Y') ?></p>
        <div class="signature">
            <p>(_______________________)</p>
            <p>Nama Petugas</p>
        </div>
    </div>
</body>
</html>