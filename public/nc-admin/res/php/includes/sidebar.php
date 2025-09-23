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
      <li class="<?= isActive('categories') ?>">
        <a class="nav-link text-white" href="admin_panel.php?page=categories"><i class="bi bi-folder"></i> Kategorien<br />und Produkte</a>
      </li>
      <!-- <li>
        <a class="nav-link text-white" href="admin_panel.php?page=products"><i class="bi bi-box"></i> Products</a>
      </li> -->
      <li class="<?= isActive('users') ?>">
        <a class="nav-link text-white" href="admin_panel.php?page=users"><i class="bi bi-person"></i> Benutzer</a>
      </li>
      <li class="<?= isActive('reports') ?>">
        <a class="nav-link text-white" href="admin_panel.php?page=reports"><i class="bi bi-bar-chart-line"></i> Berichte</a>
      </li>
      <li class="<?= isActive('bills') ?>">
        <a class="nav-link text-white" href="admin_panel.php?page=bills"><i class="bi bi-file-earmark-text"></i> Rechnungen</a>
      </li>
      <li class="<?= isActive('orders') ?>">
        <a class="nav-link text-white" href="admin_panel.php?page=orders"><i class="bi bi-cart"></i> Bestellungen</a>
      </li>
      <hr />
      <li class="<?= isActive('profile') ?>">
        <a class="nav-link text-white" href="admin_panel.php?page=profile"><i class="bi bi-gear"></i> Profil</a>
      </li>
      <li>
        <a class="nav-link text-white" href="logout.php"><i class="bi bi-box-arrow-right"></i> Abmelden</a>
      </li>
    </ul>
  </nav>
</aside>