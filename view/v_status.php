<?php

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Status Peminjaman</title>

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


<!-- SIDEBAR -->
<div class="sidebar">

    <div class="sidebar-title">
        MENU USER
    </div>

    <a href="../view/v_petugas.php">
        🏠 Dashboard
    </a>

    <a href="../view/v_peminjaman_petugas.php">
        📦 Peminjaman Alat
    </a>

    <a href="../controller/c_petugas.php?aksi=status" class="active">
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
                Status Peminjaman
            </h1>

            <p class="page-subtitle">
                Lihat status alat yang sedang kamu pinjam
            </p>
        </div>
    </div>


    <div class="table-box">

        <div class="table-title">
            Data Peminjaman
        </div>

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Alat</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>

            <?php
            $no = 1;

            if ($data_status && mysqli_num_rows($data_status) > 0) {

                while ($data = mysqli_fetch_assoc($data_status)) {
            ?>

                <tr>

                    <td><?= $no++; ?></td>

                    <td>
                        <?= htmlspecialchars($data['nama_alat']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['tanggal_pinjam']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['tanggal_kembali']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['jumlah']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['status']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($data['keterangan'] ?? '-'); ?>
                    </td>

                </tr>

            <?php
                }

            } else {
            ?>

                <tr>
                    <td colspan="7" class="text-center">
                        Belum ada data peminjaman
                    </td>
                </tr>

            <?php
            }
            ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>