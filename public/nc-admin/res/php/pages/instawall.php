<?php
include_once __DIR__ . '/../db_config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userRole = $_SESSION['user_role'] ?? 'Standard';
$canEdit = in_array($userRole, ['Admin', 'SuperUser']);

// Handle Add/Edit/Delete logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add' && $canEdit) {
        $stmt = $pdo->prepare("INSERT INTO instawalls (insta_name, domain, insta_token, published) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['insta_name'],
            $_POST['domain'],
            $_POST['insta_token'],
            isset($_POST['published']) ? 1 : 0
        ]);
    }

    if ($action === 'edit' && $canEdit) {
        $stmt = $pdo->prepare("UPDATE instawalls SET insta_name = ?, domain = ?, insta_token = ?, published = ? WHERE id = ?");
        $stmt->execute([
            $_POST['insta_name'],
            $_POST['domain'],
            $_POST['insta_token'],
            isset($_POST['published']) ? 1 : 0,
            $_POST['id']
        ]);
    }

    if ($action === 'delete' && $canEdit) {
        $stmt = $pdo->prepare("DELETE FROM instawalls WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "?page=instawall");
    exit;
}

// Fetch entries
$stmt = $pdo->query("SELECT * FROM instawalls ORDER BY created_at DESC");
$entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>InstaWalls</h2>
    <?php if ($canEdit): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-circle"></i> Add InstaWall
        </button>
    <?php endif; ?>
</div>

<table class="table table-bordered table-striped table-sm">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Domain</th>
            <th>Token</th>
            <th>Published</th>
            <th>Updated</th>
            <th>Created</th>
            <?php if ($canEdit): ?><th>Actions</th><?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($entries as $entry): ?>
            <?php
            $updated = new DateTime($entry['updated_at']);
            $now = new DateTime();
            $diffDays = $updated->diff($now)->days;

            $rowClass = '';
            if ($diffDays > 60) {
                $rowClass = 'table-danger'; // red
            } elseif ($diffDays > 40) {
                $rowClass = 'table-warning'; // yellow
            }
            ?>

            <tr class="<?= $rowClass ?>">
                <td><?= htmlspecialchars($entry['insta_name']) ?></td>
                <td><?= htmlspecialchars($entry['domain']) ?></td>
                <td><small><?= substr($entry['insta_token'], 0, 20) ?>…</small></td>
                <td><?= $entry['published'] ? '✅' : '❌' ?></td>
                <td><?= date('d.m.Y H:i', strtotime($entry['updated_at'])) ?></td>
                <td><?= date('d.m.Y H:i', strtotime($entry['created_at'])) ?></td>
                <?php if ($canEdit): ?>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $entry['id'] ?>"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $entry['id'] ?>"><i class="bi bi-trash"></i></button>
                    </td>
                <?php endif; ?>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $entry['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" value="<?= $entry['id'] ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit InstaWall</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="insta_name" class="form-control mb-2" value="<?= htmlspecialchars($entry['insta_name']) ?>" required>
                                <input type="text" name="domain" class="form-control mb-2" value="<?= htmlspecialchars($entry['domain']) ?>" required>
                                <textarea name="insta_token" class="form-control mb-2" rows="2" required><?= htmlspecialchars($entry['insta_token']) ?></textarea>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="published" id="published<?= $entry['id'] ?>" <?= $entry['published'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="published<?= $entry['id'] ?>">Published</label>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-secondary">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal<?= $entry['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $entry['id'] ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete InstaWall</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete <strong><?= htmlspecialchars($entry['insta_name']) ?></strong>?
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

<!-- Add Modal -->
<?php if ($canEdit): ?>
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add InstaWall</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="insta_name" class="form-control mb-2" placeholder="Name" required>
                        <input type="text" name="domain" class="form-control mb-2" placeholder="Domain" required>
                        <textarea name="insta_token" class="form-control mb-2" rows="2" placeholder="Token" required></textarea>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="published" id="publishedNew">
                            <label class="form-check-label" for="publishedNew">Published</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>