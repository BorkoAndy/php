<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require 'db_config.php';

// Sanitize input
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// Validate input
if (empty($username) || empty($password)) {
  echo "<div class='alert alert-danger'>Benutzername und Passwort sind erforderlich.</div>";
  exit;
}

// Query user by email
$sql = "SELECT id, company_name, user_role, password_hash FROM users WHERE email = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
  // Set session variables
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['company_name'] = $user['company_name'];
  $_SESSION['user_role'] = $user['user_role'];

  // Redirect based on role
  if (in_array($user['user_role'], ['admin', 'superuser'])) {
    session_write_close();

    header("Location: /res/php/admin_panel.php");
    exit;
  } else {
    echo "<div class='alert alert-warning'>Zugriff verweigert. Sie haben keine Berechtigung, das Admin-Panel anzuzeigen.</div>";
    exit;
  }
} else {
  echo "<div class='alert alert-danger'>Ungültige Anmeldedaten.</div>";

  // Optional: Log failed attempt
  // file_put_contents('logs/login_failures.log', date('Y-m-d H:i:s') . " - Failed login for: $username\n", FILE_APPEND);
  exit;
}
