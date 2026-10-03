-- Migration: Add new columns to sekolah table for enhanced school form
-- Run this SQL in phpMyAdmin or MySQL command line

ALTER TABLE sekolah 
ADD COLUMN IF NOT EXISTS kelurahan VARCHAR(100) NULL AFTER kecamatan,
ADD COLUMN IF NOT EXISTS kode_pos VARCHAR(10) NULL AFTER kelurahan,
ADD COLUMN IF NOT EXISTS telepon VARCHAR(20) NULL AFTER foto,
ADD COLUMN IF NOT EXISTS email VARCHAR(100) NULL AFTER telepon,
ADD COLUMN IF NOT EXISTS website VARCHAR(255) NULL AFTER email,
ADD COLUMN IF NOT EXISTS kepala_sekolah VARCHAR(100) NULL AFTER website,
ADD COLUMN IF NOT EXISTS nip_kepala_sekolah VARCHAR(30) NULL AFTER kepala_sekolah;

-- Note: If your MySQL version doesn't support IF NOT EXISTS for columns, 
-- use this alternative version (check if column exists first):

-- ALTER TABLE sekolah ADD COLUMN kelurahan VARCHAR(100) NULL;
-- ALTER TABLE sekolah ADD COLUMN kode_pos VARCHAR(10) NULL;
-- ALTER TABLE sekolah ADD COLUMN telepon VARCHAR(20) NULL;
-- ALTER TABLE sekolah ADD COLUMN email VARCHAR(100) NULL;
-- ALTER TABLE sekolah ADD COLUMN website VARCHAR(255) NULL;
-- ALTER TABLE sekolah ADD COLUMN kepala_sekolah VARCHAR(100) NULL;
-- ALTER TABLE sekolah ADD COLUMN nip_kepala_sekolah VARCHAR(30) NULL;
