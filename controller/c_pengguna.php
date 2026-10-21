<?php

require_once __DIR__ . '/auth.php';
wajib_role(['admin']);

include_once "../models/m_koneksi.php";
include_once "../models/m_pengguna.php";

$pengguna = new M_pengguna($koneksi);

$aksi = $_GET['aksi'] ?? '';
$role_valid = ['admin', 'petugas', 'peminjam'];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ../view/v_pengguna.php");
    exit;
}

/* TAMBAH */
if ($aksi == 'simpan') {

    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($nama == '' || $username == '' || $password == '' || !in_array($role, $role_valid)) {
        header("Location: ../view/v_tambah_pengguna.php?pesan=kosong");
        exit;
    }

    if ($pengguna->username_ada($username)) {
        header("Location: ../view/v_tambah_pengguna.php?pesan=username");
        exit;
    }

    $status = $_POST['status'] ?? 'Aktif';
    if (!in_array($status, ['Aktif', 'Nonaktif'])) {
        $status = 'Aktif';
    }

    $pengguna->tambah_data($nama, $username, $password, $role, $status);
    catat_log($koneksi, "Menambah user: $username ($role)");

    header("Location: ../view/v_pengguna.php?pesan=tambah");
    exit;
}

/* UBAH */
if ($aksi == 'ubah') {

    $id = (int) ($_POST['id_users'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($nama == '' || $username == '' || !in_array($role, $role_valid)) {
        header("Location: ../view/v_edit_pengguna.php?id=$id&pesan=kosong");
        exit;
    }

    if ($pengguna->username_ada($username, $id)) {
        header("Location: ../view/v_edit_pengguna.php?id=$id&pesan=username");
        exit;
    }

    $status = $_POST['status'] ?? 'Aktif';
    if (!in_array($status, ['Aktif', 'Nonaktif'])) {
        $status = 'Aktif';
    }

    $pengguna->ubah_data($id, $nama, $username, $password, $role, $status);
    catat_log($koneksi, "Mengubah user: $username ($role)");

    header("Location: ../view/v_pengguna.php?pesan=ubah");
    exit;
}
/* HAPUS */
if ($aksi == 'hapus') {

    $id = (int) ($_POST['id_users'] ?? 0);

    // Tidak boleh hapus akun sendiri
    if ($id == (int) $_SESSION['id_users']) {
        header("Location: ../view/v_pengguna.php?pesan=diri");
        exit;
    }

    // Cek user
    $data = $pengguna->tampil_by_id($id);

    if (!$data) {
        header("Location: ../view/v_pengguna.php?pesan=tidak_ada");
        exit;
    }

    // Hapus user + peminjaman + log
    $hasil = $pengguna->hapus_data($id);

    if ($hasil === true) {
        header("Location: ../view/v_pengguna.php?pesan=hapus");
        exit;
    } else {
        header("Location: ../view/v_pengguna.php?pesan=gagal_hapus");
        exit;
    }
}

/* RESET PASSWORD */
if ($aksi == 'reset') {

    $id = (int) ($_POST['id_users'] ?? 0);
    $data = $pengguna->tampil_by_id($id);

    if (!$data) {
        header("Location: ../view/v_pengguna.php?pesan=tidak_ada");
        exit;
    }

    // password acak 8 karakter (tanpa karakter yang mirip: 0 O 1 l I)
    $huruf = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $password_baru = '';

    for ($i = 0; $i < 8; $i++) {
        $password_baru .= $huruf[random_int(0, strlen($huruf) - 1)];
    }

    $pengguna->reset_password($id, $password_baru);
    catat_log($koneksi, "Reset password user: " . $data['username']);

    // ditampilkan sekali di halaman daftar user
    $_SESSION['flash_reset'] = "Password untuk " . $data['username']
        . " direset. Password baru: " . $password_baru
        . " (catat sekarang, tidak ditampilkan lagi).";

    header("Location: ../view/v_pengguna.php?pesan=reset");
    exit;
}

header("Location: ../view/v_pengguna.php");
exit;
