<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['peminjam']);

include_once "../models/m_koneksi.php";
include_once "../models/m_alat.php";

$alat = new M_alat($koneksi);

$id_alat = (int) ($_GET['id_alat'] ?? 0);
$alat_data = mysqli_fetch_assoc($alat->tampil_data_by_id($id_alat));

if (!$alat_data) {
    die("Data alat tidak ditemukan.");
}

$pesan_map = [
    'jumlah'  => 'Jumlah harus minimal 1 dan tidak boleh melebihi stok.',
    'tanggal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
];
$pesan = $pesan_map[$_GET['pesan'] ?? ''] ?? null;

$label_role = 'Peminjam';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman</title>
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
            <h1 class="page-title">Form Peminjaman</h1>
            <p class="page-subtitle">Isi data peminjaman alat.</p>
        </div>
    </div>

    <div class="form-box">

        <?php if ($pesan) { ?>
            <div class="pesan error"><?= $pesan; ?></div>
        <?php } ?>

        <form action="../controller/c_user.php?aksi=pinjam" method="POST">

            <input type="hidden" name="id_alat" value="<?= $alat_data['id_alat']; ?>">

            <div class="form-group">
                <label>Nama Alat</label>
                <input type="text" value="<?= htmlspecialchars($alat_data['nama_alat']); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Stok Tersedia</label>
                <input type="text" value="<?= (int) $alat_data['jumlah']; ?>" readonly>
            </div>

            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" min="1" max="<?= (int) $alat_data['jumlah']; ?>" required>
            </div>

            <div class="form-group">
                <label>Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" min="<?= date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" min="<?= date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="4" placeholder="Masukkan keterangan peminjaman..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                <a href="v_peminjaman_user.php" class="btn-batal">Batal</a>
            </div>

        </form>
    </div>

</div>

</body>
</html>
