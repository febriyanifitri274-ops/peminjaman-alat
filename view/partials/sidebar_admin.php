<?php $base = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\') . '/view/'; ?>
<div class="sidebar">
    <div class="sidebar-title">MENU ADMIN</div>

    <a href="<?= $base; ?>v_dashboard.php" class="<?= ($menu_aktif ?? '') == 'dashboard' ? 'active' : ''; ?>">🏠 Dashboard</a>
    <a href="<?= $base; ?>v_pengguna.php" class="<?= ($menu_aktif ?? '') == 'pengguna' ? 'active' : ''; ?>">👥 User</a>
    <a href="<?= $base; ?>v_alat.php" class="<?= ($menu_aktif ?? '') == 'alat' ? 'active' : ''; ?>">🧰 Alat</a>
    <a href="<?= $base; ?>v_kategori.php" class="<?= ($menu_aktif ?? '') == 'kategori' ? 'active' : ''; ?>">📂 Kategori</a>
    <a href="<?= $base; ?>v_peminjaman.php" class="<?= ($menu_aktif ?? '') == 'peminjaman' ? 'active' : ''; ?>">📋 Peminjaman</a>
    <a href="<?= $base; ?>../controller/c_pengembalian.php" class="<?= ($menu_aktif ?? '') == 'pengembalian' ? 'active' : ''; ?>">↩️ Pengembalian</a>
    <a href="<?= $base; ?>v_log_aktivitas.php" class="<?= ($menu_aktif ?? '') == 'log' ? 'active' : ''; ?>">🕐 Log Aktivitas</a>
</div>
