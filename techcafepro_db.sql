-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2025 at 06:17 AM
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
(1, 'Coffee', 1),
(2, 'Tea', 1),
(3, 'Frappe', 1),
(4, 'Smoothie', 1),
(5, 'Drinks', 1),
(6, 'Cake', 1),
(7, 'Pastry', 1),
(8, 'Sandwich', 1);

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

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `unit` int(11) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `amount` double(8,2) NOT NULL,
  `method` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `paid_at` datetime NOT NULL DEFAULT current_timestamp(),
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `price` double(8,2) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `photo` varchar(100) NOT NULL,
  `sold` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `price`, `description`, `created_at`, `is_available`, `photo`, `sold`, `status`, `category_id`) VALUES
(1, 'Americano', 7.00, 'A good cup of Americano', '2025-12-21 12:23:45', 1, '69477a261eb9c.jpg', 0, 1, 1),
(2, 'Latte', 8.00, 'A good cup of Latte', '2025-12-21 12:23:45', 1, '69477a5ba59e2.jpg', 0, 1, 1),
(3, 'Espresso', 9.00, 'A good cup of Espresso', '2025-12-21 12:23:45', 1, '69477a30a0481.jpg', 0, 1, 1),
(4, 'Flat White', 10.00, 'A good cup of Flat White', '2025-12-21 12:23:45', 1, '69477a378ae81.jpg', 0, 1, 1),
(5, 'Mocha', 11.00, 'A good cup of Mocha', '2025-12-21 12:23:45', 1, '69477a3ed2a8b.jpg', 0, 1, 1),
(6, 'Cappuccino', 12.00, 'A good cup of Cappuccino', '2025-12-21 12:23:45', 1, '69477a462caa0.jpg', 0, 1, 1),
(7, 'Matcha Tea', 8.00, 'Fragrant Japanese matcha tea', '2025-12-21 12:23:54', 1, '69477b9319e49.jpg', 0, 1, 2),
(8, 'Brown Sugar Milk Tea', 9.00, 'Tea with rich brown sugar flavour', '2025-12-21 12:23:54', 1, '69477bb5edcd7.jpg', 0, 1, 2),
(9, 'Strawberry Tea', 8.00, 'Refreshing strawberry-infused tea', '2025-12-21 12:23:54', 1, '69477bc8692c6.jpg', 0, 1, 2),
(10, 'Peach Tea', 8.00, 'Sweet and fruity peach tea', '2025-12-21 12:23:54', 1, '69477be6a3fbe.jpg', 0, 1, 2),
(11, 'Lemon Tea', 7.00, 'Classic lemon-flavoured tea', '2025-12-21 12:23:54', 1, '69477bf4f0226.jpg', 0, 1, 2),
(12, 'Cinnamon Tea', 8.00, 'Warm cinnamon-spiced tea', '2025-12-21 12:23:54', 1, '69477c071c0a3.jpg', 0, 1, 2),
(13, 'Jasmine Tea', 7.00, 'Light and aromatic jasmine tea', '2025-12-21 12:23:54', 1, '69477c16a2c8e.jpg', 0, 1, 2),
(14, 'Chocolate Frappe', 12.00, 'Blended chocolate frappe', '2025-12-21 12:23:54', 1, '69477ed880b79.jpg', 0, 1, 3),
(15, 'Caramel Frappe', 12.00, 'Creamy caramel blended drink', '2025-12-21 12:23:54', 1, '69477ee537d36.jpg', 0, 1, 3),
(16, 'Matcha Frappe', 13.00, 'Icy blended matcha drink', '2025-12-21 12:23:54', 1, '69477efb52e58.jpg', 0, 1, 3),
(17, 'Strawberry Frappe', 12.00, 'Sweet strawberry blended frappe', '2025-12-21 12:23:54', 1, '69477f0b886c9.jpg', 0, 1, 3),
(18, 'Mocha Frappe', 13.00, 'Coffee and chocolate blended drink', '2025-12-21 12:23:54', 1, '69477f1f4ceda.jpg', 0, 1, 3),
(19, 'Brown Sugar Frappe', 13.00, 'Rich brown sugar icy frappe', '2025-12-21 12:23:54', 1, '69477f2ea18a2.jpg', 0, 1, 3),
(20, 'Coconut Frappe', 12.00, 'Tropical coconut blended frappe', '2025-12-21 12:23:54', 1, '69477f3b43aa6.jpg', 0, 1, 3),
(21, 'Mango Smoothie', 11.00, 'Fresh blended mango smoothie', '2025-12-21 12:23:54', 1, '69478202b9b4f.jpg', 0, 1, 4),
(22, 'Strawberry Smoothie', 11.00, 'Creamy strawberry smoothie', '2025-12-21 12:23:54', 1, '694782350c085.jpg', 0, 1, 4),
(23, 'Blueberry Smoothie', 12.00, 'Antioxidant-rich blueberry smoothie', '2025-12-21 12:23:54', 1, '6947824749100.jpg', 0, 1, 4),
(24, 'Pineapple Smoothie', 11.00, 'Refreshing pineapple smoothie', '2025-12-21 12:23:54', 1, '69478255e659f.jpg', 0, 1, 4),
(25, 'Coconut Smoothie', 11.00, 'Smooth coconut-flavoured drink', '2025-12-21 12:23:54', 1, '694782644b74f.jpg', 0, 1, 4),
(26, 'Mango Pineapple Smoothie', 12.00, 'Tropical mango & pineapple blend', '2025-12-21 12:23:54', 1, '69478274e16f7.jpg', 0, 1, 4),
(27, 'Strawberry Banana Smoothie', 12.00, 'Classic strawberry banana blend', '2025-12-21 12:23:54', 1, '69478281bfa37.jpg', 0, 1, 4),
(28, 'Coca-Cola', 5.00, 'Chilled Coca-Cola', '2025-12-21 12:23:54', 1, '69477de18ffad.jpg', 0, 1, 5),
(29, 'Sprite', 5.00, 'Refreshing lemon-lime soda', '2025-12-21 12:23:54', 1, '69477df4af96e.jpg', 0, 1, 5),
(30, 'Mineral Water', 4.00, 'Bottled mineral water', '2025-12-21 12:23:54', 1, '69477e0356040.jpg', 0, 1, 5),
(31, 'Sparkling Water', 5.00, 'Carbonated bottled water', '2025-12-21 12:23:54', 1, '69477e0ddede5.jpg', 0, 1, 5),
(32, 'Orange Juice', 6.00, 'Cold bottled orange juice', '2025-12-21 12:23:54', 1, '69477e200187b.jpg', 0, 1, 5),
(33, 'Apple Juice', 6.00, 'Sweet bottled apple juice', '2025-12-21 12:23:54', 1, '69477e2bcebb7.jpg', 0, 1, 5),
(34, 'Coconut Water', 6.00, 'Natural coconut water', '2025-12-21 12:23:54', 1, '69477e38718d3.jpg', 0, 1, 5),
(35, 'Chocolate Cake', 15.00, 'Rich chocolate layered cake', '2025-12-21 12:23:54', 1, '69477cc47ce08.jpg', 0, 1, 6),
(36, 'Strawberry Cheesecake', 16.00, 'Creamy cheesecake with strawberry', '2025-12-21 12:23:54', 1, '69477cf2926a7.jpg', 0, 1, 6),
(37, 'Matcha Cake', 16.00, 'Soft matcha-flavoured cake', '2025-12-21 12:23:54', 1, '69477d0306e9c.jpg', 0, 1, 6),
(38, 'Blueberry Cheesecake', 16.00, 'Cheesecake topped with blueberry', '2025-12-21 12:23:54', 1, '69477d14dde42.jpg', 0, 1, 6),
(39, 'Caramel Cake', 15.00, 'Sweet caramel-flavoured cake', '2025-12-21 12:23:54', 1, '69477d2e61f48.jpg', 0, 1, 6),
(40, 'Coconut Cake', 15.00, 'Light coconut sponge cake', '2025-12-21 12:23:54', 1, '69477d3c75354.jpg', 0, 1, 6),
(41, 'Brown Sugar Cake', 15.00, 'Moist brown sugar cake', '2025-12-21 12:23:54', 1, '69477d4d711cc.jpg', 0, 1, 6),
(42, 'Butter Croissant', 7.00, 'Flaky butter croissant', '2025-12-21 12:23:54', 1, '69477fb664b3f.jpg', 0, 1, 7),
(43, 'Chocolate Croissant', 8.00, 'Croissant filled with chocolate', '2025-12-21 12:23:54', 1, '69477fc543313.jpg', 0, 1, 7),
(44, 'Cinnamon Roll', 8.00, 'Soft cinnamon pastry roll', '2025-12-21 12:23:54', 1, '69477fd4c658e.jpg', 0, 1, 7),
(45, 'Strawberry Danish', 8.00, 'Pastry topped with strawberry', '2025-12-21 12:23:54', 1, '69477fe75f6b1.jpg', 0, 1, 7),
(46, 'Blueberry Danish', 8.00, 'Pastry topped with blueberry', '2025-12-21 12:23:54', 1, '69477ff830c80.jpg', 0, 1, 7),
(47, 'Caramel Puff', 7.00, 'Light pastry with caramel filling', '2025-12-21 12:23:54', 1, '6947800e4cab3.jpg', 0, 1, 7),
(48, 'Coconut Tart', 7.00, 'Pastry tart with coconut filling', '2025-12-21 12:23:54', 1, '6947801d18fe6.jpg', 0, 1, 7),
(49, 'Chicken Sandwich', 12.00, 'Grilled chicken sandwich', '2025-12-21 12:23:54', 1, '694780c439f66.jpg', 0, 1, 8),
(50, 'Tuna Sandwich', 12.00, 'Classic tuna sandwich', '2025-12-21 12:23:54', 1, '694780ed82487.jpg', 0, 1, 8),
(51, 'Egg Mayo Sandwich', 11.00, 'Egg mayonnaise sandwich', '2025-12-21 12:23:54', 1, '6947810640af9.jpg', 0, 1, 8),
(52, 'Ham & Cheese Sandwich', 12.00, 'Ham and cheese sandwich', '2025-12-21 12:23:54', 1, '69478117d0e11.jpg', 0, 1, 8),
(53, 'Chicken Teriyaki Sandwich', 13.00, 'Sweet teriyaki chicken sandwich', '2025-12-21 12:23:54', 1, '6947812b773ef.jpg', 0, 1, 8),
(54, 'Veggie Sandwich', 11.00, 'Fresh vegetable sandwich', '2025-12-21 12:23:54', 1, '6947814684d26.jpg', 0, 1, 8),
(55, 'Beef Sandwich', 14.00, 'Juicy beef sandwich', '2025-12-21 12:23:54', 1, '69478152bca32.jpg', 0, 1, 8);

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
(1, 1),
(1, 2),
(1, 4),
(2, 1),
(2, 2),
(2, 4),
(2, 5),
(3, 1),
(3, 2),
(3, 4),
(4, 1),
(4, 2),
(4, 4),
(4, 5),
(5, 1),
(5, 2),
(5, 4),
(5, 5),
(5, 10),
(6, 1),
(6, 2),
(6, 4),
(6, 5),
(7, 1),
(7, 2),
(7, 12),
(7, 19),
(8, 2),
(8, 5),
(8, 14),
(8, 19),
(9, 1),
(9, 2),
(9, 9),
(9, 19),
(10, 1),
(10, 2),
(10, 19),
(11, 1),
(11, 2),
(11, 19),
(12, 1),
(12, 2),
(12, 13),
(12, 19),
(13, 1),
(13, 2),
(13, 19),
(14, 2),
(14, 4),
(14, 5),
(14, 10),
(15, 2),
(15, 4),
(15, 5),
(15, 15),
(16, 2),
(16, 4),
(16, 5),
(16, 12),
(17, 2),
(17, 4),
(17, 5),
(17, 9),
(18, 2),
(18, 4),
(18, 5),
(19, 2),
(19, 4),
(19, 5),
(19, 14),
(20, 2),
(20, 4),
(20, 5),
(20, 16),
(21, 2),
(21, 5),
(21, 11),
(22, 2),
(22, 5),
(22, 9),
(23, 2),
(23, 5),
(23, 18),
(24, 2),
(24, 5),
(24, 17),
(25, 2),
(25, 5),
(25, 16),
(26, 2),
(26, 5),
(26, 11),
(26, 17),
(27, 2),
(27, 5),
(27, 9),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(34, 16),
(35, 2),
(35, 5),
(35, 6),
(35, 8),
(35, 10),
(36, 2),
(36, 5),
(36, 6),
(36, 8),
(36, 9),
(37, 2),
(37, 5),
(37, 6),
(37, 8),
(37, 12),
(38, 2),
(38, 5),
(38, 6),
(38, 8),
(38, 18),
(39, 2),
(39, 5),
(39, 6),
(39, 8),
(39, 15),
(40, 2),
(40, 5),
(40, 6),
(40, 8),
(40, 16),
(41, 2),
(41, 5),
(41, 6),
(41, 8),
(41, 14),
(42, 1),
(42, 5),
(42, 6),
(42, 8),
(43, 1),
(43, 5),
(43, 6),
(43, 8),
(43, 10),
(44, 1),
(44, 5),
(44, 6),
(44, 8),
(44, 13),
(45, 1),
(45, 5),
(45, 6),
(45, 8),
(45, 9),
(46, 1),
(46, 5),
(46, 6),
(46, 8),
(46, 18),
(47, 2),
(47, 5),
(47, 6),
(47, 8),
(47, 15),
(48, 1),
(48, 5),
(48, 6),
(48, 8),
(48, 16),
(49, 1),
(49, 5),
(49, 6),
(49, 8),
(50, 1),
(50, 5),
(50, 6),
(50, 8),
(51, 1),
(51, 5),
(51, 6),
(51, 8),
(52, 1),
(52, 5),
(52, 6),
(52, 8),
(53, 1),
(53, 5),
(53, 6),
(53, 8),
(54, 1),
(54, 5),
(54, 6),
(54, 8),
(55, 1),
(55, 5),
(55, 6),
(55, 8);

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
(1, 'Hot', 'Temperature'),
(2, 'Cold', 'Temperature'),
(3, 'Warm', 'Temperature'),
(4, 'Coffee', 'Base'),
(5, 'Milk', 'Base'),
(6, 'Eggs', 'Base'),
(7, 'Nuts', 'Base'),
(8, 'Wheat', 'Base'),
(9, 'Strawberry', 'Flavour'),
(10, 'Chocolate', 'Flavour'),
(11, 'Mango', 'Flavour'),
(12, 'Matcha', 'Flavour'),
(13, 'Cinnamon', 'Flavour'),
(14, 'Brown Sugar', 'Flavour'),
(15, 'Caramel', 'Flavour'),
(16, 'Coconut', 'Flavour'),
(17, 'Pineapple', 'Flavour'),
(18, 'Blueberry', 'Flavour'),
(19, 'Tea', 'Base');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `token_id` varchar(100) NOT NULL,
  `expire` datetime NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'superadmin', 'superadmin@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '6947784ed647b.jpg', '2025-12-21 11:21:59', 'admin', 1, 0, 0, NULL, 0, NULL),
