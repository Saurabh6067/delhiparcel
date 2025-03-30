-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 07:20 AM
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
-- Database: `delhi_parcel`
--

-- --------------------------------------------------------

--
-- Table structure for table `branchs`
--

CREATE TABLE `branchs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fulladdress` text NOT NULL,
  `itemcount` varchar(255) NOT NULL,
  `phoneno` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `gst_panno` varchar(255) NOT NULL,
  `gst_panno_img` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `type_logo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `branch_cm` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branchs`
--

INSERT INTO `branchs` (`id`, `fullname`, `email`, `fulladdress`, `itemcount`, `phoneno`, `category`, `gst_panno`, `gst_panno_img`, `pincode`, `type`, `type_logo`, `password`, `status`, `branch_cm`, `created_at`, `updated_at`) VALUES
(5, 'Ravi', 'ravi@gmail.com', 'noida 63', '213', '9087654321', '1', '1234cxcz', 'admin/upload/branch/BID_1736417420.avif', '201301', 'Seller', 'admin/upload/branch/BL_1736417420.webp', 'Ravi@201301', 'active', NULL, '2025-01-09 04:40:20', '2025-01-10 06:43:22'),
(6, 'Anil', 'anil@gmail.com', 'lucknow', '43', '09087654321', '2', '1fgh234cxcz', 'admin/upload/branch/BID_1736421699.webp', '889991', 'Delivery', 'admin/upload/branch/BL_1736421699.avif', 'Anil@889991', 'active', NULL, '2025-01-09 05:51:39', '2025-02-06 00:49:53'),
(8, 'Anish', 'anish@gmail.com', 'noida', '987', '9876543201', '1', '1234cxcz', 'admin/upload/branch/BID_1737190826.webp', '201301', 'Booking', 'admin/upload/branch/BL_1737190826.avif', 'Anish@201301', 'active', '10', '2025-01-18 03:30:26', '2025-01-18 04:25:31');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cat_name` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `cat_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'test 1', 'active', '2025-01-08 02:52:24', '2025-01-08 04:33:02'),
(2, 'test 2', 'active', '2025-01-08 02:54:39', '2025-01-09 07:16:49');

-- --------------------------------------------------------

--
-- Table structure for table `cod`
--

CREATE TABLE `cod` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `delivery_boy_id` varchar(255) NOT NULL,
  `pyment_method` enum('NA','cash','online') NOT NULL DEFAULT 'NA',
  `datetime` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cod`
--

INSERT INTO `cod` (`id`, `order_id`, `delivery_boy_id`, `pyment_method`, `datetime`, `created_at`, `updated_at`) VALUES
(1, '7', '1', 'NA', '31-01-2025 | 11:24:15 AM', '2025-01-31 00:24:15', '2025-01-31 00:24:15'),
(2, '9', '1', 'NA', '01-02-2025 | 01:07:04 PM', '2025-02-01 02:07:04', '2025-02-01 02:07:04'),
(3, '10', '1', 'NA', '01-02-2025 | 03:35:44 PM', '2025-02-01 04:35:44', '2025-02-01 04:35:44'),
(4, '11', '1', 'NA', '03-02-2025 | 10:41:58 AM', '2025-02-02 23:41:58', '2025-02-02 23:41:58'),
(5, '11', '4', 'NA', '03-02-2025 | 10:49:44 AM', '2025-02-02 23:49:44', '2025-02-02 23:49:44'),
(6, '17', '1', 'NA', '05-02-2025 | 02:59:03 PM', '2025-02-05 03:59:03', '2025-02-05 03:59:03');

-- --------------------------------------------------------

--
-- Table structure for table `cod_amount`
--

CREATE TABLE `cod_amount` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` varchar(255) NOT NULL,
  `delivery_boy_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `datetime` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cod_amount`
--

INSERT INTO `cod_amount` (`id`, `amount`, `delivery_boy_id`, `user_id`, `datetime`, `created_at`, `updated_at`) VALUES
(1, '10', '1', '1', '31-01-2025 | 11:24:15 AM', '2025-01-31 05:54:15', '2025-01-31 05:54:15'),
(2, '10', '1', '1', '01-02-2025 15:02:40', '2025-02-01 04:02:40', '2025-02-01 04:02:40'),
(3, '10', '1', '1', '01-02-2025 15:02:43', '2025-02-01 04:02:43', '2025-02-01 04:02:43'),
(4, '10', '1', '1', '03-02-2025 10:55:07', '2025-02-02 23:55:07', '2025-02-02 23:55:07'),
(5, '50', '1', '1', '04-02-2025 20:00:58', '2025-02-04 09:00:58', '2025-02-04 09:00:58');

