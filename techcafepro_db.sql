-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 01:37 PM
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
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `is_active`) VALUES
(2, 'Cake', 0),
(3, 'Coffee', 0),
(4, 'Ice cream', 0),
(6, 'Frappe', 1),
(16, 'Donut', 0);

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `delivery_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `delivered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `order_id` int(11) NOT NULL,
  `shipping_address_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `count` int(11) NOT NULL,
  `total_amount` double(8,2) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `created_at`, `count`, `total_amount`, `user_id`) VALUES
(1, '2025-12-17 01:48:04', 4, 4.04, 2),
(2, '2025-12-17 01:48:21', 3, 3.03, 2),
(3, '2025-12-17 02:07:29', 1, 1.01, 2),
(4, '2025-12-17 02:09:00', 3, 1.03, 2),
(5, '2025-12-17 02:09:32', 3, 0.03, 2),
(6, '2025-12-17 02:09:41', 2, 13.60, 2),
(7, '2025-12-17 02:12:23', 7, 7.07, 2),
(8, '2025-12-17 02:12:42', 2, 2.02, 2),
(9, '2025-12-17 02:13:11', 2, 2.02, 2),
(10, '2025-12-17 02:19:10', 12, 11.12, 2),
(11, '2025-12-17 02:25:56', 1, 1.01, 2),
(12, '2025-12-17 02:26:57', 6, 8.55, 2),
(13, '2025-12-17 02:27:13', 1, 0.01, 2),
(14, '2025-12-17 02:34:37', 3, 3.03, 2),
(15, '2025-12-17 02:36:10', 38, 0.38, 2),
(16, '2025-12-17 04:50:43', 3, 2.03, 4),
(17, '2025-12-17 04:51:37', 1, 1.01, 4),
(18, '2025-12-17 04:52:34', 1, 1.01, 4),
(19, '2025-12-17 04:54:24', 1, 1.01, 4),
(20, '2025-12-17 04:54:26', 1, 1.01, 4),
(21, '2025-12-17 04:54:27', 1, 1.01, 4),
(22, '2025-12-17 04:54:30', 1, 1.01, 4),
(23, '2025-12-17 04:54:34', 1, 1.01, 4),
(24, '2025-12-17 04:54:36', 1, 1.01, 4),
(25, '2025-12-17 04:55:34', 1, 0.01, 4),
(26, '2025-12-17 04:55:36', 1, 0.01, 4),
(27, '2025-12-17 04:55:39', 1, 0.01, 4),
(28, '2025-12-17 04:55:41', 1, 0.01, 4),
(29, '2025-12-17 04:55:44', 1, 0.01, 4),
(30, '2025-12-17 04:55:52', 1, 0.01, 4),
(31, '2025-12-17 04:56:04', 1, 0.01, 4),
(32, '2025-12-17 04:56:14', 6, 5.06, 4),
(33, '2025-12-17 05:06:41', 4, 1.04, 4),
(34, '2025-12-17 14:28:31', 5, 5.05, 4),
(35, '2025-12-17 15:47:40', 3, 0.03, 5),
(36, '2025-12-17 15:52:31', 3, 3.03, 5),
(37, '2025-12-17 17:29:43', 11, 5.11, 4),
(38, '2025-12-17 18:06:16', 6, 20.43, 4),
(39, '2025-12-17 18:07:32', 8, 35.63, 4),
(40, '2025-12-17 18:47:10', 10, 66.00, 8),
(41, '2025-12-17 18:47:24', 1, 0.01, 8),
(42, '2025-12-17 18:48:29', 1, 1.01, 8),
(43, '2025-12-17 18:48:47', 5, 5.05, 8);

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
(1, 626, 1.01, 4, 4.04),
(2, 626, 1.01, 3, 3.03),
(3, 626, 1.01, 1, 1.01),
(4, 624, 0.01, 2, 0.02),
(4, 626, 1.01, 1, 1.01),
(5, 624, 0.01, 3, 0.03),
(6, 612, 6.80, 2, 13.60),
(7, 626, 1.01, 7, 7.07),
(8, 626, 1.01, 2, 2.02),
(9, 626, 1.01, 2, 2.02),
(10, 624, 0.01, 1, 0.01),
(10, 626, 1.01, 11, 11.11),
(11, 626, 1.01, 1, 1.01),
(12, 610, 6.50, 1, 6.50),
(12, 624, 0.01, 3, 0.03),
(12, 626, 1.01, 2, 2.02),
(13, 624, 0.01, 1, 0.01),
(14, 626, 1.01, 3, 3.03),
(15, 624, 0.01, 38, 0.38),
(16, 624, 0.01, 1, 0.01),
(16, 626, 1.01, 2, 2.02),
(17, 626, 1.01, 1, 1.01),
(18, 626, 1.01, 1, 1.01),
(19, 626, 1.01, 1, 1.01),
(20, 626, 1.01, 1, 1.01),
(21, 626, 1.01, 1, 1.01),
(22, 626, 1.01, 1, 1.01),
(23, 626, 1.01, 1, 1.01),
(24, 626, 1.01, 1, 1.01),
(25, 624, 0.01, 1, 0.01),
(26, 624, 0.01, 1, 0.01),
(27, 624, 0.01, 1, 0.01),
(28, 624, 0.01, 1, 0.01),
(29, 624, 0.01, 1, 0.01),
(30, 624, 0.01, 1, 0.01),
(31, 624, 0.01, 1, 0.01),
(32, 624, 0.01, 1, 0.01),
(32, 626, 1.01, 5, 5.05),
(33, 624, 0.01, 3, 0.03),
(33, 626, 1.01, 1, 1.01),
(34, 626, 1.01, 5, 5.05),
(35, 624, 0.01, 3, 0.03),
(36, 626, 1.01, 3, 3.03),
(37, 624, 0.01, 6, 0.06),
(37, 626, 1.01, 5, 5.05),
(38, 612, 6.80, 3, 20.40),
(38, 624, 0.01, 3, 0.03),
(39, 610, 6.50, 2, 13.00),
(39, 611, 6.00, 1, 6.00),
(39, 612, 6.80, 2, 13.60),
(39, 626, 1.01, 3, 3.03),
(40, 610, 6.50, 4, 26.00),
(40, 611, 6.00, 1, 6.00),
(40, 612, 6.80, 5, 34.00),
(41, 624, 0.01, 1, 0.01),
(42, 626, 1.01, 1, 1.01),
(43, 626, 1.01, 5, 5.05);

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
(1, 1.01, 'Cash on Delivery', 'Pending', '2025-12-17 02:07:32', 3),
(2, 7.07, 'Cash on Delivery', 'Pending', '2025-12-17 02:12:24', 7),
(3, 1.01, 'Cash on Delivery', 'Pending', '2025-12-17 02:26:00', 11),
(4, 8.55, 'Cash on Delivery', 'Pending', '2025-12-17 02:26:58', 12),
(5, 0.01, 'Cash on Delivery', 'Pending', '2025-12-17 02:27:14', 13),
(6, 3.03, 'Cash on Delivery', 'Pending', '2025-12-17 02:34:38', 14),
(7, 0.38, 'Cash on Delivery', 'Pending', '2025-12-17 02:36:11', 15),
(8, 1.01, 'Cash on Delivery', 'Pending', '2025-12-17 04:54:37', 24),
(9, 0.01, 'Cash on Delivery', 'Pending', '2025-12-17 04:55:45', 29),
(10, 5.05, 'Cash on Delivery', 'Pending', '2025-12-17 14:28:32', 34),
(11, 0.03, 'Cash on Delivery', 'Pending', '2025-12-17 15:47:45', 35),
(12, 5.11, 'Cash on Delivery', 'Pending', '2025-12-17 17:29:44', 37),
(13, 20.43, 'Cash on Delivery', 'Pending', '2025-12-17 18:06:17', 38),
(14, 66.00, 'Cash on Delivery', 'Pending', '2025-12-17 18:47:13', 40),
(15, 0.01, 'Cash on Delivery', 'Pending', '2025-12-17 18:47:25', 41);

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
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `description`, `created_at`, `is_available`, `photo`, `sold`, `is_active`, `category_id`) VALUES
(605, 'Mocha', 10.00, 'Cho\"\"colate-flavored ,latte with espresso\".', '2025-12-16 21:28:05', 1, '', 0, 1, 3),
(606, 'Cappuccino', 9.00, 'Espresso topped with frothed milk.', '2025-12-16 21:28:05', 1, '', 0, 1, 3),
(607, 'Cheese Cake', 13.00, 'Creamy baked cheese dessert.', '2025-12-16 21:28:05', 1, '', 0, 1, 2),
(608, 'Black Forest', 14.00, 'Chocolate cake with cherries and cream.', '2025-12-16 21:28:05', 1, '', 0, 1, 2),
(609, 'Tiramisu', 15.00, 'Coffee-flavored Italian layered dessert.', '2025-12-16 21:28:05', 1, '', 0, 1, 2),
(610, 'Chocolate', 6.50, 'Creamy chocolate ice cream.', '2025-12-16 21:28:05', 1, '', 0, 1, 4),
(611, 'Vanilla', 6.00, 'Classic vanilla ice cream.', '2025-12-16 21:28:05', 1, '', 0, 1, 4),
(612, 'Green Tea', 6.80, 'Japanese matcha green tea ice cream.', '2025-12-16 21:28:05', 1, '', 0, 1, 4),
(613, 'Mocha Frappe', 12.00, 'good', '2025-12-16 21:28:05', 1, '6935a67889266.jpg', 0, 1, 6),
(614, 'Matcha Tiramisu', 20.00, 'Green and good', '2025-12-16 21:28:05', 0, '6935af726ca1c.jpg', 0, 1, 2),
(615, 'Chocolate Cake', 12.50, 'Rich and moist chocolate cake.', '2025-12-16 21:28:05', 1, '', 0, 1, 2),
(616, '1111', 1.00, '11', '2025-12-16 21:28:05', 1, '693f6a53aaac4.jpg', 0, 1, 2),
(617, '2222', 0.01, '1', '2025-12-16 21:28:05', 1, '694048c59d481.jpg', 0, 1, 2),
(618, 'ccc', 0.01, 's', '2025-12-16 21:28:05', 1, '69404a58d668b.jpg', 0, 1, 2),
(619, 'coffee', 0.01, '1', '2025-12-16 21:28:05', 1, '69404aee61406.jpg', 0, 1, 3),
(620, 'milk', 0.01, '1', '2025-12-16 21:28:05', 1, '69404afe087a3.jpg', 0, 1, 3),
(621, 'Strawberry1', 0.01, '1', '2025-12-16 21:28:05', 0, '69404b1b4816b.jpg', 0, 1, 3),
(622, 'none', 0.01, '1', '2025-12-16 21:28:05', 1, '69404b2aca47e.jpg', 0, 1, 3),
(623, 'donut chocolate', 0.01, '1', '2025-12-16 21:28:05', 1, '6940532a16a0c.jpg', 0, 0, 16),
(624, 'donut plain', 0.01, '1', '2025-12-16 21:28:05', 1, '6940533ad02ca.jpg', 0, 1, 16),
(625, 'chocolate donut', 0.04, '1', '2025-12-16 21:28:05', 0, '69405e79628dd.jpg', 0, 1, 16),
(626, 'strawberry donut', 1.01, '1', '2025-12-16 21:28:05', 1, '69405e9261977.jpg', 0, 1, 16),
(627, '111', 0.01, '1', '2025-12-16 21:28:05', 1, '69406b699cb5f.jpg', 0, 1, 2),
(628, 'a', 0.01, 'k', '2025-12-16 21:28:05', 0, '6940f41492a43.jpg', 0, 0, 2),
(629, 'aaa', 0.01, 'a', '2025-12-16 21:28:05', 1, '6940f427d20f9.jpg', 0, 0, 2),
(630, 'asas', 0.01, 'as', '2025-12-16 21:28:05', 1, '6941336783e95.jpg', 0, 1, 2),
(631, 'aefeef', 0.01, 'a', '2025-12-17 02:57:54', 1, '6941abb29afcf.jpg', 0, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `product_image_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tags`
--

CREATE TABLE `product_tags` (
  `product_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_tags`
--

INSERT INTO `product_tags` (`product_id`, `tag_id`) VALUES
(605, 43),
(605, 44),
(606, 44),
(630, 43),
(630, 44),
(630, 45),
(631, 43),
(631, 44),
(631, 45),
(631, 46);

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
(43, 'Hot', 'Temperature'),
(44, 'Coffee', 'Base'),
(45, 'Cinnamon', 'Flavour'),
(46, 'berry', 'Flavour');

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
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `profile_image_path`, `created_at`, `role`, `status`) VALUES
(1, '11111', 'ong@gmail.com', '7b21848ac9af35be0ddb2d6b9fc3851934db8420', '6941ac828d7e6.jpg', '2025-12-12 16:19:11', 'admin', 1),
(2, '22222', 'onghao.howard@gmail.com', '3e511da7577d1864871b760ab30e05b56943c9b2', '693bd0424f7a0.jpg', '2025-12-12 16:20:18', 'admin', 1),
(4, 'cus', 'cus@gmail.com', '69a930c666f7e889d123ad2ec931b0184f997ace', '69427fc9a10e9.jpg', '2025-12-17 03:21:54', 'customer', 1),
(5, 'rr', 'rqew@gmail.com', '8cb2237d0679ca88db6464eac60da96345513964', '69425e0c04bd5.jpg', '2025-12-17 15:38:52', 'customer', 1),
(6, 'a', 'a@gmail.com', 'df51e37c269aa94d38f93e537bf6e2020b21406c', '69428255ea265.jpg', '2025-12-17 18:13:42', 'customer', 0),
(7, 'b', 'b@gmail.com', '68413fb4ed973e62a1f45819569915d3adf53e53', '6942828437091.jpg', '2025-12-17 18:14:28', 'customer', 0),
(8, '123', '123@gmail.com', 'aed4ef3b90d74390e125f08b74912a65b3760869', '6942885a5ab0d.jpg', '2025-12-17 18:39:22', 'customer', 1);

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
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `shipping_address_id` (`shipping_address_id`);

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
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`product_image_id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=632;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `product_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `shipping_address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `deliveries_ibfk_2` FOREIGN KEY (`shipping_address_id`) REFERENCES `shipping_addresses` (`shipping_address_id`);

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
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

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
