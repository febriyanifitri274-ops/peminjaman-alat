<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['peminjam']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";

$peminjaman = new M_peminjaman($koneksi);
$id_users = (int) $_SESSION['id_users'];

// yang bisa dikembalikan = Dipinjam, yang sudah diajukan = Menunggu Pengembalian
$data_dipinjam = $peminjaman->tampil_user_by_status($id_users, 'Dipinjam');
$data_menunggu = $peminjaman->tampil_user_by_status($id_users, 'Menunggu Pengembalian');

$pesan_map = [
    'diajukan' => ['Pengembalian diajukan, tunggu persetujuan petugas.', ''],
    'gagal'    => ['Pengajuan pengembalian gagal. Data tidak ditemukan atau statusnya sudah berubah.', 'error'],
];
$pesan = $pesan_map[$_GET['pesan'] ?? ''] ?? null;

$label_role = 'Peminjam';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kembalikan Alat</title>
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
    <a href="../view/v_pengembalian_user.php" class="active">↩️ Kembalikan Alat</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Kembalikan Alat</h1>
            <p class="page-subtitle">Alat yang sedang kamu pinjam. Klik tombol untuk mengembalikannya.</p>
        </div>
    </div>

    <?php if ($pesan) { ?>
        <div class="pesan <?= $pesan[1]; ?>"><?= $pesan[0]; ?></div>
    <?php } ?>

    <div class="table-box">
        <div class="table-title">Sedang Dipinjam</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            if ($data_dipinjam && mysqli_num_rows($data_dipinjam) > 0) {
                while ($data = mysqli_fetch_assoc($data_dipinjam)) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nama_alat'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['jumlah']); ?></td>
                    <td><?= date('d-m-Y', strtotime($data['tanggal_pinjam'])); ?></td>
                    <td><?= date('d-m-Y', strtotime($data['tanggal_kembali'])); ?></td>
                    <td>
                        <form method="POST" action="../controller/c_user.php?aksi=ajukan_kembali" class="form-inline"
                              onsubmit="return confirm('Ajukan pengembalian alat ini?');">
                            <input type="hidden" name="id_peminjaman" value="<?= $data['id_peminjaman']; ?>">
                            <button type="submit" class="btn-pinjam">Ajukan Pengembalian</button>
                        </form>
                    </td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr><td colspan="6" class="kosong">Tidak ada alat yang sedang dipinjam.</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="table-box table-box-jarak">
        <div class="table-title">Menunggu Persetujuan Pengembalian</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            if ($data_menunggu && mysqli_num_rows($data_menunggu) > 0) {
                while ($data = mysqli_fetch_assoc($data_menunggu)) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nama_alat'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['jumlah']); ?></td>
                    <td><?= date('d-m-Y', strtotime($data['tanggal_pinjam'])); ?></td>
                    <td><span class="status status-menunggu-pengembalian">Menunggu Pengembalian</span></td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr><td colspan="5" class="kosong">Belum ada pengajuan pengembalian.</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
