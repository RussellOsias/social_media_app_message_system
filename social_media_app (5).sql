-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2024 at 10:51 AM
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
-- Database: `social_media_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('djrussellosias@gmail.coma|127.0.0.1', 'i:1;', 1728200966),
('djrussellosias@gmail.coma|127.0.0.1:timer', 'i:1728200966;', 1728200966);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `comment`, `image`, `created_at`, `updated_at`) VALUES
(53, 45, 3, 'Nice post', NULL, '2024-10-05 22:51:04', '2024-10-05 22:51:04'),
(54, 46, 1, 'asd', NULL, '2024-10-05 23:44:27', '2024-10-05 23:44:27');

-- --------------------------------------------------------

--
-- Table structure for table `friend_user`
--

CREATE TABLE `friend_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `initiator_id` bigint(20) UNSIGNED DEFAULT NULL,
  `friend_id` bigint(20) UNSIGNED NOT NULL,
  `initiated_by` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','confirmed') NOT NULL,
  `friendship_type` enum('best_friend','acquaintance','close_friend','friend') DEFAULT 'friend',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `friend_user`
--

INSERT INTO `friend_user` (`id`, `user_id`, `initiator_id`, `friend_id`, `initiated_by`, `status`, `friendship_type`, `created_at`, `updated_at`) VALUES
(92, 2, 2, 1, 2, 'confirmed', 'friend', '2024-10-06 00:37:32', '2024-10-06 00:38:09'),
(93, 1, 2, 2, 2, 'confirmed', 'friend', '2024-10-06 00:37:32', '2024-10-06 00:38:09');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `created_at`, `updated_at`) VALUES
(42, 45, 3, '2024-10-05 22:50:56', '2024-10-05 22:50:56'),
(43, 45, 1, '2024-10-05 22:51:12', '2024-10-05 22:51:12'),
(44, 46, 1, '2024-10-05 23:44:25', '2024-10-05 23:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `content`, `read_at`, `created_at`, `updated_at`) VALUES
(11, 2, 1, 'Hello Kazuha', NULL, '2024-10-06 00:41:40', '2024-10-06 00:41:40'),
(12, 1, 2, 'nice to meet you Gojo Satoru', NULL, '2024-10-06 00:42:33', '2024-10-06 00:42:33'),
(13, 2, 3, 'Hello stranger', NULL, '2024-10-06 00:46:39', '2024-10-06 00:46:39'),
(14, 3, 2, 'hello there', NULL, '2024-10-06 00:48:21', '2024-10-06 00:48:21');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '2024_09_13_125901_create_posts_table', 1),
(4, '2024_09_15_170959_create_comments_table', 1),
(5, '2024_09_15_171016_create_likes_table', 1),
(6, '2024_09_23_142814_add_visibility_to_posts_table', 1),
(7, '2024_09_23_143733_create_friend_user_table', 1),
(8, '2024_09_24_155323_create_notifications_table', 1),
(9, '2024_10_04_120445_create_messages_table', 2),
(12, '2024_10_05_125523_create_messages_table', 3),
(13, '2024_10_06_024027_add_initiator_id_to_friend_user_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('039ce625-95b8-4c4b-8eb3-fe7359a55ebf', 'App\\Notifications\\FriendAcceptedNotification', 'App\\Models\\User', 1, '{\"message\":\"Kazuha is now your friend!\",\"friend_id\":2,\"friend_name\":\"Kazuha\"}', '2024-10-06 00:44:31', '2024-10-06 00:38:09', '2024-10-06 00:44:31'),
('048785c9-ce1a-4cba-85b8-42df30096ad6', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"You have a new message from Kazuha: \'Hello Kazuha\'\",\"sender_id\":2,\"receiver_id\":\"1\",\"message_id\":11,\"created_at\":\"2024-10-06T08:41:40.000000Z\"}', '2024-10-06 00:44:31', '2024-10-06 00:41:40', '2024-10-06 00:44:31'),
('05020ebe-8b2b-48b3-99e9-ec839191276f', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 3, '{\"message\":\"You have a new message from Kazuha: \'Hello stranger\'\",\"sender_id\":2,\"receiver_id\":\"3\",\"message_id\":13,\"created_at\":\"2024-10-06T08:46:39.000000Z\"}', NULL, '2024-10-06 00:46:39', '2024-10-06 00:46:39'),
('063b335c-2c89-4f5d-8fb0-683145a10720', 'App\\Notifications\\FriendRequestNotification', 'App\\Models\\User', 1, '{\"message\":\"Kazuha sent you a friend request.\",\"requester_id\":2,\"requester_name\":\"Kazuha\"}', '2024-10-06 00:44:31', '2024-10-06 00:37:32', '2024-10-06 00:44:31'),
('25a0c046-dff5-4acd-9e7d-79dfc0f8a42b', 'App\\Notifications\\FriendAcceptedNotification', 'App\\Models\\User', 2, '{\"message\":\"Gojo Satoru is now your friend!\",\"friend_id\":1,\"friend_name\":\"Gojo Satoru\"}', '2024-10-06 00:44:30', '2024-10-06 00:38:09', '2024-10-06 00:44:30'),
('e240992d-1a4d-4f7c-a80f-fc2257dd19f1', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 2, '{\"message\":\"You have a new message from Russell: \'hello there\'\",\"sender_id\":3,\"receiver_id\":\"2\",\"message_id\":14,\"created_at\":\"2024-10-06T08:48:21.000000Z\"}', NULL, '2024-10-06 00:48:21', '2024-10-06 00:48:21'),
('e74ea522-35a9-41bf-8f12-d5e5860f9f03', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 2, '{\"message\":\"You have a new message from Gojo Satoru: \'nice to meet you Gojo Satoru\'\",\"sender_id\":1,\"receiver_id\":\"2\",\"message_id\":12,\"created_at\":\"2024-10-06T08:42:33.000000Z\"}', '2024-10-06 00:44:30', '2024-10-06 00:42:33', '2024-10-06 00:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `visibility` enum('Public','Only me','Friends') NOT NULL DEFAULT 'Public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `image`, `likes_count`, `created_at`, `updated_at`, `visibility`) VALUES
(45, 1, 'Sample post', NULL, 2, '2024-10-05 22:50:53', '2024-10-05 22:51:12', 'Public'),
(46, 1, 'asdas', NULL, 1, '2024-10-05 23:42:27', '2024-10-05 23:44:25', 'Public'),
(47, 1, '123', NULL, 0, '2024-10-05 23:44:36', '2024-10-05 23:44:36', 'Public'),
(48, 1, 'aasd', NULL, 0, '2024-10-05 23:44:48', '2024-10-05 23:44:48', 'Public'),
(49, 2, 'asdasdadasda', NULL, 0, '2024-10-05 23:53:55', '2024-10-05 23:53:55', 'Public');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('dLkV8VZaFBy0D48iDWl99ti5oKq4lpD8nWrcCBQs', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36 OPR/113.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRXN1TlN0SG55N2hjUHlKRUdKNlQybXRzNmtrZmVtWmgyQjJNWlRFOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZXNzYWdlcy9tZXNzYWdlcy9jaGF0LzIiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1728204507),
('zpFM6u6qLEpCkzngJ78f2TheRd22SKAvQG1CJNcx', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36 OPR/113.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTk44ZDhCTVVoWHlNQ2xIMWt1VENmdjhQdnpMMXJOTnVhQ2s3Wjd5QSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tZXNzYWdlcy9jaGF0LzMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1728204399);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `birthday`, `occupation`, `gender`, `address`, `nationality`) VALUES
(1, 'Gojo Satoru', 'osiasrussell@gmail.com', NULL, '$2y$12$w2kxooQWPdarQbKqy/NcXu1kAJHj8SVRIijx8LCbasYREnIKGL6Nm', 't5Tfxr2qt2ZmGVMwIDzrMo4H4dxk0jGnWx4k71kLTXAuZCjz3PGPg5ft2HbE', '2024-10-03 03:42:44', '2024-10-05 23:24:30', '2003-10-17', 'Working Students', 'male', 'Balite Lagao', 'Filipino'),
(2, 'Kazuha', 'djrussellosias@gmail.com', NULL, '$2y$12$pM6X.vQN6BMI/uC1MWoFruHJVr362T38QXC9c8KY.LPZJ8DRNgz5.', NULL, '2024-10-03 03:43:29', '2024-10-05 23:18:37', '2014-06-17', 'Student', 'male', 'Tevyat', 'Filipino'),
(3, 'Russell', '123@gmail.com', NULL, '$2y$12$2tWCZU36zxbbBQ.7g9s.GeN/mkmeDrctiN1f49cEW05AsegHPE4Y6', NULL, '2024-10-03 04:23:04', '2024-10-03 04:23:04', NULL, NULL, NULL, NULL, NULL),
(4, 'admin123', 'admin@gmail.com', NULL, '$2y$12$TEqyvorgK.mxaCMUwZF0MOUNPRIiZFSbG4uoi4ySD7Ucnl5Ggmtsu', NULL, '2024-10-05 10:04:44', '2024-10-05 10:04:44', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_post_id_foreign` (`post_id`),
  ADD KEY `comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `friend_user`
--
ALTER TABLE `friend_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_friendship` (`user_id`,`friend_id`),
  ADD UNIQUE KEY `unique_user_friend` (`user_id`,`friend_id`),
  ADD KEY `friend_user_ibfk_2` (`friend_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `likes_post_id_foreign` (`post_id`),
  ADD KEY `likes_user_id_foreign` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `friend_user`
--
ALTER TABLE `friend_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `friend_user`
--
ALTER TABLE `friend_user`
  ADD CONSTRAINT `friend_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `friend_user_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
