<?php
require 'functions.php';

$nomor = $_GET['nomor'] ?? '';
$id    = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT id FROM akta_nikah 
                       WHERE nomor_akta = :no AND id != :id");
$stmt->execute(['no' => $nomor, 'id' => $id]);

echo json_encode([
    "status" => $stmt->fetch() ? "duplicate" : "ok"
]);
