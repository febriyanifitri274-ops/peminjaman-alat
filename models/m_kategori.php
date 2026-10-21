<?php

class M_kategori
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    // TAMPIL DATA
    public function tampil_data()
    {
        return mysqli_query(
            $this->koneksi,
            "SELECT * FROM kategori_alat
             ORDER BY id_kategori DESC"
        );
    }

    // AMBIL DATA BERDASARKAN ID
    public function tampil_data_by_id($id)
    {
        return mysqli_query(
            $this->koneksi,
            "SELECT * FROM kategori_alat
             WHERE id_kategori = '$id'"
        );
    }

    // TAMBAH DATA
    public function tambah_data($nama_kategori, $keterangan)
    {
        return mysqli_query(
            $this->koneksi,
            "INSERT INTO kategori_alat
             (nama_kategori, keterangan)
             VALUES
             ('$nama_kategori', '$keterangan')"
        );
    }

    // EDIT DATA
    public function ubah_data($id, $nama_kategori, $keterangan)
    {
        return mysqli_query(
            $this->koneksi,
            "UPDATE kategori_alat SET
             nama_kategori = '$nama_kategori',
             keterangan = '$keterangan'
             WHERE id_kategori = '$id'"
        );
    }

    // HAPUS DATA
    public function hapus_data($id)
    {
        return mysqli_query(
            $this->koneksi,
            "DELETE FROM kategori_alat
             WHERE id_kategori = '$id'"
        );
    }
}

?>