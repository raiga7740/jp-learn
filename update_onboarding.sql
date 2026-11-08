-- Tambahkan kolom has_completed_onboarding ke tabel users (default 1 / true agar user lama tidak terganggu)
ALTER TABLE `users` ADD COLUMN `has_completed_onboarding` TINYINT(1) NOT NULL DEFAULT 1 AFTER `last_activity_date`;

-- Pastikan semua akun pengguna lama yang sudah ada ditandai telah selesai onboarding
UPDATE `users` SET `has_completed_onboarding` = 1;