-- --------------------------------------------------------

--
-- Table structure for table `dlyboy`
--

CREATE TABLE `dlyboy` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `orderRate` varchar(255) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dlyboy`
--

INSERT INTO `dlyboy` (`id`, `name`, `email`, `phone`, `address`, `pincode`, `password`, `orderRate`, `userid`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ravi', 'ravi@gmail.com', '987654321', 'noida', '201301', '12345', '10', 5, 'active', '2025-01-11 00:54:06', '2025-01-24 04:41:12'),
(4, 'Francesca Battle', 'natim@mailinator.com', '59', 'Mollitia est adipisi', '242001', 'Pa$$w0rd!', NULL, 5, 'active', '2025-01-13 23:57:18', '2025-01-24 05:04:19'),
(5, 'Er Anshu', 'anshu@gmail.com', '9876542321', 'noida 62', '201301', '1122', NULL, 1, 'active', '2025-01-24 04:10:38', '2025-01-24 04:10:38'),
(6, 'Gemma Hensley', 'gymiru@mailinator.com', '84', 'Accusantium id volu', '242001', 'Pa$$w0rd!', '11', 1, 'active', '2025-01-27 05:49:25', '2025-01-27 05:49:25');

-- --------------------------------------------------------

--
-- Table structure for table `enquirys`
--

CREATE TABLE `enquirys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `itemno` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gst_panno` varchar(255) NOT NULL,
  `phoneno` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `fulladdress` text NOT NULL,
  `message` longtext NOT NULL,
  `gst_panno_img` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enquirys`
--

INSERT INTO `enquirys` (`id`, `fullname`, `itemno`, `email`, `gst_panno`, `phoneno`, `category`, `fulladdress`, `message`, `gst_panno_img`, `created_at`, `updated_at`) VALUES
(2, 'Preston Bowen', '65', 'sucuwupu@mailinator.com', 'Non eum voluptatem', '+1 (631) 173-5814', '2', 'Occaecat dolore quis', 'Et impedit laborum', NULL, '2025-01-10 00:35:33', '2025-01-10 00:35:33'),
(3, 'Haviva Harrington', '61', 'sybana@mailinator.com', 'Excepteur et autem s', '+1 (762) 248-8647', '1', 'Placeat omnis ipsam', 'Aspernatur ut cupida', NULL, '2025-01-10 00:35:36', '2025-01-10 00:35:36');

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
(5, '2025_01_07_111251_create_services_table', 2),
(6, '2025_01_08_065129_create_pincodes_table', 3),
(7, '2025_01_08_075107_create_categories_table', 4),
(8, '2025_01_08_103846_create_branchs_table', 5),
(9, '2025_01_09_130603_create_enquiry_table', 6),
(10, '2025_01_11_050151_create_dlyboy_table', 7),
(11, '2025_01_11_105929_create_wallets_table', 8),
(12, '2025_01_13_081908_create_orders_table', 9),
(13, '2025_01_27_065927_create_servicetypes_table', 10),
(14, '2025_01_30_092532_create_cod_table', 11),
(15, '2025_02_01_054512_create_cod_amount_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `receiver_cnumber` varchar(255) NOT NULL,
  `receiver_email` varchar(255) NOT NULL,
  `receiver_add` text NOT NULL,
  `receiver_pincode` varchar(10) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `service_title` varchar(255) NOT NULL,
  `service_price` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `seller_id` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `payment_mode` enum('online','COD') NOT NULL,
  `insurance` varchar(10) DEFAULT NULL,
  `order_status` enum('Booked','Item Picked Up','Returned','In Transit','Arrived at Destination','Out for Delivery','Delivered','Not Delivered','Returning to Origin','Out for Delivery to Origin','Cancelled') NOT NULL,
  `status_message` text DEFAULT NULL,
  `parcel_type` varchar(255) DEFAULT NULL,
  `assign_to` varchar(255) DEFAULT NULL,
  `datetime` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `receiver_name`, `receiver_cnumber`, `receiver_email`, `receiver_add`, `receiver_pincode`, `service_type`, `service_title`, `service_price`, `order_id`, `seller_id`, `price`, `payment_mode`, `insurance`, `order_status`, `status_message`, `parcel_type`, `assign_to`, `datetime`, `created_at`, `updated_at`) VALUES
