<?php

require_once __DIR__ . '/auth.php';
wajib_role(['petugas']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";
include_once "../models/m_alat.php";

$peminjaman = new M_peminjaman($koneksi);
$alat = new M_alat($koneksi);

$aksi = $_GET['aksi'] ?? '';

// semua aksi petugas mengubah data -> wajib POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ../view/v_petugas.php");
    exit;
}

$id = (int) ($_POST['id_peminjaman'] ?? 0);
$data = $peminjaman->tampil_by_id($id);

if (!$data) {
    die("Data peminjaman tidak ditemukan.");
}

/* SETUJUI PEMINJAMAN: Menunggu -> Dipinjam, stok berkurang */
if ($aksi == 'setujui_pinjam') {

    if ($data['status'] == 'Menunggu') {

        if (!$alat->kurangi_stok($data['id_alat'], $data['jumlah'])) {
            header("Location: ../view/v_setujui_peminjaman.php?pesan=stok");
            exit;
        }

        $peminjaman->ubah_status($id, 'Dipinjam');
        catat_log($koneksi, "Menyetujui peminjaman #$id");
    }

    header("Location: ../view/v_setujui_peminjaman.php?pesan=setuju");
    exit;
}

/* TOLAK PEMINJAMAN: Menunggu -> Ditolak */
if ($aksi == 'tolak_pinjam') {

    if ($data['status'] == 'Menunggu') {
        $peminjaman->ubah_status($id, 'Ditolak');
        catat_log($koneksi, "Menolak peminjaman #$id");
    }

    header("Location: ../view/v_setujui_peminjaman.php?pesan=tolak");
    exit;
}

header("Location: ../view/v_petugas.php");
exit;
