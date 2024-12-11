-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 03:30 PM
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

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `room_id`, `user_id`, `booking_start`, `booking_end`, `created_at`) VALUES
(8, 6, 3, '2024-12-20 04:20:00', '2024-12-20 05:18:00', '2024-12-10 01:18:29'),
(9, 4, 1, '2024-12-10 04:39:33', '2024-12-10 05:39:33', '2024-12-10 01:39:33'),
(11, 6, 3, '2024-12-12 04:39:33', '2024-12-12 07:39:33', '2024-12-10 01:39:33'),
(12, 7, 1, '2024-12-13 04:39:33', '2024-12-13 08:39:33', '2024-12-10 01:39:33'),
(13, 8, 2, '2024-12-14 04:39:33', '2024-12-14 05:39:33', '2024-12-10 01:39:33'),
(14, 9, 3, '2024-12-15 04:39:33', '2024-12-15 06:39:33', '2024-12-10 01:39:33'),
(15, 4, 1, '2024-12-16 04:39:33', '2024-12-16 07:39:33', '2024-12-10 01:39:33'),
(17, 6, 3, '2024-12-18 04:39:33', '2024-12-18 06:39:33', '2024-12-10 01:39:33'),
(18, 7, 1, '2024-12-19 04:39:33', '2024-12-19 05:39:33', '2024-12-10 01:39:33'),
(19, 6, 2, '2024-12-12 06:39:00', '2024-12-12 08:40:00', '2024-12-10 01:40:05'),
(20, 5, 2, '2024-12-19 05:42:00', '2024-12-19 06:42:00', '2024-12-10 01:42:18');

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
(2, 6, 2, 'Perfect for meetings!', NULL, '2024-12-10 01:30:24', '2024-12-10 01:30:24'),
(3, 4, 2, 'Great!\r\n', NULL, '2024-12-10 01:44:05', '2024-12-10 01:44:05'),
(4, 5, 3, 'Very large and cozy great for seminars!\r\n', NULL, '2024-12-10 02:02:25', '2024-12-10 02:02:25');

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

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `equipment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `capacity`, `description`, `equipment`, `created_at`, `updated_at`) VALUES
(4, '056', 20, 'A small conference room', 'Projector, Whiteboard', '2024-12-09 16:11:08', '2024-12-10 14:01:03'),
(5, '2088', 50, 'A large lecture hall', 'Microphone, Projector, Speaker System', '2024-12-09 16:11:08', '2024-12-10 14:01:38'),
(6, '1064', 25, 'A medium-sized conference room', 'Projector, Whiteboard', '2024-12-10 00:08:33', '2024-12-10 14:01:53'),
(7, '2033', 15, 'A small meeting room', 'TV Screen, Whiteboard', '2024-12-10 00:08:33', '2024-12-10 14:02:10'),
(8, '1051', 50, 'A large lecture hall with modern amenities', 'Projector, Microphones, Speakers', '2024-12-10 00:08:33', '2024-12-10 14:02:29'),
(9, '075', 10, 'A cozy workspace for brainstorming', 'Whiteboard, Coffee Machine', '2024-12-10 00:08:33', '2024-12-10 14:02:46'),
(10, '1045', 30, 'A standard classroom with good lighting', 'Projector, Desks', '2024-12-10 00:08:33', '2024-12-10 14:03:06'),
(14, '2064', 20, 'Conference Room', 'Projector, Whiteboard', '2024-12-10 00:52:07', '2024-12-10 14:03:20');

-- --------------------------------------------------------

--
-- Table structure for table `room_schedules`
--

CREATE TABLE `room_schedules` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `slot_start` time NOT NULL,
  `slot_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`id`, `room_id`, `slot_start`, `slot_end`) VALUES
(7, 4, '09:00:00', '10:00:00'),
(8, 5, '10:00:00', '11:00:00'),
(9, 6, '11:00:00', '12:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `user_type` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile_picture`, `user_type`) VALUES
(1, 'osama', '20162289@uob.edu.bh', '$2y$10$49VpVevzlaj9p7mfRYMtj.E5rF09LIFO6SExwZtOT8t7Jp7XgheRi', NULL, 'user'),
(2, 'Alaa Alshehabi', '202100334@stu.uob.edu.bh', '$2y$10$o6CQUVOGmgcP/hshxemjpeidgENicmJLlORdW6GFYhvJoOANVVK8K', NULL, 'admin'),
(3, 'Hussain Salah', '202103399@stu.uob.edu.bh', '$2y$10$lauNshxvknRQgKZZelLW/.IrRPxPFX5oZWPAvOauhYu3Iwz2NoPOK', NULL, 'user'),
(4, 'gggg', '202009109@stu.uob.edu.bh', '$2y$10$dzS0NBOMZ5vaM0YIBhunG.5/XBKkekr4ugiPOS/1czNvMi8ji.juu', NULL, 'user'),
(5, 'Fatima Abdulla', '202104367@stu.uob.edu.bh', '$2y$10$VzniJbibrExihold1ZJIVufh1UMa0mnZTy4AbBV9NSHZ0NFC1Ztx.', NULL, 'user'),
(6, 'reem rashed', 'reem22@gmail.com', '$2y$10$ohitH.zZjSxw/pFmXYONsOpSxAXb.q0zatZwe3dpjKYDFT7mIq2Dq', NULL, 'user'),
(8, 'ali ahmed', '20200888@stu.uob.edu.bh', '$2y$10$rHNct0AWpC0yYC2LeKeX.OkUIRd9aUqIqc/wSYt3ypFkrIHZzo5.y', NULL, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking` (`room_id`,`booking_start`,`booking_end`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_name` (`room_name`);

--
-- Indexes for table `room_schedules`
--
ALTER TABLE `room_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `room_schedules`
--
ALTER TABLE `room_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_schedules`
--
ALTER TABLE `room_schedules`
  ADD CONSTRAINT `room_schedules_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD CONSTRAINT `time_slots_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
