-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2024 at 07:53 PM
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
-- Database: `cshells`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '123');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `user_id` int(50) NOT NULL,
  `product_id` int(50) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_description` varchar(250) NOT NULL,
  `product_url` varchar(50) NOT NULL,
  `product_price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`user_id`, `product_id`, `product_name`, `product_description`, `product_url`, `product_price`) VALUES
(1, 11, 'Sunglass for men', 'Black/White/Red/Green/Yellow', '1734004268-product (4).jpg', 799),
(1, 15, 'Travelling Bag (Ladies)', 'Pink/White', '1734006723-product (15).jpg', 1390),
(2, 14, 'Travelling Bag', 'Medium/Large (Blcak Only)', '1734004811-product (14).jpg', 995),
(10, 12, 'Unisex Moder Cap', 'Black/Pink/White/Ash', '1734004647-product (12).jpg', 850),
(10, 17, 'Black bag', 'Black', '1734246064-product (14).jpg', 1000);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `address` text NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Delivered','Cancelled') DEFAULT 'Pending',
  `user_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_name`, `address`, `mobile_number`, `total_amount`, `order_date`, `status`, `user_id`) VALUES
(10, 'Dineth Hirusha', '210/A2, Kohombagahawatta, Mawala, Wadduwa.', '0772424521', 250.00, '2024-12-14 17:19:05', 'Delivered', 1),
(14, 'Dineth Hirusha', '210/A2, Kohombagahawatta, Mawala, Wadduwa.', '0772424521', 1640.00, '2024-12-14 17:52:38', 'Cancelled', 1),
(15, 'Janindu Vinsura', 'jdfkk', '1234566778', 1100.00, '2024-12-15 01:20:05', 'Cancelled', 1),
(16, 'Janindu Vinsura', 'jdfkk', '1234566778', 1049.00, '2024-12-15 01:59:52', 'Pending', 1),
(17, 'User5', 'User5', '1111111111', 2699.00, '2024-12-15 02:18:19', 'Cancelled', 5),
(18, 'Janindu', 'No 84/36, Red Cross Houses, Palletota, Godahena\r\nAmbalangoda, Galle.', '0775775625', 1100.00, '2024-12-15 02:19:43', 'Pending', 4),
(19, 'Janindu Vinsura', 'No 84/36, Red Cross Houses, Pallethota,\r\nGodahena, Ambalangoda.', '0775775625', 4650.00, '2024-12-15 03:57:59', 'Pending', 7),
(20, 'Jay De Rukz', 'No 21/1,Kaluwadamulla RD,\r\nKandgoda,Ambalangoda.', '0740017973', 2100.00, '2024-12-15 03:59:53', 'Pending', 6),
(21, 'Saman Kumara', '22/1, Main Rd, Ambalangoda.', '0767788344', 1100.00, '2024-12-15 11:19:42', 'Pending', 8),
(22, 'Kosala Athapaththu', 'Nisala Rest Park,Anuradapura road,Magulagama,Padeniya.', '0742397719', 6900250.00, '2024-12-15 12:02:46', 'Pending', 9),
(23, 'Janindu', 'No 84/36, Red Cross Houses, Palletota, Godahena\r\nAmbalangoda, Galle.', '0775775625', 2448.00, '2024-12-15 12:19:26', 'Pending', 4),
(24, 'dinethvinz', 'Ambalangoda', '0777060776', 15250.00, '2024-12-15 13:05:15', 'Pending', 7),
(25, 'dinethvinz', 'Ambalangoda', '0777060776', 2448.00, '2024-12-15 13:05:36', 'Pending', 7),
(26, 'dinethvinz', 'Ambalangoda', '0777060776', 6900250.00, '2024-12-15 13:07:35', 'Pending', 7),
(27, 'Dineth Hirusha', '210/A2, Kohombagahawatta, Mawala, Wadduwa.', '0772424521', 34515250.00, '2024-12-15 13:14:15', 'Cancelled', 5),
(28, 'thamod', '123/C, galle', '0123456789', 2100.00, '2024-12-15 14:14:05', 'Pending', 11);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock`, `image_url`) VALUES
(21, 'Unisex Moder Cap', 'Black/Pink/White/Ash', 850.00, 15, '1734285238-product (12).jpg'),
(22, 'Travelling Bag (Medium)', 'Black only - Medium Size', 1000.00, 20, '1734285342-product (14).jpg'),
(23, 'Belt For Men', 'Available in 3 sizes', 1075.00, 30, '1734285439-product (64).jpg'),
(24, 'Unisex Moder Ring', 'Large and Medium Sizes', 399.00, 40, '1734285501-product (63).jpg'),
(25, 'Watch For Men', 'Black/ Red/ Pink/ White', 1350.00, 15, '1734285561-product (62).jpg'),
(26, 'Crop Top', 'Green/ Yellow/ Red/ White/ Ash/ Pink/ Blue', 1799.00, 50, '1734285650-product (49).jpg'),
(27, 'Necklace For Girls', 'Pink/ Black/ White', 599.00, 45, '1734285712-product (29).jpg'),
(28, 'iPhone 11pro Back Cover', 'Transparent - Silicone ', 799.00, 30, '1734285834-product (28).jpg'),
(29, 'Halter Top', 'Large/ Medium/ Small', 1790.00, 50, '1734285968-product (40).jpg'),
(30, 'T Shirt', 'XL/ Large/ Medium', 2400.00, 40, '1734286039-product (48).jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(50) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_email`, `user_password`, `firstName`, `lastName`) VALUES
(1, 'user1@email.com', '123', 'aaa', 'aaaa'),
(2, 'user2@email.com', '456', 'bbb', 'bbb'),
(4, 'mail@gmail.com', '123', 'Janindu', 'gdg'),
(5, 'user5@mail.com', '123', 'user5', 'user5'),
(6, 'jayderukz@gmail.com', 'jayderukz123', 'Jay', 'De Rukz'),
(7, 'dinethvinz@gmail.com', 'Janindu2003', 'Janindu', 'Vinsura'),
(8, 'ubetatta@gmail.com', 'uba@1234', 'Saman', 'Kumara'),
(9, 'kosalaathapaththu1234@gmail.com', '1234', 'A.M.Kosala', 'Athapaththu'),
(10, 'sayuni@gmail.com', '123', 'sayuni', 'sathnara'),
(11, 'thamoduthpala@gmail.com', '12345', 'Thamod', 'Vithanage');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`user_id`,`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