(1, 'mohan', '7890654321', 'mohan@gmail.com', '101, A noida', '201301', 'ex', '250g to 505g', '80', 'DLYBGSA91E', '5', 80.00, 'online', NULL, 'Booked', 'assign', 'delivery', '1', '30-01-2025 | 11:53:27 AM', '2025-01-30 00:53:27', '2025-02-01 02:01:41'),
(2, 'Ravi', '9876540321', 'ravi@gmail.com', 'noida', '201301', 'ex', '250g to 505g', '80', 'DLXTZE8LNC', '5', 160.00, 'COD', 'Yes', 'Cancelled', NULL, 'delivery', NULL, '30-01-2025 | 11:58:47 AM', '2025-01-30 00:58:47', '2025-01-30 04:17:28'),
(3, 'Ravi', '9876540321', 'ravi@gmail.com', 'noida', '201301', 'ex', '250g to 505g', '80', 'DLHG6RHOLT', '5', 160.00, 'COD', 'Yes', 'Cancelled', NULL, 'delivery', NULL, '30-01-2025 | 12:02:45 PM', '2025-01-30 01:02:45', '2025-01-30 01:06:58'),
(4, 'Ravi', '9876543021', 'ravi@gmail.com', 'noida', '201301', 'ex', '250g to 505g', '80', 'DL956SM6D8', '5', 80.00, 'online', NULL, 'Booked', NULL, 'delivery', NULL, '30-01-2025 | 12:05:09 PM', '2025-01-30 01:05:09', '2025-01-30 01:05:09'),
(5, 'Kevyn Pacheco', '9876543210', 'tiqovudoxo@mailinator.com', '63 Sector A, Noida, Uttar Pradesh, India', '201301', 'ss', '1kg to 5kg', '110', 'DLAGLKYDZI', '5', 110.00, 'online', NULL, 'Delivered', NULL, 'delivery', NULL, '30-01-2025 | 12:05:27 PM', '2025-01-30 01:05:27', '2025-01-30 01:08:47'),
(6, 'Duncan Garcia', '9876543021', 'vewoheg@mailinator.com', 'Laboriosam exercita', '242001', 'ex', '505g ot 1kg', '105', 'DLU5M0INDB', '5', 105.00, 'online', NULL, 'Item Picked Up', NULL, 'delivery', NULL, '30-01-2025 | 12:06:07 PM', '2025-01-30 01:06:07', '2025-01-30 04:11:27'),
(7, 'Kevyn Pacheco', '9087654321', 'tiqovudoxo@mailinator.com', 'Cupidatat reiciendis', '201301', 'ex', '250g to 505g', '80', 'DL8LWO6AX2', '5', 110.00, 'COD', NULL, 'Item Picked Up', 'assign to', 'delivery', '1', '31-01-2025 | 11:23:35 AM', '2025-01-31 00:23:35', '2025-01-31 00:24:15'),
(8, 'Kevyn Pacheco', '9087654321', 'tiqovudoxo@mailinator.com', 'Cupidatat reiciendis', '201301', 'ex', '250g to 505g', '80', 'DL0T52ZZF8', '5', 80.00, 'online', NULL, 'Delivered', NULL, 'delivery', '1', '01-02-2025 | 01:00:54 PM', '2025-02-01 02:00:54', '2025-02-01 02:04:00'),
(9, 'Kevyn Pacheco', '9087654321', 'tiqovudoxo@mailinator.com', 'Cupidatat reiciendis', '201301', 'ex', '250g to 505g', '80', 'DLTW5OZKXN', '5', 110.00, 'COD', NULL, 'Booked', NULL, 'delivery', '1', '01-02-2025 | 01:06:50 PM', '2025-02-01 02:06:50', '2025-02-01 02:07:04'),
(10, 'Ravi', '9872654321', 'ravi@gmail.com', 'noida', '201301', 'ex', '250g to 505g', '80', 'DLSH7T9K0B', '5', 110.00, 'COD', NULL, 'Item Picked Up', NULL, 'Pickup', '1', '01-02-2025 | 03:35:26 PM', '2025-02-01 04:35:26', '2025-02-01 04:35:44'),
(11, 'Kevyn Pacheco', '9087654321', 'tiqovudoxo@mailinator.com', 'Cupidatat reiciendis', '201301', 'ex', '250g to 505g', '80', 'DLXLQY1DP0', '5', 110.00, 'COD', NULL, 'Booked', NULL, 'Pickup', '4', '03-02-2025 | 10:41:19 AM', '2025-02-02 23:41:19', '2025-02-02 23:49:44'),
(12, 'Blaine Coffey', '6789054321', 'dexog@mailinator.com', 'Ullam aute ab dolore', '201301', 'ex', '250g to 505g', '80', 'DLDS3JRBT4', '5', 110.00, 'COD', NULL, 'Booked', NULL, 'delivery', NULL, '03-02-2025 | 10:49:22 AM', '2025-02-02 23:49:22', '2025-02-02 23:49:22'),
(13, 'Coffey', '6789054321', 'dexog@mailinator.com', 'Ullam aute ab dolore', '201301', 'SuperExpress', '8 km', '65', 'DL39KRQYX6', '5', 95.00, 'COD', NULL, 'Booked', NULL, 'delivery', NULL, '04-02-2025 | 12:22:27 PM', '2025-02-04 01:22:27', '2025-02-04 01:22:27'),
(14, 'Coffey', '6789054321', 'dexog@mailinator.com', 'noida', '201301', 'SuperExpress', '2 km', '45', 'DLZOYXPXAO', '5', 75.00, 'COD', NULL, 'Booked', NULL, 'Pickup', NULL, '04-02-2025 | 12:37:07 PM', '2025-02-04 01:37:07', '2025-02-04 01:37:07'),
(15, 'Blaine', '6789054321', 'dexog@mailinator.com', 'Ullam aute ab dolore', '201301', 'ex', '250g to 505g', '80', 'DLOYGVU0Q8', '8', 110.00, 'COD', NULL, 'Cancelled', NULL, 'delivery', NULL, '04-02-2025 | 03:22:16 PM', '2025-02-04 04:22:16', '2025-02-04 05:43:50'),
(16, 'Ravi', '9876540321', 'ravi@gmail.com', 'noida 63', '201301', 'SuperExpress', '8 km', '75', 'DLKZCF8O34', '8', 105.00, 'COD', NULL, 'Booked', NULL, 'Pickup', NULL, '04-02-2025 | 06:32:22 PM', '2025-02-04 07:32:22', '2025-02-04 07:32:22'),
(17, 'Blaine Coffey', '6789054321', 'dexog@mailinator.com', 'Ullam aute ab dolore', '201301', 'stss', '300g', '25', 'DLD99XOIA4', '5', 55.00, 'COD', NULL, 'Item Picked Up', NULL, 'delivery', '1', '05-02-2025 | 02:58:39 PM', '2025-02-05 03:58:39', '2025-02-05 03:59:07');

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
-- Table structure for table `pincodes`
--

