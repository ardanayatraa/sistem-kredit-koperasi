<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Simulasi Bunga Kredit</h1>
                <p class="text-gray-600">Hitung simulasi angsuran kredit berdasarkan jumlah kredit, bunga, dan jangka waktu.</p>
            </div>
        </div>
    </div>

    <!-- Simulation Form -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Input Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Parameter Simulasi</h3>
            <form id="simulasiForm" class="space-y-4">
                <div>
                    <label for="jumlah_kredit" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kredit</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input type="number" id="jumlah_kredit" name="jumlah_kredit" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="0" min="1000000" max="500000000" step="100000">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimum Rp 1.000.000, Maksimum Rp 500.000.000</p>
                </div>

                <div>
                    <label for="bunga" class="block text-sm font-medium text-gray-700 mb-2">Bunga per Tahun (%)</label>
                    <input type="number" id="bunga" name="bunga" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="0" min="0.1" max="50" step="0.1">
                    <p class="text-xs text-gray-500 mt-1">Bunga flat per tahun (contoh: 12 untuk 12%)</p>
                </div>

                <div>
                    <label for="jangka_waktu" class="block text-sm font-medium text-gray-700 mb-2">Jangka Waktu (Bulan)</label>
                    <select id="jangka_waktu" name="jangka_waktu" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Jangka Waktu</option>
                        <option value="6">6 Bulan</option>
                        <option value="12">12 Bulan (1 Tahun)</option>
                        <option value="18">18 Bulan</option>
                        <option value="24">24 Bulan (2 Tahun)</option>
                        <option value="36">36 Bulan (3 Tahun)</option>
                        <option value="48">48 Bulan (4 Tahun)</option>
                        <option value="60">60 Bulan (5 Tahun)</option>
                    </select>
                </div>

                <div>
                    <label for="metode_bunga" class="block text-sm font-medium text-gray-700 mb-2">Metode Bunga</label>
                    <select id="metode_bunga" name="metode_bunga" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="flat">Bunga Flat</option>
                        <option value="efektif">Bunga Efektif (Anuitas)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Bunga flat: sama setiap bulan. Efektif: menurun seiring waktu</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Hitung Simulasi
                </button>
            </form>
        </div>

        <!-- Result Display -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Hasil Simulasi</h3>
            <div id="simulasiResult" class="space-y-4 hidden">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="text-sm font-medium text-blue-600">Angsuran per Bulan</div>
                        <div id="angsuran_bulanan" class="text-2xl font-bold text-blue-900">-</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-sm font-medium text-green-600">Total Bunga</div>
                        <div id="total_bunga" class="text-xl font-semibold text-green-900">-</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm font-medium text-gray-600">Total Pembayaran</div>
                        <div id="total_pembayaran" class="text-xl font-semibold text-gray-900">-</div>
                    </div>
                </div>

                <!-- Detailed Info -->
                <div class="border-t pt-4">
                    <h4 class="font-medium text-gray-900 mb-3">Detail Perhitungan</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pokok Kredit:</span>
                            <span id="detail_pokok" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Bunga per Tahun:</span>
                            <span id="detail_bunga" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jangka Waktu:</span>
                            <span id="detail_jangka" class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Metode:</span>
                            <span id="detail_metode" class="font-medium">-</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t pt-4 space-x-3">
                    <button onclick="printSimulation()" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                    <button onclick="resetSimulation()" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                        Reset
                    </button>
                </div>
            </div>

            <div id="simulasiPlaceholder" class="text-center py-12 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <p>Masukkan parameter kredit untuk melihat simulasi</p>
            </div>
        </div>
    </div>

    <!-- Table Simulasi Angsuran (Hidden by default) -->
    <div id="tabelAngsuran" class="bg-white rounded-lg shadow overflow-hidden hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Tabel Angsuran</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angsuran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pokok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bunga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Hutang</th>
                    </tr>
                </thead>
                <tbody id="tabelAngsuranBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="bg-yellow-50 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Catatan Penting</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Simulasi ini hanya untuk estimasi. Angsuran aktual dapat berbeda berdasarkan kebijakan koperasi.</li>
                        <li>Bunga flat artinya bunga dihitung dari pokok awal selama seluruh periode.</li>
                        <li>Bunga efektif artinya bunga dihitung dari sisa pokok yang belum dibayar.</li>
                        <li>Simulasi belum termasuk biaya administrasi atau biaya lainnya.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('simulasiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const jumlahKredit = parseFloat(document.getElementById('jumlah_kredit').value);
    const bunga = parseFloat(document.getElementById('bunga').value);
    const jangkaWaktu = parseInt(document.getElementById('jangka_waktu').value);
    const metodeBunga = document.getElementById('metode_bunga').value;
    
    if (!jumlahKredit || !bunga || !jangkaWaktu) {
        alert('Mohon lengkapi semua field');
        return;
    }
    
    let angsuranBulanan, totalBunga, totalPembayaran;
    
    if (metodeBunga === 'flat') {
        // Bunga Flat
        const bungaBulanan = (jumlahKredit * bunga / 100) / 12;
        const pokokBulanan = jumlahKredit / jangkaWaktu;
        angsuranBulanan = pokokBulanan + bungaBulanan;
        totalBunga = bungaBulanan * jangkaWaktu;
        totalPembayaran = jumlahKredit + totalBunga;
    } else {
        // Bunga Efektif (Anuitas)
        const r = bunga / 100 / 12; // bunga bulanan
        const n = jangkaWaktu;
        angsuranBulanan = jumlahKredit * (r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
        totalPembayaran = angsuranBulanan * jangkaWaktu;
        totalBunga = totalPembayaran - jumlahKredit;
    }
    
    // Update hasil
    document.getElementById('angsuran_bulanan').textContent = formatRupiah(angsuranBulanan);
    document.getElementById('total_bunga').textContent = formatRupiah(totalBunga);
    document.getElementById('total_pembayaran').textContent = formatRupiah(totalPembayaran);
    
    document.getElementById('detail_pokok').textContent = formatRupiah(jumlahKredit);
    document.getElementById('detail_bunga').textContent = bunga + '% per tahun';
    document.getElementById('detail_jangka').textContent = jangkaWaktu + ' bulan';
    document.getElementById('detail_metode').textContent = metodeBunga === 'flat' ? 'Bunga Flat' : 'Bunga Efektif';
    
    // Show hasil
    document.getElementById('simulasiPlaceholder').classList.add('hidden');
    document.getElementById('simulasiResult').classList.remove('hidden');
    
    // Generate tabel angsuran
    generateTabelAngsuran(jumlahKredit, bunga, jangkaWaktu, metodeBunga);
});

