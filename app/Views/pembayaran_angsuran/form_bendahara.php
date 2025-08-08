<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Pembayaran Angsuran - Bendahara</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?= form_open('pembayaran-angsuran/store', ['id' => 'pembayaranForm']) ?>
                    
                    <!-- Step 1: Pilih Anggota -->
                    <div class="form-group">
                        <label for="anggota_id">Pilih Anggota *</label>
                        <select name="anggota_id" id="anggota_id" class="form-control" required>
                            <option value="">-- Pilih Anggota --</option>
                            <?php foreach ($anggota_list as $anggota): ?>
                                <option value="<?= $anggota['id'] ?>"><?= esc($anggota['nama']) ?> (<?= esc($anggota['nomor_anggota']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Step 2: Pilih Angsuran -->
                    <div class="form-group">
                        <label for="angsuran_id">Pilih Angsuran *</label>
                        <select name="angsuran_id" id="angsuran_id" class="form-control" required disabled>
                            <option value="">-- Pilih anggota terlebih dahulu --</option>
                        </select>
                    </div>

                    <!-- Detail Angsuran - akan muncul setelah pilih angsuran -->
                    <div id="detail_angsuran" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Kredit:</label>
                                    <input type="text" id="nomor_kredit" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Angsuran ke:</label>
                                    <input type="text" id="angsuran_ke" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Jatuh Tempo:</label>
                                    <input type="text" id="tanggal_jatuh_tempo" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jumlah Angsuran:</label>
                                    <input type="text" id="jumlah_angsuran" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Denda (jika ada):</label>
                                    <input type="text" id="denda" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label><strong>Total Harus Bayar:</strong></label>
                                    <input type="text" id="total_bayar" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pembayaran -->
                    <div id="form_pembayaran" style="display: none;">
                        <hr>
                        <h5>Data Pembayaran</h5>
                        
                        <div class="form-group">
                            <label for="jumlah_bayar">Jumlah Bayar *</label>
                            <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control" step="0.01" required>
                            <small class="form-text text-muted">Masukkan jumlah yang dibayarkan</small>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_bayar">Tanggal Bayar *</label>
                            <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="form-group">
                            <label for="metode_pembayaran">Metode Pembayaran *</label>
                            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="cek">Cek</option>
                                <option value="bilyet_giro">Bilyet Giro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
                        </div>

                        <!-- Info Pembayaran -->
                        <div class="alert alert-info" id="info_pembayaran" style="display: none;">
                            <h6>Informasi Pembayaran:</h6>
                            <div id="status_pembayaran_text"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Proses Pembayaran
                            </button>
                            <a href="<?= base_url('pembayaran-angsuran') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Event handler untuk pilih anggota
    $('#anggota_id').change(function() {
        var anggotaId = $(this).val();
        
        if (anggotaId) {
            // Reset form
            $('#angsuran_id').prop('disabled', false).html('<option value="">Loading...</option>');
            $('#detail_angsuran, #form_pembayaran').hide();
            
            // Load angsuran berdasarkan anggota
            $.ajax({
                url: '<?= base_url('pembayaran-angsuran/get-angsuran-by-anggota') ?>',
                type: 'POST',
                data: { anggota_id: anggotaId },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data.length > 0) {
                        var options = '<option value="">-- Pilih Angsuran --</option>';
                        response.data.forEach(function(item) {
                            var status = item.status === 'belum_bayar' ? 'Belum Bayar' : 
                                       item.status === 'sebagian' ? 'Bayar Sebagian' : 'Lunas';
                            var tanggal = new Date(item.tanggal_jatuh_tempo).toLocaleDateString('id-ID');
                            
                            options += '<option value="' + item.id + '">' +
                                      'Angsuran ke-' + item.angsuran_ke + 
                                      ' (' + status + ') - Rp ' + new Intl.NumberFormat('id-ID').format(item.jumlah_angsuran) +
                                      ' (Jatuh tempo: ' + tanggal + ')</option>';
                        });
                        $('#angsuran_id').html(options);
                    } else {
                        $('#angsuran_id').html('<option value="">Tidak ada angsuran yang harus dibayar</option>');
                    }
                },
                error: function() {
                    $('#angsuran_id').html('<option value="">Error loading data</option>');
                }
            });
        } else {
            $('#angsuran_id').prop('disabled', true).html('<option value="">-- Pilih anggota terlebih dahulu --</option>');
            $('#detail_angsuran, #form_pembayaran').hide();
        }
    });

    // Event handler untuk pilih angsuran
    $('#angsuran_id').change(function() {
        var angsuranId = $(this).val();
        
        if (angsuranId) {
            // Load detail angsuran
            $.ajax({
                url: '<?= base_url('pembayaran-angsuran/get-detail-angsuran') ?>',
                type: 'POST',
                data: { angsuran_id: angsuranId },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data) {
                        var data = response.data;
                        
                        // Isi detail angsuran
                        $('#nomor_kredit').val(data.nomor_kredit);
                        $('#angsuran_ke').val(data.angsuran_ke);
                        $('#tanggal_jatuh_tempo').val(new Date(data.tanggal_jatuh_tempo).toLocaleDateString('id-ID'));
                        $('#jumlah_angsuran').val('Rp ' + new Intl.NumberFormat('id-ID').format(data.jumlah_angsuran));
                        $('#denda').val('Rp ' + new Intl.NumberFormat('id-ID').format(data.denda || 0));
                        
                        var totalBayar = parseFloat(data.jumlah_angsuran) + parseFloat(data.denda || 0);
                        $('#total_bayar').val('Rp ' + new Intl.NumberFormat('id-ID').format(totalBayar));
                        
                        // Set default jumlah bayar
                        $('#jumlah_bayar').val(totalBayar);
                        
                        // Tampilkan detail dan form pembayaran
                        $('#detail_angsuran, #form_pembayaran').show();
                    }
                }
            });
        } else {
            $('#detail_angsuran, #form_pembayaran').hide();
        }
    });

    // Event handler untuk jumlah bayar
    $('#jumlah_bayar').on('input', function() {
        var jumlahBayar = parseFloat($(this).val()) || 0;
        var totalHarusBayar = parseFloat($('#total_bayar').val().replace(/[^0-9.-]+/g, "")) || 0;
        
        var infoDiv = $('#info_pembayaran');
        var statusText = $('#status_pembayaran_text');
        
        if (jumlahBayar > 0) {
            if (jumlahBayar >= totalHarusBayar) {
                statusText.html('<strong>Status:</strong> Lunas<br><strong>Kembalian:</strong> Rp ' + 
                               new Intl.NumberFormat('id-ID').format(jumlahBayar - totalHarusBayar));
                infoDiv.removeClass('alert-warning').addClass('alert-success').show();
            } else {
                var sisa = totalHarusBayar - jumlahBayar;
                statusText.html('<strong>Status:</strong> Bayar Sebagian<br><strong>Sisa:</strong> Rp ' + 
                               new Intl.NumberFormat('id-ID').format(sisa));
                infoDiv.removeClass('alert-success').addClass('alert-warning').show();
            }
        } else {
            infoDiv.hide();
        }
    });

    // Validasi form sebelum submit
    $('#pembayaranForm').submit(function(e) {
        var jumlahBayar = parseFloat($('#jumlah_bayar').val()) || 0;
        
        if (jumlahBayar <= 0) {
            e.preventDefault();
            alert('Jumlah bayar harus lebih dari 0');
            return false;
        }
    });
});
</script>
<?= $this->endSection() ?>