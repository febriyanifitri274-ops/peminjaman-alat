-- Jalankan sekali di phpMyAdmin (database: peminjaman_alat)
-- Supaya kolom status bisa menampung "Menunggu Pengembalian"
ALTER TABLE peminjaman
    MODIFY status VARCHAR(30) NOT NULL DEFAULT 'Menunggu';

-- Ganti role lama 'user' menjadi 'peminjam'
UPDATE users SET role = 'peminjam' WHERE LOWER(role) = 'user';
