<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '/home/ai/vendor/autoload.php';
try {
require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
} catch (Throwable $e) {

    die('Hiba történt a global.php betöltésekor: ' . $e->getMessage());
}

// ReCaptcha ellenőrzés
$response = ['success' => false, 'message' => ''];

// reCAPTCHA ellenőrzés
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sent'])) {
    $failedcaptcha = false;
    if (isset($_POST['g-recaptcha-response'])) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $recaptcha['secret'],
            'response' => $_POST['g-recaptcha-response'],
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $captcha_json = json_decode($result, true);

        if (!$captcha_json['success']) {
            $failedcaptcha = true;
        }
    } else {
        $failedcaptcha = false;
    }

    // Egyéb ellenőrzések
    if ($_POST['password'] != $_POST['verpassword']) {
        $failedver = true;
    } elseif (!preg_match("/\S+@\S+\.\S+/i", $_POST['email'])) {
        $invalidemail = true;
    } else {
        // Adatbázis ellenőrzés
        $conn = new PDO($dsn, $db['user'], $db['pass']);
        $results = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $res) {
            if (strtolower($res['email']) === strtolower($_POST['email'])) {
                $existingemail = true;
                break;
            } elseif (strtolower($res['name']) === strtolower($_POST['username'])) {
                $existinguser = true;
                break;
            }
        }

        if (!isset($existingemail) && !isset($existinguser) && !$failedcaptcha) {
            // Felhasználó hozzáadása az adatbázishoz
            $prep = $conn->prepare("INSERT INTO users (id, name, password_hash, email, token_hash, verified) VALUES (NULL, :name, :passhash, :email, :tokenhash, NULL)");
            $prep->bindValue(":name", $_POST['username'], PDO::PARAM_STR);
            $prep->bindValue(":passhash", password_hash($_POST['password'], PASSWORD_DEFAULT), PDO::PARAM_STR);
            $prep->bindValue(":email", $_POST['email'], PDO::PARAM_STR);
            $token = generateRandomString(32);
            $prep->bindValue(":tokenhash", password_hash($token, PASSWORD_DEFAULT), PDO::PARAM_STR);
            if ($prep->execute()) {
                // Email küldése
                try {
                    $mailObj = new PHPMailer(true);
                    $mailObj->isSMTP();
                    $mailObj->Host = $mail['smtp'];
                    if (!empty($mail['username'])) {
                        $mailObj->SMTPAuth = true;
                        $mailObj->Username = $mail['username'];
                        $mailObj->Password = $mail['password'];
                    } else {
                        $mailObj->SMTPAuth = false;
                    }
                    if (!empty($mail['encryption'])) {
                        $mailObj->SMTPSecure = $mail['encryption'];
                    }
                    $mailObj->Port = $mail['port'];
                    $mailObj->setFrom($mail['from'], $mail['name']);
                    $mailObj->addAddress($_POST['email'], $_POST['username']);
                    $mailObj->isHTML(true);
                    $mailObj->Subject = 'EggPanel registration';
                    $mailObj->Body = <<<EOT
<h1>Dear {$_POST['username']}</h1>
<p>Thank you for registering on our service.</p>
<p>Here is your code to validate your account: <b>$token</b></p>
<p>Login into the dashboard and you'll find all the information you need to validate it.</p>
<p>If you have any question, please contact {$mail['from']}</p>
<p>Regards,</p>
<p>EggPanel</p>
<p>[This was an automatically generated message.]</p>
EOT;
                    $mailObj->AltBody = "Dear {$_POST['username']},\nHere is your code to validate your account: $token\nRegards,\nEggPanel\n[This was an automatically generated message.]";
                    if ($mailObj->send()) {
                        $success = true;
                    } else {
                        $success = false;
                    }
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mailObj->ErrorInfo}";
                    $success = false;
                }
            } else {
                $success = false;
            }
        }
    }
}
?>
<!-- Sign Up Modal -->
<link href="/css/signin.css" rel="stylesheet">
<h1 class="text-center">Sign Up</h1>
        <?php
        if (isset($_POST['sent'])) {
          if (empty($_POST['password']) || empty($_POST['verpassword']) || empty($_POST['username']) || empty($_POST['email'])) { ?>
            <div class="alert alert-danger" role="alert"><strong>Missing parameter.</strong> All fields are mandatory.</div>
          <?php } elseif ($failedcaptcha) { ?>
            <div class="alert alert-danger" role="alert"><strong>You failed human verification.</strong> Please click on the captcha box before submitting.</div>
          <?php } elseif (isset($failedver)) { ?>
            <div class="alert alert-danger" role="alert"><strong>You provided two different passwords.</strong> Please make sure you type the same password in both fields.</div>
          <?php } elseif (isset($invalidemail)) { ?>
            <div class="alert alert-danger" role="alert"><strong>That doesn't look like a valid email address.</strong> Please provide a valid address as you will need it to verify your account.</div>
          <?php } elseif (isset($existingemail)) { ?>
            <div class="alert alert-danger" role="alert"><strong>It looks like there's already an account registered with that email.</strong> If you forgot your password, you can <a class="alert-link" href="/reset/">reset it</a>.</div>
          <?php } elseif (isset($existinguser)) { ?>
            <div class="alert alert-danger" role="alert"><strong>That username is already taken.</strong> If you think it belongs to you, but you forgot your password, please <a class="alert-link" href="/reset/">reset it</a>.</div>
          <?php } elseif (isset($success)) {
            if ($success) { ?>
              <div class="alert alert-success" role="alert"><strong>Success!</strong> Please follow the instructions we sent you via email to validate your account.</div>
            <?php } else { ?>
              <div class="alert alert-danger" role="alert"><strong>Failed to register your account.</strong> If you see this message, please contact the system administrator ASAP.</div>
            <?php }
          }
        }
        if (!isset($success)) { ?>
<div class="container row justify-content-center d-flex ">
  <form action="/signup/" method="post" class="form-horizontal" style="max-width: 600px; width: 100%;">
    <input type="hidden" name="sent" value="1">
    <div class="form-group row p-2">
      <div class="col-sm-8">
        <input type="email" class="form-control" name="email" id="email" placeholder="Please enter your email address.">
      </div>
    </div>
    <div class="form-group row p-2">
      <div class="col-sm-8">
        <input type="text" class="form-control" name="username" id="username" placeholder="Please enter a username.">
      </div>
    </div>
    <div class="form-group row p-2">
      <div class="col-sm-8">
        <input type="password" class="form-control" name="password" id="password" placeholder="Please enter the password you would like to use.">
      </div>
    </div>
    <div class="form-group row p-2">
      <div class="col-sm-8">
        <input type="password" class="form-control" name="verpassword" id="verpassword" placeholder="Please enter the same password to verify.">
      </div>
    </div>
    <?php if (isset($showRC)) { ?>
      <div class="form-group row p-2">
        <div class="col-sm-8">
          <div class="g-recaptcha" id="captcha" data-sitekey="<?php echo $recaptcha['public']; ?>"></div>
        </div>
      </div>
    <?php } ?>
    <div class="form-group row p-2">
      <div class="col-sm-12">
        <button type="submit" class="btn btn-success">Sign up</button>
      </div>
    </div>
  </form>
</div>

        <?php } ?>
        
     

