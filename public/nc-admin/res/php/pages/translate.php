<?php
include_once __DIR__ . '/../db_config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>AiTranslate</h2>
</div>