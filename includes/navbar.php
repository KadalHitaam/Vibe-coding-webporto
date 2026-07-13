<?php
// Tentukan halaman aktif untuk highlight menu
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg glass-nav sticky-top">
  <div class="container py-2">
    <a class="navbar-brand fw-bold text-gradient fs-4" href="dashboard.php">
        MyProfile🐾
    </a>
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
            <a class="nav-link px-3 <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-3 <?= ($current_page == 'guestbook.php') ? 'active' : '' ?>" href="guestbook.php">Buku Tamu</a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-3 <?= ($current_page == 'multimedia.php') ? 'active' : '' ?>" href="multimedia.php">Multimedia</a>
        </li>
        <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
            <a class="btn btn-premium px-4" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
