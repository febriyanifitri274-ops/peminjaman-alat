<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['petugas']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";

$peminjaman = new M_peminjaman($koneksi);
$data_pengembalian = $peminjaman->tampil_pengembalian();

$label_role = 'Petugas';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantau Pengembalian</title>
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

    <a href="../view/v_petugas.php">🏠 Dashboard</a>
    <a href="../view/v_setujui_peminjaman.php">✅ Setujui Peminjaman</a>
    <a href="../view/v_pantau_pengembalian.php" class="active">📥 Pantau Pengembalian</a>
    <a href="../view/v_laporan.php">🖨️ Cetak Laporan</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Pantau Pengembalian</h1>
            <p class="page-subtitle">Pantau alat yang sedang diajukan kembali dan yang sudah dikembalikan.</p>
        </div>
    </div>

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
                </tr>
            <?php
                }
            } else {
            ?>
                <tr><td colspan="7" class="kosong">Belum ada data pengembalian.</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
