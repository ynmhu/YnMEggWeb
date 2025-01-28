<?php
// URL lekérése
$url = $_SERVER['REQUEST_URI'];

// Az URL-t részekre bontjuk
$parts = explode('/', $url);

// Feltételezzük, hogy az ID az utolsó elem az URL-ben
$id = end($parts);

// Ellenőrizzük, hogy az ID egy szám-e
if (is_numeric($id)) {
    $id = intval($id); // Az ID biztosítása tiszta számként

    // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
    if ($page === 'login') {
        include "adatok/login.php";
        exit;
    }
    if (!isset($_SESSION['login'])) {
        header("Location: /index");
        exit;
}




function safe_output($data) {
    return htmlspecialchars($data ?? '');
}

// Az adott bot adatait lekérdezzük
$check = $conn->prepare("SELECT * FROM bots WHERE id = :id AND user_id = :user_id");
$check->bindValue(':id', $id, PDO::PARAM_INT);
$check->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT); // Feltételezzük, hogy a session tartalmazza a user_id-t
$check->execute();

// Ellenőrizzük, hogy van-e találat
$botData = $check->fetch(PDO::FETCH_ASSOC);

if ($botData) {
    // Csak akkor engedélyezzük a törlést, ha a bot a felhasználóhoz tartozik
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
        $delete = $conn->prepare("DELETE FROM bots WHERE id = :id");
        $delete->bindValue(':id', $id, PDO::PARAM_INT);
        $delete->execute();
        $message = "<div class='alert alert-success'>The bot with ID $id has been successfully deleted.</div>";
        $botData = null; // Adatok eltávolítása, mert a bot törölve lett
    }
} else {
    $message = "<div class='alert alert-danger'>You are not authorized to delete this bot or it doesn't exist.</div>";
}
} else {
$message = "<div class='alert alert-danger'>Invalid ID in URL.</div>";
}
?>
<div class="container mt-5 row justify-content-center" style="width: 100%; max-width: 1200px;">
    <div class="card" style="width: 100%;">
        <div class="card-header bg-primary text-white text-center">
          Details & Delete
        </div>
        <div class="card-body">
            <?php if (isset($message)) echo $message; ?>
            <?php if ($botData): ?>
                <h5 class="card-title">Bot Details:</h5>
                <?php
                echo '<div class="list-group list-group-flush">';
                echo '  <div class="card-body list-group-item">';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>ID:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['id']) . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>Name:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['name']) . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>User ID:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['user_id']) . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>Active:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['active'] ? 'Yes' : 'No') . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>Key Hash:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['key_hash']) . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>OnTime:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['Ontime']) . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>UpTime:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['Uptime']) . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>Channels:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['Channels']) . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>Author:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['Author']) . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>Version:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['Version']) . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>Server:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['Server']) . '</span>';
                echo '    </div>';
                echo '    <div class="card-row list-group-item">';
                echo '      <span class="card-label"><strong>Status:</strong></span>';
                echo '      <span class="card-value" style="display: inline-block; width: 70%;">' . safe_output($botData['Status']) . '</span>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
                ?>
                <p class="p2 text-white bg-danger text-center">Are you sure you want to delete this bot?</p>
                <form class="content-center text-center" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="post">
                    <input type="hidden" name="delete" value="1">
                    <button type="submit" class="btn btn-danger p-1">Delete</button>
                    <a href="/adatbazis/" class="btn btn-secondary p-1">Cancel</a>
                </form>
            <?php elseif (!isset($message)): ?>
                <p>Invalid ID provided in the URL or you don't have access to this bot.</p>
                <a href="/adatbazis/" class="btn btn-secondary">Go Back</a>
            <?php endif; ?>
        </div>
    </div>
</div>




<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $verified && isset($_POST['delete'])) {
    // Az adatbázisból való törlés logikája itt
    // Például a bot törlését végző kód...

    // Javascript kód a 3 másodperces várakozáshoz és visszaszámláláshoz
    echo '<script type="text/javascript">
            var countdown = 3;
            var countdownElement = document.createElement("div");
            countdownElement.style.fontSize = "100px";
            countdownElement.style.fontWeight = "bold";
            countdownElement.style.textAlign = "center";
            countdownElement.style.marginTop = "10px";
            document.body.appendChild(countdownElement);
            
            function updateCountdown() {
                countdownElement.textContent = countdown;
                if (countdown <= 0) {
                    window.location.href = "/adatbazis/";
                } else {
                    countdown--;
                    setTimeout(updateCountdown, 1000);
                }
            }
            
            updateCountdown();
          </script>';
}
?>

