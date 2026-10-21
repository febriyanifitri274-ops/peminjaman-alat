<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['petugas']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";

$peminjaman = new M_peminjaman($koneksi);

$menunggu_pinjam = $peminjaman->hitung_status('Menunggu');
$menunggu_kembali = $peminjaman->hitung_status('Menunggu Pengembalian');
$sedang_dipinjam = $peminjaman->hitung_status('Dipinjam');

$label_role = 'Petugas';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
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
    <div class="sidebar-title">MENU PETUGAS</div>

    <a href="../view/v_petugas.php" class="active">🏠 Dashboard</a>
    <a href="../view/v_setujui_peminjaman.php">✅ Setujui Peminjaman</a>
    <a href="../view/v_pantau_pengembalian.php">📥 Pantau Pengembalian</a>
    <a href="../view/v_laporan.php">🖨️ Cetak Laporan</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Dashboard Petugas</h1>
            <p class="page-subtitle">Setujui peminjaman, pantau pengembalian, dan cetak laporan.</p>
        </div>
    </div>

    <div class="stat-cards">
        <div class="stat-card">
            <h3>Menunggu Persetujuan Peminjaman</h3>
            <div class="angka"><?= $menunggu_pinjam; ?></div>
        </div>
        <div class="stat-card">
            <h3>Menunggu Persetujuan Pengembalian</h3>
            <div class="angka"><?= $menunggu_kembali; ?></div>
        </div>
        <div class="stat-card">
            <h3>Sedang Dipinjam</h3>
            <div class="angka"><?= $sedang_dipinjam; ?></div>
        </div>
    </div>

    <div class="table-box">
        <div class="table-title">Selamat Datang 👋</div>
        <p>Gunakan menu di sebelah kiri untuk menyetujui peminjaman, memantau pengembalian, atau mencetak laporan.</p>
    </div>

</div>

</body>
</html>
