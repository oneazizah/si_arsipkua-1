<?php
require_once __DIR__ . '/config.php';

function flash($name, $msg = null) {
    if ($msg === null) {
        if (isset($_SESSION['flash'][$name])) {
            $m = $_SESSION['flash'][$name];
            unset($_SESSION['flash'][$name]);
            return $m;
        }
        return null;
    }
    $_SESSION['flash'][$name] = $msg;
}

function validate_file($file) {
    $allowed = ['application/pdf', 'image/jpeg', 'image/png'];
    $maxBytes = 5 * 1024 * 1024; // 5MB
    if ($file['error'] !== UPLOAD_ERR_OK) return "Upload gagal (error code {$file['error']}).";
    $mime = mime_content_type($file['tmp_name']);
    if (!in_array($mime, $allowed)) return "Format file tidak didukung. Hanya PDF/JPG/PNG.";
    if ($file['size'] > $maxBytes) return "Ukuran file maksimal 5MB.";
    return true;
}

function save_uploaded_file($file) {
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = uniqid('arsip_', true) . '.' . $ext;
    $dest = UPLOAD_DIR . $name;
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return $name;
    }
    return false;
}

function add_log($activity) {
    global $pdo;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '-';
    $stmt = $pdo->prepare("INSERT INTO log_activity (user_ip, activity) VALUES (:ip, :ac)");
    $stmt->execute([
        ':ip' => $ip,
        ':ac' => $activity
    ]);
}

?>