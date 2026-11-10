<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cek_akses(
    $roles_diizinkan,
    $path_login = 'v_login.php',
    $path_dashboard = 'v_dashboard.php'
) {
    if (!isset($_SESSION['id_users'])) {
        header("Location: $path_login");
        exit;
    }

    $role_user = strtolower(trim($_SESSION['role'] ?? ''));

    $roles_diizinkan = array_map(
        'strtolower',
        $roles_diizinkan
    );

    if (!in_array($role_user, $roles_diizinkan, true)) {
        switch ($role_user) {
            case 'admin':
                $path_dashboard = 'v_dashboard.php';
                break;

            case 'petugas':
                $path_dashboard = 'v_petugas.php';
                break;

            case 'peminjam':
                $path_dashboard = 'v_peminjaman_user.php';
                break;

            default:
                header("Location: $path_login");
                exit;
        }

        header("Location: $path_dashboard");
        exit;
    }
}

function role_sekarang()
{
    return isset($_SESSION['role'])
        ? strtolower(trim($_SESSION['role']))
        : '';
}
