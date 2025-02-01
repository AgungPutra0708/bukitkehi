-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2025 at 03:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bukit_kehi`
--

-- --------------------------------------------------------

--
-- Table structure for table `aparaturs`
--

CREATE TABLE `aparaturs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aparaturs`
--

INSERT INTO `aparaturs` (`id`, `name`, `position`, `image`, `created_at`, `updated_at`, `user_id`) VALUES
(6, 'alan', 'manager', '1730820611.jpeg', '2024-11-05 08:30:11', '2024-11-05 08:30:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checkouts`
--

CREATE TABLE `checkouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `code` varchar(255) NOT NULL,
  `total_price` int(11) NOT NULL,
  `ticket_date` date NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checkouts`
--

INSERT INTO `checkouts` (`id`, `ticket_id`, `user_id`, `quantity`, `status`, `code`, `total_price`, `ticket_date`, `payment_proof`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 'accepted', 'CHK-1730760975-tJT8qc8Jz0', 20000, '2024-11-05', '1730760975.jpeg', '2024-11-04 15:56:15', '2024-11-04 15:56:36'),
(2, 1, 3, 2, 'pending', 'CHK-1734487686-YO2Akzq2QE', 200000, '2024-12-19', '1734487685.png', '2024-12-17 19:08:06', '2024-12-17 19:08:06'),
(5, 2, 4, 1, 'accepted', 'CHK-1736492517-rgtNseTzDx', 5100, '2025-01-10', 'Online', '2025-01-10 00:01:57', '2025-01-10 00:02:54'),
(7, 2, 4, 2, 'accepted', 'CHK-1736495164-bBDlJMsf7f', 10200, '2025-01-10', 'Online', '2025-01-10 00:46:04', '2025-01-10 00:48:06'),
(14, 2, 4, 1, 'pending', 'CHK-1736559004-Zyjl6CuCAc', 5000, '2025-01-11', NULL, '2025-01-10 18:30:04', '2025-01-10 18:30:04'),
(15, 2, 4, 1, 'pending', 'CHK-1736582438-Fa3dqFzZhM', 10100, '2025-01-11', NULL, '2025-01-11 01:00:38', '2025-01-11 01:00:38'),
(16, 2, 4, 1, 'pending', 'CHK-1736595701-1MCdeENtZd', 10100, '2025-01-11', NULL, '2025-01-11 04:41:41', '2025-01-11 04:41:41'),
(17, 2, 4, 1, 'accepted', 'CHK-1736595754-y0zSxOKnbg', 5000, '2025-01-11', NULL, '2025-01-11 04:42:34', '2025-01-11 04:43:15');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `name`, `slug`, `short_description`, `image`, `video`, `content`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Pantai', 'pantai', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed purus nunc, imperdiet et sem in, volutpat egestas orci. Donec sed gravida mauris. Proin quam velit, fringilla accumsan convallis eget, molestie non quam. Fusce volutpat dui massa, quis sagittis quam luctus eu. Sed eget mollis eros. Nullam imperdiet non mauris sed maximus. Maecenas rhoncus efficitur aliquet. Vestibulum euismod luctus vulputate. Ut consectetur tellus a risus maximus, a laoreet dolor vehicula.', '1730758206.jpeg', 'https://www.youtube.com/watch?v=V7HKaBE6p4c', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed purus nunc, imperdiet et sem in, volutpat egestas orci. Donec sed gravida mauris. Proin quam velit, fringilla accumsan convallis eget, molestie non quam. Fusce volutpat dui massa, quis sagittis quam luctus eu. Sed eget mollis eros. Nullam imperdiet non mauris sed maximus. Maecenas rhoncus efficitur aliquet. Vestibulum euismod luctus vulputate. Ut consectetur tellus a risus maximus, a laoreet dolor vehicula.</p>\r\n\r\n<p>Aenean eu suscipit massa. Duis ut orci at lorem condimentum posuere sit amet id purus. Nulla id ex in eros egestas maximus. Aliquam auctor consequat diam vitae sodales. Nam scelerisque elit at sollicitudin consectetur. Morbi fringilla venenatis dictum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum vestibulum neque id urna pharetra pellentesque. Sed eleifend mi at turpis consectetur luctus. Duis lacus nulla, pharetra vel pellentesque at, mollis ut neque. Sed mattis vehicula sem. Morbi nunc ex, euismod quis erat ut, tincidunt euismod enim. Cras pellentesque in diam ac mattis. Vivamus bibendum placerat faucibus. Aliquam accumsan feugiat dui, et scelerisque neque vulputate eu.</p>', '2024-11-04 15:10:06', '2024-12-27 01:59:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `slug`, `description`, `image`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Toilet', 'toilet', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed purus nunc, imperdiet et sem in, volutpat egestas orci. Donec sed gravida mauris. Proin quam velit, fringilla accumsan convallis eget, molestie non quam. Fusce volutpat dui massa, quis sagittis quam luctus eu. Sed eget mollis eros. Nullam imperdiet non mauris sed maximus. Maecenas rhoncus efficitur aliquet. Vestibulum euismod luctus vulputate. Ut consectetur tellus a risus maximus, a laoreet dolor vehicula.\r\n\r\nAenean eu suscipit massa. Duis ut orci at lorem condimentum posuere sit amet id purus. Nulla id ex in eros egestas maximus. Aliquam auctor consequat diam vitae sodales. Nam scelerisque elit at sollicitudin consectetur. Morbi fringilla venenatis dictum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum vestibulum neque id urna pharetra pellentesque. Sed eleifend mi at turpis consectetur luctus. Duis lacus nulla, pharetra vel pellentesque at, mollis ut neque. Sed mattis vehicula sem. Morbi nunc ex, euismod quis erat ut, tincidunt euismod enim. Cras pellentesque in diam ac mattis. Vivamus bibendum placerat faucibus. Aliquam accumsan feugiat dui, et scelerisque neque vulputate eu.', '1730758269.jpeg', '2024-11-04 15:11:09', '2024-11-04 15:11:09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `file`, `type`, `created_at`, `updated_at`, `user_id`) VALUES
(1, '1730758218.jpeg', 'image', '2024-11-04 15:10:18', '2024-11-04 15:10:18', 1),
(2, '1730820641.jpeg', 'image', '2024-11-05 08:30:41', '2024-11-05 08:30:41', 1),
(3, 'https://www.youtube.com/watch?v=V7HKaBE6p4c', 'video', '2024-12-27 17:26:25', '2024-12-27 17:26:25', 1);

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`id`, `tahun`, `bulan`, `amount`, `created_at`, `updated_at`) VALUES
(1, 2025, 1, 730300.00, '2025-01-25 20:12:13', '2025-01-26 10:04:41'),
(2, 2025, 2, 550000.00, '2025-01-25 20:18:30', '2025-01-25 20:18:30'),
(3, 2025, 3, 10086000.00, '2025-01-26 03:06:10', '2025-01-26 03:06:10'),
(4, 2025, 4, 2050000.00, '2025-01-26 03:32:40', '2025-01-26 03:32:40'),
(5, 2025, 12, 10000.00, '2025-02-01 07:21:10', '2025-02-01 07:21:10');

