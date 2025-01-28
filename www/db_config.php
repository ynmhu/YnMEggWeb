<?php
// Adatbázis kapcsolódási beállítások
$db = [
    'host' => 'localhost',
    'name' => 'ynmegg',
    'user' => 'ai',
    'pass' => ''
];

try {
    // Kapcsolódás az adatbázishoz PDO használatával
    $pdo = new PDO(
        'mysql:host=' . $db['host'] . ';dbname=' . $db['name'], 
        $db['user'], 
        $db['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Hibakezelés
} catch (PDOException $e) {
    echo 'Adatbázis hiba: ' . $e->getMessage();
    exit;
}

// E-mail beállítások
$mail = [
    'smtp' => 'ynm.hu',
    'username' => 'ynm',
    'password' => '',
    'encryption' => 'tls',
    'port' => 587,
    'from' => 'ynm@ynm.hu',
    'name' => 'YnM Egg Web'
];

// reCAPTCHA beállítások
$recaptcha = [
    'enabled' => 0,                        // 0 = kikapcsolva, 1 = bekapcsolva
    'public' => "",                        // Google reCAPTCHA nyilvános kulcs
    'secret' => ""                         // Google reCAPTCHA titkos kulcs
];
?>
