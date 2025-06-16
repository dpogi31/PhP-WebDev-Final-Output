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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`, `image_path`) VALUES
(1, 'Apple Watch Series 10 GPS', 16000.00, 'P1.png', 'Stay connected and active with the Apple Watch Series 10. Featuring GPS tracking, fitness monitoring, and a sleek modern design perfect for everyday wear.', 'assets/images/P1.png'),
(2, 'AIYISHI Unisex Stainless Watch', 12999.00, 'P2.png', 'A stylish timepiece for both men and women, crafted with durable stainless steel for a sleek, polished finish and everyday elegance.', 'assets/images/P2.png'),
(3, 'Apple Watch Series 11 GPS + Cellular', 42000.00, 'P3.png', 'Experience the next level of connectivity with the Series 11, offering both GPS and Cellular capabilities in a stunning, high-resolution display.', 'assets/images/P3.png'),
(4, 'Black Fashion Quartz Watch', 38000.00, 'P4.png', 'Minimalist yet bold, this black quartz watch adds sophistication to any outfit with its clean lines and matte finish.', 'assets/images/P4.png'),
(5, 'Deep Sea Omega Royalty', 36850.00, 'P5.png', 'A luxurious dive watch engineered for underwater adventures, combining precision engineering with timeless Omega design.', 'assets/images/P5.png'),
(6, 'Apple Watch Ultra', 68000.00, 'P6.png', 'Engineered for adventure, the Apple Watch Ultra delivers rugged performance, extended battery life, and advanced tracking features.', 'assets/images/P6.png'),
(7, 'Xiaomi REDMI Watch 5 Active', 28000.00, 'P7.png', 'Designed for fitness enthusiasts, this smart watch tracks your health and workouts while offering a bright display and long-lasting battery.', 'assets/images/P7.png'),
(8, 'COOBOS Luxury LED Luminous Watch', 30000.00, 'P8.png', 'A modern LED watch with luminous features and a bold design—perfect for nightlife or making a statement during the day.', 'assets/images/P8.png'),
(9, 'Samsung Galaxy Watch FE', 43000.00, 'P9.png', 'Samsung’s feature-rich Galaxy Watch FE blends smart functionality with stylish comfort, ideal for managing your day on the go.', 'assets/images/P9.png'),
(10, 'SANDA Official 100% Genuine Mens Watch', 40000.00, 'P10.png', 'Reliable and refined, this genuine SANDA timepiece offers accuracy and style for today’s professional man.', 'assets/images/P10.png'),
(11, 'Titan Karishma Quartz Analog Silver Dial', 52000.00, 'P11.png', 'Classic elegance meets Titan craftsmanship with this silver-dial analog watch, perfect for formal and everyday occasions.', 'assets/images/P11.png'),
(12, 'Carlington Men Stainless Steel Watch', 47000.00, 'P12.png', 'A bold stainless steel watch with a masculine edge, built for durability and a clean, modern aesthetic.', 'assets/images/P12.png'),
(13, 'OLEVS Mens Chronograph Watch', 45000.00, 'P13.png', 'Sophisticated and functional, this chronograph timepiece by OLEVS features multiple dials for added precision and flair.', 'assets/images/P13.png'),
(14, 'Fossil Men\'s Stainless Steel Chronograph Watch', 37000.00, 'P14.png', 'Fossil brings modern masculinity and utility together with this stainless steel chronograph, perfect for everyday wear.', 'assets/images/P14.png'),
(15, 'Omega Seamaster 75th Anniversary', 41000.00, 'P15.png', 'Celebrate 75 years of dive watch excellence with this special edition Omega Seamaster—where heritage meets innovation.', 'assets/images/P15.png'),
(16, 'SRPJ45 Seiko Watch', 58000.00, 'P16.png', 'A powerful blend of Seiko’s legacy and modern style, the SRPJ45 delivers precise timekeeping with timeless appeal.', 'assets/images/P16.png'),
(17, 'Curren 8356 Black Steel Chain Round Dial Men\'s Wrist Watch', 68000.00, 'P17.png', 'A bold black steel chain watch with a clean round dial—Curren’s 8356 is built for strength and minimalist design lovers.', 'assets/images/P17.png'),
(18, 'HUAWEI WATCH FIT 3 - Smart Watch', 39000.00, 'P18.png', 'Track your fitness in style with HUAWEI\'s Watch Fit 3, featuring a lightweight design, vivid display, and all-day health monitoring.', 'assets/images/P18.png'),
(19, 'Timex Expedition North Mechanical Men\'s Watch', 54000.00, 'P19.png', 'Built for the outdoors, this mechanical Timex combines rugged construction with classic adventure-ready design.\n\nBuilt for the outdoors, this mechanical Timex combines rugged construction with classic adventure-ready design.\n\nBuilt for the outdoors, this mechanical Timex combines rugged construction with classic adventure-ready design.', 'assets/images/P19.png'),
(20, 'Men Blacktop Chronograph Watch', 56000.00, 'P20.png', 'Sharp and sleek, the MVMT Blacktop blends bold chronograph functionality with modern minimalist design.', 'assets/images/P20.png'),
(21, 'Tom Brady\'s Exclusive Watch', 4000000.00, 'P21.png', 'An ultra-luxury watch inspired by excellence—crafted with premium materials and limited-edition detailing for champions.', 'assets/images/P21.png'),
(22, 'Astos Millionaire\'s Watch', 1500000.00, 'P22.png', 'A symbol of affluence, the Astos Millionaire’s Watch is encrusted with premium finishes and made for those who demand exclusivity.', 'assets/images/P22.png'),
(23, 'Fintime Sapphire and Silver Watch', 1000000.00, 'P23.png', 'Precision meets luxury in this Fintime watch, featuring a sapphire crystal face and silver body for a sleek, elegant look.', 'assets/images/P23.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
