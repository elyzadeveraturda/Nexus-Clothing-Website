-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2025 at 11:33 AM
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
-- Database: `nexustry`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_code` varchar(20) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `payment_method` varchar(50) NOT NULL,
  `user_info_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `created_at`, `payment_method`, `user_info_id`) VALUES
(4, 3, 7512.20, 'cancelled', '2025-07-09 04:21:35', 'credit_card', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_code` varchar(20) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_code`, `size`, `quantity`, `price`) VALUES
(8, 4, 'M001', 'M', 1, 2104.24);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` enum('women','men','kids') NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_code`, `name`, `description`, `price`, `category`, `image_url`) VALUES
('K001', 'Linen Blade Blazer', 'Modern essential piece made from sustainable fabric.', 2699.00, 'kids', 'assets/images/kids/k001a.jpg'),
('K002', 'Cotton Overalls', 'Soft and breathable.', 1899.00, 'kids', 'assets/images/kids/k002a.jpg'),
('K003', 'Graphic Tee Fun', 'Playful and colorful.', 1199.00, 'kids', 'assets/images/kids/k003a.jpg'),
('K004', 'Denim Shorts', 'Tough and trendy.', 1090.00, 'kids', 'assets/images/kids/k004a.jpg'),
('K005', 'Hooded Jacket', 'Warm and water resistant.', 2100.00, 'kids', 'assets/images/kids/k005a.jpg'),
('K006', 'Cargo Pants', 'Durable for all activities.', 2100.00, 'kids', 'assets/images/kids/k006a.jpg'),
('M001', 'Wide-legged Pants', 'Funky and Casual comfort in the same feels.', 2100.00, 'men', 'assets/images/men/m001a.jpg'),
('M0010', 'Knitted Polo Shirt', 'A refined upgrade to casual wear.', 1990.00, 'men', 'assets/images/men/m0010a.jpg'),
('m0011', 'Sport Shorts', 'Comfortable and breathable.', 1299.00, 'men', 'assets/images/men/m0011a.jpg'),
('M0012', 'Training Pants', 'Sleek and flexible for workouts.', 1790.00, 'men', 'assets/images/men/m0012a.jpg'),
('M0013', 'Chino Pants', 'Versatile and tailored.', 1895.00, 'men', 'assets/images/men/m0013a.jpg'),
('M0014', 'Long Sleeve Henley', 'A casual essential with classic style.', 1499.00, 'men', 'assets/images/men/m0014a.jpg'),
('M0015', 'Puffer Vest', 'Layering piece with warmth.', 2599.00, 'men', 'assets/images/men/m0015a.jpg'),
('M002', 'Tailored Shirt', 'Sharp and structured with breathable fabric', 1899.00, 'men', 'assets/images/men/m002a.jpg'),
('M003', 'Slim Fit Trousers', 'Perfect for formal and casual styling.', 2399.00, 'men', 'assets/images/men/m003a.jpg'),
('M004', 'Cotton Hoodie', 'Comfortable everyday essential.', 1800.00, 'men', 'assets/images/men/m004a.jpg'),
('M005', 'Denim Jacket', 'Classic piece with modern cuts.', 2200.00, 'men', 'assets/images/men/m005a.jpg'),
('M006', 'Graphic Tee', 'Statement shirt for casual days.', 1099.00, 'men', 'assets/images/men/m006a.jpg'),
('M007', 'Windbreaker Jacket', 'Lightweight and stylish protection.', 2290.00, 'men', 'assets/images/men/m007a.jpg'),
('M008', 'Bomber Jacket', 'A modern classic in neutral tones.', 2699.00, 'men', 'assets/images/men/m008a.jpg'),
('M009', 'Linen Shorts', 'Perfect for warm-weather casual looks.', 1590.00, 'men', 'assets/images/men/m009a.jpg'),
('W001', 'Short Dress', 'A wardrobe staple made with precision tailoring.', 2104.24, 'women', 'assets/images/women/w001a.jpg'),
('W002', 'Blouse with Pleats', 'Light and airy for summer days.', 1799.00, 'women', 'assets/images/women/w002a.jpg'),
('W003', 'High Waist Jeans', 'A flattering fit for every figure.', 2399.00, 'women', 'assets/images/women/w003a.jpg'),
('W004', 'Maxi Skirt', 'Feminine flowy design.', 2090.00, 'women', 'assets/images/women/w004a.jpg'),
('W005', 'Cropped Top', 'Trendy and breathable.', 1099.00, 'women', 'assets/images/women/w005a.jpg'),
('W006', 'Blazer Coat', 'Structured and elegant.', 2899.00, 'women', 'assets/images/women/w006a.jpg'),
('W007', 'Denim Jacket', 'Classic with feminine twist.', 2200.00, 'women', 'assets/images/women/w007a.jpg'),
('W008', 'Wrap Dress', 'Comfortable yet classy.', 2499.00, 'women', 'assets/images/women/w008a.jpg'),
('w009', 'SIlk Camisole', 'Luxurious and soft feel.', 1890.00, 'women', 'assets/images/women/w009a.jpg'),
('W010', 'Knitted Cardigan', 'Cozy essential.', 1990.00, 'women', 'assets/images/women/w011a.jpg'),
('W012', 'Pencil Skirt', 'Office-ready fashion.', 1690.00, 'women', 'assets/images/women/w013a.jpg'),
('W014', 'Bootcut Jeans', 'Retro style revived.', 1799.00, 'women', 'assets/images/women/w014a.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_code` varchar(20) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_code`, `image_url`) VALUES
(22, 'M001', 'assets/images/men/m001a.jpg'),
(23, 'M001', 'assets/images/men/m001b.jpg'),
(24, 'M001', 'assets/images/men/m001c.jpg'),
(25, 'W001', 'assets/images/women/w001a.jpg'),
(26, 'W001', 'assets/images/women/w001b.jpg'),
(27, 'W001', 'assets/images/women/w001c.jpg'),
(39, 'M002', 'assets/images/men/m002a.jpg'),
(40, 'M002', 'assets/images/men/m002b.jpg'),
(41, 'M002', 'assets/images/men/m002c.jpg'),
(42, 'M003', 'assets/images/men/m003a.jpg'),
(43, 'M003', 'assets/images/men/m003b.jpg'),
(44, 'M003', 'assets/images/men/m003c.jpg'),
(45, 'M004', 'assets/images/men/m004a.jpg'),
(46, 'M004', 'assets/images/men/m004b.jpg'),
(47, 'M004', 'assets/images/men/m004c.jpg'),
(48, 'M005', 'assets/images/men/m005a.jpg'),
(49, 'M005', 'assets/images/men/m005b.jpg'),
(50, 'M005', 'assets/images/men/m005c.jpg'),
(51, 'M006', 'assets/images/men/m006a.jpg'),
(52, 'M006', 'assets/images/men/m006b.jpg'),
(53, 'M006', 'assets/images/men/m006c.jpg'),
(54, 'M007', 'assets/images/men/m007a.jpg'),
(55, 'M007', 'assets/images/men/m007b.jpg'),
(56, 'M007', 'assets/images/men/m007c.jpg'),
(57, 'M008', 'assets/images/men/m008a.jpg'),
(58, 'M008', 'assets/images/men/m008b.jpg'),
(59, 'M008', 'assets/images/men/m008c.jpg'),
(60, 'M009', 'assets/images/men/m009a.jpg'),
(61, 'M009', 'assets/images/men/m009b.jpg'),
(62, 'M009', 'assets/images/men/m009c.jpg'),
(63, 'M0010', 'assets/images/men/m0010a.jpg'),
(64, 'M0010', 'assets/images/men/m0010b.jpg'),
(65, 'M0010', 'assets/images/men/m0010c.jpg'),
(66, 'm0011', 'assets/images/men/m0011a.jpg'),
(67, 'm0011', 'assets/images/men/m0011b.jpg'),
(68, 'm0011', 'assets/images/men/m0011c.jpg'),
(69, 'M0012', 'assets/images/men/m0012a.jpg'),
(70, 'M0012', 'assets/images/men/m0012b.jpg'),
(71, 'M0012', 'assets/images/men/m0012c.jpg'),
(72, 'M0013', 'assets/images/men/m0013a.jpg'),
(73, 'M0013', 'assets/images/men/m0013b.jpg'),
(74, 'M0013', 'assets/images/men/m0013c.jpg'),
(75, 'M0014', 'assets/images/men/m0014a.jpg'),
(76, 'M0014', 'assets/images/men/m0014b.jpg'),
(77, 'M0014', 'assets/images/men/m0014c.jpg'),
(78, 'M0015', 'assets/images/men/m0015a.jpg'),
(79, 'M0015', 'assets/images/men/m0015b.jpg'),
(80, 'M0015', 'assets/images/men/m0015c.jpg'),
(81, 'W002', 'assets/images/women/w002a.jpg'),
(82, 'W002', 'assets/images/women/w002b.jpg'),
(83, 'W002', 'assets/images/women/w002c.jpg'),
(84, 'W003', 'assets/images/women/w003a.jpg'),
(85, 'W003', 'assets/images/women/w003b.jpg'),
(86, 'W003', 'assets/images/women/w003c.jpg'),
(87, 'W004', 'assets/images/women/w004a.jpg'),
(88, 'W004', 'assets/images/women/w004b.jpg'),
(89, 'W004', 'assets/images/women/w004c.jpg'),
(90, 'W005', 'assets/images/women/w005a.jpg'),
(91, 'W005', 'assets/images/women/w005b.jpg'),
(92, 'W005', 'assets/images/women/w005c.jpg'),
(93, 'W006', 'assets/images/women/w006a.jpg'),
(94, 'W006', 'assets/images/women/w006b.jpg'),
(95, 'W006', 'assets/images/women/w006c.jpg'),
(96, 'W007', 'assets/images/women/w007a.jpg'),
(97, 'W007', 'assets/images/women/w007b.jpg'),
(98, 'W007', 'assets/images/women/w007c.jpg'),
(99, 'W008', 'assets/images/women/w008a.jpg'),
(100, 'W008', 'assets/images/women/w008b.jpg'),
(101, 'W008', 'assets/images/women/w008c.jpg'),
(102, 'w009', 'assets/images/women/w009a.jpg'),
(103, 'w009', 'assets/images/women/w009b.jpg'),
(104, 'w009', 'assets/images/women/w009c.jpg'),
(105, 'W010', 'assets/images/women/w011a.jpg'),
(106, 'W010', 'assets/images/women/w011b.jpg'),
(107, 'W010', 'assets/images/women/w011c.jpg'),
(108, 'W012', 'assets/images/women/w013a.jpg'),
(109, 'W012', 'assets/images/women/w013b.jpg'),
(110, 'W012', 'assets/images/women/w013c.jpg'),
(111, 'W014', 'assets/images/women/w014a.jpg'),
(112, 'W014', 'assets/images/women/w014b.jpg'),
(113, 'W014', 'assets/images/women/w014c.jpg'),
(117, 'K001', 'assets/images/kids/k001a.jpg'),
(118, 'K001', 'assets/images/kids/k001b.jpg'),
(119, 'K001', 'assets/images/kids/k001c.jpg'),
(120, 'K002', 'assets/images/kids/k002a.jpg'),
(121, 'K002', 'assets/images/kids/k002b.jpg'),
(122, 'K002', 'assets/images/kids/k002c.jpg'),
(123, 'K003', 'assets/images/kids/k003a.jpg'),
(124, 'K003', 'assets/images/kids/k003b.jpg'),
(125, 'K003', 'assets/images/kids/k003c.jpg'),
(126, 'K004', 'assets/images/kids/k004a.jpg'),
(127, 'K004', 'assets/images/kids/k004b.jpg'),
(128, 'K004', 'assets/images/kids/k004c.jpg'),
(129, 'K005', 'assets/images/kids/k005a.jpg'),
(130, 'K005', 'assets/images/kids/k005b.jpg'),
(131, 'K005', 'assets/images/kids/k005c.jpg'),
(132, 'K006', 'assets/images/kids/k006a.jpg'),
(133, 'K006', 'assets/images/kids/k006b.jpg'),
(134, 'K006', 'assets/images/kids/k006c.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL,
  `product_code` varchar(20) DEFAULT NULL,
  `size` varchar(10) NOT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `product_code`, `size`, `stock`) VALUES
(22, 'M001', 'M', 18),
(23, 'M001', 'XL', 5),
(24, 'M001', 'XS', 5),
(25, 'W001', 'M', 18),
(26, 'W001', 'XL', 5),
(27, 'W001', 'XS', 5),
(29, 'M002', 'S', 10),
(30, 'M002', 'M', 15),
(31, 'M002', 'L', 20),
(32, 'M001', 'L', 10),
(33, 'M003', 'M', 12),
(34, 'M003', 'L', 10),
(35, 'M003', 'XL', 6),
(36, 'M004', 'S', 8),
(37, 'M004', 'M', 14),
(38, 'M004', 'L', 7),
(39, 'M005', 'M', 20),
(40, 'M005', 'L', 10),
(41, 'M005', 'XL', 5),
(42, 'M006', 'S', 12),
(43, 'M006', 'M', 20),
(44, 'M006', 'L', 10),
(45, 'M007', 'M', 15),
(46, 'M007', 'L', 8),
(47, 'M007', 'XL', 3),
(48, 'M008', 'M', 10),
(49, 'M008', 'L', 5),
(50, 'M008', 'XL', 2),
(51, 'M009', 'S', 10),
(52, 'M009', 'M', 12),
(53, 'M009', 'L', 8),
(54, 'M0010', 'S', 9),
(55, 'M0010', 'M', 11),
(56, 'M0010', 'L', 10),
(57, 'm0011', 'M', 15),
(58, 'm0011', 'L', 5),
(59, 'm0011', 'XL', 4),
(60, 'M0012', 'M', 12),
(61, 'M0012', 'L', 8),
(62, 'M0012', 'XL', 6),
(63, 'M0013', 'M', 10),
(64, 'M0013', 'L', 12),
(65, 'M0013', 'XL', 8),
(66, 'M0014', 'S', 10),
(67, 'M0014', 'M', 10),
(68, 'M0014', 'L', 10),
(69, 'M0015', 'M', 5),
(70, 'M0015', 'L', 5),
(71, 'M0015', 'XL', 5),
(72, 'W002', 'S', 10),
(73, 'W002', 'M', 15),
(74, 'W002', 'L', 8),
(75, 'W003', 'M', 12),
(76, 'W003', 'L', 10),
(77, 'W003', 'XL', 5),
(78, 'W004', 'S', 8),
(79, 'W004', 'M', 14),
(80, 'W004', 'L', 7),
(81, 'W005', 'S', 12),
(82, 'W005', 'M', 20),
(83, 'W005', 'L', 10),
(84, 'W006', 'M', 15),
(85, 'W006', 'L', 8),
(86, 'W006', 'XL', 3),
(87, 'W007', 'M', 10),
(88, 'W007', 'L', 5),
(89, 'W007', 'XL', 2),
(90, 'W008', 'M', 10),
(91, 'W008', 'L', 5),
(92, 'W008', 'XL', 2),
(93, 'w009', 'S', 10),
(94, 'w009', 'M', 12),
(95, 'w009', 'L', 8),
(96, 'W010', 'S', 9),
(97, 'W010', 'M', 11),
(98, 'W010', 'L', 10),
(99, 'W012', 'M', 12),
(100, 'W012', 'L', 8),
(101, 'W012', 'XL', 6),
(102, 'W014', 'S', 10),
(103, 'W014', 'M', 10),
(104, 'W014', 'L', 10),
(107, 'K001', 'XL', 12),
(108, 'K001', 'M', 14),
(109, 'K001', 'XS', 13),
(110, 'K002', 'S', 10),
(111, 'K002', 'M', 15),
(112, 'K002', 'L', 20),
(113, 'K003', 'M', 12),
(114, 'K003', 'L', 10),
(115, 'K003', 'XL', 6),
(116, 'K004', 'S', 8),
(117, 'K004', 'M', 14),
(118, 'K004', 'L', 7),
(119, 'K005', 'M', 20),
(120, 'K005', 'L', 10),
(121, 'K005', 'XL', 5),
(122, 'K006', 'M', 20),
(123, 'K006', 'L', 10),
(124, 'K006', 'XL', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `surname`) VALUES
(3, 'user@gmail.com', '$2y$10$i3ge/91ZrvIM5ExBujbq..HIxKVr/KKNNJcXxiSOg52lGSkRKL2l.', 'user', '1'),
(4, 'admin@gmail.com', '$2y$10$X.wvA/Gq/GAXXwo43xt6GORx9o3M/ectSeKe5.ZPEqwUcbb.MOuFO', 'admin', 'admin1'),
(5, 'lewispogi@gmail.com', '$2y$10$ElxchL8grDmYegjUGpNWiOzA2VgtKmr.VLJkYB3O9rB9ZOpJyifMO', 'Paul', 'Lewis');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city_municipality` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `user_id`, `country`, `region`, `province`, `city_municipality`, `barangay`, `street`, `contact_number`, `created_at`) VALUES
(1, 3, 'Philippines', 'NCR', 'Metro Manila ', 'Quezon City', 'Old Balara', '19', '11111111', '2025-07-08 20:21:35');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_item` (`user_id`,`product_code`,`size`),
  ADD KEY `product_code` (`product_code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_user_info_order` (`user_info_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_code` (`product_code`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_code`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_code` (`product_code`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_code` (`product_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_code` (`product_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_code`) REFERENCES `products` (`product_code`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user_info_order` FOREIGN KEY (`user_info_id`) REFERENCES `user_info` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_code`) REFERENCES `products` (`product_code`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_code`) REFERENCES `products` (`product_code`) ON DELETE CASCADE;

--
-- Constraints for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_code`) REFERENCES `products` (`product_code`) ON DELETE CASCADE;

--
-- Constraints for table `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `user_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_code`) REFERENCES `products` (`product_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