CREATE TABLE `pincodes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pincodes` varchar(10) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pincodes`
--

INSERT INTO `pincodes` (`id`, `pincodes`, `status`, `created_at`, `updated_at`) VALUES
(1, '201301', 'active', '2025-01-08 01:42:11', '2025-01-08 01:42:11'),
(2, '242001', 'active', '2025-01-08 01:43:04', '2025-01-08 01:43:04'),
(4, '889991', 'inactive', '2025-01-08 01:59:29', '2025-01-08 02:10:58');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `price`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, '505g ot 1kg', '105', 'ex', 'active', '2025-01-07 06:51:45', '2025-01-07 12:30:31'),
(2, '250g to 505g', '80', 'ex', 'active', '2025-01-07 06:51:54', '2025-01-08 00:02:35'),
(4, '300g', '50', 'ex', 'inactive', '2025-01-07 23:22:09', '2025-01-08 01:01:17'),
(5, '1kg to 5kg', '150', 'stex', 'active', '2025-01-07 23:39:52', '2025-02-01 01:59:04'),
(6, '1kg to 5kg', '110', 'ss', 'active', '2025-01-07 23:58:34', '2025-01-08 04:18:55'),
(10, '300g', '55', 'ss', 'inactive', '2025-01-08 00:26:09', '2025-01-08 01:01:26'),
(14, '300g', '25', 'stss', 'active', '2025-01-24 02:38:02', '2025-01-24 02:38:02'),
(15, '505g ot 1kg', '50', 'stss', 'active', '2025-01-24 02:38:34', '2025-01-24 02:38:34'),
(18, '3km', '50', 'se', 'active', '2025-01-26 23:50:46', '2025-01-26 23:57:46'),
(19, '3km', '45', 'stse', 'active', '2025-01-26 23:55:35', '2025-02-03 06:58:12'),
(20, '1km', '5', 'se', 'active', '2025-01-27 00:52:59', '2025-01-27 00:52:59'),
(21, '1km', '4', 'stse', 'active', '2025-01-27 00:53:15', '2025-01-27 00:53:15');

-- --------------------------------------------------------

--
-- Table structure for table `servicetypes`
--

CREATE TABLE `servicetypes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `userId` varchar(255) NOT NULL,
  `services` varchar(255) NOT NULL,
  `servicesType` varchar(255) NOT NULL,
  `servicesId` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `servicetypes`
