<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'functions.php';
$page = $_GET['page'] ?? 'list';
switch ($page) {
    case 'dashboard':
    require 'views/dashboard.php';
    break;
    case 'delete':
    $id = $_GET['id'] ?? null;

    if ($id) {

        // Ambil file scan jika ada
        $stmt = $pdo->prepare("SELECT file_path FROM akta_nikah WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $file = $stmt->fetch();

        // Hapus file fisik
        if ($file && $file['file_path'] !== null) {
            $path = __DIR__ . 'uploads/' . $file['file_path'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Hapus database
        $del = $pdo->prepare("DELETE FROM akta_nikah WHERE id = :id");
        $del->execute(['id' => $id]);

        flash('success', 'Data arsip berhasil dihapus.');
    }

    header('Location: index.php');
    exit;

    case 'add':
        require 'views/add.php';
        break;
    case 'view':
        require 'views/view.php';
        break;

    case 'edit':
        require 'views/edit.php';
        break;

    case 'save':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // ===============================
        // 1. Ambil semua input
        // ===============================
        $nomor_akta      = trim($_POST['nomor_akta'] ?? '');
        $tanggal_akad    = trim($_POST['tanggal_akad'] ?? '');
        $nama_pria       = trim($_POST['nama_pria'] ?? '');
        $nik_pria        = trim($_POST['nik_pria'] ?? '');
        $ttl_pria        = trim($_POST['ttl_pria'] ?? '');
        $nama_ayah_pria  = trim($_POST['nama_ayah_pria'] ?? '');
        $nama_wanita     = trim($_POST['nama_wanita'] ?? '');
        $nik_wanita      = trim($_POST['nik_wanita'] ?? '');
        $ttl_wanita      = trim($_POST['ttl_wanita'] ?? '');
        $nama_ayah_wanita= trim($_POST['nama_ayah_wanita'] ?? '');
        $alamat_kua      = trim($_POST['alamat_kua'] ?? '');

        $errors = [];

        // ===============================
        // 2. Validasi input dasar
        // ===============================
        if ($nomor_akta === '')      $errors[] = "Nomor Akta wajib diisi.";
        if ($tanggal_akad === '')    $errors[] = "Tanggal akad wajib diisi.";
        if ($nama_pria === '')       $errors[] = "Nama mempelai pria wajib diisi.";
        if ($nama_wanita === '')     $errors[] = "Nama mempelai wanita wajib diisi.";
        if ($nik_pria === '')        $errors[] = "NIK mempelai pria wajib diisi.";
        if ($nik_wanita === '')      $errors[] = "NIK mempelai wanita wajib diisi.";

        // ===============================
        // VALIDASI FORMAT NIK HARUS 16 DIGIT
        // ===============================
        
        if (!preg_match('/^[0-9]{16}$/', $nik_pria)) {
        $errors[] = "NIK mempelai pria harus 16 digit dan hanya angka.";
        }

        if (!preg_match('/^[0-9]{16}$/', $nik_wanita)) {
        $errors[] = "NIK mempelai wanita harus 16 digit dan hanya angka.";
        }

        // ===============================
        // VALIDASI NIK SUAMI & ISTRI TIDAK BOLEH SAMA
        // ===============================
        if ($nik_pria === $nik_wanita) {
        $errors[] = "NIK mempelai pria dan wanita tidak boleh sama.";
        }

        // ===============================
        // 3. Validasi nomor akta duplikat
        //===============================
        $cekAkta = $pdo->prepare("SELECT id FROM akta_nikah WHERE nomor_akta = :no");
        $cekAkta->execute([':no' => $nomor_akta]);

        if ($cekAkta->fetch()) {
            $errors[] = "Nomor Akta sudah terdaftar.";
        }

        // ===============================
        // 4. Validasi NIK duplikat
        //===============================
        $cekNikPria = $pdo->prepare("SELECT id FROM akta_nikah WHERE nik_pria = :nik");
        $cekNikPria->execute([':nik' => $nik_pria]);

        if ($cekNikPria->fetch()) {
            $errors[] = "NIK mempelai pria sudah terdaftar.";
        }

        $cekNikWanita = $pdo->prepare("SELECT id FROM akta_nikah WHERE nik_wanita = :nik");
        $cekNikWanita->execute([':nik' => $nik_wanita]);

        if ($cekNikWanita->fetch()) {
            $errors[] = "NIK mempelai wanita sudah terdaftar.";
        }

        // ===============================
        // 5. Validasi file upload (opsional)
        // ===============================
        $filename = null;

        if (!empty($_FILES['file_scan']['name'])) {
            $valid = validate_file($_FILES['file_scan']);

            if ($valid === true) {
                $saved = save_uploaded_file($_FILES['file_scan']);
                if ($saved) {
                    $filename = $saved;
                } else {
                    $errors[] = "Gagal menyimpan file.";
                }
            } else {
                $errors[] = $valid; // pesan error dari validate_file()
            }
        }

        // ===============================
        // 6. Jika ada error → kembali ke form add
        // ===============================
        if (!empty($errors)) {
    // Gabungkan semua error menjadi satu string
    $_SESSION['error'] = implode("\n", $errors);
    header("Location: index.php?page=add");
    exit;
}


        // ===============================
        // 7. Insert data ke database
        // ===============================
        $stmt = $pdo->prepare("
            INSERT INTO akta_nikah (
                nomor_akta, tanggal_akad,
                nama_pria, nik_pria, ttl_pria, nama_ayah_pria,
                nama_wanita, nik_wanita, ttl_wanita, nama_ayah_wanita,
                alamat_kua, file_path
            )
            VALUES (
                :nomor, :tanggal,
                :nama_pria, :nik_pria, :ttl_pria, :ayah_pria,
                :nama_wanita, :nik_wanita, :ttl_wanita, :ayah_wanita,
                :alamat_kua, :file_path
            )
        ");

        $stmt->execute([
            ':nomor'         => $nomor_akta,
            ':tanggal'       => $tanggal_akad,
            ':nama_pria'     => $nama_pria,
            ':nik_pria'      => $nik_pria,
            ':ttl_pria'      => $ttl_pria,
            ':ayah_pria'     => $nama_ayah_pria,
            ':nama_wanita'   => $nama_wanita,
            ':nik_wanita'    => $nik_wanita,
            ':ttl_wanita'    => $ttl_wanita,
            ':ayah_wanita'   => $nama_ayah_wanita,
            ':alamat_kua'    => $alamat_kua,
            ':file_path'     => $filename
        ]);

        // ===============================
        // 8. Tambahkan log aktivitas
        // ===============================
        add_log("Tambah arsip baru: Nomor Akta {$nomor_akta}");

        // ===============================
        // 9. Redirect + pesan sukses
        // ===============================
        flash('success', 'Data arsip berhasil disimpan.');
        header("Location: index.php?page=list");
        exit;
    }
    break;

    case 'update':
    $id = $_GET['id'] ?? null;

    if (!$id) {
        flash('error', 'ID tidak ditemukan.');
        header("Location: index.php?page=list");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Ambil data lama
        $stmt = $pdo->prepare("SELECT file_path FROM akta_nikah WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $old = $stmt->fetch();

        // Ambil data input
        $nomor_akta = $_POST['nomor_akta'];
        $tanggal_akad = $_POST['tanggal_akad'];
        $nama_pria = $_POST['nama_pria'];
        $nik_pria = $_POST['nik_pria'];
        $ttl_pria = $_POST['ttl_pria'];
        $nama_ayah_pria = $_POST['nama_ayah_pria'];
        $nama_wanita = $_POST['nama_wanita'];
        $nik_wanita = $_POST['nik_wanita'];
        $ttl_wanita = $_POST['ttl_wanita'];
        $nama_ayah_wanita = $_POST['nama_ayah_wanita'];
        $alamat_kua = $_POST['alamat_kua'];

        // File scan
        $filename = $old['file_path'];

        if (!empty($_FILES['file_scan']['name'])) {
            $valid = validate_file($_FILES['file_scan']);

            if ($valid === true) {
                // Hapus file lama
                if ($filename && file_exists("libs/uploads/" . $filename)) {
                    unlink("uploads/" . $filename);
                }

                // Upload file baru
                $filename = save_uploaded_file($_FILES['file_scan']);
            } else {
                flash('error', $valid);
                header("Location: index.php?page=edit&id=$id");
                exit;
            }
        }

        // Update data
        $stmt = $pdo->prepare("
            UPDATE akta_nikah SET
                nomor_akta = :nomor,
                tanggal_akad = :tanggal,
                nama_pria = :nama_pria,
                nik_pria = :nik_pria,
                ttl_pria = :ttl_pria,
                nama_ayah_pria = :ayah_pria,
                nama_wanita = :nama_wanita,
                nik_wanita = :nik_wanita,
                ttl_wanita = :ttl_wanita,
                nama_ayah_wanita = :ayah_wanita,
                alamat_kua = :alamat,
                file_path = :file
            WHERE id = :id
        ");

        $stmt->execute([
            ':nomor' => $nomor_akta,
            ':tanggal' => $tanggal_akad,
            ':nama_pria' => $nama_pria,
            ':nik_pria' => $nik_pria,
            ':ttl_pria' => $ttl_pria,
            ':ayah_pria' => $nama_ayah_pria,
            ':nama_wanita' => $nama_wanita,
            ':nik_wanita' => $nik_wanita,
            ':ttl_wanita' => $ttl_wanita,
            ':ayah_wanita' => $nama_ayah_wanita,
            ':alamat' => $alamat_kua,
            ':file' => $filename,
            ':id' => $id,
        ]);

        flash('success', 'Data arsip berhasil diperbarui.');
        header("Location: index.php?page=list");
        exit;
    }
    break;

    case 'report':
        require 'views/report.php';
        break;
    case 'log':
        require 'views/log.php';
        break;
    default:
        require 'views/list.php';
}
?>