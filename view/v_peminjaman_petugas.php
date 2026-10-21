<?php

include_once "../models/m_koneksi.php";
include_once "../models/m_alat.php";

$alat = new M_alat($koneksi);
$data_alat = $alat->tampil_data();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Alat</title>

    <link rel="stylesheet" href="../asset/petugas.css">
</head>

<body>

    <!-- NAVBAR -->
<div class="navbar">

    <div class="logo">
        🌸 PEMINJAMAN ALAT
    </div>

    <div class="navbar-right">
        <span>🔔</span>
        <span class="admin">Petugas</span>

       
    </div>

</div>
   <div class="sidebar">

    <div class="sidebar-title">
        MENU USER
    </div>

    <a href="../view/v_petugas.php">
        🏠 Dashboard
    </a>

    <a href="../view/v_peminjaman_petugas.php" class="active">
        📦 Peminjaman Alat
    </a>

    <a href="../controller/c_petugas.php?aksi=status">
        📋 Status Peminjaman
    </a>

    <a href="../view/v_riwayat.php">
        🕘 Riwayat Peminjaman
    </a>

</div>

    <!-- CONTENT -->
    <div class="content">

        <div class="page-top">
            <div>
                <h1 class="page-title">
                    Peminjaman Alat
                </h1>

                <p class="page-subtitle">
                    Pilih alat yang ingin kamu pinjam
                </p>
            </div>
        </div>

        <div class="table-box">

            <div class="table-title">
                Daftar Alat
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Kondisi</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                  <?php
$no = 1;

while ($data = mysqli_fetch_assoc($data_alat)) {
?>
<tr>
    <td><?= $no++; ?></td>

    <td>
        <?= htmlspecialchars($data['nama_alat']); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['nama_kategori'] ?? '-'); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['keterangan'] ?? '-'); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['jumlah']); ?>
    </td>

    <td>
        <a
    href="v_form_peminjaman_petugas.php?id_alat=<?= $data['id_alat']; ?>"
    class="btn-pinjam-petugas">
    Pinjam
</a>
    </td>
</tr>
<?php
 } ?>
                </tbody>
            </table>

        </div>

    </div>

</body>
</html>