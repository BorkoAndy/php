<header class="header d-flex justify-content-between align-items-center px-3 py-2 bg-light border-bottom">
  <h1 class="h4 m-0">Admin Panel</h1>

  <div class="d-flex align-items-center gap-3">
    <span class="notification position-relative">
      🔔<span class="badge bg-danger position-absolute top-0 start-100 translate-middle">3</span>
    </span>

    <?php if (isset($_SESSION['user_id'])): ?>
      <div class="user-info text-end">
        <div class="fw-bold"><?= htmlspecialchars($_SESSION['username']) ?></div>
        <small class="text-muted"><?= htmlspecialchars($_SESSION['user_role']) ?></small>
      </div>
    <?php else: ?>
      <div class="user-info text-end">
        <div class="fw-bold">Guest</div>
        <small class="text-muted">No Role</small>
      </div>
    <?php endif; ?>
  </div>
</header>
