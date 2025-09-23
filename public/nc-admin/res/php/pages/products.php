<?php
include_once __DIR__ . '/../db_config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userRole = $_SESSION['user_role'] ?? '';
$canEdit = in_array($userRole, ['admin', 'superuser']);
$categoryId = $_GET['category_id'] ?? null;

if (!$categoryId) {
    echo "<div class='alert alert-warning'>No category selected.</div>";
    return;
}
$categoryName = 'Unknown Category';
if ($categoryId) {
    $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->execute([$categoryId]);
    $categoryName = $stmt->fetchColumn() ?: $categoryName;
}

// Handle Add Product
if ($canEdit && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $artnr = $_POST['artnr'];
    $bez1 = $_POST['bez1'];
    $bez2 = $_POST['bez2'];
    $text1 = $_POST['text1'];
    $text2 = $_POST['text2'];
    $image = $_POST['image'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $currency = $_POST['currency'];

    $pdo->prepare("INSERT INTO ARTIKEL (ARTIKEL_ARTNR, ARTIKEL_BEZ1, ARTIKEL_BEZ2, ARTIKEL_TEXT, ARTIKEL_TEXT2, image_path, ARTIKEL_DEF9) VALUES (?, ?, ?, ?, ?, ?, ?)")
        ->execute([$artnr, $bez1, $bez2, $text1, $text2, $image, $category]);

    $pdo->prepare("INSERT INTO preis_vk (PREIS_VK_KEY, PREIS_VK_BRUTTO, PREIS_VK_WAEH) VALUES (?, ?, ?)")
        ->execute([$artnr, $price, $currency]);

    header("Location: ?page=products&category_id=$category");
    exit();
}

// Handle Edit Product
if ($canEdit && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $artnr = $_POST['artnr'];
    $bez1 = $_POST['bez1'];
    $bez2 = $_POST['bez2'];
    $text1 = $_POST['text1'];
    $text2 = $_POST['text2'];
    $image = $_POST['image'];
    $newPrice = floatval($_POST['price']);
// Then update your database with $newPrice

    $stmt = $pdo->prepare("UPDATE ARTIKEL SET ARTIKEL_BEZ1 = ?, ARTIKEL_BEZ2 = ?, ARTIKEL_TEXT = ?, ARTIKEL_TEXT2 = ?, image_path = ? WHERE ARTIKEL_ARTNR = ?");
    $stmt->execute([$bez1, $bez2, $text1, $text2, $image, $artnr]);
    header("Location: ?page=products&category_id=$categoryId");
    exit();
}

// Handle Delete Product
if ($canEdit && isset($_GET['confirm_delete_product'])) {
    $artnr = $_GET['confirm_delete_product'];

    // Delete product from ARTIKEL
    $pdo->prepare("DELETE FROM ARTIKEL WHERE ARTIKEL_ARTNR = ?")->execute([$artnr]);

    // Delete price from preis_vk
    $pdo->prepare("DELETE FROM preis_vk WHERE PREIS_VK_KEY = ?")->execute([$artnr]);

    header("Location: ?page=products&category_id=$categoryId");
    exit();
}

// Fetch Products with Price
$stmt = $pdo->prepare("
  SELECT 
    a.ARTIKEL_ARTNR, a.ARTIKEL_BEZ1, a.ARTIKEL_BEZ2, a.ARTIKEL_TEXT, a.ARTIKEL_TEXT2, a.image_path,
    p.PREIS_VK_BRUTTO, p.PREIS_VK_WAEH
  FROM ARTIKEL a
  LEFT JOIN preis_vk p ON p.PREIS_VK_KEY = a.ARTIKEL_ARTNR
  WHERE a.ARTIKEL_DEF9 = ?
");
$stmt->execute([$categoryId]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php if ($canEdit): ?>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
        Produkt hinzufügen
    </button>
    
<?php endif; ?>
<div class="row">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <?php if (!empty($product['image_path'])): ?>
                    <img src="<?= htmlspecialchars($product['image_path']) ?>" class="card-img-top" alt="Product Image">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title mb-1"><?= htmlspecialchars($product['ARTIKEL_BEZ1']) ?></h5>
                    <p class="text-muted mb-2">Artikelnumer: <?= htmlspecialchars($product['ARTIKEL_ARTNR']) ?></p>

                    <?php
                    $shortText = trim($product['ARTIKEL_TEXT']);
                    $shortText = strlen($shortText) > 80 ? substr($shortText, 0, 77) . '...' : $shortText;
                    ?>
                    <p class="card-text small"><?= nl2br(htmlspecialchars($shortText)) ?></p>

                    <p class="fw-bold mt-2">Preis: <?= number_format($product['PREIS_VK_BRUTTO'], 2) . ' ' . htmlspecialchars($product['PREIS_VK_WAEH']) ?></p>

                    <?php if ($canEdit): ?>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal<?= $product['ARTIKEL_ARTNR'] ?>">Edit</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProductModal<?= $product['ARTIKEL_ARTNR'] ?>">Delete</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <!-- Edit Modal -->
        <div class="modal fade" id="editProductModal<?= $product['ARTIKEL_ARTNR'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Produkt bearbeiten</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="artnr" value="<?= $product['ARTIKEL_ARTNR'] ?>">
                        <div class="mb-3">
                            <label class="form-label">Beschreibung 1</label>
                            <input type="text" name="bez1" class="form-control" value="<?= htmlspecialchars($product['ARTIKEL_BEZ1']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Beschreibung 2</label>
                            <input type="text" name="bez2" class="form-control" value="<?= htmlspecialchars($product['ARTIKEL_BEZ2']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Text 1</label>
                            <textarea name="text1" class="form-control"><?= htmlspecialchars($product['ARTIKEL_TEXT']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Text 2</label>
                            <textarea name="text2" class="form-control"><?= htmlspecialchars($product['ARTIKEL_TEXT2']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bild-URL</label>
                            <input type="url" name="image" class="form-control" value="<?= htmlspecialchars($product['image_path']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preis (€)</label>
                            <input type="number" step="0.01" name="price" class="form-control"
                                value="<?= number_format((float)$product['PREIS_VK_BRUTTO'], 2, '.', '') ?>" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="edit_product" class="btn btn-success">Änderungen speichern</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteProductModal<?= $product['ARTIKEL_ARTNR'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <form method="GET" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Löschen bestätigen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Produkt löschen <strong><?= htmlspecialchars($product['ARTIKEL_BEZ1'] . ' ' . $product['ARTIKEL_BEZ2']) ?></strong>?</p>
                        <input type="hidden" name="page" value="products">
                        <input type="hidden" name="category_id" value="<?= $categoryId ?>">
                        <input type="hidden" name="confirm_delete_product" value="<?= $product['ARTIKEL_ARTNR'] ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Löschen</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($canEdit): ?>
    <!-- Add Product Modal -->

    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Produkt zur Kategorie hinzufügen: <?= htmlspecialchars($categoryName) ?></h5> <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="add_product" value="1">
                    <input type="hidden" name="category" value="<?= $categoryId ?>">

                    <div class="mb-3">
                        <label class="form-label">Artikelnumer</label>
                        <input type="text" name="artnr" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Beschreibung 1</label>
                        <input type="text" name="bez1" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Beschreibung 2</label>
                        <input type="text" name="bez2" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Text 1</label>
                        <textarea name="text1" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Text 2</label>
                        <textarea name="text2" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bild-URL</label>
                        <input type="url" name="image" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Preis</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                            <select name="currency" class="form-select w-auto">
                                <option value="EUR" selected>EUR</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Produkt hinzufügen</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>