(2, 'Charlie Blake', 'charlieblake@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '6947789d22338.jpg', '2025-12-21 11:34:14', 'customer', 1, 0, 0, NULL, 0, NULL),
(3, 'White Black', 'darkchoco@outlook.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '694778a2c5c6d.jpg', '2025-12-21 11:35:43', 'customer', 1, 0, 0, NULL, 0, NULL),
(4, 'Shahira Angelito', 'shashask@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '694778b242d1e.jpg', '2025-12-21 11:36:35', 'customer', 1, 0, 0, NULL, 0, NULL),
(5, 'Smith Smooth', 'operator3798@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '694778b6e2a8f.jpg', '2025-12-21 11:37:36', 'customer', 1, 0, 0, NULL, 0, NULL),
(6, 'Singh Naveen Harjot', 'singhnh86@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '694778c73fbbe.jpg', '2025-12-21 11:40:24', 'customer', 1, 0, 0, NULL, 0, NULL),
(7, 'Alif Muhhamed', 'alippt@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '694778cc39318.jpg', '2025-12-21 11:41:19', 'customer', 1, 0, 0, NULL, 0, NULL),
(8, 'Catherine Karen', 'calvinkleinn43@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '694778d0c1964.jpg', '2025-12-21 11:42:08', 'customer', 1, 0, 0, NULL, 0, NULL),
(9, 'Angie Tick', 'angietic@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '694778d54518c.jpg', '2025-12-21 11:43:54', 'customer', 1, 0, 0, NULL, 0, NULL),
(10, 'Siti Haslinda', 'sitihaslinda@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '694778d9b041c.jpg', '2025-12-21 11:48:58', 'customer', 1, 0, 0, NULL, 0, NULL),
(11, 'techcafepro', 'techcafepro@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '694778de5dfa7.jpg', '2025-12-21 11:54:02', 'admin', 1, 0, 0, NULL, 0, NULL),
(12, 'admin1', 'admin1@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '6947785435355.jpg', '2025-12-21 12:11:32', 'admin', 1, 0, 0, NULL, 0, NULL),
(13, 'admin2', 'admin2@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '6947785953972.jpg', '2025-12-21 12:11:55', 'admin', 1, 0, 0, NULL, 0, NULL);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
