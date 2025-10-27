<?php
$currentUserLevel = session()->get('level');
$canEditBendaharaFields = $currentUserLevel === 'Bendahara';
$canEditAppraiserFields = $currentUserLevel === 'Appraiser';
$canEditKetuaFields = $currentUserLevel === 'Ketua Koperasi';
$canEditAllFields = $currentUserLevel === 'Admin';

// Jika sedang edit, ambil data anggota dari $anggota yang dikirim controller
$anggotaData = isset($anggota) ? $anggota : null;

?>

<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="w-full mx-auto mt-4">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-900">
                <?= isset($kredit) ? 'Edit Kredit' : 'Tambah Kredit Baru' ?>
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                <?= isset($kredit) ? 'Perbarui informasi pengajuan kredit' : 'Lengkapi form untuk menambah pengajuan kredit baru' ?>
            </p>
            <?php if ($currentUserLevel && $currentUserLevel !== 'Admin'): ?>
                <p class="text-xs text-gray-500 mt-2">
                    Anda login sebagai: <span class="font-medium"><?= $currentUserLevel ?></span>
                    <?php if ($canEditBendaharaFields): ?>
                        | Anda dapat mengedit catatan bendahara
                    <?php elseif ($canEditAppraiserFields): ?>
                        | Anda dapat mengedit catatan penilai dan nilai taksiran agunan
                    <?php elseif ($canEditKetuaFields): ?>
                        | Anda dapat mengedit catatan ketua
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>

        <form action="<?= isset($kredit) ? '/kredit/update/' . esc($kredit['id_kredit']) : '/kredit/create' ?>" method="post" enctype="multipart/form-data" class="p-6 space-y-6">
            <?= csrf_field() ?>

            <!-- Data Pengajuan Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Data Pengajuan
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="id_anggota" class="block text-sm font-medium text-gray-700 mb-2">
                            ID Anggota <span class="text-red-500">*</span>
                        </label>
                        <?php
                        $idAnggotaValue = old('id_anggota', $kredit['id_anggota'] ?? ($userAnggotaId ?? ''));
                        $isReadonly = isset($userAnggotaId) && !empty($userAnggotaId);
                        ?>
                        <input type="number"
                               name="id_anggota"
                               id="id_anggota"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?= $isReadonly ? 'bg-gray-100' : '' ?>"
                               value="<?= $idAnggotaValue ?>"
                               placeholder="<?= $isReadonly ? 'ID Anggota Anda' : 'Masukkan ID anggota' ?>"
                               <?= $isReadonly ? 'readonly' : '' ?>
                               required>
                        <?php if ($isReadonly): ?>
                            <p class="text-green-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-check-circle h-4 w-4"></i>
                                ID Anggota terisi otomatis dari profil Anda
                            </p>
                        <?php endif; ?>

                        <!-- Tampilkan info anggota jika sedang edit -->
                        <?php if (isset($kredit) && $anggotaData): ?>
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <i class="bx bx-user text-blue-600 h-4 w-4"></i>
                                    <span class="text-sm font-medium text-blue-900">Data Anggota:</span>
                                </div>
                                <div class="mt-1 text-sm text-blue-800">
                                    <p><strong>Nama:</strong> <?= esc($anggotaData['nama_lengkap'] ?? 'N/A') ?></p>
                                    <p><strong>No. Anggota:</strong> <?= esc($anggotaData['no_anggota'] ?? 'N/A') ?></p>
                                    <p><strong>Alamat:</strong> <?= esc($anggotaData['alamat'] ?? 'N/A') ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (session('errors.id_anggota')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-exclamation-circle h-4 w-4"></i>
                                <?= session('errors.id_anggota') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="tanggal_pengajuan" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pengajuan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_pengajuan" 
                               id="tanggal_pengajuan" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('tanggal_pengajuan', $kredit['tanggal_pengajuan'] ?? date('Y-m-d')) ?>" 
                               required>
                        <?php if (session('errors.tanggal_pengajuan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.tanggal_pengajuan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="jumlah_pengajuan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Pengajuan (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="jumlah_pengajuan"
                               id="jumlah_pengajuan"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               value="<?= old('jumlah_pengajuan', $kredit['jumlah_pengajuan'] ?? '') ?>"
                               placeholder="Contoh: 5.000.000 (ketik angka saja, titik otomatis)"
                               pattern="[0-9,.]+"
                               inputmode="numeric"
                               required>
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="bx bx-info-circle mr-1"></i>
                            Ketik angka saja, format titik akan ditambahkan otomatis
                        </p>
                        <?php if (session('errors.jumlah_pengajuan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.jumlah_pengajuan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="jangka_waktu" class="block text-sm font-medium text-gray-700 mb-2">
                            Jangka Waktu (bulan) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="jangka_waktu" 
                               id="jangka_waktu" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="<?= old('jangka_waktu', $kredit['jangka_waktu'] ?? '') ?>" 
                               placeholder="Contoh: 12"
                               required>
                        <?php if (session('errors.jangka_waktu')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.jangka_waktu') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label for="tujuan_kredit" class="block text-sm font-medium text-gray-700 mb-2">
                        Tujuan Kredit <span class="text-red-500">*</span>
                    </label>
                    <textarea name="tujuan_kredit" 
                              id="tujuan_kredit" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" 
                              placeholder="Jelaskan tujuan pengajuan kredit"
                              required><?= old('tujuan_kredit', $kredit['tujuan_kredit'] ?? '') ?></textarea>
                    <?php if (session('errors.tujuan_kredit')): ?>
                        <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <?= session('errors.tujuan_kredit') ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Data Agunan Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="bx bx-shield-alt text-green-600 h-5 w-5"></i>
                        Data Agunan
                        <?php if (session('level') === 'Appraiser'): ?>
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Area Appraiser</span>
                        <?php endif; ?>
                    </h3>
                    <?php if (session('level') === 'Appraiser'): ?>
                        <p class="text-sm text-green-600 mt-1"><i class="bx bx-check-circle mr-1"></i> Anda memiliki akses untuk verifikasi dan penilaian agunan</p>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="jenis_agunan" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Agunan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_agunan" 
                                id="jenis_agunan" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                required>
                            <option value="">Pilih Jenis Agunan</option>
                            <option value="Sertifikat Tanah" <?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') == 'Sertifikat Tanah' ? 'selected' : '' ?>>Sertifikat Tanah</option>
                            <option value="BPKB Kendaraan" <?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') == 'BPKB Kendaraan' ? 'selected' : '' ?>>BPKB Kendaraan</option>
                            <option value="Deposito" <?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') == 'Deposito' ? 'selected' : '' ?>>Deposito</option>
                            <option value="SK Pegawai" <?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') == 'SK Pegawai' ? 'selected' : '' ?>>SK Pegawai</option>
                            <option value="Lainnya" <?= old('jenis_agunan', $kredit['jenis_agunan'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                        <?php if (session('errors.jenis_agunan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.jenis_agunan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="nilai_taksiran_agunan" class="block text-sm font-medium text-gray-700 mb-2">
                            Nilai Taksiran Agunan (Rp)
                            <?php if (session('level') === 'Appraiser'): ?>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">TUGAS ANDA</span>
                            <?php else: ?>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full ml-2">Diisi Appraiser</span>
                            <?php endif; ?>
                        </label>
                        <input type="text"
                               name="nilai_taksiran_agunan"
                               id="nilai_taksiran_agunan"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?= session('level') === 'Appraiser' ? 'border-green-300 bg-green-50' : 'bg-gray-50' ?>"
                               value="<?= old('nilai_taksiran_agunan', $kredit['nilai_taksiran_agunan'] ?? '') ?>"
                               placeholder="<?= session('level') === 'Appraiser' ? 'Masukkan hasil penilaian agunan' : 'Akan diisi oleh Appraiser setelah verifikasi' ?>"
                               pattern="[0-9,.]+"
                               inputmode="numeric"
                               <?= session('level') !== 'Appraiser' ? 'readonly' : '' ?>>
                        
                        <?php if (session('level') === 'Appraiser'): ?>
                            <p class="text-green-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-check-circle h-4 w-4"></i>
                                <strong>TUGAS ANDA:</strong> Kunjungi lokasi, verifikasi agunan, lalu isi nilai taksiran
                            </p>
                        <?php else: ?>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-2">
                                <p class="text-blue-800 text-sm flex items-start gap-2">
                                    <i class="bx bx-info-circle text-blue-600 mt-0.5"></i>
                                    <span>
                                        <strong>Yang mengisi:</strong> <span class="text-green-600 font-medium">Appraiser (Penilai Agunan)</span><br>
                                        <strong>Kapan diisi:</strong> Setelah verifikasi fisik agunan<br>
                                        <strong>Proses:</strong> Appraiser akan mengunjungi/menilai agunan yang Anda serahkan, lalu menentukan nilai wajar agunan tersebut
                                    </span>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (session('errors.nilai_taksiran_agunan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.nilai_taksiran_agunan') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Upload Dokumen Agunan -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="dokumen_agunan" class="block text-sm font-medium text-gray-700 mb-2">
                            Dokumen Agunan <span class="text-red-500">*</span>
                            <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full ml-2">Wajib Upload</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <input type="file"
                                    name="dokumen_agunan"
                                    id="dokumen_agunan"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    <?= !isset($kredit) ? 'required' : '' ?>>
                            <?php if (isset($kredit) && !empty($kredit['dokumen_agunan'])): ?>
                                <div class="mt-2 p-3 bg-gray-50 rounded-lg border">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Dokumen Agunan</p>
                                            <p class="text-xs text-gray-500">File saat ini: <?= basename($kredit['dokumen_agunan']) ?></p>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="/kredit/view-document/<?= esc($kredit['dokumen_agunan']) ?>" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                                <i class="bx bx-show h-3 w-3"></i>
                                                Lihat Dokumen
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            <p><strong>Jenis dokumen sesuai agunan:</strong></p>
                            <ul class="ml-4 mt-1 space-y-1">
                                <li>• <strong>Sertifikat Tanah:</strong> Scan sertifikat tanah asli</li>
                                <li>• <strong>BPKB Kendaraan:</strong> Scan BPKB asli</li>
                                <li>• <strong>Deposito:</strong> Scan bilyet deposito</li>
                                <li>• <strong>SK Pegawai:</strong> Scan SK pengangkatan</li>
                                <li>• <strong>Lainnya:</strong> Dokumen kepemilikan yang sah</li>
                            </ul>
                            <p class="mt-2 text-orange-600"><strong>Format:</strong> PDF, JPG, PNG (Max 5MB)</p>
                        </div>
                        <?php if (session('errors.dokumen_agunan')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.dokumen_agunan') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
           </div>

           <!-- Dokumen Anggota Section -->
           <div class="space-y-4">
               <div class="border-b border-gray-200 pb-2">
                   <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                       <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                       </svg>
                       Dokumen Anggota
                       <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">Tersedia</span>
                   </h3>
                   <p class="text-sm text-gray-600 mt-1">Dokumen KTP dan slip gaji diambil dari data anggota yang sudah ada di sistem</p>
               </div>

               <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                   <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                       <!-- KTP Info -->
                       <div class="bg-white border border-green-200 rounded-lg p-4">
                           <div class="flex items-center">
                               <div class="flex-shrink-0">
                                   <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                   </svg>
                               </div>
                               <div class="ml-3 flex-1">
                                   <p class="text-sm font-medium text-gray-900">📄 KTP</p>
                                   <p class="text-sm text-gray-500">Tersedia dari profil anggota</p>
                               </div>
                               <div class="ml-auto flex gap-2">
                                   <button type="button" onclick="previewAnggotaDocument('ktp', '<?= esc($userAnggotaId ?? session()->get('id_anggota_ref') ?? '') ?>')" class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                       <i class="bx bx-show h-3 w-3"></i>
                                       Preview
                                   </button>
                                   <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                       ✓ Tersedia
                                   </span>
                               </div>
                           </div>
                       </div>

                       <!-- Slip Gaji Info -->
                       <div class="bg-white border border-green-200 rounded-lg p-4">
                           <div class="flex items-center">
                               <div class="flex-shrink-0">
                                   <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                   </svg>
                               </div>
                               <div class="ml-3 flex-1">
                                   <p class="text-sm font-medium text-gray-900">💰 Slip Gaji</p>
                                   <p class="text-sm text-gray-500">Tersedia dari profil anggota</p>
                               </div>
                               <div class="ml-auto flex gap-2">
                                   <button type="button" onclick="previewAnggotaDocument('slip_gaji', '<?= esc($userAnggotaId ?? session()->get('id_anggota_ref') ?? '') ?>')" class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                       <i class="bx bx-show h-3 w-3"></i>
                                       Preview
                                   </button>
                                   <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                       ✓ Tersedia
                                   </span>
                               </div>
                           </div>
                       </div>
                   </div>
                   
                   <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                       <div class="flex">
                           <div class="flex-shrink-0">
                               <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                   <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                               </svg>
                           </div>
                           <div class="ml-3">
                               <p class="text-sm text-blue-700">
                                   <strong>Informasi:</strong> Dokumen KTP dan slip gaji akan digunakan dari data anggota yang sudah tersimpan di sistem.
                                   Pastikan data profil anggota sudah lengkap dan terkini.
                               </p>
                           </div>
                       </div>
                   </div>
               </div>
           </div>

           <!-- Catatan dan Status Section -->
            <div class="space-y-4">
                <div class="border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Catatan dan Status
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="catatan_bendahara" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Bendahara
                            <?php if ($canEditBendaharaFields || $canEditAllFields): ?>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full ml-2">Area Anda</span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full ml-2">Tersedia</span>
                            <?php endif; ?>
                        </label>
                        <textarea name="catatan_bendahara"
                                  id="catatan_bendahara"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none <?= !($canEditBendaharaFields || $canEditAllFields) ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                                  placeholder="Catatan dari bendahara"
                                  <?= !($canEditBendaharaFields || $canEditAllFields) ? 'readonly' : '' ?>><?= old('catatan_bendahara', $kredit['catatan_bendahara'] ?? '') ?></textarea>
                        <?php if (!($canEditBendaharaFields || $canEditAllFields)): ?>
                            <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-info-circle h-4 w-4"></i>
                                Hanya <i class="bx bx-user-tie mr-1"></i>Bendahara yang dapat mengedit catatan ini
                            </p>
                        <?php elseif ($canEditBendaharaFields || $canEditAllFields): ?>
                            <p class="text-blue-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-check-circle h-4 w-4"></i>
                                Tugas: <i class="bx bx-tasks mr-1"></i>Berikan catatan terkait verifikasi data anggota
                            </p>
                        <?php endif; ?>
                        <?php if (session('errors.catatan_bendahara')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.catatan_bendahara') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <?php if ($canEditAppraiserFields || $canEditAllFields || !empty($kredit['catatan_appraiser'])): ?>
                    <div>
                        <label for="catatan_appraiser" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Penilai (Appraiser)
                            <?php if ($canEditAppraiserFields): ?>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">TUGAS ANDA</span>
                            <?php elseif ($canEditAllFields): ?>
                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full ml-2">Admin</span>
                            <?php else: ?>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full ml-2">Hasil Verifikasi</span>
                            <?php endif; ?>
                        </label>
                        <textarea name="catatan_appraiser"
                                  id="catatan_appraiser"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none <?= $canEditAppraiserFields ? 'border-green-300 bg-green-50' : 'bg-gray-100' ?>"
                                  placeholder="<?= $canEditAppraiserFields ? 'Masukkan hasil verifikasi dan penilaian agunan' : 'Catatan hasil verifikasi akan muncul di sini' ?>"
                                  <?= !($canEditAppraiserFields || $canEditAllFields) ? 'readonly' : '' ?>><?= old('catatan_appraiser', $kredit['catatan_appraiser'] ?? '') ?></textarea>
                        
                        <?php if ($canEditAppraiserFields): ?>
                            <p class="text-green-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-check-circle h-4 w-4"></i>
                                <strong>TUGAS ANDA:</strong> Verifikasi dan nilai agunan berdasarkan jenis dan kondisi
                            </p>
                        <?php elseif (!empty($kredit['catatan_appraiser'])): ?>
                            <p class="text-blue-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-check-circle h-4 w-4"></i>
                                Catatan dari Appraiser setelah verifikasi agunan
                            </p>
                        <?php endif; ?>
                        
                        <?php if (session('errors.catatan_appraiser')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.catatan_appraiser') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div>
                        <label for="catatan_ketua" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Ketua
                            <?php if ($canEditKetuaFields || $canEditAllFields): ?>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full ml-2">Area Anda</span>
                            <?php else: ?>
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full ml-2">Tersedia</span>
                            <?php endif; ?>
                        </label>
                        <textarea name="catatan_ketua"
                                  id="catatan_ketua"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none <?= !($canEditKetuaFields || $canEditAllFields) ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                                  placeholder="Catatan dari ketua"
                                  <?= !($canEditKetuaFields || $canEditAllFields) ? 'readonly' : '' ?>><?= old('catatan_ketua', $kredit['catatan_ketua'] ?? '') ?></textarea>
                        <?php if (!($canEditKetuaFields || $canEditAllFields)): ?>
                            <p class="text-gray-500 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-info-circle h-4 w-4"></i>
                                Hanya <i class="bx bx-user-tie mr-1"></i>Ketua Koperasi yang dapat mengedit catatan ini
                            </p>
                        <?php elseif ($canEditKetuaFields || $canEditAllFields): ?>
                            <p class="text-purple-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-check-circle h-4 w-4"></i>
                                Tugas: <i class="bx bx-tasks mr-1"></i>Berikan keputusan akhir dan persetujuan kredit
                            </p>
                        <?php endif; ?>
                        <?php if (session('errors.catatan_ketua')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.catatan_ketua') ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="status_kredit" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Kredit <span class="text-red-500">*</span>
                            <?php if ($canEditAllFields): ?>
                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full ml-2">Admin</span>
                            <?php else: ?>
                                <span class="bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full ml-2">Auto-Set</span>
                            <?php endif; ?>
                        </label>
                        <?php
                        $defaultStatus = 'Diajukan';
                        $currentStatus = old('status_kredit', $kredit['status_kredit'] ?? $defaultStatus);
                        ?>
                        <select name="status_kredit"
                                id="status_kredit"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?= !($canEditAllFields) ? 'bg-gray-100 cursor-not-allowed' : '' ?>"
                                required
                                <?= !($canEditAllFields) ? 'disabled' : '' ?>>
                            <option value="Diajukan" <?= $currentStatus == 'Diajukan' ? 'selected' : '' ?>>Diajukan</option>
                            <?php if ($canEditAllFields): ?>
                                <option value="Pending" <?= $currentStatus == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Disetujui" <?= $currentStatus == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                                <option value="Ditolak" <?= $currentStatus == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                <option value="Berjalan" <?= $currentStatus == 'Berjalan' ? 'selected' : '' ?>>Berjalan</option>
                                <option value="Selesai" <?= $currentStatus == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            <?php endif; ?>
                        </select>
                        
                        <!-- Hidden input for non-admin users to ensure value is submitted -->
                        <?php if (!$canEditAllFields): ?>
                            <input type="hidden" name="status_kredit" value="<?= $defaultStatus ?>">
                        <?php endif; ?>
                        
                        <?php if (!($canEditAllFields)): ?>
                            <p class="text-orange-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-info-circle h-4 w-4"></i>
                                Status otomatis "Diajukan" untuk pengajuan baru
                            </p>
                        <?php elseif ($canEditAllFields): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <i class="bx bx-check-circle h-4 w-4"></i>
                                Tugas: <i class="bx bx-tasks mr-1"></i>Tetapkan status akhir pengajuan kredit
                            </p>
                        <?php endif; ?>
                        <?php if (session('errors.status_kredit')): ?>
                            <p class="text-red-600 text-sm mt-1 flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <?= session('errors.status_kredit') ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <a href="/kredit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <i class="bx bx-times h-4 w-4"></i>
                    Batal
                </a>
                <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                    <i class="bx bx-save h-4 w-4"></i>
                    <?= isset($kredit) ? 'Perbarui Data' : 'Simpan Data' ?>
                </button>
            </div>
        </form>
    </div>
</div>



// Enhanced currency input formatting
document.addEventListener('DOMContentLoaded', function() {
    const currencyInputs = document.querySelectorAll('#jumlah_pengajuan, #nilai_taksiran_agunan');

    currencyInputs.forEach(input => {
        // Format function
        const formatNumber = (value) => {
            // Only allow numbers
            let cleanValue = value.replace(/[^\d]/g, '');

            // Add thousand separators
            if (cleanValue.length > 0) {
                return cleanValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            return '';
        };

        // Format existing value on load
        if (input.value) {
            let cleanValue = input.value.replace(/[^\d]/g, '');
            input.value = formatNumber(cleanValue);
        }

        // Handle typing
        input.addEventListener('input', function(e) {
            let cursorPosition = this.selectionStart;
            let oldValue = this.value;
            let cleanValue = this.value.replace(/[^\d]/g, '');

            // Prevent if too long (max 15 digits for safety)
            if (cleanValue.length > 15) {
                cleanValue = cleanValue.substring(0, 15);
            }

            let newValue = formatNumber(cleanValue);
            this.value = newValue;

            // Adjust cursor position
            let diff = newValue.length - oldValue.length;
            this.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
        });

        // Prevent non-numeric input
        input.addEventListener('keypress', function(e) {
            // Allow: backspace, delete, tab, escape, enter
            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        // Show raw value in console for debugging
        input.addEventListener('blur', function() {
            let rawValue = this.value.replace(/[^\d]/g, '');
            console.log('Raw value:', rawValue);
        });
    });

    // Clean format before form submission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitting...');
            currencyInputs.forEach(input => {
                let cleanValue = input.value.replace(/[^\d]/g, '');
                console.log('Cleaning input:', input.name, 'from', input.value, 'to', cleanValue);
                input.value = cleanValue;
            });
        });
    }
});






</script>

<?= $this->endSection() ?>
