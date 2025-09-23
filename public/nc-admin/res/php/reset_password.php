<?php
session_start();
require 'db_config.php';

$token = $_GET['token'] ?? '';

$stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
$stmt->execute([':token' => $token]);

if ($stmt->rowCount() === 0) {
    echo "Ungültiger oder abgelaufener Link.";
    exit;
}

$email = $stmt->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm || strlen($password) < 6) {
        echo "Passwörter stimmen nicht überein oder sind zu kurz.";
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $pdo->prepare("UPDATE users SET password_hash = :hash WHERE email = :email")
        ->execute([':hash' => $hash, ':email' => $email]);

    $pdo->prepare("DELETE FROM password_resets WHERE email = :email")->execute([':email' => $email]);

    echo "Passwort erfolgreich zurückgesetzt. <a href='login.php'>Jetzt einloggen</a>";
    exit;
}
?>

<form method="post">
  <input type="password" name="password" placeholder="Neues Passwort" required />
  <input type="password" name="confirm" placeholder="Passwort bestätigen" required />
  <button type="submit">Passwort zurücksetzen</button>
</form>