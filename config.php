<?php

define('DB_HOST','localhost');
define('DB_NAME','si_arsip_kua');
define('DB_USER','root');
define('DB_PASS','');
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_BASE', '/si_arsip_kua/uploads/');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch(Exception $e){
    die('Database connection failed: '.$e->getMessage());
}
?>