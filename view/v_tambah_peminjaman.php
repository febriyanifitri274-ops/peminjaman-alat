<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Peminjaman</title>

    <link rel="stylesheet" href="../asset/peminjaman.css">
    <link rel="stylesheet" href="../asset/tambah_peminjaman.css">

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
    <a href="../view/v_alat.php">🧰 Alat</a>
    <a href="../view/v_kategori.php">📂 Kategori</a>
    <a href="../view/v_peminjaman.php" class="active">📋 Peminjaman</a>
    <a href="../controller/c_pengembalian.php">↩️ Pengembalian</a>
    <a href="../view/v_log_aktivitas.php">🕐 Log Aktivitas</a>
</div>


    <div class="content">

        <h1 class="page-title">
            Tambah Peminjaman
        </h1>

        <p class="page-subtitle">
            Tambahkan data peminjaman baru.
        </p>

        <div class="form-container">

            <form action="../controller/c_peminjaman.php?aksi=tambah" method="POST">

                <!-- PEMINJAM -->

                <div class="form-group">

                    <label for="id_users">Peminjam</label>

                    <select name="id_users" id="id_users" required>

                        <option value="">-- Pilih Peminjam --</option>

                        <?php while ($user = mysqli_fetch_assoc($data_users)) : ?>

                            <option value="<?= $user['id_users']; ?>">
                                <?= htmlspecialchars($user['nama'] ?? ''); ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- ALAT -->

                <div class="form-group">

                    <label for="id_alat">Nama Alat</label>

                    <select name="id_alat" id="id_alat" required>

                        <option value="">-- Pilih Alat --</option>

                        <?php while ($alat = mysqli_fetch_assoc($data_alat)) : ?>

                            <option value="<?= $alat['id_alat']; ?>">
                                <?= htmlspecialchars($alat['nama_alat'] ?? ''); ?>
                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- TANGGAL PINJAM -->

                <div class="form-group">

                    <label for="tanggal_pinjam">Tanggal Pinjam</label>

                    <input
                        type="date"
                        name="tanggal_pinjam"
                        id="tanggal_pinjam"
                        required>

                </div>


                <!-- TANGGAL KEMBALI -->

                <div class="form-group">

                    <label for="tanggal_kembali">Tanggal Kembali</label>

                    <input
                        type="date"
                        name="tanggal_kembali"
                        id="tanggal_kembali"
                        required>

                </div>


                <!-- JUMLAH -->

                <div class="form-group">

                    <label for="jumlah">Jumlah</label>

                    <input
                        type="number"
                        name="jumlah"
                        id="jumlah"
                        min="1"
                        required>

                </div>


                <!-- STATUS -->

                <div class="form-group">

                    <label for="status">Status</label>

                    <select name="status" id="status" required>

                        <option value="Dipinjam">Dipinjam</option>
                        <option value="Dikembalikan">Dikembalikan</option>

                    </select>

                </div>


                <!-- KETERANGAN -->

                <div class="form-group">

                    <label for="keterangan">Keterangan</label>

                    <textarea
                        name="keterangan"
                        id="keterangan"
                        placeholder="Masukkan keterangan"></textarea>

                </div>


                <!-- BUTTON -->

                <div class="button-group">

                    <button type="submit" class="btn btn-simpan">
                        Simpan
                    </button>

                    <a
                        href="../controller/c_peminjaman.php"
                        class="btn btn-kembali">
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>