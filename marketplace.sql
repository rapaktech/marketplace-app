-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2021 at 01:43 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marketplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(16) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` varchar(1000) NOT NULL,
  `item_price` int(16) NOT NULL,
  `item_time_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `item_last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `item_creator_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `item_name`, `item_description`, `item_price`, `item_time_added`, `item_last_updated`, `item_creator_email`) VALUES
(3, 'cl', 'uvuzxy', 7, '2021-07-14 17:23:50', '2021-07-14 17:24:37', 'ezesinachijim@gmail.com'),
(6, 'Omo', 'ggvsty', 8, '2021-07-14 17:29:12', '2021-07-14 17:29:12', 'jim@jimezesinachi.com'),
(7, 'd', 'ev', 2147483647, '2021-07-14 17:29:26', '2021-07-14 17:29:26', 'jim@jimezesinachi.com'),
(9, 'gcty', 'gfcs', 6, '2021-07-14 18:59:34', '2021-07-14 18:59:34', 'carl@stone.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(16) UNSIGNED NOT NULL,
  `user_firstname` varchar(255) NOT NULL,
  `user_lastname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_reg_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_firstname`, `user_lastname`, `user_email`, `user_password`, `user_reg_time`, `user_last_updated`, `user_enabled`) VALUES
(1, 'Jim', 'Ezesinachi', 'ezesinachijim@gmail.com', '$2y$10$R4yNxEPrX/Ta638/vSEYguJEuNzoPyqYE0Dvv6y4FAVytSBnrUaoa', '2021-07-14 17:14:41', '2021-07-14 17:14:41', 1),
(2, 'Jim', 'Ezesinachi', 'jim@jimezesinachi.com', '$2y$10$hexchFlaMjh7LFTJcqAP8u1S8A22mKG4lN/ckEmNKQi06IsOEtOiO', '2021-07-14 17:28:45', '2021-07-14 17:28:45', 1),
(3, 'Valid', 'Man', 'carl@stone.com', '$2y$10$0lZZB8WxSvf2msp9Fpjt4u83M1JWa8.LXo/4HKJ0yw2BC/WnTw6G2', '2021-07-14 18:58:57', '2021-07-14 18:58:57', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(16) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(16) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
