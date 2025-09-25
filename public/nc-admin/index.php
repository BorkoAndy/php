<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// include_once 'res/php/includes/auth.php';
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | NC-Werbung Admin Panel</title>
  <link rel="stylesheet" href="res/css/styles.css" />
</head>
<body>
  <div class="container">
    <form class="form-box" action="res/php/login.php" method="POST">
      <h2>Login</h2>
      <label for="email">Benutzername</label>
      <input type="text" id="email" name="identifier" required />

      <label for="password">Passwort</label>
      <div class="password-row">
        <input type="password" id="password" name="password" required />
        <!-- <a href="res/php/forgot.php" class="forgot">Passwort vergessen?</a> -->
      </div>

      <button type="submit">Login</button>

      <!-- <p class="signup-text">
        Sie haben noch kein Konto? <a href="res/php/signup.php">Anmelden</a>
      </p> -->
    </form>
  </div>
  <script src="res/js/scripts.js"></script>  
</body>
</html>