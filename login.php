<?php
require 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u LIMIT 1");
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau Password salah";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Arsip KUA</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="assets/css/auth.css" rel="stylesheet">
</head>

<body>

<div class="login-wrapper">
    <div class="login-card animate__animated animate__fadeInDown">

        <div class="text-center mb-4">
            <h3 class="fw-bold">
                <i class="bi bi-lock-fill"></i> Login Sistem Arsip
            </h3>
            <span class="text-muted">Masukkan kredensial Anda</span>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">

            <div class="input-group mb-3">
                <span class="input-group-text bg-primary text-white"><i class="bi bi-person-fill"></i></span>
                <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required autofocus>
            </div>

            <div class="input-group mb-4">
                <span class="input-group-text bg-primary text-white"><i class="bi bi-shield-lock-fill"></i></span>
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
            </div>

            <button class="btn btn-primary w-100 btn-lg">Masuk</button>
        </form>

    </div>
</div>

</body>
</html>
