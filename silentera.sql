-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 10.1.111.141:32491
-- Generation Time: Nov 14, 2024 at 05:04 AM
-- Server version: 9.0.1
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `silentera`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(3, 'Basic', 'Basic Plan', '2024-11-07 09:40:07', '2024-11-07 09:41:15'),
(4, 'Professional', 'Professional Plan', '2024-11-07 16:27:17', '2024-11-07 16:27:17'),
(5, 'Enterprise', 'Enterprise Plan', '2024-11-07 16:27:35', '2024-11-07 16:27:35'),
(6, 'Combo', 'Combo Plan', '2024-11-07 16:27:43', '2024-11-07 16:27:43');

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
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint UNSIGNED NOT NULL,
  `lon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `lon`, `lat`, `region`, `province`, `created_at`, `updated_at`) VALUES
(5, '-12.09329', '-10.23232', 'Kabupaten Kudus', 'Jawa Tengah', '2024-11-07 12:54:01', '2024-11-07 12:54:01'),
(6, '-12.09329', '-10.23232', 'Kabupaten Grobogan', 'Jawa Tengah', '2024-11-07 15:24:30', '2024-11-07 15:24:30'),
(7, '-11.11111', '-9.99999', 'Kabupaten Grobogan', 'Jawa Tengah', '2024-11-07 20:55:52', '2024-11-07 20:55:52'),
(8, '-11.11111', '-9.99999', 'Kabupaten Pati', 'Jawa Tengah', '2024-11-07 21:34:26', '2024-11-07 21:34:26'),
(9, '-11.11111', '-9.99999', 'Kabupaten Magelang', 'Jawa Tengah', '2024-11-08 09:48:19', '2024-11-08 09:48:19'),
(10, '139.7900390625', '-3.651503771723', 'KABUPATEN YALIMO', 'PAPUA', '2024-11-13 00:15:06', '2024-11-13 00:15:06'),
(11, '104.4580078125', '-3.651503771723', 'KABUPATEN OGAN ILIR', 'SUMATERA SELATAN', '2024-11-13 00:18:16', '2024-11-13 00:18:16'),
(12, '139.658203125', '-7.5008524937254', 'KABUPATEN MERAUKE', 'PAPUA', '2024-11-13 03:46:50', '2024-11-13 03:46:50'),
(13, '120.05859375', '-2.6703791252344', 'KABUPATEN LUWU', 'SULAWESI SELATAN', '2024-11-13 03:58:42', '2024-11-13 03:58:42'),
(14, '111.4453125', '-0.16765686275797', 'KABUPATEN SINTANG', 'KALIMANTAN BARAT', '2024-11-13 04:17:31', '2024-11-13 04:17:31'),
(15, '107.9296875', '-6.9339157740224', 'KABUPATEN SUMEDANG', 'JAWA BARAT', '2024-11-14 01:52:12', '2024-11-14 01:52:12'),
(16, '120.1904296875', '-9.9339275113387', 'KABUPATEN SUMBA TIMUR', 'NUSA TENGGARA TIMUR', '2024-11-14 02:11:00', '2024-11-14 02:11:00');

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
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_05_23_121331_create_savelocs_table', 1),
(6, '2023_06_20_035006_create_payments_table', 1),
(7, '2023_08_07_133123_create_surveys_table', 1),
(8, '2023_08_14_105258_create_profil_images_table', 1),
(9, '2024_11_07_092054_create_categories_table', 2),
(10, '2024_11_07_092055_create_plans_table', 2),
(11, '2024_11_07_092056_create_plan_features_table', 2),
(12, '2024_11_07_092056_create_subscriptions_table', 2),
(13, '2024_11_07_102617_create_locations_table', 3),
(14, '2024_11_07_102856_create_subscriptions_table', 3),
(15, '2024_11_07_203809_add_image_column_to_subscriptions_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT (uuid()),
  `user_id` bigint UNSIGNED NOT NULL,
  `lon` decimal(8,5) NOT NULL,
  `lat` decimal(8,5) NOT NULL,
  `region` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastday` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`uuid`, `user_id`, `lon`, `lat`, `region`, `province`, `status`, `lastday`, `created_at`, `updated_at`) VALUES
