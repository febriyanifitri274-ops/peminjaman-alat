<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_kategori.php";

if (isset($_POST['simpan'])) {

    $nama_kategori = $_POST['nama_kategori'];
    $keterangan = $_POST['keterangan'];

    $kategori = new M_kategori($koneksi);

    $kategori->tambah_data(
        $nama_kategori,
        $keterangan
    );

    header("Location: v_kategori.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori</title>
    <link rel="stylesheet" href="../asset/kategori.css">
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">

    <div class="logo">
        🌸 Peminjaman Alat
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

    <h1 class="page-title">
        Tambah Kategori
    </h1>

    <p class="page-subtitle">
        Tambahkan kategori alat baru.
    </p>

    <div class="form-box">

        <form method="POST">
            
<div class="form-group">
    <label>Nama Kategori</label>
    <input type="text" name="nama_kategori" required>
</div>

<div class="form-group">
    <label>Keterangan</label>
    <textarea name="keterangan"></textarea>
</div>


            <div class="form-buttons">

                <button type="submit"
        name="simpan"
        class="btn-simpan">
    Simpan
</button>

                <a href="v_kategori.php"
                   class="btn-batal">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>