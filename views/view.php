<?php require 'header.php'; ?>

<head>
    <link rel="stylesheet" href="assets/css/view.css">
</head>

<?php
// ====================== LOAD DATA ======================
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<script>
        Swal.fire('Error', 'ID arsip tidak ditemukan!', 'error');
        setTimeout(()=>{ window.location='index.php?page=list'; }, 1500);
    </script>";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM akta_nikah WHERE id = :id");
$stmt->execute(['id' => $id]);
$data = $stmt->fetch();

if (!$data) {
    echo "<script>
        Swal.fire('Error', 'Data tidak ditemukan!', 'error');
        setTimeout(()=>{ window.location='index.php?page=list'; }, 1500);
    </script>";
    exit;
}
?>

<h3 class="mb-4">📄 Detail Arsip Buku Nikah</h3>

<div class="view-card">

    <!-- NOMOR AKTA -->
    <div class="akta-header">
        <span class="akta-badge-big">
            <i class="bi bi-hash"></i> <?= htmlspecialchars($data['nomor_akta']) ?>
        </span>
    </div>

    <!-- ================= INFORMASI AKTA ================= -->
    <div class="section-title">📌 Informasi Akta</div>

    <div class="data-grid">
        <div class="data-label">Nomor Akta</div>
        <div class="data-value"><?= htmlspecialchars($data['nomor_akta']) ?></div>

        <div class="data-label">Tanggal Akad</div>
        <div class="data-value"><?= htmlspecialchars($data['tanggal_akad']) ?></div>
    </div>


    <!-- ================= MEMPELAI PRIA ================= -->
    <div class="section-title">👨 Mempelai Pria</div>

    <div class="data-grid">
        <div class="data-label">Nama Lengkap</div>
        <div class="data-value"><?= htmlspecialchars($data['nama_pria']) ?></div>

        <div class="data-label">NIK</div>
        <div class="data-value"><?= htmlspecialchars($data['nik_pria']) ?></div>

        <div class="data-label">TTL</div>
        <div class="data-value"><?= htmlspecialchars($data['ttl_pria']) ?></div>

        <div class="data-label">Nama Ayah</div>
        <div class="data-value"><?= htmlspecialchars($data['nama_ayah_pria']) ?></div>
    </div>


    <!-- ================= MEMPELAI WANITA ================= -->
    <div class="section-title">👩 Mempelai Wanita</div>

    <div class="data-grid">
        <div class="data-label">Nama Lengkap</div>
        <div class="data-value"><?= htmlspecialchars($data['nama_wanita']) ?></div>

        <div class="data-label">NIK</div>
        <div class="data-value"><?= htmlspecialchars($data['nik_wanita']) ?></div>

        <div class="data-label">TTL</div>
        <div class="data-value"><?= htmlspecialchars($data['ttl_wanita']) ?></div>

        <div class="data-label">Nama Ayah</div>
        <div class="data-value"><?= htmlspecialchars($data['nama_ayah_wanita']) ?></div>
    </div>


    <!-- ================= ALAMAT ================= -->
    <div class="section-title">📍 Alamat KUA</div>
    <div class="data-value mb-3">
        <?= nl2br(htmlspecialchars($data['alamat_kua'])) ?>
    </div>


    <!-- ================= FILE SCAN ================= -->
    <div class="section-title">📎 File Scan</div>

    <?php if ($data['file_path']): ?>
        <div class="file-preview-box mb-3">

            <?php 
            $file = "uploads/" . $data['file_path'];
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            ?>

            <?php if ($ext === "pdf"): ?>
                <iframe src="<?= $file ?>"></iframe>
            <?php else: ?>
                <img src="<?= $file ?>" class="img-fluid rounded" alt="File Scan">
            <?php endif; ?>

            <a href="<?= $file ?>" download class="btn btn-success mt-3">
                <i class="bi bi-download"></i> Unduh File
            </a>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Tidak ada file upload.</div>
    <?php endif; ?>


    <!-- ================= TOMBOL ================= -->
    <div class="mt-4">
        <a href="index.php?page=list" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <a href="index.php?page=edit&id=<?= $data['id'] ?>" class="btn btn-warning">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
    </div>

</div>

<?php require 'footer.php'; ?>
