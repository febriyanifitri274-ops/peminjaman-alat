<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tambah Alat</title>

    <link rel="stylesheet" href="../asset/alat.css">
</head>
<body>

<!-- NAVBAR -->

<div class="navbar">

    <div class="logo">
        🌸 Peminjaman Alat
    </div>

    <div class="navbar-right">
        <span>🔔</span>
        <span class="admin">Admin</span>
    </div>

</div>


<!-- SIDEBAR -->

<div class="sidebar">
    <div class="sidebar-title">MENU ADMIN</div>

    <a href="../view/v_dashboard.php">🏠 Dashboard</a>
    <a href="../view/v_pengguna.php">👥 User</a>
    <a href="../view/v_alat.php" class="active">🧰 Alat</a>
    <a href="../view/v_kategori.php">📂 Kategori</a>
    <a href="../view/v_peminjaman.php">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php">🕐 Log Aktivitas</a>
</div>


<!-- CONTENT -->

<div class="content">

    <h1 class="page-title">
        Tambah Alat
    </h1>

    <p class="page-subtitle">
        Tambahkan data alat baru.
    </p>


    <div class="form-box">

      <form method="POST"
      action="../controller/c_alat.php?aksi=tambah"
      enctype="multipart/form-data">

            <div class="form-group">

                <label>Kategori</label>

                <select name="id_kategori" required>

                    <option value="">
                        -- Pilih Kategori --
                    </option>

                    <?php while ($kategori = mysqli_fetch_assoc($data_kategori)) { ?>

                        <option value="<?= $kategori['id_kategori']; ?>">

                            <?= $kategori['nama_kategori']; ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <div class="form-group">

                <label>Nama Alat</label>

                <input
                    type="text"
                    name="nama_alat"
                    placeholder="Masukkan nama alat"
                    required
                >

            </div>


            <div class="form-group">

                <label>Jumlah</label>

                <input
                    type="number"
                    name="jumlah"
                    min="0"
                    placeholder="Masukkan jumlah alat"
                    required
                >

            </div>


            <div class="form-group">

                <label>Harga</label>

                <input
                    type="number"
                    name="harga"
                    min="0"
                    placeholder="Masukkan harga"
                    required
                >

            </div>


            <div class="form-group">

                <label>Keterangan</label>

                <textarea
                    name="keterangan"
                    placeholder="Masukkan keterangan alat"
                ></textarea>

            </div>

<div class="form-group">

    <label>Foto Alat</label>

    <div class="foto-upload">

        <div class="foto-preview">
            <div class="foto-placeholder">
                <span>🖼️</span>
                <p>Preview Foto</p>
            </div>
        </div>

        <div class="foto-input">
            <input type="file" name="foto" accept="image/png, image/jpeg">
            <small>Format gambar: JPG, PNG, JPEG. Maksimal 2MB.</small>
        </div>

    </div>

</div>


            <div class="form-buttons">

             <button type="submit" name="simpan" class="btn-simpan">Simpan</button>

                <a
                    href="../view/v_alat.php"
                    class="btn-batal">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>