('03140917-1f7d-4cc7-ae0b-2d36fc240dba', 6, 113.37891, -2.59480, 'KABUPATEN KATINGAN', 'KALIMANTAN TENGAH', 'Success', '2025-10-29', '2024-10-29 15:15:50', '2024-10-29 15:15:50'),
('0682a094-32c8-4432-9bcb-40100fc24e60', 8, 115.13672, -1.93504, 'KABUPATEN BARITO TIMUR', 'KALIMANTAN TENGAH', 'Success', '2024-11-30', '2024-10-31 20:03:04', '2024-10-31 20:03:04'),
('0aac0d2e-14b8-4d21-b09e-5b37024c1a8d', 10, 111.62109, -7.57772, 'KABUPATEN MADIUN', 'JAWA TIMUR', 'Success', '2025-02-04', '2024-11-06 23:19:29', '2024-11-06 23:19:29'),
('0c1116f2-32b4-45b2-af3a-ed9ab9d4efbc', 10, 116.80664, 0.74477, 'KABUPATEN KUTAI TIMUR', 'KALIMANTAN TIMUR', 'Cancel', '2024-12-06', '2024-11-06 21:02:28', '2024-11-06 21:02:28'),
('3c36d113-f7ee-4094-aacd-47fd20b75ffa', 9, 107.92969, -6.93392, 'KABUPATEN SUMEDANG', 'JAWA BARAT', 'Success', '2024-12-14', '2024-11-14 01:52:13', '2024-11-14 01:52:13'),
('4ff72dbd-d7bd-4650-a5c0-16dd160874e7', 10, 116.80664, 0.74477, 'KABUPATEN KUTAI TIMUR', 'KALIMANTAN TIMUR', 'Pending', '2024-12-06', '2024-11-06 15:05:51', '2024-11-06 15:05:51'),
('6284df55-c988-42e5-99f1-55a926820191', 9, 120.19043, -9.93393, 'KABUPATEN SUMBA TIMUR', 'NUSA TENGGARA TIMUR', 'Success', '2024-12-14', '2024-11-14 02:11:00', '2024-11-14 02:11:00'),
('64baa569-c7b5-4136-acb8-1ca8444ac821', 9, 114.47754, -0.83662, 'KABUPATEN MURUNG RAYA', 'KALIMANTAN TENGAH', 'Success', '2024-12-03', '2024-11-03 19:39:13', '2024-11-03 19:39:13'),
('6d2efde8-fecd-48f1-93c1-3abfc7e47912', 8, 115.13672, -0.44109, 'KABUPATEN BARITO UTARA', 'KALIMANTAN TENGAH', 'Success', '2025-10-31', '2024-10-31 20:05:17', '2024-10-31 20:05:17'),
('7b924962-ec2f-41a1-87fd-b3c0753c4dc5', 6, 139.26270, -3.73344, 'KABUPATEN MAMBERAMO TENGAH', 'PAPUA', 'Pending', '2025-11-07', '2024-11-07 11:17:56', '2024-11-07 11:17:56'),
('85a7d237-1cd7-47cc-9383-3f765c9ab6ab', 6, 109.81119, -7.48455, 'KABUPATEN WONOSOBO', 'JAWA TENGAH', 'Pending', '2024-11-28', '2024-10-29 15:08:53', '2024-10-29 15:08:53'),
('88c54a1a-e1b5-4595-995b-74e4e84f5b1d', 6, 113.37891, -2.59480, 'KABUPATEN KATINGAN', 'KALIMANTAN TENGAH', 'Pending', '2024-11-28', '2024-10-29 15:15:37', '2024-10-29 15:15:37'),
('89414a9f-55f6-4819-84e3-0e62ee6940a9', 5, 113.90625, -0.22658, 'KABUPATEN MURUNG RAYA', 'KALIMANTAN TENGAH', 'Success', '2024-11-28', '2024-10-29 17:32:46', '2024-10-29 17:32:46'),
('95fe7e54-fe81-4754-86b1-645f95209042', 2, 113.90625, -1.93603, 'KABUPATEN PULANGPISAU', 'KALIMANTAN TENGAH', 'Success', '2024-09-22', '2024-09-19 12:00:08', '2024-09-19 12:00:08'),
('a796baa7-9be8-49c6-8e8a-3aab3c8be7a6', 9, 139.65820, -4.74751, 'KABUPATEN YAHUKIMO', 'PAPUA', 'Pending', '2025-11-13', '2024-11-13 01:39:50', '2024-11-13 01:39:50'),
('b01e0a99-e739-4f9a-92f7-cb36c2e51ec4', 6, 112.85156, -0.57189, 'KABUPATEN KATINGAN', 'KALIMANTAN TENGAH', 'Pending', '2025-10-29', '2024-10-29 18:51:47', '2024-10-29 18:51:47'),
('ccd72420-b043-46c2-982a-d7a12edd7dd5', 9, 111.44531, -0.16766, 'KABUPATEN SINTANG', 'KALIMANTAN BARAT', 'Success', '2024-12-13', '2024-11-13 04:17:31', '2024-11-13 04:17:31'),
('e88f1e2b-a0f8-42db-b548-d10695680917', 9, 120.05859, -2.67038, 'KABUPATEN LUWU', 'SULAWESI SELATAN', 'Pending', '2024-12-13', '2024-11-13 03:58:42', '2024-11-13 03:58:42'),
('ebc3ed48-9d2f-4741-bf4e-c780c5038586', 9, 139.65820, -7.50085, 'KABUPATEN MERAUKE', 'PAPUA', 'Pending', '2025-11-13', '2024-11-13 03:46:50', '2024-11-13 03:46:50'),
('ecf661f0-28d1-4fe2-9536-8d66172cd4ad', 10, 111.62109, -7.57772, 'KABUPATEN MADIUN', 'JAWA TIMUR', 'Pending', '2025-02-04', '2024-11-06 23:19:01', '2024-11-06 23:19:01'),
('f1b6dfac-cd34-4758-9679-047f069f4401', 6, 114.28333, -0.03333, 'KABUPATEN MURUNG RAYA', 'KALIMANTAN TENGAH', 'Pending', '2025-11-11', '2024-11-11 09:34:51', '2024-11-11 09:34:51');

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
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_weekly` decimal(65,2) NOT NULL,
  `price_monthly` decimal(65,2) NOT NULL,
  `price_annual` decimal(65,2) NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `category_id`, `name`, `price_weekly`, `price_monthly`, `price_annual`, `qty`, `description`, `created_at`, `updated_at`) VALUES
(3, 3, 'Combo', 0.00, 1500000.00, 0.00, 1, 'For Monitoring', '2024-11-07 19:24:55', '2024-11-07 19:24:55'),
(6, 4, 'Monitoring', 0.00, 1300000.00, 0.00, 1, 'For Monitoring', '2024-11-07 19:52:37', '2024-11-07 19:52:37'),
(7, 4, 'Monitoring', 0.00, 0.00, 100000.00, 1, 'For Monitoring', '2024-11-07 21:22:33', '2024-11-07 21:22:33'),
(8, 3, 'Monitoring', 0.00, 0.00, 100000.00, 1, 'For Monitoring', '2024-11-07 21:23:31', '2024-11-07 21:23:31'),
(12, 4, 'Forecast', 0.00, 200000.00, 0.00, 1, '7 Days', '2024-11-10 17:50:30', '2024-11-10 17:50:30'),
(13, 5, 'Monitoring', 300000.00, 0.00, 0.00, 1, '14 Days', '2024-11-10 17:54:02', '2024-11-10 17:54:02'),
(32, 4, 'Forecast', 0.00, 123.00, 0.00, 1, '14 Days', '2024-11-10 22:01:01', '2024-11-10 22:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `plan_features`
--

CREATE TABLE `plan_features` (
  `id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL,
  `feature` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_features`
--

INSERT INTO `plan_features` (`id`, `plan_id`, `feature`, `created_at`, `updated_at`) VALUES
(7, 3, 'Akses data dalam format tabular (csv) dan format pdf', '2024-11-07 19:47:49', '2024-11-07 19:47:49'),
(8, 3, 'Akses data dalam format tabular (csv) dan format pdf', '2024-11-07 19:48:03', '2024-11-07 19:48:03'),
(16, 32, '12', '2024-11-10 22:01:01', '2024-11-10 22:01:01'),
(17, 32, '1234', '2024-11-10 22:01:01', '2024-11-10 22:01:01'),
(18, 32, '123', '2024-11-10 22:01:01', '2024-11-10 22:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `profil_images`
--

CREATE TABLE `profil_images` (
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT (uuid()),
  `user_id` bigint UNSIGNED NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profil_images`
