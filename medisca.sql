SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `system_reports`;
DROP TABLE IF EXISTS `medical_records`;
DROP TABLE IF EXISTS `medicines`;
DROP TABLE IF EXISTS `complaint_categories`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','guru_uks','pihak_sekolah') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pihak_sekolah',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `complaint_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_keluhan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `medicines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_obat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `satuan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_kedaluwarsa` date DEFAULT NULL,
  `status_stok` enum('aman','menipis','kritis') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aman',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `medical_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `petugas_id` bigint unsigned NOT NULL,
  `complaint_category_id` bigint unsigned DEFAULT NULL,
  `nama_pasien` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas_pasien` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keluhan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `medicine_id` bigint unsigned DEFAULT NULL,
  `jumlah_obat` int DEFAULT NULL,
  `tindakan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','sedang_dirawat','selesai','dirujuk') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'selesai',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `medical_records_petugas_id_foreign` (`petugas_id`),
  KEY `medical_records_complaint_category_id_foreign` (`complaint_category_id`),
  KEY `medical_records_medicine_id_foreign` (`medicine_id`),
  CONSTRAINT `medical_records_complaint_category_id_foreign` FOREIGN KEY (`complaint_category_id`) REFERENCES `complaint_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medical_records_medicine_id_foreign` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE SET NULL,
  CONSTRAINT `medical_records_petugas_id_foreign` FOREIGN KEY (`petugas_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `system_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category` enum('bug','error','mencurigakan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','done') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `status_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Perlu Ditinjau',
  `desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `solution` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `google_id`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@medisca.sch.id', NULL, '$2y$10$VK/7xW/3fT1G9R.tESHKL.tw7bfe6PricnSgA3yY/VqD03RM4yKvq', 'admin', NOW(), NOW()),
(2, 'petugas', 'petugas@medisca.sch.id', NULL, '$2y$10$UfRfgODmxeBMzu1yWTIPGucZdcHSjOT1OESqx5RrxVn553xH6CEvy', 'guru_uks', NOW(), NOW()),
(3, 'sekolah', 'sekolah@medisca.sch.id', NULL, '$2y$10$ueP0nlqDwDfKvCp.9cCr2uXvz6InZ1jzLHUId5r4OW6WL5PohdJ4.', 'pihak_sekolah', NOW(), NOW());

INSERT INTO `complaint_categories` (`id`, `nama_keluhan`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Demam timbang tinggi dan berat badan', 'Pemeriksaan suhu dan pengukuran berat/tinggi badan', NOW(), NOW()),
(2, 'Demam, flu, batuk', 'Gejala infeksi saluran pernapasan atas', NOW(), NOW()),
(3, 'Sakit perut', 'Rasa nyeri atau kram pada perut', NOW(), NOW()),
(4, 'Sakit gigi', 'Nyeri pada gigi atau gusi', NOW(), NOW()),
(5, 'Kecelakaan kecil / jatuh', 'Luka lecet, memar akibat terjatuh atau terbentur', NOW(), NOW()),
(6, 'Alergi', 'Reaksi gatal, bentol, atau bersin', NOW(), NOW()),
(7, 'Lambung, maag', 'Nyeri ulu hati, mual, kembung', NOW(), NOW()),
(8, 'Diare', 'BAB cair lebih dari tiga kali sehari', NOW(), NOW()),
(9, 'Kekurangan darah', 'Anemia, pusing, lemas, pucat', NOW(), NOW());

INSERT INTO `medicines` (`id`, `nama_obat`, `stok`, `satuan`, `tgl_kedaluwarsa`, `status_stok`, `created_at`, `updated_at`) VALUES
(1, 'Paracetamol', 24, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(2, 'Tablet Tambah Darah', 40, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(3, 'Entrostop', 8, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'menipis', NOW(), NOW()),
(4, 'Promag', 15, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(5, 'Mefenamic Acid', 12, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(6, 'Cetirizine', 10, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(7, 'Antasida', 18, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(8, 'Diatabs', 9, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(9, 'Ibuprofen', 11, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(10, 'Ambroxol', 7, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'menipis', NOW(), NOW()),
(11, 'Feminax', 14, 'strip', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(12, 'Geliga', 3, 'botol', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'menipis', NOW(), NOW()),
(13, 'Hotin Cream', 4, 'tube', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'menipis', NOW(), NOW()),
(14, 'Fresh Care', 5, 'botol', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(15, 'GPU', 4, 'botol', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'menipis', NOW(), NOW()),
(16, 'Minyak Kayu Putih', 6, 'botol', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(17, 'Koyok Hansaplast', 20, 'sachet', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(18, 'Minyak Tawon', 3, 'botol', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'menipis', NOW(), NOW()),
(19, 'Kasa Hydrophil', 9, 'pak', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(20, 'Bufacetine', 3, 'tube', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'menipis', NOW(), NOW()),
(21, 'Betadine', 5, 'botol', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(22, 'Kapas', 6, 'pak', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW()),
(23, 'Hansaplast', 60, 'pcs', DATE_ADD(CURRENT_DATE, INTERVAL 12 MONTH), 'aman', NOW(), NOW());

INSERT INTO `medical_records` (`id`, `petugas_id`, `complaint_category_id`, `nama_pasien`, `kelas_pasien`, `keluhan`, `medicine_id`, `jumlah_obat`, `tindakan`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Aisyah Putri', 'X IPA 1', 'Demam', 1, 1, 'Kompres hangat dan pemberian paracetamol', 'sedang_dirawat', NOW(), NOW()),
(2, 2, 5, 'Rafi Pratama', 'XI IPS 2', 'Kecelakaan kecil / jatuh', 21, 1, 'Pembersihan luka dan perban', 'selesai', NOW(), NOW()),
(3, 2, 9, 'Nadia Safitri', 'X IPS 1', 'Kekurangan darah', 2, 1, 'Rujukan ke Puskesmas terdekat', 'dirujuk', NOW(), NOW()),
(4, 2, 7, 'Dimas Arya', 'XII IPA 2', 'Lambung, maag', 7, 1, 'Istirahat dan antasida', 'selesai', NOW(), NOW()),
(5, 2, 2, 'Salsa Amelia', 'X IPA 3', 'Demam, flu, batuk', 16, 1, 'Balur minyak kayu putih dan istirahat', 'sedang_dirawat', NOW(), NOW()),
(6, 2, 2, 'Bima Saputra', 'XI IPA 1', 'Demam, flu, batuk', 10, 1, 'Minum air hangat dan ambroxol', 'selesai', NOW(), NOW());

INSERT INTO `system_reports` (`id`, `category`, `category_label`, `title`, `page`, `status`, `status_label`, `desc`, `solution`, `created_at`, `updated_at`) VALUES
(1, 'mencurigakan', 'Mencurigakan', 'Percobaan login gagal 15x berturut-turut', '/login-petugas', 'pending', 'Perlu Ditinjau', 'Ada pengguna atau robot asing dari IP luar negeri mencoba menebak kata sandi akun Guru UKS sebanyak 15 kali.', 'Sistem otomatis mengunci akun sementara selama 15 menit. Anda dapat memblokir IP ini jika terus berlanjut.', NOW(), NOW()),
(2, 'error', 'Error', 'Gagal kirim notifikasi WhatsApp ke wali murid', '/notifikasi-wali', 'pending', 'Perlu Ditinjau', 'Laporan hasil skrining kesehatan siswa semester ini gagal terkirim ke WhatsApp wali murid karena nomor format tidak lengkap.', 'Periksa kembali format nomor WhatsApp siswa (+62) pada data induk siswa.', NOW(), NOW()),
(3, 'bug', 'Bug', 'Tombol \'Kirim Skrining\' tidak bisa diklik di HP', '/skrining-siswa', 'pending', 'Perlu Ditinjau', 'Beberapa siswa melaporkan tombol \'Kirim\' macet saat menggunakan peramban Google Chrome di ponsel Android.', 'Bug tampilan tombol tertutup elemen footer sudah dicatat untuk diperbaiki pengembang.', NOW(), NOW()),
(4, 'mencurigakan', 'Mencurigakan', 'Percobaan input kode aneh (SQL Injection)', '/cari-artikel', 'done', 'Selesai (Diblokir)', 'Seseorang memasukkan karakter simbol dan perintah database berbahaya pada kolom pencarian artikel kesehatan.', 'Sistem keamanan Medisca berhasil memfilter karakter tersebut sehingga tidak membahayakan website.', NOW(), NOW()),
(5, 'error', 'Error', 'Koneksi data Puskesmas lambat (Timeout 502)', '/rujukan-puskesmas', 'done', 'Selesai', 'Server rujukan Puskesmas sempat tidak merespon saat jam sibuk pengiriman data pasien UKS.', 'Koneksi server Puskesmas sudah pulih normal kembali.', NOW(), NOW()),
(6, 'bug', 'Bug', 'Tulisan \'Jadwal Dokter Kecil\' terpotong di layar kecil', '/jadwal-dokter-kecil', 'done', 'Selesai', 'Judul kegiatan dokter kecil terpotong di layar HP berukuran 5 inci.', 'Ukuran font sudah disesuaikan agar pas di semua ukuran layar.', NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
