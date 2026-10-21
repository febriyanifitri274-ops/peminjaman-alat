
<?php

class m_log_aktivitas
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
    }

    // Jumlah seluruh data log
    public function hitung_total()
    {
        $result = mysqli_query($this->koneksi, "SELECT COUNT(*) AS total FROM log_aktivitas");
        $row = mysqli_fetch_assoc($result);

        return (int) ($row['total'] ?? 0);
    }

    // Tampil data log per halaman (LIMIT / OFFSET)
    public function tampil_data($limit = 15, $offset = 0)
    {
        $limit  = (int) $limit;
        $offset = (int) $offset;

        $query = "SELECT 
                    log_aktivitas.*,
                    users.nama
                  FROM log_aktivitas
                  LEFT JOIN users
                    ON log_aktivitas.id_users = users.id_users
                  ORDER BY log_aktivitas.id_log DESC
                  LIMIT $limit OFFSET $offset";

        return mysqli_query($this->koneksi, $query);
    }
}

?>