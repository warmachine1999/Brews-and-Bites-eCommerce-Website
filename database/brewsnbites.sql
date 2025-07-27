-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2024 at 04:18 PM
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
-- Database: `brewsnbites`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', 'admin', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `beverage`
--

CREATE TABLE `beverage` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` int(255) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `image` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beverage`
--

INSERT INTO `beverage` (`id`, `product_name`, `price`, `product_code`, `image`) VALUES
(1, 'tasty and unhealthy', 170, 'drinks', 0x75706c6f6164732f636f66666565312e706e67),
(2, 'masarap walang tubig', 189, 'drinks', 0x75706c6f6164732f636f66666565322e706e67),
(3, 'ready kang ipaglaban', 175, 'drinks', 0x75706c6f6164732f636f66666565332e6a7067),
(4, 'kape sa tanghali', 170, 'drinks', 0x75706c6f6164732f636f66666565342e6a7067),
(5, '3 in 1 kopiko', 189, 'drinks', 0x75706c6f6164732f636172742d6974656d2d342e6a7067),
(8, 'strawberry cake', 250, 'cake', 0x75706c6f6164732f6d656e752d372e6a7067),
(9, 'Baguio Brew', 450, 'product', 0x75706c6f6164732f70726f647563742d312e6a7067),
(10, 'Coffee Buddy', 480, 'product', 0x75706c6f6164732f70726f647563742d322e6a7067),
(11, 'Basilio Coffee', 580, 'product', 0x75706c6f6164732f70726f647563742d332e6a7067),
(12, 'Cinnamon Rolls', 95, 'bread', 0x75706c6f6164732f626c6f67732d332e6a7067),
(13, 'Brownies with Choco Syrup', 100, 'cake', 0x75706c6f6164732f6d656e752d31302e6a7067),
(14, 'Blackberry Brownies', 180, 'bread', 0x75706c6f6164732f6d656e752d392e6a7067),
(15, 'Ultimate Mocha Cake', 1500, 'cake', 0x75706c6f6164732f6d656e752d382e706e67),
(16, 'Normal Bread', 89, 'bread', 0x75706c6f6164732f6d656e752d362e706e67),
(17, 'Cinnamon Rolls with free Croissant', 150, 'bread', 0x75706c6f6164732f626c6f67732d322e6a7067),
(18, 'Kapeng may Heart', 145, 'drinks', 0x75706c6f6164732f6d656e752d352e706e67);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `customer_number` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  `status` enum('Pending','Processed','Out for Delivery','Delivered') DEFAULT 'Pending',
  `payment_method` enum('cash_on_delivery','instapay') NOT NULL DEFAULT 'cash_on_delivery',
  `reference_number` varchar(255) NOT NULL DEFAULT 'N/A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `customer_address`, `customer_number`, `total_price`, `order_date`, `user_id`, `status`, `payment_method`, `reference_number`) VALUES
