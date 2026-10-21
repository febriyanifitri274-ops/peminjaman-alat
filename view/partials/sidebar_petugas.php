<?php $base = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\') . '/view/'; ?>
<div class="sidebar">
    <div class="sidebar-title">MENU PETUGAS</div>

    <a href="<?= $base; ?>v_petugas.php" class="<?= ($menu_aktif ?? '') == 'dashboard' ? 'active' : ''; ?>">🏠 Dashboard</a>
    <a href="<?= $base; ?>v_setujui_peminjaman.php" class="<?= ($menu_aktif ?? '') == 'setujui_pinjam' ? 'active' : ''; ?>">✅ Setujui Peminjaman</a>
    <a href="<?= $base; ?>v_pantau_pengembalian.php" class="<?= ($menu_aktif ?? '') == 'pantau_kembali' ? 'active' : ''; ?>">📥 Pantau Pengembalian</a>
    <a href="<?= $base; ?>v_laporan.php" class="<?= ($menu_aktif ?? '') == 'laporan' ? 'active' : ''; ?>">🖨️ Cetak Laporan</a>
</div>
