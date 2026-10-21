<?php

class M_pengguna
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
        $this->pastikan_kolom_status();
    }

    // Tambah kolom status otomatis kalau belum ada (aman dipanggil berulang)
    private function pastikan_kolom_status()
    {
        try {
            $r = mysqli_query($this->koneksi, "SHOW COLUMNS FROM users LIKE 'status'");

            if ($r && mysqli_num_rows($r) == 0) {
                mysqli_query(
                    $this->koneksi,
                    "ALTER TABLE users
                     ADD COLUMN status VARCHAR(10) NOT NULL DEFAULT 'Aktif'"
                );
            }
        } catch (Throwable $e) {
            // abaikan: halaman tetap jalan, status dianggap Aktif
        }
    }

    private function esc($v)
    {
        return mysqli_real_escape_string($this->koneksi, $v);
    }

    public function tampil_data()
    {
        return mysqli_query(
            $this->koneksi,
            "SELECT * FROM users ORDER BY id_users DESC"
        );
    }

    public function tampil_by_id($id)
    {
        $id = (int) $id;
        $r = mysqli_query($this->koneksi, "SELECT * FROM users WHERE id_users = '$id'");
        return mysqli_fetch_assoc($r);
    }

    public function username_ada($username, $kecuali_id = 0)
    {
        $username = $this->esc($username);
        $kecuali_id = (int) $kecuali_id;
        $r = mysqli_query(
            $this->koneksi,
            "SELECT id_users FROM users
             WHERE username = '$username' AND id_users != '$kecuali_id'"
        );
        return mysqli_num_rows($r) > 0;
    }

    public function tambah_data($nama, $username, $password, $role, $status = 'Aktif')
    {
        $nama = $this->esc($nama);
        $username = $this->esc($username);
        $password = $this->esc($password);
        $role = $this->esc($role);
        $status = $this->esc($status);

        return mysqli_query(
            $this->koneksi,
            "INSERT INTO users (nama, username, password, role, status)
             VALUES ('$nama', '$username', '$password', '$role', '$status')"
        );
    }

    // password kosong = tidak diubah
    public function ubah_data($id, $nama, $username, $password, $role, $status = null)
    {
        $id = (int) $id;
        $nama = $this->esc($nama);
        $username = $this->esc($username);
        $role = $this->esc($role);

        $set_password = '';
        if ($password !== '') {
            $set_password = ", password = '" . $this->esc($password) . "'";
        }

        $set_status = '';
        if ($status !== null) {
            $set_status = ", status = '" . $this->esc($status) . "'";
        }

        return mysqli_query(
            $this->koneksi,
            "UPDATE users SET
                nama = '$nama',
                username = '$username',
                role = '$role'
                $set_password
                $set_status
             WHERE id_users = '$id'"
        );
    }

    // Reset password (disimpan sama seperti password lain di project ini)
    public function reset_password($id, $password_baru)
    {
        $id = (int) $id;
        $password_baru = $this->esc($password_baru);

        return mysqli_query(
            $this->koneksi,
            "UPDATE users SET password = '$password_baru'
             WHERE id_users = '$id'"
        );
    }

    public function hapus_data($id)
{
    $id = (int) $id;

    mysqli_begin_transaction($this->koneksi);

    try {

        // 1. Ambil peminjaman yang masih memakai stok alat
        $query = mysqli_query(
            $this->koneksi,
            "SELECT id_alat, jumlah
             FROM peminjaman
             WHERE id_users = '$id'
             AND status IN ('Dipinjam', 'Menunggu Pengembalian')"
        );

        if (!$query) {
            throw new Exception(mysqli_error($this->koneksi));
        }

        // 2. Kembalikan stok alat
        while ($data = mysqli_fetch_assoc($query)) {

            $id_alat = (int) $data['id_alat'];
            $jumlah = (int) $data['jumlah'];

            $update_stok = mysqli_query(
                $this->koneksi,
                "UPDATE alat
                 SET jumlah = jumlah + $jumlah
                 WHERE id_alat = '$id_alat'"
            );

            if (!$update_stok) {
                throw new Exception(mysqli_error($this->koneksi));
            }
        }

        // 3. Hapus semua peminjaman user
        $hapus_peminjaman = mysqli_query(
            $this->koneksi,
            "DELETE FROM peminjaman
             WHERE id_users = '$id'"
        );

        if (!$hapus_peminjaman) {
            throw new Exception(mysqli_error($this->koneksi));
        }

        // 4. Hapus semua log aktivitas user
        $hapus_log = mysqli_query(
            $this->koneksi,
            "DELETE FROM log_aktivitas
             WHERE id_users = '$id'"
        );

        if (!$hapus_log) {
            throw new Exception(mysqli_error($this->koneksi));
        }

        // 5. Hapus user
        $hapus_user = mysqli_query(
            $this->koneksi,
            "DELETE FROM users
             WHERE id_users = '$id'"
        );

        if (!$hapus_user) {
            throw new Exception(mysqli_error($this->koneksi));
        }

        // Semua berhasil
        mysqli_commit($this->koneksi);

        return true;

    } catch (Throwable $e) {

        // Kalau ada yang gagal, batalkan semuanya
        mysqli_rollback($this->koneksi);

        return false;
    }
}
}
