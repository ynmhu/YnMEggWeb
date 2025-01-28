<?php
// Start a session
session_start();

// Töröljük az összes session változót
session_unset();

// Megszüntetjük a session-t
session_destroy();

// Átirányítjuk a felhasználót a főoldalra vagy a bejelentkezési oldalra
header("Location: /index");  // Cseréld le a megfelelő URL-re
exit();
?>