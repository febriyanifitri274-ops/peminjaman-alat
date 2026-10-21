
<?php
session_start();

// Cek apakah sudah login sebagai Admin
if (
    !isset($_SESSION['id_users']) ||
    strtolower($_SESSION['role']) != 'admin'
) {
    header("Location: v_login.php");
    exit;
}

include_once "../models/m_koneksi.php";

// STATISTIK
$total_kategori = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kategori_alat")
)['total'];

$total_alat = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM alat")
)['total'];

$total_peminjaman = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM peminjaman")
)['total'];

$sedang_dipinjam = mysqli_fetch_assoc(
    mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total FROM peminjaman
         WHERE status = 'Dipinjam'"
    )
)['total'];

// PEMINJAMAN TERBARU
$peminjaman_terbaru = mysqli_query(
    $koneksi,
    "SELECT peminjaman.*,
            users.nama AS nama_peminjam,
            alat.nama_alat
     FROM peminjaman
     LEFT JOIN users
        ON peminjaman.id_users = users.id_users
     LEFT JOIN alat
        ON peminjaman.id_alat = alat.id_alat
     ORDER BY peminjaman.id_peminjaman DESC
     LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Peminjaman Alat</title>

    <link rel="stylesheet" href="../asset/dashboard.css">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-title">MENU ADMIN</div>

    <a href="../view/v_dashboard.php" class="active">🏠 Dashboard</a>
    <a href="../view/v_pengguna.php">👥 User</a>
    <a href="../view/v_alat.php">🧰 Alat</a>
    <a href="../view/v_kategori.php">📂 Kategori</a>
    <a href="../view/v_peminjaman.php">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php">🕐 Log Aktivitas</a>
</div>

<!-- MAIN -->
<div class="main">

   <!-- NAVBAR -->
<div class="navbar">

    <div class="logo">
        🌸 PEMINJAMAN ALAT
    </div>

    <div class="navbar-right">
        <span>🔔</span>
        <span class="admin">Admin</span>

        <a href="v_logout.php" class="logout">
            Logout
        </a>
    </div>

</div>
    <!-- CONTENT -->
    <div class="content">

        <div class="content-header">

            <h1>Dashboard</h1>

            <p>
                Selamat datang di sistem peminjaman alat 👋
            </p>

        </div>

        <!-- STATISTIC CARD -->
      <div class="cards">

    <div class="card">
        <div class="card-icon">📂</div>
        <h3>Total Kategori</h3>
        <div class="number"><?= $total_kategori; ?></div>
    </div>

    <div class="card">
        <div class="card-icon">🧰</div>
        <h3>Total Alat</h3>
        <div class="number"><?= $total_alat; ?></div>
    </div>

    <div class="card">
        <div class="card-icon">📋</div>
        <h3>Total Peminjaman</h3>
        <div class="number"><?= $total_peminjaman; ?></div>
    </div>

    <div class="card">
        <div class="card-icon">⏳</div>
        <h3>Sedang Dipinjam</h3>
        <div class="number"><?= $sedang_dipinjam; ?></div>
    </div>

</div>
        <!-- TABEL PEMINJAMAN -->
        <section class="table-card">

            <div class="table-header">
                <h2>Peminjaman Terbaru</h2>
            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $no = 1;

                        while (
                            $data = mysqli_fetch_assoc($peminjaman_terbaru)
                        ) {
                        ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td>
                                <?= htmlspecialchars(
                                    $data['nama_peminjam'] ?? '-'
                                ); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $data['nama_alat'] ?? '-'
                                ); ?>
                            </td>

                            <td>
                                <?= date(
                                    'd-m-Y',
                                    strtotime($data['tanggal_pinjam'])
                                ); ?>
                            </td>

                            <td>
                                <span class="status">
                                    <?= htmlspecialchars(
                                        $data['status'] ?? '-'
                                    ); ?>
                                </span>
                            </td>

                        </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </section>

    </div>

</div>

</body>
</html>