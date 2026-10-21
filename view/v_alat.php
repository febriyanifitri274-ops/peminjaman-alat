<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);

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

    <title>Alat</title>

    <link rel="stylesheet" href="../asset/alat.css">
</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

    <div class="logo">
        🌸 PEMINJAMAN ALAT
    </div>

    <div class="navbar-right">

        <span>🔔</span>

        <span class="admin">
            Admin
        </span>

    </div>

</div>


<!-- SIDEBAR -->

<div class="sidebar">
    <div class="sidebar-title">MENU ADMIN</div>

    <a href="../view/v_dashboard.php">🏠 Dashboard</a>
    <a href="../view/v_pengguna.php">👥 User</a>
    <a href="../view/v_alat.php" class="active">🧰 Alat</a>
    <a href="../view/v_kategori.php">📂 Kategori</a>
    <a href="../view/v_peminjaman.php">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php">🕐 Log Aktivitas</a>
</div>


<!-- CONTENT -->

<div class="content">

   <div class="page-top">
    <div>
        <h1 class="page-title">Alat</h1>
        <p class="page-subtitle">Kelola data alat yang tersedia.</p>
    </div>

    <a href="../controller/c_alat.php?aksi=tambah" class="btn-tambah">
        + Tambah Alat
    </a>
</div>


    <!-- DATA ALAT -->

    <div class="table-box">

        <h2>
            Data Alat
        </h2>

        <div class="table-responsive">

            <table>

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while ($data = mysqli_fetch_assoc($data_alat)) {
                    ?>

                    <tr>

                        <td>
                            <?= $no++; ?>
                        </td>


                        <!-- FOTO -->
<td>

    <?php if (!empty($data['foto'])) { ?>

        <img
            src="../asset/foto_alat/<?= htmlspecialchars(trim($data['foto'])); ?>"
            class="foto-alat"
            width="60"
            height="60"
            alt="Foto Alat"
        >

    <?php } else { ?>

        <span>Tidak ada foto</span>

    <?php } ?>

</td>


                        <!-- NAMA ALAT -->

                        <td>
                            <?= htmlspecialchars($data['nama_alat']); ?>
                        </td>


                        <!-- KATEGORI -->

                        <td>
                            <?= htmlspecialchars($data['nama_kategori']); ?>
                        </td>


                        <!-- JUMLAH -->

                        <td>
                            <?= htmlspecialchars($data['jumlah']); ?>
                        </td>


                        <!-- HARGA -->

                        <td>
                            Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
                        </td>


                        <!-- KETERANGAN -->

                        <td>
                            <?= htmlspecialchars($data['keterangan']); ?>
                        </td>


                        <!-- AKSI -->

                        <td>
                            <div class="aksi">
                                <a href="../controller/c_alat.php?aksi=edit&id=<?= $data['id_alat']; ?>" class="btn-edit">
                                    Edit
                                </a>

                                <a href="../controller/c_alat.php?aksi=hapus&id=<?= $data['id_alat']; ?>" class="btn-hapus">
                                    Hapus
                                </a>
                            </div>
                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>