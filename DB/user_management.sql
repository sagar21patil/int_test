-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2019 at 12:05 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `modified_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `modified_date`, `is_deleted`) VALUES
(1, 'Symfony Product Team', 'This groups created for Symfony Product Team.', '2019-09-10 12:22:34', 0),
(2, 'Magento Product Team', 'This groups created for Magento Product Team.', '2019-09-10 12:23:32', 0),
(3, 'Social Media Team', 'This groups created for Social Media Team.', '2019-09-10 12:24:19', 0),
(4, 'HR Team', 'This groups created for HR Team.', '2019-09-10 12:24:40', 0),
(5, 'PHP Team', 'This groups created for PHP Developers Team.', '2019-09-10 12:25:15', 0),
(6, 'Sales Team', 'This groups created for SalesTeam.', '2019-09-10 12:26:44', 1),
(7, 'Operation Team', 'This groups created for Operation Team.', '2019-09-10 12:26:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `gender` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `status` int(10) NOT NULL,
  `user_type` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `modified_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `gender`, `email`, `password`, `designation`, `status`, `user_type`, `is_deleted`, `modified_date`) VALUES
(1, 'John', 'Male', 'john@example.com', 'e10adc3949ba59abbe56e057f20f883e', 'Magento Developer', 1, 0, 0, '2019-09-10 12:41:08'),
(2, 'Celia', 'Female', 'ross@example.com', 'e10adc3949ba59abbe56e057f20f883e', 'Symfony developer', 0, 0, 0, '2019-09-10 12:29:21'),
(3, 'munira', 'Not Disclose', 'munia@example.com', 'e10adc3949ba59abbe56e057f20f883e', 'Full Stack Developer', 1, 1, 0, '2019-09-10 12:45:54'),
(4, 'vickey', 'Male', 'vickey@example.com', 'e10adc3949ba59abbe56e057f20f883e', 'Marketing', 0, 0, 0, '2019-09-10 12:36:28'),
(5, 'mike', 'Male', 'mike@example.com', 'e10adc3949ba59abbe56e057f20f883e', 'Social Media Developer', 1, 0, 1, '2019-09-10 12:41:01'),
(6, 'sagar', 'Male', 'sagar@example.com', 'e10adc3949ba59abbe56e057f20f883e', 'PHP Senior Developer', 1, 1, 0, '2019-09-10 12:41:15'),
(7, 'admin', 'Not Disclose', 'internations@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'IT Company', 1, 1, 0, '2019-09-10 12:45:38'),
(8, 'mona', 'Female', 'mona@example.com', 'e10adc3949ba59abbe56e057f20f883e', 'Human resource', 0, 1, 0, '2019-09-10 12:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(10) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `modified_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`, `is_deleted`, `modified_date`) VALUES
(1, 1, 2, 0, '2019-09-10 12:28:19'),
(2, 2, 1, 0, '2019-09-10 12:29:21'),
(3, 3, 1, 0, '2019-09-10 12:30:29'),
(4, 3, 2, 0, '2019-09-10 12:30:29'),
(5, 3, 3, 1, '2019-09-10 12:30:34'),
(6, 4, 3, 0, '2019-09-10 12:36:28'),
(7, 5, 3, 1, '2019-09-10 12:41:01'),
(8, 6, 5, 0, '2019-09-10 12:39:00'),
(9, 7, 1, 0, '2019-09-10 12:39:41'),
(10, 7, 2, 0, '2019-09-10 12:39:41'),
(11, 7, 3, 1, '2019-09-10 12:41:57'),
(12, 7, 4, 1, '2019-09-10 12:41:57'),
(13, 7, 5, 1, '2019-09-10 12:43:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
