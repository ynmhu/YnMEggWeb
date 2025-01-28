<?php
    if (isset($_POST['logout'])) {
        unset($_SESSION['login']);
        unset($_SESSION['username']);
        session_destroy();
        header("Location: /");
        exit;
    }
    if ($page === 'login') {
      include "login.php";
      exit;
    }
    if (!isset($_SESSION['login'])) {
        header("Location: /");
        exit;
    }
    $include = "adatok/eggdrop/";
    switch ($page) {
        case '':
        case 'index':
            $title = "YnM Egg Web";
            $include .= "main.php";
            break;
        case 'addbot':
            $title = "YnM Egg Web - Add bot";
            $include .= "addbot.php";
            break;
        case 'editbot':
            $title = "YnM Egg Web - Edit bot";
            $include .= "editbot.php";
            break;
        case 'switch':
            $title = "YnM Egg Web - Switch";
            $include .= "switch.php";
            break;
        case 'logs':
            $title = "YnM Egg Web - Logs";
            $include .= "logs.php";
            break;
        case 'system':
            $title = "YnM Egg Web - System operations";
            $include .= "system.php";
            break;
        case 'verify':
          $title = "YnM Egg Web - Email verification";
          $include .= "verify.php";
          break;
        default:
            $title = "YnM Egg Web - 404 (Not found)";
            $include .= "404.php";
            break;
       case 'logout':
          $title = "YnM Egg Web - Logout";
          $include .= "logout.php";
          break;
    }
    $conn = new PDO($dsn, $db['user'], $db['pass']);
    $prep = $conn->prepare("SELECT * FROM bots WHERE user_id=:user");
    $prep->bindValue(":user", $_SESSION['user_id'], PDO::PARAM_INT);
    $prep->execute();
    foreach ($prep->fetchAll(PDO::FETCH_ASSOC) as $bot) {
      $prep = $conn->prepare("SELECT last FROM requests WHERE bot_id=:bot");
      $prep->bindValue(":bot", $bot['id'], PDO::PARAM_INT);
      $prep->execute();
      $reqs = $prep->fetchAll(PDO::FETCH_ASSOC);
      if (empty($reqs)) {
        $status[$bot['id']] = false;
      } else {
        $req = $reqs[0];
        if ((time() - $req['last']) > 30) {
          $status[$bot['id']] = false;
        } else {
          $status[$bot['id']] = true;
        }
      }
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="YnM Ai - The advanced Eggdrop bot management panel with AI-powered functionalities.">
    <meta property="og:title" content="<?php echo $title; ?>">
    <meta property="og:description" content="YnM Ai offers an advanced Eggdrop bot management panel, enhanced with AI functionalities for smarter management and actions.">
    <meta property="og:image" content="https://ai.ynm.hu/favicon.webp">
    <meta property="og:url" content="https://ai.ynm.hu">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $title; ?>">
    <meta name="twitter:description" content="Manage your Eggdrop bots with advanced AI capabilities in the YnM Ai panel.">
    <meta name="twitter:image" content="https://ai.ynm.hu/favicon.webp">
    <meta name="twitter:site" content="@ynmhu">
    <link rel="icon" href="https://ai.ynm.hu/favicon.webp" type="image/x-icon">

    <title><?php echo $title; ?></title>
    <link href="/css/b.css" rel="stylesheet">
    <link href="/css/ynm.css" rel="stylesheet">
</head>
    <body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item <?= $page === 'index' ? 'active' : ''; ?>">
                <a class="nav-link" href="/adatbazis/">Overview</a>
            </li>
            <li class="nav-item <?= $page === 'logs' ? 'active' : ''; ?>">
                <a class="nav-link" href="/adatbazis/logs/">Logs</a>
            </li>
	 <li class="nav-item <?= $page === 'system' ? 'active' : ''; ?>">
                <a class="nav-link" href="/adatbazis/system/">SySTem</a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
<li class="nav-item">
    <a class="nav-link text-white bg-danger" href="/adatbazis/logout" >Logout: <?= htmlspecialchars($_SESSION['user']); ?> </a>
</li>
        </ul>
    </div>
</nav>

<div class="container mt-5">






                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<?php
    $prep = $conn->prepare("SELECT verified FROM users WHERE id=:userid");
    $prep->bindValue(":userid", $_SESSION['user_id'], PDO::PARAM_INT);
    $prep->execute();    
    $res = $prep->fetchAll(PDO::FETCH_ASSOC);
    $res = $res[0];
    $verified = is_null($res['verified']) ? false : true;
    if (!$verified) { ?>
<div id="notverified" class="alert alert-warning" role="alert"><strong>You haven't verified your email yet.</strong>
<strong>You Can ADD/EDIT view bots.</strong> <a href="/adatbazis/verify/">Click here</a> to verify your email address.</div>
<?php } ?>
                    <?php include $include; ?>
                </div>
</div>


<?php if ($page === 'verify' && isset($success) && $success) { ?>
      <script type="text/javascript">
$(function e () { $('#notverified').hide() } )
      </script>
<?php } ?>

<footer class="footer"><div class="container">
<p class="footer-text">© 2012 <a href="https://ynm.hu">YnM</a> ℠ ™ - All rights reserved. ® 2012 - <span id="currentYear"></span> <a href="https://ynm.hu">YnM</a> LCC</p>
</div></footer>
</body>
<script src="/js/b.js"></script>
<script src="/js/ynm.js"></script>
</html>