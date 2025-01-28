<?php
define("YnM Egg Web", true);
$include = "ynm/oldalak/";
switch ($page) {
  case '':
  case 'index':
    $title = "YnM Egg Web - Home";
    $include .= "main.php";
    break;
  case 'media':
    $title = "YnM Egg Web - Media";
    $include .= "media.php";
    break;
  case 'top':
    $title = "YnM Egg Web - Top";
    $include .= "top.php";
    break;
  case 'faq':
    $title = "YnM Egg Web - FAQ";
    $include .= "faq.php";
    break;
  case 'downloads':
    $title = "YnM Egg Web - Downloads";
    $include .= "downloads.php";
    break;
  case 'contact':
    $title = "YnM Egg Web - Contact";
    $include .= "contact.php";
    break;
  case 'signup':
    $title = "YnM Egg Web - Sign up";
    $include .= "signup.php";
    if (isset($recaptcha['enabled']) && $recaptcha['enabled']) {
      $showRC = true;
    }
    break;
  default:
    $title = "YnM Egg Web - 404 (Not Found)";
    $include .= "404.php";
    break;
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
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<?php if (isset($showRC)) { ?><script src='https://www.google.com/recaptcha/api.js'></script><?php } ?>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="/index">YnM Egg Web</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">
    <!-- Bal oldali menüelemek -->
    <ul class="navbar-nav">
      <li class="nav-item <?= ($page === 'index') ? 'active' : ''; ?>">
        <a class="nav-link text-white <?= ($page === 'index') ? 'bg-secondary' : ''; ?>" href="index">Home</a>
      </li>
      <li class="nav-item <?= ($page === 'media') ? 'active' : ''; ?>">
        <a class="nav-link text-white <?= ($page === 'media') ? 'bg-secondary' : ''; ?>" href="media">Media</a>
      </li>
      <li class="nav-item <?= ($page === 'top') ? 'active' : ''; ?>">
        <a class="nav-link text-white <?= ($page === 'top') ? 'bg-secondary' : ''; ?>" href="top">Top</a>
      </li>
      <li class="nav-item <?= ($page === 'faq') ? 'active' : ''; ?>">
        <a class="nav-link text-white <?= ($page === 'faq') ? 'bg-secondary' : ''; ?>" href="faq">FAQ</a>
      </li>
      <li class="nav-item <?= ($page === 'downloads') ? 'active' : ''; ?>">
        <a class="nav-link text-white <?= ($page === '404') ? 'bg-danger' : ''; ?>" href="404">Downloads</a>
      </li>   
      <li class="nav-item <?= ($page === 'contact') ? 'active' : ''; ?>">
        <a class="nav-link text-white <?= ($page === 'contact') ? 'bg-secondary' : ''; ?>" href="contact">Contact</a>
      </li>
    </ul>
    <!-- Jobb oldali menüelemek -->
    <ul class="navbar-nav ms-auto">
<li class="nav-item <?= ($page === 'signup') ? 'active' : ''; ?>">
  <?php if ($signupAvailable): ?>
    <a class="nav-link" href="/signup">Sign up</a>
  <?php else: ?>
    <a class="nav-link text-white bg-warning" href="#">Sign up (Currently Disabled by Markus)</a>
  <?php endif; ?>
</li>
      <li class="nav-item">
        <a class="nav-link text-white <?= ($page === 'login') ? 'bg-secondary' : ''; ?>" href="/adatbazis/login" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
      </li>
    </ul>
  </div>
</nav>




<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <form action="/adatbazis/login" method="post" class="form-signin">
          <input type="hidden" name="sent" value="1">
          <h5 class="modal-title" id="loginModalLabel">Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </form>
      </div>
      <div class="modal-body">
        <?php if (isset($failed) && $failed) { ?>
          <div class="alert alert-danger" role="alert">
            <strong>Login failed</strong> Please check your username or password.
          </div>
        <?php } ?>
        <form action="/adatbazis/login" method="post" class="form-signin">
          <input type="hidden" name="sent" value="1">
          <div class="mb-3">
            <label for="username" class="sr-only">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
          </div>
          <div class="mb-3">
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
          </div>
          <div class="mb-3">
            <div class="form-check">
              <input type="checkbox" name="remember" value="remember-me" class="form-check-input" id="remember">
              <label class="form-check-label" for="remember">Remember me</label>
            </div>
          </div>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>
      </div>
    </div>
  </div>
</div>


<?php include $include; ?>




<footer class="footer"><div class="container">
<p class="footer-text">© 2012 <a href="https://ynm.hu">YnM</a> ℠ ™ - All rights reserved. ® 2012 - <span id="currentYear"></span> <a href="https://ynm.hu">YnM</a> LCC</p>
</div></footer>
</body>
<script src="/js/b.js"></script>
<script src="/js/ynm.js"></script>
</html>
