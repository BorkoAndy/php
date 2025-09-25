<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require 'db_config.php';

// Sanitize input
$identifier = trim($_POST['identifier'] ?? '');
$password = $_POST['password'] ?? '';

// Validate input
if (empty($identifier) || empty($password)) {
  echo "<div class='alert alert-danger'>Benutzername und Passwort sind erforderlich.</div>";
  exit;
}

// Query user by email or username
$sql = "SELECT id, username, role, password FROM users WHERE username = :identifier";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':identifier', $identifier);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC); // ✅ Fetch the result

if ($user && password_verify($password, $user['password'])) {
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['user_role'] = $user['role'];

  if (in_array($user['role'], ['Standard','Admin', 'SuperUser'])) {
    session_write_close();
    header("Location: admin_panel.php");
    exit;
  } else {
    echo $user['role']."<div class='alert alert-warning'>Zugriff verweigert. Sie haben keine Berechtigung, das Admin-Panel anzuzeigen.</div>";
    exit;
  }
} else {
  echo "<div class='alert alert-danger'>Ungültige Anmeldedaten.</div>";
  exit;
}