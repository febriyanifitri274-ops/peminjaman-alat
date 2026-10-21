<?php

class M_users
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    // Tampil semua user, bisa dicari berdasarkan nama atau username
    public function tampil_data($cari = '')
    {
        $cari = mysqli_real_escape_string($this->koneksi, trim($cari));

        $where = "";

        if ($cari !== '') {
            $where = "WHERE nama LIKE '%$cari%'
                      OR username LIKE '%$cari%'";
        }

        $query = "SELECT * FROM users
                  $where
                  ORDER BY id_users DESC";

        return mysqli_query($this->koneksi, $query);
    }

    public function tampil_by_id($id)
    {
        $id = (int) $id;

        $result = mysqli_query(
            $this->koneksi,
            "SELECT * FROM users WHERE id_users = '$id'"
        );

        return mysqli_fetch_assoc($result);
    }

    public function username_ada($username, $kecuali_id = 0)
    {
        $username = mysqli_real_escape_string($this->koneksi, $username);
        $kecuali_id = (int) $kecuali_id;

        $result = mysqli_query(
            $this->koneksi,
            "SELECT id_users FROM users
             WHERE username = '$username'
             AND id_users != '$kecuali_id'"
        );

        return mysqli_num_rows($result) > 0;
    }

    public function tambah_data($nama, $username, $password, $role)
    {
        $nama = mysqli_real_escape_string($this->koneksi, $nama);
        $username = mysqli_real_escape_string($this->koneksi, $username);
        $password = mysqli_real_escape_string($this->koneksi, $password);
        $role = mysqli_real_escape_string($this->koneksi, $role);

       return mysqli_query(
    $this->koneksi,
    "INSERT INTO users (nama, username, password, role, status)
     VALUES ('$nama', '$username', '$password', '$role', 'Aktif','Nonaktif')"
);
    }

    // Password kosong = password lama tidak diubah
    public function ubah_data($id, $nama, $username, $password, $role)
    {
        $id = (int) $id;
        $nama = mysqli_real_escape_string($this->koneksi, $nama);
        $username = mysqli_real_escape_string($this->koneksi, $username);
        $password = mysqli_real_escape_string($this->koneksi, $password);
        $role = mysqli_real_escape_string($this->koneksi, $role);

        $set_password = ($password !== '') ? ", password = '$password'" : "";

        return mysqli_query(
            $this->koneksi,
            "UPDATE users SET
                nama = '$nama',
                username = '$username',
                role = '$role'
                $set_password
             WHERE id_users = '$id'"
        );
    }

 public function hapus_data($id)
{
    $id = (int) $id;

    mysqli_begin_transaction($this->koneksi);

    try {

        // Ambil peminjaman yang masih menggunakan stok
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

        // Kembalikan stok
        while ($data = mysqli_fetch_assoc($query)) {

            $id_alat = (int) $data['id_alat'];
            $jumlah = (int) $data['jumlah'];

            $update = mysqli_query(
                $this->koneksi,
                "UPDATE alat
                 SET jumlah = jumlah + $jumlah
                 WHERE id_alat = '$id_alat'"
            );

            if (!$update) {
                throw new Exception(mysqli_error($this->koneksi));
            }
        }

        // Hapus semua peminjaman user
        $hapus_peminjaman = mysqli_query(
            $this->koneksi,
            "DELETE FROM peminjaman
             WHERE id_users = '$id'"
        );

        if (!$hapus_peminjaman) {
            throw new Exception(mysqli_error($this->koneksi));
        }

        // Hapus semua log user
        $hapus_log = mysqli_query(
            $this->koneksi,
            "DELETE FROM log_aktivitas
             WHERE id_users = '$id'"
        );

        if (!$hapus_log) {
            throw new Exception(mysqli_error($this->koneksi));
        }

        // Hapus user
        $hapus_user = mysqli_query(
            $this->koneksi,
            "DELETE FROM users
             WHERE id_users = '$id'"
        );

        if (!$hapus_user) {
            throw new Exception(mysqli_error($this->koneksi));
        }

        mysqli_commit($this->koneksi);

        return true;

    } catch (Exception $e) {

        mysqli_rollback($this->koneksi);

        return false;
    }
}
?>