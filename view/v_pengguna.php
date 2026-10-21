<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_pengguna.php";

$pengguna = new M_pengguna($koneksi);
$data_pengguna = $pengguna->tampil_data();

// PENCARIAN (nama atau username)
$cari = trim($_GET['cari'] ?? '');
$hasil_pengguna = [];

while ($row = mysqli_fetch_assoc($data_pengguna)) {
    if (
        $cari === '' ||
        stripos($row['nama'], $cari) !== false ||
        stripos($row['username'], $cari) !== false
    ) {
        $hasil_pengguna[] = $row;
    }
}

$pesan_map = [
    'tambah'      => ['User berhasil ditambahkan.', ''],
    'hapus'       => ['User berhasil dihapus beserta data peminjaman dan log aktivitasnya.', ''],
    'diri'        => ['Kamu tidak bisa menghapus akun yang sedang dipakai.', 'error'],
    'gagal_hapus' => ['User gagal dihapus. Silakan coba lagi.', 'error'],
    'tidak_ada'   => ['User tidak ditemukan.', 'error'],
];
$pesan = $pesan_map[$_GET['pesan'] ?? ''] ?? null;

// hasil reset password (berisi password baru, hanya tampil sekali)
if (($_GET['pesan'] ?? '') == 'reset' && isset($_SESSION['flash_reset'])) {
    $pesan = [htmlspecialchars($_SESSION['flash_reset']), ''];
    unset($_SESSION['flash_reset']);
}

$label_role = 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User</title>
    <link rel="stylesheet" href="../asset/peminjaman.css">
    <link rel="stylesheet" href="../asset/pengguna.css">
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
    <div class="sidebar-title">MENU ADMIN</div>

    <a href="../view/v_dashboard.php">🏠 Dashboard</a>
    <a href="../view/v_pengguna.php" class="active">👥 User</a>
    <a href="../view/v_alat.php">🧰 Alat</a>
    <a href="../view/v_kategori.php">📂 Kategori</a>
    <a href="../view/v_peminjaman.php">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php">🕐 Log Aktivitas</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">User</h1>
            <p class="page-subtitle">Kelola akun admin, petugas, dan peminjam.</p>
        </div>

        <a href="v_tambah_pengguna.php" class="btn-tambah">+ Tambah User</a>
    </div>

    <?php if ($pesan) { ?>
        <div class="pesan <?= $pesan[1]; ?>"><?= $pesan[0]; ?></div>
    <?php } ?>

    <div class="table-box">
        <div class="table-title">Data User</div>

        <!-- PENCARIAN -->
        <form method="GET" action="v_pengguna.php" class="search-form">
            <input type="text"
                   name="cari"
                   class="search-input"
                   placeholder="Cari nama atau username..."
                   value="<?= htmlspecialchars($cari); ?>">

            <button type="submit" class="btn-cari">Cari</button>

            <?php if ($cari !== '') { ?>
                <a href="v_pengguna.php" class="btn-reset">Reset</a>
            <?php } ?>
        </form>

        <?php if ($cari !== '') { ?>
            <p class="search-info">
                Hasil pencarian untuk "<b><?= htmlspecialchars($cari); ?></b>":
                <?= count($hasil_pengguna); ?> data ditemukan.
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
            foreach ($hasil_pengguna as $data) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nama']); ?></td>
                    <td><?= htmlspecialchars($data['username']); ?></td>
                    <td><?= htmlspecialchars(ucfirst($data['role'])); ?></td>
                    <?php $status_user = !empty($data['status']) ? $data['status'] : 'Aktif'; ?>
                    <td>
                        <span class="status status-<?= strtolower($status_user); ?>"><?= htmlspecialchars($status_user); ?></span>
                    </td>
                    <td>
                        <a href="v_edit_pengguna.php?id=<?= $data['id_users']; ?>" class="btn-edit">Edit</a>

                        <?php if ($data['id_users'] != $_SESSION['id_users']) { ?>
                       <form method="POST"
                              action="../controller/c_pengguna.php?aksi=hapus"
                              class="form-inline"
                              onsubmit="return confirm('Hapus user ini?');">
                            <input type="hidden" name="id_users" value="<?= $data['id_users']; ?>">
                            <button type="submit" class="btn-tolak">Hapus</button>
                        </form>
                        <?php } ?>

                        <form method="POST"
                              action="../controller/c_pengguna.php?aksi=reset"
                              class="form-inline"
                              onsubmit="return confirm('Reset password user ini? Password lama tidak berlaku lagi.');">
                            <input type="hidden" name="id_users" value="<?= $data['id_users']; ?>">
                            <button type="submit" class="btn-reset-pw">Reset Password</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>

            <?php if (count($hasil_pengguna) == 0) { ?>
                <tr>
                    <td colspan="6" class="text-center">
                        Data user tidak ditemukan.
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
