<?php

require_once __DIR__ . '/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_alat.php";

$alat = new M_alat($koneksi);

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'tampil';

function upload_foto($file, $foto_lama = "")
{
    // tidak ada file baru -> pakai foto lama (atau kosong)
    if (empty($file['name'])) {
        return $foto_lama;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ['jpg', 'jpeg', 'png']) || $file['size'] > 2 * 1024 * 1024) {
        return false;
    }

    $nama_baru = time() . "_" . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file['name']));

    if (!move_uploaded_file($file['tmp_name'], __DIR__ . "/../asset/foto_alat/" . $nama_baru)) {
        return false;
    }

    return $nama_baru;
}

function gagal_upload($kembali)
{
    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Gagal Upload Foto</title>
        <link rel="stylesheet" href="../asset/alat.css">
    </head>
    <body>
        <div class="content content-error">
            <div class="form-card">
                <h2 class="judul-error">Foto gagal diunggah</h2>
                <p>Foto harus berformat JPG, JPEG, atau PNG dan maksimal 2MB.</p>
                <div class="form-buttons">
                    <a href="' . $kembali . '" class="btn-batal">Kembali</a>
                </div>
            </div>
        </div>
    </body>
    </html>';
    exit;
}

/* =========================
   TAMPIL EDIT ALAT
========================= */

if ($aksi == 'edit') {

    $id = $_GET['id'];

    $query = $alat->tampil_data_by_id($id);
    $data = mysqli_fetch_assoc($query);

    $data_kategori = $alat->tampil_kategori();

    include "../view/v_edit_alat.php";

    exit;
}


/* =========================
   TAMBAH ALAT
========================= */

if ($aksi == 'tambah') {

    /* Simpan data */
    if (isset($_POST['simpan'])) {

        $id_kategori = $_POST['id_kategori'];
        $nama_alat = $_POST['nama_alat'];
        $jumlah = $_POST['jumlah'];
        $harga = $_POST['harga'];
        $keterangan = $_POST['keterangan'];

        $foto = upload_foto($_FILES['foto']);

        if ($foto === false) {
            gagal_upload("c_alat.php?aksi=tambah");
        }

        $alat->tambah_data(
            $id_kategori,
            $nama_alat,
            $jumlah,
            $harga,
            $keterangan,
            $foto
        );

        header("Location: ../view/v_alat.php");
        exit;
    }

    /* Tampil form tambah */
    $data_kategori = $alat->tampil_kategori();

    include "../view/v_tambah_alat.php";

    exit;
}


/* =========================
   SIMPAN EDIT ALAT
========================= */

if ($aksi == 'simpan_edit') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_POST['id_alat'];
        $id_kategori = $_POST['id_kategori'];
        $nama_alat = $_POST['nama_alat'];
        $jumlah = $_POST['jumlah'];
        $harga = $_POST['harga'];
        $keterangan = $_POST['keterangan'];

        /* Ambil data lama */
        $query = $alat->tampil_data_by_id($id);
        $data_lama = mysqli_fetch_assoc($query);

        $foto = upload_foto($_FILES['foto'], $data_lama['foto']);

        if ($foto === false) {
            gagal_upload("c_alat.php?aksi=edit&id=" . urlencode($id));
        }

        $alat->ubah_data(
            $id,
            $id_kategori,
            $nama_alat,
            $jumlah,
            $harga,
            $keterangan,
            $foto
        );

        header("Location: ../view/v_alat.php");
        exit;
    }
}


/* =========================
   HAPUS ALAT
========================= */

if ($aksi == 'hapus') {

    $id = (int) ($_GET['id'] ?? 0);

    try {
        $alat->hapus_data($id);
    } catch (Throwable $e) {
        // alat masih dipakai di data peminjaman -> tidak bisa dihapus
    }

    header("Location: ../view/v_alat.php");
    exit;
}


/* Aksi tidak dikenal / bukan POST -> kembali ke daftar alat (bukan halaman kosong) */
header("Location: ../view/v_alat.php");
exit;

?>