--

INSERT INTO `profil_images` (`uuid`, `user_id`, `source`, `created_at`, `updated_at`) VALUES
('0d2ccce3-61c7-4132-909e-661a01412668', 6, 'null', '2024-10-01 16:06:16', '2024-10-01 16:06:16'),
('1428407b-c8e0-480b-ac0b-e7492279e5cc', 8, 'null', '2024-10-31 20:02:38', '2024-10-31 20:02:38'),
('509a9e2d-feb8-405b-8f37-5fcfa7b097f1', 4, 'null', '2024-10-01 15:37:58', '2024-10-01 15:37:58'),
('633acd82-e892-4ef6-9353-76049644f501', 2, 'null', '2024-09-19 11:59:53', '2024-09-19 11:59:53'),
('6439b359-8a2c-4830-8e4d-7e97dbf7ca98', 3, 'null', '2024-09-19 12:04:24', '2024-09-19 12:04:24'),
('95e38930-a2c1-4a4c-a3c5-27cbb82b5a49', 9, 'null', '2024-11-03 19:38:51', '2024-11-03 19:38:51'),
('c00096c5-0caa-412c-8e3d-e08adf7da14b', 5, 'null', '2024-10-01 15:54:04', '2024-10-01 15:54:04'),
('e0647dc2-eff1-42b9-8eb0-4f8628fd45a3', 7, 'null', '2024-10-31 19:01:22', '2024-10-31 19:01:22'),
('ff366c84-d333-4ef5-add7-9977cd6206b5', 10, 'null', '2024-11-04 19:44:13', '2024-11-04 19:44:13');

