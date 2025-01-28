<?php if (!defined("YnM Egg Web")) die; ?>
<h2 class="YnM-Stats">YnM Egg Web Demo Inside</h2>
 <?php
try {
    // Adatok lekérése az adatbázisból
	$stmt = $pdo->query('SELECT Id, user_id, name, active, Uptime, Ontime, Author, Server, Version, Channels, created_at FROM `bots`');
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Kártyák szülő konténerének kezdete
    echo '<div class="card-container">';

    // Kártyák generálása
	foreach ($rows as $row) {
	    $active = $row['active'];
$statusColor = ($active === '1' || $active === 1) ? 'green' : 'red'; // Online (zöld) vagy offline (piros)
    $blinkClass = ($active === '1' || $active === 1) ? 'blinking' : ''; // Pislogás csak akkor, ha online



        echo '<div class="card">';
        echo '  <div class="card-body">';
        echo '  <h5 class="text-center">Demo YnM-Egg-Web</h5>';
                echo '    <div class="card-row">';
        echo '      <span class="card-label">ID:</span>';
        echo '      <span class="card-value">' . htmlspecialchars($row['Id'] ?? 'N/A') . '</span>';
        echo '    </div>';
                echo '    <div class="card-row">';
        echo '      <span class="card-label">UserID:</span>';
        echo '      <span class="card-value">' . htmlspecialchars($row['user_id'] ?? 'N/A') . '</span>';
        echo '    </div>';
        echo '    <div class="card-row">';
        echo '      <span class="card-label">Nick:</span>';
        echo '      <span class="card-value">' . htmlspecialchars($row['name'] ?? 'N/A') . '</span>';
        echo '    </div>';
        echo '    <div class="card-row">';
        echo '      <span class="card-label">Version:</span>';
        echo '      <span class="card-value">' . htmlspecialchars($row['Version'] ?? 'N/A') . '</span>';
        echo '    </div>';
        echo '    <div class="card-row">';
        echo '      <span class="card-label">OnTime:</span>';
        echo '      <span class="card-value">' . htmlspecialchars($row['Ontime'] ?? 'N/A') . '</span>';
        echo '    </div>';
        echo '    <div class="card-row">';
        echo '      <span class="card-label">UpTime:</span>';
        echo '      <span class="card-value">' . htmlspecialchars($row['Uptime'] ?? 'N/A') . '</span>';
        echo '    </div>';
        echo '    <div class="card-row">';
        echo '      <span class="card-label">Channels:</span>';
        echo '      <span class="card-value">' . htmlspecialchars($row['Channels'] ?? 'N/A') . '</span>';
        echo '    </div>';
        echo '    <div class="card-row">';
        echo '      <span class="card-label">Server:</span>';
        echo '      <span class="card-value">' . htmlspecialchars($row['Server'] ?? 'N/A') . '</span>';
        echo '    </div>';
        echo '    <div class="card-row">';
        echo '      <span class="card-label">Author:</span>';
        echo '      <span class="card-value">' . htmlspecialchars($row['Author'] ?? 'N/A') . '</span>';
        echo '    </div>';
        echo '    <div class="card-row">';
        echo '      <span class="card-label">Created:</span>';
        echo '      <span class="card-value">' . htmlspecialchars($row['created_at'] ?? 'N/A') . '</span>';
        echo '    </div>';

        // Státusz indikátor (pötty)
        echo '    <div class="card-row">';
        echo '      <span class="card-label">Status:</span>';
        echo '  <div class="status-indicator ' . $blinkClass . '" style="background-color: ' . $statusColor . ';"></div>';
        echo '    </div>';
        
        
	$logged_in = false; // Ezt állítsd true-ra, ha a felhasználó be van jelentkezve.

	// if ($logged_in) {
	    // echo '        <button class="btn">Rehash</button>';
	    // echo '        <button class="btn">Restart</button>';
	    // echo '        <button class="btn">Kill</button>';
	    // echo '        <button class="btn">Config</button>';
	// } else {
	    // echo '        <button class="btn" onclick="alert(\'You must log in to use this command.\')">Rehash</button>';
	    // echo '        <button class="btn" onclick="alert(\'You must log in to use this command.\')">Restart</button>';
	    // echo '        <button class="btn" onclick="alert(\'You must log in to use this command.\')">Kill</button>';
	    // echo '        <button class="btn" onclick="alert(\'You must log in to use this command.\')">Config</button>';
	// }
        
        echo '  </div>';
        echo '</div>';
    }

    // Kártyák szülő konténerének vége
    echo '</div>';
} catch (PDOException $e) {
    echo 'Hiba: ' . $e->getMessage();
}
?>      


