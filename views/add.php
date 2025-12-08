<?php require 'header.php'; ?>

<head>
    <link rel="stylesheet" href="assets/css/add.css">
</head>

<h3 class="mb-4">➕ Tambah Arsip Buku Nikah</h3>

<div class="form-card">

<form action="index.php?page=save" method="post" enctype="multipart/form-data" class="row g-3">

    <!-- BAGIAN NOMOR AKTA -->
    <div class="section-title">📌 Informasi Akta</div>

    <div class="col-md-6">
        <label class="input-label">Nomor Akta</label>
        <input name="nomor_akta" class="form-control form-control-lg" placeholder="Contoh: 123/AB/2024" required>
    </div>

    <div class="col-md-6">
        <label class="input-label">Tanggal Akad</label>
        <input type="date" name="tanggal_akad" class="form-control form-control-lg" required>
    </div>

    <!-- BAGIAN MEMPELAI PRIA -->
    <div class="section-title mt-3">👨 Mempelai Pria</div>

    <div class="col-md-6">
        <label class="input-label">Nama Lengkap</label>
        <input name="nama_pria" class="form-control form-control-lg" required>
    </div>

    <div class="col-md-6">
        <label class="input-label">NIK</label>
        <input name="nik_pria" class="form-control form-control-lg" maxlength="16">
    </div>

    <div class="col-md-6">
        <label class="input-label">Tempat, Tanggal Lahir</label>
        <input name="ttl_pria" class="form-control form-control-lg">
    </div>

    <div class="col-md-6">
        <label class="input-label">Nama Ayah</label>
        <input name="nama_ayah_pria" class="form-control form-control-lg">
    </div>

    <!-- BAGIAN MEMPELAI WANITA -->
    <div class="section-title mt-3">👩 Mempelai Wanita</div>

    <div class="col-md-6">
        <label class="input-label">Nama Lengkap</label>
        <input name="nama_wanita" class="form-control form-control-lg" required>
    </div>

    <div class="col-md-6">
        <label class="input-label">NIK</label>
        <input name="nik_wanita" class="form-control form-control-lg" maxlength="16">
    </div>

    <div class="col-md-6">
        <label class="input-label">Tempat, Tanggal Lahir</label>
        <input name="ttl_wanita" class="form-control form-control-lg">
    </div>

    <div class="col-md-6">
        <label class="input-label">Nama Ayah</label>
        <input name="nama_ayah_wanita" class="form-control form-control-lg">
    </div>

    <!-- BAGIAN LAINNYA -->
    <div class="section-title mt-3">📍 Informasi Tambahan</div>

    <div class="col-md-12">
        <label class="input-label">Alamat KUA</label>
        <input name="alamat_kua" class="form-control form-control-lg">
    </div>

    <!-- UPLOAD -->
    <div class="card-section">
        <h5><i class="bi bi-folder2-open"></i> File Scan</h5>
    </div>

    <div class="col-12">
        <input type="file" name="file_scan" class="form-control">


    <!-- TOMBOL -->
    <div class="col-12 mt-4">
        <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        <a href="index.php?page=list" class="btn btn-secondary">Batal</a>
    </div>

</form>

</div>

<script src="assets/js/form-validation.js"></script>

<?php require 'footer.php'; ?>
