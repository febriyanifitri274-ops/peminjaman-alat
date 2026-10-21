<?php $base = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\') . '/view/'; ?>
<div class="navbar">
    <div class="logo">🌸 PEMINJAMAN ALAT</div>

    <div class="navbar-right">
        <span class="admin"><?= htmlspecialchars($label_role ?? ''); ?> · <?= htmlspecialchars($_SESSION['nama'] ?? ''); ?></span>
        <a href="<?= $base; ?>v_logout.php" class="logout">Logout</a>
    </div>
</div>
