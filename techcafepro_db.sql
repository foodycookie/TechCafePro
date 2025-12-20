-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2025 at 06:19 PM
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
-- Database: `techcafepro_db`
--
CREATE DATABASE IF NOT EXISTS `techcafepro_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `techcafepro_db`;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `status`) VALUES
(2, 'Cake', 1),
(3, 'Coffee', 1),
(4, 'Ice cream', 1),
(6, 'Frappe', 1),
(16, 'Donut', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `count` int(11) NOT NULL,
  `total_amount` double(8,2) NOT NULL,
  `reward_given` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `created_at`, `count`, `total_amount`, `reward_given`, `user_id`) VALUES
(28, '2025-12-17 04:55:41', 1, 0.01, 0, 4),
(29, '2025-12-17 04:55:44', 1, 0.01, 0, 4),
(30, '2025-12-17 04:55:52', 1, 0.01, 0, 4),
(31, '2025-12-17 04:56:04', 1, 0.01, 0, 4),
(32, '2025-12-17 04:56:14', 6, 5.06, 0, 4),
(33, '2025-12-17 05:06:41', 4, 1.04, 0, 4),
(34, '2025-12-17 14:28:31', 5, 5.05, 0, 4),
(35, '2025-12-17 15:47:40', 3, 0.03, 0, 5),
(36, '2025-12-17 15:52:31', 3, 3.03, 0, 5),
(37, '2025-12-17 17:29:43', 11, 5.11, 0, 4),
(38, '2025-12-17 18:06:16', 6, 20.43, 0, 4),
(39, '2025-12-17 18:07:32', 8, 35.63, 0, 4),
(40, '2025-12-17 18:47:10', 10, 66.00, 0, 8),
(41, '2025-12-17 18:47:24', 1, 0.01, 0, 8),
(42, '2025-12-17 18:48:29', 1, 1.01, 0, 8),
(43, '2025-12-17 18:48:47', 5, 5.05, 0, 8),
(44, '2025-12-18 04:12:52', 3, 0.03, 0, 4),
(45, '2025-12-18 04:13:45', 1, 0.01, 0, 4),
(46, '2025-12-18 04:13:54', 1, 0.01, 0, 4),
(47, '2025-12-18 04:14:39', 1, 9.00, 0, 4),
(48, '2025-12-19 03:09:30', 2, 0.02, 0, 4),
(49, '2025-12-19 03:09:35', 2, 0.02, 0, 4),
(50, '2025-12-19 03:09:37', 2, 0.02, 0, 4),
(51, '2025-12-19 03:09:39', 2, 0.02, 0, 4),
(52, '2025-12-19 03:09:40', 2, 0.02, 0, 4),
(53, '2025-12-19 03:09:43', 2, 0.02, 0, 4),
(54, '2025-12-19 03:15:00', 4, 0.04, 0, 4),
(55, '2025-12-19 03:15:03', 4, 0.04, 0, 4),
(56, '2025-12-19 03:15:04', 4, 0.04, 0, 4),
(57, '2025-12-19 03:15:07', 4, 0.04, 0, 4),
(58, '2025-12-19 03:15:09', 4, 0.04, 0, 4),
(59, '2025-12-19 03:15:10', 4, 0.04, 0, 4),
(60, '2025-12-19 03:15:47', 4, 0.04, 0, 4),
(61, '2025-12-19 03:15:50', 4, 0.04, 0, 4),
(62, '2025-12-19 03:15:52', 4, 0.04, 0, 4),
(63, '2025-12-19 03:15:54', 4, 0.04, 0, 4),
(64, '2025-12-19 03:15:56', 4, 0.04, 0, 4),
(65, '2025-12-19 05:14:43', 4, 0.04, 0, 4),
(66, '2025-12-19 05:14:48', 2, 0.02, 0, 4),
(67, '2025-12-19 05:14:51', 2, 0.02, 0, 4),
(68, '2025-12-19 05:15:11', 6, 0.06, 0, 4),
(69, '2025-12-19 05:15:16', 4, 0.04, 0, 4),
(70, '2025-12-19 05:16:11', 4, 0.04, 0, 4),
(71, '2025-12-19 05:16:18', 3, 0.03, 0, 4),
(72, '2025-12-19 05:16:23', 5, 0.05, 0, 4),
(73, '2025-12-19 05:16:51', 1, 0.01, 0, 4),
(74, '2025-12-19 05:17:15', 3, 0.03, 0, 4),
(75, '2025-12-19 05:17:21', 4, 0.04, 0, 4),
(76, '2025-12-19 05:17:48', 2, 0.02, 0, 4),
(77, '2025-12-19 05:18:40', 4, 0.04, 0, 4),
(78, '2025-12-19 05:21:07', 7, 0.07, 0, 4),
(79, '2025-12-19 05:22:15', 4, 1.04, 0, 4),
(80, '2025-12-19 05:22:57', 2, 0.02, 0, 4),
(81, '2025-12-19 05:25:49', 7, 0.07, 0, 4),
(82, '2025-12-19 05:26:26', 2, 0.02, 0, 4),
(83, '2025-12-19 05:27:46', 4, 0.04, 0, 4),
(84, '2025-12-19 05:27:59', 4, 0.04, 0, 4),
(85, '2025-12-19 05:28:21', 4, 0.04, 0, 4),
(86, '2025-12-19 05:29:39', 4, 26.00, 0, 4),
(87, '2025-12-19 05:35:34', 2, 13.60, 0, 4),
(88, '2025-12-19 05:37:27', 1, 12.00, 0, 4),
(89, '2025-12-19 05:37:38', 2, 24.00, 0, 4),
(90, '2025-12-19 05:37:53', 1, 12.00, 0, 4),
(91, '2025-12-19 05:39:06', 3, 20.40, 0, 4),
(92, '2025-12-19 05:39:16', 3, 20.40, 0, 4),
(93, '2025-12-19 05:39:19', 3, 20.40, 0, 4),
(94, '2025-12-19 05:39:24', 3, 20.40, 0, 4),
(95, '2025-12-19 05:39:27', 3, 19.50, 0, 4),
(96, '2025-12-19 05:39:32', 3, 20.40, 0, 4),
(97, '2025-12-19 05:39:34', 3, 19.50, 0, 4),
(98, '2025-12-19 05:39:47', 3, 20.40, 0, 4),
(99, '2025-12-19 05:40:34', 3, 20.40, 0, 4),
(100, '2025-12-19 05:41:24', 3, 20.40, 0, 4),
(101, '2025-12-19 05:41:32', 3, 20.40, 0, 4),
(102, '2025-12-19 05:41:36', 3, 20.40, 0, 4),
(103, '2025-12-19 05:41:39', 3, 20.40, 0, 4),
(104, '2025-12-19 05:41:49', 6, 39.90, 0, 4),
(105, '2025-12-19 05:42:21', 6, 39.90, 0, 4),
(106, '2025-12-19 05:42:25', 6, 39.90, 0, 4),
(107, '2025-12-19 05:42:28', 6, 39.90, 0, 4),
(108, '2025-12-19 05:42:31', 6, 39.90, 0, 4),
(109, '2025-12-19 05:42:45', 6, 39.90, 0, 4),
(110, '2025-12-19 05:43:26', 3, 20.40, 0, 4),
(111, '2025-12-19 05:43:33', 3, 20.40, 0, 4),
(112, '2025-12-19 05:43:39', 3, 20.40, 0, 4),
(113, '2025-12-19 05:43:53', 3, 20.40, 0, 4),
(114, '2025-12-19 05:44:41', 2, 0.02, 0, 4),
(115, '2025-12-19 05:45:26', 2, 0.02, 0, 4),
(116, '2025-12-19 05:46:06', 4, 0.04, 0, 4),
(117, '2025-12-19 05:46:13', 7, 0.07, 0, 4),
(118, '2025-12-19 06:10:05', 1, 0.01, 0, 4),
(119, '2025-12-19 06:10:14', 1, 0.01, 0, 4),
(120, '2025-12-19 06:10:52', 5, 0.05, 0, 4),
(121, '2025-12-19 06:10:56', 5, 0.05, 0, 4),
(122, '2025-12-19 06:11:02', 7, 0.07, 0, 4),
(123, '2025-12-19 14:59:52', 2, 12.80, 0, 4),
(124, '2025-12-19 14:59:58', 2, 12.80, 0, 4),
(125, '2025-12-19 15:01:51', 3, 19.50, 0, 4),
(126, '2025-12-19 18:24:01', 4, 25.60, 0, 4),
(127, '2025-12-20 05:26:59', 19, 28.66, 0, 4),
(128, '2025-12-20 05:27:21', 9, 113.02, 0, 4),
(129, '2025-12-20 05:36:23', 2, 18.00, 0, 4),
(130, '2025-12-20 05:57:14', 3, 1.02, 0, 4),
(131, '2025-12-20 05:58:11', 18, 115.30, 0, 4),
(132, '2025-12-21 00:42:40', 50, 50.00, 1, 4),
(133, '2025-12-21 00:48:05', 0, 0.00, 0, 4),
(134, '2025-12-21 00:50:21', 3, 0.03, 1, 4),
(135, '2025-12-21 00:50:28', 0, 0.00, 0, 4),
(136, '2025-12-21 00:51:42', 1, 1.00, 1, 4),
(137, '2025-12-21 00:51:58', 0, 0.00, 0, 4),
(138, '2025-12-21 00:56:00', 1, 0.01, 0, 4),
(139, '2025-12-21 00:56:07', 1, 0.01, 0, 4),
(140, '2025-12-21 00:56:10', 1, 0.01, 0, 4),
(141, '2025-12-21 01:04:08', 0, 0.00, 0, 4),
(142, '2025-12-21 01:04:27', 0, 0.00, 0, 4),
(143, '2025-12-21 01:04:41', 0, 0.00, 0, 4),
(144, '2025-12-21 01:05:07', 0, 0.00, 0, 4),
(145, '2025-12-21 01:05:40', 0, 0.00, 0, 4),
(146, '2025-12-21 01:12:11', 1, 1.00, 0, 4),
(147, '2025-12-21 01:12:24', 99, 99.00, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(4,2) NOT NULL,
  `unit` int(11) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `product_id`, `price`, `unit`, `subtotal`) VALUES
(123, 719, 6.00, 1, 6.00),
(123, 720, 6.80, 1, 6.80),
(124, 719, 6.00, 1, 6.00),
(124, 720, 6.80, 1, 6.80),
(125, 718, 6.50, 3, 19.50),
(126, 719, 6.00, 2, 12.00),
(126, 720, 6.80, 2, 13.60),
(127, 713, 10.00, 2, 20.00),
(127, 718, 6.50, 1, 6.50),
(127, 725, 0.01, 1, 0.01),
(127, 727, 0.01, 1, 0.01),
(127, 728, 0.01, 2, 0.02),
(127, 730, 0.01, 2, 0.02),
(127, 732, 0.01, 3, 0.03),
(127, 734, 1.01, 2, 2.02),
(127, 738, 0.01, 3, 0.03),
(127, 739, 0.01, 2, 0.02),
(128, 715, 13.00, 1, 13.00),
(128, 717, 15.00, 1, 15.00),
(128, 722, 20.00, 3, 60.00),
(128, 723, 12.50, 2, 25.00),
(128, 726, 0.01, 2, 0.02),
(129, 714, 9.00, 2, 18.00),
(130, 724, 1.00, 1, 1.00),
(130, 725, 0.01, 2, 0.02),
(131, 718, 6.50, 5, 32.50),
(131, 719, 6.00, 7, 42.00),
(131, 720, 6.80, 6, 40.80),
(132, 724, 1.00, 50, 50.00),
(133, 761, 0.00, 1, 0.00),
(134, 735, 0.01, 3, 0.03),
(135, 735, 0.00, 1, 0.00),
(136, 751, 1.00, 1, 1.00),
(137, 735, 0.00, 1, 0.00),
(138, 725, 0.01, 1, 0.01),
(139, 725, 0.01, 1, 0.01),
(140, 725, 0.01, 1, 0.01),
(141, 761, 0.00, 1, 0.00),
(142, 761, 0.00, 1, 0.00),
(143, 730, 0.00, 1, 0.00),
(144, 728, 0.00, 1, 0.00),
(145, 760, 0.00, 1, 0.00),
(146, 724, 1.00, 1, 1.00),
(147, 724, 1.00, 99, 99.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `amount` double(4,2) NOT NULL,
  `method` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `paid_at` datetime NOT NULL DEFAULT current_timestamp(),
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `amount`, `method`, `status`, `paid_at`, `order_id`) VALUES
(25, 12.80, 'Cash on Delivery', 'Pending', '2025-12-19 14:59:59', 124),
(26, 25.60, 'Cash on Delivery', 'Pending', '2025-12-19 18:24:05', 126),
(27, 28.66, 'Cash on Delivery', 'Pending', '2025-12-20 05:26:59', 127),
(28, 99.99, 'Cash on Delivery', 'Pending', '2025-12-20 05:27:22', 128),
(29, 18.00, 'Cash on Delivery', 'Pending', '2025-12-20 05:36:24', 129),
(30, 1.02, 'Cash on Delivery', 'Pending', '2025-12-20 05:57:15', 130),
(31, 99.99, 'Cash on Delivery', 'Pending', '2025-12-20 05:58:12', 131),
(32, 0.03, 'Cash on Delivery', 'Pending', '2025-12-21 00:50:22', 134),
(33, 1.00, 'Cash on Delivery', 'Pending', '2025-12-21 00:51:44', 136);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `price` double(4,2) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_available` tinyint(1) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `sold` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `description`, `created_at`, `is_available`, `photo`, `sold`, `status`, `category_id`) VALUES
(713, 'Mocha', 10.00, 'Cho\"\"colate-flavored ,latte with espresso\".', '2025-12-19 06:31:22', 1, '', 0, 1, 3),
(714, 'Cappuccino', 9.00, 'Espresso topped with frothed milk.', '2025-12-19 06:31:22', 1, '', 0, 1, 3),
(715, 'Cheese Cake', 13.00, 'Creamy baked cheese dessert.', '2025-12-19 06:31:22', 1, '', 0, 1, 2),
(716, 'Black Forest', 14.00, 'Chocolate cake with cherries and cream.', '2025-12-19 06:31:22', 1, '', 0, 1, 2),
(717, 'Tiramisu', 15.00, 'Coffee-flavored Italian layered dessert.', '2025-12-19 06:31:22', 1, '', 0, 1, 2),
(718, 'Chocolate', 6.50, 'Creamy chocolate ice cream.', '2025-12-19 06:31:22', 1, '', 5, 1, 4),
(719, 'Vanilla', 6.00, 'Classic vanilla ice cream.', '2025-12-19 06:31:22', 1, '', 7, 1, 4),
(720, 'Green Tea', 6.80, 'Japanese matcha green tea ice cream.', '2025-12-19 06:31:22', 1, '', 6, 1, 4),
(721, 'Mocha Frappe', 12.00, 'good', '2025-12-19 06:31:22', 1, '6935a67889266.jpg', 0, 1, 6),
(722, 'Matcha Tiramisu', 20.00, 'Green and good', '2025-12-19 06:31:22', 1, '6935af726ca1c.jpg', 0, 1, 2),
(723, 'Chocolate Cake', 12.50, 'Rich and moist chocolate cake.', '2025-12-19 06:31:22', 1, '', 0, 1, 2),
(724, '1111', 1.00, '11', '2025-12-19 06:31:22', 1, '693f6a53aaac4.jpg', 1, 1, 2),
(725, '2222', 0.01, '1', '2025-12-19 06:31:22', 1, '694048c59d481.jpg', 2, 1, 2),
(726, 'ccc', 0.01, 's', '2025-12-19 06:31:22', 1, '69404a58d668b.jpg', 0, 1, 2),
(727, 'coffee', 0.01, '1', '2025-12-19 06:31:22', 1, '69404aee61406.jpg', 0, 1, 3),
(728, 'milk', 0.01, '1', '2025-12-19 06:31:22', 1, '69404afe087a3.jpg', 0, 1, 3),
(729, 'Strawberry1', 0.01, '1', '2025-12-19 06:31:22', 0, '69404b1b4816b.jpg', 0, 1, 3),
(730, 'none', 0.01, '1', '2025-12-19 06:31:22', 1, '69404b2aca47e.jpg', 0, 1, 3),
(731, 'donut chocolate', 0.01, '1', '2025-12-19 06:31:22', 1, '6940532a16a0c.jpg', 0, 0, 16),
(732, 'donut plain', 0.01, '1', '2025-12-19 06:31:22', 1, '6940533ad02ca.jpg', 0, 1, 16),
(733, 'chocolate donut', 0.04, '1', '2025-12-19 06:31:22', 0, '69405e79628dd.jpg', 0, 1, 16),
(734, 'strawberry donut', 1.01, '1', '2025-12-19 06:31:22', 1, '69405e9261977.jpg', 0, 1, 16),
(735, '111', 0.01, '1', '2025-12-19 06:31:22', 1, '69406b699cb5f.jpg', 3, 1, 2),
(736, 'a', 0.01, 'k', '2025-12-19 06:31:22', 0, '6940f41492a43.jpg', 0, 0, 2),
(737, 'aaa', 0.01, 'a', '2025-12-19 06:31:22', 1, '6940f427d20f9.jpg', 0, 0, 2),
(738, 'asas', 0.01, 'as', '2025-12-19 06:31:22', 1, '6941336783e95.jpg', 0, 1, 2),
(739, 'aefeef', 0.01, 'a', '2025-12-19 06:31:22', 1, '6941abb29afcf.jpg', 0, 1, 2),
(740, 'Mocha', 10.00, 'Cho\"\"colate-flavored ,latte with espresso\".', '2025-12-20 23:28:16', 1, '', 0, 1, 3),
(741, 'Cappuccino', 9.00, 'Espresso topped with frothed milk.', '2025-12-20 23:28:16', 1, '', 0, 1, 3),
(742, 'Cheese Cake', 13.00, 'Creamy baked cheese dessert.', '2025-12-20 23:28:16', 1, '', 0, 1, 2),
(743, 'Black Forest', 14.00, 'Chocolate cake with cherries and cream.', '2025-12-20 23:28:16', 1, '', 0, 1, 2),
(744, 'Tiramisu', 15.00, 'Coffee-flavored Italian layered dessert.', '2025-12-20 23:28:16', 1, '', 0, 1, 2),
(745, 'Chocolate', 6.50, 'Creamy chocolate ice cream.', '2025-12-20 23:28:16', 1, '', 5, 1, 4),
(746, 'Vanilla', 6.00, 'Classic vanilla ice cream.', '2025-12-20 23:28:16', 1, '', 7, 1, 4),
(747, 'Green Tea', 6.80, 'Japanese matcha green tea ice cream.', '2025-12-20 23:28:16', 1, '', 6, 1, 4),
(748, 'Mocha Frappe', 12.00, 'good', '2025-12-20 23:28:16', 1, '', 0, 1, 6),
(749, 'Matcha Tiramisu', 20.00, 'Green and good', '2025-12-20 23:28:16', 1, '', 0, 1, 2),
(750, 'Chocolate Cake', 12.50, 'Rich and moist chocolate cake.', '2025-12-20 23:28:16', 1, '', 0, 1, 2),
(751, '1111', 1.00, '11', '2025-12-20 23:28:16', 1, '', 2, 1, 2),
(752, '2222', 0.01, '1', '2025-12-20 23:28:16', 1, '', 2, 1, 2),
(753, 'ccc', 0.01, 's', '2025-12-20 23:28:16', 1, '', 0, 1, 2),
(754, 'coffee', 0.01, '1', '2025-12-20 23:28:16', 1, '', 0, 1, 3),
(755, 'milk', 0.01, '1', '2025-12-20 23:28:16', 1, '', 0, 1, 3),
(756, 'Strawberry1', 0.01, '1', '2025-12-20 23:28:16', 0, '', 0, 1, 3),
(757, 'none', 0.01, '1', '2025-12-20 23:28:16', 1, '', 0, 1, 3),
(758, 'donut chocolate', 0.01, '1', '2025-12-20 23:28:16', 1, '', 0, 0, 16),
(759, 'donut plain', 0.01, '1', '2025-12-20 23:28:16', 1, '', 0, 1, 16),
(760, 'chocolate donut', 0.04, '1', '2025-12-20 23:28:16', 0, '', 0, 1, 16),
(761, 'strawberry donut', 1.01, '1', '2025-12-20 23:28:16', 1, '', 0, 1, 16),
(762, '111', 0.01, '1', '2025-12-20 23:28:16', 1, '', 0, 1, 2),
(763, 'a', 0.01, 'k', '2025-12-20 23:28:16', 0, '', 0, 0, 2),
(764, 'aaa', 0.01, 'a', '2025-12-20 23:28:16', 1, '', 0, 0, 2),
(765, 'asas', 0.01, 'as', '2025-12-20 23:28:16', 1, '', 0, 1, 2),
(766, 'aefeef', 0.01, 'a', '2025-12-20 23:28:16', 1, '', 0, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `product_tags`
--

CREATE TABLE `product_tags` (
  `product_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_addresses`
--

CREATE TABLE `shipping_addresses` (
  `shipping_address_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `postal_code` int(11) NOT NULL,
  `state` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_addresses`
--

INSERT INTO `shipping_addresses` (`shipping_address_id`, `address`, `city`, `postal_code`, `state`, `country`, `user_id`) VALUES
(1, '1', '1', 1, '1', '1', 6),
(2, '', '', 0, '', '', 7),
(3, '1', '1', 1, '1', '1', 8);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category` enum('Temperature','Base','Flavour') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `name`, `category`) VALUES
(50, 'a', 'Temperature'),
(51, 'b', 'Flavour');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `token_id` varchar(100) NOT NULL,
  `expire` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`token_id`, `expire`, `user_id`) VALUES
('8ea417e16e8f6883033e61dbdd653f57ab3d924f', '2025-12-17 02:58:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `profile_image_path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_member` tinyint(1) NOT NULL DEFAULT 0,
  `reward_points` int(11) NOT NULL DEFAULT 0,
  `member_since` datetime DEFAULT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `profile_image_path`, `created_at`, `role`, `status`, `is_member`, `reward_points`, `member_since`, `failed_attempts`, `last_failed_login`) VALUES
(1, '11111', 'ong@gmail.com', '7b21848ac9af35be0ddb2d6b9fc3851934db8420', '6941ac828d7e6.jpg', '2025-12-12 16:19:11', 'admin', 1, 0, 0, NULL, 0, NULL),
(2, '22222', 'onghao.howard@gmail.com', '3e511da7577d1864871b760ab30e05b56943c9b2', '693bd0424f7a0.jpg', '2025-12-12 16:20:18', 'admin', 1, 0, 0, NULL, 0, NULL),
(4, 'cus', 'cus@gmail.com', '69a930c666f7e889d123ad2ec931b0184f997ace', '69427fc9a10e9.jpg', '2025-12-17 03:21:54', 'member', 1, 1, 115, '2025-12-21 00:40:50', 0, NULL),
(5, 'rr', 'rqew@gmail.com', '8cb2237d0679ca88db6464eac60da96345513964', '69425e0c04bd5.jpg', '2025-12-17 15:38:52', 'customer', 1, 0, 0, NULL, 0, NULL),
(6, 'a', 'a@gmail.com', 'df51e37c269aa94d38f93e537bf6e2020b21406c', '69428255ea265.jpg', '2025-12-17 18:13:42', 'customer', 1, 0, 0, NULL, 0, NULL),
(7, 'b', 'b@gmail.com', '68413fb4ed973e62a1f45819569915d3adf53e53', '6942828437091.jpg', '2025-12-17 18:14:28', 'customer', 1, 0, 0, NULL, 0, NULL),
(8, '123', '123@gmail.com', 'aed4ef3b90d74390e125f08b74912a65b3760869', '6942885a5ab0d.jpg', '2025-12-17 18:39:22', 'customer', 1, 0, 0, NULL, 0, NULL),
(206, 'user1', 'user1@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user1.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(207, 'user2', 'user2@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user2.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(208, 'user3', 'user3@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user3.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(209, 'user4', 'user4@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user4.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(210, 'user5', 'user5@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user5.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(211, 'user6', 'user6@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user6.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(212, 'user7', 'user7@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user7.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(213, 'user8', 'user8@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user8.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(214, 'user9', 'user9@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user9.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(215, 'user10', 'user10@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user10.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(216, 'user11', 'user11@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user11.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(217, 'user12', 'user12@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user12.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(218, 'user13', 'user13@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user13.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(219, 'user14', 'user14@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user14.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL),
(220, 'user15', 'user15@example.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'user15.jpg', '2025-12-20 23:30:47', 'admin', 1, 0, 0, NULL, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_id`,`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_tags`
--
ALTER TABLE `product_tags`
  ADD PRIMARY KEY (`product_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`shipping_address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=767;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `shipping_address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `product_tags`
--
ALTER TABLE `product_tags`
  ADD CONSTRAINT `product_tags_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `product_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`);

--
-- Constraints for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD CONSTRAINT `shipping_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
