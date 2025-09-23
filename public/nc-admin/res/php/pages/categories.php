<?php
include_once __DIR__ . '/../db_config.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$userRole = $_SESSION['user_role'] ?? '';
$canEdit = in_array($userRole, ['admin', 'superuser']);
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

// Handle Add
if ($canEdit && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
  $name = $_POST['name'];
  $image = $_POST['image'];
  $shopVisible = isset($_POST['shop_visible']) ? 1 : 0;
  $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

  if (!in_array($ext, $allowedExtensions)) {
    echo "<div class='alert alert-danger'>Invalid image format. Allowed: jpg, jpeg, png, webp, gif.</div>";
  } else {
    $stmt = $pdo->prepare("INSERT INTO categories (name, image_path, shop_visible) VALUES (?, ?, ?)");
    $stmt->execute([$name, $image, $shopVisible]);
    header("Location: ?page=categories");
    exit();
  }
}

// Handle Edit
if ($canEdit && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $image = $_POST['image'];
  $shopVisible = isset($_POST['shop_visible']) ? 1 : 0;
  $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

  if (!in_array($ext, $allowedExtensions)) {
    echo "<div class='alert alert-danger'>Invalid image format. Allowed: jpg, jpeg, png, webp, gif.</div>";
  } else {
    $stmt = $pdo->prepare("UPDATE categories SET name = ?, image_path = ?, shop_visible = ? WHERE id = ?");
    $stmt->execute([$name, $image, $shopVisible, $id]);
    header("Location: ?page=categories&visibility=" . ($_GET['visibility'] ?? 'all'));
    exit();
  }
}

// Handle Delete
if ($canEdit && isset($_GET['confirm_delete'])) {
  $id = $_GET['confirm_delete'];
  $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
  header("Location: ?page=categories&visibility=" . ($_GET['visibility'] ?? 'all'));
  exit();
}

// Visibility filter
$visibilityFilter = $_GET['visibility'] ?? 'all';
$query = "
  SELECT c.id, c.name, c.image_path, c.shop_visible,
  (SELECT COUNT(*) 
 FROM ARTIKEL a 
 LEFT JOIN preis_vk p ON p.PREIS_VK_KEY = a.ARTIKEL_ARTNR 
 WHERE a.ARTIKEL_DEF9 = c.id
) AS product_count
  FROM categories c
";

if ($visibilityFilter === 'visible') {
  $query .= " WHERE c.shop_visible = 1";
} elseif ($visibilityFilter === 'invisible') {
  $query .= " WHERE c.shop_visible = 0";
}

$query .= " ORDER BY c.shop_visible DESC, c.name ASC";
$categories = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mt-4 mb-3 gap-3">
  <!-- Left: Title -->
  <h2 class="mb-0">Kategorien</h2>

  <!-- Center: Filter -->
  <form method="GET" class="d-flex align-items-center gap-2">
    <input type="hidden" name="page" value="categories">
    <label for="visibilityFilter" class="form-label mb-0">Sichtbarkeit:</label>
    <select name="visibility" id="visibilityFilter" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
      <option value="all" <?= $visibilityFilter === 'all' ? 'selected' : '' ?>>Alle</option>
      <option value="visible" <?= $visibilityFilter === 'visible' ? 'selected' : '' ?>>Sichtbar</option>
      <option value="invisible" <?= $visibilityFilter === 'invisible' ? 'selected' : '' ?>>Unsichtbar</option>
    </select>
  </form>

  <!-- Right: Add Button -->
  <?php if ($canEdit): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
      Kategorie hinzufügen
    </button>
  <?php endif; ?>
</div>


<div class="container mt-4">
  <div class="row">
    <?php foreach ($categories as $cat): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 <?= $cat['shop_visible'] ? '' : 'shop_invisible' ?>">
          <a href="?page=products&category_id=<?= $cat['id'] ?>" class="text-decoration-none text-dark">

            <img src="<?= htmlspecialchars($cat['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($cat['name']) ?>">
          </a>
          <div class="card-body">
            <h5 class="card-title text-truncate" style="max-width: 100%"><?= htmlspecialchars($cat['name']) ?></h5>
            <p class="card-text">Produkte: <?= $cat['product_count'] ?></p>
            <?php if ($canEdit): ?>
              <div class="d-flex gap-2">
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $cat['id'] ?>">Bearbeiten</button>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $cat['id'] ?>">Löschen</button>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Delete Modal -->
      <div class="modal fade" id="deleteModal<?= $cat['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <form method="GET" action="" class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Löschen bestätigen</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <p>Sind Sie sicher, dass Sie löschen möchten? <strong><?= htmlspecialchars($cat['name']) ?></strong>?</p>
              <input type="hidden" name="page" value="categories">
              <input type="hidden" name="visibility" value="<?= htmlspecialchars($visibilityFilter) ?>">
              <input type="hidden" name="confirm_delete" value="<?= $cat['id'] ?>">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-danger">Löschen</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Edit Modal -->
      <div class="modal fade" id="editModal<?= $cat['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <form method="POST" class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Kategorie bearbeiten</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="id" value="<?= $cat['id'] ?>">
              <div class="mb-3">
                <label class="form-label">Kategorie-ID</label>
                <input type="text" class="form-control" value="<?= $cat['id'] ?>" readonly>
              </div>
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($cat['name']) ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Bild-URL</label>
                <input type="url" name="image" class="form-control" value="<?= htmlspecialchars($cat['image_path']) ?>" required>
              </div>
              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="shop_visible" id="shopVisibleEdit<?= $cat['id'] ?>" <?= $cat['shop_visible'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="shopVisibleEdit<?= $cat['id'] ?>">Im Shop sichtbar</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="edit" class="btn btn-success">Änderungen speichern</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
            </div>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>





<!-- Add Category Modal -->
<?php if ($canEdit): ?>
  <div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
      <form method="POST" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Kategorie hinzufügen</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="add" value="1">
          <div class="mb-3">
            <label class="form-label">Kategorie-ID</label>
            <input type="text" class="form-control" value="Auto-generated" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Kategorie Name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Bild-URL</label>
            <input type="url" name="image" class="form-control" placeholder="Bild-URL (.jpg, .png, .webp)" required>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="shop_visible" id="shopVisibleAdd" checked>
            <label class="form-check-label" for="shopVisibleAdd">Im Shop sichtbar</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Kategorie hinzufügen</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>