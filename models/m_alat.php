<?php

class M_alat
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
            "SELECT alat.*, kategori_alat.nama_kategori
             FROM alat
             LEFT JOIN kategori_alat
             ON alat.id_kategori = kategori_alat.id_kategori
             ORDER BY alat.id_alat DESC"
        );
    }

    // DATA ALAT BERDASARKAN ID
    public function tampil_data_by_id($id)
    {
        return mysqli_query(
            $this->koneksi,
            "SELECT * FROM alat
             WHERE id_alat = '$id'"
        );
    }

    // DATA KATEGORI
    public function tampil_kategori()
    {
        return mysqli_query(
            $this->koneksi,
            "SELECT * FROM kategori_alat
             ORDER BY nama_kategori ASC"
        );
    }

    // TAMBAH
    public function tambah_data(
        $id_kategori,
        $nama_alat,
        $jumlah,
        $harga,
        $keterangan,
        $foto
    ) {
        return mysqli_query(
            $this->koneksi,
            "INSERT INTO alat
            (id_kategori, nama_alat, jumlah, harga, keterangan, foto)
            VALUES
            ('$id_kategori',
             '$nama_alat',
             '$jumlah',
             '$harga',
             '$keterangan',
             '$foto')"
        );
    }

    // EDIT
    public function ubah_data(
        $id,
        $id_kategori,
        $nama_alat,
        $jumlah,
        $harga,
        $keterangan,
        $foto
    ) {
        return mysqli_query(
            $this->koneksi,
            "UPDATE alat SET
            id_kategori = '$id_kategori',
            nama_alat = '$nama_alat',
            jumlah = '$jumlah',
            harga = '$harga',
            keterangan = '$keterangan',
            foto = '$foto'
            WHERE id_alat = '$id'"
        );
    }

    // KURANGI STOK (dipakai saat peminjaman di-ACC)
    // hanya berhasil mengurangi kalau stok masih mencukupi
    public function kurangi_stok($id_alat, $jumlah)
    {
        mysqli_query(
            $this->koneksi,
            "UPDATE alat
             SET jumlah = jumlah - '$jumlah'
             WHERE id_alat = '$id_alat'
             AND jumlah >= '$jumlah'"
        );

        return mysqli_affected_rows($this->koneksi) > 0;
    }

    // TAMBAH STOK (dipakai saat peminjaman ditolak setelah sempat di-ACC, atau saat dikembalikan)
    public function tambah_stok($id_alat, $jumlah)
    {
        return mysqli_query(
            $this->koneksi,
            "UPDATE alat
             SET jumlah = jumlah + '$jumlah'
             WHERE id_alat = '$id_alat'"
        );
    }

    // HAPUS
    public function hapus_data($id)
    {
        return mysqli_query(
            $this->koneksi,
            "DELETE FROM alat
             WHERE id_alat = '$id'"
        );
    }
}

?>