<?php

class M_peminjaman
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    public function tampil_data($cari = '')
    {
        $cari = mysqli_real_escape_string($this->koneksi, trim($cari));

        $where = '';
        if ($cari !== '') {
            $where = "WHERE users.nama LIKE '%$cari%'
                         OR users.username LIKE '%$cari%'";
        }

        $query = "SELECT 
                    peminjaman.*,
                    users.nama,
                    alat.nama_alat,
                    kategori_alat.nama_kategori
                  FROM peminjaman
                  LEFT JOIN users
                    ON peminjaman.id_users = users.id_users
                  LEFT JOIN alat
                    ON peminjaman.id_alat = alat.id_alat
                  LEFT JOIN kategori_alat
                    ON peminjaman.id_kategori = kategori_alat.id_kategori
                  $where
                  ORDER BY peminjaman.id_peminjaman DESC";

        return mysqli_query($this->koneksi, $query);
    }

    public function tampil_by_id($id)
    {
        $query = "SELECT * FROM peminjaman
                  WHERE id_peminjaman = '$id'";

        $result = mysqli_query($this->koneksi, $query);

        return mysqli_fetch_assoc($result);
    }

    public function tampil_users()
    {
        $query = "SELECT * FROM users
                  ORDER BY nama ASC";

        return mysqli_query($this->koneksi, $query);
    }

    public function tampil_alat()
    {
        $query = "SELECT * FROM alat
                  ORDER BY nama_alat ASC";

        return mysqli_query($this->koneksi, $query);
    }

    public function tampil_kategori()
    {
        $query = "SELECT * FROM kategori_alat
                  ORDER BY nama_kategori ASC";

        return mysqli_query($this->koneksi, $query);
    }

    public function tambah_data(
        $id_users,
        $id_alat,
        $id_kategori,
        $tanggal_pinjam,
        $tanggal_kembali,
        $jumlah,
        $status,
        $keterangan
    ) {
        $query = "INSERT INTO peminjaman
                  (
                    id_users,
                    id_alat,
                    id_kategori,
                    tanggal_pinjam,
                    tanggal_kembali,
                    jumlah,
                    status,
                    keterangan
                  )
                  VALUES
                  (
                    '$id_users',
                    '$id_alat',
                    '$id_kategori',
                    '$tanggal_pinjam',
                    '$tanggal_kembali',
                    '$jumlah',
                    '$status',
                    '$keterangan'
                  )";

        return mysqli_query($this->koneksi, $query);
    }

    public function ubah_data(
        $id,
        $id_users,
        $id_alat,
        $id_kategori,
        $tanggal_pinjam,
        $tanggal_kembali,
        $jumlah,
        $status,
        $keterangan
    ) {
        $query = "UPDATE peminjaman SET
                    id_users = '$id_users',
                    id_alat = '$id_alat',
                    id_kategori = '$id_kategori',
                    tanggal_pinjam = '$tanggal_pinjam',
                    tanggal_kembali = '$tanggal_kembali',
                    jumlah = '$jumlah',
                    status = '$status',
                    keterangan = '$keterangan'
                  WHERE id_peminjaman = '$id'";

        return mysqli_query($this->koneksi, $query);
    }

    // UBAH STATUS SAJA (dipakai untuk ACC / TOLAK oleh petugas)
    public function ubah_status($id, $status)
    {
        return mysqli_query(
            $this->koneksi,
            "UPDATE peminjaman
             SET status = '$status'
             WHERE id_peminjaman = '$id'"
        );
    }

    // STATUS PEMINJAMAN USER
    public function tampil_status_user($id_users)
    {
        $query = "SELECT 
                    peminjaman.*,
                    alat.nama_alat
                  FROM peminjaman
                  LEFT JOIN alat
                    ON peminjaman.id_alat = alat.id_alat
                  WHERE peminjaman.id_users = '$id_users'
                  ORDER BY peminjaman.id_peminjaman DESC";

        return mysqli_query($this->koneksi, $query);
    }

    // RIWAYAT PEMINJAMAN USER
    public function tampil_riwayat_user($id_users)
    {
        $query = "SELECT 
                    peminjaman.*,
                    alat.nama_alat
                  FROM peminjaman
                  LEFT JOIN alat
                    ON peminjaman.id_alat = alat.id_alat
                  WHERE peminjaman.id_users = '$id_users'
                  ORDER BY peminjaman.id_peminjaman DESC";

        return mysqli_query($this->koneksi, $query);
    }

    public function hapus_data($id)
    {
        $query = "DELETE FROM peminjaman
                  WHERE id_peminjaman = '$id'";

        return mysqli_query($this->koneksi, $query);
    }

    // ===== TAMBAHAN ALUR PEMINJAMAN / PENGEMBALIAN =====

    // Semua peminjaman dengan status tertentu (untuk halaman petugas)
    public function tampil_by_status($status)
    {
        $status = mysqli_real_escape_string($this->koneksi, $status);

        return mysqli_query(
            $this->koneksi,
            "SELECT peminjaman.*, users.nama, alat.nama_alat
             FROM peminjaman
             LEFT JOIN users ON peminjaman.id_users = users.id_users
             LEFT JOIN alat ON peminjaman.id_alat = alat.id_alat
             WHERE peminjaman.status = '$status'
             ORDER BY peminjaman.id_peminjaman ASC"
        );
    }

    // Peminjaman milik satu user dengan status tertentu
    public function tampil_user_by_status($id_users, $status)
    {
        $id_users = (int) $id_users;
        $status = mysqli_real_escape_string($this->koneksi, $status);

        return mysqli_query(
            $this->koneksi,
            "SELECT peminjaman.*, alat.nama_alat
             FROM peminjaman
             LEFT JOIN alat ON peminjaman.id_alat = alat.id_alat
             WHERE peminjaman.id_users = '$id_users'
             AND peminjaman.status = '$status'
             ORDER BY peminjaman.id_peminjaman DESC"
        );
    }

    public function hitung_status($status)
    {
        $status = mysqli_real_escape_string($this->koneksi, $status);
        $r = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(*) AS total FROM peminjaman WHERE status = '$status'"
        );
        return (int) mysqli_fetch_assoc($r)['total'];
    }

    public function hitung_user_status($id_users, $status)
    {
        $id_users = (int) $id_users;
        $status = mysqli_real_escape_string($this->koneksi, $status);
        $r = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(*) AS total FROM peminjaman
             WHERE id_users = '$id_users' AND status = '$status'"
        );
        return (int) mysqli_fetch_assoc($r)['total'];
    }

    // Peminjam mengajukan pengembalian (hanya milik sendiri & sedang Dipinjam)
    public function ajukan_pengembalian($id, $id_users)
    {
        $id = (int) $id;
        $id_users = (int) $id_users;

        mysqli_query(
            $this->koneksi,
            "UPDATE peminjaman SET status = 'Menunggu Pengembalian'
             WHERE id_peminjaman = '$id'
             AND id_users = '$id_users'
             AND status = 'Dipinjam'"
        );

        return mysqli_affected_rows($this->koneksi) > 0;
    }

    // ===== TAMBAHAN: PENGEMBALIAN & LAPORAN =====

    // Data pengembalian = peminjaman yang sudah diajukan kembali / sudah dikembalikan
    public function tampil_pengembalian()
    {
        return mysqli_query(
            $this->koneksi,
            "SELECT peminjaman.*, users.nama, alat.nama_alat
             FROM peminjaman
             LEFT JOIN users ON peminjaman.id_users = users.id_users
             LEFT JOIN alat ON peminjaman.id_alat = alat.id_alat
             WHERE peminjaman.status IN ('Menunggu Pengembalian', 'Dikembalikan')
             ORDER BY FIELD(peminjaman.status, 'Menunggu Pengembalian', 'Dikembalikan'),
                      peminjaman.id_peminjaman DESC"
        );
    }

    // Peminjaman yang masih bisa dicatat pengembaliannya oleh admin
    public function tampil_belum_kembali()
    {
        return mysqli_query(
            $this->koneksi,
            "SELECT peminjaman.*, users.nama, alat.nama_alat
             FROM peminjaman
             LEFT JOIN users ON peminjaman.id_users = users.id_users
             LEFT JOIN alat ON peminjaman.id_alat = alat.id_alat
             WHERE peminjaman.status IN ('Dipinjam', 'Menunggu Pengembalian')
             ORDER BY peminjaman.id_peminjaman DESC"
        );
    }

    // Laporan peminjaman (filter tanggal pinjam & status, boleh kosong)
    public function tampil_laporan($dari = '', $sampai = '', $status = '')
    {
        $where = [];

        if ($dari !== '') {
            $dari = mysqli_real_escape_string($this->koneksi, $dari);
            $where[] = "peminjaman.tanggal_pinjam >= '$dari'";
        }
        if ($sampai !== '') {
            $sampai = mysqli_real_escape_string($this->koneksi, $sampai);
            $where[] = "peminjaman.tanggal_pinjam <= '$sampai'";
        }
        if ($status !== '') {
            $status = mysqli_real_escape_string($this->koneksi, $status);
            $where[] = "peminjaman.status = '$status'";
        }

        $sql_where = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        return mysqli_query(
            $this->koneksi,
            "SELECT peminjaman.*, users.nama, alat.nama_alat, kategori_alat.nama_kategori
             FROM peminjaman
             LEFT JOIN users ON peminjaman.id_users = users.id_users
             LEFT JOIN alat ON peminjaman.id_alat = alat.id_alat
             LEFT JOIN kategori_alat ON peminjaman.id_kategori = kategori_alat.id_kategori
             $sql_where
             ORDER BY peminjaman.tanggal_pinjam DESC, peminjaman.id_peminjaman DESC"
        );
    }

    // Admin: ubah status & keterangan pengembalian
    public function ubah_pengembalian($id, $status, $keterangan)
    {
        $id = (int) $id;
        $status = mysqli_real_escape_string($this->koneksi, $status);
        $keterangan = mysqli_real_escape_string($this->koneksi, $keterangan);

        return mysqli_query(
            $this->koneksi,
            "UPDATE peminjaman
             SET status = '$status', keterangan = '$keterangan'
             WHERE id_peminjaman = '$id'"
        );
    }
}