function generateTabelAngsuran(jumlahKredit, bunga, jangkaWaktu, metodeBunga) {
    const tbody = document.getElementById('tabelAngsuranBody');
    tbody.innerHTML = '';
    
    let sisaHutang = jumlahKredit;
    
    if (metodeBunga === 'flat') {
        const bungaBulanan = (jumlahKredit * bunga / 100) / 12;
        const pokokBulanan = jumlahKredit / jangkaWaktu;
        const angsuranBulanan = pokokBulanan + bungaBulanan;
        
        for (let i = 1; i <= jangkaWaktu; i++) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${i}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatRupiah(angsuranBulanan)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatRupiah(pokokBulanan)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatRupiah(bungaBulanan)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatRupiah(sisaHutang - pokokBulanan)}</td>
            `;
            tbody.appendChild(row);
            sisaHutang -= pokokBulanan;
        }
    } else {
        const r = bunga / 100 / 12;
        const angsuranBulanan = jumlahKredit * (r * Math.pow(1 + r, jangkaWaktu)) / (Math.pow(1 + r, jangkaWaktu) - 1);
        
        for (let i = 1; i <= jangkaWaktu; i++) {
            const bungaBulanan = sisaHutang * r;
            const pokokBulanan = angsuranBulanan - bungaBulanan;
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${i}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatRupiah(angsuranBulanan)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatRupiah(pokokBulanan)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatRupiah(bungaBulanan)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatRupiah(sisaHutang - pokokBulanan)}</td>
            `;
            tbody.appendChild(row);
            sisaHutang -= pokokBulanan;
        }
    }
    
    document.getElementById('tabelAngsuran').classList.remove('hidden');
}

function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

function resetSimulation() {
    document.getElementById('simulasiForm').reset();
    document.getElementById('simulasiResult').classList.add('hidden');
    document.getElementById('simulasiPlaceholder').classList.remove('hidden');
    document.getElementById('tabelAngsuran').classList.add('hidden');
}

function printSimulation() {
    window.print();
}

// Format input as user types
document.getElementById('jumlah_kredit').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    e.target.value = value;
});
</script>

<style>
@media print {
    .no-print {
        display: none;
    }
}
</style>
<?= $this->endSection() ?>