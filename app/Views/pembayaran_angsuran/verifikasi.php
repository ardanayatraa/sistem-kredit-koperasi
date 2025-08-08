<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Verifikasi Pembayaran' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card { box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .bukti-img { max-width: 100px; cursor: pointer; }
        .bukti-img:hover { opacity: 0.8; }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0"><?= $headerTitle ?></h1>
                        <p class="text-muted">Verifikasi pembayaran angsuran yang masuk</p>
                    </div>
                    <a href="/pembayaran-angsuran" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0"><?= count($pembayaran_pending) ?></h4>
                                        <p class="mb-0">Pembayaran Menunggu Verifikasi</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Pembayaran Pending -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list"></i> Daftar Pembayaran Pending
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($pembayaran_pending)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5>Tidak ada pembayaran yang perlu diverifikasi</h5>
                                <p class="text-muted">Semua pembayaran sudah diverifikasi</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Anggota</th>
                                            <th>ID Kredit</th>
                                            <th>Angsuran Ke</th>
                                            <th>Jumlah Bayar</th>
                                            <th>Denda</th>
                                            <th>Metode</th>
                                            <th>Bukti</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pembayaran_pending as $index => $pembayaran): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= date('d/m/Y', strtotime($pembayaran['tanggal_bayar'])) ?></td>
                                                <td>
                                                    <div>
                                                        <strong><?= esc($pembayaran['nama_lengkap']) ?></strong><br>
                                                        <small class="text-muted"><?= esc($pembayaran['no_anggota']) ?></small>
                                                    </div>
                                                </td>
                                                <td><?= esc($pembayaran['id_kredit']) ?></td>
                                                <td><?= $pembayaran['angsuran_ke'] ?></td>
                                                <td class="text-end">
                                                    <strong>Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></strong>
                                                </td>
                                                <td class="text-end">
                                                    <?php if ($pembayaran['denda'] > 0): ?>
                                                        <span class="text-danger">Rp <?= number_format($pembayaran['denda'], 0, ',', '.') ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info"><?= esc($pembayaran['metode_pembayaran']) ?></span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($pembayaran['bukti_pembayaran'])): ?>
                                                        <img src="/writable/uploads/pembayaran_angsuran/<?= esc($pembayaran['bukti_pembayaran']) ?>"
                                                             class="bukti-img img-thumbnail"
                                                             onclick="showBukti('<?= esc($pembayaran['bukti_pembayaran']) ?>')"
                                                             alt="Bukti">
                                                    <?php else: ?>
                                                        <span class="text-muted">Tidak ada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge status-pending">
                                                        <i class="fas fa-clock"></i> Pending
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-success btn-sm" 
                                                                onclick="verifikasiPembayaran(<?= $pembayaran['id_pembayaran'] ?>)"
                                                                title="Setujui">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-danger btn-sm" 
                                                                onclick="tolakPembayaran(<?= $pembayaran['id_pembayaran'] ?>)"
                                                                title="Tolak">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <button class="btn btn-info btn-sm" 
                                                                onclick="detailPembayaran(<?= $pembayaran['id_pembayaran'] ?>)"
                                                                title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bukti Pembayaran -->
    <div class="modal fade" id="buktiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="buktiImage" src="" class="img-fluid" alt="Bukti Pembayaran">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Pembayaran -->
    <div class="modal fade" id="tolakModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="tolakForm">
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Penolakan *</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3" 
                                      placeholder="Masukkan alasan penolakan pembayaran" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="konfirmasiTolak()">
                        <i class="fas fa-times"></i> Tolak Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentPembayaranId = null;

        function showBukti(filename) {
            document.getElementById('buktiImage').src = '/writable/uploads/pembayaran_angsuran/' + filename;
            new bootstrap.Modal(document.getElementById('buktiModal')).show();
        }

        function verifikasiPembayaran(id) {
            if (confirm('Anda yakin ingin menyetujui pembayaran ini?')) {
                fetch(`/pembayaran-angsuran/verifikasi-pembayaran/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses permintaan');
                });
            }
        }

        function tolakPembayaran(id) {
            currentPembayaranId = id;
            document.getElementById('alasan').value = '';
            new bootstrap.Modal(document.getElementById('tolakModal')).show();
        }

        function konfirmasiTolak() {
            const alasan = document.getElementById('alasan').value.trim();
            if (!alasan) {
                alert('Alasan penolakan harus diisi');
                return;
            }

            fetch(`/pembayaran-angsuran/tolak-pembayaran/${currentPembayaranId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    alasan: alasan
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    bootstrap.Modal.getInstance(document.getElementById('tolakModal')).hide();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses permintaan');
            });
        }

        function detailPembayaran(id) {
            window.open(`/pembayaran-angsuran/show/${id}`, '_blank');
        }
    </script>
</body>
</html>