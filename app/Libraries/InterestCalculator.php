<?php

namespace App\Libraries;

/**
 * Library untuk perhitungan bunga otomatis sistem kredit koperasi
 *
 * Library ini menyediakan berbagai metode perhitungan bunga yang umum digunakan
 * dalam sistem kredit koperasi:
 * - Bunga Flat: Bunga dihitung dari pokok awal, tetap setiap bulan
 * - Bunga Menurun: Bunga dihitung dari saldo menurun, berkurang setiap bulan
 * - Bunga Efektif (Anuitas): Angsuran tetap, proporsi bunga-pokok berubah
 *
 * Setiap method mengembalikan jadwal lengkap dengan rincian pokok, bunga,
 * dan saldo untuk setiap periode pembayaran.
 */
class InterestCalculator
{
    /**
     * Menghitung jadwal angsuran dengan sistem bunga flat
     *
     * Sistem bunga flat menghitung bunga berdasarkan pokok pinjaman awal
     * yang tidak berubah sepanjang periode. Bunga per bulan = (Pokok × Rate) / 12
     * Angsuran per bulan = (Pokok / Periode) + Bunga Bulanan
     *
     * Karakteristik:
     * - Bunga tetap setiap bulan
     * - Pokok angsuran tetap setiap bulan
     * - Total angsuran tetap setiap bulan
     *
     * @param float $principal Jumlah pokok pinjaman
     * @param float $rate Persentase bunga per tahun (contoh: 12 untuk 12%)
     * @param int $periods Jangka waktu dalam bulan
     * @return array Jadwal angsuran dengan rincian per periode
     */
    public static function calculateFlatInterest($principal, $rate, $periods)
    {
        $monthlyRate = $rate / 100 / 12; // Konversi ke bulanan
        $monthlyInterest = $principal * $monthlyRate;
        $monthlyPrincipal = $principal / $periods;
        $monthlyPayment = $monthlyPrincipal + $monthlyInterest;
        
        $schedule = [];
        $remainingBalance = $principal;
        
        for ($i = 1; $i <= $periods; $i++) {
            $interestAmount = $monthlyInterest;
            $principalAmount = $monthlyPrincipal;
            $remainingBalance -= $principalAmount;
            
            $schedule[] = [
                'angsuran_ke' => $i,
                'jumlah_angsuran' => round($monthlyPayment, 2),
                'pokok' => round($principalAmount, 2),
                'bunga' => round($interestAmount, 2),
                'saldo_akhir' => round(max(0, $remainingBalance), 2)
            ];
        }
        
        return $schedule;
    }
    
    /**
     * Menghitung jadwal angsuran dengan sistem bunga menurun (declining balance)
     *
     * Sistem bunga menurun menghitung bunga berdasarkan saldo pokok yang tersisa.
     * Setiap bulan saldo berkurang sehingga bunga juga berkurang.
     * Bunga per bulan = Saldo Tersisa × Rate Bulanan
     *
     * Karakteristik:
     * - Pokok angsuran tetap setiap bulan
     * - Bunga menurun setiap bulan
     * - Total angsuran menurun setiap bulan
     * - Lebih menguntungkan untuk nasabah
     *
     * @param float $principal Jumlah pokok pinjaman
     * @param float $rate Persentase bunga per tahun (contoh: 12 untuk 12%)
     * @param int $periods Jangka waktu dalam bulan
     * @return array Jadwal angsuran dengan rincian per periode
     */
    public static function calculateDecliningInterest($principal, $rate, $periods)
    {
        $monthlyRate = $rate / 100 / 12; // Konversi ke bulanan
        $monthlyPrincipal = $principal / $periods;
        
        $schedule = [];
        $remainingBalance = $principal;
        
        for ($i = 1; $i <= $periods; $i++) {
            $interestAmount = $remainingBalance * $monthlyRate;
            $principalAmount = $monthlyPrincipal;
            $monthlyPayment = $principalAmount + $interestAmount;
            $remainingBalance -= $principalAmount;
            
            $schedule[] = [
                'angsuran_ke' => $i,
                'jumlah_angsuran' => round($monthlyPayment, 2),
                'pokok' => round($principalAmount, 2),
                'bunga' => round($interestAmount, 2),
                'saldo_akhir' => round(max(0, $remainingBalance), 2)
            ];
        }
        
        return $schedule;
    }
    
