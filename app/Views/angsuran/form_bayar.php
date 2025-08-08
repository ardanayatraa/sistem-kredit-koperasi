<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-credit-card"></i> Pembayaran Angsuran
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="/pembayaran/jadwal/<?= $angsuran['id_kredit_ref'] ?>">Jadwal</a></li>
                <li class="breadcrumb-item active">Bayar Angsuran</li>
            </ol>
        </nav>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- Detail Angsuran -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-info-circle"></i> Detail Angsuran
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Angsuran Ke:</strong></td>
                            <td><span class="badge badge-primary badge-lg"><?= $angsuran['angsuran_ke'] ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Angsuran:</strong></td>
                            <td class="text-primary font-weight-bold">Rp <?= number_format($angsuran['jumlah_angsuran'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Jatuh Tempo:</strong></td>
                            <td>
                                <?php
                                $tanggalTempo = date('d M Y', strtotime($angsuran['tgl_jatuh_tempo']));
                                $isOverdue = strtotime($angsuran['tgl_jatuh_tempo']) < strtotime('now');
                                ?>
                                <span class="badge badge-<?= $isOverdue ? 'danger' : 'warning' ?>">
                                    <?= $tanggalTempo ?> 
                                    <?= $isOverdue ? '(TERLAMBAT)' : '' ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Status:</strong></td>
                            <td><span class="badge badge-warning">Belum Lunas</span></td>
                        </tr>
                        <tr>
                            <td><strong>Sudah Dibayar:</strong></td>
                            <td>Rp <?= number_format($total_dibayar ?? 0, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Sisa Tagihan:</strong></td>
                            <td class="text-danger font-weight-bold">Rp <?= number_format($angsuran['jumlah_angsuran'] - ($total_dibayar ?? 0), 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pembayaran -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-money-bill-wave"></i> Form Pembayaran
            </h6>
        </div>
        <div class="card-body">
            <form action="/bayar-angsuran/proses/<?= $angsuran['id_angsuran'] ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Jumlah Bayar -->
                        <div class="form-group">
                            <label for="jumlah_bayar" class="font-weight-bold">
                                Jumlah Bayar <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" 
                                       class="form-control <?= isset($validation) && $validation->hasError('jumlah_bayar') ? 'is-invalid' : '' ?>"
                                       id="jumlah_bayar" 
                                       name="jumlah_bayar" 
                                       value="<?= number_format($angsuran['jumlah_angsuran'] - ($total_dibayar ?? 0), 0, ',', '.') ?>"
                                       placeholder="Masukkan jumlah pembayaran" 
                                       required>
                                <?php if (isset($validation) && $validation->hasError('jumlah_bayar')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('jumlah_bayar') ?></div>
                                <?php endif; ?>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Disarankan bayar penuh untuk menghindari denda keterlambatan
                            </small>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="form-group">
                            <label for="metode_pembayaran" class="font-weight-bold">
                                Metode Pembayaran <span class="text-danger">*</span>
                            </label>
                            <select class="form-control <?= isset($validation) && $validation->hasError('metode_pembayaran') ? 'is-invalid' : '' ?>"
                                    id="metode_pembayaran" 
                                    name="metode_pembayaran" 
                                    required>
                                <option value="">-- Pilih Metode Pembayaran --</option>
                                <option value="transfer" <?= old('metode_pembayaran') == 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                                <option value="tunai" <?= old('metode_pembayaran') == 'tunai' ? 'selected' : '' ?>>Tunai</option>
                                <option value="debit" <?= old('metode_pembayaran') == 'debit' ? 'selected' : '' ?>>Kartu Debit</option>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('metode_pembayaran')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('metode_pembayaran') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Bukti Pembayaran -->
                        <div class="form-group">
                            <label for="bukti_pembayaran" class="font-weight-bold">
                                Bukti Pembayaran <span class="text-danger">*</span>
                            </label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input <?= isset($validation) && $validation->hasError('bukti_pembayaran') ? 'is-invalid' : '' ?>"
                                       id="bukti_pembayaran" 
                                       name="bukti_pembayaran" 
                                       accept="image/*"
                                       required>
                                <label class="custom-file-label" for="bukti_pembayaran">Pilih file gambar...</label>
                                <?php if (isset($validation) && $validation->hasError('bukti_pembayaran')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('bukti_pembayaran') ?></div>
                                <?php endif; ?>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Upload foto struk/bukti transfer (JPG/PNG, max 2MB)
                            </small>
                        </div>

                        <!-- Keterangan -->
                        <div class="form-group">
                            <label for="keterangan" class="font-weight-bold">Keterangan</label>
                            <textarea class="form-control" 
                                      id="keterangan" 
                                      name="keterangan" 
                                      rows="3"
                                      placeholder="Keterangan tambahan (opsional)"><?= old('keterangan') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Warning Box -->
                <div class="alert alert-warning" role="alert">
                    <h6><i class="fas fa-exclamation-triangle"></i> <strong>Perhatian!</strong></h6>
                    <ul class="mb-0">
                        <li>Pastikan bukti pembayaran yang diupload jelas dan dapat dibaca</li>
                        <li>Pembayaran akan diverifikasi oleh admin dalam 1x24 jam</li>
                        <li>Simpan bukti pembayaran asli sampai angsuran selesai</li>
                        <?php if ($isOverdue): ?>
                        <li class="text-danger"><strong>Pembayaran Anda sudah terlambat. Mungkin dikenakan denda.</strong></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Form Actions -->
                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-success mr-2">
                            <i class="fas fa-paper-plane"></i> Kirim Pembayaran
                        </button>
                        <a href="/pembayaran/jadwal/<?= $angsuran['id_kredit_ref'] ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Jadwal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Format currency input
document.getElementById('jumlah_bayar').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d]/g, '');
    if (value) {
        e.target.value = new Intl.NumberFormat('id-ID').format(value);
    }
});

// Remove formatting before form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const jumlahBayar = document.getElementById('jumlah_bayar');
    jumlahBayar.value = jumlahBayar.value.replace(/[^\d]/g, '');
});

// Custom file input label
document.getElementById('bukti_pembayaran').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'Pilih file gambar...';
    e.target.nextElementSibling.textContent = fileName;
});

// Auto-select full payment amount button
function bayarPenuh() {
    const sisaTagihan = <?= $angsuran['jumlah_angsuran'] - ($total_dibayar ?? 0) ?>;
    document.getElementById('jumlah_bayar').value = new Intl.NumberFormat('id-ID').format(sisaTagihan);
}
</script>
<?= $this->endSection() ?>