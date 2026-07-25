-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 09 Bulan Mei 2026 pada 22.49
-- Versi server: 10.6.25-MariaDB-cll-lve
-- Versi PHP: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_emindtutor`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `target_audience` enum('all','companies','teachers','students') NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `target_audience`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'PERCUBAAN', 'ASSALAMUALAIKUM', 'teachers', 94, '2026-04-13 17:54:58', '2026-04-13 17:54:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `announcement_user`
--

CREATE TABLE `announcement_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `announcement_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `announcement_user`
--

INSERT INTO `announcement_user` (`id`, `announcement_id`, `user_id`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 95, 1, '2026-04-13 17:54:58', '2026-04-15 19:23:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `broadcasts`
--

CREATE TABLE `broadcasts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_schedules`
--

CREATE TABLE `class_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `teacher_id` int(10) UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `link_group` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `allowance_type` enum('per_month','per_session','per_hour') DEFAULT NULL,
  `allowance` decimal(10,2) DEFAULT NULL,
  `status` enum('selesai','akan_datang','batal') NOT NULL DEFAULT 'akan_datang',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `class_schedules`
--

INSERT INTO `class_schedules` (`id`, `company_id`, `course_id`, `teacher_id`, `nama_kelas`, `link_group`, `notes`, `allowance_type`, `allowance`, `status`, `created_at`, `updated_at`) VALUES
(13, 17, 19, 16, 'PERCUBAAN - Ezzat Qaisar', NULL, NULL, 'per_hour', 20.00, 'akan_datang', '2026-04-13 17:48:21', '2026-04-13 17:48:21'),
(18, 17, 20, 16, 'KELAS PENGAJIAN AL QURAN DAN IQRA\' PROMOSI RM 60.00 - contoh_final', NULL, NULL, NULL, NULL, 'akan_datang', '2026-05-07 01:04:58', '2026-05-07 01:04:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_schedule_days`
--

CREATE TABLE `class_schedule_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `hari` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `jam_mulai` time NOT NULL,
  `durasi_menit` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `class_schedule_days`
--

INSERT INTO `class_schedule_days` (`id`, `class_schedule_id`, `hari`, `jam_mulai`, `durasi_menit`, `created_at`, `updated_at`) VALUES
(45, 13, 'Tuesday', '15:20:00', 30, '2026-04-13 17:48:21', '2026-04-13 17:48:21'),
(50, 18, 'Monday', '12:12:00', 60, '2026-05-07 01:04:58', '2026-05-07 01:04:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_schedule_student`
--

CREATE TABLE `class_schedule_student` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `class_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `class_schedule_student`
--

INSERT INTO `class_schedule_student` (`id`, `student_id`, `class_schedule_id`, `created_at`, `updated_at`) VALUES
(26, 45, 13, '2026-04-13 17:48:21', '2026-04-13 17:48:21'),
(27, 49, 18, '2026-05-07 01:04:58', '2026-05-07 01:04:58'),
(28, 46, 18, '2026-05-07 01:09:09', '2026-05-07 01:09:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logo_syarikat` varchar(255) DEFAULT NULL,
  `nombor_pendaftaran_syarikat` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_wakil_syarikat` varchar(255) NOT NULL,
  `pic_email` varchar(255) DEFAULT NULL,
  `pic_telefon` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_telefon` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `companies`
--

INSERT INTO `companies` (`id`, `logo_syarikat`, `nombor_pendaftaran_syarikat`, `user_id`, `nama_wakil_syarikat`, `pic_email`, `pic_telefon`, `alamat`, `no_telefon`, `status`, `link`, `created_at`, `updated_at`) VALUES
(16, NULL, 'EMT-0001', 93, 'alifa', NULL, '098765432', 'jalan itulah', '081374292558', 1, NULL, '2026-04-12 03:16:35', '2026-04-12 03:16:35'),
(17, NULL, 'EMT-0002', 94, 'EZZAT QAISAR BIN ROSLI', NULL, '01165543557', 'NO 113 LORONG MELOR 1/3,\r\nTAMAN MELOR INDAH, \r\n09400 PADANG SERAI, \r\nKEDAH.', '0103306210', 1, NULL, '2026-04-13 17:13:20', '2026-04-13 17:13:20'),
(18, NULL, 'EMT-0003', 100, 'Dragon', NULL, '01114282819', '1 Jalan Rambutan', '123123123', 1, NULL, '2026-04-27 07:58:31', '2026-04-27 07:58:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `company_bank_details`
--

CREATE TABLE `company_bank_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `nama_bank` varchar(255) NOT NULL,
  `nombor_akaun_bank` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `company_bank_details`
--

INSERT INTO `company_bank_details` (`id`, `company_id`, `nama_bank`, `nombor_akaun_bank`, `created_at`, `updated_at`) VALUES
(1, 17, 'MAYBANK', '552107697006', '2026-04-13 19:45:49', '2026-04-13 19:45:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `company_invoices`
--

CREATE TABLE `company_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `package_id` varchar(255) DEFAULT NULL,
  `nomor_invoice` varchar(255) NOT NULL,
  `external_id` varchar(255) DEFAULT NULL,
  `xendit_id` varchar(255) DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `xendit_invoice_url` varchar(255) DEFAULT NULL,
  `status_bayaran` varchar(20) NOT NULL,
  `tarikh_terbit` date DEFAULT NULL,
  `tarikh_tamat` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reminder_expired_sent` tinyint(1) NOT NULL DEFAULT 0,
  `reminder_3_days_sent` tinyint(1) NOT NULL DEFAULT 0,
  `reminder_7_days_processed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `company_invoices`
--

INSERT INTO `company_invoices` (`id`, `company_id`, `package_id`, `nomor_invoice`, `external_id`, `xendit_id`, `jumlah`, `xendit_invoice_url`, `status_bayaran`, `tarikh_terbit`, `tarikh_tamat`, `status`, `created_at`, `updated_at`, `reminder_expired_sent`, `reminder_3_days_sent`, `reminder_7_days_processed`) VALUES
(134, 16, '18', 'INV-20260412-0001-TRIAL', 'EMT-20260412-0001-1775989443-TRIAL', NULL, 0, NULL, 'paid', '2026-04-12', '2026-05-12', 'active', '2026-04-12 03:24:03', '2026-04-12 03:24:03', 0, 0, 0),
(135, 17, '18', 'INV-20260414-0001-TRIAL', 'EMT-20260414-0001-1776125667-TRIAL', NULL, 0, NULL, 'paid', '2026-04-14', '2026-05-14', 'active', '2026-04-13 17:14:27', '2026-04-13 17:14:27', 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `company_payments`
--

CREATE TABLE `company_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_invoice_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `dibayar_pada` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `company_payments`
--

INSERT INTO `company_payments` (`id`, `company_invoice_id`, `jumlah`, `status`, `transaction_id`, `payment_method`, `dibayar_pada`, `created_at`, `updated_at`) VALUES
(1, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:23:37', '2025-09-01 15:23:37'),
(2, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:24:04', '2025-09-01 15:24:04'),
(3, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:24:33', '2025-09-01 15:24:33'),
(4, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:08', '2025-09-01 15:25:08'),
(5, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:12', '2025-09-01 15:25:12'),
(6, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:17', '2025-09-01 15:25:17'),
(7, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:20', '2025-09-01 15:25:20'),
(8, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:27', '2025-09-01 15:25:27'),
(9, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:30', '2025-09-01 15:25:30'),
(10, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:33', '2025-09-01 15:25:33'),
(11, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:46', '2025-09-01 15:25:46'),
(12, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:49', '2025-09-01 15:25:49'),
(13, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:52', '2025-09-01 15:25:52'),
(14, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:55', '2025-09-01 15:25:55'),
(15, 19, 50, 'pending', NULL, NULL, NULL, '2025-09-01 15:25:59', '2025-09-01 15:25:59'),
(16, 76, 50, 'pending', NULL, NULL, NULL, '2025-09-03 04:50:39', '2025-09-03 04:50:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `company_reviews`
--

CREATE TABLE `company_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `company_reviews`
--

INSERT INTO `company_reviews` (`id`, `company_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 5, NULL, '2026-02-04 00:15:00', '2026-02-04 00:15:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `nama_kos` varchar(255) NOT NULL,
  `fee` int(11) NOT NULL,
  `max_student` int(11) NOT NULL,
  `charge_on_registration` tinyint(1) NOT NULL DEFAULT 0,
  `include_registration_fee` tinyint(1) NOT NULL DEFAULT 0,
  `teaching_mode` enum('offline','online','hybrid') NOT NULL,
  `minimum_sessions` int(11) DEFAULT NULL,
  `minimum_sessions_unit` enum('session','hour','minute') DEFAULT NULL,
  `sessions_duration` int(11) DEFAULT NULL,
  `sessions_duration_unit` enum('minute','hour') NOT NULL,
  `description` text DEFAULT NULL,
  `instructions_template` text DEFAULT NULL,
  `session_notes_template` text DEFAULT NULL,
  `jenis` enum('individu','kumpulan') NOT NULL,
  `registration_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `courses`
--

INSERT INTO `courses` (`id`, `company_id`, `nama_kos`, `fee`, `max_student`, `charge_on_registration`, `include_registration_fee`, `teaching_mode`, `minimum_sessions`, `minimum_sessions_unit`, `sessions_duration`, `sessions_duration_unit`, `description`, `instructions_template`, `session_notes_template`, `jenis`, `registration_link`, `created_at`, `updated_at`) VALUES
(19, 17, 'PERCUBAAN', 600, 1, 0, 0, 'online', 4, 'session', 30, 'minute', NULL, NULL, NULL, 'individu', 'https://emindtutor.com/admin/smartquran', '2026-04-13 17:45:37', '2026-04-13 17:45:37'),
(20, 17, 'KELAS PENGAJIAN AL QURAN DAN IQRA\' PROMOSI RM 60.00', 60, 20, 0, 0, 'online', NULL, 'session', 2, 'hour', NULL, NULL, 'LAPORAN PENGAJIAN 📝\r\n\r\nTarikh :\r\nMasa :\r\nModul / Muka Surat :\r\nKomen Guru : \r\n\r\nSmart Quran Malaysia', 'kumpulan', 'https://emindtutor.com/admin/kursus/tambah', '2026-04-13 19:02:58', '2026-04-13 19:03:46'),
(21, 16, 'kelas', 20, 1, 0, 1, 'online', 4, 'session', 60, 'minute', NULL, NULL, NULL, 'individu', 'https://www.youtube.com/', '2026-04-24 08:54:47', '2026-04-24 08:54:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_diskon` varchar(255) NOT NULL,
  `kode_diskon` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `percent` decimal(5,2) DEFAULT NULL,
  `potongan` int(11) DEFAULT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"5cf0e1a1-bdf8-4d05-9d32-273aa7889c40\",\"displayName\":\"App\\\\Mail\\\\AnnouncementEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\AnnouncementEmail\\\":5:{s:12:\\\"announcement\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Announcement\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:6:\\\"sender\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:94;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:9:\\\"recipient\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:95;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:23:\\\"ezzatqaisar98@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"},\"createdAt\":1776128098,\"delay\":null}', 0, NULL, 1776128098, 1776128098);

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(20, '2025_07_23_153420_create_companies_table', 4),
(21, '2025_07_25_083452_create_subscription_packages_table', 4),
(22, '2025_07_25_084046_create_subscriptions_table', 4),
(24, '2025_07_25_084113_create_discounts_table', 4),
(26, '2025_07_29_154937_add_pic_fields_to_companies_table', 5),
(30, '2025_07_29_162022_create_courses_table', 6),
(35, '2025_07_30_020535_create_teacher_rules_table', 7),
(36, '2025_07_30_025040_create_teacher_reviews_table', 8),
(39, '2025_07_30_015935_create_teachers_table', 9),
(41, '2025_07_30_152044_create_students_table', 10),
(44, '2025_07_31_020447_create_class_schedule_student_table', 11),
(47, '2025_07_31_021605_create_student_invoices_table', 12),
(49, '2025_07_31_032415_create_class_schedule_days_table', 13),
(51, '2025_07_23_090618_create_teacher_attendances_table', 14),
(54, '2025_07_31_044414_add_max_student_field_to_course_table', 16),
(56, '2025_08_24_130918_add_company_id_to_teacher_rules_table', 18),
(57, '2025_08_26_121438_add_student_invoice_id_to_teacher_reviews_table', 19),
(60, '2025_08_26_145709_create_teacher_unavailabilities_table', 20),
(64, '2025_07_23_090007_create_class_schedules_table', 21),
(66, '2025_08_29_055336_create_staff_table', 22),
(67, '2025_07_25_084057_create_invoices_table', 23),
(69, '2025_08_29_090227_create_staff_emind_table', 24),
(76, '2025_07_23_090626_create_student_attendances_table', 25),
(80, '2025_09_01_221739_add_snap_token_to_company_invoices_table', 27),
(81, '2025_07_25_084143_create_payments_table', 28),
(82, '2025_08_28_161610_create_reviews_table', 29),
(85, '2025_07_31_110554_create_sallaries_table', 30),
(86, '2025_09_03_134230_add_package_id_to_company_invoices_table', 31),
(87, '2025_09_03_173822_add_status_to_company_invoices_table', 32),
(89, '2025_09_04_070932_alter_tarikh_nullable_on_company_invoices_table', 33),
(90, '2025_09_04_121926_create_activity_logs_table', 34),
(91, '2025_09_04_180534_alter_tarikh_nullable_on_student_invoices_table', 35),
(92, '2025_07_31_021613_create_student_payments_table', 36),
(96, '2025_09_11_012849_create_broadcasts_table', 37),
(97, '2025_09_11_053020_add_link_to_companies_table', 38);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 0,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `reviews`
--

INSERT INTO `reviews` (`id`, `student_id`, `teacher_id`, `course_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(10, 45, 16, 19, 5, NULL, '2026-04-13 18:04:10', '2026-04-13 18:04:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sallaries`
--

CREATE TABLE `sallaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `class_schedule_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `month` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `status` enum('pending','paid') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sallaries`
--

INSERT INTO `sallaries` (`id`, `teacher_id`, `staff_id`, `class_schedule_id`, `amount`, `description`, `month`, `year`, `payment_date`, `status`, `created_at`, `updated_at`) VALUES
(19, 16, NULL, 13, 10.00, NULL, 'April', '2026', '2026-04-14', 'pending', '2026-04-13 17:52:15', '2026-04-13 17:52:15'),
(20, 16, NULL, 13, 10.00, NULL, 'April', '2026', '2026-04-19', 'pending', '2026-04-18 20:09:21', '2026-04-18 20:09:21'),
(21, 16, NULL, NULL, 0.35, 'Gaji guru bulan 4 2026 - 2 sesi (0 jam)', '4', '2026', '2026-04-28', 'paid', '2026-04-27 18:47:22', '2026-04-27 18:47:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0q9vzk4eGGFAXgvByNBFM41BVlQu2paoLj4Eq2CS', NULL, '46.224.208.228', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSUNkTjhFS1hmS3NsM1JkMVBXUFBodDdxV3ZsT2x4N09TQU5iU001USI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778284467),
('6M1bD3tfGXtA1egrsPKNYsqdRp4fTFKvQLJ8FjuG', NULL, '146.56.199.139', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYmpmWDU0YjFrSlBvN2xxNHJiV2haSnRtYmJJQ0d5cnBFaU9oVEw1SCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vZW1pbmR0dXRvci5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778337627),
('8pRwbzyLq3vekDXU4Ws5AndQoJqBVT1L4KFbDRyJ', NULL, '137.184.15.181', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibll5TzJ5anM2Yzg0NGJVZjNTWDRaY3lnMTNoMVV4NW56U0VNTFlIbSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwczovL21haWwuZW1pbmR0dXRvci5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778138518),
('8WbZ3DLhOw4CsHHn5ivWZrJMGKJcHR1lB1iviCjE', NULL, '49.51.180.2', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN3FtV2k1c2VRdW90V3I0dm8yajJlUXRRZ3FtYVkxWlZ4R1JMRHJwZiI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI1OiJodHRwOi8vd3d3LmVtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778148447),
('9LAidhZlq2Htz4shETAQRqWywRLfQrpQ2Y9mdMli', NULL, '198.244.240.114', 'Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUWhTNFUxbFZ2OTFqUXdzNHVyZWs0b3V5MW9XMktjaXpiY3NERnZsZiI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2VtaW5kdHV0b3IuY29tL2NvbXBhbmllcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778212751),
('ABiJyuWdBbssyMdtnJI35LXe4qxrr1QahZak6cex', NULL, '40.77.167.230', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUGI4cTFDQkUzYnR3d3BRSVlORGozN2dFMkc4aGV0dnp1TjZPWGdiTCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2VtaW5kdHV0b3IuY29tL2Fib3V0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778281031),
('agxWwdutzGnhPRA1AE5qIh2kmknPXnu3EAU2BaUL', NULL, '43.204.131.127', 'Go-http-client/1.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ3BXQloxSnRNellRRHZlMVhwR05UaUtKT1VIblVCdUpuWjdTVnZpNyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778240971),
('awAQ40OFFFTNYpfXf7CaJ0s9CY8r9bhHSQlJxyOX', NULL, '43.159.46.41', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRWtBZG1CMlRTZW9WVGtZUlVWTGRQQmdUMXc5ZEhlNkd4SzYwUVZZNSI7czo2OiJsb2NhbGUiO3M6MjoibXkiO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778325851),
('axo9E3eYMPGkyuP1ibrn1J99JV9vrHgFuF1tApFg', NULL, '114.122.114.187', 'WhatsApp/2.2616.100 W', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibWpUb0pqVWlSMGZvNEptcW1XVXlFOWoyRXU3QUlGblZxY0hFRWVSViI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cHM6Ly9lbWluZHR1dG9yLmNvbS9sb2dvdXQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyODoiaHR0cHM6Ly9lbWluZHR1dG9yLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778340787),
('bot06tiVz0JKboBjSwfdKwRTM4AyeO9gpYy45nBZ', NULL, '161.35.80.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMGlwbmdOS2dGdUtFeWRhcVBCWlk1NFFYTjZpcWROakt6UHJielZPaCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778199600),
('bVFmxp0HAarSC5STXwjD66macLYzvhUyJvfH8JY9', NULL, '200.75.226.76', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTjVuQVVsd3Jtc1VWNTNQUFM3VTRWRnVjN3FlN3BPSGI0QzkyeXNzaSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cHM6Ly9lbWluZHR1dG9yLmNvbS9hZG1pbi9rdXJzdXMvdGFtYmFoIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHBzOi8vZW1pbmR0dXRvci5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778167742),
('ddTcJbDMAWr9SCvhzStiVwdPdi0O8oCYHvpXLH4W', NULL, '43.156.66.8', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS0trZ1hkcHprNGtvSGUyR0xQYm81VG1yRUQ3dHRHR2I4TVcxMXFyOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHBzOi8vZW1pbmR0dXRvci5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778327051),
('DH5AerJeindoMW5EKJe9OtjWRatdTIVTS45DjEXP', NULL, '161.35.80.10', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSGkwWlBaeTVOTWdldXd1WVlwdlBNUElPVkdMN0EwdFhFNmh2ZFNncCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vZW1pbmR0dXRvci5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778199598),
('fJvRVYtIEkbBT8iSddSWKism1qw8tmK0kg3h9wBx', NULL, '120.140.8.215', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTUhvYjZQSFJxRU1oOFZleU1iaXh6QUt3STFVQno5VU1OSTBCcDdvTyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cHM6Ly9lbWluZHR1dG9yLmNvbS9sb2dvdXQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyODoiaHR0cHM6Ly9lbWluZHR1dG9yLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778340874),
('FVE0jIoBUlQYT4iv1mzAheZwqzhv1EZwlYqk1s6i', NULL, '180.153.236.68', 'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0; 360Spider', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieWRveXRFMHd5UGZYMnZ1SHJFQ3Y2U0VqeGdsSnF2SWJjRk1MeXJ0diI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vZW1pbmR0dXRvci5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778135786),
('FyxoI1zXjqZv4XdTuIN4LzDiASoBX03cmrWjyMIN', NULL, '216.244.66.245', 'Mozilla/5.0 (compatible; DotBot/1.2; +https://opensiteexplorer.org/dotbot; help@moz.com)', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTWNZZ0tiR3YyNmlhc3cyWEFNT0JWRUV2bngwUG1ybThmZ1I2b3FObSI7czoxNDoiZnJvbV9zdWJzY3JpYmUiO2I6MTtzOjE2OiJzZWxlY3RlZF9wYWNrYWdlIjtzOjI6IjE4IjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovL2VtaW5kdHV0b3IuY29tL3N1YnNjcmliZS9zdGFydC8xOCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778336091),
('ifv6gFklPYX73SpZj2bnGV8irlmr2fBvpA2o0voS', NULL, '3.233.88.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSzNzekpVOXRrRkZYYTdFb0R1ME94dG1ybFp6YmNleU1DdzhjbGZHdSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI2OiJodHRwczovL3d3dy5lbWluZHR1dG9yLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778337436),
('JpMSKwASsFwjmneiNHUUlKhMEoZZK06m6bzb1E4w', NULL, '114.122.103.247', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWDk1eW5kaFpITGlRclJpdmRUR1B4ZTM0eVdKSktiWXNrRzBkcGY1NCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778340777),
('MCVWrSqmX9url3xBA0hJ4HJGqeN5wkkthAFFVJKb', NULL, '181.41.206.224', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZVhDWnJsTW9GWndURUdMMXBYdXdnVXljZHZ1RmxNWXhSRlVPcUEyZiI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI1OiJodHRwOi8vd3d3LmVtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778160637),
('mSorizXvGGn786SrjLUg7KlwxLtJoDTaQ9v0gxhR', NULL, '43.156.127.60', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTk1SS0FsTlZ6Mmd0UVpKbzlPN1IxZ0c3QmlwMFh2eHkwOG9TM0ZSTyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778324027),
('nfoDMclLwequ8FhpbDtzPdE3SCdUQHjLU4dY5xpt', 94, '60.50.63.181', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoib2Fobmo5T2J0aGdNbjFRR0NJU0NsZmlyaFpCRjJkc0UwV3VPS3dqcSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyODoiaHR0cHM6Ly9lbWluZHR1dG9yLmNvbS9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQwOiJodHRwczovL2VtaW5kdHV0b3IuY29tL2FkbWluL3N0dWRlbnRzLzQ2Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzNkYzdhOTEzZWY1ZmQ0Yjg5MGVjYWJlMzQ4NzA4NTU3M2UxNmNmODIiO2k6OTQ7czo2OiJsb2NhbGUiO3M6MjoiZW4iO30=', 1778141357),
('nZm9fDD46bQZx9uPtMtKn9TUfTuwYnGXi729wQFX', NULL, '198.244.240.124', 'Mozilla/5.0 (compatible; AhrefsBot/7.0; +http://ahrefs.com/robot/)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRFJvQmQ1eEtNNlA1NTgwUjRabjRyYUc3dkdDeHR0ZVJhMTlwTmNZbyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778242492),
('OiBOyGAiyUnjvnpiibGzi5nUWBajouDGGLq4GdyG', NULL, '129.226.210.142', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNkNIcmdMaTFIa2hpWG1XZ1pMZkN2RE5XUExldFhjTVF2dkU4RzE2ViI7czoxNDoiZnJvbV9zdWJzY3JpYmUiO2I6MTtzOjE2OiJzZWxlY3RlZF9wYWNrYWdlIjtzOjI6IjE4IjtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyOToiaHR0cHM6Ly9lbWluZHR1dG9yLmNvbS9kYWZ0YXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778327650),
('OVIrabE0vpMTmMUnYEfKyhM2HIhP3e2Qywe8jcvb', NULL, '66.249.71.135', 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRDNXWTIyY0ZrM0xVUEVrNkFwN1dPUlJadm9yTTVvUkN4Mk1GWUVWdyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778201064),
('PBRRgyl9o0TeavN1e00JUYr3gesD9eRWHvZ5MjGT', NULL, '34.145.140.20', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYTFkZ2lRcXByU1hPejVBNHRiZFp3ZGhtMEE2QVk2Z1RQYlYyM0FycSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vZW1pbmR0dXRvci5jb20iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1778211452),
('pEjsvwSGr6ZAiyZhmhmgNsTGbTDw8WqQgSYjNskW', NULL, '38.68.69.254', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHVCNTBpME43bERHV3Y5YkNQZkNCblNTMmV5dG5ScjEyU1RWSDlZTyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778340520),
('PzyUd0Z02lMHQCs43fnX4Se5OBTbeM4GxjJn9wk2', NULL, '114.122.114.187', 'WhatsApp/2.2616.100 W', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicHZHZFA5cWkzMk1BeGhHU3BsTk9EMnR1ckNoNFpGRFZHT1J5YXJCciI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cHM6Ly9lbWluZHR1dG9yLmNvbS9sb2dvdXQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyODoiaHR0cHM6Ly9lbWluZHR1dG9yLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778340872),
('QHWYLX2kD3WgAGAWAohms3cVpqBwpgZwvyp8K2iF', 94, '125.163.75.76', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRmdXMTVzdThwRzljdDJVQVlLZGF0Vk96dHRObjV5S1V6UTlQQ1VpMCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzNkYzdhOTEzZWY1ZmQ0Yjg5MGVjYWJlMzQ4NzA4NTU3M2UxNmNmODIiO2k6OTQ7fQ==', 1778141207),
('RRcu0TMXOso41n6PJogQ926E0SELMLbQrP0FEBwc', NULL, '43.133.60.72', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVE1xeTBndW96RHRWSTY1MDBFTFpGNWI3aUtTcFBmZkNvNG5mMWxKaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vZW1pbmR0dXRvci5jb20vZGFmdGFyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778326451),
('U1ufw3Ese9BZhsqmr7g3C5V6k47NozuQR5kf0D8Z', NULL, '5.189.156.109', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaU5Fa0lhcDRnRlNzd250VnJyZU1CWlJETldHaGd3ckU0aVFGRVZkZyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778284466),
('U7wYBfJskl2Gyyj8XksMJG5cWTFDHUbbOO76l3F0', NULL, '66.249.66.168', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWnh2Zmo4ZmZyclFMcGZlVnR6dXdvU2h0b3ZYUG5IbmV2Y3VmQXJCdSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778201064),
('uIPt0JJNHPZNM8g4L9OxkwE7P9kgPEo4n40Nw1g2', NULL, '43.165.195.234', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTU5ucWQ3V0g5ekIzT3ZDQUZXSlVSSEVPNTZaRGRmc3dEaUw0WmNKYyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI1OiJodHRwOi8vd3d3LmVtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778131964),
('WPP5l92sBGMXBdtjZnO1lhSHmGHBZCgSkMngoR7b', NULL, '5.133.192.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaE1PQ2VXWkhoVjlVMVBLMzRhS2VudDN1Q0FDMTRuUE5LaXVqbFR6MCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778188055),
('x6f1d1UxsnHvdcoxWNtRR0YiCkh6GM8hEdolhNjo', NULL, '43.134.5.186', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY0N5OFBQeVJHNmpUY25xUkdEaU5IZGR0N2dZTHV2RVBwTXlTOWhkTSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778325254),
('Y5KZHfz3ovFjNqzqjPRzZzdeVdbyoMh9F2v9d0CQ', NULL, '114.122.114.187', 'WhatsApp/2.2616.100 W', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVFFtSFZwYmRld0ZKOWJsb3UzQTdkeDBEN1VsQUtLZHdDeENYWWc1YSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL2VtaW5kdHV0b3IuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1778340785),
('YW1ztqMb0DjvXj9fnEL6uAQXLXaORzbPHNa2iu6p', NULL, '137.184.15.181', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYkZHTWpCV3pRSENLaFhGREl5VzVLMkFKRkttbHJTMzh1aW5CWUk4WCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI2OiJodHRwOi8vbWFpbC5lbWluZHR1dG9yLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1778138516);

-- --------------------------------------------------------

--
-- Struktur dari tabel `staff`
--

CREATE TABLE `staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) NOT NULL,
  `ic_number` varchar(255) NOT NULL,
  `allowance` int(11) NOT NULL,
  `allowance_type` enum('per_hour','per_session','per_month') NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_account_number` varchar(255) NOT NULL,
  `bank_account_holder` varchar(255) NOT NULL,
  `account_holders_ic_number` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `staff_emind`
--

CREATE TABLE `staff_emind` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) NOT NULL,
  `ic_number` varchar(255) NOT NULL,
  `allowance` int(11) NOT NULL,
  `allowance_type` enum('per_hour','per_session','per_month') NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_account_number` varchar(255) NOT NULL,
  `bank_account_holder` varchar(255) NOT NULL,
  `account_holders_ic_number` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `ic_number` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `sponsor_name` varchar(255) DEFAULT NULL,
  `sponsor_phone` varchar(255) DEFAULT NULL,
  `sponsor_email` varchar(255) DEFAULT NULL,
  `is_approved` enum('yes','no') NOT NULL DEFAULT 'no',
  `status` enum('active','expired','pending') DEFAULT 'pending',
  `sub_start` date DEFAULT NULL,
  `sub_end` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `students`
--

INSERT INTO `students` (`id`, `user_id`, `company_id`, `ic_number`, `phone`, `address`, `notes`, `sponsor_name`, `sponsor_phone`, `sponsor_email`, `is_approved`, `status`, `sub_start`, `sub_end`, `created_at`, `updated_at`) VALUES
(45, 96, 17, '01165543557', '01165543557', NULL, NULL, NULL, NULL, NULL, 'yes', 'pending', NULL, NULL, '2026-04-13 17:46:33', '2026-04-13 17:48:21'),
(46, 98, 17, '0126124452', '0126124452', NULL, NULL, NULL, NULL, NULL, 'yes', 'active', NULL, NULL, '2026-04-13 19:04:49', '2026-05-07 01:09:09'),
(47, 99, 16, '1234567890', '1234567890', NULL, NULL, NULL, NULL, NULL, 'no', 'pending', NULL, NULL, '2026-04-24 08:55:33', '2026-04-24 08:55:33'),
(48, 101, 16, '09876567890', '09876543', NULL, NULL, NULL, NULL, NULL, 'no', 'pending', NULL, NULL, '2026-05-05 02:18:19', '2026-05-05 02:18:19'),
(49, 102, 17, '12345678990', '098765432', NULL, NULL, NULL, NULL, NULL, 'yes', 'active', NULL, NULL, '2026-05-06 21:03:55', '2026-05-07 01:04:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_attendances`
--

CREATE TABLE `student_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `class_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `attendance_time` time NOT NULL,
  `status` enum('present','absent','late') DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_invoices`
--

CREATE TABLE `student_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `nomor_invoice` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `status_bayaran` enum('paid','overdue','upcoming') NOT NULL DEFAULT 'upcoming',
  `tarikh_terbit` date DEFAULT NULL,
  `tarikh_tamat` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `student_invoices`
--

INSERT INTO `student_invoices` (`id`, `student_id`, `course_id`, `nomor_invoice`, `jumlah`, `status_bayaran`, `tarikh_terbit`, `tarikh_tamat`, `created_at`, `updated_at`) VALUES
(33, 45, 19, 'EMT-20260414-170001', 600, 'paid', '2026-04-14', '2026-05-14', '2026-04-13 17:46:33', '2026-04-13 17:48:21'),
(34, 46, 20, 'EMT-20260414-170002', 60, 'paid', '2026-05-07', '2026-06-06', '2026-04-13 19:04:49', '2026-05-07 01:09:09'),
(35, 47, 21, 'EMT-20260424-160001', 20, 'paid', '2026-04-28', '2026-05-28', '2026-04-24 08:55:33', '2026-04-27 18:47:54'),
(36, 48, 21, 'EMT-20260505-160001', 20, 'upcoming', NULL, NULL, '2026-05-05 02:18:19', '2026-05-05 02:18:19'),
(37, 49, 20, 'EMT-20260507-170001', 60, 'paid', '2026-05-07', '2026-06-06', '2026-05-06 21:03:55', '2026-05-07 01:04:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_payments`
--

CREATE TABLE `student_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_invoice_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `dibayar_pada` datetime NOT NULL,
  `bukti_bayar` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `student_payments`
--

INSERT INTO `student_payments` (`id`, `student_invoice_id`, `status`, `dibayar_pada`, `bukti_bayar`, `created_at`, `updated_at`) VALUES
(7, 35, 'success', '2026-04-24 00:00:00', 'bukti_bayar/UFGADdXkTwV4BEuGGhPDE5N0n79GgocFTAJOcM1M.png', '2026-04-24 08:56:36', '2026-04-27 18:47:54'),
(8, 37, 'pending', '2026-05-07 00:00:00', 'bukti_bayar/Ime28hulnsaJRmsHEjWmAfK9mXfYuQU0ExdBbZO3.png', '2026-05-06 21:07:23', '2026-05-06 21:07:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `package_id` int(10) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `company_id`, `package_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-08-29', '2025-09-28', 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `subscription_packages`
--

CREATE TABLE `subscription_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `is_trial` tinyint(1) NOT NULL DEFAULT 0,
  `nama_pakej` varchar(255) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `maks_pelajar` int(11) NOT NULL,
  `maks_guru` int(11) NOT NULL,
  `maks_kelas` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('aktif','tamat') NOT NULL DEFAULT 'aktif',
  `pricing_per_student` tinyint(1) NOT NULL DEFAULT 0,
  `harga_per_pelajar` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `subscription_packages`
--

INSERT INTO `subscription_packages` (`id`, `is_trial`, `nama_pakej`, `duration_days`, `harga`, `maks_pelajar`, `maks_guru`, `maks_kelas`, `deskripsi`, `status`, `pricing_per_student`, `harga_per_pelajar`, `created_at`, `updated_at`) VALUES
(18, 1, 'PERCUBAAN', 30, 0.00, 100, 100, 100, NULL, 'aktif', 0, NULL, '2026-04-11 17:19:12', '2026-04-13 17:32:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_aktif`
--

CREATE TABLE `sub_aktif` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pricing_per_student` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sub_aktif`
--

INSERT INTO `sub_aktif` (`id`, `pricing_per_student`, `created_at`, `updated_at`) VALUES
(2, 1, '2026-04-06 19:19:12', '2026-04-06 19:19:12'),
(3, 1, '2026-04-06 19:19:13', '2026-04-06 19:19:13'),
(4, 1, '2026-04-13 17:32:10', '2026-04-13 17:32:10'),
(5, 1, '2026-04-13 17:32:12', '2026-04-13 17:32:12'),
(6, 1, '2026-04-13 17:32:13', '2026-04-13 17:32:13'),
(7, 1, '2026-04-13 17:32:14', '2026-04-13 17:32:14'),
(8, 1, '2026-04-13 17:32:14', '2026-04-13 17:32:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) NOT NULL,
  `ic_number` varchar(255) NOT NULL,
  `allowance` int(11) NOT NULL,
  `allowance_type` enum('per_hour','per_session','per_month') NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_account_number` varchar(255) NOT NULL,
  `bank_account_holder` varchar(255) NOT NULL,
  `account_holders_ic_number` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `company_id`, `phone`, `ic_number`, `allowance`, `allowance_type`, `bank_name`, `bank_account_number`, `bank_account_holder`, `account_holders_ic_number`, `status`, `created_at`, `updated_at`) VALUES
(16, 95, 17, '01165543557', '050826070669', 20, 'per_hour', 'CIMB BANK', '7636635456', 'NURUL HAZIQAH BINTI HUSSIAN', '940619105560', 'active', '2026-04-13 17:40:22', '2026-04-13 17:40:22'),
(17, 97, 17, '0124515324', '030703020815', 20, 'per_hour', 'MAYBANK BERHAD', '552059852485', 'AHMAD SARHAN BIN DAUD', '030703020815', 'active', '2026-04-13 18:34:59', '2026-04-13 18:34:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teacher_attendances`
--

CREATE TABLE `teacher_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `class_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL,
  `class_photo` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `teacher_attendances`
--

INSERT INTO `teacher_attendances` (`id`, `teacher_id`, `class_schedule_id`, `check_in`, `check_out`, `class_photo`, `note`, `created_at`, `updated_at`) VALUES
(16, 16, 13, '2026-04-14 08:51:32', '2026-04-14 08:52:15', NULL, NULL, '2026-04-13 17:51:32', '2026-04-13 17:52:15'),
(17, 16, 13, '2026-04-19 11:09:01', '2026-04-19 11:09:21', 'class_photos/WsoJR82ZN3DWE7kdkd3FU65EtKx9aA6XlILzYBpW.jpg', NULL, '2026-04-18 20:09:01', '2026-04-18 20:09:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teacher_reviews`
--

CREATE TABLE `teacher_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_invoice_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL COMMENT '1-5',
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `teacher_rules`
--

CREATE TABLE `teacher_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `rule_description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `teacher_rules`
--

INSERT INTO `teacher_rules` (`id`, `company_id`, `rule_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Baca dengan seksama\r\n\r\n1. Wajib bla bla blaaaa\r\n2. Diwajibkan ...\r\n3. Dilirang ... ⚠️\r\n4. Bersikap ...\r\n\r\n❤️ Terima Kasih Atas Perhatiannya 🌹', '2025-08-25 08:49:27', '2025-08-25 09:02:40'),
(2, 14, '5', '2025-11-04 19:13:18', '2025-11-04 19:13:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `teacher_unavailabilities`
--

CREATE TABLE `teacher_unavailabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super admin','admin','teacher','student') NOT NULL DEFAULT 'student',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin eMindTutor', 'superadmin@example.com', NULL, '$2y$12$ACES9IiuXQU632F/BtsxlOWr9ToWAaO8oNQ5pjhDdo98lW33oV1E.', 'super admin', NULL, NULL, '2025-09-04 05:31:45'),
(2, 'Admin', 'admin@example.com', NULL, '$2y$12$a9Cu5FsRoQNhk3en6eKoNeaaFpi0OXi8Ex/dLDA5lLED0cjkieug6', 'admin', NULL, NULL, NULL),
(3, 'Teacher', 'teacher@example.com', NULL, '$2y$12$a9Cu5FsRoQNhk3en6eKoNeaaFpi0OXi8Ex/dLDA5lLED0cjkieug6', 'teacher', NULL, NULL, NULL),
(4, 'Student', 'student@example.com', NULL, '$2y$12$a9Cu5FsRoQNhk3en6eKoNeaaFpi0OXi8Ex/dLDA5lLED0cjkieug6', 'student', NULL, NULL, NULL),
(89, 'Budi', 'budi.test@email.com', NULL, '$2y$12$0zHH/wFhhsi1Zm/CYdJk/uQ/vpPCTGdhXTm3SYMp26NjSe4i3CkmK', 'student', NULL, '2025-12-26 11:44:59', '2025-12-26 11:44:59'),
(90, 'Wonyoung', 'wony@gmail.com', NULL, '$2y$12$wwoePccl3OtnuJjB4KO1P.Ac93EAtBf225qqm5VrZi2YcGJBCC5JC', 'student', NULL, '2025-12-26 12:33:57', '2025-12-26 12:33:57'),
(91, 'Tono', 'tono@email.com', NULL, '$2y$12$4yPBkfUhAkFkoBJm3KwB0.tPOKxn1zbX2EJqJoqDai2pZqgMBWhte', 'student', NULL, '2025-12-26 12:42:53', '2025-12-26 12:42:53'),
(92, 'siapa', 'siapa@gmail.com', NULL, '$2y$12$SibJ9IGMR3z2S9K42SSXku/Al2iwNa.Gm1lagfSkoH1wxtJd8QL26', 'student', NULL, '2025-12-30 07:08:39', '2025-12-30 07:08:39'),
(93, 'contoh', 'mkanzaki0212@gmail.com', NULL, '$2y$12$kmlb6y8H3nKo58WhWn8G.OYh9P81Eqwwrs2jzGVheAr1As4IifZoe', 'admin', NULL, '2026-04-12 03:16:35', '2026-04-12 03:16:35'),
(94, 'SMART QURAN MALAYSIA CENTRE', 'smartqurancentral@gmail.com', NULL, '$2y$12$APe1qS7B5BvT5/CbtQH4WunMma5EWnS6l0kpl5BEnXVSO2regbqVC', 'admin', NULL, '2026-04-13 17:13:20', '2026-04-13 17:13:20'),
(95, 'Ezzat Qaisar Bin Rosli', 'ezzatqaisar98@gmail.com', NULL, '$2y$12$uURgBvKBN9oNrmqCJ7r3MefTTUtAKkxV8eF9epG7PdSfWecOtAlQm', 'teacher', NULL, '2026-04-13 17:40:22', '2026-04-13 17:40:22'),
(96, 'Ezzat Qaisar', 'ezzatqaisar05@gmail.com', NULL, '$2y$12$ALTfVLzxbYZ8OxWn8sbzBuDgwhuYuUqyKi6U2UOaeC7zVYF4t1LPy', 'student', 'PlA6Ue8rFCrbC0X4OnYiFbeS2xBHiAGHKS0k56LRssMrFAWynhrTZ7A7fkV9', '2026-04-13 17:46:33', '2026-04-13 18:01:27'),
(97, 'AHMAD SARHAN BIN DAUD', 'ahmadsarhan9385@gmail.com', NULL, '$2y$12$RM3PuJYxwdTvfk1Qwsmj2e5lLDtAmWki9hOZKSkme5zn3dPIJSPw.', 'teacher', NULL, '2026-04-13 18:34:59', '2026-04-13 18:34:59'),
(98, 'AISY RAYYAN BIN SYAHRIL', 'izrawati.ibrahim@perkeso.gov.my', NULL, '$2y$12$AsU7kiJza.zF.tRhKnUN/eqHuTO2PBP5APLDA0QlDHIZeVfcSG5QW', 'student', NULL, '2026-04-13 19:04:49', '2026-04-13 19:04:49'),
(99, 'aqua', 'aqua@email.com', NULL, '$2y$12$3HiHKJxWu3B7AxqzsYA32Ol5AzXvTOLX.yVrCk8ARshb0pxe/L9Fu', 'student', NULL, '2026-04-24 08:55:33', '2026-05-05 02:15:44'),
(100, 'How To Train Your Cat', 'ordersiapsiap+emindtutor@gmail.com', NULL, '$2y$12$SFWQrvIhr8LeUgHzmml4O.M3IOD6uALuU3ZRRK1UdLX6ffT52omIu', 'admin', NULL, '2026-04-27 07:58:31', '2026-04-27 07:58:31'),
(101, 'Student coba pertama', 'studentcoba1@gmail.com', NULL, '$2y$12$0aLhe0c/Z9k1npFXdS48ju/hUwKN/EfVuqQRfgW.ro809b1T16TaS', 'student', NULL, '2026-05-05 02:18:19', '2026-05-05 02:18:19'),
(102, 'contoh_final', 'contohfinal@gmail.com', NULL, '$2y$12$3IavgsrM0wkEekzGplXv.OQBe7d.NRThF0zUiCzb/dRAllS9IdBFu', 'student', NULL, '2026-05-06 21:03:55', '2026-05-06 21:03:55');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_created_by_foreign` (`created_by`);

--
-- Indeks untuk tabel `announcement_user`
--
ALTER TABLE `announcement_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcement_user_announcement_id_foreign` (`announcement_id`),
  ADD KEY `announcement_user_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `broadcasts`
--
ALTER TABLE `broadcasts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `class_schedule_days`
--
ALTER TABLE `class_schedule_days`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `class_schedule_student`
--
ALTER TABLE `class_schedule_student`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companies_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `company_bank_details`
--
ALTER TABLE `company_bank_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_bank_details_company_id_foreign` (`company_id`);

--
-- Indeks untuk tabel `company_invoices`
--
ALTER TABLE `company_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `company_payments`
--
ALTER TABLE `company_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `company_reviews`
--
ALTER TABLE `company_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sallaries`
--
ALTER TABLE `sallaries`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `staff_emind`
--
ALTER TABLE `staff_emind`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `student_attendances`
--
ALTER TABLE `student_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_attendances_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `student_invoices`
--
ALTER TABLE `student_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `student_payments`
--
ALTER TABLE `student_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `subscription_packages`
--
ALTER TABLE `subscription_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sub_aktif`
--
ALTER TABLE `sub_aktif`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `teacher_attendances`
--
ALTER TABLE `teacher_attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `teacher_reviews`
--
ALTER TABLE `teacher_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `teacher_rules`
--
ALTER TABLE `teacher_rules`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `teacher_unavailabilities`
--
ALTER TABLE `teacher_unavailabilities`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `announcement_user`
--
ALTER TABLE `announcement_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `broadcasts`
--
ALTER TABLE `broadcasts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `class_schedules`
--
ALTER TABLE `class_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `class_schedule_days`
--
ALTER TABLE `class_schedule_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `class_schedule_student`
--
ALTER TABLE `class_schedule_student`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `company_bank_details`
--
ALTER TABLE `company_bank_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `company_invoices`
--
ALTER TABLE `company_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT untuk tabel `company_payments`
--
ALTER TABLE `company_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `company_reviews`
--
ALTER TABLE `company_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT untuk tabel `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `sallaries`
--
ALTER TABLE `sallaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `staff_emind`
--
ALTER TABLE `staff_emind`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `student_attendances`
--
ALTER TABLE `student_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `student_invoices`
--
ALTER TABLE `student_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `student_payments`
--
ALTER TABLE `student_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `subscription_packages`
--
ALTER TABLE `subscription_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `sub_aktif`
--
ALTER TABLE `sub_aktif`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `teacher_attendances`
--
ALTER TABLE `teacher_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `teacher_reviews`
--
ALTER TABLE `teacher_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `teacher_rules`
--
ALTER TABLE `teacher_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `teacher_unavailabilities`
--
ALTER TABLE `teacher_unavailabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `announcement_user`
--
ALTER TABLE `announcement_user`
  ADD CONSTRAINT `announcement_user_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcement_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `company_bank_details`
--
ALTER TABLE `company_bank_details`
  ADD CONSTRAINT `company_bank_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `student_attendances`
--
ALTER TABLE `student_attendances`
  ADD CONSTRAINT `student_attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
