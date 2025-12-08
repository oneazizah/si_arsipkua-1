<?php require 'header.php'; ?>

<?php
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

<head>
    <link rel="stylesheet" href="assets/css/edit.css">
</head>

<h3 class="mb-3"><i class="bi bi-pencil-square"></i> Edit Data Arsip Buku Nikah</h3>

<div class="card shadow-sm p-4">
<form id="editForm" action="index.php?page=update&id=<?= $data['id'] ?>" method="post" enctype="multipart/form-data" class="row g-3">

    <!-- ====================== -->
    <!-- BAGIAN NOMOR AKTA -->
    <!-- ====================== -->
    <div class="card-section">
        <h5><i class="bi bi-file-earmark-text"></i> Informasi Akta</h5>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Nomor Akta</label>
        <input name="nomor_akta" class="form-control" value="<?= htmlspecialchars($data['nomor_akta']) ?>" required>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Tanggal Akad</label>
        <input type="date" name="tanggal_akad" class="form-control" value="<?= $data['tanggal_akad'] ?>" required>
    </div>


    <!-- ====================== -->
    <!-- MEMPELAI PRIA -->
    <!-- ====================== -->
    <div class="card-section">
        <h5><i class="bi bi-person"></i> Mempelai Pria</h5>
    </div>

    <div class="col-md-6">
        <label class="form-label">Nama Lengkap</label>
        <input name="nama_pria" class="form-control" value="<?= htmlspecialchars($data['nama_pria']) ?>" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">NIK</label>
        <input name="nik_pria" class="form-control" value="<?= htmlspecialchars($data['nik_pria']) ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Tempat, Tanggal Lahir</label>
        <input name="ttl_pria" class="form-control" value="<?= htmlspecialchars($data['ttl_pria']) ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Nama Ayah</label>
        <input name="nama_ayah_pria" class="form-control" value="<?= htmlspecialchars($data['nama_ayah_pria']) ?>">
    </div>


    <!-- ====================== -->
    <!-- MEMPELAI WANITA -->
    <!-- ====================== -->
    <div class="card-section">
        <h5><i class="bi bi-person-heart"></i> Mempelai Wanita</h5>
    </div>

    <div class="col-md-6">
        <label class="form-label">Nama Lengkap</label>
        <input name="nama_wanita" class="form-control" value="<?= htmlspecialchars($data['nama_wanita']) ?>" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">NIK</label>
        <input name="nik_wanita" class="form-control" value="<?= htmlspecialchars($data['nik_wanita']) ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Tempat, Tanggal Lahir</label>
        <input name="ttl_wanita" class="form-control" value="<?= htmlspecialchars($data['ttl_wanita']) ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Nama Ayah</label>
        <input name="nama_ayah_wanita" class="form-control" value="<?= htmlspecialchars($data['nama_ayah_wanita']) ?>">
    </div>


    <!-- ====================== -->
    <!-- ALAMAT KUA -->
    <!-- ====================== -->
    <div class="card-section">
        <h5><i class="bi bi-geo-alt"></i> Alamat KUA</h5>
    </div>

    <div class="col-12">
        <input name="alamat_kua" class="form-control" value="<?= htmlspecialchars($data['alamat_kua']) ?>">
    </div>


    <!-- ====================== -->
    <!-- FILE SCAN -->
    <!-- ====================== -->
    <div class="card-section">
        <h5><i class="bi bi-folder2-open"></i> File Scan</h5>
    </div>

    <div class="col-12">
        <input type="file" name="file_scan" class="form-control">

        <?php if ($data['file_path']): ?>
            <div class="mt-2">
                <a href="uploads/<?= $data['file_path'] ?>" target="_blank" class="btn btn-sm btn-success">
                    <i class="bi bi-file-earmark"></i> Lihat File Lama
                </a>
                <input type="hidden" name="old_file" value="<?= $data['file_path'] ?>">
            </div>
        <?php endif; ?>
    </div>


    <!-- ====================== -->
    <!-- BUTTON -->
    <!-- ====================== -->
    <div class="col-12 mt-3">
        <button class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
        <a href="index.php?page=list" class="btn btn-secondary">Kembali</a>
    </div>

</form>
</div>

<script src="assets/js/form-validation-edit.js"></script>

<?php require 'footer.php'; ?>