(39, '', '', 0, 0.00, '2024-10-10 23:46:59', 0, 'Delivered', '', ''),
(40, '', '', 0, 0.00, '2024-10-10 23:46:59', 0, 'Delivered', '', ''),
(41, '', '', 0, 1096.00, '2024-10-10 23:46:59', 0, 'Delivered', '', ''),
(42, '', '', 0, 2598.00, '2024-10-10 23:48:56', 0, 'Delivered', '', ''),
(43, '', '', 0, 1422.00, '2024-10-11 00:07:57', 0, 'Delivered', '', ''),
(44, '', '', 0, 0.00, '2024-10-11 10:09:20', 1, 'Delivered', '', ''),
(45, '', '', 0, 0.00, '2024-10-11 10:11:56', 1, 'Delivered', '', ''),
(46, '', '', 0, 0.00, '2024-10-11 10:16:51', 1, 'Delivered', '', ''),
(47, '', '', 0, 1630.00, '2024-10-11 10:18:19', 1, 'Delivered', '', ''),
(48, '', '', 0, 539.00, '2024-10-11 10:24:03', 3, 'Delivered', '', ''),
(49, '', '', 0, 539.00, '2024-10-20 09:29:34', 3, 'Delivered', '', ''),
(50, '', '', 0, 175.00, '2024-10-20 10:21:11', 3, 'Delivered', '', ''),
(51, '', '', 0, 567.00, '2024-10-20 11:20:35', 3, 'Delivered', '', ''),
(52, '', '', 0, 718.00, '2024-10-20 20:28:19', 3, 'Delivered', '', ''),
(53, 'ian', 'caloocan', 938123123, 893.00, '2024-10-20 21:12:14', 3, 'Delivered', 'instapay', '1234ert'),
(54, 'ian', 'caloocan', 938123123, 893.00, '2024-10-20 21:14:12', 3, 'Delivered', 'instapay', '12345356'),
(55, 'ian', 'caloocan', 938123123, 175.00, '2024-10-20 22:59:19', 3, 'Delivered', '', ''),
(56, 'ian', 'caloocan', 938123123, 893.00, '2024-10-20 23:05:01', 3, 'Delivered', '', ''),
(57, 'ian', 'caloocan', 938123123, 345.00, '2024-10-20 23:05:51', 3, 'Delivered', '', ''),
(58, 'ian', 'caloocan', 938123123, 1890.00, '2024-10-20 23:07:22', 3, 'Delivered', '', ''),
(59, 'ian', 'caloocan', 938123123, 534.00, '2024-10-20 23:09:03', 3, 'Delivered', '', ''),
(60, 'ian', 'caloocan', 938123123, 534.00, '2024-10-20 23:10:50', 3, 'Delivered', 'cash_on_delivery', ''),
(61, 'ian', 'caloocan', 938123123, 189.00, '2024-10-20 23:12:31', 3, 'Delivered', 'cash_on_delivery', ''),
(62, 'ian', 'caloocan', 938123123, 189.00, '2024-10-20 23:13:14', 3, 'Delivered', 'cash_on_delivery', ''),
(63, 'ian', 'caloocan', 938123123, 0.00, '2024-10-20 23:14:52', 0, 'Delivered', 'cash_on_delivery', 'N/A'),
(64, 'admin', 'caloocan', 938123123, 0.00, '2024-10-20 23:15:27', 0, 'Out for Delivery', 'cash_on_delivery', 'N/A'),
(65, 'admin', 'caloocan', 938123123, 345.00, '2024-10-20 23:16:18', 3, 'Delivered', 'cash_on_delivery', 'N/A'),
(66, 'admin', 'caloocan', 938123123, 723.00, '2024-10-22 10:08:03', 3, 'Delivered', 'cash_on_delivery', 'N/A'),
(67, 'admin', 'caloocan', 938123123, 784.00, '2024-10-22 13:05:39', 3, 'Processed', 'cash_on_delivery', 'N/A'),
(68, 'admin', 'caloocan', 938123123, 559.00, '2024-10-22 13:12:31', 3, 'Processed', 'instapay', '1234567'),
(69, 'riko', 'caloocan', 938123123, 1263.00, '2024-10-22 14:03:29', 3, 'Processed', 'instapay', 'a12qwe34'),
(70, 'admin', 'caloocan', 938123123, 1630.00, '2024-10-23 12:09:59', 3, 'Processed', 'cash_on_delivery', 'N/A'),
(71, 'admin', 'caloocan', 938123123, 1039.00, '2024-10-23 12:11:09', 3, 'Processed', 'cash_on_delivery', 'N/A'),
(72, 'riko', 'manila', 2147483647, 1854.00, '2024-10-24 12:39:14', 1, 'Processed', 'cash_on_delivery', 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `price`, `quantity`) VALUES
(33, 42, 'tasty and unhealthy', 170.00, 3),
(34, 42, 'masarap walang tubig', 189.00, 2),
(35, 42, 'ready kang ipaglaban', 175.00, 2),
(36, 42, 'kape sa tanghali', 170.00, 4),
(37, 42, 'kape sa tanghali', 170.00, 4),
(38, 43, 'masarap walang tubig', 189.00, 3),
(39, 43, 'ready kang ipaglaban', 175.00, 1),
(40, 43, 'kape sa tanghali', 170.00, 2),
(41, 43, 'kape sa tanghali', 170.00, 2),
(42, 44, '3n1 kopiko', 189.00, 1),
(43, 44, 'masarap walang tubig', 189.00, 1),
(44, 44, 'masarap walang tubig', 189.00, 1),
(45, 45, 'kape sa tanghali', 170.00, 1),
(46, 45, 'ready kang ipaglaban', 175.00, 1),
(47, 45, 'ready kang ipaglaban', 175.00, 1),
(48, 46, 'masarap walang tubig', 189.00, 3),
(49, 46, 'ready kang ipaglaban', 175.00, 2),
(50, 46, '3n1 kopiko', 189.00, 1),
(51, 46, '3n1 kopiko', 189.00, 1),
(52, 47, 'tasty and unhealthy', 170.00, 3),
(53, 47, '3n1 kopiko', 189.00, 3),
(54, 47, 'ready kang ipaglaban', 175.00, 1),
(55, 47, 'masarap walang tubig', 189.00, 1),
(56, 47, 'masarap walang tubig', 189.00, 1),
(57, 48, 'masarap walang tubig', 189.00, 1),
(58, 48, 'ready kang ipaglaban', 175.00, 1),
(59, 48, 'ready kang ipaglaban', 175.00, 1),
(60, 49, 'masarap walang tubig', 189.00, 1),
(61, 49, 'ready kang ipaglaban', 175.00, 1),
(62, 49, 'ready kang ipaglaban', 175.00, 1),
(63, 50, 'ready kang ipaglaban', 175.00, 1),
(64, 51, 'masarap walang tubig', 189.00, 3),
(65, 52, 'tasty and unhealthy', 170.00, 1),
(66, 52, 'masarap walang tubig', 189.00, 1),
(67, 52, 'kape sa tanghali', 170.00, 1),
(68, 52, '3n1 kopiko', 189.00, 1),
(69, 53, 'tasty and unhealthy', 170.00, 1),
(70, 53, 'masarap walang tubig', 189.00, 1),
(71, 53, 'ready kang ipaglaban', 175.00, 1),
(72, 53, 'kape sa tanghali', 170.00, 1),
(73, 53, '3n1 kopiko', 189.00, 1),
(74, 54, 'tasty and unhealthy', 170.00, 1),
(75, 54, 'masarap walang tubig', 189.00, 1),
(76, 54, 'ready kang ipaglaban', 175.00, 1),
(77, 54, 'kape sa tanghali', 170.00, 1),
(78, 54, '3n1 kopiko', 189.00, 1),
(79, 55, 'ready kang ipaglaban', 175.00, 1),
(80, 56, 'tasty and unhealthy', 170.00, 1),
(81, 56, 'masarap walang tubig', 189.00, 1),
(82, 56, 'ready kang ipaglaban', 175.00, 1),
(83, 56, 'kape sa tanghali', 170.00, 1),
(84, 56, '3n1 kopiko', 189.00, 1),
(85, 57, 'ready kang ipaglaban', 175.00, 1),
(86, 57, 'kape sa tanghali', 170.00, 1),
(87, 58, '3n1 kopiko', 189.00, 10),
(88, 59, 'masarap walang tubig', 189.00, 1),
(89, 59, 'ready kang ipaglaban', 175.00, 1),
(90, 59, 'kape sa tanghali', 170.00, 1),
(91, 60, 'masarap walang tubig', 189.00, 1),
(92, 60, 'ready kang ipaglaban', 175.00, 1),
(93, 60, 'kape sa tanghali', 170.00, 1),
(94, 61, 'masarap walang tubig', 189.00, 1),
(95, 62, '3n1 kopiko', 189.00, 1),
(96, 65, 'ready kang ipaglaban', 175.00, 1),
(97, 65, 'kape sa tanghali', 170.00, 1),
(98, 66, 'masarap walang tubig', 189.00, 1),
(99, 66, 'ready kang ipaglaban', 175.00, 1),
(100, 66, 'kape sa tanghali', 170.00, 1),
(101, 66, '3n1 kopiko', 189.00, 1),
(102, 67, 'masarap walang tubig', 189.00, 1),
(103, 67, 'strawberry cake', 250.00, 1),
(104, 67, 'ready kang ipaglaban', 175.00, 1),
(105, 67, 'kape sa tanghali', 170.00, 1),
(106, 68, '3 in 1 kopiko', 189.00, 1),
(107, 68, 'strawberry cake', 250.00, 1),
(108, 69, 'strawberry cake', 250.00, 1),
(109, 69, '3 in 1 kopiko', 189.00, 1),
(110, 69, 'tasty and unhealthy', 170.00, 1),
(111, 69, 'masarap walang tubig', 189.00, 1),
(112, 69, 'ready kang ipaglaban', 175.00, 1),
(113, 69, 'kape sa tanghali', 170.00, 1),
(114, 70, 'Baguio Brew', 450.00, 1),
(115, 70, 'Coffee Buddy', 480.00, 1),
(116, 70, 'Basilio Coffee', 580.00, 1),
(117, 71, 'Coffee Buddy', 480.00, 1),
(118, 71, 'strawberry cake', 250.00, 1),
(119, 71, '3 in 1 kopiko', 189.00, 1),
(120, 72, 'Kapeng may Heart', 145.00, 1),
(121, 72, 'Ultimate Mocha Cake', 1500.00, 1),
(122, 72, 'Normal Bread', 89.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `review` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `order_id`, `user_id`, `review`, `rating`, `created_at`) VALUES
(1, 50, 3, 'Fast transaction and very delicious ', 5, '2024-10-23 13:44:29'),
(2, 53, 3, 'Very affordable', 5, '2024-10-23 13:49:09'),
(3, 54, 3, 'Delivery is very late and my order is already cold', 3, '2024-10-23 13:51:46'),
(4, 55, 3, 'testing', 4, '2024-10-23 13:52:39'),
(5, 56, 3, 'awesome', 5, '2024-10-23 13:57:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `address`, `created_at`) VALUES
(1, 'Ian Diether', '$2y$10$gd382iJM9r6Rf7qKRsfGL.nxboAglPcWDCZHNgY7pldHuKu56ICEa', 'domingoiandiether@yahoo.com', 'Caloocan City', '2024-10-10 16:01:07'),
(2, 'admin', '$2y$10$QPrJRgn46gNLNmsMM1ezSeeTKj2kC4X.N8nzWiGfZyQEwBivWiqnm', 'iandiethersubala@gmail.com', 'Quezon City', '2024-10-10 16:06:34'),
(3, 'JuanD', '$2y$10$JPJNMTuNWuOmkHZyQ5laguHOkRPpiTdcNyLsZUEH4LqGf0EbiXCia', 'admin@gmail.com', 'Pasig City', '2024-10-11 02:23:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beverage`
--
ALTER TABLE `beverage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `beverage`
--
ALTER TABLE `beverage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
