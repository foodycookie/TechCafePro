-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 07:23 AM
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
(4, 'Ice cream', 1),
(6, 'Frappe', 1),
(16, 'Donut', 1);

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
(378, 'Mocha', 10.00, 'Chocolate-flavored latte with espresso.', '2025-12-13 20:04:42', 0, '', 0, 0, 3),
(379, 'Cappuccino', 9.00, 'Espresso topped with frothed milk.', '2025-12-13 20:04:42', 0, '', 0, 0, 3),
(385, 'Cheese Cake', 13.00, 'Creamy baked cheese dessert.', '2025-12-13 20:04:42', 0, '', 0, 0, 2),
(386, 'Black Forest', 14.00, 'Chocolate cake with cherries and cream.', '2025-12-13 20:04:42', 1, '', 0, 1, 2),
(387, 'Tiramisu', 15.00, 'Coffee-flavored Italian layered dessert.', '2025-12-13 20:04:42', 1, '', 0, 1, 2),
(388, 'Chocolate', 6.50, 'Creamy chocolate ice cream.', '2025-12-13 20:04:42', 1, '', 0, 1, 4),
(389, 'Vanilla', 6.00, 'Classic vanilla ice cream.', '2025-12-13 20:04:42', 1, '', 0, 1, 4),
(391, 'Green Tea', 6.80, 'Japanese matcha green tea ice cream.', '2025-12-13 20:04:42', 1, '', 0, 1, 4),
(392, 'Mocha Frappe', 12.00, 'good', '2025-12-13 20:04:42', 0, '6935a67889266.jpg', 0, 1, 6),
(393, 'Matcha Tiramisu', 20.00, 'Green and good', '2025-12-13 20:04:42', 0, '6935af726ca1c.jpg', 0, 1, 2),
(403, 'Mocha', 10.00, 'Chocolate-flavored latte with espresso.', '2025-12-13 20:05:41', 1, '', 0, 1, 3),
(404, 'Cappuccino', 9.00, 'Espresso topped with frothed milk.', '2025-12-13 20:05:41', 1, '', 0, 1, 3),
(409, 'Chocolate Cake', 12.50, 'Rich and moist chocolate cake.', '2025-12-13 20:05:41', 1, '', 0, 1, 2),
(410, 'Cheese Cake', 13.00, 'Creamy baked cheese dessert.', '2025-12-13 20:05:41', 1, '', 0, 1, 2),
(411, 'Black Forest', 14.00, 'Chocolate cake with cherries and cream.', '2025-12-13 20:05:41', 1, '', 0, 1, 2),
(412, 'Tiramisu', 15.00, 'Coffee-flavored Italian layered dessert.', '2025-12-13 20:05:41', 1, '', 0, 1, 2),
(413, 'Chocolate', 6.50, 'Creamy chocolate ice cream.', '2025-12-13 20:05:41', 1, '', 0, 1, 4),
(414, 'Vanilla', 6.00, 'Classic vanilla ice cream.', '2025-12-13 20:05:41', 1, '', 0, 1, 4),
(416, 'Green Tea', 6.80, 'Japanese matcha green tea ice cream.', '2025-12-13 20:05:41', 1, '', 0, 1, 4),
(417, 'Mocha Frappe', 12.00, 'good', '2025-12-13 20:05:41', 0, '6935a67889266.jpg', 0, 1, 6),
(418, 'Matcha Tiramisu', 20.00, 'Green and good', '2025-12-13 20:05:41', 0, '6935af726ca1c.jpg', 0, 1, 2),
(458, '1111', 1.00, '11', '2025-12-15 09:54:27', 1, '693f6a53aaac4.jpg', 0, 1, 2),
(459, '2222', 0.01, '1', '2025-12-16 01:43:33', 1, '694048c59d481.jpg', 0, 1, 2),
(460, 'ccc', 0.01, 's', '2025-12-16 01:50:16', 1, '69404a58d668b.jpg', 0, 1, 2),
(461, 'coffee', 0.01, '1', '2025-12-16 01:52:46', 1, '69404aee61406.jpg', 0, 1, 3),
(462, 'milk', 0.01, '1', '2025-12-16 01:53:02', 1, '69404afe087a3.jpg', 0, 1, 3),
(463, 'Strawberry1', 0.01, '1', '2025-12-16 01:53:31', 0, '69404b1b4816b.jpg', 0, 1, 3),
(464, 'none', 0.01, '1', '2025-12-16 01:53:46', 1, '69404b2aca47e.jpg', 0, 1, 3),
(465, 'donut chocolate', 0.01, '1', '2025-12-16 02:27:54', 1, '6940532a16a0c.jpg', 0, 0, 16),
(466, 'donut plain', 0.01, '1', '2025-12-16 02:28:10', 1, '6940533ad02ca.jpg', 0, 1, 16),
(467, 'chocolate donut', 0.04, '1', '2025-12-16 03:16:09', 0, '69405e79628dd.jpg', 0, 1, 16),
(468, 'strawberry donut', 1.01, '1', '2025-12-16 03:16:34', 1, '69405e9261977.jpg', 0, 1, 16),
(469, '111', 0.01, '1', '2025-12-16 04:11:21', 1, '69406b699cb5f.jpg', 0, 1, 2),
(470, 'a', 0.01, 'k', '2025-12-16 13:54:28', 0, '6940f41492a43.jpg', 0, 0, 2),
(471, 'aaa', 0.01, 'a', '2025-12-16 13:54:47', 1, '6940f427d20f9.jpg', 0, 0, 2);

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
(45, 'Cinnamon', 'Flavour');

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
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `profile_image_path`, `created_at`, `role`) VALUES
(1, '11111', 'ong@gmail.com', '7b21848ac9af35be0ddb2d6b9fc3851934db8420', '693bcfff700d8.jpg', '2025-12-12 16:19:11', 'customer'),
(2, '22222', 'onghao.howard@gmail.com', '3e511da7577d1864871b760ab30e05b56943c9b2', '693bd0424f7a0.jpg', '2025-12-12 16:20:18', 'customer'),
(3, 'aaaaa', 'onghao@gmail.com', 'df51e37c269aa94d38f93e537bf6e2020b21406c', '693d620f89157.jpg', '2025-12-13 20:54:39', 'customer');

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
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=472;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `product_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `shipping_address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
