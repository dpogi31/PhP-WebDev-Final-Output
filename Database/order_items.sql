-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 03:21 PM
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
-- Database: `chronosync`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 'AIYISHI Unisex Stainless Watch', 12999.00, 4, 51996.00),
(2, 1, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 2, 84000.00),
(3, 1, 'Omega Seamaster 75th Anniversary', 41000.00, 1, 41000.00),
(4, 2, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 1, 42000.00),
(5, 3, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 2, 84000.00),
(6, 3, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00),
(7, 3, 'Deep Sea Omega Royalty', 36850.00, 1, 36850.00),
(8, 4, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 2, 84000.00),
(9, 5, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 3, 126000.00),
(10, 5, 'Deep Sea Omega Royalty', 36850.00, 1, 36850.00),
(11, 5, 'Apple Watch Series 10 GPS', 16000.00, 1, 16000.00),
(12, 5, 'Black Fashion Quartz Watch', 38000.00, 2, 76000.00),
(13, 6, 'AIYISHI Unisex Stainless Watch', 12999.00, 1, 12999.00),
(14, 6, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 1, 42000.00),
(15, 7, 'Deep Sea Omega Royalty', 36850.00, 1, 36850.00),
(16, 8, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00),
(17, 9, 'Deep Sea Omega Royalty', 36850.00, 1, 36850.00),
(18, 10, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 1, 42000.00),
(19, 11, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 1, 42000.00),
(20, 12, 'Deep Sea Omega Royalty', 36850.00, 4, 147400.00),
(21, 13, 'Apple Watch Series 10 GPS', 16000.00, 2, 32000.00),
(22, 13, 'AIYISHI Unisex Stainless Watch', 12999.00, 2, 25998.00),
(23, 14, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00),
(24, 15, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 4, 168000.00),
(25, 16, 'MVMT Men Blacktop Analogue Chronograph Watch', 56000.00, 1, 56000.00),
(26, 17, 'Xiaomi REDMI Watch 5 Active', 28000.00, 1, 28000.00),
(27, 18, 'Apple Watch Series 10 GPS', 16000.00, 1, 16000.00),
(28, 19, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00),
(29, 20, 'Apple Watch Ultra', 68000.00, 1, 68000.00),
(30, 20, 'Deep Sea Omega Royalty', 36850.00, 1, 36850.00),
(31, 21, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00),
(32, 22, 'Apple Watch Ultra', 68000.00, 1, 68000.00),
(33, 23, 'Apple Watch Ultra', 68000.00, 1, 68000.00),
(34, 23, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 1, 42000.00),
(35, 24, 'Xiaomi REDMI Watch 5 Active', 28000.00, 1, 28000.00),
(36, 24, 'Carlington Men Stainless Steel Watch', 47000.00, 1, 47000.00),
(37, 25, 'Black Fashion Quartz Watch', 38000.00, 4, 152000.00),
(38, 25, 'Deep Sea Omega Royalty', 36850.00, 1, 36850.00),
(39, 26, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 1, 42000.00),
(40, 27, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 1, 42000.00),
(41, 28, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 1, 42000.00),
(42, 29, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00),
(43, 30, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00),
(44, 31, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00),
(57, 41, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00),
(58, 42, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00),
(59, 43, 'AIYISHI Unisex Stainless Watch', 12999.00, 1, 12999.00),
(60, 44, 'Apple Watch Ultra', 68000.00, 1, 68000.00),
(61, 45, 'Fossil Men\'s Stainless Steel Chronograph Watch', 37000.00, 1, 37000.00),
(62, 46, 'Men Blacktop Chronograph Watch', 56000.00, 1, 56000.00),
(63, 47, 'Apple Watch Series 10 GPS', 16000.00, 1, 16000.00),
(64, 48, 'Samsung Galaxy Watch FE', 43000.00, 1, 43000.00),
(65, 49, 'Apple Watch Series 10 GPS', 16000.00, 1, 16000.00),
(66, 49, 'Samsung Galaxy Watch FE', 43000.00, 1, 43000.00),
(67, 50, 'Deep Sea Omega Royalty', 36850.00, 1, 36850.00),
(68, 51, 'OLEVS Mens Chronograph Watch', 45000.00, 1, 45000.00),
(69, 52, 'HUAWEI WATCH FIT 3 - Smart Watch', 39000.00, 1, 39000.00),
(70, 52, 'Black Fashion Quartz Watch', 38000.00, 1, 38000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
