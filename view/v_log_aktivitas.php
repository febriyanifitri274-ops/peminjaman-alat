<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_log_aktivitas.php";

$log = new m_log_aktivitas($koneksi);
// PAGINATION (15 data per halaman)
$per_halaman   = 15;
$total_data    = $log->hitung_total();
$total_halaman = max(1, (int) ceil($total_data / $per_halaman));

$halaman = (int) ($_GET['hal'] ?? 1);
if ($halaman < 1) {
    $halaman = 1;
}
if ($halaman > $total_halaman) {
    $halaman = $total_halaman;
}

$offset   = ($halaman - 1) * $per_halaman;
$data_log = $log->tampil_data($per_halaman, $offset);

if (!$data_log) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas</title>

    <link rel="stylesheet" href="../asset/log_aktivitas.css">
</head>
<body>

<!-- NAVBAR -->

<div class="navbar">
    <div class="logo">🌸 PEMINJAMAN ALAT</div>

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
    <a href="../view/v_kategori.php">📂 Kategori</a>
    <a href="../view/v_peminjaman.php">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php" class="active">🕐 Log Aktivitas</a>
</div>

<!-- CONTENT -->

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Log Aktivitas</h1>
            <p class="page-subtitle">
                Riwayat aktivitas pengguna sistem
            </p>
        </div>
    </div>

    <div class="table-box">

        <div class="table-title">
            Data Log Aktivitas
        </div>

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pengguna</th>
                    <th>Aktivitas</th>
                    <th>Waktu</th>
                </tr>
            </thead>

            <tbody>

                <?php
                $no = $offset + 1;

                while ($data = mysqli_fetch_assoc($data_log)) {
                ?>

                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nama'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['aktivitas']); ?></td>
                    <td><?= htmlspecialchars($data['waktu']); ?></td>
                </tr>

                <?php } ?>

            </tbody>

        </table>

        <!-- PAGINATION -->
        <?php if ($total_halaman > 1) {

            $dari   = $offset + 1;
            $sampai = min($offset + $per_halaman, $total_data);

            // nomor halaman yang ditampilkan (halaman aktif +/- 2)
            $awal  = max(1, $halaman - 2);
            $akhir = min($total_halaman, $halaman + 2);
        ?>

        <div class="pagination-wrap">

            <div class="pagination-info">
                Menampilkan <?= $dari; ?>–<?= $sampai; ?> dari <?= $total_data; ?> data
            </div>

            <div class="pagination">

                <?php if ($halaman > 1) { ?>
                    <a href="?hal=<?= $halaman - 1; ?>">&laquo; Sebelumnya</a>
                <?php } else { ?>
                    <span class="disabled">&laquo; Sebelumnya</span>
                <?php } ?>

                <?php if ($awal > 1) { ?>
                    <a href="?hal=1">1</a>
                    <?php if ($awal > 2) { ?><span class="dots">…</span><?php } ?>
                <?php } ?>

                <?php for ($i = $awal; $i <= $akhir; $i++) { ?>
                    <?php if ($i == $halaman) { ?>
                        <span class="active"><?= $i; ?></span>
                    <?php } else { ?>
                        <a href="?hal=<?= $i; ?>"><?= $i; ?></a>
                    <?php } ?>
                <?php } ?>

                <?php if ($akhir < $total_halaman) { ?>
                    <?php if ($akhir < $total_halaman - 1) { ?><span class="dots">…</span><?php } ?>
                    <a href="?hal=<?= $total_halaman; ?>"><?= $total_halaman; ?></a>
                <?php } ?>

                <?php if ($halaman < $total_halaman) { ?>
                    <a href="?hal=<?= $halaman + 1; ?>">Berikutnya &raquo;</a>
                <?php } else { ?>
                    <span class="disabled">Berikutnya &raquo;</span>
                <?php } ?>

            </div>

        </div>

        <?php } ?>

    </div>

</div>

</body>
</html>