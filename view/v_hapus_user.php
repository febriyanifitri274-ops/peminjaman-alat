<?php
session_start();

if (
    !isset($_SESSION['id_users']) ||
    strtolower($_SESSION['role']) != 'admin'
) {
    header("Location: v_login.php");
    exit;
}

include_once "../models/m_koneksi.php";
include_once "../models/m_users.php";

$users = new M_users($koneksi);

$id = $_GET['id'] ?? 0;
$id = (int) $id;

// Tidak boleh hapus akun sendiri
if ($id == $_SESSION['id_users']) {
    header("Location: v_pengguna.php?pesan=hapus_sendiri");
    exit;
}

// Cek user
$data_user = $users->tampil_by_id($id);

if (!$data_user) {
    header("Location: v_pengguna.php?pesan=user_tidak_ditemukan");
    exit;
}

// Proses hapus
$hasil = $users->hapus_data($id);

if ($hasil === true) {
    header("Location: v_pengguna.php?pesan=berhasil_hapus");
    exit;
} else {
    header("Location: v_pengguna.php?pesan=gagal_hapus");
    exit;
}
?>