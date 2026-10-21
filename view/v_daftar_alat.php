<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['peminjam']);

include_once "../models/m_koneksi.php";
include_once "../models/m_alat.php";

$alat = new M_alat($koneksi);
$data_alat = $alat->tampil_data();

$label_role = 'Peminjam';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Alat</title>
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
    <div class="sidebar-title">MENU PEMINJAM</div>

    <a href="../view/v_user.php">🏠 Dashboard</a>
    <a href="../view/v_daftar_alat.php" class="active">🧰 Daftar Alat</a>
    <a href="../view/v_peminjaman_user.php">📦 Ajukan Peminjaman</a>
    <a href="../view/v_pengembalian_user.php">↩️ Kembalikan Alat</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Daftar Alat</h1>
            <p class="page-subtitle">Alat yang tersedia untuk dipinjam.</p>
        </div>
    </div>

    <div class="table-box">
        <div class="table-title">Daftar Alat</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            if ($data_alat && mysqli_num_rows($data_alat) > 0) {
                while ($data = mysqli_fetch_assoc($data_alat)) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <?php if (!empty(trim($data['foto'] ?? ''))) { ?>
                            <img src="../asset/foto_alat/<?= htmlspecialchars(trim($data['foto'])); ?>"
                                 width="60" height="60" alt="Foto Alat">
                        <?php } else { ?>
                            <span>Tidak ada foto</span>
                        <?php } ?>
                    </td>
                    <td><?= htmlspecialchars($data['nama_alat']); ?></td>
                    <td><?= htmlspecialchars($data['nama_kategori'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['keterangan'] ?? '-'); ?></td>
                    <td>
                        <?php if ($data['jumlah'] > 0) { ?>
                            <?= htmlspecialchars($data['jumlah']); ?>
                        <?php } else { ?>
                            <span class="status status-ditolak">Stok habis</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr><td colspan="6" class="kosong">Belum ada data alat.</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
