-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 09:43 PM
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
-- Database: `uob_room_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$e.J84pdy8QgZJgr5vIQHUOHNywUoIaPpOfPrrz63EhLgBhTO/r44W', '2024-12-07 18:16:35'),
(2, 'admin2', '$2y$10$XO4rt75NFU3uZKddDeYC0O4W/raq73d6uzp/3ucRLeOKLVed/BI0e', '2024-12-07 18:19:18');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_start` datetime NOT NULL,
  `booking_end` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `room_id`, `user_id`, `comment`, `response`, `created_at`, `updated_at`) VALUES
(0, 1, 1, 'hhhhh', NULL, '2024-12-09 20:17:02', '2024-12-09 20:17:02'),
(1, 4, 1, 'wtfffff', NULL, '2024-12-09 16:37:56', '2024-12-09 16:37:56');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `admin_id`, `comment_id`, `is_read`, `created_at`) VALUES
(1, 1, 1, 0, '2024-12-09 16:37:56'),
(2, 2, 1, 1, '2024-12-09 16:37:56'),
(0, 1, 0, 0, '2024-12-09 20:17:02'),
(0, 2, 0, 0, '2024-12-09 20:17:02');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `floor` enum('Ground','First Floor','Second Floor') NOT NULL,
  `section` enum('IS','CS','CE') NOT NULL,
  `capacity` int(11) NOT NULL,
  `equipment` text NOT NULL,
  `available_timeslots` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `floor`, `section`, `capacity`, `equipment`, `available_timeslots`) VALUES
(1, '059', 'Ground', 'IS', 20, 'Projector, Whiteboard', '08:00-12:00, 14:00-16:00'),
(2, '1046', 'First Floor', 'CS', 40, 'Smart Board, Projector', '09:00-11:00, 13:00-15:00'),
(3, '2088', 'Second Floor', 'CE', 30, 'Workstations, Smart Board', '10:00-12:00, 15:00-17:00'),
(4, '1065', 'First Floor', 'CS', 30, 'Projector, Whiteboard', '08:00-10:00, 13:00-15:00'),
(5, '2083', 'Second Floor', 'CE', 25, 'Smart Board, Workstations', '09:00-11:00, 14:00-16:00'),
(6, '055', 'Ground', 'CS', 40, 'Projector, Smart Board', '08:00-10:00, 11:00-13:00'),
(7, '1070', 'First Floor', 'CE', 35, 'Projector, Smart Board', '10:00-12:00, 15:00-17:00'),
(8, '2033', 'Second Floor', 'IS', 30, 'Workstations, Whiteboard', '09:00-11:00, 14:00-16:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile_picture`) VALUES
(1, 'Alaa', '20210334@stu.uob.edu.bh', '$2y$10$asEF0iTHZawgSrGaR2zYzemg9gSauIteg.xMYsHsiT3AtOMekEykS', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bookings_room_time` (`room_id`,`booking_start`,`booking_end`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`),
  ADD KEY `idx_rooms_id` (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