--

INSERT INTO `servicetypes` (`id`, `userId`, `services`, `servicesType`, `servicesId`, `created_at`, `updated_at`) VALUES
(3, '5', 'ss', 'stss', '14,15', '2025-01-27 02:52:25', '2025-01-27 02:52:25'),
(4, '5', 'ex', 'stex', '16', '2025-01-27 03:51:03', '2025-01-27 03:51:03'),
(5, '5', 'se', 'stse', '19,21', '2025-01-27 05:02:28', '2025-01-27 05:02:28'),
(6, '5', 'ex', 'stex', '5', '2025-02-03 07:04:26', '2025-02-03 07:04:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `gst_no` varchar(255) DEFAULT NULL,
  `gst_img` varchar(255) DEFAULT NULL,
  `pin_code` varchar(255) DEFAULT NULL,
  `type` enum('admin','seller','booking','delivery','delivery_boy') NOT NULL DEFAULT 'admin',
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `phone`, `password`, `address`, `category`, `gst_no`, `gst_img`, `pin_code`, `type`, `logo`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin-DelhiParcel', 'admin@gmail.com', NULL, '9876543210', '$2y$10$a/86BdD3wIXxU/DoeLpsY.dC2b.TgJFoYvKYh61D0lFl7/WS2ps2y', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, 'active', NULL, '2025-01-07 00:40:46', '2025-01-10 02:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `userid` bigint(20) UNSIGNED NOT NULL,
  `c_amount` varchar(255) DEFAULT NULL,
  `d_amount` varchar(255) DEFAULT NULL,
  `total` varchar(255) DEFAULT NULL,
  `datetime` varchar(255) DEFAULT NULL,
  `status` enum('success','cancelled','pending') NOT NULL,
  `adminid` varchar(255) DEFAULT NULL,
  `refno` varchar(255) DEFAULT NULL,
  `msg` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `userid`, `c_amount`, `d_amount`, `total`, `datetime`, `status`, `adminid`, `refno`, `msg`, `created_at`, `updated_at`) VALUES
