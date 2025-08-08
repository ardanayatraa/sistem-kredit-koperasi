<?php

try {
    // Database config from .env
    $dbConfig = [
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'koperasi-baru'
    ];
    
    $pdo = new PDO(
        "mysql:host={$dbConfig['hostname']};dbname={$dbConfig['database']}", 
        $dbConfig['username'], 
        $dbConfig['password']
    );
    
    echo "=== DEBUG CHECK DATA ===\n\n";
    
    // Check users table
    $stmt = $pdo->prepare("SELECT user_id, username, nama_lengkap, id_anggota_ref FROM users WHERE username = 'anggota_demo'");
    $stmt->execute();
    $users = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "User anggota_demo: ";
    print_r($users);
    echo "\n";
    
    if ($users && $users['id_anggota_ref']) {
        $idAnggota = $users['id_anggota_ref'];
        
        // Check kredit for this anggota
        $stmt = $pdo->prepare("SELECT * FROM kredit WHERE id_anggota = ?");
        $stmt->execute([$idAnggota]);
        $kredit = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Kredit data for anggota ID $idAnggota:\n";
        print_r($kredit);
        echo "\n";
        
        if ($kredit) {
            foreach ($kredit as $k) {
                $idKredit = $k['id_kredit'];
                
                // Check pencairan for this kredit
                $stmt = $pdo->prepare("SELECT * FROM pencairan WHERE id_kredit = ?");
                $stmt->execute([$idKredit]);
                $pencairan = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "Pencairan data for kredit ID $idKredit:\n";
                print_r($pencairan);
                echo "\n";
                
                // Check angsuran for this kredit
                $stmt = $pdo->prepare("SELECT * FROM angsuran WHERE id_kredit = ?");
                $stmt->execute([$idKredit]);
                $angsuran = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "Angsuran data for kredit ID $idKredit:\n";
                print_r($angsuran);
                echo "\n";
            }
        }
        
        // Check angsuran by anggota ID directly
        $stmt = $pdo->prepare("SELECT * FROM angsuran WHERE id_anggota = ?");
        $stmt->execute([$idAnggota]);
        $angsuranByAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Angsuran data by anggota ID $idAnggota:\n";
        print_r($angsuranByAnggota);
        echo "\n";
        
        // Check payment permissions in users table
        $stmt = $pdo->prepare("SELECT permission_keys FROM users WHERE username = 'anggota_demo'");
        $stmt->execute();
        $permissions = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "User permissions:\n";
        print_r($permissions);
        echo "\n";
    }
    
    echo "=== END DEBUG ===\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}