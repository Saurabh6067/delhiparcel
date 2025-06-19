-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 06, 2025 at 03:40 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u218051520_delhiparceldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_cod_history`
--

CREATE TABLE `admin_cod_history` (
  `id` int(11) NOT NULL,
  `branch_id` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `datetime` varchar(100) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `updated_at` varchar(100) DEFAULT NULL,
  `created_at` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_cod_history`
--

INSERT INTO `admin_cod_history` (`id`, `branch_id`, `amount`, `type`, `datetime`, `remarks`, `updated_at`, `created_at`) VALUES
(1, '4', '1', 'Received', '29-04-2025 | 03:14:04 PM', 'e', '2025-04-29 09:44:04', '2025-04-29 09:44:04'),
(2, '4', '1', 'Received', '29-04-2025 | 03:22:07 PM', 'd', '2025-04-29 09:52:07', '2025-04-29 09:52:07'),
(3, '4', '1', 'Received', '29-04-2025 | 03:29:36 PM', 'e', '2025-04-29 09:59:36', '2025-04-29 09:59:36');

-- --------------------------------------------------------

--
-- Table structure for table `admin_total_cod`
--

CREATE TABLE `admin_total_cod` (
  `id` int(11) NOT NULL,
  `branch_id` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `updated_at` varchar(100) DEFAULT NULL,
  `created_at` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_total_cod`
--

INSERT INTO `admin_total_cod` (`id`, `branch_id`, `amount`, `updated_at`, `created_at`) VALUES
(1, '4', '3', '2025-04-29 09:59:36', '2025-04-29 09:44:04');

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
  `branch_otp` varchar(6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branchs`
--

INSERT INTO `branchs` (`id`, `fullname`, `email`, `fulladdress`, `itemcount`, `phoneno`, `category`, `gst_panno`, `gst_panno_img`, `pincode`, `type`, `type_logo`, `password`, `status`, `branch_cm`, `branch_otp`, `created_at`, `updated_at`) VALUES
(15, 'Bhajan', 'caraj1992@gmail.com', 'Thane Ke Samne', '1', '9876543210', '1', '1', NULL, '110001,110002,110003,110004,110005,110006,110007,110008,110009,110010', 'Delivery', NULL, 'Bhajanpura@1234', 'active', NULL, NULL, '2025-05-03 10:52:35', '2025-05-06 11:23:05'),
(16, 'Karol', 'yadavboys91@gmail.com', 'Thane Ke Samne', '2', '9988776655', '2', '2', NULL, '110011,110012,110013,110014,110015,110016,110017,110018,110019', 'Delivery', NULL, 'Delivery@9988776655', 'active', NULL, NULL, '2025-05-03 10:53:48', '2025-05-06 11:24:33'),
(17, 'Aiims', 'arenterprise1516@gmail.com', 'Thane Ke Samne', '1', '7065193555', '2', '2', NULL, '110021,110022,110023,110024,110025,110026,110027,110028,110029,110030', 'Delivery', NULL, 'Delivery@7065193555', 'active', NULL, NULL, '2025-05-03 10:55:56', '2025-05-03 10:55:56'),
(18, 'Uttam n', 'aventerprise1516@gmail.com', 'Thane Ke Samne', '1', '9876541122', '1', '1', NULL, '110031,110032,110033,110034,110035,110036,110037,110038,110039,110040', 'Delivery', NULL, 'Delivery@9876541122', 'active', NULL, NULL, '2025-05-03 10:56:56', '2025-05-06 11:25:19');

-- --------------------------------------------------------

--
-- Table structure for table `branch_cod_history`
--

CREATE TABLE `branch_cod_history` (
  `id` int(11) NOT NULL,
  `delivery_boy_id` varchar(50) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `branch_id` varchar(100) DEFAULT NULL,
  `updated_at` varchar(50) DEFAULT NULL,
  `created_at` varchar(50) DEFAULT NULL,
  `datetime` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch_cod_history`
--

INSERT INTO `branch_cod_history` (`id`, `delivery_boy_id`, `amount`, `type`, `branch_id`, `updated_at`, `created_at`, `datetime`) VALUES
(1, '8', '1', 'Received', '3', '2025-04-29 08:06:11', '2025-04-29 08:06:11', '29-04-2025 | 01:36:10 PM'),
(2, '8', '1', 'Received', '3', '2025-04-29 08:06:40', '2025-04-29 08:06:40', '29-04-2025 | 01:36:40 PM'),
(3, '8', '10', 'Received', '4', '2025-04-29 08:37:21', '2025-04-29 08:37:21', '29-04-2025 | 02:07:21 PM'),
(4, NULL, '1', 'Debited', '4', '2025-04-29 09:44:04', '2025-04-29 09:44:04', '29-04-2025 | 03:14:04 PM'),
(5, '8', '1', 'Debited', '4', '2025-04-29 09:52:07', '2025-04-29 09:52:07', '29-04-2025 | 03:22:07 PM'),
(6, NULL, '1', 'Debited', '4', '2025-04-29 09:59:36', '2025-04-29 09:59:36', '29-04-2025 | 03:29:36 PM'),
(7, '24', '30', 'Received', '17', '2025-05-06 12:31:46', '2025-05-06 12:31:46', '06-05-2025 | 06:01:46 PM'),
(8, '24', '500', 'Received', '17', '2025-05-06 12:32:49', '2025-05-06 12:32:49', '06-05-2025 | 06:02:49 PM'),
(9, '24', '3500', 'Received', '17', '2025-05-06 12:33:40', '2025-05-06 12:33:40', '06-05-2025 | 06:03:40 PM');

-- --------------------------------------------------------

--
-- Table structure for table `branch_total_cod`
--

CREATE TABLE `branch_total_cod` (
  `id` int(11) NOT NULL,
  `delivery_boy_id` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `branch_id` varchar(100) DEFAULT NULL,
  `updated_at` varchar(100) DEFAULT NULL,
  `created_at` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch_total_cod`
--

INSERT INTO `branch_total_cod` (`id`, `delivery_boy_id`, `amount`, `branch_id`, `updated_at`, `created_at`) VALUES
(1, '8', '2', '3', '2025-04-29 08:06:40', '2025-04-29 08:06:11'),
(2, '8', '8', '4', '2025-04-29 09:59:36', '2025-04-29 08:37:21'),
(3, '24', '4030', '17', '2025-05-06 12:33:40', '2025-05-06 12:31:46');

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
(1, 'Books', 'active', '2025-04-05 12:33:09', '2025-04-05 12:33:09'),
(2, 'Giting Item', 'active', '2025-04-05 12:33:18', '2025-04-05 12:33:18');

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
(1, '1', '24', 'NA', '04-05-2025 | 05:24:41 PM', '2025-04-26 03:57:04', '2025-05-04 11:54:41'),
(2, '3', '24', 'NA', '06-05-2025 | 05:48:06 PM', '2025-04-26 04:58:18', '2025-05-06 12:18:06'),
(3, '4', '8', 'NA', '28-04-2025 | 01:29:36 PM', '2025-04-26 10:11:19', '2025-04-28 02:29:36'),
(4, '2', '8', 'NA', '26-04-2025 | 09:43:09 PM', '2025-04-26 10:43:09', '2025-04-26 10:43:09');

-- --------------------------------------------------------

--
-- Table structure for table `cod_amount`
--

CREATE TABLE `cod_amount` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `delivery_boy_id` varchar(255) NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `transfer_to_branch` varchar(100) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `affected_orders` varchar(100) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `datetime` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cod_amount`
--

INSERT INTO `cod_amount` (`id`, `amount`, `delivery_boy_id`, `user_id`, `transfer_to_branch`, `remarks`, `affected_orders`, `type`, `datetime`, `created_at`, `updated_at`) VALUES
(1, '70', '8', '1', NULL, NULL, NULL, 'Credited', '28-04-2025 | 08:51:20 PM', '2025-04-28 09:51:20', '2025-04-28 09:51:20'),
(4, '5', '8', '8', NULL, 'hello', NULL, NULL, '28-04-2025 | 09:36:32 PM', '2025-04-28 10:36:32', '2025-04-28 10:36:32'),
(5, '10', '8', '8', NULL, 'ok', NULL, NULL, '28-04-2025 | 09:37:19 PM', '2025-04-28 10:37:19', '2025-04-28 10:37:19'),
(6, '5', '8', '8', NULL, NULL, NULL, 'Debited', '28-04-2025 | 09:38:29 PM', '2025-04-28 10:38:29', '2025-04-28 10:38:29'),
(7, '1', '8', '8', NULL, 'dd', NULL, 'Debited', '29-04-2025 | 01:33:45 PM', '2025-04-29 02:33:45', '2025-04-29 02:33:45'),
(8, '1', '8', '8', NULL, 'dd', NULL, 'Debited', '29-04-2025 | 01:36:10 PM', '2025-04-29 02:36:11', '2025-04-29 02:36:11'),
(9, '1', '8', '8', NULL, 'ss', NULL, 'Debited', '29-04-2025 | 01:36:40 PM', '2025-04-29 02:36:40', '2025-04-29 02:36:40'),
(10, '10', '8', '8', NULL, 'ee', NULL, 'Debited', '29-04-2025 | 02:07:21 PM', '2025-04-29 03:07:21', '2025-04-29 03:07:21'),
(11, '30', '24', '1', NULL, NULL, NULL, 'Credited', '04-05-2025 | 05:25:36 PM', '2025-05-04 11:55:36', '2025-05-04 11:55:36'),
(12, '30', '24', '24', NULL, 'transferred', NULL, 'Debited', '06-05-2025 | 06:01:46 PM', '2025-05-06 12:31:46', '2025-05-06 12:31:46'),
(13, '4000', '24', '3', NULL, NULL, NULL, 'Credited', '06-05-2025 | 06:02:23 PM', '2025-05-06 12:32:23', '2025-05-06 12:32:23'),
(14, '500', '24', '24', NULL, NULL, NULL, 'Debited', '06-05-2025 | 06:02:49 PM', '2025-05-06 12:32:49', '2025-05-06 12:32:49'),
(15, '3500', '24', '24', NULL, NULL, NULL, 'Debited', '06-05-2025 | 06:03:40 PM', '2025-05-06 12:33:40', '2025-05-06 12:33:40'),
(16, '30', '20', '8', NULL, NULL, NULL, 'Credited', '06-05-2025 | 08:37:57 PM', '2025-05-06 15:07:57', '2025-05-06 15:07:57'),
(17, '30', '20', '8', NULL, NULL, NULL, 'Credited', '06-05-2025 | 08:39:00 PM', '2025-05-06 15:09:00', '2025-05-06 15:09:00'),
(18, '30', '20', '8', NULL, NULL, NULL, 'Credited', '06-05-2025 | 08:46:15 PM', '2025-05-06 15:16:15', '2025-05-06 15:16:15'),
(19, '30', '20', '8', NULL, NULL, NULL, 'Credited', '06-05-2025 | 08:47:54 PM', '2025-05-06 15:17:54', '2025-05-06 15:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `cod_wallet`
--

CREATE TABLE `cod_wallet` (
  `id` int(11) NOT NULL,
  `delivery_boy_id` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `updated_at` varchar(100) DEFAULT NULL,
  `created_at` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cod_wallet`
--

INSERT INTO `cod_wallet` (`id`, `delivery_boy_id`, `amount`, `type`, `updated_at`, `created_at`) VALUES
(1, '8', '52', NULL, '2025-04-29 08:37:21', '2025-04-28 14:17:09'),
(2, '24', '0', NULL, '2025-05-06 12:33:40', '2025-05-04 11:55:36'),
(3, '20', '120', NULL, '2025-05-06 15:17:54', '2025-05-06 15:07:57');

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
  `userid` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dlyboy`
--

INSERT INTO `dlyboy` (`id`, `name`, `email`, `phone`, `address`, `pincode`, `password`, `orderRate`, `userid`, `status`, `created_at`, `updated_at`) VALUES
(20, 'Vini', 'vini@gmail.com', '9876543210', 'Thane Ke Samne', '110001,110002,110003,110004,110005', 'vini@123', '10', 15, 'active', '2025-05-03 10:58:28', '2025-05-03 10:58:28'),
(21, 'Ajay', 'ajay.aryis@gmail.com', '7065193555', 'Police thane ke samne', '110006,110007,110008,110009,110010', 'ajay@123', '12', 15, 'active', '2025-05-03 11:00:49', '2025-05-03 11:00:49'),
(22, 'Jatin', 'jatin@gmail.com', '9876543210', 'Police thane ke samne', '110011,110012,110013,110014,110015', 'jatin@123', '15', 16, 'active', '2025-05-03 11:02:49', '2025-05-03 11:02:49'),
(23, 'Preet', 'preet@gmail.com', '9988771122', 'Police thane ke samne', '110016,110017,110018,110019,110020', 'preet@123', '20', 16, 'active', '2025-05-03 11:03:29', '2025-05-03 11:03:29'),
(24, 'mukesh', 'mukesh@gmail.com', '8700663512', 'Kanpur Dehat', '110021,110022,110023,110024,110025', '123', '20', 17, 'active', '2025-05-04 11:54:22', '2025-05-06 11:31:42'),
(25, 'Neeraj', 'neeraj@gmail.com', '9696969696', 'neeraj ka ghar', '110026,110027,110028,110029,110030', '123', '30', 17, 'active', '2025-05-06 11:36:32', '2025-05-06 11:39:27');

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
  `pinCode` varchar(6) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `feed_backs`
--

CREATE TABLE `feed_backs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `datetime` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(19, '2014_10_12_000000_create_users_table', 1),
(20, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(21, '2019_08_19_000000_create_failed_jobs_table', 1),
(22, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(23, '2025_01_07_111251_create_services_table', 1),
(24, '2025_01_08_065129_create_pincodes_table', 1),
(25, '2025_01_08_075107_create_categories_table', 1),
(26, '2025_01_08_103846_create_branchs_table', 1),
(27, '2025_01_09_130603_create_enquiry_table', 1),
(28, '2025_01_11_050151_create_dlyboy_table', 1),
(29, '2025_01_11_105929_create_wallets_table', 1),
(30, '2025_01_13_081908_create_orders_table', 1),
(31, '2025_01_27_065927_create_servicetypes_table', 1),
(32, '2025_01_30_092532_create_cod_table', 1),
(33, '2025_02_01_054512_create_cod_amount_table', 1),
(34, '2025_02_25_053801_create_web_orders_table', 1),
(35, '2025_03_04_093130_create_order_cod_table', 1),
(36, '2025_03_15_131004_create_feed_backs_table', 1),
(37, '2025_04_16_093551_create_order_histories_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pickupAddress` text DEFAULT NULL,
  `deliveryAddress` text DEFAULT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `receiver_cnumber` varchar(255) NOT NULL,
  `receiver_email` varchar(255) NOT NULL,
  `receiver_add` text NOT NULL,
  `receiver_pincode` varchar(10) NOT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `sender_number` varchar(255) DEFAULT NULL,
  `sender_email` varchar(255) DEFAULT NULL,
  `sender_address` text DEFAULT NULL,
  `sender_pincode` varchar(100) DEFAULT NULL,
  `service_type` varchar(255) NOT NULL,
  `service_title` varchar(255) NOT NULL,
  `service_price` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `seller_id` varchar(255) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `payment_mode` enum('online','COD') NOT NULL,
  `codAmount` varchar(255) DEFAULT NULL,
  `insurance` varchar(10) DEFAULT NULL,
  `order_status` enum('Booked','Item Picked Up','Returned','In Transit','Arrived at Destination','Out for Delivery','Delivered','Not Delivered','Returning to Origin','Out for Delivery to Origin','Cancelled','Item Not Picked Up','Delivered to branch','Delivered to near by branch') NOT NULL,
  `status_message` text DEFAULT NULL,
  `parcel_type` varchar(255) DEFAULT NULL,
  `assign_to` varchar(255) DEFAULT NULL,
  `assign_by` varchar(255) DEFAULT NULL,
  `sender_order_pin` varchar(255) DEFAULT NULL,
  `sender_order_pin_by` varchar(255) DEFAULT NULL,
  `sender_order_status` enum('Pending','Processing','Delivered') DEFAULT NULL,
  `otp` varchar(200) DEFAULT NULL,
  `cod_submitted` enum('false','true') NOT NULL,
  `datetime` varchar(255) NOT NULL,
  `delivery_time` varchar(100) DEFAULT NULL,
  `transfer_other_branch` enum('false','true') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `pickupAddress`, `deliveryAddress`, `receiver_name`, `receiver_cnumber`, `receiver_email`, `receiver_add`, `receiver_pincode`, `sender_name`, `sender_number`, `sender_email`, `sender_address`, `sender_pincode`, `service_type`, `service_title`, `service_price`, `order_id`, `seller_id`, `price`, `payment_mode`, `codAmount`, `insurance`, `order_status`, `status_message`, `parcel_type`, `assign_to`, `assign_by`, `sender_order_pin`, `sender_order_pin_by`, `sender_order_status`, `otp`, `cod_submitted`, `datetime`, `delivery_time`, `transfer_other_branch`, `created_at`, `updated_at`) VALUES
(1, 'NA', 'NA', 'Khushnasib', '9876543210', 'abc@gmail.com', 'q\r\nqq', '110027', 'Saurabh', '9876543210', 'fodetynohe@mailinator.com', 'Enim eum voluptatem', '110001', 'ex', '200 gm', '60.00', 'DLSOQDUHPR', '15', '60.00', 'COD', '30', NULL, 'Out for Delivery', 'Done', 'Direct', '24', '24', NULL, NULL, NULL, '9937', 'false', '04-05-2025 | 05:17:10 PM', '321', 'true', '2025-05-04 17:17:10', '2025-05-04 11:55:36'),
(2, 'Bhajanpura Police Station, Block C 2, Bhajanpura, Tukhmirpur, Delhi, India', 'Pratap Nagar, Gulabi Bagh, Delhi, India', 'Vijay', '9953187035', 'vijay@gmail.com', 'Pratap Nagar, Gulabi Bagh, Delhi, India', '110015', 'Pradep', '9868385152', 'pradeep@gmail.com', 'Bhajanpura Police Station, Block C 2, Bhajanpura, Tukhmirpur, Delhi, India', '110006', 'SuperExpress', '13 km', '130.00', 'DLZAPKQPDJ', '15', '130.00', 'COD', '1000', NULL, 'Delivered to branch', NULL, 'Direct', '', NULL, NULL, NULL, NULL, '7822', 'false', '04-05-2025 | 11:21:10 PM', NULL, 'false', '2025-05-04 23:21:10', '2025-05-04 18:00:36'),
(3, 'NA', 'NA', 'Deepti', '9658965895', 'depti@gmail.com', 'd', '110025', 'Rahul S', '9876543211', 'Rahul@gmail.com', 'g', '110007', 'ex', '200 gm', '110.00', 'DL5QVYE20H', '15', '110.00', 'COD', '4000', NULL, 'Delivered', NULL, 'Direct', '24', '24', NULL, NULL, NULL, '2811', 'false', '06-05-2025 | 05:14:03 PM', '281', 'true', '2025-05-06 17:14:03', '2025-05-06 12:32:23'),
(4, 'NA', 'NA', 'khan', '9090909090', 'fodetynohe@mailinator.com', 'Enim eum voluptatem', '110027', 'sam', '9090909090', 'wuvy@mailinator.com', 'Delectus occaecat d', '110001', 'ex', '200 gm', '60.00', 'DLTUOP9AC9', '15', '60.00', 'COD', '30', NULL, 'Delivered to branch', NULL, 'Direct', '', NULL, NULL, NULL, NULL, '8238', 'false', '06-05-2025 | 06:48:56 PM', NULL, 'false', '2025-05-06 18:48:56', '2025-05-06 13:20:44'),
(5, 'NA', 'NA', 'Saurabh Singh', '8318259972', 'saurabh6067@gmail.com', 'Panna ka Pura Post Dharampur Kaushmabi', '110027', 'Sender', '8318259972', 'saurabh6067@gmail.com', 'Panna ka Pura Post Dharampur Kaushmabi', '110001', 'ex', '200 gm', '60.00', 'DLWDOUOFGH', '15', '60.00', 'COD', '12', NULL, 'Booked', NULL, 'Direct', '20', NULL, NULL, NULL, NULL, NULL, 'false', '06-05-2025 | 07:03:30 PM', NULL, 'false', '2025-05-06 19:03:30', '2025-05-06 19:03:30'),
(6, 'NA', 'NA', 'Ravi', '9123456789', 'ravi@gmail.com', 'j', '110025', 'U', '9876543211', 'U@gmail.com', 'GH', '110039', 'ss', '200 gm', '40.00', 'DLBZEKOBDS', '18', '40.00', 'COD', '900', NULL, 'Booked', NULL, 'Direct', NULL, NULL, NULL, NULL, NULL, NULL, 'false', '06-05-2025 | 07:36:11 PM', NULL, 'false', '2025-05-06 19:36:11', '2025-05-06 19:36:11'),
(7, 'Bhajanpura Police Station, Block C 2, Bhajanpura, Tukhmirpur, Delhi, India', 'Burari Hospital, Burari Road, Kaushik Enclave, Shankarpura, Burari, Delhi, India', 'Rahul', '9123456789', 'rahul@gmail.com', 'Burari Hospital, Burari Road, Kaushik Enclave, Shankarpura, Burari, Delhi, India', '110025', 'deepika', '9876543211', 'deep@gmail.com', 'Bhajanpura Police Station, Block C 2, Bhajanpura, Tukhmirpur, Delhi, India', '110002', 'SuperExpress', '14 km', '135.00', 'DLZDUYPIS4', '15', '135.00', 'COD', '25', NULL, 'Booked', NULL, 'Direct', '20', NULL, NULL, NULL, NULL, NULL, 'false', '06-05-2025 | 07:50:21 PM', NULL, 'false', '2025-05-06 19:50:21', '2025-05-06 19:50:21'),
(8, 'NA', 'NA', 'khan', '9876543210', 'wuvy@mailinator.com', 'Delectus occaecat d', '110027', 'samar', '9876543210', 'fodetynohe@mailinator.com', 'Enim eum voluptatem', '110001', 'ex', '200 gm', '60.00', 'DLZQ1OAPXC', '15', '60.00', 'COD', '30', NULL, 'Delivered', 'jii', 'Direct', '20', '20', NULL, NULL, NULL, '7527', 'false', '06-05-2025 | 08:14:32 PM', '296', 'false', '2025-05-06 20:14:32', '2025-05-06 15:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `order_cod`
--

CREATE TABLE `order_cod` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `userid` bigint(20) UNSIGNED NOT NULL,
  `debit_by` bigint(20) UNSIGNED DEFAULT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `order_histories`
--

CREATE TABLE `order_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  `tracking_id` varchar(255) DEFAULT NULL,
  `datetime` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_histories`
--

INSERT INTO `order_histories` (`id`, `order_id`, `tracking_id`, `datetime`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'DL8FTZ38R3', '04-05-2025 | 03:56:26 PM', 'Booked', '2025-05-04 10:26:26', '2025-05-04 10:26:26'),
(2, NULL, 'DL8FTZ38R3', '04-05-2025 | 04:05:37 PM', 'item Picked Up', '2025-05-04 10:35:37', '2025-05-04 10:35:37'),
(3, NULL, 'DL8FTZ38R3', '04-05-2025 | 04:07:09 PM', 'Delivered to branch', '2025-05-04 10:37:09', '2025-05-04 10:37:09'),
(4, NULL, 'DL8FTZ38R3', '04-05-2025 | 04:10:23 PM', 'Transit', '2025-05-04 10:40:23', '2025-05-04 10:40:23'),
(5, NULL, 'DL2X0GIVY9', '04-05-2025 | 04:39:17 PM', 'Booked', '2025-05-04 11:09:17', '2025-05-04 11:09:17'),
(6, NULL, 'DL2X0GIVY9', '04-05-2025 | 04:41:52 PM', 'item Picked Up', '2025-05-04 11:11:52', '2025-05-04 11:11:52'),
(7, NULL, 'DL2X0GIVY9', '04-05-2025 | 04:44:27 PM', 'Delivered to branch', '2025-05-04 11:14:27', '2025-05-04 11:14:27'),
(8, NULL, 'DL2X0GIVY9', '04-05-2025 | 04:44:52 PM', 'Transit', '2025-05-04 11:14:52', '2025-05-04 11:14:52'),
(9, NULL, 'DLSOQDUHPR', '04-05-2025 | 05:17:10 PM', 'Booked', '2025-05-04 11:47:10', '2025-05-04 11:47:10'),
(10, NULL, 'DLSOQDUHPR', '04-05-2025 | 05:17:46 PM', 'item Picked Up', '2025-05-04 11:47:46', '2025-05-04 11:47:46'),
(11, NULL, 'DLSOQDUHPR', '04-05-2025 | 05:18:10 PM', 'Delivered to branch', '2025-05-04 11:48:10', '2025-05-04 11:48:10'),
(12, NULL, 'DLSOQDUHPR', '04-05-2025 | 05:19:09 PM', 'Transit', '2025-05-04 11:49:09', '2025-05-04 11:49:09'),
(13, NULL, 'DLSOQDUHPR', '04-05-2025 | 05:22:25 PM', 'Order Reached in Near By Hub', '2025-05-04 11:52:25', '2025-05-04 11:52:25'),
(14, NULL, 'DLSOQDUHPR', '04-05-2025 | 05:25:36 PM', 'Delivered', '2025-05-04 11:55:36', '2025-05-04 11:55:36'),
(15, NULL, 'DLZAPKQPDJ', '04-05-2025 | 11:21:10 PM', 'Booked', '2025-05-04 17:51:10', '2025-05-04 17:51:10'),
(16, NULL, 'DLZAPKQPDJ', '04-05-2025 | 11:28:44 PM', 'item Picked Up', '2025-05-04 17:58:44', '2025-05-04 17:58:44'),
(17, NULL, 'DLZAPKQPDJ', '04-05-2025 | 11:30:36 PM', 'Delivered to branch', '2025-05-04 18:00:36', '2025-05-04 18:00:36'),
(18, NULL, 'DL5QVYE20H', '06-05-2025 | 05:14:03 PM', 'Booked', '2025-05-06 11:44:03', '2025-05-06 11:44:03'),
(19, NULL, 'DL5QVYE20H', '06-05-2025 | 05:32:50 PM', 'item Picked Up', '2025-05-06 12:02:50', '2025-05-06 12:02:50'),
(20, NULL, 'DL5QVYE20H', '06-05-2025 | 05:34:24 PM', 'Delivered to branch', '2025-05-06 12:04:24', '2025-05-06 12:04:24'),
(21, NULL, 'DL5QVYE20H', '06-05-2025 | 05:36:59 PM', 'Transit', '2025-05-06 12:06:59', '2025-05-06 12:06:59'),
(22, NULL, 'DL5QVYE20H', '06-05-2025 | 05:41:22 PM', 'Order Reached in Near By Hub', '2025-05-06 12:11:22', '2025-05-06 12:11:22'),
(23, NULL, 'DL5QVYE20H', '06-05-2025 | 06:02:23 PM', 'Delivered', '2025-05-06 12:32:23', '2025-05-06 12:32:23'),
(24, NULL, 'DLTUOP9AC9', '06-05-2025 | 06:48:56 PM', 'Booked', '2025-05-06 13:18:56', '2025-05-06 13:18:56'),
(25, NULL, 'DLTUOP9AC9', '06-05-2025 | 06:50:13 PM', 'item Picked Up', '2025-05-06 13:20:13', '2025-05-06 13:20:13'),
(26, NULL, 'DLTUOP9AC9', '06-05-2025 | 06:50:44 PM', 'Delivered to branch', '2025-05-06 13:20:44', '2025-05-06 13:20:44'),
(27, NULL, 'DLWDOUOFGH', '06-05-2025 | 07:03:30 PM', 'Booked', '2025-05-06 13:33:30', '2025-05-06 13:33:30'),
(28, NULL, 'DLBZEKOBDS', '06-05-2025 | 07:36:11 PM', 'Booked', '2025-05-06 14:06:11', '2025-05-06 14:06:11'),
(29, NULL, 'DLGSG5J4E0', '06-05-2025 | 07:37:54 PM', 'Booked', '2025-05-06 14:07:54', '2025-05-06 14:07:54'),
(30, NULL, 'DL1HD4ABCO', '06-05-2025 | 07:37:55 PM', 'Booked', '2025-05-06 14:07:55', '2025-05-06 14:07:55'),
(31, NULL, 'DL1CLX7HO9', '06-05-2025 | 07:37:55 PM', 'Booked', '2025-05-06 14:07:55', '2025-05-06 14:07:55'),
(32, NULL, 'DLLN7E3IA5', '06-05-2025 | 07:38:10 PM', 'Booked', '2025-05-06 14:08:10', '2025-05-06 14:08:10'),
(33, NULL, 'DLSMUHVASS', '06-05-2025 | 07:38:10 PM', 'Booked', '2025-05-06 14:08:10', '2025-05-06 14:08:10'),
(34, NULL, 'DLB97364FP', '06-05-2025 | 07:38:11 PM', 'Booked', '2025-05-06 14:08:11', '2025-05-06 14:08:11'),
(35, NULL, 'DLKH0XWTCL', '06-05-2025 | 07:38:11 PM', 'Booked', '2025-05-06 14:08:11', '2025-05-06 14:08:11'),
(36, NULL, 'DL8SPU5ODH', '06-05-2025 | 07:38:11 PM', 'Booked', '2025-05-06 14:08:11', '2025-05-06 14:08:11'),
(37, NULL, 'DL5SK6TI8C', '06-05-2025 | 07:38:11 PM', 'Booked', '2025-05-06 14:08:11', '2025-05-06 14:08:11'),
(38, NULL, 'DLIMDB0RO9', '06-05-2025 | 07:38:11 PM', 'Booked', '2025-05-06 14:08:11', '2025-05-06 14:08:11'),
(39, NULL, 'DLKRS6F666', '06-05-2025 | 07:38:12 PM', 'Booked', '2025-05-06 14:08:12', '2025-05-06 14:08:12'),
(40, NULL, 'DLWM23BH4H', '06-05-2025 | 07:38:12 PM', 'Booked', '2025-05-06 14:08:12', '2025-05-06 14:08:12'),
(41, NULL, 'DLBOVFQ70W', '06-05-2025 | 07:38:12 PM', 'Booked', '2025-05-06 14:08:12', '2025-05-06 14:08:12'),
(42, NULL, 'DLV8K2N2WJ', '06-05-2025 | 07:38:12 PM', 'Booked', '2025-05-06 14:08:12', '2025-05-06 14:08:12'),
(43, NULL, 'DLZDUYPIS4', '06-05-2025 | 07:50:21 PM', 'Booked', '2025-05-06 14:20:21', '2025-05-06 14:20:21'),
(44, NULL, 'DLZQ1OAPXC', '06-05-2025 | 08:14:32 PM', 'Booked', '2025-05-06 14:44:32', '2025-05-06 14:44:32'),
(45, NULL, 'DLZQ1OAPXC', '06-05-2025 | 08:25:41 PM', 'item Picked Up', '2025-05-06 14:55:41', '2025-05-06 14:55:41'),
(46, NULL, 'DLZQ1OAPXC', '06-05-2025 | 08:32:17 PM', 'item Picked Up', '2025-05-06 15:02:17', '2025-05-06 15:02:17'),
(47, NULL, 'DLZQ1OAPXC', '06-05-2025 | 08:37:57 PM', 'Delivered', '2025-05-06 15:07:57', '2025-05-06 15:07:57'),
(48, NULL, 'DLZQ1OAPXC', '06-05-2025 | 08:38:43 PM', 'Out for Delivery to Origin', '2025-05-06 15:08:43', '2025-05-06 15:08:43'),
(49, NULL, 'DLZQ1OAPXC', '06-05-2025 | 08:39:00 PM', 'Delivered', '2025-05-06 15:09:00', '2025-05-06 15:09:00'),
(50, NULL, 'DLZQ1OAPXC', '06-05-2025 | 08:45:24 PM', 'item Picked Up', '2025-05-06 15:15:24', '2025-05-06 15:15:24'),
(51, NULL, 'DLZQ1OAPXC', '06-05-2025 | 08:46:15 PM', 'Delivered', '2025-05-06 15:16:15', '2025-05-06 15:16:15'),
(52, NULL, 'DLZQ1OAPXC', '06-05-2025 | 08:47:40 PM', 'Out for Delivery to Origin', '2025-05-06 15:17:40', '2025-05-06 15:17:40'),
(53, NULL, 'DLZQ1OAPXC', '06-05-2025 | 08:47:54 PM', 'Delivered', '2025-05-06 15:17:54', '2025-05-06 15:17:54');

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
(1, '110001', 'active', '2025-04-05 12:30:55', '2025-04-05 12:30:55'),
(2, '110002', 'active', '2025-04-05 12:30:59', '2025-04-05 12:30:59'),
(3, '110003', 'active', '2025-04-05 12:31:02', '2025-04-05 12:31:02'),
(4, '110004', 'active', '2025-04-05 12:31:05', '2025-04-05 12:31:05'),
(5, '110005', 'active', '2025-04-05 12:31:07', '2025-04-05 12:31:07'),
(6, '110006', 'active', '2025-04-05 12:31:11', '2025-04-05 12:31:11'),
(7, '110007', 'active', '2025-04-05 12:31:15', '2025-04-05 12:31:15'),
(8, '110008', 'active', '2025-04-05 12:31:19', '2025-04-05 12:31:19'),
(9, '110009', 'active', '2025-04-05 12:31:22', '2025-04-05 12:31:22'),
(10, '110010', 'active', '2025-04-05 12:31:24', '2025-04-05 12:31:24'),
(11, '110011', 'active', '2025-04-05 12:31:26', '2025-04-05 12:31:26'),
(12, '110012', 'active', '2025-04-05 12:31:29', '2025-04-05 12:31:29'),
(13, '110013', 'active', '2025-04-05 12:31:32', '2025-04-05 12:31:32'),
(14, '110014', 'active', '2025-04-05 12:31:35', '2025-04-05 12:31:35'),
(15, '110015', 'active', '2025-04-05 12:31:37', '2025-04-05 12:31:37'),
(16, '110016', 'active', '2025-04-05 12:31:44', '2025-04-05 12:31:44'),
(17, '110017', 'active', '2025-04-05 12:31:48', '2025-04-05 12:31:48'),
(18, '110018', 'active', '2025-04-05 12:31:51', '2025-04-05 12:31:51'),
(19, '110019', 'active', '2025-04-05 12:31:53', '2025-04-05 12:31:53'),
(20, '110020', 'active', '2025-04-05 12:31:55', '2025-04-05 12:31:55'),
(21, '110021', 'active', '2025-04-05 12:32:06', '2025-04-05 12:32:06'),
(22, '110022', 'active', '2025-04-05 12:32:09', '2025-04-05 12:32:09'),
(23, '110023', 'active', '2025-04-05 12:32:11', '2025-04-05 12:32:11'),
(24, '110024', 'active', '2025-04-05 12:32:13', '2025-04-05 12:32:13'),
(25, '110025', 'active', '2025-04-05 12:32:16', '2025-04-05 12:32:16'),
(26, '110026', 'active', '2025-04-05 12:32:19', '2025-04-05 12:32:19'),
(27, '110027', 'active', '2025-04-05 12:32:21', '2025-04-05 12:32:21'),
(28, '110028', 'active', '2025-04-05 12:32:24', '2025-04-05 12:32:24'),
(29, '110029', 'active', '2025-04-05 12:32:28', '2025-04-05 12:32:28'),
(30, '110030', 'active', '2025-04-05 12:32:31', '2025-04-05 12:32:31'),
(31, '110031', 'active', '2025-04-05 12:32:34', '2025-04-05 12:32:34'),
(32, '110032', 'active', '2025-04-05 12:32:36', '2025-04-05 12:32:36'),
(33, '110033', 'active', '2025-04-05 12:32:37', '2025-04-05 12:32:37'),
(34, '110034', 'active', '2025-04-05 12:32:40', '2025-04-05 12:32:40'),
(35, '110035', 'active', '2025-04-05 12:32:44', '2025-04-05 12:32:44'),
(36, '110036', 'active', '2025-04-05 12:32:46', '2025-04-05 12:32:46'),
(37, '110037', 'active', '2025-04-05 12:32:50', '2025-04-05 12:32:50'),
(38, '110038', 'active', '2025-04-05 12:32:52', '2025-04-05 12:32:52'),
(41, '110039', 'active', '2025-05-03 10:50:03', '2025-05-03 10:50:03'),
(42, '110040', 'active', '2025-05-03 10:50:06', '2025-05-03 10:50:06');

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
(1, '200 gm', '30', 'ex', 'active', '2025-04-05 12:52:20', '2025-04-05 12:52:20'),
(2, '200 gm', '19', 'stex', 'active', '2025-04-05 12:52:35', '2025-04-05 12:52:35'),
(3, '3km', '50', 'se', 'active', '2025-04-23 06:47:36', '2025-04-30 11:44:13'),
(4, '1km', '5', 'se', 'active', '2025-04-24 02:09:08', '2025-04-30 11:44:18'),
(5, '200 gm', '10', 'ss', 'active', '2025-05-03 11:10:59', '2025-05-03 11:10:59');

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
(1, 'Admin-DelhiParcel', 'admin@gmail.com', NULL, NULL, '$2y$10$.e3za/gAy55GJ81IdxAoA.DPTqP6Ty/011aUbBemmm3Z5C/LEp7/i', NULL, NULL, NULL, NULL, NULL, 'admin', NULL, 'active', NULL, '2025-03-20 23:22:14', '2025-03-20 23:22:14');

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
(1, 7, '10000', NULL, '10000', '16-04-2025 | 01:09:51 PM', 'success', NULL, NULL, 'credit', '2025-04-16 02:09:51', '2025-04-16 02:09:51'),
(2, 7, NULL, '80.00', '9920', '16-04-2025 | 01:13:40 PM', 'success', NULL, NULL, 'debit', '2025-04-16 02:13:40', '2025-04-16 02:13:40'),
(3, 7, NULL, '110.00', '9810', '16-04-2025 | 01:14:17 PM', 'success', NULL, NULL, 'debit', '2025-04-16 02:14:17', '2025-04-16 02:14:17'),
(4, 7, NULL, '110.00', '9700', '16-04-2025 | 01:14:41 PM', 'success', NULL, NULL, 'debit', '2025-04-16 02:14:41', '2025-04-16 02:14:41'),
(5, 7, NULL, '110.00', '9590', '16-04-2025 | 01:32:51 PM', 'success', NULL, NULL, 'debit', '2025-04-16 02:32:51', '2025-04-16 02:32:51'),
(6, 7, NULL, '110.00', '9480', '16-04-2025 | 01:39:55 PM', 'success', NULL, NULL, 'debit', '2025-04-16 02:39:55', '2025-04-16 02:39:55'),
(7, 7, NULL, '60.00', '9420', '16-04-2025 | 02:49:09 PM', 'success', NULL, NULL, 'debit', '2025-04-16 03:49:09', '2025-04-16 03:49:09'),
(8, 7, NULL, '60.00', '9360', '17-04-2025 | 10:45:24 AM', 'success', NULL, NULL, 'debit', '2025-04-16 23:45:24', '2025-04-16 23:45:24'),
(9, 7, '60.00', NULL, '9420', '17-04-2025 | 10:50:13 AM', 'success', NULL, NULL, 'Order Cancelled', '2025-04-16 23:50:13', '2025-04-16 23:50:13'),
(10, 7, NULL, '60.00', '9360', '17-04-2025 | 03:28:48 PM', 'success', NULL, NULL, 'debit', '2025-04-17 04:28:48', '2025-04-17 04:28:48'),
(11, 7, NULL, '60.00', '9300', '17-04-2025 | 04:10:57 PM', 'success', NULL, NULL, 'debit', '2025-04-17 05:10:57', '2025-04-17 05:10:57'),
(12, 7, NULL, '60.00', '9240', '18-04-2025 | 12:31:26 PM', 'success', NULL, NULL, 'debit', '2025-04-18 01:31:26', '2025-04-18 01:31:26'),
(13, 7, NULL, '60.00', '9180', '18-04-2025 | 08:48:47 PM', 'success', NULL, NULL, 'debit', '2025-04-18 09:48:47', '2025-04-18 09:48:47'),
(14, 7, NULL, '60.00', '9120', '19-04-2025 | 05:37:58 PM', 'success', NULL, NULL, 'debit', '2025-04-19 06:37:58', '2025-04-19 06:37:58'),
(15, 8, '1000', NULL, '1000', '21-04-2025 | 11:05:59 AM', 'success', NULL, NULL, 'credit', '2025-04-21 00:05:59', '2025-04-21 00:05:59'),
(16, 8, NULL, '60.00', '940', '21-04-2025 | 11:08:56 AM', 'success', NULL, NULL, 'debit', '2025-04-21 00:08:56', '2025-04-21 00:08:56'),
(17, 8, NULL, '60.00', '880', '21-04-2025 | 02:41:52 PM', 'success', NULL, NULL, 'debit', '2025-04-21 03:41:52', '2025-04-21 03:41:52'),
(18, 6, '4000', NULL, '4000', '22-04-2025 | 10:23:54 AM', 'success', NULL, NULL, 'credit', '2025-04-21 23:23:54', '2025-04-21 23:23:54'),
(19, 6, NULL, '60.00', '3940', '22-04-2025 | 10:24:56 AM', 'success', NULL, NULL, 'debit', '2025-04-21 23:24:56', '2025-04-21 23:24:56'),
(20, 6, NULL, '60.00', '3880', '22-04-2025 | 10:25:12 AM', 'success', NULL, NULL, 'debit', '2025-04-21 23:25:12', '2025-04-21 23:25:12'),
(21, 6, NULL, '60.00', '3820', '22-04-2025 | 10:27:38 AM', 'success', NULL, NULL, 'debit', '2025-04-21 23:27:38', '2025-04-21 23:27:38'),
(22, 6, NULL, '60.00', '3760', '22-04-2025 | 10:29:04 AM', 'success', NULL, NULL, 'debit', '2025-04-21 23:29:04', '2025-04-21 23:29:04'),
(23, 6, NULL, '60.00', '3700', '22-04-2025 | 10:34:49 AM', 'success', NULL, NULL, 'debit', '2025-04-21 23:34:49', '2025-04-21 23:34:49'),
(24, 6, NULL, '60.00', '3640', '22-04-2025 | 10:38:28 AM', 'success', NULL, NULL, 'debit', '2025-04-21 23:38:28', '2025-04-21 23:38:28'),
(25, 6, NULL, '60.00', '3580', '22-04-2025 | 11:05:40 AM', 'success', NULL, NULL, 'debit', '2025-04-22 00:05:40', '2025-04-22 00:05:40'),
(26, 8, NULL, '60.00', '820', '22-04-2025 | 11:13:04 AM', 'success', NULL, NULL, 'debit', '2025-04-22 00:13:04', '2025-04-22 00:13:04'),
(27, 8, NULL, '60.00', '760', '22-04-2025 | 11:48:32 AM', 'success', NULL, NULL, 'debit', '2025-04-22 00:48:32', '2025-04-22 00:48:32'),
(28, 8, NULL, '60.00', '700', '22-04-2025 | 03:12:18 PM', 'success', NULL, NULL, 'debit', '2025-04-22 04:12:18', '2025-04-22 04:12:18'),
(29, 6, NULL, '60.00', '3520', '22-04-2025 | 03:31:05 PM', 'success', NULL, NULL, 'debit', '2025-04-22 04:31:05', '2025-04-22 04:31:05'),
(30, 8, NULL, '60.00', '640', '22-04-2025 | 03:46:24 PM', 'success', NULL, NULL, 'debit', '2025-04-22 04:46:24', '2025-04-22 04:46:24'),
(31, 6, NULL, '60.00', '3460', '22-04-2025 | 04:13:57 PM', 'success', NULL, NULL, 'debit', '2025-04-22 05:13:57', '2025-04-22 05:13:57'),
(32, 8, NULL, '60.00', '580', '23-04-2025 | 11:05:14 AM', 'success', NULL, NULL, 'debit', '2025-04-23 00:05:14', '2025-04-23 00:05:14'),
(33, 8, NULL, '60.00', '520', '23-04-2025 | 04:19:33 PM', 'success', NULL, NULL, 'debit', '2025-04-23 05:19:33', '2025-04-23 05:19:33'),
(34, 6, '50', NULL, '3510', '29-04-2025 | 05:13:28 PM', 'success', NULL, NULL, 'credit', '2025-04-29 11:43:28', '2025-04-29 11:43:28'),
(35, 13, '100', NULL, '100', '01-05-2025 | 04:58:41 PM', 'success', NULL, NULL, 'credit', '2025-05-01 11:28:41', '2025-05-01 11:28:41'),
(36, 13, NULL, '60.00', '40', '01-05-2025 | 04:59:06 PM', 'success', NULL, NULL, 'debit', '2025-05-01 11:29:06', '2025-05-01 11:29:06'),
(37, 13, '2000', NULL, '2040', '01-05-2025 | 05:09:19 PM', 'success', NULL, NULL, 'credit', '2025-05-01 11:39:19', '2025-05-01 11:39:19'),
(38, 13, NULL, '60.00', '1980', '01-05-2025 | 05:09:47 PM', 'success', NULL, NULL, 'debit', '2025-05-01 11:39:47', '2025-05-01 11:39:47'),
(39, 14, '2000', NULL, '2000', '01-05-2025 | 05:37:44 PM', 'success', NULL, NULL, 'credit', '2025-05-01 12:07:44', '2025-05-01 12:07:44'),
(40, 14, NULL, '60.00', '1940', '01-05-2025 | 05:40:37 PM', 'success', NULL, NULL, 'debit', '2025-05-01 12:10:37', '2025-05-01 12:10:37'),
(41, 14, NULL, '60.00', '1880', '01-05-2025 | 06:45:07 PM', 'success', NULL, NULL, 'debit', '2025-05-01 13:15:07', '2025-05-01 13:15:07');

-- --------------------------------------------------------

--
-- Table structure for table `web_orders`
--

CREATE TABLE `web_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `service_id` varchar(255) NOT NULL,
  `pickupAddress` varchar(255) DEFAULT NULL,
  `deliveryAddress` varchar(255) DEFAULT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_number` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `sender_address` text NOT NULL,
  `senderPinCode` varchar(255) NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `receiver_number` varchar(255) NOT NULL,
  `receiver_email` varchar(255) NOT NULL,
  `receiver_address` text NOT NULL,
  `receiverPinCode` varchar(255) NOT NULL,
  `payment_methods` varchar(255) NOT NULL,
  `codAmount` varchar(255) DEFAULT NULL,
  `insurance` varchar(255) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `order_status` enum('Booked','Item Picked Up','Returned','In Transit','Arrived at Destination','Out for Delivery','Delivered','Not Delivered','Returning to Origin','Out for Delivery to Origin','Cancelled') NOT NULL,
  `status_message` text DEFAULT NULL,
  `parcel_type` varchar(255) DEFAULT NULL,
  `assign_to` varchar(255) DEFAULT NULL,
  `datetime` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_cod_history`
--
ALTER TABLE `admin_cod_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_total_cod`
--
ALTER TABLE `admin_total_cod`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branchs`
--
ALTER TABLE `branchs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branchs_email_unique` (`email`);

--
-- Indexes for table `branch_cod_history`
--
ALTER TABLE `branch_cod_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_total_cod`
--
ALTER TABLE `branch_total_cod`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `cod_wallet`
--
ALTER TABLE `cod_wallet`
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
  ADD UNIQUE KEY `enquirys_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feed_backs`
--
ALTER TABLE `feed_backs`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `order_cod`
--
ALTER TABLE `order_cod`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_histories`
--
ALTER TABLE `order_histories`
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
-- Indexes for table `web_orders`
--
ALTER TABLE `web_orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_cod_history`
--
ALTER TABLE `admin_cod_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin_total_cod`
--
ALTER TABLE `admin_total_cod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branchs`
--
ALTER TABLE `branchs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `branch_cod_history`
--
ALTER TABLE `branch_cod_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `branch_total_cod`
--
ALTER TABLE `branch_total_cod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cod`
--
ALTER TABLE `cod`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cod_amount`
--
ALTER TABLE `cod_amount`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `cod_wallet`
--
ALTER TABLE `cod_wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dlyboy`
--
ALTER TABLE `dlyboy`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `enquirys`
--
ALTER TABLE `enquirys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feed_backs`
--
ALTER TABLE `feed_backs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_cod`
--
ALTER TABLE `order_cod`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_histories`
--
ALTER TABLE `order_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pincodes`
--
ALTER TABLE `pincodes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `servicetypes`
--
ALTER TABLE `servicetypes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `web_orders`
--
ALTER TABLE `web_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
