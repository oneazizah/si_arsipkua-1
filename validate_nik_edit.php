<?php
require 'functions.php';

$nik = $_GET['nik'] ?? '';
$type = $_GET['type'] ?? '';
$id   = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT id FROM akta_nikah 
                       WHERE ($type = :nik) AND id != :id");
$stmt->execute(['nik' => $nik, 'id' => $id]);

echo json_encode([
    "status" => $stmt->fetch() ? "duplicate" : "ok"
]);
