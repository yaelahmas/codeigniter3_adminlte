-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2024 at 07:01 PM
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
-- Database: `codeigniter3_adminlte`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `nama_menu` varchar(128) NOT NULL,
  `icon` varchar(64) DEFAULT NULL,
  `url` varchar(128) NOT NULL,
  `urut` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `parent_id`, `module_id`, `nama_menu`, `icon`, `url`, `urut`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Dashboard', 'fa fa-dashboard', 'dashboard', 0, '2024-12-26 17:04:13', '2024-12-26 17:32:14'),
(2, NULL, NULL, 'User', 'fa fa-user', 'user', 0, '2024-12-26 17:04:13', '2024-12-26 17:34:36'),
(3, NULL, NULL, 'Role', 'fa fa-tasks', 'role', 0, '2024-12-26 17:04:13', '2024-12-26 17:35:20'),
(4, NULL, NULL, 'Module', 'fa fa-list-alt', 'module', 0, '2024-12-26 17:04:13', '2024-12-26 17:33:50'),
(5, NULL, NULL, 'Profile', 'fa fa-user-o', 'profile', 0, '2024-12-26 17:04:13', '2024-12-26 17:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `module_id` int(11) NOT NULL,
  `nama_module` varchar(128) NOT NULL,
  `url_module` varchar(128) NOT NULL,
  `status_module` enum('active','not active','under development') NOT NULL,
  `login` enum('yes','no','restrict') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`module_id`, `nama_module`, `url_module`, `status_module`, `login`, `created_at`, `updated_at`) VALUES
(1, 'Login', 'login', 'active', 'restrict', '2024-12-26 16:09:01', '2024-12-26 16:09:01'),
(2, 'Register', 'register', 'active', 'restrict', '2024-12-26 16:09:01', '2024-12-26 16:09:01'),
(3, 'Forgot Password', 'forgot-password', 'active', 'restrict', '2024-12-26 16:09:01', '2024-12-26 16:09:01'),
(4, 'Reset Password', 'reset-password', 'active', 'restrict', '2024-12-26 16:09:01', '2024-12-26 16:09:01'),
(5, 'Resend Verification', 'resend-verification', 'active', 'restrict', '2024-12-26 16:09:01', '2024-12-26 16:09:01'),
(6, 'Verify', 'verify', 'active', 'restrict', '2024-12-26 16:09:01', '2024-12-26 16:09:01'),
(7, 'Dashboard', 'dashboard', 'active', 'yes', '2024-12-26 16:09:01', '2024-12-26 16:09:01'),
(8, 'Profile', 'profile', 'active', 'yes', '2024-12-26 16:09:01', '2024-12-26 16:09:01'),
(9, 'User', 'user', 'active', 'yes', '2024-12-26 16:09:01', '2024-12-26 16:49:10'),
(10, 'Module', 'module', 'active', 'yes', '2024-12-26 16:09:01', '2024-12-26 16:49:10'),
(11, 'Role', 'role', 'active', 'yes', '2024-12-26 16:09:01', '2024-12-26 16:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role` enum('Administrator','User','Web Developer') NOT NULL DEFAULT 'User',
  `module_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role`, `module_id`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 7, '2024-12-26 16:41:18', '2024-12-26 16:41:35'),
(2, 'User', 8, '2024-12-26 16:41:18', '2024-12-26 16:54:21'),
(3, 'Web Developer', 7, '2024-12-26 16:41:18', '2024-12-26 16:41:35');

-- --------------------------------------------------------

--
-- Table structure for table `role_access`
--

CREATE TABLE `role_access` (
  `role_id` int(11) NOT NULL,
  `access` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_access`
--

INSERT INTO `role_access` (`role_id`, `access`, `created_at`, `updated_at`) VALUES
(1, '[{\"module\":\"dashboard\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"profile\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"user\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"module\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"role\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0}]', '2024-12-26 16:50:50', '2024-12-26 16:51:09'),
(2, '[{\"module\":\"dashboard\",\"access\":0,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"profile\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"user\",\"access\":0,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"module\",\"access\":0,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"role\",\"access\":0,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0}]', '2024-12-26 16:54:02', '2024-12-26 17:06:59'),
(3, '[{\"module\":\"dashboard\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"profile\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"user\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"module\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0},{\"module\":\"role\",\"access\":1,\"create_records\":0,\"read_records\":0,\"update_records\":0,\"delete_records\":0}]', '2024-12-26 17:31:14', '2024-12-26 17:31:18');

-- --------------------------------------------------------

--
-- Table structure for table `role_menu`
--

CREATE TABLE `role_menu` (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_menu`
--

INSERT INTO `role_menu` (`role_id`, `menu_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `username` varchar(64) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `image` varchar(128) NOT NULL DEFAULT 'default.png',
  `status` enum('active','not active') NOT NULL DEFAULT 'active',
  `verified` tinyint(4) NOT NULL DEFAULT 0,
  `last_login` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `name`, `username`, `email`, `password`, `image`, `status`, `verified`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 'OKX 2024 001', 'okx2024001', 'okx2024001@gmail.com', '$2y$10$xP5/wUOjcBT2.g.YghY4Ru5OQPsf1dRqaqXBgGQJJrPru5d1gWhja', 'default.png', 'active', 1, 1735235732, '2024-12-26 16:33:25', '2024-12-26 17:55:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_has_role`
--

CREATE TABLE `user_has_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_has_role`
--

INSERT INTO `user_has_role` (`user_id`, `role_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_token`
--

CREATE TABLE `user_token` (
  `user_id` int(11) NOT NULL,
  `action` enum('verification_email','reset_password') NOT NULL,
  `selector` varchar(128) NOT NULL,
  `code` varchar(128) NOT NULL,
  `expired_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `module_id` (`module_id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_access`
--
ALTER TABLE `role_access`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_menu`
--
ALTER TABLE `role_menu`
  ADD PRIMARY KEY (`role_id`,`menu_id`),
  ADD KEY `role_id` (`role_id`,`menu_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `user_token`
--
ALTER TABLE `user_token`
  ADD PRIMARY KEY (`selector`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
