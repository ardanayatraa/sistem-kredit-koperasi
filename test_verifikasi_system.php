<!DOCTYPE html>
<html>
<head>
    <title>Test Sistem Verifikasi Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        .test-section { border: 1px solid #ddd; margin: 20px 0; padding: 20px; }
        h2 { color: #333; }
    </style>
</head>
<body>
    <h1>Test Sistem Verifikasi Pembayaran Angsuran</h1>
    
    <div class="test-section">
        <h2>✅ Komponen yang Telah Dibuat:</h2>
        <ul>
            <li class="success">✓ Tampilan verifikasi detail pembayaran (<code>app/Views/pembayaran_angsuran/verifikasi_detail.php</code>)</li>
            <li class="success">✓ Controller methods untuk verifikasi:
                <ul>
                    <li><code>verifikasiDetail($id)</code> - Menampilkan detail pembayaran untuk verifikasi</li>
                    <li><code>verifikasiApprove($id)</code> - Approve pembayaran via AJAX</li>
                    <li><code>verifikasiReject($id)</code> - Reject pembayaran via AJAX</li>
                </ul>
            </li>
            <li class="success">✓ Model telah diupdate untuk support join dengan tabel verifikator</li>
            <li class="success">✓ Tampilan show telah diupdate untuk menampilkan status dan verifikator</li>
            <li class="success">✓ Routes telah ditambahkan untuk semua method verifikasi</li>
        </ul>
    </div>
    
    <div class="test-section">
        <h2>🔧 Fitur Sistem Verifikasi:</h2>
        <ol>
            <li><strong>Status Verifikasi:</strong>
                <ul>
                    <li><span class="info">pending</span> - Belum diverifikasi (default)</li>
                    <li><span class="success">approved</span> - Telah disetujui</li>
                    <li><span class="error">rejected</span> - Ditolak</li>
                </ul>
            </li>
            <li><strong>Update Otomatis Status Angsuran:</strong>
                <ul>
                    <li>Saat pembayaran diapprove, sistem akan menghitung total pembayaran yang sudah diverifikasi</li>
                    <li>Jika total >= jumlah angsuran: status angsuran menjadi "Lunas"</li>
                    <li>Jika total < jumlah angsuran: status angsuran menjadi "Bayar Sebagian"</li>
                </ul>
            </li>
            <li><strong>Pencatatan Verifikator:</strong>
                <ul>
                    <li>ID user yang melakukan verifikasi disimpan di field <code>id_bendahara_verifikator</code></li>
                    <li>Nama verifikator ditampilkan di detail pembayaran</li>
                </ul>
            </li>
            <li><strong>Alasan Penolakan:</strong>
                <ul>
                    <li>Jika ditolak, alasan penolakan disimpan di field <code>keterangan</code></li>
                </ul>
            </li>
        </ol>
    </div>
    
    <div class="test-section">
        <h2>🌐 URL Routes untuk Testing:</h2>
        <ul>
            <li><code>/pembayaran-angsuran/verifikasi</code> - Dashboard verifikasi (list pending)</li>
            <li><code>/pembayaran-angsuran/verifikasi-detail/{id}</code> - Detail verifikasi pembayaran</li>
            <li><code>/pembayaran-angsuran/show/{id}</code> - Detail pembayaran (dengan status verifikasi)</li>
        </ul>
    </div>
    
    <div class="test-section">
        <h2>📋 Langkah Testing Manual:</h2>
        <ol>
            <li><strong>Login sebagai Bendahara atau Ketua</strong></li>
            <li><strong>Akses halaman verifikasi:</strong> <code>/pembayaran-angsuran/verifikasi</code></li>
            <li><strong>Pilih pembayaran yang pending verifikasi</strong></li>
            <li><strong>Klik detail untuk melihat informasi lengkap</strong></li>
            <li><strong>Test approve:</strong> Klik tombol "Setujui Pembayaran"</li>
            <li><strong>Test reject:</strong> Klik tombol "Tolak Pembayaran" dan isi alasan</li>
            <li><strong>Verifikasi update database:</strong> Cek status verifikasi dan id verifikator tersimpan</li>
            <li><strong>Cek update status angsuran:</strong> Pastikan status angsuran berubah sesuai total pembayaran</li>
        </ol>
    </div>
    
    <div class="test-section">
        <h2>🔍 Cek Database Manual:</h2>
        <pre><code>
-- Cek pembayaran yang sudah diverifikasi
SELECT pa.*, u.nama_lengkap as verifikator, a.status_pembayaran 
FROM tbl_pembayaran_angsuran pa
LEFT JOIN tbl_users u ON u.id_user = pa.id_bendahara_verifikator  
LEFT JOIN tbl_angsuran a ON a.id_angsuran = pa.id_angsuran
WHERE pa.status_verifikasi != 'pending';

-- Cek total pembayaran per angsuran
SELECT pa.id_angsuran, 
       SUM(CASE WHEN pa.status_verifikasi = 'approved' THEN pa.jumlah_bayar ELSE 0 END) as total_approved,
       a.jumlah_angsuran,
       a.status_pembayaran
FROM tbl_pembayaran_angsuran pa
LEFT JOIN tbl_angsuran a ON a.id_angsuran = pa.id_angsuran
GROUP BY pa.id_angsuran;
        </code></pre>
    </div>
    
    <div class="test-section">
        <h2>✨ Perbaikan yang Telah Dilakukan:</h2>
        <ul>
            <li class="success">✓ Fixed "Undefined array key 'status'" error dengan menggunakan <code>status_kredit</code></li>
            <li class="success">✓ Updated URLs dari underscore ke hyphen format</li>
            <li class="success">✓ Added comprehensive verification system</li>
            <li class="success">✓ Added automatic payment status calculation</li>
            <li class="success">✓ Added verifier information tracking</li>
            <li class="success">✓ Added consistent UI styling</li>
        </ul>
    </div>
    
    <p><strong>Status:</strong> <span class="success">✅ Sistem verifikasi pembayaran sudah siap untuk testing!</span></p>
</body>
</html>