    /**
     * Menghitung jadwal angsuran dengan sistem bunga efektif (anuitas)
     *
     * Sistem anuitas menghasilkan angsuran yang sama setiap bulan, tetapi
     * komposisi bunga dan pokok berubah. Di awal lebih banyak bunga,
     * di akhir lebih banyak pokok.
     *
     * Formula: PMT = P × [r(1+r)^n] / [(1+r)^n - 1]
     *
     * Karakteristik:
     * - Total angsuran tetap setiap bulan
     * - Proporsi bunga menurun setiap bulan
     * - Proporsi pokok meningkat setiap bulan
     * - Umum digunakan di perbankan modern
     *
     * @param float $principal Jumlah pokok pinjaman
     * @param float $rate Persentase bunga per tahun (contoh: 12 untuk 12%)
     * @param int $periods Jangka waktu dalam bulan
     * @return array Jadwal angsuran dengan rincian per periode
     */
    public static function calculateEffectiveInterest($principal, $rate, $periods)
    {
        $monthlyRate = $rate / 100 / 12; // Konversi ke bulanan
        
        // Rumus anuitas: PMT = P * [r(1+r)^n] / [(1+r)^n - 1]
        if ($monthlyRate == 0) {
            $monthlyPayment = $principal / $periods;
        } else {
            $monthlyPayment = $principal * ($monthlyRate * pow(1 + $monthlyRate, $periods)) / 
                             (pow(1 + $monthlyRate, $periods) - 1);
        }
        
        $schedule = [];
        $remainingBalance = $principal;
        
        for ($i = 1; $i <= $periods; $i++) {
            $interestAmount = $remainingBalance * $monthlyRate;
            $principalAmount = $monthlyPayment - $interestAmount;
            $remainingBalance -= $principalAmount;
            
            $schedule[] = [
                'angsuran_ke' => $i,
                'jumlah_angsuran' => round($monthlyPayment, 2),
                'pokok' => round($principalAmount, 2),
                'bunga' => round($interestAmount, 2),
                'saldo_akhir' => round(max(0, $remainingBalance), 2)
            ];
        }
        
        return $schedule;
    }
    
    /**
     * Menghitung jadwal angsuran berdasarkan tipe bunga yang dipilih
     *
     * Method utama yang menentukan jenis perhitungan bunga berdasarkan parameter.
     * Mendukung berbagai variasi nama untuk setiap tipe bunga.
     *
     * Tipe yang didukung:
     * - 'Flat': Bunga flat/tetap
     * - 'Menurun'/'Declining': Bunga menurun
     * - 'Efektif'/'Anuitas'/'Effective': Bunga efektif/anuitas
     *
     * @param float $principal Jumlah pokok pinjaman
     * @param float $rate Persentase bunga per tahun (contoh: 12 untuk 12%)
     * @param int $periods Jangka waktu dalam bulan
     * @param string $interestType Jenis bunga (default: 'Flat')
     * @return array Jadwal angsuran lengkap dengan rincian per periode
     */
    public static function calculateInstallmentSchedule($principal, $rate, $periods, $interestType = 'Flat')
    {
        switch (strtolower($interestType)) {
            case 'menurun':
            case 'declining':
                return self::calculateDecliningInterest($principal, $rate, $periods);
                
            case 'efektif':
            case 'anuitas':
            case 'effective':
                return self::calculateEffectiveInterest($principal, $rate, $periods);
                
            case 'flat':
            default:
                return self::calculateFlatInterest($principal, $rate, $periods);
        }
    }
    
    /**
     * Menambahkan tanggal jatuh tempo untuk setiap angsuran
     *
     * Method ini menambahkan kolom 'tgl_jatuh_tempo' ke setiap item dalam
     * jadwal angsuran berdasarkan tanggal pencairan. Tanggal jatuh tempo
     * dihitung dengan menambahkan jumlah bulan sesuai urutan angsuran.
     *
     * Contoh: Jika pencairan 1 Jan 2024, maka:
     * - Angsuran ke-1 jatuh tempo: 1 Feb 2024
     * - Angsuran ke-2 jatuh tempo: 1 Mar 2024, dst.
     *
     * @param string $startDate Tanggal pencairan (format Y-m-d)
     * @param int $periods Jumlah periode (tidak digunakan, bisa dihapus)
     * @param array $schedule Jadwal angsuran dari method calculate*
     * @return array Jadwal angsuran dengan tanggal jatuh tempo
     */
    public static function generateDueDates($startDate, $periods, $schedule)
    {
        $start = new \DateTime($startDate);
        
        foreach ($schedule as $key => $installment) {
            $dueDate = clone $start;
            $dueDate->add(new \DateInterval('P' . $installment['angsuran_ke'] . 'M'));
            $schedule[$key]['tgl_jatuh_tempo'] = $dueDate->format('Y-m-d');
        }
        
        return $schedule;
    }
    
    /**
     * Menghitung ringkasan total dari jadwal angsuran
     *
     * Method ini menghitung berbagai total dari jadwal angsuran yang sudah dibuat,
     * berguna untuk menampilkan ringkasan kepada nasabah tentang total kewajiban
     * dan rincian pembayaran selama periode kredit.
     *
     * @param array $schedule Jadwal angsuran lengkap dari method calculate*
     * @return array Array dengan total_pembayaran, total_bunga, total_pokok, jumlah_periode
     */
    public static function calculateTotals($schedule)
    {
        $totalPayment = 0;
        $totalInterest = 0;
        $totalPrincipal = 0;
        
        foreach ($schedule as $installment) {
            $totalPayment += $installment['jumlah_angsuran'];
            $totalInterest += $installment['bunga'];
            $totalPrincipal += $installment['pokok'];
        }
        
        return [
            'total_pembayaran' => round($totalPayment, 2),
            'total_bunga' => round($totalInterest, 2),
            'total_pokok' => round($totalPrincipal, 2),
            'jumlah_periode' => count($schedule)
        ];
    }
}