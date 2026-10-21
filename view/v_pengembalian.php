<?php
$label_role = 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian</title>
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
            <h1 class="page-title">Pengembalian</h1>
            <p class="page-subtitle">Kelola data pengembalian alat.</p>
        </div>

        <a href="../controller/c_pengembalian.php?aksi=tambah" class="btn-tambah">+ Catat Pengembalian</a>
    </div>

    <?php if ($pesan) { ?>
        <div class="pesan <?= $pesan[1]; ?>"><?= $pesan[0]; ?></div>
    <?php } ?>

    <div class="table-box">
        <div class="table-title">Data Pengembalian</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            if ($data_pengembalian && mysqli_num_rows($data_pengembalian) > 0) {
                while ($data = mysqli_fetch_assoc($data_pengembalian)) {
                    $status = $data['status'] ?? '-';
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nama'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['nama_alat'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['jumlah']); ?></td>
                    <td><?= date('d-m-Y', strtotime($data['tanggal_pinjam'])); ?></td>
                    <td><?= date('d-m-Y', strtotime($data['tanggal_kembali'])); ?></td>
                    <td><span class="status <?= kelas_status($status); ?>"><?= htmlspecialchars($status); ?></span></td>
                    <td>
                        <?php if ($status == 'Menunggu Pengembalian') { ?>
                            <form method="POST" action="../controller/c_pengembalian.php?aksi=terima" class="form-inline"
                                  onsubmit="return confirm('Terima pengembalian ini? Stok alat akan bertambah.');">
                                <input type="hidden" name="id_peminjaman" value="<?= $data['id_peminjaman']; ?>">
                                <button type="submit" class="btn-acc">Terima</button>
                            </form>
                        <?php } ?>

                        <a href="../controller/c_pengembalian.php?aksi=edit&id=<?= $data['id_peminjaman']; ?>" class="btn-edit">Edit</a>

                        <form method="POST" action="../controller/c_pengembalian.php?aksi=hapus" class="form-inline"
                              onsubmit="return confirm('Hapus data pengembalian ini?');">
                            <input type="hidden" name="id_peminjaman" value="<?= $data['id_peminjaman']; ?>">
                            <button type="submit" class="btn-hapus">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr><td colspan="8" class="kosong">Belum ada data pengembalian.</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
