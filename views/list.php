<?php require 'header.php'; ?>

<head>
    <link rel="stylesheet" href="assets/css/list.css">
</head>
<h3 class="page-title mb-3">📁 Daftar Arsip Buku Nikah</h3>

<!-- ====================== SEARCH CARD ====================== -->
<div class="search-card mb-4">
<form class="row g-3" method="get" action="index.php">
    <input type="hidden" name="page" value="list">

    <div class="col-md-5">
        <input name="q" class="form-control form-control-lg"
               placeholder="🔍 Cari nama mempelai atau nomor akta..."
               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
    </div>

    <div class="col-md-3">
        <label class="form-label">Dari Tanggal</label>
        <input type="date" name="from" class="form-control"
               value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">
    </div>

    <div class="col-md-3">
        <label class="form-label">Sampai Tanggal</label>
        <input type="date" name="to" class="form-control"
               value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">
    </div>

    <div class="col-md-1 d-grid">
        <button class="btn btn-primary btn-lg mt-4">Cari</button>
    </div>
</form>
</div>

<?php
$q = trim($_GET['q'] ?? '');
$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;

$limit = 3; 
$pageNum = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$offset = ($pageNum - 1) * $limit;

$sqlCount = "SELECT COUNT(*) FROM akta_nikah WHERE 1=1";
$params = [];

if ($q !== '') {
    $sqlCount .= " AND (nomor_akta LIKE :q OR nama_pria LIKE :q OR nama_wanita LIKE :q)";
    $params[':q'] = "%$q%";
}

if ($from && $to) {
    $sqlCount .= " AND tanggal_akad BETWEEN :from AND :to";
    $params[':from'] = $from;
    $params[':to'] = $to;
}

$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute($params);
$totalData = $stmtCount->fetchColumn();
$totalPage = ceil($totalData / $limit);

$sql = "SELECT * FROM akta_nikah WHERE 1=1";

if ($q !== '') {
    $sql .= " AND (nomor_akta LIKE :q OR nama_pria LIKE :q OR nama_wanita LIKE :q)";
}

if ($from && $to) {
    $sql .= " AND tanggal_akad BETWEEN :from AND :to";
}

$sql .= " ORDER BY tanggal_akad DESC LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();


?>

<!-- FLASH MESSAGE -->
<?php if ($msg = flash('success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '<?= $msg ?>',
    timer: 1800,
    showConfirmButton: false
});
</script>
<?php endif; ?>


<?php if (empty($rows)): ?>
    <div class="alert alert-info">Data tidak ditemukan.</div>

<?php else: ?>

<div class="card-grid">

<?php foreach ($rows as $r): ?>
    <div class="arsip-card">

        <div class="mb-2">
            <span class="akta-badge"><?= htmlspecialchars($r['nomor_akta']) ?></span>
        </div>

        <div class="card-title">
            <?= htmlspecialchars($r['nama_pria']) ?> & <?= htmlspecialchars($r['nama_wanita']) ?>
        </div>

        <div class="card-sub mb-3">
            Tanggal Akad: <b><?= htmlspecialchars($r['tanggal_akad']) ?></b>
        </div>

        <div class="action-btns">
            <a class="btn btn-sm btn-outline-primary"
               href="index.php?page=view&id=<?= $r['id'] ?>">
                <i class="bi bi-eye"></i>
            </a>

            <a class="btn btn-sm btn-outline-warning"
               href="index.php?page=edit&id=<?= $r['id'] ?>">
                <i class="bi bi-pencil-square"></i>
            </a>

            <?php if ($r['file_path']): ?>
            <a class="btn btn-sm btn-success"
   href="<?= UPLOAD_BASE . urlencode($r['file_path']) ?>"
   download>
   <i class="bi bi-download"></i> 
</a>

            <?php endif; ?>

            <button class="btn btn-sm btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#del<?= $r['id'] ?>">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>

    <!-- MODAL HAPUS -->
    <div class="modal fade" id="del<?= $r['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Yakin ingin menghapus arsip <b><?= htmlspecialchars($r['nomor_akta']) ?></b> ?
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="index.php?page=delete&id=<?= $r['id'] ?>" class="btn btn-danger">Hapus</a>
                </div>

            </div>
        </div>
    </div>

<?php endforeach; ?>

</div> <!-- END GRID -->

<?php if ($totalPage > 1): ?>
<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">

        <li class="page-item <?= ($pageNum <= 1 ? 'disabled' : '') ?>">
            <a class="page-link"
                href="index.php?page=list&p=<?= $pageNum - 1 ?>&q=<?= urlencode($q) ?>&from=<?= $from ?>&to=<?= $to ?>">
                Prev
            </a>
        </li>

        <?php for ($i = 1; $i <= $totalPage; $i++): ?>
            <li class="page-item <?= ($pageNum == $i ? 'active' : '') ?>">
                <a class="page-link"
                    href="index.php?page=list&p=<?= $i ?>&q=<?= urlencode($q) ?>&from=<?= $from ?>&to=<?= $to ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <li class="page-item <?= ($pageNum >= $totalPage ? 'disabled' : '') ?>">
            <a class="page-link"
                href="index.php?page=list&p=<?= $pageNum + 1 ?>&q=<?= urlencode($q) ?>&from=<?= $from ?>&to=<?= $to ?>">
                Next
            </a>
        </li>

    </ul>
</nav>
<?php endif; ?>


<?php endif; ?>


<?php require 'footer.php'; ?>
