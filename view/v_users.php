<?php
session_start();

if (
    !isset($_SESSION['id_users']) ||
    strtolower($_SESSION['role']) != 'admin'
) {
    header("Location: v_login.php");
    exit;
}

include_once "../models/m_koneksi.php";
include_once "../models/m_users.php";

$users = new M_users($koneksi);

$cari = trim($_GET['cari'] ?? '');

$data_users = $users->tampil_data($cari);

if (!$data_users) {
    die("Query user gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>User</title>

    <link rel="stylesheet" href="../asset/users.css">
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

    <div class="sidebar-title">
        MENU UTAMA
    </div>

    <a href="v_dashboard.php">
        🏠 Dashboard
    </a>

    <a href="v_users.php" class="active">
        👥 User
    </a>

    <a href="v_alat.php">
        🧰 Alat
    </a>

    <a href="v_kategori.php">
        📂 Kategori
    </a>

    <a href="v_peminjaman.php">
        📋 Peminjaman
    </a>

    <a href="v_log_aktivitas.php">
        🕐 Log Aktivitas
    </a>

</div>

<!-- CONTENT -->
<div class="content">

    <div class="page-top">

        <div>
            <h1 class="page-title">
                User
            </h1>

            <p class="page-subtitle">
                Kelola data user.
            </p>
        </div>

        <a href="v_tambah_user.php" class="btn-tambah">
            + Tambah User
        </a>

    </div>

    <div class="table-box">

        <div class="table-title">
            Data User
        </div>

        <!-- PENCARIAN -->
        <form method="GET" action="v_users.php" class="search-form">
            <input type="text"
                   name="cari"
                   class="search-input"
                   placeholder="Cari nama atau username..."
                   value="<?= htmlspecialchars($cari); ?>">

            <button type="submit" class="btn-cari">Cari</button>

            <?php if ($cari !== '') { ?>
                <a href="v_users.php" class="btn-reset">Reset</a>
            <?php } ?>
        </form>

        <?php if ($cari !== '') { ?>
            <p class="search-info">
                Hasil pencarian untuk "<b><?= htmlspecialchars($cari); ?></b>":
                <?= mysqli_num_rows($data_users); ?> data ditemukan.
            </p>
        <?php } ?>

        <table>

           <thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Username</th>
        <th>Role</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
</thead>

            <tbody>

            <?php
            $no = 1;

            while ($data = mysqli_fetch_assoc($data_users)) {
            ?>

               <tr>
    <td><?= $no++; ?></td>

    <td>
        <?= htmlspecialchars($data['nama']); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['username']); ?>
    </td>

    <td>
        <?= htmlspecialchars($data['role']); ?>
    </td>

    <td>
        <?php if (($data['status'] ?? '') == 'Aktif') { ?>
            <span class="status aktif">Aktif</span>
        <?php } else { ?>
            <span class="status nonaktif">Nonaktif</span>
        <?php } ?>
    </td>

    <td class="aksi">

        <a href="v_edit_user.php?id=<?= $data['id_users']; ?>"
           class="btn-edit">
            ✏️ Edit
        </a>

        <?php if ($data['id_users'] != $_SESSION['id_users']) { ?>

            <a href="v_hapus_user.php?id=<?= $data['id_users']; ?>"
               class="btn-hapus"
               onclick="return confirm('Yakin ingin menghapus user ini?')">
                🗑️ Hapus
            </a>

        <?php } ?>

        <a href="v_reset_password.php?id=<?= $data['id_users']; ?>"
           class="btn-reset-password">
            🔑 Reset Password
        </a>

    </td>
</tr>
            <?php
            }

            if ($no == 1) {
            ?>

                <tr>
                   <td colspan="6" class="text-center">
                        Data user tidak ditemukan.
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