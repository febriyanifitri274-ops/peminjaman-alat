<?php
// Helper login & hak akses per peran + pencatatan log aktivitas

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Halaman awal tiap peran
function halaman_awal($role)
{
    $role = normal_role($role);

    if ($role == 'admin') {
        return 'v_dashboard.php';
    }

    if ($role == 'petugas') {
        return 'v_petugas.php';
    }

    return 'v_user.php'; // dashboard peminjam
}

// Peran yang dikenali sistem (data lama 'user' otomatis dianggap 'peminjam')
function normal_role($role)
{
    $role = strtolower(trim($role));
    return $role == 'user' ? 'peminjam' : $role;
}

function role_valid($role)
{
    return in_array(normal_role($role), ['admin', 'petugas', 'peminjam']);
}

function url_view()
{
    $root = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
    return $root . '/view/';
}

// Hanya peran tertentu yang boleh masuk.
function wajib_role(array $boleh)
{
    $role = normal_role($_SESSION['role'] ?? '');

    // belum login
    if (!isset($_SESSION['id_users'])) {
        header("Location: " . url_view() . "v_login.php");
        exit;
    }

    // role akun tidak dikenali -> keluarkan sesi (mencegah loop redirect)
    if (!role_valid($role)) {
        session_destroy();
        header("Location: " . url_view() . "v_login.php?pesan=role");
        exit;
    }

    // login tapi bukan halaman perannya -> arahkan ke halaman perannya
    if (!in_array($role, $boleh)) {
        header("Location: " . url_view() . halaman_awal($role));
        exit;
    }
}

// Catat aktivitas ke tabel log_aktivitas (tidak boleh menggagalkan proses utama)
function catat_log($koneksi, $aktivitas)
{
    try {
        $id_users = (int) ($_SESSION['id_users'] ?? 0);
        $aktivitas = mysqli_real_escape_string($koneksi, $aktivitas);

        mysqli_query(
            $koneksi,
            "INSERT INTO log_aktivitas (id_users, aktivitas, waktu)
             VALUES ('$id_users', '$aktivitas', NOW())"
        );
    } catch (Throwable $e) {
        // abaikan
    }
}

// Kelas CSS badge status peminjaman
function kelas_status($status)
{
    switch ($status) {
        case 'Menunggu':
            return 'status-menunggu';
        case 'Dikembalikan':
            return 'status-dikembalikan';
        case 'Ditolak':
            return 'status-ditolak';
        case 'Menunggu Pengembalian':
            return 'status-menunggu-pengembalian';
        default:
            return 'status-dipinjam';
    }
}
