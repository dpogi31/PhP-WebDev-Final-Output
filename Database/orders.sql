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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `shipping_address` text DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `full_name`, `order_date`, `total_amount`, `payment_method`, `shipping_address`, `billing_address`, `phone`) VALUES
(1, NULL, NULL, '2025-06-09 20:50:32', 176996.00, '', NULL, NULL, NULL),
(2, NULL, NULL, '2025-06-09 21:38:42', 42000.00, '', NULL, NULL, NULL),
(3, NULL, NULL, '2025-06-09 21:56:06', 158850.00, '', NULL, NULL, NULL),
(4, NULL, NULL, '2025-06-09 22:03:25', 84000.00, '', NULL, NULL, NULL),
(5, NULL, NULL, '2025-06-09 22:10:05', 254850.00, '', NULL, NULL, NULL),
(6, NULL, NULL, '2025-06-09 22:11:38', 54999.00, '', NULL, NULL, NULL),
(7, NULL, NULL, '2025-06-09 22:12:09', 36850.00, '', NULL, NULL, NULL),
(8, NULL, NULL, '2025-06-09 22:12:41', 38000.00, '', NULL, NULL, NULL),
(9, NULL, NULL, '2025-06-09 22:13:07', 36850.00, '', NULL, NULL, NULL),
(10, NULL, NULL, '2025-06-09 22:21:54', 42000.00, '', NULL, NULL, NULL),
(11, NULL, NULL, '2025-06-09 23:19:39', 42000.00, '', NULL, NULL, NULL),
(12, NULL, NULL, '2025-06-09 23:40:42', 147400.00, '', NULL, NULL, NULL),
(13, NULL, NULL, '2025-06-09 23:41:38', 57998.00, '', NULL, NULL, NULL),
(14, NULL, NULL, '2025-06-10 00:00:09', 38000.00, '', NULL, NULL, NULL),
(15, NULL, NULL, '2025-06-10 00:48:12', 168000.00, '', NULL, NULL, NULL),
(16, NULL, NULL, '2025-06-10 07:59:19', 56000.00, '', NULL, NULL, NULL),
(17, NULL, NULL, '2025-06-10 21:36:10', 28000.00, '', NULL, NULL, NULL),
(18, NULL, NULL, '2025-06-12 09:21:03', 16000.00, '', NULL, NULL, NULL),
(19, NULL, NULL, '2025-06-13 00:55:54', 38000.00, '', NULL, NULL, NULL),
(20, NULL, NULL, '2025-06-14 11:06:41', 104850.00, '', NULL, NULL, NULL),
(21, NULL, NULL, '2025-06-14 11:10:08', 38000.00, '', NULL, NULL, NULL),
(22, NULL, NULL, '2025-06-14 11:17:18', 68000.00, '', NULL, NULL, NULL),
(23, NULL, NULL, '2025-06-14 11:23:57', 110000.00, '', NULL, NULL, NULL),
(24, NULL, NULL, '2025-06-14 11:27:56', 75000.00, '', NULL, NULL, NULL),
(25, NULL, NULL, '2025-06-14 16:34:03', 188850.00, '', NULL, NULL, NULL),
(26, NULL, NULL, '2025-06-14 16:35:48', 42000.00, '', NULL, NULL, NULL),
(27, NULL, NULL, '2025-06-14 17:11:30', 42000.00, '', NULL, NULL, NULL),
(28, NULL, NULL, '2025-06-14 17:12:18', 42000.00, '', NULL, NULL, NULL),
(29, NULL, NULL, '2025-06-14 21:14:12', 38000.00, '', NULL, NULL, NULL),
(30, NULL, NULL, '2025-06-14 21:18:07', 38000.00, '', NULL, NULL, NULL),
(31, NULL, NULL, '2025-06-14 21:20:01', 38000.00, '', NULL, NULL, NULL),
(41, 17, NULL, '2025-06-16 19:36:25', 38000.00, 'GCash', NULL, NULL, NULL),
(42, 17, NULL, '2025-06-16 20:26:35', 38000.00, 'GCash', NULL, NULL, NULL),
(43, 17, NULL, '2025-06-16 20:28:30', 12999.00, 'GCash', NULL, NULL, NULL),
(44, 17, NULL, '2025-06-16 20:29:28', 68000.00, 'GCash', NULL, NULL, NULL),
(45, 17, NULL, '2025-06-16 20:32:17', 37000.00, 'GCash', NULL, NULL, NULL),
(46, 17, NULL, '2025-06-16 14:36:11', 56000.00, 'GCash', NULL, NULL, NULL),
(47, 17, NULL, '2025-06-16 14:39:15', 16000.00, 'GCash', NULL, NULL, NULL),
(48, 17, NULL, '2025-06-16 14:40:12', 43000.00, 'GCash', NULL, NULL, NULL),
(49, 17, 'Guest User', '2025-06-16 14:53:02', 59000.00, 'GCash', 'dwdada', 'dwada', '16811'),
(50, 17, 'Guest User', '2025-06-16 14:55:37', 36850.00, 'GCash', 'dwada', 'dawda', '0890948'),
(51, 17, 'dan', '2025-06-16 14:58:19', 45000.00, 'GCash', 'B.C', 'B.C', '0978135813'),
(52, 17, 'Daniel Nice', '2025-06-16 15:19:00', 77000.00, 'GCash', 'Gibraltar Baguio City', 'Purok 4 east #352', '09087936108');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
