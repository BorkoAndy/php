<?php
include_once __DIR__ . '/../db_config.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

?>
<div class="d-flex flex-wrap justify-content-between align-items-center mt-4 mb-3 gap-3">
    <!-- Left: Title -->
    <h2 class="mb-0">Bestellungen</h2>
</div>