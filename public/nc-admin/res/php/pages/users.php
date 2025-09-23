<?php
include_once __DIR__ . '/../db_config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userRole = $_SESSION['user_role'] ?? '';
$canEdit = in_array($userRole, ['admin', 'superuser']);

// Handle Add User
if ($canEdit && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $stmt = $pdo->prepare("
    INSERT INTO users (
      user_role, company_name, first_name, last_name, phone_number, email, street, city, postal_code, country,
      vat_number, password_hash, wants_common_info, wants_sales_info, wants_technical_info, other_info,
      trade_license_path, terms_accepted, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
  ");
    $stmt->execute([
        $_POST['user_role'],
        $_POST['company_name'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['phone_number'],
        $_POST['email'],
        $_POST['street'],
        $_POST['city'],
        $_POST['postal_code'],
        $_POST['country'],
        $_POST['vat_number'] ?? null,
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        isset($_POST['wants_common_info']) ? 1 : 0,
        isset($_POST['wants_sales_info']) ? 1 : 0,
        isset($_POST['wants_technical_info']) ? 1 : 0,
        $_POST['other_info'] ?? null,
        $_POST['trade_license_path'] ?? null,
        isset($_POST['terms_accepted']) ? 1 : 0
    ]);
    header("Location: ?page=users");
    exit();
}

// Handle Edit User
if ($canEdit && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $stmt = $pdo->prepare("
    UPDATE users SET 
      first_name = ?, last_name = ?, company_name = ?, email = ?, user_role = ?, country = ?
    WHERE id = ?
  ");
    $stmt->execute([
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['company_name'],
        $_POST['email'],
        $_POST['user_role'],
        $_POST['country'],
        $_POST['user_id']
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
  SELECT 
    id, user_role, company_name, first_name, last_name, phone_number, email, street, city, postal_code, country,
    vat_number, trade_license_path, wants_common_info, wants_sales_info, wants_technical_info,
    other_info, terms_accepted, created_at
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
            <th>Name</th>
            <th>Unternehmen</th>
            <th>E-Mail</th>
            <th>Rolle</th>
            <th>Land</th>
            <th>Registriert</th>
            <?php if ($canEdit): ?><th>Maßnahmen</th><?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                <td><?= htmlspecialchars($user['company_name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['user_role']) ?></td>
                <td><?= htmlspecialchars($user['country']) ?></td>
                <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                <?php if ($canEdit): ?>
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
                        <label class="form-label">Vorname</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nachname</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>

                    <!-- Company Info -->
                    <div class="col-md-6">
                        <label class="form-label">Firmenname</label>
                        <input type="text" name="company_name" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Umsatzsteuer-Identifikationsnummer(VAT)</label>
                        <input type="text" name="vat_number" class="form-control">
                    </div>

                    <!-- Contact Info -->
                    <div class="col-md-6">
                        <label class="form-label">E-Mail</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telefonnummer</label>
                        <input type="text" name="phone_number" class="form-control">
                    </div>

                    <!-- Address -->
                    <div class="col-md-6">
                        <label class="form-label">Straße</label>
                        <input type="text" name="street" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Stadt</label>
                        <input type="text" name="city" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Postleitzahl</label>
                        <input type="text" name="postal_code" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Land</label>
                        <select name="country" id="countrySelect" class="form-select" required>
                            <option value="<?= htmlspecialchars($user['country'] ?? '') ?>" selected>Österreich</option>
                        </select>
                    </div>

                    <!-- Account Info -->
                    <div class="col-md-6">
                        <label class="form-label">Passwort</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Benutzerrolle</label>
                        <select name="user_role" class="form-select">
                            <option value="regular" selected>Regular</option>
                            <option value="premium">Premium</option>
                            <option value="vip">VIP</option>
                            <option value="admin">Admin</option>
                            <option value="superuser">Superuser</option>
                        </select>
                    </div>

                    <!-- Preferences -->
                    <div class="col-md-12">
                        <label class="form-label">Einstellungen</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="wants_common_info" value="1">
                            <label class="form-check-label">Allgemeine Informationen</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="wants_sales_info" value="1">
                            <label class="form-check-label">Verkaufsinformationen</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="wants_technical_info" value="1">
                            <label class="form-check-label">Technische Informationen</label>
                        </div>
                    </div>

                    <!-- Other Info -->
                    <div class="col-md-12">
                        <label class="form-label">Weitere Informationen</label>
                        <textarea name="other_info" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Trade License -->
                    <div class="col-md-12">
                        <label class="form-label">Gewerbescheinpfad</label>
                        <input type="text" name="trade_license_path" class="form-control">
                    </div>

                    <!-- Terms -->
                    <div class="col-md-12 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="terms_accepted" value="1" required>
                            <label class="form-check-label">Ich akzeptiere die Allgemeinen Geschäftsbedingungen.</label>
                        </div>
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
                        <h5 class="modal-title">Benutzer bearbeiten: <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row g-3">
                        <input type="hidden" name="edit_user" value="1">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                        <!-- Personal Info -->
                        <div class="col-md-6">
                            <label class="form-label">Vorname</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nachname</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                        </div>

                        <!-- Company Info -->
                        <div class="col-md-6">
                            <label class="form-label">Firmenname</label>
                            <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($user['company_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Umsatzsteuer-Identifikationsnummer(VAT)</label>
                            <input type="text" name="vat_number" class="form-control" value="<?= htmlspecialchars($user['vat_number'] ?? '') ?>">
                        </div>

                        <!-- Contact Info -->
                        <div class="col-md-6">
                            <label class="form-label">E-Mail</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefonnummer</label>
                            <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>
">
                        </div>

                        <!-- Address -->
                        <div class="col-md-6">
                            <label class="form-label">Straße</label>
                            <input type="text" name="street" class="form-control" value="<?= htmlspecialchars($user['street'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stadt</label>
                            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Postleitzahl</label>
                            <input type="text" name="postal_code" class="form-control" value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Land</label>
                            <select name="country" id="countrySelect" class="form-select" required>
                                <option value="<?= htmlspecialchars($user['country'] ?? '') ?>" selected>Österreich</option>
                            </select>
                        </div>


                        <!-- Role -->
                        <div class="col-md-6">
                            <label class="form-label">Benutzerrolle</label>
                            <select name="user_role" class="form-select">
                                <?php
                                $roles = ['regular', 'premium', 'vip', 'admin', 'superuser'];
                                foreach ($roles as $role) {
                                    $selected = ($user['user_role'] === $role) ? 'selected' : '';
                                    echo "<option value=\"$role\" $selected>$role</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Preferences -->
                        <div class="col-md-12">
                            <label class="form-label">Einstellungen</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="wants_common_info" value="1" <?= ($user['wants_common_info'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label">Allgemeine Informationen</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="wants_sales_info" value="1" <?= ($user['wants_sales_info'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label">Verkaufsinformationen</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="wants_technical_info" value="1" <?= ($user['wants_technical_info'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label">Technische Informationen</label>
                            </div>
                        </div>

                        <!-- Other Info -->
                        <div class="col-md-12">
                            <label class="form-label">Weitere Informationen</label>
                            <textarea name="other_info" class="form-control" rows="3"><?= htmlspecialchars($user['other_info'] ?? '') ?></textarea>
                        </div>

                        <!-- Trade License -->
                        <div class="col-md-12">
                            <label class="form-label">Gewerbescheinpfad</label>
                            <input type="text" name="trade_license_path" class="form-control" value="<?= htmlspecialchars($user['trade_license_path'] ?? '') ?>">
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