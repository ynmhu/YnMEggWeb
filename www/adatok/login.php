<?php
  if (isset($_SESSION['login'])) {
      header("Location: /adatbazis/");
      exit;
  }
  // Authentication
  $failed = false;
  if (isset($_POST['sent'])) {
    $conn = new PDO($dsn, $db['user'], $db['pass']);
    $prep = $conn->prepare("SELECT * FROM users WHERE name=:username");
    $prep->bindValue(":username", $_POST['username'], PDO::PARAM_STR);
    $prep->execute();
    $res = $prep->fetchAll(PDO::FETCH_ASSOC);
    if (empty($res)) {
      $failed = true;
    } else {
      $pass = $res[0]['password_hash'];
      if (password_verify($_POST['password'], $pass)) {
        $_SESSION['user'] = $res[0]['name'];
        $_SESSION['user_id'] = $res[0]['id'];
        $_SESSION['login'] = true;
        $prep = $conn->prepare("SELECT * FROM bots WHERE user_id=:userid AND active=1");
        $prep->bindValue(":userid", $res[0]['id'], PDO::PARAM_INT);
        $prep->execute();
        $results = $prep->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($results)) {
          $_SESSION['botname'] = $results[0]['name'];
          $_SESSION['bot_id'] = $results[0]['id'];
        }
        header("Location: /adatbazis/");
        exit;
      } else {
        $failed = true;
      }
    }
  }
?>
        <div class="container">
            <?php if ($failed) { ?>
<h1>Failed</h1>
            <?php } ?> 

            
        </div>