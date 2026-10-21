<?php

// CRUD data pengembalian (khusus admin)
require_once __DIR__ . '/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";
include_once "../models/m_alat.php";

$peminjaman = new M_peminjaman($koneksi);
$alat = new M_alat($koneksi);

$aksi = $_GET['aksi'] ?? 'tampil';
$post = $_SERVER['REQUEST_METHOD'] == 'POST';

function ke_daftar($pesan = '')
{
    header("Location: c_pengembalian.php?aksi=tampil" . ($pesan ? "&pesan=$pesan" : ""));
    exit;
}

/* TERIMA / CATAT PENGEMBALIAN: -> Dikembalikan, stok bertambah */
function proses_kembali($peminjaman, $alat, $koneksi, $id)
{
    $data = $peminjaman->tampil_by_id($id);

    if ($data && in_array($data['status'], ['Dipinjam', 'Menunggu Pengembalian'])) {
        $alat->tambah_stok($data['id_alat'], $data['jumlah']);
        $peminjaman->ubah_status($id, 'Dikembalikan');
        catat_log($koneksi, "Mencatat pengembalian #$id");
        return true;
    }

    return false;
}

/* ===== TAMBAH (catat pengembalian langsung) ===== */
if ($aksi == 'tambah') {

    if ($post) {
        $ok = proses_kembali($peminjaman, $alat, $koneksi, (int) ($_POST['id_peminjaman'] ?? 0));
        ke_daftar($ok ? 'dicatat' : 'gagal');
    }

    $data_belum = $peminjaman->tampil_belum_kembali();
    include "../view/v_tambah_pengembalian.php";
    exit;
}

/* ===== TERIMA ===== */
if ($aksi == 'terima' && $post) {
    $ok = proses_kembali($peminjaman, $alat, $koneksi, (int) ($_POST['id_peminjaman'] ?? 0));
    ke_daftar($ok ? 'dicatat' : 'gagal');
}

/* ===== EDIT ===== */
if ($aksi == 'edit') {

    $status_boleh = ['Dipinjam', 'Menunggu Pengembalian', 'Dikembalikan'];

    if ($post) {
        $id = (int) ($_POST['id_peminjaman'] ?? 0);
        $baru = $_POST['status'] ?? '';
        $keterangan = $_POST['keterangan'] ?? '';
        $data = $peminjaman->tampil_by_id($id);

        if (!$data || !in_array($baru, $status_boleh)) {
            ke_daftar('gagal');
        }

        // jaga stok tetap sinkron dengan status
        if ($data['status'] != 'Dikembalikan' && $baru == 'Dikembalikan') {
            $alat->tambah_stok($data['id_alat'], $data['jumlah']);
        } elseif ($data['status'] == 'Dikembalikan' && $baru != 'Dikembalikan') {
            if (!$alat->kurangi_stok($data['id_alat'], $data['jumlah'])) {
                ke_daftar('stok');
            }
        }

        $peminjaman->ubah_pengembalian($id, $baru, $keterangan);
        catat_log($koneksi, "Mengubah data pengembalian #$id");
        ke_daftar('diubah');
    }

    $id = (int) ($_GET['id'] ?? 0);
    $data = $peminjaman->tampil_by_id($id);

    if (!$data) {
        die("Data pengembalian tidak ditemukan.");
    }

    include "../view/v_edit_pengembalian.php";
    exit;
}

/* ===== HAPUS ===== */
if ($aksi == 'hapus' && $post) {
    $id = (int) ($_POST['id_peminjaman'] ?? 0);
    $data = $peminjaman->tampil_by_id($id);

    if ($data) {
        // alat yang belum selesai dikembalikan: stoknya dikembalikan dulu
        if (in_array($data['status'], ['Dipinjam', 'Menunggu Pengembalian'])) {
            $alat->tambah_stok($data['id_alat'], $data['jumlah']);
        }
        $peminjaman->hapus_data($id);
        catat_log($koneksi, "Menghapus data pengembalian #$id");
    }

    ke_daftar('dihapus');
}

/* ===== TAMPIL ===== */
$pesan_map = [
    'dicatat' => ['Pengembalian berhasil dicatat, stok alat bertambah.', ''],
    'diubah'  => ['Data pengembalian berhasil diubah.', ''],
    'dihapus' => ['Data pengembalian berhasil dihapus.', ''],
    'stok'    => ['Stok alat tidak mencukupi untuk membatalkan pengembalian ini.', 'error'],
    'gagal'   => ['Aksi gagal. Data tidak ditemukan atau statusnya sudah berubah.', 'error'],
];
$pesan = $pesan_map[$_GET['pesan'] ?? ''] ?? null;

$data_pengembalian = $peminjaman->tampil_pengembalian();

include "../view/v_pengembalian.php";
