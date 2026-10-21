<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_pengguna.php";

$pengguna = new M_pengguna($koneksi);
$data = $pengguna->tampil_by_id($_GET['id'] ?? 0);

if (!$data) {
    die("Data user tidak ditemukan.");
}

$pesan_map = [
    'kosong'   => 'Nama, username, dan role wajib diisi.',
    'username' => 'Username sudah dipakai, pilih yang lain.',
];
$pesan = $pesan_map[$_GET['pesan'] ?? ''] ?? null;

$role_sekarang = strtolower($data['role']);
$role_sekarang = normal_role($data['role']);

$label_role = 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../asset/peminjaman.css">
</head>
<body>

<div class="navbar">
    <div class="logo">🌸 PEMINJAMAN ALAT</div>

    <div class="navbar-right">
        <span class="admin"><?= htmlspecialchars($label_role ?? ''); ?> · <?= htmlspecialchars($_SESSION['nama'] ?? ''); ?></span>
        <a href="../view/v_logout.php" class="logout">Logout</a>
    </div>
</div>
<div class="sidebar">
    <div class="sidebar-title">MENU ADMIN</div>

    <a href="../view/v_dashboard.php">🏠 Dashboard</a>
    <a href="../view/v_pengguna.php" class="active">👥 User</a>
    <a href="../view/v_alat.php">🧰 Alat</a>
    <a href="../view/v_kategori.php">📂 Kategori</a>
    <a href="../view/v_peminjaman.php">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php">🕐 Log Aktivitas</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Edit User</h1>
            <p class="page-subtitle">Ubah data akun.</p>
        </div>
    </div>

    <div class="form-box form-stack">

        <?php if ($pesan) { ?>
            <div class="pesan error"><?= $pesan; ?></div>
        <?php } ?>

        <form action="../controller/c_pengguna.php?aksi=ubah" method="POST">

            <input type="hidden" name="id_users" value="<?= $data['id_users']; ?>">

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($data['username']); ?>" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" required>
                    <option value="peminjam" <?= $role_sekarang == 'peminjam' ? 'selected' : ''; ?>>Peminjam</option>
                    <option value="petugas" <?= $role_sekarang == 'petugas' ? 'selected' : ''; ?>>Petugas</option>
                    <option value="admin" <?= $role_sekarang == 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="Aktif" <?= ($data['status'] ?? 'Aktif') == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Nonaktif" <?= ($data['status'] ?? 'Aktif') == 'Nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                </select>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                <a href="v_pengguna.php" class="btn-batal">Batal</a>
            </div>

        </form>
    </div>

</div>

</body>
</html>
