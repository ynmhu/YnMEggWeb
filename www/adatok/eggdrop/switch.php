<?php

session_start();
if (isset($_GET['activate'])) {
    $arg = (int) $_GET['activate']; // Paraméter biztonságos integer értékké alakítása

    // Kérjük le a botot a paraméterrel
    $prep = $conn->prepare("SELECT * FROM bots WHERE user_id=:userid AND id=:botid");
    $prep->bindValue(":userid", $_SESSION['user_id'], PDO::PARAM_INT);
    $prep->bindValue(":botid", $arg, PDO::PARAM_INT);
    $prep->execute();
    $results = $prep->fetchAll(PDO::FETCH_ASSOC);

    // Ha nem található a bot, átirányítjuk a felhasználót
    if (empty($results)) {
        header("Location: /adatbazis/");
        exit;
    }

    // Beállítjuk az aktív bot nevét session változóban
    $_SESSION['botname'] = $results[0]['name'];

    // Az összes botot inaktívvá tesszük
    $prep = $conn->prepare("UPDATE bots SET active=0 WHERE user_id=:userid");
    $prep->bindValue(":userid", $_SESSION['user_id'], PDO::PARAM_INT);
    $prep->execute();

    // Az aktív botot beállítjuk
    $prep = $conn->prepare("UPDATE bots SET active=1 WHERE id=:botid");
    $prep->bindValue(":botid", $arg, PDO::PARAM_INT);
    $prep->execute();

    // Beállítjuk a session változót az aktív bot ID-jával
    $_SESSION['bot_id'] = $arg;

    // Visszairányítjuk a felhasználót a /adatbazis/ oldalra
    header("Location: /adatbazis/");
    exit;
} else {
    // Ha nincs paraméter, visszairányítjuk a felhasználót
    header("Location: /adatbazis/");
    exit;
}
?>
Tszzt

