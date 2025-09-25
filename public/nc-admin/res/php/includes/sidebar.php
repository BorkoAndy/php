<?php
$page = $_GET['page'] ?? 'dashboard';
function isActive($tab)
{
  global $page;
  return $page === $tab ? 'active' : '';
}
?>


<aside class="sidebar">
  <h2>Admin Panel</h2>
  <nav>
    <ul>
      <li class="<?= isActive('dashboard') ?>">
        <a class="nav-link text-white" href="admin_panel.php?page=dashboard"><i class="bi bi-grid"></i> Übersicht
        </a>
      </li>

      <li class="<?= isActive('weather') || isActive('icons') ? 'active' : '' ?>">
        <a class="nav-link text-white" data-bs-toggle="collapse" href="#weatherSubmenu" role="button" aria-expanded="<?= isActive('weather') || isActive('icons') ? 'true' : 'false' ?>" aria-controls="weatherSubmenu">
          <i class="bi bi-cloud-sun"></i> Wetter API
        </a>
        <ul class="collapse <?= isActive('weather') || isActive('icons') ? 'show' : '' ?>" id="weatherSubmenu">
          <li class="<?= isActive('weather') ?>">
            <a class="nav-link text-white fw-medium" href="admin_panel.php?page=weather"><i class="bi bi-cloud"></i> Domains</a>
          </li>
          <li class="<?= isActive('icons') ?>">
            <a class="nav-link text-white fw-medium" href="admin_panel.php?page=icons"><i class="bi bi-images"></i> Icons</a>
          </li>
        </ul>
      </li>

      <li class="<?= isActive('instawall') ?>">
        <a class="nav-link text-white" href="admin_panel.php?page=instawall"><i class="bi bi-camera"></i> InstaWalls</a>
      </li>

      <li class="<?= isActive('translations') || isActive('translate_domains') || isActive('translate_tools') ? 'active' : '' ?>">
        <a class="nav-link text-white" data-bs-toggle="collapse" href="#translateSubmenu" role="button" aria-expanded="<?= isActive('translations') || isActive('translate_domains') || isActive('translate_tools') ? 'true' : 'false' ?>" aria-controls="translateSubmenu">
          <i class="bi bi-translate"></i> AiTranslate
        </a>
        <ul class="collapse <?= isActive('translations') || isActive('translate_domains') || isActive('translate_tools') ? 'show' : '' ?>" id="translateSubmenu">

          <li class="<?= isActive('translate_domains') ?>">
            <a class="nav-link text-white fw-medium" href="admin_panel.php?page=translate_domains"><i class="bi bi-globe"></i> Domains</a>
          </li>
          <li class="<?= isActive('translate_tools') ?>">
            <a class="nav-link text-white fw-medium" href="admin_panel.php?page=translate_tools"><i class="bi bi-wrench"></i> Tools</a>
          </li>
        </ul>
      </li>

      <li class="<?= isActive('users') ?>">
        <a class="nav-link text-white" href="admin_panel.php?page=users"><i class="bi bi-person"></i> Benutzer</a>
      </li>
      <!-- Add more API -->

      <hr />

      <li>
        <a class="nav-link text-white" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
      </li>
    </ul>
  </nav>
</aside>