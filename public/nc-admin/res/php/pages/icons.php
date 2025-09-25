<?php
include_once __DIR__ . '/../db_config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userRole = $_SESSION['user_role'] ?? 'Standard';
$canEdit = in_array($userRole, ['Admin', 'SuperUser']);

// Utility: Get ENUM values from Weather.icon_collection
function getEnumValues(PDO $pdo, string $table, string $column): array
{
    $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || strpos($row['Type'], 'enum') === false) return [];

    preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
    $values = str_getcsv($matches[1], ',', "'");
    return array_combine($values, $values);
}

$collections = getEnumValues($pdo, 'Weather', 'icon_collection');

// Handle Add Collection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add_collection') {
    $newValue = trim($_POST['new_collection']);
    if ($newValue !== '') {
        $stmt = $pdo->query("SHOW COLUMNS FROM Weather LIKE 'icon_collection'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
        $currentValues = str_getcsv($matches[1], ',', "'");
        if (!in_array($newValue, $currentValues)) {
            $updatedValues = array_merge($currentValues, [$newValue]);
            $enumList = implode(",", array_map(fn($v) => "'$v'", $updatedValues));
            $pdo->exec("ALTER TABLE Weather MODIFY COLUMN icon_collection ENUM($enumList) NOT NULL DEFAULT ''");
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=icons");
    exit;
}

// Handle Rename Collection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'rename_collection') {
    $old = $_POST['old_collection'];
    $new = trim($_POST['new_name']);
    if ($new !== '' && $old !== $new) {
        $stmt = $pdo->query("SHOW COLUMNS FROM Weather LIKE 'icon_collection'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
        $currentValues = str_getcsv($matches[1], ',', "'");
        if (in_array($old, $currentValues) && !in_array($new, $currentValues)) {
            $updatedValues = array_map(fn($v) => $v === $old ? $new : $v, $currentValues);
            $enumList = implode(",", array_map(fn($v) => "'$v'", $updatedValues));
            $pdo->exec("ALTER TABLE Weather MODIFY COLUMN icon_collection ENUM($enumList) NOT NULL DEFAULT ''");
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=icons");
    exit;
}

// Handle Delete Collection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete_collection') {
    $target = $_POST['collection'];
    $stmt = $pdo->query("SHOW COLUMNS FROM Weather LIKE 'icon_collection'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
    $currentValues = str_getcsv($matches[1], ',', "'");
    if (in_array($target, $currentValues)) {
        $updatedValues = array_filter($currentValues, fn($v) => $v !== $target);
        $enumList = implode(",", array_map(fn($v) => "'$v'", $updatedValues));
        $pdo->exec("ALTER TABLE Weather MODIFY COLUMN icon_collection ENUM($enumList) NOT NULL DEFAULT ''");
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=icons");
    exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Icon Collections</h2>
    <?php if ($canEdit): ?>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCollectionModal">
            <i class="bi bi-plus-circle"></i> Add Collection
        </button>
    <?php endif; ?>
</div>

<ul class="nav nav-tabs mb-3" id="iconTab" role="tablist">
    <?php foreach (array_keys($collections) as $i => $collection): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= $i === 0 ? 'active' : '' ?>" id="tab-<?= $i ?>" data-bs-toggle="tab" data-bs-target="#pane-<?= $i ?>" type="button" role="tab">
                <?= $collection ?: '—' ?>
            </button>
        </li>
    <?php endforeach; ?>
</ul>

<div class="tab-content" id="iconTabContent">
    <?php foreach (array_keys($collections) as $i => $collection): ?>
        <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>" id="pane-<?= $i ?>" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><?= $collection ?: '—' ?></h5>
                <?php if ($canEdit): ?>
                    <div>
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#renameModal<?= $i ?>">Rename</button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $i ?>">Delete</button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row">
                <?php
                $folder = "https://www.netcontact.at/API/Weather/weather-icons/" . $collection;
                $urlPath = 'https://www.netcontact.at/API/Weather/weather-icons/' . $collection;
                // echo $urlPath;
                print_r(($folder));
                if (is_dir($folder)) {
                    $files = array_filter(scandir($folder), fn($f) => preg_match('/\.(png|svg)$/i', $f));
                    foreach ($files as $file):
                ?>
                        <div class="col-md-2 mb-3 text-center">
                            <div class="card">
                                <img src="<?= $urlPath . '/' . $file ?>" class="card-img-top p-2" alt="<?= $file ?>">
                                <div class="card-body p-2">
                                    <small class="text-muted"><?= $file ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                } else { ?>
                    icons should be on the same server as the admin panel
                    <div class="col-12"><em>No icons found in folder: <?= $collection ?></em></div>
                <?php } ?>
            </div>
        </div>

        <!-- Add Modal -->
        <?php if ($canEdit): ?>
            <div class="modal fade" id="addCollectionModal" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST">
                        <input type="hidden" name="action" value="add_collection">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Icon Collection</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="new_collection" class="form-control" placeholder="Collection name (e.g. WetterIcons)" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Add Collection</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Rename Modal -->
        <div class="modal fade" id="renameModal<?= $i ?>" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST">
                    <input type="hidden" name="action" value="rename_collection">
                    <input type="hidden" name="old_collection" value="<?= $collection ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Rename Collection: <?= $collection ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="new_name" class="form-control" placeholder="New name" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning">Rename</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal<?= $i ?>" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST">
                    <input type="hidden" name="action" value="delete_collection">
                    <input type="hidden" name="collection" value="<?= $collection ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Collection: <?= $collection ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this collection from the ENUM list? This won't delete any files.
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach;
