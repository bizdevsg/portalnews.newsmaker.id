-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 18, 2025 at 04:28 PM
-- Server version: 8.0.30
-- PHP Version: 8.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `beritas`
--

CREATE TABLE `beritas` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image1` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image2` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image3` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image4` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image5` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image6` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beritas`
--

INSERT INTO `beritas` (`id`, `title`, `slug`, `content`, `image1`, `image2`, `image3`, `image4`, `image5`, `image6`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Emas Stabil Menanti Keputusan The Fed', '18062025-emas-stabil-menanti-keputusan-the-fed', '<p>Harga emas global bergerak stabil pada Rabu pagi, dengan <strong data-start=\"177\" data-end=\"190\">spot gold</strong> turun tipis 0,2% ke level <strong data-start=\"217\" data-end=\"243\">US$ 3.381,10 per ounce</strong>, dan kontrak berjangka Amerika di kisaran <strong data-start=\"286\" data-end=\"302\">US$ 3.399,30</strong>. <span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">Pelemahan kecil ini terjadi karena pelaku pasar menahan posisi menjelang keputusan suku bunga oleh Federal Reserve (The Fed), serta meningkatnya ketegangan geopolitik di Timur Tengah.</span></p>\r\n<p><code><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">&ldquo;Gold fluctuated as investors tracked the escalation of risk in the Middle East.... Tepid U.S. reports ... bolstered the case for the Fed to cut rates later this year.&rdquo;</span></code></p>\r\n<p data-start=\"460\" data-end=\"497\"><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">Sentimen investor saat ini didorong oleh beberapa faktor:</span></p>\r\n<ul data-start=\"498\" data-end=\"738\">\r\n<li data-start=\"498\" data-end=\"576\">\r\n<p data-start=\"500\" data-end=\"576\"><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\"><strong data-start=\"0\" data-end=\"24\" data-is-only-node=\"\">Tegangan Israel&ndash;Iran</strong> yang memasuki hari ke-6, serta penempatan tambahan pesawat tempur AS di kawasan</span>.</p>\r\n</li>\r\n<li data-start=\"577\" data-end=\"655\">\r\n<p data-start=\"579\" data-end=\"655\"><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">Data-data ekonomi AS yang lemah&mdash;penjualan ritel, output industri, dan sektor properti&mdash;memperkuat harapan pemangkasan suku bunga oleh The Fed</span>.</p>\r\n</li>\r\n<li data-start=\"656\" data-end=\"738\">\r\n<p data-start=\"658\" data-end=\"738\"><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">Rencana harga emas diperkirakan akan naik ke <strong data-start=\"45\" data-end=\"75\">US$ 3.700/oz di akhir 2025</strong>, dan mencapai <strong data-start=\"90\" data-end=\"123\">US$ 4.000/oz pertengahan 2026</strong> menurut Goldman Sachs, didukung oleh pembelian bank sentral dan pelonggaran kebijakan The Fed</span>.</p>\r\n</li>\r\n</ul>\r\n<p data-start=\"740\" data-end=\"903\"><span class=\"relative -mx-px my-[-0.2rem] rounded px-px py-[0.2rem] transition-colors duration-100 ease-in-out\">Secara lokal di Indonesia, harga emas per ounce tercatat berada di kisaran <strong data-start=\"75\" data-end=\"102\">IDR 55,4 juta&ndash;55,9 juta</strong>, atau sekitar <strong data-start=\"117\" data-end=\"143\">IDR 1,78 juta per gram</strong>, dengan kenaikan harian sekitar 0,3&ndash;0,4%</span>. Volatilitas terbatas ini wajar mengingat pasar masih menunggu sinyal dari The Fed.</p>', 'uploads/1750263899_a_67aed98b33fc2.jpg', 'uploads/1750263899_a_679c2de31934f.jpg', 'uploads/1750263899_a_679da7610ef1d.jpg', 'uploads/1750263899_emas-3_169.jpeg', 'uploads/1750263899_pd-loco-london.jpg', 'uploads/1750264012_a_67aed98b33fc2.jpg', 1, '2025-06-18 09:24:59', '2025-06-18 09:26:52');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Gold', 'gold', '2025-06-18 09:05:55', '2025-06-18 09:05:55');

-- --------------------------------------------------------

--
-- Table structure for table `datafeeds`
--

CREATE TABLE `datafeeds` (
  `id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` float DEFAULT NULL,
  `dataset_name` tinyint DEFAULT NULL,
  `data_type` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `penulis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_terbit` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_ebook` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_image` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `economic_calendars`
--

CREATE TABLE `economic_calendars` (
  `id` bigint UNSIGNED NOT NULL,
  `sources` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `measures` text COLLATE utf8mb4_unicode_ci,
  `usual_effect` text COLLATE utf8mb4_unicode_ci,
  `frequency` text COLLATE utf8mb4_unicode_ci,
  `next_released` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `why_trader_care` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `impact` enum('Low','Medium','High') COLLATE utf8mb4_unicode_ci NOT NULL,
  `figures` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `previous` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forecast` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actual` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_200000_add_two_factor_columns_to_users_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_03_23_163443_create_sessions_table', 1),
(6, '2022_05_11_154250_create_datafeeds_table', 1),
(7, '2025_03_20_054419_create_categories_table', 1),
(8, '2025_03_20_054420_create_beritas_table', 1),
(9, '2025_04_10_070127_create_economic_calendars_table', 1),
(10, '2025_05_15_020629_create_pivots_table', 1),
(11, '2025_05_22_023953_create_ebooks_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pivots`
--

CREATE TABLE `pivots` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `open` decimal(10,2) NOT NULL,
  `high` decimal(10,2) NOT NULL,
  `low` decimal(10,2) NOT NULL,
  `close` decimal(10,2) NOT NULL,
  `category` enum('LGD Daily','LSI','HSI Daily','SNI Daily','AUD/USD','EUR/USD','GBP/USD','USD/CHF','USD/JPY') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('mOcmhgPfGYsaof5Jq84YKke6QV1H3nmgRNHZkiNw', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiT0h2d1Nualh6ajlneHZNU3E4bmJPVGxiTkk4cU5DdHRNUGtwY1dKRSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ0OiJodHRwOi8vcG9ydGFsLWJhY2twYW5lbC50ZXN0L2thbGVuZGVyL3RhbWJhaCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTIkVjNKS1ZCV1h1c0pDWFBYNnNweXZPLkRsdUxpQ2w5Z29VRk1xUmR4cUZ5VEx1TFRDUWplUU8iO30=', 1750264081);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Superadmin','Admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Admin',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `role`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'Admin', 'Admin@newsmaker23.id', 'Superadmin', NULL, '$2y$12$V3JKVBWXusJCXPX6spyvO.DluLiCl9goUFMqRdxqFyTLuLTCQjeQO', NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-18 09:05:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beritas`
--
ALTER TABLE `beritas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beritas_category_id_foreign` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `datafeeds`
--
ALTER TABLE `datafeeds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `economic_calendars`
--
ALTER TABLE `economic_calendars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pivots`
--
ALTER TABLE `pivots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beritas`
--
ALTER TABLE `beritas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `datafeeds`
--
ALTER TABLE `datafeeds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `economic_calendars`
--
ALTER TABLE `economic_calendars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pivots`
--
ALTER TABLE `pivots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `beritas`
--
ALTER TABLE `beritas`
  ADD CONSTRAINT `beritas_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
