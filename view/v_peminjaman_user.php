<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['peminjam']);

include_once "../models/m_koneksi.php";
include_once "../models/m_alat.php";

$alat = new M_alat($koneksi);
$data_alat = $alat->tampil_data();

include_once "../models/m_peminjaman.php";
$peminjaman = new M_peminjaman($koneksi);
$data_pengajuan = $peminjaman->tampil_riwayat_user((int) $_SESSION['id_users']);

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
    <title>Ajukan Peminjaman</title>
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
    <a href="../view/v_peminjaman_user.php" class="active">📦 Ajukan Peminjaman</a>
    <a href="../view/v_pengembalian_user.php">↩️ Kembalikan Alat</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Ajukan Peminjaman</h1>
            <p class="page-subtitle">Pilih alat yang ingin kamu pinjam</p>
        </div>
    </div>

    <?php if ($pesan) { ?>
        <div class="pesan <?= $pesan[1]; ?>"><?= $pesan[0]; ?></div>
    <?php } ?>

    <div class="table-box">
        <div class="table-title">Pilih Alat</div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Kondisi</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            while ($data = mysqli_fetch_assoc($data_alat)) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nama_alat']); ?></td>
                    <td><?= htmlspecialchars($data['nama_kategori'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['keterangan'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['jumlah']); ?></td>
                    <td>
                        <?php if ($data['jumlah'] > 0) { ?>
                            <a href="v_form_peminjaman_user.php?id_alat=<?= $data['id_alat']; ?>" class="btn-pinjam">Pinjam</a>
                        <?php } else { ?>
                            <span class="status status-ditolak">Stok habis</span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="table-box table-box-jarak">
        <div class="table-title">Pengajuan Saya</div>

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
            if ($data_pengajuan && mysqli_num_rows($data_pengajuan) > 0) {
                while ($p = mysqli_fetch_assoc($data_pengajuan)) {
                    $status = $p['status'] ?? '-';
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($p['nama_alat'] ?? '-'); ?></td>
                    <td><?= date('d-m-Y', strtotime($p['tanggal_pinjam'])); ?></td>
                    <td><?= date('d-m-Y', strtotime($p['tanggal_kembali'])); ?></td>
                    <td><?= htmlspecialchars($p['jumlah']); ?></td>
                    <td><span class="status <?= kelas_status($status); ?>"><?= htmlspecialchars($status); ?></span></td>
                    <td><?= htmlspecialchars($p['keterangan'] ?? '-'); ?></td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr><td colspan="7" class="kosong">Belum ada pengajuan peminjaman.</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
