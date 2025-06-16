-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 03:22 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `age` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remember_token` varchar(64) DEFAULT NULL,
  `token_expires_at` datetime DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiration` datetime DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `password`, `gender`, `age`, `created_at`, `remember_token`, `token_expires_at`, `role`, `otp_code`, `otp_expiration`, `google_id`, `address`, `city`, `postal_code`, `country`, `phone`) VALUES
(9, 'admin', '', 'admin@gmail.com', '$2y$12$Z/XIlq2w22yZ3SCPJMV35eU0oB3syBQqaWQuBGaa0dd7i6KCt2WzC', 'male', '17', '2025-06-12 23:36:44', NULL, NULL, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'daniel31', '', 'danielpogi90@gmail.com', '$2y$10$7r8Ru/fQC3nMeJY7hb/seu.68Vx79HGRLdrXrWgaZ9gBa3alcROjK', 'male', '30', '2025-06-14 08:06:33', NULL, NULL, 'user', NULL, NULL, '113607991900409416606', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
