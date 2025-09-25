<?php
include_once __DIR__ . '/../db_config.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Role check
$userRole = $_SESSION['user_role'] ?? 'Standard';
$canEdit = in_array($userRole, ['Admin', 'SuperUser']);

// Get ENUM values for icon_collection
function getEnumValues(PDO $pdo, string $table, string $column): array
{
  $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$row || strpos($row['Type'], 'enum') === false) return [];

  preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
  $values = str_getcsv($matches[1], ',', "'");
  return array_combine($values, $values);
}

$iconOptions = getEnumValues($pdo, 'Weather', 'icon_collection');

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
  $stmt = $pdo->prepare("INSERT INTO Weather (domain, token, latitude, longitude, icon_collection, published) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([
    $_POST['domain'],
    $_POST['token'],
    $_POST['latitude'],
    $_POST['longitude'],
    $_POST['icon_collection'],
    isset($_POST['published']) ? 1 : 0
  ]);
  header("Location: admin_panel.php?page=weather");
  exit;
}

// Handle Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit') {
  $stmt = $pdo->prepare("UPDATE Weather SET domain=?, token=?, latitude=?, longitude=?, icon_collection=?, published=? WHERE id=?");
  $stmt->execute([
    $_POST['domain'],
    $_POST['token'],
    $_POST['latitude'],
    $_POST['longitude'],
    $_POST['icon_collection'],
    isset($_POST['published']) ? 1 : 0,
    $_POST['id']
  ]);
  header("Location: admin_panel.php?page=weather");
  exit;
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
  $stmt = $pdo->prepare("DELETE FROM Weather WHERE id=?");
  $stmt->execute([$_POST['id']]);
  header("Location: admin_panel.php?page=weather");
  exit;
}

// Fetch data
$weatherData = $pdo->query("SELECT * FROM Weather ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2>Weather Entries</h2>
  <?php if ($canEdit): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
      <i class="bi bi-plus-circle"></i> Add New Entry
    </button>
  <?php endif; ?>
</div>

<div class="container mt-4">
  <div class="table-responsive">

    <table id="weather" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Domain</th>
          <th>Token</th>
          <th>Latitude</th>
          <th>Longitude</th>
          <th>Icon Collection</th>
          <th>Published</th>
          <th>Created At</th>
          <?php if ($canEdit): ?><th>Actions</th><?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($weatherData as $row): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['domain']) ?></td>
            <td><?= htmlspecialchars($row['token']) ?></td>
            <td><?= htmlspecialchars($row['latitude']) ?></td>
            <td><?= htmlspecialchars($row['longitude']) ?></td>
            <td><?= $row['icon_collection'] ?: '—' ?></td>
            <td><?= $row['published'] ? '✅' : '❌' ?></td>
            <td><?= $row['created_at'] ?></td>
            <?php if ($canEdit): ?>
              <td>
                 <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>" title="Edit">
    <i class="bi bi-pencil"></i>
  </button>
  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id'] ?>" title="Delete">
    <i class="bi bi-trash"></i>
  </button>

              </td>
            <?php endif; ?>
          </tr>

          <!-- Edit Modal -->
          <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
              <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Entry #<?= $row['id'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="text" name="domain" class="form-control mb-2" value="<?= $row['domain'] ?>" required>
                    <input type="text" name="token" class="form-control mb-2" value="<?= $row['token'] ?>" required>
                    <input type="text" name="latitude" class="form-control mb-2" value="<?= $row['latitude'] ?>" required>
                    <input type="text" name="longitude" class="form-control mb-2" value="<?= $row['longitude'] ?>" required>
                    <select name="icon_collection" class="form-select mb-2">
                      <?php foreach ($iconOptions as $value => $label): ?>
                        <option value="<?= $value ?>" <?= $row['icon_collection'] === $value ? 'selected' : '' ?>>
                          <?= $label ?: '—' ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <div class="form-check form-switch mb-2">
                      <input class="form-check-input" type="checkbox" name="published" id="editPublished<?= $row['id'] ?>" value="1" <?= $row['published'] ? 'checked' : '' ?>>
                      <label class="form-check-label" for="editPublished<?= $row['id'] ?>">Published</label>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- Delete Modal -->
          <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
              <form method="POST">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Delete Entry #<?= $row['id'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    Are you sure you want to delete this entry?
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>

<!-- Add Modal -->
<?php if ($canEdit): ?>
  <div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
      <form method="POST">
        <input type="hidden" name="action" value="add">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add New Entry</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="text" name="domain" class="form-control mb-2" placeholder="Domain" required>
            <input type="text" name="token" class="form-control mb-2" placeholder="Token" required>
            <input type="text" name="latitude" class="form-control mb-2" placeholder="Latitude" required>
            <input type="text" name="longitude" class="form-control mb-2" placeholder="Longitude" required>

            <select name="icon_collection" class="form-select mb-2">
              <?php foreach ($iconOptions as $value => $label): ?>
                <option value="<?= $value ?>"><?= $label ?: '—' ?></option>
              <?php endforeach; ?>
            </select>

            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="published" id="addPublished" value="1">
              <label class="form-check-label" for="addPublished">Published</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add Entry</button>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>


<!-- <style>
  .table td {
  max-width: 200px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style> -->