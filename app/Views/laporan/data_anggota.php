<?= $this->extend('laporan/template') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <h2>Laporan Data Anggota</h2>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIK</th>
            <th>Nama Anggota</th>
            <th>Tempat, Tanggal Lahir</th>
            <th>Alamat</th>
            <th>Pekerjaan</th>
            <th>Tanggal Pendaftaran</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($anggota as $index => $item): ?>
        <tr>
            <td><?= $index + 1 ?></td>
            <td><?= esc($item['nik']) ?></td>
            <td><?= esc($item['tempat_lahir']) ?>, <?= date('d/m/Y', strtotime($item['tanggal_lahir'])) ?></td>
            <td><?= esc($item['alamat']) ?></td>
            <td><?= esc($item['pekerjaan']) ?></td>
            <td><?= date('d/m/Y', strtotime($item['tanggal_pendaftaran'])) ?></td>
            <td><?= esc($item['status_keanggotaan']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?= $this->endSection() ?>