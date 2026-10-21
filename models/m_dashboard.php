<?php

class M_dashboard
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    // TOTAL KATEGORI
    public function total_kategori()
    {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(*) AS total FROM kategori_alat"
        );

        $data = mysqli_fetch_assoc($query);

        return $data['total'];
    }

    // TOTAL ALAT
    public function total_alat()
    {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(*) AS total FROM alat"
        );

        $data = mysqli_fetch_assoc($query);

        return $data['total'];
    }

    // TOTAL PEMINJAMAN
    public function total_peminjaman()
    {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(*) AS total FROM peminjaman"
        );

        $data = mysqli_fetch_assoc($query);

        return $data['total'];
    }

    // SEDANG DIPINJAM
    public function sedang_dipinjam()
    {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(*) AS total
             FROM peminjaman
             WHERE status = 'Dipinjam'"
        );

        $data = mysqli_fetch_assoc($query);

        return $data['total'];
    }

    // PEMINJAMAN TERBARU
   public function peminjaman_terbaru()
{
    $query = "SELECT peminjaman.*,
                     users.nama AS nama_peminjam,
                     alat.nama_alat
              FROM peminjaman
              LEFT JOIN users
              ON peminjaman.id_users = users.id_users
              LEFT JOIN alat
              ON peminjaman.id_alat = alat.id_alat
              ORDER BY peminjaman.id_peminjaman DESC
              LIMIT 5";

    return mysqli_query($this->koneksi, $query);
   }

}
?>