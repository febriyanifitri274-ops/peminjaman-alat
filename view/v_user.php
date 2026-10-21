<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['peminjam']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";

$peminjaman = new M_peminjaman($koneksi);
$id_users = (int) $_SESSION['id_users'];

$menunggu = $peminjaman->hitung_user_status($id_users, 'Menunggu');
$dipinjam = $peminjaman->hitung_user_status($id_users, 'Dipinjam');
$menunggu_kembali = $peminjaman->hitung_user_status($id_users, 'Menunggu Pengembalian');

$label_role = 'Peminjam';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjam</title>
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

    <a href="../view/v_user.php" class="active">🏠 Dashboard</a>
    <a href="../view/v_daftar_alat.php">🧰 Daftar Alat</a>
    <a href="../view/v_peminjaman_user.php">📦 Ajukan Peminjaman</a>
    <a href="../view/v_pengembalian_user.php">↩️ Kembalikan Alat</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Dashboard Peminjam</h1>
            <p class="page-subtitle">Selamat datang di aplikasi peminjaman alat</p>
        </div>
    </div>

    <div class="stat-cards">
        <div class="stat-card">
            <h3>Menunggu Persetujuan</h3>
            <div class="angka"><?= $menunggu; ?></div>
        </div>
        <div class="stat-card">
            <h3>Sedang Dipinjam</h3>
            <div class="angka"><?= $dipinjam; ?></div>
        </div>
        <div class="stat-card">
            <h3>Menunggu Pengembalian</h3>
            <div class="angka"><?= $menunggu_kembali; ?></div>
        </div>
    </div>

    <div class="table-box">
        <div class="table-title">Selamat Datang 👋</div>
        <p>Lihat daftar alat, ajukan peminjaman, dan kembalikan alat setelah selesai memakai lewat menu di sebelah kiri.</p>
    </div>

</div>

</body>
</html>
