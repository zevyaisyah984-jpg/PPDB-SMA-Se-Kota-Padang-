-- =====================================================
-- PPDB SMA - Publication Features Migration
-- Created: 2026-01-05
-- Description: Add activity logs, update publikasi_hasil, 
--              and ensure siswa table has required fields
-- =====================================================

-- 1. Create activity_logs table for audit trail
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_id` INT(11) DEFAULT NULL,
  `action_type` VARCHAR(50) DEFAULT NULL COMMENT 'publikasi, seleksi, user_management, etc',
  `description` TEXT COMMENT 'Detailed description of the action',
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_admin` (`admin_id`),
  KEY `idx_action` (`action_type`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Audit trail for admin actions';

-- 2. Update publikasi_hasil table
-- Add updated_at column if not exists
SET @dbname = DATABASE();
SET @tablename = 'publikasi_hasil';
SET @columnname = 'updated_at';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER published_at')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 3. Ensure siswa table has sekolah_asal column
SET @tablename = 'siswa';
SET @columnname = 'sekolah_asal';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' VARCHAR(255) DEFAULT NULL AFTER alamat')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 4. Add indexes for better performance
-- Check and add index on pendaftaran (siswa_id, status)
SET @tablename = 'pendaftaran';
SET @indexname = 'idx_siswa_status';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = @indexname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD INDEX ', @indexname, ' (siswa_id, status)')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 5. Add index on publikasi_hasil (sekolah_id, jalur, status)
SET @tablename = 'publikasi_hasil';
SET @indexname = 'idx_sekolah_jalur_status';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (index_name = @indexname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD INDEX ', @indexname, ' (sekolah_id, jalur, status)')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 6. Insert sample activity log (for testing)
INSERT INTO `activity_logs` (`admin_id`, `action_type`, `description`, `created_at`)
VALUES (1, 'system', 'Publication features migration completed successfully', NOW())
ON DUPLICATE KEY UPDATE description = description;

-- =====================================================
-- Migration Complete!
-- =====================================================
-- Tables Created/Updated:
--   ✓ activity_logs (created)
--   ✓ publikasi_hasil (updated_at added)
--   ✓ siswa (sekolah_asal added)
--   ✓ Indexes optimized
-- =====================================================

SELECT 'Migration completed successfully!' AS Status,
       NOW() AS Timestamp,
       DATABASE() AS Database_Name;
