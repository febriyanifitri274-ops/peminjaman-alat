-- Jalankan sekali di phpMyAdmin (database: peminjaman_alat)
-- Menambah kolom status untuk user: 'Aktif' atau 'Nonaktif'.
-- User yang sudah ada otomatis berstatus 'Aktif'.
ALTER TABLE users
    ADD COLUMN status VARCHAR(10) NOT NULL DEFAULT 'Aktif';
