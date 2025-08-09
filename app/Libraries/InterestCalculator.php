<?php

namespace App\Libraries;

/**
 * Library untuk perhitungan bunga otomatis
 * Mendukung berbagai jenis bunga: Flat, Menurun (Declining), Efektif (Anuitas)
 */
class InterestCalculator
{
    /**
     * Hitung angsuran dengan bunga flat
     * 
     * @param float $principal Pokok pinjaman
     * @param float $rate Persentase bunga per tahun
     * @param int $periods Jangka waktu dalam bulan
     * @return array
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
     * Hitung angsuran dengan bunga menurun (declining balance)
     * 
     * @param float $principal Pokok pinjaman
     * @param float $rate Persentase bunga per tahun
     * @param int $periods Jangka waktu dalam bulan
     * @return array
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
     * Hitung angsuran dengan bunga efektif (anuitas)
     * 
     * @param float $principal Pokok pinjaman
     * @param float $rate Persentase bunga per tahun
     * @param int $periods Jangka waktu dalam bulan
     * @return array
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
     * Hitung jadwal angsuran berdasarkan tipe bunga
     * 
     * @param float $principal Pokok pinjaman
     * @param float $rate Persentase bunga per tahun
     * @param int $periods Jangka waktu dalam bulan
     * @param string $interestType Jenis bunga: 'Flat', 'Menurun', 'Efektif'
     * @return array
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
     * Generate tanggal jatuh tempo untuk setiap angsuran
     * 
     * @param string $startDate Tanggal mulai (format Y-m-d)
     * @param int $periods Jumlah periode
     * @param array $schedule Jadwal angsuran
     * @return array
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
     * Hitung total pembayaran dan total bunga
     * 
     * @param array $schedule Jadwal angsuran
     * @return array
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