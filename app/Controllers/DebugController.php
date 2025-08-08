<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\KreditModel;
use App\Models\PencairanModel;
use App\Models\AngsuranModel;

class DebugController extends BaseController
{
    public function checkAnggotaData()
    {
        // Models
        $userModel = new UserModel();
        $kreditModel = new KreditModel();
        $pencairanModel = new PencairanModel();
        $angsuranModel = new AngsuranModel();
        
        echo "<h1>DEBUG: Anggota Payment Issue</h1>";
        
        // Check anggota_demo user
        $user = $userModel->where('username', 'anggota_demo')->first();
        echo "<h2>1. User anggota_demo:</h2>";
        if ($user) {
            echo "<pre>";
            print_r($user);
            echo "</pre>";
            
            $idAnggota = $user['id_anggota_ref'];
            
            if ($idAnggota) {
                // Check kredit for this anggota
                $kredit = $kreditModel->where('id_anggota', $idAnggota)->findAll();
                echo "<h2>2. Kredit untuk anggota ID {$idAnggota}:</h2>";
                echo "<pre>";
                print_r($kredit);
                echo "</pre>";
                
                if ($kredit) {
                    foreach ($kredit as $k) {
                        $idKredit = $k['id_kredit'];
                        echo "<h3>Kredit ID: {$idKredit}</h3>";
                        echo "<p>Status: {$k['status_kredit']} | Status Aktif: {$k['status_aktif']}</p>";
                        
                        // Check pencairan
                        $pencairan = $pencairanModel->where('id_kredit', $idKredit)->findAll();
                        echo "<h4>Pencairan:</h4>";
                        echo "<pre>";
                        print_r($pencairan);
                        echo "</pre>";
                        
                        // Check angsuran
                        $angsuran = $angsuranModel->where('id_kredit_ref', $idKredit)->findAll();
                        echo "<h4>Angsuran Schedule:</h4>";
                        echo "<pre>";
                        print_r($angsuran);
                        echo "</pre>";
                        
                        // Check angsuran by anggota ID
                        $angsuranByAnggota = $angsuranModel->where('id_anggota', $idAnggota)->findAll();
                        echo "<h4>Angsuran by Anggota ID:</h4>";
                        echo "<pre>";
                        print_r($angsuranByAnggota);
                        echo "</pre>";
                        
                        echo "<hr>";
                    }
                }
            } else {
                echo "<p style='color:red;'>ERROR: User tidak memiliki id_anggota_ref!</p>";
            }
        } else {
            echo "<p style='color:red;'>ERROR: User anggota_demo tidak ditemukan!</p>";
        }
        
        echo "<h2>3. Route Test:</h2>";
        echo "<p><a href='" . base_url('angsuran/bayar') . "' target='_blank'>Test Payment Route</a></p>";
        
        echo "<h2>4. Raw Queries:</h2>";
        
        // Raw query check
        $db = \Config\Database::connect();
        
        echo "<h3>Users with anggota_demo:</h3>";
        $query = $db->query("SELECT * FROM users WHERE username = 'anggota_demo'");
        echo "<pre>";
        print_r($query->getResultArray());
        echo "</pre>";
        
        echo "<h3>All Kredit:</h3>";
        $query = $db->query("SELECT * FROM kredit WHERE id_anggota = 1");
        echo "<pre>";
        print_r($query->getResultArray());
        echo "</pre>";
        
        echo "<h3>All Pencairan:</h3>";
        $query = $db->query("SELECT * FROM pencairan");
        echo "<pre>";
        print_r($query->getResultArray());
        echo "</pre>";
        
        echo "<h3>All Angsuran:</h3>";
        $query = $db->query("SELECT * FROM angsuran WHERE id_anggota = 1");
        echo "<pre>";
        print_r($query->getResultArray());
        echo "</pre>";
    }
}