-- --------------------------------------------------------

--
-- Table structure for table `save_locs`
--

CREATE TABLE `save_locs` (
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT (uuid()),
  `user_id` bigint UNSIGNED NOT NULL,
  `lon` decimal(8,5) NOT NULL,
  `lat` decimal(8,5) NOT NULL,
  `region` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `save_locs`
--

INSERT INTO `save_locs` (`uuid`, `user_id`, `lon`, `lat`, `region`, `province`, `created_at`, `updated_at`) VALUES
('140eb7e1-69c1-4447-9318-206765ef9b53', 9, 120.05859, -2.67038, 'KABUPATEN LUWU', 'SULAWESI SELATAN', '2024-11-13 03:58:42', '2024-11-13 03:58:42'),
('1d9bd635-1662-46a6-8cf2-d13d6609c31f', 6, 113.59863, -1.57363, 'KABUPATEN GUNUNGMAS', 'KALIMANTAN TENGAH', '2024-11-11 09:28:39', '2024-11-11 09:28:39'),
('28ace8e2-7726-454c-979c-933cd2548702', 2, 113.90625, -1.93603, 'KABUPATEN PULANGPISAU', 'KALIMANTAN TENGAH', '2024-09-19 12:00:08', '2024-09-19 12:00:08'),
('39389790-3079-47ec-bba5-49c54c032208', 10, 116.80664, 0.74477, 'KABUPATEN KUTAI TIMUR', 'KALIMANTAN TIMUR', '2024-11-06 21:02:28', '2024-11-06 21:02:28'),
('44dbe9c4-3a53-4573-86d2-a68531b97dba', 9, 114.47754, -0.83662, 'KABUPATEN MURUNG RAYA', 'KALIMANTAN TENGAH', '2024-11-03 19:39:13', '2024-11-03 19:39:13'),
('47b79243-4b1c-4dd1-8e1e-a9b7e6f75e1d', 8, 115.13672, -1.93504, 'KABUPATEN BARITO TIMUR', 'KALIMANTAN TENGAH', '2024-10-31 20:03:04', '2024-10-31 20:03:04'),
('4ddba1b8-d4ed-4ec8-a8a2-04c69ca1b8e0', 9, 107.92969, -6.93392, 'KABUPATEN SUMEDANG', 'JAWA BARAT', '2024-11-14 01:52:13', '2024-11-14 01:52:13'),
('5d250dc0-c984-4aba-99b2-c662eee37c5e', 10, 111.62109, -7.57772, 'KABUPATEN MADIUN', 'JAWA TIMUR', '2024-11-06 23:19:30', '2024-11-06 23:19:30'),
('787e38e1-10dd-4dbe-88ec-52940c6bc26c', 9, 139.79004, -3.65150, 'KABUPATEN YALIMO', 'PAPUA', '2024-11-13 00:15:06', '2024-11-13 00:15:06'),
('83823433-4055-4f38-8950-9a01f6826d7d', 9, 120.19043, -9.93393, 'KABUPATEN SUMBA TIMUR', 'NUSA TENGGARA TIMUR', '2024-11-14 02:11:00', '2024-11-14 02:11:00'),
('90ed6fbd-4f11-45be-8760-ecadbaec873d', 9, 139.65820, -7.50085, 'KABUPATEN MERAUKE', 'PAPUA', '2024-11-13 03:46:50', '2024-11-13 03:46:50'),
('962e594a-f200-48fb-b197-fde571d93662', 9, 139.79004, -3.65150, 'KABUPATEN YALIMO', 'PAPUA', '2024-11-13 01:30:02', '2024-11-13 01:30:02'),
('964ccc45-639c-491d-b772-c6303e1eecf1', 5, 113.90625, -0.22658, 'KABUPATEN MURUNG RAYA', 'KALIMANTAN TENGAH', '2024-10-29 17:32:47', '2024-10-29 17:32:47'),
('ae883c31-ddaf-4ceb-b631-227024385058', 9, 139.65820, -4.74751, 'KABUPATEN YAHUKIMO', 'PAPUA', '2024-11-13 01:39:51', '2024-11-13 01:39:51'),
('c5203e2e-69af-49e2-b002-620dfccec51d', 8, 115.13672, -0.44109, 'KABUPATEN BARITO UTARA', 'KALIMANTAN TENGAH', '2024-10-31 20:05:18', '2024-10-31 20:05:18'),
('c6433231-c3e1-4f30-81fb-3d10d269e2d2', 6, 114.28333, -0.03333, 'KABUPATEN MURUNG RAYA', 'KALIMANTAN TENGAH', '2024-11-11 09:28:15', '2024-11-11 09:28:15'),
('cc383d30-b809-4d84-9ffc-307259fdb704', 9, 111.44531, -0.16766, 'KABUPATEN SINTANG', 'KALIMANTAN BARAT', '2024-11-13 04:17:31', '2024-11-13 04:17:31'),
('d9a534c6-5233-4fe6-b49c-415c4dfd7586', 6, 114.28333, -0.03333, 'KABUPATEN MURUNG RAYA', 'KALIMANTAN TENGAH', '2024-11-11 09:34:51', '2024-11-11 09:34:51'),
('f321bcb3-0aaa-468d-843f-aa8022ba6f8e', 9, 104.45801, -3.65150, 'KABUPATEN OGAN ILIR', 'SUMATERA SELATAN', '2024-11-13 00:18:17', '2024-11-13 00:18:17');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL,
  `location_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `package` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(65,2) NOT NULL,
  `grand_price` decimal(65,2) NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('PENDING','FAILED','SUCCESS') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan_id`, `location_id`, `start_date`, `end_date`, `package`, `qty`, `price`, `grand_price`, `image_path`, `status`, `created_at`, `updated_at`) VALUES
