<?php
$servername = "localhost";
$username = ""; // az AI felhasználó neve
$password = ""; // az AI felhasználó jelszava
$dbname = "ynmegg"; // az adatbázis neve

// Csatlakozás létrehozása
try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Ellenőrzés, hogy sikerült-e a csatlakozás
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $records_per_page = 10;
    $offset = ($page - 1) * $records_per_page;

    // SQL lekérdezés az oldalak kezelésére
    $sql = "SELECT * FROM bots WHERE active = ? LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $active, $offset, $records_per_page);
    $stmt->execute();
    $result = $stmt->get_result();

    // CSS stílusok a középre helyezéshez
    echo '<style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        h3 {
            color: #333;
        }
        .content {
            display: inline-block;
            text-align: left;
            padding: 20px;
        }
    </style>';

    echo '<div class="content">';

    if ($result->num_rows > 0) {
        echo "Találatok: " . $result->num_rows;
        // További adatok megjelenítése, ha vannak találatok
        while ($row = $result->fetch_assoc()) {
            echo "<br>ID: " . $row["id"] . " - Name: " . $row["name"];
        }
    } else {
        echo "<h3>Nincs aktív bot</h3>";
    }

    echo '</div>';

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo "Hiba történt: " . $e->getMessage();
    error_log($e->getMessage());
}
?>
