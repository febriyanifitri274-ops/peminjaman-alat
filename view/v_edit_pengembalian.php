<?php
$label_role = 'Admin';
$status_boleh = ['Dipinjam', 'Menunggu Pengembalian', 'Dikembalikan'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengembalian</title>
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
            <h1 class="page-title">Edit Pengembalian</h1>
            <p class="page-subtitle">Ubah status dan keterangan pengembalian.</p>
        </div>
    </div>

    <div class="form-box form-stack">
        <form method="POST" action="../controller/c_pengembalian.php?aksi=edit">
            <input type="hidden" name="id_peminjaman" value="<?= $data['id_peminjaman']; ?>">

            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <?php foreach ($status_boleh as $st) { ?>
                        <option value="<?= $st; ?>" <?= $data['status'] == $st ? 'selected' : ''; ?>><?= $st; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan"><?= htmlspecialchars($data['keterangan'] ?? ''); ?></textarea>
            </div>

            <button type="submit" class="btn-simpan">Simpan Perubahan</button>
            <a href="../controller/c_pengembalian.php" class="btn-batal">Batal</a>
        </form>
    </div>

</div>

</body>
</html>
