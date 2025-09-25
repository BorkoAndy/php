<?php
include_once 'includes/auth.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Panel | NC-Werbung</title>
  <link rel="stylesheet" href="../css/admin_panel_styles.css" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body>
  <div class="container">
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
      <?php include 'includes/header.php'; ?>

      <?php
      $page = $_GET['page'] ?? 'dashboard';

      switch ($page) {
        case 'weather':
          include 'pages/weather.php';
          break;
        case 'instawall':
          include 'pages/instawall.php';
          break;
        case 'translate':
          include 'pages/translate.php';
          break;
        case 'users':
          include 'pages/users.php';
          break;
        case 'icons':
          include 'pages/icons.php';
          break;

          // Add more cases as needed
        default:
          include 'pages/dashboard.php';
          break;
      }
      ?>
    </main>
  </div>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>