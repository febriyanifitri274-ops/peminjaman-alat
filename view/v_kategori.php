<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_kategori.php";

$kategori = new M_kategori($koneksi);
$data_kategori = $kategori->tampil_data();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kategori</title>

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
    <div class="sidebar-title">MENU ADMIN</div>

    <a href="../view/v_dashboard.php">🏠 Dashboard</a>
    <a href="../view/v_pengguna.php">👥 User</a>
    <a href="../view/v_alat.php">🧰 Alat</a>
    <a href="../view/v_kategori.php" class="active">📂 Kategori</a>
    <a href="../view/v_peminjaman.php">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php">🕐 Log Aktivitas</a>
</div>


<!-- CONTENT -->
<div class="content">

    <div class="page-top">

        <div>
            <h1 class="page-title">
                Kategori
            </h1>

            <p class="page-subtitle">
                Kelola kategori alat.
            </p>
        </div>

        <a href="v_tambah_kategori.php" class="btn-tambah">
            + Tambah Kategori
        </a>

    </div>


    <!-- TABEL -->
    <div class="table-box">

        <div class="table-title">
            Data Kategori
        </div>

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php
            $no = 1;

            while ($data = mysqli_fetch_assoc($data_kategori)) {
            ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td>
                        <?= $data['nama_kategori']; ?>
                    </td>

                    <td>
                        <?= $data['keterangan']; ?>
                    </td>

                    <!-- AKSI -->
                     
                    <td>

                        <div class="aksi">

                        <a href="v_edit_kategori.php?id=<?= $data['id_kategori']; ?>"
                           class="btn-edit">
                            Edit
                        </a>

                        <a href="v_hapus_kategori.php?id=<?= $data['id_kategori']; ?>"
                           class="btn-hapus"
                           onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                            Hapus
                        </a>

                        </div>

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