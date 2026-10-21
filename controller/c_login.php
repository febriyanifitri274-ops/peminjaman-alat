<?php

session_start();

include_once "../models/m_koneksi.php";
include_once "../models/m_login.php";
include_once "auth.php";

$login = new M_login($koneksi);

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $hasil = $login->login($username, $password);

    if (mysqli_num_rows($hasil) > 0) {

        $data = mysqli_fetch_assoc($hasil);

        $_SESSION['id_users'] = $data['id_users'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        catat_log($koneksi, "Login ke sistem");

        header("Location: ../view/" . halaman_awal($data['role']));
        exit;

    } else {

        header("Location: ../view/v_login.php?pesan=gagal");
        exit;

    }
}
