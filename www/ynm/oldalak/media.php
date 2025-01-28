<link href="/css/ynm.media.css" rel="stylesheet">
  <div class="container-fluid">
        <h2>Movier Request TCL List</h2>
        <div class="table-container">
            <table class="table table-striped table-responsive">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cím</th>
						<th>Év</th>
                        <th>PIN</th>
						<th>Kérte</th>
                        <th>Kérés Időpontja</th>
                        <th>Állapot</th>
						<th>Teljesítés Dátuma</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Kapcsolat létrehozása az adatbázissal
                    $conn = new mysqli('localhost', 'ai', 'Ai@19811031', 'moviedb');

                    // Kapcsolat ellenőrzése
                    if ($conn->connect_error) {
                        die("Kapcsolati hiba: " . $conn->connect_error);
                    }

 // Adatok lekérése, függőben lévő filmek legyenek elöl
$sql = "SELECT * FROM movies ORDER BY status DESC, id DESC";  // Az 'Igen' érték lesz elöl, majd az id szerint csökkenő sorrendben
// $sql = "SELECT * FROM movies";  // Ha alapértelmezett rendezést akarsz
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Sorok bejárása
    while ($movie = $result->fetch_assoc()) {
        // Sor osztályának beállítása az állapot alapján
        $rowClass = ($movie['status'] === 'Igen') ? 'row-completed' : 'row-pending';
        echo "<tr class=\"$rowClass\">";
        echo "<td>{$movie['id']}</td>";
        echo "<td>{$movie['title']}</td>";
        echo "<td>{$movie['year']}</td>";
        echo "<td>{$movie['pin']}</td>";
        echo "<td>{$movie['requested_by']}</td>";
        echo "<td>{$movie['upload_date']}</td>";
        echo "<td class=\"" . ($movie['status'] === 'Igen' ? 'status-completed' : 'status-pending') . "\">" . ($movie['status'] === 'Igen' ? 'Teljesítve' : 'Függőben') . "</td>";
        echo "<td>" . ($movie['date_completed'] ?? 'N/A') . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>Nincsenek filmek az adatbázisban.</td></tr>";
}

// Kapcsolat lezárása
$conn->close();
?>
                </tbody>
            </table>
        </div>
    </div>