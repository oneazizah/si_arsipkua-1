<?php require_once "auth.php"; ?>
<!DOCTYPE html>
<html>
<head>
    
    <title>SI Arsip KUA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/styles.css">

</head>
<body>

<!-- TOPBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container-fluid">

        <!-- Tombol Hamburger -->
        <button class="btn btn-dark me-3" onclick="toggleSidebar()">
            <i class="bi bi-list fs-4"></i>
        </button>

        <!-- Judul -->
        <a class="navbar-brand fw-bold" href="#">
            <i class="bi bi-journal-bookmark"></i> SI Arsip KUA
        </a>

        <div class="ms-auto d-flex align-items-center">

            <!-- Tombol Dark Mode -->
            <button class="btn btn-dark" onclick="toggleDarkMode()" id="darkBtn">
                <i class="bi bi-moon fs-5"></i>
            </button>

        </div>

    </div>
    <li class="nav-item">
    <a href="logout.php" class="nav-link text-danger">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</li>

</nav>


<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <div class="brand-logo mb-3">
        <i class="bi bi-folder2-open"></i> Menu
    </div>

    <!-- Dashboard -->
    <a href="index.php?page=dashboard" class="<?= ($_GET['page'] ?? '') == 'dashboard' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i><span> Dashboard</span>
    </a>

    <!-- Submenu Data Arsip -->
    <div class="submenu-item">
        <a href="#" class="submenu-toggle">
            <i class="bi bi-archive"></i>
            <span>Data Arsip</span>
            <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <div class="submenu">
            <a href="index.php" class="ps-5 <?= (!isset($_GET['page']) ? 'active-sub' : '') ?>"><i class="bi bi-dot"></i> List Arsip </a>

            <a href="index.php?page=add" class="ps-5 <?= ($_GET['page'] ?? '') == 'add' ? 'active-sub' : '' ?>"><i class="bi bi-dot"></i> Tambah Arsip </a>
        </div>
    </div>

    <!-- Submenu Laporan -->
    <div class="submenu-item">
        <a href="#" class="submenu-toggle">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <span>Laporan</span>
            <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <div class="submenu">
            <a href="index.php?page=report" 
   class="ps-5 <?= ($_GET['page'] ?? '') == 'report' ? 'active-sub' : '' ?>">
   <i class="bi bi-dot"></i> Laporan Arsip
</a>

        </div>
    </div>

    <!-- Submenu System -->
    <div class="submenu-item">
        <a href="#" class="submenu-toggle">
            <i class="bi bi-gear"></i>
            <span>System</span>
            <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <div class="submenu">
            <a href="index.php?page=log" 
   class="ps-5 <?= ($_GET['page'] ?? '') == 'log' ? 'active-sub' : '' ?>">
   <i class="bi bi-dot"></i> Log Aktivitas
</a>

        </div>
    </div>

</div>


<!-- CONTENT -->
<div class="content" id="content" style="margin-top:70px;">