(1, 5, '5431', NULL, '5431', '30-01-2025 | 12:01:21 PM', 'success', NULL, NULL, 'credit', '2025-01-30 01:01:21', '2025-01-30 01:01:21'),
(2, 5, NULL, '160', '5271', '30-01-2025 | 12:02:45 PM', 'success', NULL, NULL, 'debit', '2025-01-30 01:02:45', '2025-01-30 01:02:45'),
(3, 5, '10', NULL, '5281', '30-01-2025 | 12:03:37 PM', 'success', '1', NULL, 'add', '2025-01-30 01:03:37', '2025-01-30 01:03:37'),
(4, 5, NULL, '80', '5201', '30-01-2025 | 12:05:09 PM', 'success', NULL, NULL, 'debit', '2025-01-30 01:05:09', '2025-01-30 01:05:09'),
(5, 5, NULL, '110', '5091', '30-01-2025 | 12:05:27 PM', 'success', NULL, NULL, 'debit', '2025-01-30 01:05:27', '2025-01-30 01:05:27'),
(6, 5, NULL, '105', '4986', '30-01-2025 | 12:06:07 PM', 'success', NULL, NULL, 'debit', '2025-01-30 01:06:07', '2025-01-30 01:06:07'),
(7, 5, '160.00', NULL, '5146', '30-01-2025 | 12:06:58 PM', 'success', NULL, NULL, 'Order Cancelled', '2025-01-30 01:06:58', '2025-01-30 01:06:58'),
(8, 5, '160.00', NULL, '5306', '30-01-2025 | 03:17:28 PM', 'success', NULL, NULL, 'Order Cancelled', '2025-01-30 04:17:28', '2025-01-30 04:17:28'),
(10, 5, NULL, '110', '5196', '31-01-2025 | 11:23:35 AM', 'success', NULL, NULL, 'debit', '2025-01-31 00:23:35', '2025-01-31 00:23:35'),
(11, 5, NULL, '80', '5116', '01-02-2025 | 01:00:54 PM', 'success', NULL, NULL, 'debit', '2025-02-01 02:00:54', '2025-02-01 02:00:54'),
(12, 5, NULL, '110', '5006', '01-02-2025 | 01:06:50 PM', 'success', NULL, NULL, 'debit', '2025-02-01 02:06:50', '2025-02-01 02:06:50'),
(13, 5, NULL, '110', '4896', '01-02-2025 | 03:35:26 PM', 'success', NULL, NULL, 'debit', '2025-02-01 04:35:26', '2025-02-01 04:35:26'),
(14, 5, NULL, '110', '4786', '03-02-2025 | 10:41:19 AM', 'success', NULL, NULL, 'debit', '2025-02-02 23:41:19', '2025-02-02 23:41:19'),
(15, 5, NULL, '110', '4676', '03-02-2025 | 10:49:22 AM', 'success', NULL, NULL, 'debit', '2025-02-02 23:49:22', '2025-02-02 23:49:22'),
(16, 5, NULL, '2733.6', '1942.4', '04-02-2025 | 12:12:58 PM', 'success', NULL, NULL, 'debit', '2025-02-04 01:12:58', '2025-02-04 01:12:58'),
(17, 5, NULL, '95', '1847.4', '04-02-2025 | 12:14:09 PM', 'success', NULL, NULL, 'debit', '2025-02-04 01:14:09', '2025-02-04 01:14:09'),
(18, 5, NULL, '95', '1752.4', '04-02-2025 | 12:22:27 PM', 'success', NULL, NULL, 'debit', '2025-02-04 01:22:27', '2025-02-04 01:22:27'),
(19, 5, NULL, '75', '1677.4', '04-02-2025 | 12:37:07 PM', 'success', NULL, NULL, 'debit', '2025-02-04 01:37:07', '2025-02-04 01:37:07'),
(20, 8, '5000', NULL, '5000', '04-02-2025 | 03:21:46 PM', 'success', NULL, NULL, NULL, '2025-02-04 04:21:46', '2025-02-04 04:21:46'),
(21, 8, NULL, '110', '4890', '04-02-2025 | 03:22:16 PM', 'success', NULL, NULL, NULL, '2025-02-04 04:22:16', '2025-02-04 04:22:16'),
(22, 8, '110.00', NULL, '5000', '04-02-2025 | 04:43:50 PM', 'success', NULL, NULL, 'Order Cancelled', '2025-02-04 05:43:50', '2025-02-04 05:43:50'),
(23, 8, '10', NULL, '5010', '04-02-2025 | 04:51:44 PM', 'success', NULL, NULL, 'credit', '2025-02-04 05:51:44', '2025-02-04 05:51:44'),
(24, 8, NULL, '5', '5005', '04-02-2025 | 04:52:14 PM', 'success', '1', NULL, 'didact', '2025-02-04 05:52:14', '2025-02-04 05:52:14'),
(25, 8, '5', NULL, '5010', '04-02-2025 | 04:56:51 PM', 'success', '1', NULL, 'add', '2025-02-04 05:56:51', '2025-02-04 05:56:51'),
(26, 8, NULL, '105', '4905', '04-02-2025 | 06:32:22 PM', 'success', NULL, NULL, 'debit', '2025-02-04 07:32:22', '2025-02-04 07:32:22'),
(27, 5, NULL, '55', '1622.4', '05-02-2025 | 02:58:39 PM', 'success', NULL, NULL, 'debit', '2025-02-05 03:58:39', '2025-02-05 03:58:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branchs`
--
ALTER TABLE `branchs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branchs_email_unique` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cod`
--
ALTER TABLE `cod`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cod_amount`
--
ALTER TABLE `cod_amount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dlyboy`
--
ALTER TABLE `dlyboy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enquirys`
--
ALTER TABLE `enquirys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enquiry_email_unique` (`email`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pincodes`
--
ALTER TABLE `pincodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `servicetypes`
--
ALTER TABLE `servicetypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branchs`
--
ALTER TABLE `branchs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cod`
--
ALTER TABLE `cod`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cod_amount`
--
ALTER TABLE `cod_amount`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dlyboy`
--
ALTER TABLE `dlyboy`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `enquirys`
--
ALTER TABLE `enquirys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pincodes`
--
ALTER TABLE `pincodes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `servicetypes`
--
ALTER TABLE `servicetypes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
