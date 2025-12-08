<?php require 'header.php'; ?>

<head>
    <link rel="stylesheet" href="assets/css/log.css">
</head>

<h3 class="page-title">🧾 Riwayat Aktivitas Sistem</h3>

<!-- Search -->
<div class="search-box mb-3">
    <form method="get" class="row g-2">
        <input type="hidden" name="page" value="log">
        <div class="col-md-10">
            <input type="text" name="q" class="form-control"
                   placeholder="🔍 Cari aktivitas atau IP..."
                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Cari</button>
        </div>
    </form>
</div>

<?php
$q = trim($_GET['q'] ?? '');
if ($q === '') {
    $stmt = $pdo->query("SELECT * FROM log_activity ORDER BY created_at DESC LIMIT 200");
} else {
    $stmt = $pdo->prepare("
        SELECT * FROM log_activity
        WHERE activity LIKE :q OR user_ip LIKE :q
        ORDER BY created_at DESC
    ");
    $stmt->execute([':q' => "%$q%"]);
}
$rows = $stmt->fetchAll();
?>

<?php if(empty($rows)): ?>
    <div class="alert alert-info">Belum ada log aktivitas.</div>
<?php else: ?>

<div class="timeline">
<?php foreach ($rows as $index => $r): ?>
    <div class="timeline-item animate-item" style="animation-delay: <?= $index * 0.08 ?>s">
        <div class="timeline-marker"></div>
        <div class="t-time">
            <i class="bi bi-clock"></i> <?= $r['created_at'] ?>
            <span class="badge-ip"><?= $r['user_ip'] ?></span>
        </div>
        <div class="t-body">
            <?= htmlspecialchars($r['activity']) ?>
        </div>
    </div>
<?php endforeach; ?>

</div>

<?php endif; ?>

<a href="index.php?page=list" class="btn btn-secondary mt-3">
    <i class="bi bi-arrow-left"></i> Kembali
</a>

<?php require 'footer.php'; ?>
