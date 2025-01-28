<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$signupAvailable = true;

// Véletlenszerű karaktersor generálása
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Konfigurációs fájl betöltése
$configFile = "db_config.php";
if (!file_exists($configFile)) {
    die("A konfigurációs fájl nem található.");
}
include $configFile;

// Ellenőrizzük, hogy a szükséges adatbázis beállítások elérhetők
if (!isset($db['host']) || !isset($db['name']) || !isset($db['user']) || !isset($db['pass'])) {
    die('Hibás konfigurációs fájl.');
}

// DSN létrehozása
$dsn = "mysql:host=" . $db['host'] . ";dbname=" . $db['name'];

// Adatbázis kapcsolat létrehozása
try {
    $conn = new PDO($dsn, $db['user'], $db['pass']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Az adatbázis kapcsolat sikeres.";
} catch (PDOException $e) {
    echo 'Adatbázis hiba: ' . $e->getMessage();
    exit;
}
?>
