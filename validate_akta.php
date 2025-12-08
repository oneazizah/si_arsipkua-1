<?php
require 'functions.php';  // pastikan sudah ada koneksi PDO: $pdo

header('Content-Type: application/json');

$nomor = $_GET['nomor'] ?? '';
$id    = $_GET['id'] ?? null;

if ($nomor === '') {
    echo json_encode(['status' => 'empty']);
    exit;
}

// Cek apakah nomor akta sudah dipakai arsip lain
if ($id) {
    // EDIT MODE → abaikan nomor akta miliknya sendiri
    $stmt = $pdo->prepare("SELECT id FROM akta_nikah WHERE nomor_akta = :nomor AND id != :id LIMIT 1");
    $stmt->execute([
        ':nomor' => $nomor,
        ':id' => $id
    ]);
} else {
    // ADD MODE
    $stmt = $pdo->prepare("SELECT id FROM akta_nikah WHERE nomor_akta = :nomor LIMIT 1");
    $stmt->execute([':nomor' => $nomor]);
}

if ($stmt->fetch()) {
    echo json_encode(['status' => 'duplicate']);
} else {
    echo json_encode(['status' => 'ok']);
}
