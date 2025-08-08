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
                        <i class="fas fa-crown text-success"></i> 
                        Persetujuan Final - Ketua Koperasi
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-success">Step 3: Persetujuan Final</span>
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
                                <i class="fas fa-search text-success"></i> Appraiser ✓ → 
                                <i class="fas fa-crown text-warning"></i> <strong>Ketua (Sedang Proses)</strong> → 
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

                    <!-- Ringkasan Pengajuan -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Data Anggota</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
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
                                            <td><strong>Status</strong></td>
                                            <td>: <span class="badge badge-success"><?= esc($anggota['status_keanggotaan']) ?></span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Detail Pengajuan</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Tanggal</strong></td>
                                            <td>: <?= date('d/m/Y', strtotime($kredit['tanggal_pengajuan'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jumlah</strong></td>
                                            <td>: <span class="text-success"><strong>Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?></strong></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jangka Waktu</strong></td>
                                            <td>: <?= $kredit['jangka_waktu'] ?> bulan</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tujuan</strong></td>
                                            <td>: <?= esc($kredit['tujuan_kredit']) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card card-outline card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Penilaian Agunan</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Jenis</strong></td>
                                            <td>: <?= esc($kredit['jenis_agunan']) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nilai Taksir</strong></td>
                                            <td>: <span class="text-primary"><strong>Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?></strong></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>LTV Ratio</strong></td>
                                            <td>: 
                                                <?php 
                                                $ltvRatio = ($kredit['jumlah_pengajuan'] / $kredit['nilai_taksiran_agunan']) * 100;
                                                $ltvClass = $ltvRatio <= 80 ? 'text-success' : ($ltvRatio <= 90 ? 'text-warning' : 'text-danger');
                                                ?>
                                                <span class="<?= $ltvClass ?>"><strong><?= number_format($ltvRatio, 2) ?>%</strong></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>: <span class="badge badge-info"><?= esc($kredit['status_kredit']) ?></span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Proses -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-check-circle"></i> 
                                        Hasil Verifikasi Bendahara
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-success">
                                        <strong>Status:</strong> Diverifikasi dan Diterima<br>
                                        <strong>Tanggal:</strong> 
                                        <?php if (!empty($kredit['tanggal_verifikasi_bendahara'])): ?>
                                            <?= date('d/m/Y H:i', strtotime($kredit['tanggal_verifikasi_bendahara'])) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif ?>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label><strong>Catatan Bendahara:</strong></label>
                                        <p class="form-control-static border rounded p-2 bg-light"><?= esc($kredit['catatan_bendahara'] ?? 'Tidak ada catatan') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-calculator"></i> 
                                        Hasil Penilaian Appraiser
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <strong>Status:</strong> Dinilai dan Direkomendasikan<br>
                                        <strong>Tanggal:</strong> 
                                        <?php if (!empty($kredit['tanggal_penilaian_appraiser'])): ?>
                                            <?= date('d/m/Y H:i', strtotime($kredit['tanggal_penilaian_appraiser'])) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif ?>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label><strong>Catatan Appraiser:</strong></label>
                                        <p class="form-control-static border rounded p-2 bg-light"><?= esc($kredit['catatan_appraiser'] ?? 'Tidak ada catatan') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Pendukung -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-file-alt"></i> 
                                        Dokumen Pendukung
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?php if (!empty($anggota['dokumen_ktp'])): ?>
                                                <a href="/<?= $anggota['dokumen_ktp'] ?>" target="_blank" class="btn btn-sm btn-info btn-block">
                                                    <i class="fas fa-id-card"></i> Lihat KTP
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary btn-block" disabled>
                                                    <i class="fas fa-times"></i> KTP N/A
                                                </button>
                                            <?php endif ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if (!empty($anggota['dokumen_slip_gaji'])): ?>
                                                <a href="/<?= $anggota['dokumen_slip_gaji'] ?>" target="_blank" class="btn btn-sm btn-info btn-block">
                                                    <i class="fas fa-money-check"></i> Lihat Slip Gaji
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary btn-block" disabled>
                                                    <i class="fas fa-times"></i> Slip Gaji N/A
                                                </button>
                                            <?php endif ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?php if (!empty($kredit['dokumen_agunan'])): ?>
                                                <a href="/<?= $kredit['dokumen_agunan'] ?>" target="_blank" class="btn btn-sm btn-primary btn-block">
                                                    <i class="fas fa-file-pdf"></i> Lihat Dokumen Agunan
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary btn-block" disabled>
                                                    <i class="fas fa-times"></i> Dokumen Agunan N/A
                                                </button>
                                            <?php endif ?>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-sm btn-warning btn-block" onclick="window.print()">
                                                <i class="fas fa-print"></i> Print Ringkasan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Persetujuan Final -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-gavel"></i> 
                                        Form Keputusan Final Ketua Koperasi
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Perhatian!</strong> Keputusan ini bersifat final dan tidak dapat diubah setelah disimpan.
                                    </div>

                                    <form method="POST">
                                        <?= csrf_field() ?>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="keputusan_final">Keputusan Final <span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-lg" id="keputusan_final" name="keputusan_final" required>
                                                        <option value="">-- Pilih Keputusan Final --</option>
                                                        <option value="Disetujui" class="text-success">✓ DISETUJUI - Kredit Siap Dicairkan</option>
                                                        <option value="Ditolak" class="text-danger">✗ DITOLAK - Tidak Memenuhi Kebijakan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Ringkasan Analisis</label>
                                                    <div class="border rounded p-2 bg-light">
                                                        <small>
                                                            <strong>Jumlah Kredit:</strong> Rp <?= number_format($kredit['jumlah_pengajuan'], 0, ',', '.') ?><br>
                                                            <strong>Nilai Agunan:</strong> Rp <?= number_format($kredit['nilai_taksiran_agunan'], 0, ',', '.') ?><br>
                                                            <strong>LTV Ratio:</strong> <?= number_format($ltvRatio, 2) ?>% 
                                                            <?php if ($ltvRatio <= 80): ?>
                                                                <span class="badge badge-success">Rendah</span>
                                                            <?php elseif ($ltvRatio <= 90): ?>
                                                                <span class="badge badge-warning">Sedang</span>
                                                            <?php else: ?>
                                                                <span class="badge badge-danger">Tinggi</span>
                                                            <?php endif ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="catatan_ketua">Catatan dan Alasan Keputusan <span class="text-danger">*</span></label>
                                            <textarea class="form-control" id="catatan_ketua" name="catatan_ketua" rows="5" required placeholder="Berikan alasan dan pertimbangan keputusan final..."></textarea>
                                            <small class="form-text text-muted">
                                                Jelaskan pertimbangan dalam mengambil keputusan berdasarkan verifikasi bendahara, penilaian appraiser, dan kebijakan koperasi
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-success btn-lg btn-block">
                                                        <i class="fas fa-gavel"></i> Simpan Keputusan Final
                                                    </button>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="/kredit/pengajuan-untuk-role" class="btn btn-secondary btn-lg btn-block">
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
<?= $this->endSection() ?>