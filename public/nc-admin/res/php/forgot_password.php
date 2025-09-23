<?php
require 'db_config.php'; // your PDO connection

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate email
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

    if (!$email) {
        echo "Ungültige E-Mail-Adresse.";
        exit;
    }

    // Check if email exists in users table
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);

    if ($stmt->rowCount() === 0) {
        echo "Diese E-Mail ist nicht registriert.";
        exit;
    }

    // Generate secure token and expiration
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Save token to password_resets table
    $insert = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
    $insert->execute([
        ':email' => $email,
        ':token' => $token,
        ':expires_at' => $expires
    ]);

    // Build reset link
    $resetLink = "https://yourdomain.com/reset_password.php?token=$token";

    // Send email
    $subject = "Passwort zurücksetzen";
    $message = "Hallo,\n\nKlicken Sie auf den folgenden Link, um Ihr Passwort zurückzusetzen:\n\n$resetLink\n\nDieser Link ist 1 Stunde gültig.";
    $headers = "From: no-reply@yourdomain.com\r\nContent-Type: text/plain; charset=UTF-8";

    if (mail($email, $subject, $message, $headers)) {
        echo "Link zum Zurücksetzen wurde an Ihre E-Mail gesendet!";
    } else {
        echo "Fehler beim Senden der E-Mail. Bitte versuchen Sie es später erneut.";
    }

    exit;
}
?>