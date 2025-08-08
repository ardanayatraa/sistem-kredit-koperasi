<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-alt"></i> Jadwal Angsuran
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Jadwal Angsuran</li>
            </ol>
        </nav>
    </div>

    <!-- Info Kredit -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-info-circle"></i> Informasi Kredit
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID Kredit:</strong></td>
                            <td><?= $kredit['id_kredit'] ?></td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Kredit:</strong></td>
                            <td>Rp <?= number_format($kredit['jumlah_kredit'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Jangka Waktu:</strong></td>
                            <td><?= $kredit['jangka_waktu'] ?> bulan</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td><span class="badge badge-success"><?= $kredit['status_kredit'] ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Pengajuan:</strong></td>
                            <td><?= date('d M Y', strtotime($kredit['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tujuan Kredit:</strong></td>
                            <td><?= $kredit['tujuan_kredit'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Angsuran -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Jadwal Pembayaran Angsuran
            </h6>
            <div>
                <button class="btn btn-sm btn-success" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($jadwal_angsuran)): ?>
            <div class="text-center">
                <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                <h5>Jadwal Angsuran Belum Dibuat</h5>
                <p class="text-muted">Jadwal angsuran akan otomatis dibuat setelah pencairan dana.</p>
            </div>
            <?php else: ?>
            
            <!-- Summary -->
            <?php 
            $totalAngsuran = count($jadwal_angsuran);
            $totalLunas = 0;
            $totalTerlambat = 0;
            $totalBelumBayar = 0;
            
            foreach ($jadwal_angsuran as $angsuran) {
                if ($angsuran['status_realtime'] == 'Lunas') $totalLunas++;
                else if ($angsuran['status_realtime'] == 'Terlambat') $totalTerlambat++;
                else $totalBelumBayar++;
            }
            ?>
            
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-left-primary">
                        <div class="card-body py-2">
                            <div class="text-center">
                                <h4 class="font-weight-bold text-primary"><?= $totalAngsuran ?></h4>
                                <small>Total Angsuran</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-success">
                        <div class="card-body py-2">
                            <div class="text-center">
                                <h4 class="font-weight-bold text-success"><?= $totalLunas ?></h4>
                                <small>Sudah Lunas</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-danger">
                        <div class="card-body py-2">
                            <div class="text-center">
                                <h4 class="font-weight-bold text-danger"><?= $totalTerlambat ?></h4>
                                <small>Terlambat</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-left-warning">
                        <div class="card-body py-2">
                            <div class="text-center">
                                <h4 class="font-weight-bold text-warning"><?= $totalBelumBayar ?></h4>
                                <small>Belum Bayar</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Jadwal -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th width="10%">Angsuran Ke</th>
                            <th width="15%">Tanggal Jatuh Tempo</th>
                            <th width="15%">Jumlah Angsuran</th>
                            <th width="15%">Sudah Dibayar</th>
                            <th width="15%">Sisa</th>
                            <th width="15%">Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jadwal_angsuran as $angsuran): ?>
                        <?php 
                        $sisa = $angsuran['jumlah_angsuran'] - $angsuran['total_dibayar'];
                        $statusClass = 'secondary';
                        $statusIcon = 'fa-clock';
                        
                        if ($angsuran['status_realtime'] == 'Lunas') {
                            $statusClass = 'success';
                            $statusIcon = 'fa-check-circle';
                        } else if ($angsuran['status_realtime'] == 'Terlambat') {
                            $statusClass = 'danger';
                            $statusIcon = 'fa-exclamation-triangle';
                        } else if ($angsuran['status_realtime'] == 'Belum Bayar') {
                            $statusClass = 'warning';
                            $statusIcon = 'fa-clock';
                        }
                        ?>
                        <tr class="<?= $angsuran['status_realtime'] == 'Terlambat' ? 'table-danger' : '' ?>">
                            <td class="text-center">
                                <span class="badge badge-primary"><?= $angsuran['angsuran_ke'] ?></span>
                            </td>
                            <td><?= date('d M Y', strtotime($angsuran['tgl_jatuh_tempo'])) ?></td>
                            <td>Rp <?= number_format($angsuran['jumlah_angsuran'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($angsuran['total_dibayar'] > 0): ?>
                                <span class="text-success">Rp <?= number_format($angsuran['total_dibayar'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($sisa > 0): ?>
                                <span class="text-danger">Rp <?= number_format($sisa, 0, ',', '.') ?></span>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $statusClass ?>">
                                    <i class="fas <?= $statusIcon ?>"></i> <?= $angsuran['status_realtime'] ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($angsuran['status_realtime'] != 'Lunas'): ?>
                                <a href="/angsuran/bayar/<?= $angsuran['id_angsuran'] ?>" 
                                   class="btn btn-sm btn-<?= $angsuran['status_realtime'] == 'Terlambat' ? 'danger' : 'success' ?>">
                                    <i class="fas fa-credit-card"></i> Bayar
                                </a>
                                <?php else: ?>
                                <span class="text-success">
                                    <i class="fas fa-check"></i> Lunas
                                </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="row">
        <div class="col-12">
            <a href="/dashboard-pembayaran/<?= session('user_data')['id_anggota'] ?? '' ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .breadcrumb, .card-header .btn {
        display: none !important;
    }
}
</style>
<?= $this->endSection() ?>