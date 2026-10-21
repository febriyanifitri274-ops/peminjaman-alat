<?php

include_once "../models/m_koneksi.php";
include_once "../models/m_alat.php";

$alat = new M_alat($koneksi);

$id_alat = $_GET['id_alat'] ?? '';

$data_alat = $alat->tampil_data_by_id($id_alat);
$alat_data = mysqli_fetch_assoc($data_alat);

if (!$alat_data) {
    die("Data alat tidak ditemukan.");
}

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

    <div class="logo">
        🌸 Peminjaman Alat
    </div>

    <div class="navbar-right">
        <span>🔔</span>
        <span class="admin">Petugas</span>
    </div>

</div>


<div class="sidebar">

    <div class="sidebar-title">
        MENU UTAMA
    </div>

    <a href="v_petugas.php">
        🏠 Dashboard
    </a>

    <a href="v_form_peminjaman_petugas.php" class="active">
        📦 Peminjaman Alat
    </a>

    <a href="../controller/c_petugas.php?aksi=status">
            📋 Status Peminjaman
        </a>

    <a href="../controller/c_petugas.php?aksi=riwayat">
    🕘 Riwayat Peminjaman
</a>
</div>


<div class="content">

    <div class="page-top">

        <div>

            <h1 class="page-title">
                Form Peminjaman
            </h1>

            <p class="page-subtitle">
                Isi data peminjaman alat.
            </p>

        </div>

    </div>


    <div class="form-box">

        <form action="../controller/c_petugas.php?aksi=pinjam"
              method="POST">

            <!-- ID ALAT -->
            <input
                type="hidden"
                name="id_alat"
                value="<?= $alat_data['id_alat']; ?>"
            >


            <div class="form-group">

                <label>Nama Alat</label>

                <input
                    type="text"
                    value="<?= htmlspecialchars($alat_data['nama_alat']); ?>"
                    readonly
                >

            </div>


            <div class="form-group">

                <label>Jumlah</label>

                <input
                    type="number"
                    name="jumlah"
                    min="1"
                    max="<?= $alat_data['jumlah']; ?>"
                    required
                >

            </div>


            <div class="form-group">

                <label>Tanggal Pinjam</label>

                <input
                    type="date"
                    name="tanggal_pinjam"
                    required
                >

            </div>


            <div class="form-group">

                <label>Tanggal Kembali</label>

                <input
                    type="date"
                    name="tanggal_kembali"
                    required
                >

            </div>


            <div class="form-group">

                <label>Keterangan</label>

                <textarea
                    name="keterangan"
                    rows="4"
                    placeholder="Masukkan keterangan peminjaman..."
                ></textarea>

            </div>


            <!-- SEMENTARA -->
            <div class="form-action">

                <a href="v_peminjaman_petugas.php"
                   class="btn-edit">
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-simpan">
                    Ajukan Peminjaman
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>