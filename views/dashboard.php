<?php 
require 'header.php';

// Statistik utama
$total = $pdo->query("SELECT COUNT(*) FROM akta_nikah")->fetchColumn();
$totalPria = $pdo->query("SELECT COUNT(*) FROM akta_nikah WHERE nama_pria != '' ")->fetchColumn();
$totalWanita = $pdo->query("SELECT COUNT(*) FROM akta_nikah WHERE nama_wanita != '' ")->fetchColumn();
$totalFile = $pdo->query("SELECT COUNT(file_path) FROM akta_nikah WHERE file_path IS NOT NULL")->fetchColumn();

?>

<head>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>

<div class="container mt-4">
    <h3 class="mb-4 fw-bold">Dashboard Arsip KUA</h3>

    <div class="row g-4">

        <div class="col-md-3">
            <div class="dashboard-card bg-total">
                <h6>Total Arsip</h6>
                <h2><?= $total ?></h2>
                <i class="bi bi-archive fs-2"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card bg-pria">
                <h6>Mempelai Pria</h6>
                <h2><?= $totalPria ?></h2>
                <i class="bi bi-person fs-2"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card bg-wanita">
                <h6>Mempelai Wanita</h6>
                <h2><?= $totalWanita ?></h2>
                <i class="bi bi-person-heart fs-2"></i>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-card bg-file">
                <h6>File Terupload</h6>
                <h2><?= $totalFile ?></h2>
                <i class="bi bi-file-earmark-arrow-up fs-2"></i>
            </div>
        </div>

    </div>
</div>
</html>

<?php require 'footer.php'; ?>