-- --------------------------------------------------------

--
-- Table structure for table `income_details`
--

CREATE TABLE `income_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `income_id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `facilities_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `metode` enum('manual','online') NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `income_details`
--

INSERT INTO `income_details` (`id`, `income_id`, `ticket_id`, `facilities_id`, `type`, `metode`, `harga_satuan`, `jumlah`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, 0, 'manual', 500000.00, 1.00, 500000.00, '2025-01-25 20:12:13', '2025-01-25 20:12:13'),
(2, 1, 2, NULL, 0, 'manual', 5000.00, 2.00, 10000.00, '2025-01-25 20:12:13', '2025-01-25 20:12:48'),
(3, 1, 1, NULL, 1, 'manual', 100000.00, 2.00, 200000.00, '2025-01-25 20:12:48', '2025-01-25 20:12:48'),
(4, 2, 2, NULL, 0, 'manual', 5000.00, 10.00, 50000.00, '2025-01-25 20:18:30', '2025-01-25 20:18:30'),
(5, 2, 1, NULL, 1, 'manual', 100000.00, 5.00, 500000.00, '2025-01-25 20:18:30', '2025-01-25 20:18:30'),
(6, 3, 2, NULL, 0, 'manual', 5000.00, 12.00, 60000.00, '2025-01-26 03:06:10', '2025-01-26 03:06:10'),
(7, 3, 1, NULL, 1, 'manual', 100000.00, 100.00, 10000000.00, '2025-01-26 03:06:10', '2025-01-26 03:06:10'),
(8, 3, NULL, 1, 2, 'manual', 2000.00, 13.00, 26000.00, '2025-01-26 03:06:10', '2025-01-26 03:06:10'),
(9, 4, 2, NULL, 0, 'manual', 5000.00, 10.00, 50000.00, '2025-01-26 03:32:40', '2025-01-26 03:32:40'),
(10, 4, 1, NULL, 1, 'manual', 100000.00, 20.00, 2000000.00, '2025-01-26 03:32:40', '2025-01-26 03:32:40'),
(11, 1, 2, NULL, 0, 'online', 5000.00, 4.00, 20300.00, '2025-01-26 09:24:07', '2025-01-26 10:04:41'),
(12, 5, 2, NULL, 0, 'manual', 5000.00, 2.00, 10000.00, '2025-02-01 07:21:10', '2025-02-01 07:21:10');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_08_28_050846_create_destinations_table', 1),
(6, '2024_08_28_080231_create_galleries_table', 1),
(7, '2024_08_31_013530_create_news_table', 1),
(8, '2024_08_31_034344_create_products_table', 1),
(9, '2024_09_02_034122_create_facilities_table', 1),
(10, '2024_09_02_043515_create_aparaturs_table', 1),
(11, '2024_09_16_024044_create_tickets_table', 1),
(12, '2024_09_17_134156_create_carts_table', 1),
(13, '2024_09_17_155517_create_checkouts_table', 1),
(14, '2024_09_18_061110_create_payment_informations_table', 1),
(15, '2024_09_18_074845_create_statistics_table', 1),
(17, '2024_11_04_151133_add_user_id_to_some_tables', 1),
(18, '2025_01_18_155926_create_support_objects_table', 2),
(20, '2025_01_20_061628_add_address_to_objek_pendukung_table', 3),
(21, '2025_01_26_003809_create_income_details_table', 4),
(22, '2024_09_20_020031_create_incomes_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image`, `status`, `author`, `slug`, `video`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Belajar Bersama', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed purus nunc, imperdiet et sem in, volutpat egestas orci. Donec sed gravida mauris. Proin quam velit, fringilla accumsan convallis eget, molestie non quam. Fusce volutpat dui massa, quis sagittis quam luctus eu. Sed eget mollis eros. Nullam imperdiet non mauris sed maximus. Maecenas rhoncus efficitur aliquet. Vestibulum euismod luctus vulputate. Ut consectetur tellus a risus maximus, a laoreet dolor vehicula.</p>\r\n\r\n<p>Aenean eu suscipit massa. Duis ut orci at lorem condimentum posuere sit amet id purus. Nulla id ex in eros egestas maximus. Aliquam auctor consequat diam vitae sodales. Nam scelerisque elit at sollicitudin consectetur. Morbi fringilla venenatis dictum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum vestibulum neque id urna pharetra pellentesque. Sed eleifend mi at turpis consectetur luctus. Duis lacus nulla, pharetra vel pellentesque at, mollis ut neque. Sed mattis vehicula sem. Morbi nunc ex, euismod quis erat ut, tincidunt euismod enim. Cras pellentesque in diam ac mattis. Vivamus bibendum placerat faucibus. Aliquam accumsan feugiat dui, et scelerisque neque vulputate eu.</p>', '1730758248.jpeg', 'publish', 'Alan', 'belajar-bersama', '1730758248.mp4', '2024-11-04 15:10:48', '2024-11-04 15:10:48', 1),
(2, 'Belajar Bersama', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam congue turpis nec nulla auctor pharetra fringilla vel metus. Nunc pellentesque purus nec libero ullamcorper gravida. Sed posuere interdum mi, sit amet pretium risus sagittis varius. Sed fringilla tortor eros, nec lacinia magna sagittis sed. Curabitur est risus, bibendum id vulputate venenatis, consectetur euismod massa. Aenean non ornare magna. Nulla facilisi.</p>\r\n\r\n<p>Nullam ut tortor a urna pellentesque rhoncus sed eu nisl. In scelerisque mi id elit interdum, quis scelerisque eros gravida. Vestibulum purus est, bibendum vitae magna eu, ornare feugiat mi. Etiam venenatis lectus eu sem iaculis, vel dignissim enim laoreet. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam in purus nec est dignissim lobortis. Fusce scelerisque consequat ipsum convallis efficitur. Nullam egestas urna at nunc elementum congue. Donec pharetra at sapien sed tempus. Cras egestas, ligula non porta feugiat, ligula est imperdiet orci, eu placerat massa sem eget mi. Sed ut magna quis augue accumsan ultricies et sed odio. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent consectetur pretium varius. Vestibulum enim tellus, convallis vel venenatis sed, porttitor non elit. Morbi vel quam turpis.</p>', '1730821463.jpeg', 'draft', 'Alan', 'belajar-bersama', '1730821800.mp4', '2024-11-05 08:44:23', '2024-12-27 16:57:00', 1),
(4, 'Kertagenah', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ultricies, est vel auctor ultricies, massa ante fringilla nisl, eu euismod augue neque non felis. Vestibulum fermentum quam eget diam vestibulum consequat. Etiam id scelerisque augue. Phasellus non pellentesque est. Mauris fermentum turpis quam, sit amet eleifend lacus placerat ac. Donec eget convallis purus, sed ullamcorper eros. Sed efficitur augue eget dolor dictum, interdum viverra libero condimentum. Proin mollis est id est lacinia, et commodo nunc efficitur. Suspendisse fermentum felis sit amet ipsum egestas viverra. Aliquam mattis massa tortor, nec tincidunt est elementum sit amet. Duis dignissim lorem erat, in facilisis ligula aliquam nec. Pellentesque sed lacus congue sem tincidunt porta. Nam ornare laoreet neque, ac euismod dui laoreet consequat.</p>\r\n\r\n<p>Cras est mi, convallis in mauris ac, molestie tristique purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec imperdiet, nulla ut blandit sagittis, dui nibh laoreet tellus, sit amet iaculis magna turpis sed odio. Mauris sed feugiat augue, at sodales eros. Quisque a elit eu dolor scelerisque molestie vitae ut nulla. Mauris sed nisi accumsan libero gravida mollis. Etiam posuere convallis volutpat. Etiam cursus eros non velit tempus, id commodo elit consequat. Praesent in elementum justo.</p>\r\n\r\n<p>Etiam tempor ornare nisl, a semper neque pretium id. Aliquam placerat purus ac elit vulputate, nec suscipit est fringilla. Aenean ante arcu, interdum in feugiat auctor, vulputate vitae magna. Nam non eleifend sapien, id posuere metus. Nulla ac hendrerit lectus, posuere hendrerit magna. Mauris gravida mi eu est auctor, et aliquam felis pharetra. Donec porttitor blandit orci vitae volutpat. Nunc imperdiet finibus nulla. Mauris non tempus arcu. Sed neque ligula, porttitor eu felis in, cursus feugiat diam. Vestibulum suscipit condimentum hendrerit. Fusce eget ante sit amet quam mattis egestas. Suspendisse nulla libero, mollis iaculis justo vitae, elementum eleifend dui. Mauris pulvinar lorem eget mattis ornare. Ut non congue nunc. Quisque id dui erat.</p>', '1735468957.jpg', 'publish', 'Alan', 'kertagenah', 'https://www.youtube.com/watch?v=Ttfb7P0uEhU', '2024-12-29 03:42:38', '2024-12-29 03:59:10', 1),
(5, 'Wawancara Kepala Desa', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ultricies, est vel auctor ultricies, massa ante fringilla nisl, eu euismod augue neque non felis. Vestibulum fermentum quam eget diam vestibulum consequat. Etiam id scelerisque augue. Phasellus non pellentesque est. Mauris fermentum turpis quam, sit amet eleifend lacus placerat ac. Donec eget convallis purus, sed ullamcorper eros. Sed efficitur augue eget dolor dictum, interdum viverra libero condimentum. Proin mollis est id est lacinia, et commodo nunc efficitur. Suspendisse fermentum felis sit amet ipsum egestas viverra. Aliquam mattis massa tortor, nec tincidunt est elementum sit amet. Duis dignissim lorem erat, in facilisis ligula aliquam nec. Pellentesque sed lacus congue sem tincidunt porta. Nam ornare laoreet neque, ac euismod dui laoreet consequat.</p>\r\n\r\n<p>Cras est mi, convallis in mauris ac, molestie tristique purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec imperdiet, nulla ut blandit sagittis, dui nibh laoreet tellus, sit amet iaculis magna turpis sed odio. Mauris sed feugiat augue, at sodales eros. Quisque a elit eu dolor scelerisque molestie vitae ut nulla. Mauris sed nisi accumsan libero gravida mollis. Etiam posuere convallis volutpat. Etiam cursus eros non velit tempus, id commodo elit consequat. Praesent in elementum justo.</p>\r\n\r\n<p>Etiam tempor ornare nisl, a semper neque pretium id. Aliquam placerat purus ac elit vulputate, nec suscipit est fringilla. Aenean ante arcu, interdum in feugiat auctor, vulputate vitae magna. Nam non eleifend sapien, id posuere metus. Nulla ac hendrerit lectus, posuere hendrerit magna. Mauris gravida mi eu est auctor, et aliquam felis pharetra. Donec porttitor blandit orci vitae volutpat. Nunc imperdiet finibus nulla. Mauris non tempus arcu. Sed neque ligula, porttitor eu felis in, cursus feugiat diam. Vestibulum suscipit condimentum hendrerit. Fusce eget ante sit amet quam mattis egestas. Suspendisse nulla libero, mollis iaculis justo vitae, elementum eleifend dui. Mauris pulvinar lorem eget mattis ornare. Ut non congue nunc. Quisque id dui erat.</p>', '1735469363.jpg', 'draft', 'Dhiaz', 'wawancara-kepala-desa', 'https://www.youtube.com/watch?v=Ttfb7P0uEhU', '2024-12-29 03:49:23', '2024-12-29 03:59:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `objek_pendukung`
--

CREATE TABLE `objek_pendukung` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `objek_pendukung`
--

INSERT INTO `objek_pendukung` (`id`, `name`, `tipe`, `longitude`, `latitude`, `address`, `description`, `image`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Balai Desa Kertanegara', '3', '113.59740028093242', '-7.082876613052554', '', '', NULL, 1, '2025-01-18 10:01:23', '2025-01-18 10:01:23'),
(2, 'WARKOP P. Monalah', '2', '113.60997247772401', '-7.075413414595096', '', '', NULL, 1, '2025-01-18 17:56:25', '2025-01-18 17:56:25'),
(3, 'Kompleks Zall Febriand', '1', '113.63768380458771', '-7.059504381353918', 'WJQQ+GJ5, Lamujang Timur, Pordapor, Kec. Guluk-Guluk, Kabupaten Sumenep, Jawa Timur 69463', '<p>Penginapan</p>', '1737357540.jpeg', 1, '2025-01-18 18:07:41', '2025-01-20 00:19:01');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_informations`
--

CREATE TABLE `payment_informations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `status`, `slug`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Getah Karet', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed purus nunc, imperdiet et sem in, volutpat egestas orci. Donec sed gravida mauris. Proin quam velit, fringilla accumsan convallis eget, molestie non quam. Fusce volutpat dui massa, quis sagittis quam luctus eu. Sed eget mollis eros. Nullam imperdiet non mauris sed maximus. Maecenas rhoncus efficitur aliquet. Vestibulum euismod luctus vulputate. Ut consectetur tellus a risus maximus, a laoreet dolor vehicula.</p>\r\n\r\n<p>Aenean eu suscipit massa. Duis ut orci at lorem condimentum posuere sit amet id purus. Nulla id ex in eros egestas maximus. Aliquam auctor consequat diam vitae sodales. Nam scelerisque elit at sollicitudin consectetur. Morbi fringilla venenatis dictum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum vestibulum neque id urna pharetra pellentesque. Sed eleifend mi at turpis consectetur luctus. Duis lacus nulla, pharetra vel pellentesque at, mollis ut neque. Sed mattis vehicula sem. Morbi nunc ex, euismod quis erat ut, tincidunt euismod enim. Cras pellentesque in diam ac mattis. Vivamus bibendum placerat faucibus. Aliquam accumsan feugiat dui, et scelerisque neque vulputate eu.</p>', 20000.00, '1730758297.jpeg', 'publish', 'getah-karet', '2024-11-04 15:11:37', '2024-11-04 15:11:37', 1);

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bulan` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `jumlah_lakilaki` int(11) NOT NULL,
  `jumlah_perempuan` int(11) NOT NULL,
  `tidak_diketahui` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statistics`
--

INSERT INTO `statistics` (`id`, `bulan`, `tahun`, `jumlah_lakilaki`, `jumlah_perempuan`, `tidak_diketahui`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 1, 2025, 10, 10, 10, '2024-11-04 15:12:03', '2024-11-04 15:12:03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `type` int(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `photo`, `name`, `price`, `description`, `status`, `type`, `created_at`, `updated_at`) VALUES
(1, '1730760564.jpeg', 'Tiket 1', 100000.00, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed purus nunc, imperdiet et sem in, volutpat egestas orci. Donec sed gravida mauris. Proin quam velit, fringilla accumsan convallis eget, molestie non quam. Fusce volutpat dui massa, quis sagittis quam luctus eu. Sed eget mollis eros. Nullam imperdiet non mauris sed maximus. Maecenas rhoncus efficitur aliquet. Vestibulum euismod luctus vulputate. Ut consectetur tellus a risus maximus, a laoreet dolor vehicula.</p>\r\n\r\n<p>Aenean eu suscipit massa. Duis ut orci at lorem condimentum posuere sit amet id purus. Nulla id ex in eros egestas maximus. Aliquam auctor consequat diam vitae sodales. Nam scelerisque elit at sollicitudin consectetur. Morbi fringilla venenatis dictum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum vestibulum neque id urna pharetra pellentesque. Sed eleifend mi at turpis consectetur luctus. Duis lacus nulla, pharetra vel pellentesque at, mollis ut neque. Sed mattis vehicula sem. Morbi nunc ex, euismod quis erat ut, tincidunt euismod enim. Cras pellentesque in diam ac mattis. Vivamus bibendum placerat faucibus. Aliquam accumsan feugiat dui, et scelerisque neque vulputate eu.</p>', 'publish', 1, '2024-11-04 15:49:24', '2025-01-22 23:50:35'),
(2, '1736441742.png', 'Tiket Ayunan', 5000.00, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 'publish', 0, '2025-01-09 09:55:44', '2025-01-09 09:55:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `level` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `level`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, 'admin', '$2y$12$CL9lDMgceIC3yQWz8Sd0xeoawZUidyw3u.3zYlUAPAL0PM7QwWuna', NULL, '2024-11-04 14:57:28', '2024-11-04 14:57:28'),
(2, 'Alan', 'ahmatjailani9@gmail.com', NULL, 'user', '$2y$12$ErLySOp1W7ZG8sG8UXD2suY/n/QjO.PTKBs8sTGTV1SZcD9qpggK6', NULL, '2024-11-04 15:55:21', '2024-11-04 15:55:21'),
(3, 'Muhammd Dhiaz Muliansyah', '1305muhammaddhiaz@gmail.com', NULL, 'user', '$2y$12$03WQRd4v5TCi0nk/91.6bethvxKvnDkliFT6DiXHz9V6iz9HftrIq', NULL, '2024-12-17 17:59:49', '2024-12-17 17:59:49'),
(4, 'Agung', 'sangmoduser@gmail.com', NULL, 'user', '$2y$12$CL9lDMgceIC3yQWz8Sd0xeoawZUidyw3u.3zYlUAPAL0PM7QwWuna', NULL, '2024-11-04 15:55:21', '2024-11-04 15:55:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aparaturs`
--
ALTER TABLE `aparaturs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aparaturs_user_id_foreign` (`user_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_ticket_id_foreign` (`ticket_id`);

--
-- Indexes for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `checkouts_code_unique` (`code`),
  ADD KEY `checkouts_ticket_id_foreign` (`ticket_id`),
  ADD KEY `checkouts_user_id_foreign` (`user_id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `destinations_slug_unique` (`slug`),
  ADD KEY `destinations_user_id_foreign` (`user_id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facilities_slug_unique` (`slug`),
  ADD KEY `facilities_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `galleries_user_id_foreign` (`user_id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income_details`
--
ALTER TABLE `income_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_user_id_foreign` (`user_id`);

--
-- Indexes for table `objek_pendukung`
--
ALTER TABLE `objek_pendukung`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_informations`
--
ALTER TABLE `payment_informations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_informations_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_user_id_foreign` (`user_id`);

--
-- Indexes for table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `statistics_user_id_foreign` (`user_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
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
-- AUTO_INCREMENT for table `aparaturs`
--
ALTER TABLE `aparaturs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checkouts`
--
ALTER TABLE `checkouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `income_details`
--
ALTER TABLE `income_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `objek_pendukung`
--
ALTER TABLE `objek_pendukung`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_informations`
--
ALTER TABLE `payment_informations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aparaturs`
--
ALTER TABLE `aparaturs`
  ADD CONSTRAINT `aparaturs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `checkouts`
--
ALTER TABLE `checkouts`
  ADD CONSTRAINT `checkouts_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkouts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `destinations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `facilities`
--
ALTER TABLE `facilities`
  ADD CONSTRAINT `facilities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `galleries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payment_informations`
--
ALTER TABLE `payment_informations`
  ADD CONSTRAINT `payment_informations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `statistics`
--
ALTER TABLE `statistics`
  ADD CONSTRAINT `statistics_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
