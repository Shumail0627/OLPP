-- phpMyAdmin SQL Dump
-- version 5.1.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 08, 2024 at 07:06 AM
-- Server version: 8.0.36-28
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbcxw5qyg5togs`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin', 'shumail@rehanfoundation.com', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `installments`
--

CREATE TABLE `installments` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `paid` tinyint(1) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `status` enum('paid','unpaid') DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `installments`
--

INSERT INTO `installments` (`id`, `user_id`, `amount`, `due_date`, `paid`, `payment_date`, `status`) VALUES
(1, 1, '500.00', '2024-01-01', 1, '2024-01-03', 'unpaid'),
(2, 1, '1000.00', '2024-02-01', 1, '2024-02-03', 'unpaid'),
(3, 1, '1000.00', '2024-03-01', 1, '2024-08-03', 'unpaid'),
(4, 1, '1000.00', '2024-08-07', 1, '2024-08-07', 'unpaid'),
(5, 1, '1000.00', '2024-08-07', 1, '2024-09-06', 'unpaid'),
(6, 1, '1000.00', '2024-08-07', 1, '2024-08-04', 'unpaid'),
(7, 1, '1000.00', '2024-08-10', 1, '2024-08-07', 'unpaid'),
(8, 1, '1000.00', '2024-08-10', 1, '2024-08-07', 'unpaid'),
(9, 7, '1000.00', '2024-08-07', 1, '2024-08-07', 'unpaid');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int NOT NULL,
  `model` varchar(255) NOT NULL,
  `configuration` varchar(255) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `model`, `configuration`, `serial_number`, `quantity`) VALUES
(1, 'Samsung Series 5 Chrome Book', '16GB Storage | 2GB RAM | 11.6 Inch Display | Silver Color | Chromebook', 'XE303C12', 4),
(3, 'ACER', '4/16', 'xyz', 10);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `laptop_model` varchar(255) NOT NULL,
  `laptop_configuration` varchar(255) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `laptop_model`, `laptop_configuration`, `payment_type`, `serial_number`, `status`) VALUES
(1, 8, 'Dell Chrome Book 2/16', 'i5', 'installment', '1569', 'Pending'),
(2, 10, 'Dell Chrome Book 2/16', 'i5', 'installment', '1569', 'Pending'),
(3, 10, 'Dell Chrome Book 2/16', 'i5', 'installment', '1569', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `laptop_model` varchar(255) DEFAULT NULL,
  `laptop_configuration` text,
  `serial_number` varchar(255) DEFAULT NULL,
  `photos` text,
  `cnic` varchar(20) DEFAULT NULL,
  `payment_type` enum('cash','installment') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `laptop_model`, `laptop_configuration`, `serial_number`, `photos`, `cnic`, `payment_type`) VALUES
(1, 'Shumail Mehboob', 'shumailmehboob33@gmail.com', '$2y$10$ZiIbn2pePrJ6KJUs1bco3uwTnlJEdoGDgspC4j7OznVy103djHT6e', '03202579151', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Umair Anwar', 'umair123@gmail.com', '$2y$10$RlOtk5ymtetZ3vHZmjXJDutgV7WqHkcZIQpJLJZ5Rrx1nFbNRH4nC', '03313116191', 'Dell Chrome Book 2/16', 'i5 5th Gen', '090', NULL, NULL, NULL),
(6, 'Huzaifa', 'huzaifa123@gmail.com', '$2y$10$rNAVGdbMRNINKjvJB.6Nh.OLUA8IzDo/sUZGbgz2eoZJEWtkBPGoG', '+9292929292929292', 'HP Chrome Book', 'i5', '1569', NULL, NULL, NULL),
(7, 'Tahir', 'tahir@gmail.com', '$2y$10$QVdmktNadkAioPNCnAZRBONPDA20n6uzS2OUL6GS94tXbX/5yk33u', '+32811321321', 'ACER', '4/16', '123', NULL, NULL, NULL),
(8, 'Mehtaab', 'mehtaab123@gmail.com', '$2y$10$MTgIGjjJLxyY79hM1OkbGehTL83OGIUQ0/YXnSy9JCY3Wiyji01KO', '159753', 'Dell Chrome Book 2/16', 'i5', 'asd', NULL, '3154-321654984-13216', 'cash'),
(9, 'Hamid', 'ah@gmail.com', '$2y$10$YxLWgB2g32PeQGBlqAvuyu32daR1iJ12Ql1mMeiVUPPPkCUY1IRgO', '145498', 'asd', 'fa', 'f', NULL, '465498456', 'cash'),
(10, 'Ahmed', 'ahmed@gmail.com', '$2y$10$cStwExgiY5XE2dkp3Fpb9uhwSmHXKtmTXF.q09Y7ZtIKG8.gF3Mq.', '157', NULL, NULL, NULL, NULL, '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `installments`
--
ALTER TABLE `installments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `installments`
--
ALTER TABLE `installments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `installments`
--
ALTER TABLE `installments`
  ADD CONSTRAINT `installments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