(39, 9, 32, 10, '2024-11-12', '2024-12-12', 'monthly', 1, 123.00, 123.00, 'subscriptions/67338d1a66500.png', 'SUCCESS', '2024-11-13 00:15:06', '2024-11-13 01:04:47'),
(40, 9, 32, 11, '2024-11-12', '2024-12-12', 'monthly', 1, 123.00, 123.00, 'subscriptions/67338dd8c399c.png', 'SUCCESS', '2024-11-13 00:18:16', '2024-11-13 02:08:38'),
(41, 9, 32, 10, '2024-11-12', '2024-12-12', 'monthly', 1, 123.00, 123.00, 'subscriptions/67339ea9d8a64.png', 'SUCCESS', '2024-11-13 01:30:01', '2024-11-13 02:09:48'),
(42, 9, 7, 12, '2024-11-12', '2025-11-12', 'annual', 1, 100000.00, 100000.00, 'subscriptions/6733beba2d233.png', 'SUCCESS', '2024-11-13 03:46:50', '2024-11-13 03:48:45'),
(43, 9, 3, 13, '2024-11-12', '2024-12-12', 'monthly', 1, 1500000.00, 1500000.00, 'subscriptions/6733c18211005.png', 'SUCCESS', '2024-11-13 03:58:42', '2024-11-13 03:59:11'),
(44, 9, 32, 14, '2024-11-12', '2024-12-12', 'monthly', 1, 123.00, 123.00, 'subscriptions/6733c5eb3f2b6.png', 'SUCCESS', '2024-11-13 04:17:31', '2024-11-13 04:18:00'),
(45, 9, 32, 15, '2024-11-13', '2024-12-13', 'monthly', 1, 123.00, 123.00, 'subscriptions/6734f55cb72ac.png', 'SUCCESS', '2024-11-14 01:52:12', '2024-11-14 02:06:26'),
(46, 9, 3, 16, '2024-11-13', '2024-12-13', 'monthly', 1, 1500000.00, 1500000.00, 'subscriptions/6734f9c40d575.png', 'SUCCESS', '2024-11-14 02:11:00', '2024-11-14 02:11:19');

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` bigint UNSIGNED NOT NULL,
  `pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `umur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `informasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `surveys`
