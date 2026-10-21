<?php
session_start();

include_once "../models/m_koneksi.php";
include_once "../controller/auth.php";

// Kalau sudah login, langsung ke halaman perannya
if (isset($_SESSION['id_users'], $_SESSION['role']) && role_valid($_SESSION['role'])) {
    header("Location: " . halaman_awal($_SESSION['role']));
    exit;
}

if (($_GET['pesan'] ?? '') == 'role') {
    $error = "Role akun tidak dikenali. Gunakan admin, petugas, atau peminjam.";
}

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = mysqli_query($koneksi, "
        SELECT * FROM users
        WHERE username = '$username'
        AND password = '$password'
    ");

    $user = mysqli_fetch_assoc($query);

    if ($user && !role_valid($user['role'])) {

        $error = "Role akun tidak dikenali. Gunakan admin, petugas, atau peminjam.";

    } elseif ($user) {

        $_SESSION['id_users'] = $user['id_users'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        catat_log($koneksi, "Login ke sistem");

        header("Location: " . halaman_awal($user['role']));
        exit;

    } else {

        $error = "Username atau password salah!";

    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link rel="stylesheet" href="../asset/login.css">
</head>

<body>

    <div class="login-box">

        <div class="login-icon">🌸</div>

        <h2>Login</h2>

        <p class="subtitle">
            Silakan masuk ke akun kamu
        </p>

        <?php if (isset($error)) { ?>
            <p class="error">
                <?= $error; ?>
            </p>
        <?php } ?>

        <form method="POST">

            <div class="input-group">
                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    placeholder="Masukkan username"
                    required
                >
            </div>

            <div class="input-group">
                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <button type="submit" name="login" class="login-btn">
    Login
</button>

        </form>

    </div>

</body>
</html>