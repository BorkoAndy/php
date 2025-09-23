<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Passwort vergessen | E-Shop</title>
  <link rel="stylesheet" href="../css/forgot.css" />
</head>
<body>
  <div class="container">
    <form id="forgotForm">
      <h2>Passwort vergessen</h2>
      <p>Bitte geben Sie Ihre E-Mail-Adresse ein, um Ihr Passwort zurückzusetzen.</p>

      <label for="email">E-Mail-Adresse</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" required />

      <div id="feedback">
</div>

      <button type="submit">Link zum Zurücksetzen senden</button>
    </form>
    <?php unset($_SESSION['feedback']); ?>

  </div>

  <script src="../js/forgot.js"></script>
</body>
</html>