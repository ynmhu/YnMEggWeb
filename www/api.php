<?php
header("Content-type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "global.php";

$conn = new PDO($dsn, $db['user'], $db['pass']);
$res = $conn->query("SELECT * FROM bots");
$found = false;

foreach ($res as $el) {
  if (password_verify($_POST['key'], $el['key_hash'])) {
    $bot_record = $el;
    $user = $el['user_id'];
    $bot = $el['id'];
    $found = true;
    if ($el['active'] == 0) {
            // First deactivate all other bots for this user
            $prep = $conn->prepare("UPDATE bots SET active = 0 WHERE user_id = :userid");
            $prep->bindValue(":userid", $user, PDO::PARAM_INT);
            $prep->execute();
            
            // Then activate this bot
            $prep = $conn->prepare("UPDATE bots SET active = 1 WHERE id = :botid");
            $prep->bindValue(":botid", $bot, PDO::PARAM_INT);
            $prep->execute();
        }
        break;
    }
}  

if (!isset($_POST['key']) || !isset($_POST['command'])) {
  $json['code'] = 300;
  $json['message'] = "Missing parameter.";
  echo json_encode($json);
  exit;
}

if (!$found) {
  $json['code'] = 400;
  $json['message'] = "Invalid API key.";
  echo json_encode($json);
  exit;
} else {
  // bot is online, regardless of the syntax
  $prep = $conn->prepare("SELECT * FROM requests WHERE bot_id=:bot");
  $prep->bindValue(":bot", $bot, PDO::PARAM_INT);
  $prep->execute();
  $reqs = $prep->fetchAll(PDO::FETCH_ASSOC);
  if (empty($reqs)) {
    $prep = $conn->prepare("INSERT INTO requests (id, bot_id, last) VALUES (NULL, :bot, :time)");
  } else {
    $prep = $conn->prepare("UPDATE requests SET last=:time WHERE bot_id=:bot");
  }
  $prep->bindValue(":bot", $bot, PDO::PARAM_INT);
  $prep->bindValue(":time", time(), PDO::PARAM_INT);
  $prep->execute();

  // parse the command
  switch (strtolower($_POST['command'])) {
  case 'fetch':
    $prep = $conn->prepare("SELECT id, command, arguments FROM actions WHERE bot_id=:bot AND executed=0");
    $prep->bindValue(":bot", $bot, PDO::PARAM_INT);
    $prep->execute();
    $results = $prep->fetchAll(PDO::FETCH_ASSOC);
    $json['code'] = 200;
    $json['message'] = $results;
    echo json_encode($json);
    break;
    
case 'chanlist':
    $prep = $conn->prepare("SELECT Channels FROM bots WHERE Id = :botid");
    $prep->bindValue(":botid", $bot, PDO::PARAM_INT);
    
    try {
        $prep->execute();
        $result = $prep->fetch(PDO::FETCH_ASSOC);
        
        // Split the comma-separated channels
        $channels = explode(',', $result['Channels']);
        
        $json['code'] = 200;
        $json['message'] = $channels;
        echo json_encode($json);
    } catch (PDOException $e) {
        $json['code'] = 500;
        $json['message'] = "Database error: " . $e->getMessage();
        echo json_encode($json);
    }
    break;
case 'updatechannels':
    if (!isset($_POST['channels'])) {
        $json['code'] = 300;
        $json['message'] = "Missing channels parameter.";
        echo json_encode($json);
        exit;
    }
    
    error_log("Bot ID: " . $bot);
    error_log("Channels: " . $_POST['channels']);
    
    $prep = $conn->prepare("UPDATE bots SET Channels = :channels WHERE id = :botid");
    $prep->bindValue(":channels", $_POST['channels'], PDO::PARAM_STR);
    $prep->bindValue(":botid", $bot, PDO::PARAM_INT);
    
    if ($prep->execute()) {
        // Check affected rows
        $affected = $prep->rowCount();
        error_log("Affected rows: " . $affected);
        
        if ($affected == 0) {
            $json['code'] = 404;
            $json['message'] = "No matching bot found. Check bot ID.";
        } else {
            $json['code'] = 200;
            $json['message'] = "Channels updated successfully.";
        }
    } else {
        $json['code'] = 410;
        $json['message'] = "Error updating channels.";
        error_log("Update error: " . print_r($prep->errorInfo(), true));
    }
    echo json_encode($json);
    break;

case 'uptime':
    try {
        if (!isset($_POST['server_uptime'])) {
            $json['code'] = 300;
            $json['message'] = "Missing server_uptime parameter";
            echo json_encode($json);
            exit;
        }
        
        // Update the uptime in the bots table
        $prep = $conn->prepare("UPDATE bots SET Uptime = :server_uptime WHERE Id = :botid");
        $prep->bindValue(":server_uptime", $_POST['server_uptime'], PDO::PARAM_STR);
        $prep->bindValue(":botid", $bot, PDO::PARAM_INT);
        
        if ($prep->execute()) {
            $affected = $prep->rowCount();
            if ($affected > 0) {
                $json['code'] = 200;
                $json['message'] = "Bot start time updated successfully";
            } else {
                $json['code'] = 404;
                $json['message'] = "Bot not found";
            }
        } else {
            $json['code'] = 500;
            $json['message'] = "Database error";
            error_log("Update error: " . print_r($prep->errorInfo(), true));
        }
    } catch (PDOException $e) {
        $json['code'] = 500;
        $json['message'] = "Database error: " . $e->getMessage();
        error_log("Start time update error: " . $e->getMessage());
    }
    echo json_encode($json);
    break;
case 'ontime':
    try {
        if (!isset($_POST['on_time'])) {
            $json['code'] = 300;
            $json['message'] = "Missing on_time parameter";
            echo json_encode($json);
            exit;
        }
        
        // Update the uptime in the bots table
        $prep = $conn->prepare("UPDATE bots SET Ontime = :on_time WHERE Id = :botid");
        $prep->bindValue(":on_time", $_POST['on_time'], PDO::PARAM_STR);
        $prep->bindValue(":botid", $bot, PDO::PARAM_INT);
        
        if ($prep->execute()) {
            $affected = $prep->rowCount();
            if ($affected > 0) {
                $json['code'] = 200;
                $json['message'] = "Bot on time updated successfully";
            } else {
                $json['code'] = 404;
                $json['message'] = "Bot not found";
            }
        } else {
            $json['code'] = 500;
            $json['message'] = "Database error";
            error_log("Update error: " . print_r($prep->errorInfo(), true));
        }
    } catch (PDOException $e) {
        $json['code'] = 500;
        $json['message'] = "Database error: " . $e->getMessage();
        error_log("Start time update error: " . $e->getMessage());
    }
    echo json_encode($json);
    break;    
  case 'pickup':
    if (!isset($_POST['action']) || !isset($_POST['success'])) {
      $json['code'] = 300;
      $json['message'] = "Missing parameter.";
      echo json_encode($json);
      exit;
    }
    if (!in_array($_POST['success'], array(0, 1))) {
      $json['code'] = 305;
      $json['message'] = "Invalid parameter. 'success' MUST be either 0 or 1.";
    }
    $prep = $conn->prepare("SELECT * FROM actions WHERE bot_id=:bot AND id=:action AND executed=0");
    $prep->bindValue(":bot", $bot, PDO::PARAM_INT);
    $prep->bindValue(":action", $_POST['action'], PDO::PARAM_INT);
    $prep->execute();
    $results = $prep->fetchAll(PDO::FETCH_ASSOC);
    if (empty($results)) {
      $json['code'] = 405;
      $json['message'] = "Action not found, already picked up, or you're not allowed to pick it up.";
      echo json_encode($json);
      exit;
    }
    $prep = $conn->prepare("UPDATE actions SET executed=1, pickup=:time, success=:success, message=:message WHERE id=:id");
    $prep->bindValue(":time", time(), PDO::PARAM_INT);
    $prep->bindValue(":id", $_POST['action'], PDO::PARAM_INT);
    $prep->bindValue(":success", $_POST['success'], PDO::PARAM_BOOL);
    if (isset($_POST['message']) && !empty($_POST['message'])) {
      $prep->bindValue(":message", $_POST['message'], PDO::PARAM_STR);
    } else {
      $prep->bindValue(":message", "", PDO::PARAM_NULL);
    }
    if ($prep->execute()) {
      $json['code'] = 200;
      $json['message'] = "Success.";
    } else {
      $json['code'] = 410;
      $json['message'] = "Error executing the action.";
    }
    echo json_encode($json);
    break;
  default:
    $json['code'] = 350;
    $json['message'] = "Invalid command.";
    echo json_encode($json);
    break;
  }
}
exit;
