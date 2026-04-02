-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 02, 2026 at 07:55 AM
-- Server version: 8.0.30
-- PHP Version: 8.4.14

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
  `title_sg` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_rfb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_kpf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_ewf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_bpf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image1` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image2` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image3` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image4` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image5` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image6` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `economic_calendar_category_id` bigint UNSIGNED DEFAULT NULL,
  `sources` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `measures` text COLLATE utf8mb4_unicode_ci,
  `usual_effect` text COLLATE utf8mb4_unicode_ci,
  `frequency` text COLLATE utf8mb4_unicode_ci,
  `next_released` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `isBankHoliday` tinyint(1) DEFAULT '0',
  `bankHolidayNote` text COLLATE utf8mb4_unicode_ci,
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
-- Table structure for table `economic_calendar_categories`
--

CREATE TABLE `economic_calendar_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `impact` enum('Low','Medium','High') COLLATE utf8mb4_unicode_ci NOT NULL,
  `figures` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sources` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `measures` text COLLATE utf8mb4_unicode_ci,
  `usual_effect` text COLLATE utf8mb4_unicode_ci,
  `frequency` text COLLATE utf8mb4_unicode_ci,
  `next_released` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `isBankHoliday` tinyint(1) NOT NULL DEFAULT '0',
  `bankHolidayNote` text COLLATE utf8mb4_unicode_ci,
  `why_trader_care` text COLLATE utf8mb4_unicode_ci,
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
(11, '2025_05_22_023953_create_ebooks_table', 1),
(12, '2025_09_03_063936_add_pt_titles_to_beritas_table', 1),
(13, '2025_09_03_064929_remove_title_backup_from_beritas_table', 1),
(14, '2025_09_04_000000_update_pivots_columns_to_text', 1),
(15, '2025_09_30_093959_add_volume_and_open_interest_to_pivots_table', 1),
(16, '2025_09_30_095055_add_chg_to_pivots_table', 1),
(17, '2026_01_14_084542_add_bank_holiday_to_pivots', 1),
(18, '2026_02_17_120000_add_bank_holiday_to_economic_calendars', 1),
(19, '2026_02_17_123000_make_pivot_ohlc_nullable', 1),
(20, '2026_03_04_000001_create_newsmaker_main_categories_table', 1),
(21, '2026_03_04_000002_create_newsmaker_sub_categories_table', 1),
(22, '2026_03_04_000003_create_newsmaker_articles_table', 1),
(23, '2026_03_04_000004_add_content_en_to_newsmaker_articles_table', 1),
(24, '2026_03_05_090000_create_tiktoks_table', 1),
(25, '2026_03_26_000000_create_economic_calendar_categories_table', 1),
(26, '2026_03_26_000001_add_bank_holiday_to_economic_calendar_categories', 1),
(27, '2026_03_30_094920_add_slug_to_newsmaker_articles_table', 1),
(28, '2026_03_30_095345_refresh_newsmaker_article_slugs_with_date_prefix', 1),
(29, '2026_03_30_110000_create_popup_banners_table', 1),
(30, '2026_03_31_150000_add_modal_html_to_popup_banners_table', 1),
(31, '2026_04_02_000001_create_pasar_indonesia_articles_table', 3),
(32, '2026_04_02_000002_add_user_id_to_beritas_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `newsmaker_articles`
--

CREATE TABLE `newsmaker_articles` (
  `id` bigint UNSIGNED NOT NULL,
  `main_category_id` bigint UNSIGNED NOT NULL,
  `sub_category_id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_id` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsmaker_main_categories`
--

CREATE TABLE `newsmaker_main_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsmaker_sub_categories`
--

CREATE TABLE `newsmaker_sub_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `main_category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- Table structure for table `pasar_indonesia_articles`
--

CREATE TABLE `pasar_indonesia_articles` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_id` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `open` text COLLATE utf8mb4_unicode_ci,
  `high` text COLLATE utf8mb4_unicode_ci,
  `low` text COLLATE utf8mb4_unicode_ci,
  `close` text COLLATE utf8mb4_unicode_ci,
  `chg` text COLLATE utf8mb4_unicode_ci,
  `isBankHoliday` tinyint(1) DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` enum('LGD Daily','LSI','HSI Daily','SNI Daily','AUD/USD','EUR/USD','GBP/USD','USD/CHF','USD/JPY') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `volume` text COLLATE utf8mb4_unicode_ci,
  `open_interest` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `popup_banners`
--

CREATE TABLE `popup_banners` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_label` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_at` timestamp NULL DEFAULT NULL,
  `end_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `modal_html` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `popup_banners`
--

INSERT INTO `popup_banners` (`id`, `title`, `description`, `image`, `cta_label`, `cta_url`, `start_at`, `end_at`, `is_active`, `sort_order`, `created_at`, `updated_at`, `modal_html`) VALUES
(6, 'Download Aplikasi', NULL, NULL, NULL, NULL, '2026-03-31 08:16:00', '2026-04-10 08:16:00', 1, 1, '2026-03-31 08:16:31', '2026-04-02 05:51:11', '<style>\r\n.bias-modal{\r\n  position:relative;\r\n  margin:0 16px;\r\n  width:100%;\r\n  max-width:420px;\r\n  overflow:hidden;\r\n  border-radius:24px;\r\n  background:white;\r\n  box-shadow:0 25px 60px rgba(0,0,0,0.15);\r\n  font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;\r\n}\r\n\r\n/* close button */\r\n.bias-close{\r\n  position:absolute;\r\n  top:12px;\r\n  right:12px;\r\n  font-size:20px;\r\n  color:#9ca3af;\r\n  border:none;\r\n  background:none;\r\n  cursor:pointer;\r\n  transition:.2s;\r\n}\r\n\r\n.bias-close:hover{\r\n  color:#4b5563;\r\n}\r\n\r\n/* content */\r\n.bias-content{\r\n  padding:32px;\r\n  text-align:center;\r\n}\r\n\r\n/* logo wrapper */\r\n.bias-logo-wrap{\r\n  margin:0 auto 20px auto;\r\n  width:fit-content;\r\n  height:fit-content;\r\n}\r\n\r\n/* logo */\r\n.bias-logo{\r\n  height:160px;\r\n  border-radius:24px;\r\n  object-fit:cover;\r\n  object-position:center;\r\n  box-shadow:0 20px 40px rgba(0,0,0,0.15);\r\n}\r\n\r\n/* title */\r\n.bias-title{\r\n  margin-bottom:8px;\r\n  font-size:24px;\r\n  font-weight:700;\r\n  color:#1f2937;\r\n}\r\n\r\n/* subtitle */\r\n.bias-desc{\r\n  margin-bottom:24px;\r\n  color:#4b5563;\r\n  line-height:1.6;\r\n  font-size:15px;\r\n}\r\n\r\n/* button wrapper */\r\n.bias-actions{\r\n  display:flex;\r\n  flex-direction:column;\r\n  align-items:center;\r\n  gap:12px;\r\n}\r\n\r\n/* playstore img */\r\n.bias-playstore{\r\n  height:56px;\r\n  transition:.25s;\r\n}\r\n\r\n.bias-playstore:hover{\r\n  transform:scale(1.05);\r\n}\r\n</style>\r\n\r\n\r\n<div class=\"bias-modal\">\r\n\r\n  <button class=\"bias-close\" id=\"close\">&times;</button>\r\n\r\n  <div class=\"bias-content\">\r\n\r\n    <div class=\"bias-logo-wrap\">\r\n      <img \r\n        src=\"https://newsmaker.id/images/Logo_BIAS23.png\"\r\n        alt=\"Logo BiAS23\"\r\n        class=\"bias-logo\"\r\n      >\r\n    </div>\r\n\r\n    <h2 class=\"bias-title\">\r\n      Download BIAS23 di Play Store!\r\n    </h2>\r\n\r\n    <p class=\"bias-desc\">\r\n      Analisis konten, engagement, sampai traffic semua dalam satu aplikasi.\r\n    </p>\r\n\r\n    <div class=\"bias-actions\">\r\n\r\n      <a href=\"#\">\r\n        <img \r\n          src=\"https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg\"\r\n          alt=\"Download on Google Play\"\r\n          class=\"bias-playstore\"\r\n        >\r\n      </a>\r\n\r\n    </div>\r\n\r\n  </div>\r\n\r\n</div>'),
(7, 'Gwenstacy', NULL, NULL, NULL, NULL, '2026-03-31 08:45:00', '2027-04-01 08:45:00', 1, 2, '2026-03-31 08:45:31', '2026-04-01 01:38:18', '<style>\r\n.nm-ai-banner{\r\n  position:relative;\r\n  max-width:420px;\r\n  width:100%;\r\n  margin:20px auto;\r\n  padding:42px 30px;\r\n  border-radius:30px;\r\n  background:linear-gradient(135deg,#020617,#0f172a,#020617);\r\n  color:white;\r\n  overflow:hidden;\r\n  font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;\r\n}\r\n\r\n/* glow */\r\n.nm-ai-banner::before,\r\n.nm-ai-banner::after{\r\n  content:\"\";\r\n  position:absolute;\r\n  width:260px;\r\n  height:260px;\r\n  border-radius:50%;\r\n  filter:blur(90px);\r\n  opacity:.25;\r\n}\r\n\r\n.nm-ai-banner::before{\r\n  background:#3b82f6;\r\n  top:-80px;\r\n  left:-60px;\r\n}\r\n\r\n.nm-ai-banner::after{\r\n  background:#06b6d4;\r\n  bottom:-80px;\r\n  right:-60px;\r\n}\r\n\r\n.nm-content{\r\n  position:relative;\r\n  text-align:center;\r\n  z-index:2;\r\n}\r\n\r\n.nm-logo{\r\n  width:150px;\r\n  margin-bottom:20px;\r\n  border-radius:18px;\r\n  box-shadow:0 10px 35px rgba(0,0,0,.6);\r\n}\r\n\r\n.nm-title{\r\n  font-size:24px;\r\n  font-weight:600;\r\n  margin-bottom:12px;\r\n  letter-spacing:.3px;\r\n}\r\n\r\n.nm-desc{\r\n  font-size:14px;\r\n  color:#cbd5f5;\r\n  margin-bottom:28px;\r\n  line-height:1.6;\r\n}\r\n\r\n.nm-btn{\r\n  display:inline-block;\r\n  padding:13px 30px;\r\n  border-radius:14px;\r\n  background:linear-gradient(90deg,#3b82f6,#06b6d4);\r\n  color:white;\r\n  text-decoration:none;\r\n  font-weight:600;\r\n  letter-spacing:.4px;\r\n  box-shadow:0 12px 30px rgba(0,0,0,.6);\r\n  transition:.25s;\r\n}\r\n\r\n.nm-btn:hover{\r\n  transform:translateY(-3px);\r\n  box-shadow:0 20px 45px rgba(0,0,0,.8);\r\n}\r\n\r\n.nm-close{\r\n  position:absolute;\r\n  top:14px;\r\n  right:18px;\r\n  border:none;\r\n  background:none;\r\n  font-size:22px;\r\n  color:#94a3b8;\r\n  cursor:pointer;\r\n  transition:.2s;\r\n}\r\n\r\n.nm-close:hover{\r\n  color:white;\r\n}\r\n</style>\r\n\r\n\r\n<div class=\"nm-ai-banner\">\r\n\r\n  <button class=\"nm-close\">&times;</button>\r\n\r\n  <div class=\"nm-content\">\r\n\r\n    <img \r\n      src=\"https://newsmaker.id/images/LogoNM23_Ai_22.png\"\r\n      class=\"nm-logo\"\r\n      alt=\"NM AI Logo\"\r\n    >\r\n\r\n    <h2 class=\"nm-title\">\r\n      Gunakan NM AI 🚀\r\n    </h2>\r\n\r\n    <p class=\"nm-desc\">\r\n      Buat berita, analisis market, dan ide konten lebih cepat dengan bantuan AI.\r\n      Tingkatkan produktivitas dan kualitas konten dalam satu platform.\r\n    </p>\r\n\r\n    <a href=\"#\" class=\"nm-btn\">\r\n      Coba NM AI Sekarang\r\n    </a>\r\n\r\n  </div>\r\n\r\n</div>'),
(11, 'Install Aplikasi Newsmaker23', NULL, NULL, NULL, NULL, '2026-04-01 01:42:00', '2030-04-01 01:42:00', 1, 3, '2026-04-01 01:43:01', '2026-04-01 01:43:01', '<style>\r\n.nm-app-banner{\r\n  display:flex;\r\n  align-items:center;\r\n  justify-content:space-between;\r\n  gap:32px;\r\n  padding:32px;\r\n  border-radius:24px;\r\n  background:linear-gradient(135deg,#0f172a,#1e293b);\r\n  color:white;\r\n  font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;\r\n  overflow:hidden;\r\n  position:relative;\r\n}\r\n\r\n/* glow effect */\r\n.nm-app-banner::before{\r\n  content:\"\";\r\n  position:absolute;\r\n  width:300px;\r\n  height:300px;\r\n  background:#3b82f6;\r\n  filter:blur(120px);\r\n  opacity:.25;\r\n  top:-80px;\r\n  right:-80px;\r\n}\r\n\r\n/* text area */\r\n.nm-app-content{\r\n  max-width:420px;\r\n  z-index:2;\r\n}\r\n\r\n.nm-app-title{\r\n  font-size:28px;\r\n  font-weight:700;\r\n  margin-bottom:12px;\r\n}\r\n\r\n.nm-app-desc{\r\n  font-size:15px;\r\n  line-height:1.6;\r\n  color:#cbd5f5;\r\n  margin-bottom:22px;\r\n}\r\n\r\n/* buttons */\r\n.nm-store-buttons{\r\n  display:flex;\r\n  gap:14px;\r\n  flex-wrap:wrap;\r\n}\r\n\r\n.nm-store-buttons img{\r\n  height:52px;\r\n  transition:.25s;\r\n  cursor:pointer;\r\n}\r\n\r\n.nm-store-buttons img:hover{\r\n  transform:translateY(-3px) scale(1.03);\r\n}\r\n\r\n/* preview image */\r\n.nm-app-image{\r\n  max-width:260px;\r\n  border-radius:20px;\r\n  box-shadow:0 30px 60px rgba(0,0,0,.35);\r\n  z-index:2;\r\n}\r\n\r\n/* responsive */\r\n@media(max-width:768px){\r\n  .nm-app-banner{\r\n    flex-direction:column-reverse;\r\n    text-align:center;\r\n    padding:26px;\r\n  }\r\n\r\n  .nm-app-image{\r\n    max-width:200px;\r\n  }\r\n\r\n  .nm-store-buttons{\r\n    justify-content:center;\r\n  }\r\n}\r\n</style>\r\n\r\n\r\n<div class=\"nm-app-banner\">\r\n\r\n  <div class=\"nm-app-content\">\r\n    <div class=\"nm-app-title\">\r\n      Download Aplikasi NewsMaker 23 📱\r\n    </div>\r\n\r\n    <div class=\"nm-app-desc\">\r\n      Dapatkan berita market, analisis, dan insight terbaru langsung dari smartphone kamu. \r\n      Akses cepat, ringan, dan selalu update setiap hari.\r\n    </div>\r\n\r\n    <div class=\"nm-store-buttons\">\r\n\r\n      <a href=\"https://play.google.com/store/apps/details?id=com.nm23.newsmaker23\" target=\"_blank\">\r\n        <img src=\"https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg\">\r\n      </a>\r\n\r\n      <a href=\"https://apps.apple.com/id/app/newsmaker-23/id6754815466?l=id\" target=\"_blank\">\r\n        <img src=\"https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg\">\r\n      </a>\r\n\r\n    </div>\r\n  </div>\r\n\r\n\r\n  <img \r\n    src=\"https://newsmaker.id/images/newsmaker-app-banner.png\"\r\n    class=\"nm-app-image\"\r\n  >\r\n\r\n</div>'),
(12, 'Pasar Indonesia', NULL, NULL, NULL, NULL, '2026-04-01 05:20:00', '2026-04-03 05:20:00', 1, 6, '2026-04-02 05:20:45', '2026-04-02 06:17:42', '<style>\r\n.pasar-modal{\r\n  position:relative;\r\n  margin:0 16px;\r\n  width:100%;\r\n  max-width:430px;\r\n  overflow:hidden;\r\n  border-radius:26px;\r\n  background:linear-gradient(145deg,#0f172a,#1e3a8a);\r\n  font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;\r\n  color:white;\r\n  z-index:1;\r\n}\r\n\r\n/* glow background decoration */\r\n.pasar-modal::before{\r\n  content:\"\";\r\n  position:absolute;\r\n  width:220px;\r\n  height:220px;\r\n  background:radial-gradient(circle,#22c55e55,transparent);\r\n  top:-60px;\r\n  right:-60px;\r\n  z-index:0;\r\n}\r\n\r\n.pasar-modal::after{\r\n  content:\"\";\r\n  position:absolute;\r\n  width:220px;\r\n  height:220px;\r\n  background:radial-gradient(circle,#3b82f655,transparent);\r\n  bottom:-60px;\r\n  left:-60px;\r\n  z-index:0;\r\n}\r\n\r\n/* close button */\r\n.pasar-close{\r\n  position:absolute;\r\n  top:14px;\r\n  right:14px;\r\n  width:36px;\r\n  height:36px;\r\n  border-radius:50%;\r\n  border:none;\r\n  background:rgba(255,255,255,0.15);\r\n  backdrop-filter:blur(6px);\r\n  color:white;\r\n  font-size:20px;\r\n  cursor:pointer;\r\n  transition:.25s;\r\n  z-index:9999; /* setara z-50 */\r\n  display:flex;\r\n  align-items:center;\r\n  justify-content:center;\r\n}\r\n\r\n.pasar-close:hover{\r\n  background:rgba(255,255,255,0.25);\r\n  transform:rotate(90deg);\r\n}\r\n\r\n/* content */\r\n.pasar-content{\r\n  position:relative;\r\n  z-index:2;\r\n  padding:36px 30px 42px;\r\n  text-align:center;\r\n}\r\n\r\n/* badge */\r\n.pasar-badge{\r\n  display:inline-block;\r\n  margin-bottom:14px;\r\n  padding:6px 14px;\r\n  border-radius:999px;\r\n  background:linear-gradient(90deg,#22c55e,#4ade80);\r\n  color:#022c22;\r\n  font-size:13px;\r\n  font-weight:700;\r\n  letter-spacing:.4px;\r\n  box-shadow:0 6px 18px rgba(34,197,94,.35);\r\n}\r\n\r\n/* icon circle */\r\n.pasar-icon-wrap{\r\n  margin:0 auto 18px auto;\r\n  width:82px;\r\n  height:82px;\r\n  display:flex;\r\n  align-items:center;\r\n  justify-content:center;\r\n  border-radius:22px;\r\n  background:linear-gradient(135deg,#3b82f6,#60a5fa);\r\n  font-size:40px;\r\n  box-shadow:\r\n    0 15px 40px rgba(59,130,246,.45),\r\n    inset 0 0 20px rgba(255,255,255,.25);\r\n}\r\n\r\n/* title */\r\n.pasar-title{\r\n  font-size:26px;\r\n  font-weight:800;\r\n  margin-bottom:8px;\r\n  letter-spacing:.3px;\r\n}\r\n\r\n/* desc */\r\n.pasar-desc{\r\n  font-size:15px;\r\n  color:#dbeafe;\r\n  line-height:1.6;\r\n  margin-bottom:26px;\r\n}\r\n\r\n/* button */\r\n.pasar-btn{\r\n  display:inline-block;\r\n  padding:13px 24px;\r\n  border-radius:14px;\r\n  background:linear-gradient(90deg,#22c55e,#4ade80);\r\n  color:#022c22;\r\n  text-decoration:none;\r\n  font-weight:700;\r\n  letter-spacing:.3px;\r\n  transition:.25s;\r\n  box-shadow:0 12px 25px rgba(34,197,94,.35);\r\n}\r\n\r\n.pasar-btn:hover{\r\n  transform:translateY(-3px) scale(1.03);\r\n  box-shadow:0 18px 40px rgba(34,197,94,.45);\r\n}\r\n\r\n/* mini chart decoration */\r\n.chart-line{\r\n  position:absolute;\r\n  bottom:0;\r\n  left:0;\r\n  width:100%;\r\n  height:70px;\r\n  opacity:.25;\r\n  z-index:0;\r\n}\r\n\r\n.chart-line svg{\r\n  width:100%;\r\n  height:100%;\r\n}\r\n\r\n/* confetti dots */\r\n.dot{\r\n  position:absolute;\r\n  width:6px;\r\n  height:6px;\r\n  border-radius:50%;\r\n  opacity:.7;\r\n  z-index:0;\r\n}\r\n\r\n.dot1{background:#22c55e; top:18px; left:20px;}\r\n.dot2{background:#3b82f6; top:60px; right:30px;}\r\n.dot3{background:#facc15; bottom:40px; left:40px;}\r\n.dot4{background:#4ade80; bottom:80px; right:50px;}\r\n</style>\r\n\r\n\r\n<div class=\"pasar-modal\">\r\n\r\n  <button class=\"pasar-close\" id=\"close\">&times;</button>\r\n\r\n  <div class=\"dot dot1\"></div>\r\n  <div class=\"dot dot2\"></div>\r\n  <div class=\"dot dot3\"></div>\r\n  <div class=\"dot dot4\"></div>\r\n\r\n  <div class=\"pasar-content\">\r\n\r\n    <div class=\"pasar-icon-wrap\">\r\n      📈\r\n    </div>\r\n\r\n    <span class=\"pasar-badge\">\r\n      MENU BARU\r\n    </span>\r\n\r\n    <div class=\"pasar-title\">\r\n      Pasar Indonesia\r\n    </div>\r\n\r\n    <div class=\"pasar-desc\">\r\n      Akses data IHSG, saham unggulan, market insight,\r\n      dan pergerakan pasar Indonesia dalam satu halaman interaktif.\r\n    </div>\r\n\r\n    <a href=\"/pasar-indonesia\" class=\"pasar-btn\">\r\n      Buka Halaman\r\n    </a>\r\n\r\n  </div>\r\n\r\n  <div class=\"chart-line\">\r\n    <svg viewBox=\"0 0 500 100\" preserveAspectRatio=\"none\">\r\n      <polyline\r\n        points=\"0,80 60,60 120,65 180,40 240,45 300,20 360,30 420,10 500,15\"\r\n        fill=\"none\"\r\n        stroke=\"white\"\r\n        stroke-width=\"3\"\r\n      />\r\n    </svg>\r\n  </div>\r\n\r\n</div>');

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
('QFajBBdJtM91cLpMlj48xNljThBAQJ3FXtMKO0aL', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRVZ3dEZ6VTNtb21ydnVrQWhxVDhTSUxkMXNWdVJEdm42ZTNXbVhpeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9wb3J0YWxuZXdzLm5ld3NtYWtlci50ZXN0L3Bhc2FyLWluZG9uZXNpYSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1775116536);

-- --------------------------------------------------------

--
-- Table structure for table `tiktoks`
--

CREATE TABLE `tiktoks` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `embed_code` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `backup_video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tiktoks`
--

INSERT INTO `tiktoks` (`id`, `title`, `embed_code`, `backup_video_url`, `created_at`, `updated_at`) VALUES
(1, 'Test 1', '<blockquote class=\"tiktok-embed\" cite=\"https://www.tiktok.com/@newsmaker23/video/7621503871155817735\" data-video-id=\"7621503871155817735\" style=\"max-width: 605px;min-width: 325px;\" > <section> <a target=\"_blank\" title=\"@newsmaker23\" href=\"https://www.tiktok.com/@newsmaker23?refer=embed\">@newsmaker23</a> Sama-sama minyak mentah, tapi kenapa ada dua harga yang sering jadi acuan dunia: WTI dan Brent? 🤔 Perbedaan ini bukan cuma soal nama, tapi juga soal lokasi, kualitas, distribusi, sampai kondisi geopolitik dan supply global. Di video ini kita bahas singkat kenapa WTI dan Brent bisa punya harga yang berbeda meskipun sama-sama jadi benchmark minyak dunia. <a title=\"wti\" target=\"_blank\" href=\"https://www.tiktok.com/tag/wti?refer=embed\">#WTI</a> <a title=\"brent\" target=\"_blank\" href=\"https://www.tiktok.com/tag/brent?refer=embed\">#Brent</a> <a title=\"hargaminyak\" target=\"_blank\" href=\"https://www.tiktok.com/tag/hargaminyak?refer=embed\">#HargaMinyak</a> <a title=\"minyakdunia\" target=\"_blank\" href=\"https://www.tiktok.com/tag/minyakdunia?refer=embed\">#MinyakDunia</a> <a title=\"oilmarket\" target=\"_blank\" href=\"https://www.tiktok.com/tag/oilmarket?refer=embed\">#OilMarket</a> <a target=\"_blank\" title=\"♬ suara asli  - NewsMaker²³\" href=\"https://www.tiktok.com/music/suara-asli-NewsMaker²³-7621503972276177672?refer=embed\">♬ suara asli  - NewsMaker²³</a> </section> </blockquote> <script async src=\"https://www.tiktok.com/embed.js\"></script>', 'https://www.tiktok.com/@newsmaker23/video/7621503871155817735', '2026-03-31 09:17:06', '2026-03-31 09:17:06');

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
(1, 'Superadmin', 'Superadmin', 'Admin@newsmaker23.id', 'Superadmin', NULL, '$2y$12$cS6GbZMO8zS7VbFBAhEvCOfgwXHwWgoL02hqbCB6uhACl2.J0h.6C', NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-18 11:51:01'),
(2, 'adminnm', 'AdminNM', '23newsmaker@gmail.com', 'Superadmin', NULL, '$2y$12$1hsURx6jQjX16ZnXAIwMRO8bW/81OSRK8rzXAdDtccbZsnHc.TDMm', NULL, NULL, NULL, NULL, NULL, '2025-06-18 15:19:28', '2025-09-16 23:42:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beritas`
--
ALTER TABLE `beritas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `beritas_slug_index` (`slug`(191)),
  ADD KEY `beritas_category_id_foreign` (`category_id`),
  ADD KEY `beritas_user_id_foreign` (`user_id`),
  ADD KEY `beritas_category_id_created_at_index` (`category_id`,`created_at`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `economic_calendars_economic_calendar_category_id_foreign` (`economic_calendar_category_id`);

--
-- Indexes for table `economic_calendar_categories`
--
ALTER TABLE `economic_calendar_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `economic_calendar_categories_unique` (`country`,`impact`,`figures`);

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
-- Indexes for table `newsmaker_articles`
--
ALTER TABLE `newsmaker_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `newsmaker_articles_slug_unique` (`slug`),
  ADD KEY `newsmaker_articles_main_category_id_foreign` (`main_category_id`),
  ADD KEY `newsmaker_articles_sub_category_id_foreign` (`sub_category_id`);

--
-- Indexes for table `newsmaker_main_categories`
--
ALTER TABLE `newsmaker_main_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `newsmaker_main_categories_slug_unique` (`slug`);

--
-- Indexes for table `newsmaker_sub_categories`
--
ALTER TABLE `newsmaker_sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `newsmaker_sub_categories_main_category_id_slug_unique` (`main_category_id`,`slug`);

-- Indexes for table `pasar_indonesia_articles`
--
ALTER TABLE `pasar_indonesia_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pasar_indonesia_articles_slug_unique` (`slug`),
  ADD KEY `pasar_indonesia_articles_author_id_foreign` (`author_id`),
  ADD KEY `pasar_indonesia_articles_type_index` (`type`);

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
-- Indexes for table `popup_banners`
--
ALTER TABLE `popup_banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tiktoks`
--
ALTER TABLE `tiktoks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beritas`
--
ALTER TABLE `beritas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `economic_calendar_categories`
--
ALTER TABLE `economic_calendar_categories`
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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `newsmaker_articles`
--
ALTER TABLE `newsmaker_articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsmaker_main_categories`
--
ALTER TABLE `newsmaker_main_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `newsmaker_sub_categories`
--
ALTER TABLE `newsmaker_sub_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

-- AUTO_INCREMENT for table `pasar_indonesia_articles`
--
ALTER TABLE `pasar_indonesia_articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `popup_banners`
--
ALTER TABLE `popup_banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tiktoks`
--
ALTER TABLE `tiktoks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `beritas`
--
ALTER TABLE `beritas`
  ADD CONSTRAINT `beritas_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `beritas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `economic_calendars`
--
ALTER TABLE `economic_calendars`
  ADD CONSTRAINT `economic_calendars_economic_calendar_category_id_foreign` FOREIGN KEY (`economic_calendar_category_id`) REFERENCES `economic_calendar_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `newsmaker_articles`
--
ALTER TABLE `newsmaker_articles`
  ADD CONSTRAINT `newsmaker_articles_main_category_id_foreign` FOREIGN KEY (`main_category_id`) REFERENCES `newsmaker_main_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `newsmaker_articles_sub_category_id_foreign` FOREIGN KEY (`sub_category_id`) REFERENCES `newsmaker_sub_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `newsmaker_sub_categories`
--
ALTER TABLE `newsmaker_sub_categories`
  ADD CONSTRAINT `newsmaker_sub_categories_main_category_id_foreign` FOREIGN KEY (`main_category_id`) REFERENCES `newsmaker_main_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Constraints for table `pasar_indonesia_articles`
--
ALTER TABLE `pasar_indonesia_articles`
  ADD CONSTRAINT `pasar_indonesia_articles_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



