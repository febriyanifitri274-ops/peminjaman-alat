<?php
$label_role = 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catat Pengembalian</title>
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
    <a href="../view/v_pengguna.php">👥 User</a>
    <a href="../view/v_alat.php">🧰 Alat</a>
    <a href="../view/v_kategori.php">📂 Kategori</a>
    <a href="../view/v_peminjaman.php">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php" class="active">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php">🕐 Log Aktivitas</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Catat Pengembalian</h1>
            <p class="page-subtitle">Pilih peminjaman yang alatnya sudah dikembalikan.</p>
        </div>
    </div>

    <div class="form-box form-stack">
        <form method="POST" action="../controller/c_pengembalian.php?aksi=tambah">

            <div class="form-group">
                <label>Peminjaman</label>
                <select name="id_peminjaman" required>
                    <option value="">-- Pilih peminjaman --</option>
                    <?php while ($p = mysqli_fetch_assoc($data_belum)) { ?>
                        <option value="<?= $p['id_peminjaman']; ?>">
                            <?= htmlspecialchars(($p['nama'] ?? '-') . ' - ' . ($p['nama_alat'] ?? '-') . ' (' . $p['jumlah'] . ') - ' . $p['status']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit" class="btn-simpan">Simpan</button>
            <a href="../controller/c_pengembalian.php" class="btn-batal">Batal</a>
        </form>
    </div>

</div>

</body>
</html>
