-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2024 at 05:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cashtrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `log_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `account` int(11) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `description` varchar(300) NOT NULL,
  `balance_before` decimal(10,0) NOT NULL,
  `balance_after` decimal(10,0) NOT NULL,
  `log_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`log_id`, `username`, `type`, `account`, `amount`, `description`, `balance_before`, `balance_after`, `log_date`) VALUES
(1, 'bandana', 1, 1, 6000, 'my money', 0, 6000, '2024-09-01 09:04:08'),
(2, 'bandana', 1, 1, 2000, 'add money', 0, 2000, '2024-09-01 09:14:28'),
(3, 'bandana', 1, 1, 6000, 'last money', 2000, 8000, '2024-09-01 09:14:56'),
(4, 'bandana', 1, 2, 4000, 'debit money', 8000, 12000, '2024-09-01 09:15:34'),
(5, 'bandana', 1, 3, 1000, 'leko money', 12000, 12000, '2024-09-01 09:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(300) NOT NULL,
  `hash_pwd` varchar(255) NOT NULL,
  `currency_default` decimal(10,0) NOT NULL,
  `credit_bal` decimal(10,0) NOT NULL,
  `debit_bal` decimal(10,0) NOT NULL,
  `cash_bal` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `hash_pwd`, `currency_default`, `credit_bal`, `debit_bal`, `cash_bal`) VALUES
('bandana', '$2y$10$FQTv47LwCGMCdNwhKzM0NOhShPs908oMnmjb1VAb2Af6yQxAaBDVK', 1, 1000, 4000, 8000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
