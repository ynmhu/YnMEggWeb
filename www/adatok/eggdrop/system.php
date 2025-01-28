<?php

try {
    // Botok lekérdezése az adatbázisból
    $prep = $conn->prepare("SELECT * FROM bots WHERE user_id = :userid");
    $prep->bindValue(":userid", $_SESSION['user_id'], PDO::PARAM_INT);
    $prep->execute();
    $bots = $prep->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Hiba történt az adatbázis lekérdezés során: " . $e->getMessage();
    exit;
}

// Üzenetek a visszajelzéshez
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (empty($_POST['bot_id'])) {
        $message = "Nincs bot kiválasztva.";
    } else {
        try {
            // Prepare the INSERT statement
            $prep = $conn->prepare("INSERT INTO actions 
            (id, bot_id, command, arguments, timestamp, executed, pickup, success, message) 
            VALUES (NULL, :botid, :command, :arguments, :time, 0, NULL, NULL, NULL)");

            switch (strtolower($_POST['submit'])) {
                case 'rehash':
                    $prep->bindValue(":command", "rehash", PDO::PARAM_STR);
                    $prep->bindValue(":arguments", NULL, PDO::PARAM_NULL);
                    break;
                case 'restart':
                    $prep->bindValue(":command", "restart", PDO::PARAM_STR);
                    $prep->bindValue(":arguments", NULL, PDO::PARAM_NULL);
                    break;
                case 'die':
                    $prep->bindValue(":command", "die", PDO::PARAM_STR);
                    $prep->bindValue(":arguments", NULL, PDO::PARAM_NULL);
                    break;
	      case 'join':
		if (empty($_POST['channel'])) {
		$message = "Kérem adja meg a csatorna nevét.";
		break; // Just break out of the switch
		}
		$prep->bindValue(":command", "join", PDO::PARAM_STR);
		$prep->bindValue(":arguments", $_POST['channel'], PDO::PARAM_STR);
		break;
	      case 'part':
		if (empty($_POST['channel'])) {
		$message = "Kérem adja meg a csatorna nevét.";
		break; // Just break out of the switch
		}
		$prep->bindValue(":command", "part", PDO::PARAM_STR);
		$prep->bindValue(":arguments", $_POST['channel'], PDO::PARAM_STR);
		break;
                default:
                    header("Location: /adatbazis/system/");
                    exit;
            }

            $prep->bindValue(":time", time(), PDO::PARAM_INT);
            $prep->bindValue(":botid", $_POST['bot_id'], PDO::PARAM_INT);

            // Execute the statement
            if ($prep->execute()) {
                $message = "Parancs sikeresen elküldve.";
            } else {
                $message = "Hiba történt a parancs végrehajtása közben.";
            }
        } catch (PDOException $e) {
            $message = "Adatbázis hiba: " . $e->getMessage();
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }
}
?>

<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Bot Kezelő Panel</h1>

        <?php if ($message): ?>
            <div class="alert <?php echo isset($error_message) ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Online</th>
                        <th>Nick</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bots)): ?>
                        <?php foreach ($bots as $bot): ?>
                            <tr>
                                <!-- Bot ID, Name and Status -->
                                <td><?php echo htmlspecialchars($bot['id'] ?? ''); ?></td>
			                                  <td>
                                    <span class="badge <?php echo $bot['active'] === '1' ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo htmlspecialchars($bot['active'] ?? '0'); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($bot['name'] ?? ''); ?></td>

                                
                                <!-- Commands on the Left -->
                                <td>
                                    <form method="POST" class="text-center">
                                        <input type="hidden" name="bot_id" value="<?php echo htmlspecialchars($bot['id'] ?? ''); ?>">
                                        <button type="submit" name="submit" value="rehash" class="btn btn-info btn-sm mb-2">Rehash</button><br>
                                        <button type="submit" name="submit" value="restart" class="btn btn-warning btn-sm mb-2">Restart</button><br>
                                        <button type="submit" name="submit" value="die" class="btn btn-danger btn-sm mb-2">Die</button><br>
<div class="d-flex">
    <input type="text" name="channel" class="form-control mb-2 me-2" placeholder="Enter channel (e.g., #Marika)">
    <button type="submit" name="submit" value="join" class="btn btn-success btn-sm mb-2">Add</button>
    <button type="submit" name="submit" value="part" class="btn btn-success btn-sm mb-2">Del</button>
</div>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Nincsenek elérhető botok.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>   
            </table>
        </div>
    </div>
</body>

