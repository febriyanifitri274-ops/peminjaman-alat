<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['petugas']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";

$peminjaman = new M_peminjaman($koneksi);

$dari = trim($_GET['dari'] ?? '');
$sampai = trim($_GET['sampai'] ?? '');
$status = trim($_GET['status'] ?? '');

$data_laporan = $peminjaman->tampil_laporan($dari, $sampai, $status);

// PAGINATION (15 data per halaman di layar; saat dicetak semua data tetap ikut)
$per_halaman   = 15;
$total_data    = $data_laporan ? mysqli_num_rows($data_laporan) : 0;
$total_halaman = max(1, (int) ceil($total_data / $per_halaman));

$halaman = (int) ($_GET['hal'] ?? 1);
if ($halaman < 1) {
    $halaman = 1;
}
if ($halaman > $total_halaman) {
    $halaman = $total_halaman;
}

$offset = ($halaman - 1) * $per_halaman;

// link halaman membawa filter yang sedang aktif
$query_filter = http_build_query(array_filter([
    'dari'   => $dari,
    'sampai' => $sampai,
    'status' => $status,
], fn($v) => $v !== ''));
$link_hal = fn($h) => '?' . ($query_filter !== '' ? $query_filter . '&' : '') . 'hal=' . $h;

$daftar_status = ['Menunggu', 'Dipinjam', 'Menunggu Pengembalian', 'Dikembalikan', 'Ditolak'];

$label_role = 'Petugas';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan</title>
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
    <div class="sidebar-title">MENU PETUGAS</div>

    <a href="../view/v_petugas.php">🏠 Dashboard</a>
    <a href="../view/v_setujui_peminjaman.php">✅ Setujui Peminjaman</a>
    <a href="../view/v_pantau_pengembalian.php">📥 Pantau Pengembalian</a>
    <a href="../view/v_laporan.php" class="active">🖨️ Cetak Laporan</a>
</div>

<div class="content">

    <div class="page-top">
        <div>
            <h1 class="page-title">Laporan Peminjaman</h1>
            <p class="page-subtitle">Laporan peminjaman &amp; pengembalian alat.
                <span class="print-only">Dicetak: <?= date('d-m-Y H:i'); ?> oleh <?= htmlspecialchars($_SESSION['nama'] ?? ''); ?></span>
            </p>
        </div>

        <button type="button" onclick="window.print()" class="btn-cetak">🖨️ Cetak Laporan</button>
    </div>

    <div class="table-box">
        <div class="table-title">Data Laporan</div>

        <form method="GET" action="v_laporan.php" class="filter-laporan">
            <div class="filter-item">
                <label for="dari">Dari Tanggal</label>
                <input type="date" id="dari" name="dari" value="<?= htmlspecialchars($dari); ?>">
            </div>

            <div class="filter-item">
                <label for="sampai">Sampai Tanggal</label>
                <input type="date" id="sampai" name="sampai" value="<?= htmlspecialchars($sampai); ?>">
            </div>

            <div class="filter-item">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">Semua Status</option>
                    <?php foreach ($daftar_status as $s) { ?>
                        <option value="<?= $s; ?>" <?= $status == $s ? 'selected' : ''; ?>><?= $s; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="filter-aksi">
                <button type="submit" class="btn-filter">Tampilkan</button>
                <?php if ($dari !== '' || $sampai !== '' || $status !== '') { ?>
                    <a href="v_laporan.php" class="btn-filter-reset">Reset</a>
                <?php } ?>
            </div>
        </form>

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
            if ($data_laporan && mysqli_num_rows($data_laporan) > 0) {
                while ($data = mysqli_fetch_assoc($data_laporan)) {
                    $st = $data['status'] ?? '-';
                    $di_halaman_ini = ($no > $offset && $no <= $offset + $per_halaman);
            ?>
                <tr<?= $di_halaman_ini ? '' : ' class="baris-sembunyi"'; ?>>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($data['nama'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['nama_alat'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['nama_kategori'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($data['jumlah'] ?? '0'); ?></td>
                    <td><?= !empty($data['tanggal_pinjam']) ? date('d-m-Y', strtotime($data['tanggal_pinjam'])) : '-'; ?></td>
                    <td><?= !empty($data['tanggal_kembali']) ? date('d-m-Y', strtotime($data['tanggal_kembali'])) : '-'; ?></td>
                    <td><span class="status <?= kelas_status($st); ?>"><?= htmlspecialchars($st); ?></span></td>
                    <td><?= htmlspecialchars($data['keterangan'] ?? '-'); ?></td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr><td colspan="9" class="kosong">Tidak ada data untuk filter ini.</td></tr>
            <?php } ?>
            </tbody>
        </table>

        <!-- PAGINATION -->
        <?php if ($total_halaman > 1) {
            $dari_no = $offset + 1;
            $sampai_no = min($offset + $per_halaman, $total_data);
            $awal  = max(1, $halaman - 2);
            $akhir = min($total_halaman, $halaman + 2);
        ?>
        <div class="pagination-wrap">

            <div class="pagination-info">
                Menampilkan <?= $dari_no; ?>–<?= $sampai_no; ?> dari <?= $total_data; ?> data
            </div>

            <div class="pagination">

                <?php if ($halaman > 1) { ?>
                    <a href="<?= $link_hal($halaman - 1); ?>">&laquo; Sebelumnya</a>
                <?php } else { ?>
                    <span class="disabled">&laquo; Sebelumnya</span>
                <?php } ?>

                <?php if ($awal > 1) { ?>
                    <a href="<?= $link_hal(1); ?>">1</a>
                    <?php if ($awal > 2) { ?><span class="dots">…</span><?php } ?>
                <?php } ?>

                <?php for ($i = $awal; $i <= $akhir; $i++) { ?>
                    <?php if ($i == $halaman) { ?>
                        <span class="active"><?= $i; ?></span>
                    <?php } else { ?>
                        <a href="<?= $link_hal($i); ?>"><?= $i; ?></a>
                    <?php } ?>
                <?php } ?>

                <?php if ($akhir < $total_halaman) { ?>
                    <?php if ($akhir < $total_halaman - 1) { ?><span class="dots">…</span><?php } ?>
                    <a href="<?= $link_hal($total_halaman); ?>"><?= $total_halaman; ?></a>
                <?php } ?>

                <?php if ($halaman < $total_halaman) { ?>
                    <a href="<?= $link_hal($halaman + 1); ?>">Berikutnya &raquo;</a>
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