--

INSERT INTO `surveys` (`id`, `pekerjaan`, `jenis_kelamin`, `umur`, `informasi`, `created_at`, `updated_at`) VALUES
(1, 'Pelajar/Mahasiswa', 'Perempuan', '26 - 35 Tahun', 'Energi Surya', '2024-10-30 15:34:49', '2024-10-30 15:34:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT (uuid()),
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taketour` int NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `name`, `status`, `email`, `email_verified_at`, `password`, `taketour`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'uuid', 'dhohir pradana', '1', 'dhohirpradana@bodha.co.id', NULL, '2wsx1qaz', 123, NULL, NULL, NULL),
(2, '633acd82-e892-4ef6-9353-76049644f501', 'adisurya(?)adi', 'Authenticated', 'adisurya@gmail.com', NULL, '$2y$10$ujG0Nab10WbztY/v.G4oAubt9JrvfEEr8wLsRSxzPnpji2xYGxrSu', 4, NULL, '2024-09-19 11:59:53', '2024-09-19 11:59:53'),
(3, '6439b359-8a2c-4830-8e4d-7e97dbf7ca98', 'admin(?)adi', 'Admin', 'admin@gmail.com', NULL, '$2y$10$WcF.kI9PzQiW0UVR2V8t4.yjnZVkGvzGB3wODiTlBRskGwRB8vDDi', 124, NULL, '2024-09-19 12:04:24', '2024-09-19 12:04:24'),
(4, '509a9e2d-feb8-405b-8f37-5fcfa7b097f1', 'teguh(?)teguh', 'Authenticated', 'teguh@bodha.co.id', NULL, '$2y$10$BFtl48owXeHZ4ylhLoqXqeal./BUu4X1Nj3.NxIOxoWs6cRAi2hFO', 24, NULL, '2024-10-01 15:37:58', '2024-10-01 15:37:58'),
(5, 'c00096c5-0caa-412c-8e3d-e08adf7da14b', 'yusuf(?)fauziyan', 'Authenticated', 'yusuf@gmail.com', NULL, '$2y$10$3qF3T10Pr8IcxxOJQuOgQOMv8RgwH2REcYp1LJxvDgth68GoQBw16', 4, NULL, '2024-10-01 15:54:04', '2024-10-01 15:54:04'),
(6, '0d2ccce3-61c7-4132-909e-661a01412668', 'Aini(?)Sterna', 'Authenticated', 'naini.ginting01@gmail.com', NULL, '$2y$10$6rrvLKffXbqi6HzMYjR00eR/wGW7l0Z0wmq1NN7ZpoGi2xMHzB3ca', 4, NULL, '2024-10-01 16:06:16', '2024-10-01 16:06:16'),
(7, 'e0647dc2-eff1-42b9-8eb0-4f8628fd45a3', 'teguh(?)pra', 'Authenticated', 'teguh@mailcom', NULL, '$2y$10$JjKDx8RIHodnwAzF7KtGpuz0Mowu7ycSbgaOmHdrvZqCE9Q.S4vZ.', 124, NULL, '2024-10-31 19:01:22', '2024-10-31 19:01:22'),
(8, '1428407b-c8e0-480b-ac0b-e7492279e5cc', 'teuh(?)h', 'Authenticated', 'teguh@mail.com', NULL, '$2y$10$ngmFm4YuIplH47qmwtnc5.v54NfGXDYXiHIo4dWh7t8kgKdLn8F5.', 4, NULL, '2024-10-31 20:02:38', '2024-10-31 20:02:38'),
(9, '95e38930-a2c1-4a4c-a3c5-27cbb82b5a49', 'tetsu(?)shu', 'Authenticated', '1@mail.com', NULL, '$2y$10$fiY0lph41cNNhKbrOw2Dh.AudG9a3HKQZ4EZzpNVjxRAHt3vyypD2', 4, NULL, '2024-11-03 19:38:51', '2024-11-03 19:38:51'),
(10, 'ff366c84-d333-4ef5-add7-9977cd6206b5', 'ryfa(?)karin', 'Authenticated', 'fitrianakarin@yahoo.com', NULL, '$2y$10$VZGaGuFTNlAaH29h2B1fnul6m6nUYCEW7V5Ri069OuIrHhhBoKJ8y', 4, NULL, '2024-11-04 19:44:13', '2024-11-04 19:44:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plans_category_id_foreign` (`category_id`);

--
-- Indexes for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_features_plan_id_foreign` (`plan_id`);

--
-- Indexes for table `profil_images`
--
ALTER TABLE `profil_images`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `profil_images_user_id_foreign` (`user_id`);

--
-- Indexes for table `save_locs`
--
ALTER TABLE `save_locs`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `save_locs_user_id_foreign` (`user_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `subscriptions_plan_id_foreign` (`plan_id`),
  ADD KEY `subscriptions_location_id_foreign` (`location_id`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `plan_features`
--
ALTER TABLE `plan_features`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `plans`
--
ALTER TABLE `plans`
  ADD CONSTRAINT `plans_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plan_features`
--
ALTER TABLE `plan_features`
  ADD CONSTRAINT `plan_features_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profil_images`
--
ALTER TABLE `profil_images`
  ADD CONSTRAINT `profil_images_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `save_locs`
--
ALTER TABLE `save_locs`
  ADD CONSTRAINT `save_locs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
