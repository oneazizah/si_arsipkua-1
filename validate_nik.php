<?php
require 'functions.php';  // pastikan sudah ada koneksi PDO: $pdo

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';   // pria / wanita
$nik  = $_GET['nik']  ?? '';
$id   = $_GET['id']   ?? null; // aktif hanya ketika EDIT DATA

if ($nik === '') {
    echo json_encode(['status' => 'empty']);
    exit;
}

// Tentukan kolom berdasarkan type
if ($type === "pria") {
    $column = "nik_pria";
} elseif ($type === "wanita") {
    $column = "nik_wanita";
} else {
    echo json_encode(['status' => 'error']);
    exit;
}

// Query cek duplikasi
if ($id) {
    // EDIT MODE → abaikan miliknya sendiri
    $stmt = $pdo->prepare("
        SELECT id FROM akta_nikah 
        WHERE $column = :nik AND id != :id LIMIT 1
    ");
    $stmt->execute([
        ':nik' => $nik,
        ':id' => $id
    ]);
} else {
    // ADD MODE
    $stmt = $pdo->prepare("
        SELECT id FROM akta_nikah 
        WHERE $column = :nik LIMIT 1
    ");
    $stmt->execute([':nik' => $nik]);
}

// Hasil
if ($stmt->fetch()) {
    echo json_encode(['status' => 'duplicate']);
} else {
    echo json_encode(['status' => 'ok']);
}
