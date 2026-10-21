<?php

require_once __DIR__ . '/auth.php';
wajib_role(['peminjam']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";
include_once "../models/m_alat.php";

$peminjaman = new M_peminjaman($koneksi);
$alat = new M_alat($koneksi);

$aksi = $_GET['aksi'] ?? '';
$id_users = (int) $_SESSION['id_users'];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ../view/v_user.php");
    exit;
}

/* AJUKAN PEMINJAMAN */
if ($aksi == 'pinjam') {

    $id_alat = (int) ($_POST['id_alat'] ?? 0);
    $tanggal_pinjam = $_POST['tanggal_pinjam'] ?? '';
    $tanggal_kembali = $_POST['tanggal_kembali'] ?? '';
    $jumlah = (int) ($_POST['jumlah'] ?? 0);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan'] ?? '');

    $data_alat = mysqli_fetch_assoc($alat->tampil_data_by_id($id_alat));

    if (!$data_alat) {
        die("Data alat tidak ditemukan.");
    }

    if ($jumlah < 1 || $jumlah > (int) $data_alat['jumlah']) {
        header("Location: ../view/v_form_peminjaman_user.php?id_alat=$id_alat&pesan=jumlah");
        exit;
    }

    if ($tanggal_kembali < $tanggal_pinjam) {
        header("Location: ../view/v_form_peminjaman_user.php?id_alat=$id_alat&pesan=tanggal");
        exit;
    }

    $tanggal_pinjam = mysqli_real_escape_string($koneksi, $tanggal_pinjam);
    $tanggal_kembali = mysqli_real_escape_string($koneksi, $tanggal_kembali);

    $hasil = $peminjaman->tambah_data(
        $id_users,
        $id_alat,
        $data_alat['id_kategori'],
        $tanggal_pinjam,
        $tanggal_kembali,
        $jumlah,
        'Menunggu',
        $keterangan
    );

    if (!$hasil) {
        die("Pengajuan peminjaman gagal: " . mysqli_error($koneksi));
    }

    catat_log($koneksi, "Mengajukan peminjaman " . $data_alat['nama_alat'] . " ($jumlah)");

    header("Location: ../view/v_peminjaman_user.php?pesan=diajukan");
    exit;
}

/* AJUKAN PENGEMBALIAN */
if ($aksi == 'ajukan_kembali') {

    $id = (int) ($_POST['id_peminjaman'] ?? 0);

    if ($peminjaman->ajukan_pengembalian($id, $id_users)) {
        catat_log($koneksi, "Mengajukan pengembalian #$id");
        header("Location: ../view/v_pengembalian_user.php?pesan=diajukan");
    } else {
        header("Location: ../view/v_pengembalian_user.php?pesan=gagal");
    }
    exit;
}

header("Location: ../view/v_user.php");
exit;
