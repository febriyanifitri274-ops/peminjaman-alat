
<?php

require_once __DIR__ . '/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_peminjaman.php";
include_once "../models/m_alat.php";

$peminjaman = new M_peminjaman($koneksi);
$alat = new M_alat($koneksi);

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'tampil';


/* =========================
   EDIT DATA
========================= */

if ($aksi == 'edit') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_POST['id_peminjaman'] ?? '';
        $id_users = $_POST['id_users'] ?? '';
        $id_alat = $_POST['id_alat'] ?? '';
        $id_kategori = $_POST['id_kategori'] ?? '';
        $tanggal_pinjam = $_POST['tanggal_pinjam'] ?? '';
        $tanggal_kembali = $_POST['tanggal_kembali'] ?? '';
        $jumlah = $_POST['jumlah'] ?? '';
        $status = $_POST['status'] ?? '';
        $keterangan = $_POST['keterangan'] ?? '';

        $hasil = $peminjaman->ubah_data(
            $id,
            $id_users,
            $id_alat,
            $id_kategori,
            $tanggal_pinjam,
            $tanggal_kembali,
            $jumlah,
            $status,
            $keterangan
        );

        if (!$hasil) {
            die("Simpan perubahan gagal: " . mysqli_error($koneksi));
        }

        header("Location: c_peminjaman.php?aksi=tampil");
        exit;
    }

    $id = $_GET['id'] ?? '';

    $data = $peminjaman->tampil_by_id($id);

    if (!$data) {
        die("Data peminjaman tidak ditemukan.");
    }

    $data_users = $peminjaman->tampil_users();
    $data_alat = $peminjaman->tampil_alat();
    $data_kategori = $peminjaman->tampil_kategori();

    include "../view/v_edit_peminjaman.php";

    exit;
}


/* =========================
   TAMPIL DATA
========================= */

if ($aksi == 'tampil') {

    $cari = trim($_GET['cari'] ?? '');

    $data_peminjaman = $peminjaman->tampil_data($cari);

    if (!$data_peminjaman) {
        die("Query peminjaman gagal: " . mysqli_error($koneksi));
    }

    include "../view/v_peminjaman.php";
}


/* =========================
   TAMBAH DATA
========================= */
elseif ($aksi == 'tambah') {

    $data_users = $peminjaman->tampil_users();
    $data_alat = $peminjaman->tampil_alat();
    $data_kategori = $peminjaman->tampil_kategori();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id_users = $_POST['id_users'] ?? '';
        $id_alat = $_POST['id_alat'] ?? '';
        $tanggal_pinjam = $_POST['tanggal_pinjam'] ?? '';
        $tanggal_kembali = $_POST['tanggal_kembali'] ?? '';
        $jumlah = $_POST['jumlah'] ?? '';
        $status = $_POST['status'] ?? '';
        $keterangan = $_POST['keterangan'] ?? '';

        $query_kategori = mysqli_query(
            $koneksi,
            "SELECT id_kategori FROM alat
             WHERE id_alat = '$id_alat'"
        );

        $data_kategori_alat = mysqli_fetch_assoc($query_kategori);

        if (!$data_kategori_alat) {
            die("Kategori alat tidak ditemukan.");
        }

        $id_kategori = $data_kategori_alat['id_kategori'];

        $hasil = $peminjaman->tambah_data(
            $id_users,
            $id_alat,
            $id_kategori,
            $tanggal_pinjam,
            $tanggal_kembali,
            $jumlah,
            $status,
            $keterangan
        );

        if (!$hasil) {
            die("Tambah data gagal: " . mysqli_error($koneksi));
        }

        header("Location: c_peminjaman.php?aksi=tampil");
        exit;
    }

    include "../view/v_tambah_peminjaman.php";
}

/* =========================
   HAPUS DATA
========================= */

elseif ($aksi == 'hapus') {

    $id = $_GET['id'] ?? '';

    $hasil = $peminjaman->hapus_data($id);

    if (!$hasil) {
        die("Hapus data gagal: " . mysqli_error($koneksi));
    }

    header("Location: c_peminjaman.php");
    exit;
}

?>