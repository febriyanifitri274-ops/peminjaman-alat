<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['peminjam']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";

$peminjaman = new M_peminjaman($koneksi);
$data_riwayat = $peminjaman->tampil_riwayat_user((int) $_SESSION['id_users']);

$pesan_map = [
    'diajukan' => ['Peminjaman berhasil diajukan, tunggu persetujuan petugas.', ''],
];
$pesan = $pesan_map[$_GET['pesan'] ?? ''] ?? null;

$label_role = 'Peminjam';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Peminjaman</title>
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
    <a href="../view/v_daftar_alat.php">🧰 Daftar Alat</a>
    <a href="../view/v_peminjaman_user.php">📦 Ajukan Peminjaman</a>
    <a href="../view/v_pengembalian_user.php">↩️ Kembalikan Alat</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Histori Peminjaman</h1>
            <p class="page-subtitle">Riwayat peminjaman alat yang telah kamu lakukan.</p>
        </div>
    </div>

    <?php if ($pesan) { ?>
        <div class="pesan <?= $pesan[1]; ?>"><?= $pesan[0]; ?></div>
    <?php } ?>

    <div class="table-box">
        <div class="table-title">Histori Peminjaman Saya</div>

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
            if ($data_riwayat && mysqli_num_rows($data_riwayat) > 0) {
                while ($data = mysqli_fetch_assoc($data_riwayat)) {
                    $status = $data['status'] ?? '-';
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nama_alat'] ?? '-'); ?></td>
                    <td><?= date('d-m-Y', strtotime($data['tanggal_pinjam'])); ?></td>
                    <td><?= date('d-m-Y', strtotime($data['tanggal_kembali'])); ?></td>
                    <td><?= htmlspecialchars($data['jumlah']); ?></td>
                    <td><span class="status <?= kelas_status($status); ?>"><?= htmlspecialchars($status); ?></span></td>
                    <td><?= htmlspecialchars($data['keterangan'] ?? '-'); ?></td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr><td colspan="7" class="kosong">Belum ada riwayat peminjaman.</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
