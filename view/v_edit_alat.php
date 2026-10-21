<?php
require_once __DIR__ . '/../controller/auth.php';
wajib_role(['admin']);
?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Alat</title>

    <link rel="stylesheet" href="../asset/alat.css">


</head>


<body>


<!-- NAVBAR -->

<div class="navbar">

    <div class="logo">
        🌸 PEMINJAMAN ALAT
    </div>

    <div class="navbar-right">

        <span>
            🔔
        </span>

        <span class="admin">
            Admin
        </span>

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
        Edit Alat
    </h1>

    <p class="page-subtitle">
        Ubah data alat.
    </p>

    <div class="form-card">


        <form
            action="../controller/c_alat.php?aksi=simpan_edit"
            method="POST"
            enctype="multipart/form-data"
        >

            <!-- ID ALAT -->

            <input
                type="hidden"
                name="id_alat"
                value="<?= $data['id_alat']; ?>"
            >



            <!-- KATEGORI -->

            <div class="form-row">

                <label>
                    Kategori
                </label>

                <select
                    name="id_kategori"
                    required>

                    <option value="">
                        -- Pilih Kategori --
                    </option>

                    <?php while ($kategori = mysqli_fetch_assoc($data_kategori)) { ?>

                        <option
                            value="<?= $kategori['id_kategori']; ?>"
                            <?= ($kategori['id_kategori'] == $data['id_kategori']) ? 'selected' : ''; ?>
                        >
                            <?= htmlspecialchars($kategori['nama_kategori']); ?>
                        </option>

                    <?php } ?>

                </select>

            </div>



            <!-- NAMA ALAT -->

            <div class="form-row">

                <label>
                    Nama Alat
                </label>

                <input
                    type="text"
                    name="nama_alat"
                    value="<?= htmlspecialchars($data['nama_alat']); ?>"
                    required
                >

            </div>



            <!-- JUMLAH -->

            <div class="form-row">

                <label>
                    Jumlah
                </label>

                <input
                    type="number"
                    name="jumlah"
                    value="<?= $data['jumlah']; ?>"
                    min="0"
                    required
                >

            </div>



            <!-- HARGA -->

            <div class="form-row">

                <label>
                    Harga
                </label>

                <input
                    type="number"
                    name="harga"
                    value="<?= $data['harga']; ?>"
                    min="0"
                    required
                >

            </div>



            <!-- KETERANGAN -->

            <div class="form-row">

                <label>
                    Keterangan
                </label>

                <textarea
                    name="keterangan"
                ><?= htmlspecialchars($data['keterangan']); ?></textarea>

            </div>



            <!-- FOTO -->

          <div class="form-row">

    <label>Foto Alat</label>

    <div class="foto-upload">

        <div class="foto-preview">

            <?php if (!empty($data['foto'])) { ?>

                <img
                    src="../asset/foto_alat/<?= htmlspecialchars($data['foto']); ?>"
                    alt="Foto Alat"
                >

            <?php } else { ?>

                <div class="foto-placeholder">
                    <span>🖼️</span>
                    <p>Preview Foto</p>
                </div>

            <?php } ?>

        </div>

        <div class="foto-input">
            <input type="file" name="foto" accept="image/png, image/jpeg">
            <small>Format gambar: JPG, PNG, JPEG. Maksimal 2MB. Kosongkan jika tidak ingin mengganti foto.</small>
        </div>

    </div>

</div>



            <!-- BUTTON -->

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