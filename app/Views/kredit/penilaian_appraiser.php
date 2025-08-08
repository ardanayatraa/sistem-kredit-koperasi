<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-search text-info"></i> 
                        Penilaian Appraiser - Agunan Kredit
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-info">Step 2: Penilaian Agunan</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- ALUR WORKFLOW KOPERASI MITRA SEJAHTRA -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Alur Pengajuan Kredit Koperasi Mitra Sejahtra:</strong><br>
                                <i class="fas fa-user text-success"></i> Anggota ✓ → 
                                <i class="fas fa-user-tie text-success"></i> Bendahara ✓ → 
                                <i class="fas fa-search text-info"></i> <strong>Appraiser (Sedang Proses)</strong> → 
                                <i class="fas fa-crown text-muted"></i> Ketua → 
                                <i class="fas fa-check-circle text-muted"></i> Selesai
                            </div>
                        </div>
                    </div>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    <?php endif ?>

                    <!-- Detail Pengajuan dan Verifikasi Sebelumnya -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Data Anggota & Pengajuan</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Nama</strong></td>
                                            <td>: <?= esc($anggota['nama']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>No. Anggota</strong></td>
                                            <td>: <?= esc($anggota['no_anggota']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pekerjaan</strong></td>
                                            <td>: <?= esc($anggota['pekerjaan']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Pengajuan</strong></td>
                                            <td>: <?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jumlah Kredit</strong></td>
                                            <td>: <span class="text-success"><strong>Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></strong></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jangka Waktu</strong></td>
                                            <td>: <?= $kredit['jangka_waktu'] ?> bulan</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tujuan Kredit</strong></td>
                                            <td>: <?= esc($kredit['tujuan_kredit']) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Hasil Verifikasi Bendahara -->
                            <div class="card card-outline card-success mt-3">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-check-circle"></i> 
                                        Hasil Verifikasi Bendahara
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-success">
                                        <strong>Status:</strong> Diverifikasi Bendahara<br>
                                        <strong>Tanggal Verifikasi:</strong> 
                                        <?php if (!empty($kredit['tanggal_verifikasi_bendahara'])): ?>
                                            <?= date('d/m/Y H:i', strtotime($kredit['tanggal_verifikasi_bendahara'])) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif ?>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Catatan Bendahara:</strong></label>
                                        <p class="form-control-static"><?= esc($kredit['catatan_bendahara'] ?? 'Tidak ada catatan') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Data Agunan -->
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-home"></i> 
                                        Data Agunan
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Jenis Agunan</strong></td>
                                            <td>: <?= esc($kredit['jenis_agunan']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nilai Taksiran Saat Ini</strong></td>
                                            <td>: 
                                                <?php if (!empty($kredit['nilai_taksiran_agunan'])): ?>
                                                    <span class="text-primary">Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Belum dinilai</span>
                                                <?php endif ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>: <span class="badge badge-info"><?= esc($kredit['status_kredit']) ?></span></td>
                                        </tr>
                                    </table>

                                    <!-- Dokumen Agunan -->
                                    <div class="mt-3">
                                        <h6><strong>Dokumen Agunan:</strong></h6>
                                        <?php if (!empty($kredit['dokumen_agunan'])): ?>
                                            <a href="/<?= $kredit['dokumen_agunan'] ?>" target="_blank" class="btn btn-sm btn-primary btn-block">
                                                <i class="fas fa-file-pdf"></i> Lihat Dokumen Agunan
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary btn-block" disabled>
                                                <i class="fas fa-times"></i> Dokumen Agunan Tidak Ada
                                            </button>
                                        <?php endif ?>
                                    </div>

                                    <!-- LTV Ratio Calculator -->
                                    <div class="mt-4">
                                        <h6><strong>Kalkulator LTV Ratio:</strong></h6>
                                        <div class="alert alert-light">
                                            <small>
                                                <strong>Loan to Value (LTV):</strong><br>
                                                Jumlah Kredit: <span id="jumlah-kredit">Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></span><br>
                                                Nilai Agunan: <span id="nilai-agunan">Rp 0</span><br>
                                                <strong>LTV Ratio: <span id="ltv-ratio" class="text-primary">0%</span></strong><br>
                                                <small class="text-muted">Rekomendasi LTV maksimal 80%</small>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Penilaian Appraiser -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-calculator"></i> 
                                        Form Penilaian Agunan
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <?= csrf_field() ?>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nilai_taksiran_agunan">Nilai Taksiran Agunan <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="number" class="form-control" id="nilai_taksiran_agunan" name="nilai_taksiran_agunan" required min="1" placeholder="0" onkeyup="calculateLTV()">
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        Masukkan hasil penilaian agunan berdasarkan survey lapangan
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="rekomendasi_appraiser">Rekomendasi Appraiser <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="rekomendasi_appraiser" name="rekomendasi_appraiser" required>
                                                        <option value="">-- Pilih Rekomendasi --</option>
                                                        <option value="Disetujui">Disetujui - Teruskan ke Ketua</option>
                                                        <option value="Ditolak">Ditolak - Agunan Tidak Memenuhi Syarat</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="catatan_appraiser">Catatan Penilaian Agunan <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="catatan_appraiser" name="catatan_appraiser" rows="5" required placeholder="Berikan detail penilaian agunan, kondisi fisik, lokasi, nilai pasar, dan rekomendasi..."></textarea>
                                            <small class="form-text text-muted">
                                                Jelaskan secara detail hasil survey agunan, kondisi fisik, lokasi, nilai pasar saat ini, dan alasan rekomendasi
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-success btn-block">
                                                        <i class="fas fa-check"></i> Simpan Penilaian Agunan
                                                    </button>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="/kredit/pengajuan-untuk-role" class="btn btn-secondary btn-block">
                                                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculateLTV() {
    const jumlahKredit = <?= $kredit['jumlah_pengajuan'] ?>;
    const nilaiAgunan = parseFloat(document.getElementById('nilai_taksiran_agunan').value) || 0;
    
    if (nilaiAgunan > 0) {
        const ltvRatio = (jumlahKredit / nilaiAgunan * 100).toFixed(2);
        document.getElementById('nilai-agunan').textContent = 'Rp ' + nilaiAgunan.toLocaleString('id-ID');
        document.getElementById('ltv-ratio').textContent = ltvRatio + '%';
        
        // Color coding for LTV ratio
        const ltvElement = document.getElementById('ltv-ratio');
        if (ltvRatio <= 80) {
            ltvElement.className = 'text-success';
        } else if (ltvRatio <= 90) {
            ltvElement.className = 'text-warning';
        } else {
            ltvElement.className = 'text-danger';
        }
    } else {
        document.getElementById('nilai-agunan').textContent = 'Rp 0';
        document.getElementById('ltv-ratio').textContent = '0%';
        document.getElementById('ltv-ratio').className = 'text-primary';
    }
}
</script>

<?= $this->endSection() ?>