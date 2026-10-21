<?php $base = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\') . '/view/'; ?>
<div class="sidebar">
    <div class="sidebar-title">MENU PEMINJAM</div>

    <a href="<?= $base; ?>v_user.php" class="<?= ($menu_aktif ?? '') == 'dashboard' ? 'active' : ''; ?>">🏠 Dashboard</a>
    <a href="<?= $base; ?>v_daftar_alat.php" class="<?= ($menu_aktif ?? '') == 'daftar_alat' ? 'active' : ''; ?>">🧰 Daftar Alat</a>
    <a href="<?= $base; ?>v_peminjaman_user.php" class="<?= ($menu_aktif ?? '') == 'pinjam' ? 'active' : ''; ?>">📦 Ajukan Peminjaman</a>
    <a href="<?= $base; ?>v_pengembalian_user.php" class="<?= ($menu_aktif ?? '') == 'kembali' ? 'active' : ''; ?>">↩️ Kembalikan Alat</a>
</div>
