<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin', 'petugas']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";

$peminjaman = new M_peminjaman($koneksi);
$data_histori = $peminjaman->tampil_data();

$role = strtolower($_SESSION['role']);
$label_role = ucfirst($role);
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
<?php if ($role == 'admin') { ?>
<div class="sidebar">
    <div class="sidebar-title">MENU ADMIN</div>

    <a href="../view/v_dashboard.php">🏠 Dashboard</a>
    <a href="../view/v_pengguna.php">👥 User</a>
    <a href="../view/v_alat.php">🧰 Alat</a>
    <a href="../view/v_kategori.php">📂 Kategori</a>
    <a href="../view/v_peminjaman.php">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php">🕐 Log Aktivitas</a>
</div>
<?php } else { ?>
<div class="sidebar">
    <div class="sidebar-title">MENU PETUGAS</div>

    <a href="../view/v_petugas.php">🏠 Dashboard</a>
    <a href="../view/v_setujui_peminjaman.php">✅ Setujui Peminjaman</a>
    <a href="../view/v_pantau_pengembalian.php">📥 Pantau Pengembalian</a>
    <a href="../view/v_laporan.php">🖨️ Cetak Laporan</a>
</div>
<?php } ?>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Histori Peminjaman</h1>
            <p class="page-subtitle">Seluruh riwayat peminjaman dan pengembalian alat.</p>
        </div>

        <button onclick="window.print()" class="btn-cetak">
        🖨️ Cetak Laporan
    </button>
    
    </div>

    <div class="table-box">
        <div class="table-title">Data Histori</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            if ($data_histori && mysqli_num_rows($data_histori) > 0) {
                while ($data = mysqli_fetch_assoc($data_histori)) {
                    $status = $data['status'] ?? '-';
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nama'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['nama_alat'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['nama_kategori'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['jumlah'] ?? '0'); ?></td>
                    <td><?= !empty($data['tanggal_pinjam']) ? date('d-m-Y', strtotime($data['tanggal_pinjam'])) : '-'; ?></td>
                    <td><?= !empty($data['tanggal_kembali']) ? date('d-m-Y', strtotime($data['tanggal_kembali'])) : '-'; ?></td>
                    <td><span class="status <?= kelas_status($status); ?>"><?= htmlspecialchars($status); ?></span></td>
                    <td><?= htmlspecialchars($data['keterangan'] ?? '-'); ?></td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr><td colspan="9" class="kosong">Belum ada data peminjaman.</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
