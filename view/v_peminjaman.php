<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";

$peminjaman = new m_peminjaman($koneksi);

$cari = trim($_GET['cari'] ?? '');

$data_peminjaman = $peminjaman->tampil_data($cari);

if (!$data_peminjaman) {
    die("Query peminjaman gagal: " . mysqli_error($koneksi));
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Peminjaman</title>

    <link rel="stylesheet" href="../asset/peminjaman.css">
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
    <div class="sidebar-title">MENU ADMIN</div>

    <a href="../view/v_dashboard.php">🏠 Dashboard</a>
    <a href="../view/v_pengguna.php">👥 User</a>
    <a href="../view/v_alat.php">🧰 Alat</a>
    <a href="../view/v_kategori.php">📂 Kategori</a>
    <a href="../view/v_peminjaman.php" class="active">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php">🕐 Log Aktivitas</a>
</div>


<!-- CONTENT -->

<div class="content">

    <div class="page-top">

        <div>

            <h1 class="page-title">
                Peminjaman
            </h1>

            <p class="page-subtitle">
                Kelola data peminjaman alat.
            </p>

        </div>

        <a href="../controller/c_peminjaman.php?aksi=tambah"
   class="btn-tambah">
    + Tambah Peminjaman
</a>

    </div>


    <!-- TABLE -->

    <div class="table-box">

        <div class="table-title">
            Data Peminjaman
        </div>

        <!-- PENCARIAN -->
        <form method="GET" action="../view/v_peminjaman.php" class="search-form">
            <input type="text"
                   name="cari"
                   class="search-input"
                   placeholder="Cari nama atau username peminjam..."
                   value="<?= htmlspecialchars($cari); ?>">

            <button type="submit" class="btn-cari">Cari</button>

            <?php if ($cari !== '') { ?>
                <a href="../view/v_peminjaman.php" class="btn-reset">Reset</a>
            <?php } ?>
        </form>

        <?php if ($cari !== '') { ?>
            <p class="search-info">
                Hasil pencarian untuk "<b><?= htmlspecialchars($cari); ?></b>":
                <?= mysqli_num_rows($data_peminjaman); ?> data ditemukan.
            </p>
        <?php } ?>

        <table>

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
                <th>Aksi</th>
            </tr>

           <?php
$no = 1;

while ($data = mysqli_fetch_assoc($data_peminjaman)) {
?>

<tr>
    <td><?= $no++; ?></td>

    <td><?= htmlspecialchars($data['nama'] ?? '-'); ?></td>

    <td><?= htmlspecialchars($data['nama_alat'] ?? '-'); ?></td>

    <td><?= htmlspecialchars($data['nama_kategori'] ?? '-'); ?></td>

    <td><?= htmlspecialchars($data['jumlah'] ?? '0'); ?></td>

    <td>
        <?= !empty($data['tanggal_pinjam'])
            ? date('d-m-Y', strtotime($data['tanggal_pinjam']))
            : '-'; ?>
    </td>

    <td>
        <?= !empty($data['tanggal_kembali'])
            ? date('d-m-Y', strtotime($data['tanggal_kembali']))
            : '-'; ?>
    </td>

    <td>
        <?php
        $status_saat_ini = $data['status'] ?? '-';

        if ($status_saat_ini == 'Menunggu') {
            $kelas_status = 'status-menunggu';
        } elseif ($status_saat_ini == 'Dikembalikan') {
            $kelas_status = 'status-dikembalikan';
        } elseif ($status_saat_ini == 'Ditolak') {
            $kelas_status = 'status-ditolak';
        } else {
            $kelas_status = 'status-dipinjam';
        }
        ?>

        <span class="status <?= $kelas_status; ?>">
            <?= htmlspecialchars($status_saat_ini); ?>
        </span>
    </td>

    <td>
        <?= htmlspecialchars($data['keterangan'] ?? '-'); ?>
    </td>

    <td>
        <div class="aksi">
        <a href="../controller/c_peminjaman.php?aksi=edit&id=<?= $data['id_peminjaman']; ?>"
           class="btn-edit">
            Edit
        </a>

        <a href="../controller/c_peminjaman.php?aksi=hapus&id=<?= $data['id_peminjaman']; ?>"
           class="btn-hapus">
            Hapus
        </a>
        </div>
    </td>
</tr>

<?php
}

if ($no == 1) {
?>

<tr>
    <td colspan="10" class="text-center">
        Data peminjaman tidak ditemukan.
    </td>
</tr>

<?php
}
?>
</table>
    </div>

</div>

</body>
</html>