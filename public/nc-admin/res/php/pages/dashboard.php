<?php
include_once __DIR__ . '/../db_config.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Fetch counts
// $categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
// $productCount = $pdo->query("SELECT COUNT(DISTINCT ARTIKEL_ARTNR) FROM ARTIKEL")->fetchColumn();
// $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); // Adjust table name if needed

// Fetch latest orders
//$orders = $pdo->query("SELECT id, product, customer FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Fetch notifications
//$notifications = $pdo->query("SELECT message FROM notifications WHERE is_read = 0 ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<!-- Dashboard Cards -->
<!-- <section class="cards d-flex gap-3 mt-4">
  <a href="?page=categories" class="card p-3 flex-fill text-center text-decoration-none text-dark">
    <div class="mb-2">
      <i class="bi bi-folder-fill fs-2 text-primary"></i>
    </div>
    <h3>Kategorien</h3>
    <p class="fs-4"><?= $categoryCount ?></p>
  </a>

  <a href="?page=products" class="card p-3 flex-fill text-center text-decoration-none text-dark">
    <div class="mb-2">
      <i class="bi bi-box-seam fs-2 text-success"></i>
    </div>
    <h3>Produkte</h3>
    <p class="fs-4"><?= $productCount ?></p>
  </a>

  <a href="?page=users" class="card p-3 flex-fill text-center text-decoration-none text-dark">
    <div class="mb-2">
      <i class="bi bi-people-fill fs-2 text-warning"></i>
    </div>
    <h3>Benutzer</h3>
    <p class="fs-4"><?= $userCount ?></p>
  </a>
</section> -->

<!-- Latest Orders -->
<!-- <section class="orders mt-5">
  <h2>Neueste Bestellungen</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Bestellung</th>
        <th>Produkt</th>
        <th>Kunde</th>
      </tr>
    </thead>
    <tbody> -->
      <?php //foreach ($orders as $order): ?>
        <!-- <tr>
          <td>#<?= htmlspecialchars($order['id']) ?></td>
          <td><?= htmlspecialchars($order['product']) ?></td>
          <td><?= htmlspecialchars($order['customer']) ?></td>
        </tr> -->
      <?php //endforeach; ?>
    <!-- </tbody>
  </table>
</section> -->

<!-- Notifications -->
<section class="notifications mt-5">
  <!-- <h2>Benachrichtigungen</h2>
  <ul class="list-group"> -->
    <?php //foreach ($notifications as $note): ?>
      <!-- <li class="list-group-item">
        <i class="bi bi-bell-fill me-2 text-danger"></i>
        <?= htmlspecialchars($note['message']) ?>
      </li> -->
    <?php //endforeach; ?>
  </ul>
</section>