<?php
session_start();

if (
    !isset($_SESSION['id_users']) ||
    strtolower($_SESSION['role']) != 'admin'
) {
    header("Location: v_login.php");
    exit;
}

include_once "../models/m_koneksi.php";
include_once "../models/m_users.php";

$users = new M_users($koneksi);

$id = $_GET['id'] ?? 0;

$data = $users->tampil_by_id($id);

if (!$data) {
    die("Data user tidak ditemukan.");
}

$error = '';

if (isset($_POST['ubah'])) {

    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($users->username_ada($username, $id)) {
        $error = "Username sudah dipakai user lain.";
        $data['nama'] = $nama;
        $data['username'] = $username;
        $data['role'] = $role;
    } else {
        $users->ubah_data($id, $nama, $username, $password, $role);

        header("Location: v_users.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>

    <link rel="stylesheet" href="../asset/kategori.css">
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">

    <div class="logo">
        🌸 PEMINJAMAN ALAT
    </div>

    <div class="navbar-right">
        <span>🔔</span>
        <span class="admin">Admin</span>
    </div>

</div>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="sidebar-title">
        MENU UTAMA
    </div>

    <a href="v_dashboard.php">
        🏠 Dashboard
    </a>

    <a href="v_users.php" class="active">
        👥 User
    </a>

    <a href="v_alat.php">
        🧰 Alat
    </a>

    <a href="v_kategori.php">
        📂 Kategori
    </a>

    <a href="v_peminjaman.php">
        📋 Peminjaman
    </a>

    <a href="v_log_aktivitas.php">
        🕐 Log Aktivitas
    </a>

</div>

<!-- CONTENT -->
<div class="content">

    <h1 class="page-title">
        Edit User
    </h1>

    <p class="page-subtitle">
        Ubah data user.
    </p>

    <div class="form-box">

        <?php if ($error !== '') { ?>
            <p class="form-error"><?= htmlspecialchars($error); ?></p>
        <?php } ?>

        <form method="POST">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama"
                       value="<?= htmlspecialchars($data['nama']); ?>" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username"
                       value="<?= htmlspecialchars($data['username']); ?>" required>
            </div>

            <div class="form-group">
                <label>Password <small>(kosongkan jika tidak diubah)</small></label>
                <input type="password" name="password">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <?php foreach (['admin', 'petugas', 'user'] as $r) { ?>
                        <option value="<?= $r; ?>"
                            <?= (strtolower($data['role']) == $r) ? 'selected' : ''; ?>>
                            <?= ucfirst($r); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-buttons">
                <button type="submit" name="ubah" class="btn-simpan">Simpan Perubahan</button>

                <a href="v_users.php" class="btn-batal">Batal</a>
            </div>

        </form>

    </div>

</div>

</body>
</html>