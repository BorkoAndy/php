<?php
include_once __DIR__ . '/../db_config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function getEnumValues($table, $column, $pdo)
{
    $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
    $values = str_getcsv($matches[1], ',', "'");
    return $values;
}
$roles = getEnumValues('users', 'role', $pdo);
$selectedRole = 'Standard'; // Or fetch from user data



$userRole = $_SESSION['user_role'] ?? '';
$canEdit = in_array($userRole, ['Admin', 'SuperUser']);

// Handle Add User
if ($canEdit && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $stmt = $pdo->prepare("
        INSERT INTO users (username, password, role)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([
        $_POST['username'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['role'] ?? 'Standard' // default role if not provided
    ]);
    header("Location: ?page=users");
    exit();
}

// Handle Edit User
if ($canEdit && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $stmt = $pdo->prepare("
        UPDATE users SET username = ?, role = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $_POST['username'],
        $_POST['role'],
        $_POST['user_id'],
        password_hash($_POST['password'], PASSWORD_DEFAULT)
    ]);
    header("Location: ?page=users");
    exit();
}

// Handle Delete User
if ($canEdit && isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    header("Location: ?page=users");
    exit();
}

// Fetch Users
$users = $pdo->query("
    SELECT id, username, role, created_at
    FROM users
    ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Benutzer</h2>
    <?php if ($canEdit): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
    <?php endif; ?>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Rolle</th>
            <th>Registriert</th>
            <?php if ($canEdit): ?><th>Maßnahmen</th><?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                <?php if ($canEdit): ?>
                    <?php if($user['role'] === 'SuperUser' && $userRole !== 'SuperUser') continue; ?>                        
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $user['id'] ?>">Edit</button>
                        <a href="?page=users&delete_user=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add User Modal -->
<?php if ($canEdit): ?>
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Benutzer hinzufügen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                    <input type="hidden" name="add_user" value="1">

                    <!-- Personal Info -->
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>


                    <!-- Account Info -->
                    <div class="col-md-6">
                        <label class="form-label">Passwort</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Benutzerrolle</label>
                        <?php
                        echo '<select name="role" class="form-select">';
                        foreach ($roles as $role) {
                            $selected = ($role === $selectedRole) ? 'selected' : '';
                            echo "<option value=\"$role\" $selected>" . ucfirst($role) . "</option>";
                        }
                        echo '</select>';
                        ?>
                        <!-- <select name="user_role" class="form-select">
                            <option value="regular" selected>Regular</option>
                            <option value="premium">Premium</option>
                            <option value="vip">VIP</option>
                            <option value="admin">Admin</option>
                            <option value="superuser">Superuser</option>
                        </select> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Erstellen</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- Edit User Modals -->
<?php if ($canEdit): ?>
    <?php foreach ($users as $user): ?>
        <div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Benutzer bearbeiten: <?= htmlspecialchars($user['username']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row g-3">
                        <input type="hidden" name="edit_user" value="1">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                        <!-- Personal Info -->
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>
                        <!-- Role -->
                        <div class="col-md-6">
                            <label class="form-label">Benutzerrolle</label>

                            <?php
                            echo '<select name="role" class="form-select">';
                            foreach ($roles as $role) {
                                $selected = ($role === $selectedRole) ? 'selected' : '';
                                echo "<option value=\"$role\" $selected>" . ucfirst($role) . "</option>";
                            }
                            echo '</select>';
                            ?>

                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Passwort</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script src="../js/fetch_countries.js"></script>