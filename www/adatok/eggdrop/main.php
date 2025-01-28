<?php
try {
    $prep = $conn->prepare("SELECT * FROM bots WHERE user_id = :userid");
    $prep->bindValue(":userid", $_SESSION['user_id'], PDO::PARAM_INT);
    $prep->execute();
    $bots = $prep->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Hiba történt az adatbázis lekérdezés során: " . $e->getMessage();
    exit;
}

// Bot aktiválása
if (isset($_GET['activate'])) {
    $bot_id = intval($_GET['activate']);
    try {
        $prep = $conn->prepare("UPDATE bots SET active = 0 WHERE user_id = :userid");
        $prep->bindValue(":userid", $_SESSION['user_id'], PDO::PARAM_INT);
        $prep->execute();

        $prep = $conn->prepare("UPDATE bots SET active = 1 WHERE id = :botid AND user_id = :userid");
        $prep->bindValue(":botid", $bot_id, PDO::PARAM_INT);
        $prep->bindValue(":userid", $_SESSION['user_id'], PDO::PARAM_INT);
        $prep->execute();

        $_SESSION['bot_id'] = $bot_id;
        header("Location: /adatbazis/");
        exit;
    } catch (PDOException $e) {
        echo "Hiba történt az adatbázis frissítés során: " . $e->getMessage();
        exit;
    }
}
// Bot hozzáadásának ellenőrzése
$limitreached = false;
$prep = $conn->prepare("SELECT * FROM bots WHERE user_id=:userid");
$prep->bindValue(":userid", $_SESSION['user_id'], PDO::PARAM_INT);
$prep->execute();
if (count($prep->fetchAll(PDO::FETCH_ASSOC)) >= 5) {
    $limitreached = true;
}
if (isset($_POST['sent']) && $verified && !$limitreached) {
    $prep = $conn->prepare("SELECT * FROM bots WHERE user_id=:userid AND name=:botname");
    $prep->bindValue(":userid", $_SESSION['user_id'], PDO::PARAM_INT);
    $prep->bindValue(":botname", $_POST['botname'], PDO::PARAM_STR);
    $prep->execute();
    $res = $prep->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($res)) {
        $existing = true;
    } else {
        
        $prep = $conn->prepare("INSERT INTO bots (
    name, 
    user_id, 
    active, 
    key_hash, 
    Ontime, 
    Uptime, 
    Channels, 
    Author, 
    Version, 
    Server, 
    Status
) VALUES (
    :name, 
    :userid, 
    0, 
    :keyhash, 
    NULL, 
    NULL, 
    NULL, 
    NULL, 
    NULL, 
    NULL, 
    NULL
)");

$prep->bindValue(":name", $_POST['botname'], PDO::PARAM_STR);
$prep->bindValue(":userid", $_SESSION['user_id'], PDO::PARAM_INT);
$key = generateRandomString(20);
$prep->bindValue(":keyhash", password_hash($key, PASSWORD_DEFAULT), PDO::PARAM_STR);
        if ($prep->execute()) {
            $created = true;
        } else {
            $created = false;
        }
    }

}
?>
<div class="container mt-5"> 
<p class="text-center egg-text">
        Welcome back, <?= htmlspecialchars($_SESSION['user']); ?>
        
    </p>
    <h1 class="text-center">Bots Status</h1>
    <table class="table table-bordered text-center w-100">
        <thead>
        <tr>
            <th>Bot Name</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        
        <tbody>
        <?php if (!empty($bots)): ?>
            <?php foreach ($bots as $bot): ?>
                <tr class="<?= $bot['active'] ? 'table-success' : '' ?>">
                    <td><?= htmlspecialchars($bot['name']) ?></td>
                    <td>
                        <?php if ($bot['active']): ?>
                            <span class="text-success"><strong>Aktív</strong></span>
                        <?php else: ?>
                            <span class="text-danger"><strong>Inaktív</strong></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$bot['active']): ?>
                            <a href="" class="btn btn-success btn-sm" onclick="return confirmActivation()">Aktiválás</a>
                        <?php endif; ?>
                        <a href="/adatbazis/editbot/<?= $bot['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No BOTS.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="container row justify-content-center">
    <h1 class="text-center">Add New Bot</h1>
    
    <?php if ($limitreached): ?>
        <div class="alert alert-danger" role="alert"><strong>Sorry, you reached the limit of 5 bots.</strong></div>
    <?php endif; ?>
    
    <?php if (isset($existing) && $existing): ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Couldn't create the bot.</strong> There's already a bot known by that name.
        </div>
    <?php endif; ?>
    
    <?php if (isset($created) && $created): ?>
   <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>Success!</strong> Here's the API key, keep it safe:
        <div class="input-group">
	   <label for="apiKey">API Key:</label>
            <input type="text" id="apiKey" value="<?php echo $key; ?>"  class="form-control" readonly>
            <div class="input-group-append">
                <button class="btn btn-primary" onclick="copyApiKey()">Copy</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="d-flex justify-content-center">    
        <form action="/adatbazis/" method="post" class="form-horizontal mx-auto">
            <input type="hidden" name="sent" value="1">
            <div class="form-group ">
                <div class="col-sm-10 p-2">
                    <input type="text" 
		class="form-control" 
		id="botname" name="botname" 
		placeholder="Please choose BOT Nick Name" 
		style="margin-right: 200px;">
                </div>

            </div>
            <div class="form-group ">
                <div class="col-sm-offset-12 p-2 col-sm-10 d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 ?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sent'])) {
    echo '<script type="text/javascript">
            var countdown = 5;
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

<script>
function copyApiKey() {
    // Get the input field
    var apiKeyInput = document.getElementById("apiKey");
    
    // Select the text
    apiKeyInput.select();
    apiKeyInput.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        // Try to use the modern clipboard API
        navigator.clipboard.writeText(apiKeyInput.value)
            .then(() => {
                // alert("API Key has been copied to your clipboard!");
            })
            .catch(() => {
                // Fallback to the older execCommand method
                document.execCommand('copy');
                // alert("API Key has been copied to your clipboard!");
            });
    } catch (err) {
        alert("Failed to copy API Key. Please copy it manually.");
    }
}

// Remove focus from input after copying
        document.addEventListener('DOMContentLoaded', function() {
            const apiKeyInput = document.getElementById("apiKey");
            if (apiKeyInput) {
                apiKeyInput.addEventListener('focus', function(e) {
                    this.select();
                });
            }
        });
function confirmActivation() {
    alert("You have to add API Key to TCL/Py to Activate");
    return true;  
}        
</script>
