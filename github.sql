-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 28, 2017 at 01:26 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `github`
--

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `user_id`, `follower_id`, `created_at`, `updated_at`) VALUES
(6, 4, 6, '2017-10-16 11:02:11', '2017-10-16 11:02:11'),
(17, 5, 8, '2017-10-19 10:56:45', '2017-10-19 10:56:45'),
(30, 3, 5, '2017-10-28 08:13:43', '2017-10-28 08:13:43');

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `image` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `user_id`, `image`, `created_at`, `updated_at`) VALUES
(19, 5, 'BrmfQA1objPaPBdI3HXoDGWCWnvY7ahCQeqmN4OG.jpeg', NULL, NULL),
(21, 5, 'Pw4DxbDxuxQHxskDgqhwuXOGQ0u2t0fE6v23Rg7s.jpeg', NULL, NULL),
(22, 3, 'HUrqVp0NZaWNFJpQRDTtqk6Xs216JgjZ9ScS8Sn9.jpeg', NULL, NULL),
(23, 3, 'cG2kgP1CvqcnzxYyBjyZ4vxfjsN64faunKLNyWJx.jpeg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seen` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `from`, `to`, `message`, `seen`, `created_at`, `updated_at`) VALUES
(119, 5, 3, 'lav', 1, '2017-10-25 10:39:06', '2017-10-25 10:39:09'),
(120, 5, 3, 'hello', 1, '2017-10-25 10:39:48', '2017-10-25 10:39:49'),
(121, 5, 3, 'hahsah', 1, '2017-10-25 11:17:44', '2017-10-25 11:17:46'),
(122, 3, 5, 'my friend', 1, '2017-10-25 11:17:54', '2017-10-25 11:17:55'),
(123, 5, 3, 'yeah', 1, '2017-10-25 11:18:00', '2017-10-25 11:18:01'),
(124, 3, 5, 'yo', 1, '2017-10-25 11:18:11', '2017-10-25 11:18:15'),
(125, 5, 3, 'how are you', 1, '2017-10-25 11:18:20', '2017-10-25 11:18:21'),
(126, 3, 5, 'name', 1, '2017-10-25 11:18:28', '2017-10-25 11:18:30'),
(127, 5, 3, 'firstname', 1, '2017-10-25 11:18:39', '2017-10-25 11:18:41'),
(128, 3, 5, 'lastname', 1, '2017-10-25 11:18:45', '2017-10-25 11:18:50'),
(129, 3, 5, 'power', 1, '2017-10-25 11:18:56', '2017-10-25 11:19:00'),
(130, 5, 3, 'slow', 1, '2017-10-25 11:19:04', '2017-10-25 11:19:06'),
(184, 5, 3, 'new', 1, '2017-10-27 14:13:12', '2017-10-27 14:14:24'),
(185, 5, 3, 'hi', 1, '2017-10-27 14:19:05', '2017-10-27 14:19:14'),
(186, 5, 3, 'boot', 1, '2017-10-27 14:19:53', '2017-10-27 14:20:11'),
(187, 5, 3, 'google', 1, '2017-10-27 14:23:48', '2017-10-27 14:23:56'),
(188, 5, 3, 'volvo', 1, '2017-10-27 14:42:09', '2017-10-27 14:44:11'),
(189, 5, 3, 'ee', 1, '2017-10-27 14:44:19', '2017-10-27 14:44:26'),
(190, 3, 5, 'lav', 1, '2017-10-27 14:44:47', '2017-10-27 14:44:59'),
(191, 5, 3, 'none', 1, '2017-10-27 14:45:35', '2017-10-27 14:45:41'),
(192, 3, 5, 'name', 1, '2017-10-27 14:45:50', '2017-10-27 14:45:59'),
(193, 3, 5, 'baba', 1, '2017-10-27 14:46:47', '2017-10-27 14:46:57'),
(194, 3, 5, 'love', 1, '2017-10-27 14:47:05', '2017-10-27 14:47:12'),
(195, 3, 5, 'annsa', 1, '2017-10-27 15:21:29', '2017-10-27 15:21:38');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2017_10_12_115511_change_users_table', 2),
(3, '2017_10_13_132705_create_laravel_follow_tables', 3),
(4, '2017_10_13_193740_create_notifications_table', 4),
(5, '2017_10_14_080841_create_followers_table', 5),
(6, '2017_10_21_081216_create_galleries_table', 6),
(7, '2017_10_23_115255_create_messages_table', 7),
(8, '2017_10_23_133441_change_messages_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` int(10) UNSIGNED NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_id`, `notifiable_type`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('59990448-9ce0-4ff7-ad47-f772c0f827fa', 'App\\Notifications\\RepliedToFollow', 4, 'App\\User', '{\"repliedTime\":{\"date\":\"2017-10-18 20:00:35.000000\",\"timezone_type\":3,\"timezone\":\"UTC\"},\"follower_id\":3,\"follower_name\":\"Mko\"}', NULL, '2017-10-18 16:00:35', '2017-10-18 16:00:35');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role` int(11) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `email`, `password`, `avatar`, `gender`, `provider`, `api_info`, `provider_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 1, 'MkoMalkhasyan', 'mkrtich.malkhasyan1996@gmail.co', NULL, 'https://avatars0.githubusercontent.com/u/29379333?v=4', 'None', 'github', 'https://api.github.com/users/MkoMalkhasyan/repos', 29379333, '1AUNGycpvblvYqNLbanJYdOaYtLXwoRIRRXfLryyXbinhQuMVMmDFFtdrkJ5', '2017-10-10 06:49:47', '2017-10-10 06:49:47'),
(3, 1, 'Mko', 'mkrtich.malkhasyan1996@gmail.com', '$2y$10$MGJaD7OZFLJsP7mOvMWfzuoZNknBCi13rF1UjGAaJJ0bvvG5ncbNm', 'avatar.jpeg', '0', NULL, NULL, NULL, 'BgMpGrLS14JV062MQzuKYy6cu0FZ1w0M6Tt5kTpw71Z88mDTpytXGluWIBV6', '2017-10-13 06:56:23', '2017-10-23 03:39:19'),
(4, 1, 'Valod', 'valodyan@mail.ru', '$2y$10$P4XZixZMaG2.ra/yPWR3pe1aO39tIonQHMvzC1Wtg4Jj.TRPWyZI6', NULL, '0', NULL, NULL, NULL, 'W1UhFcPqtyGTcURcfW5uD318van6N7SSSmcZCAvnEcb2ZRDKYhnDtEcvQ95I', '2017-10-13 09:43:32', '2017-10-13 09:43:32'),
(5, 1, 'Hakob', 'hakobyan@gmail.com', '$2y$10$05KXhWdLN4uUpkiQd2zeUeeJwo7suqAfnd0R1xnCYCY/dv2gKjEfa', 'avatar.jpeg', '0', NULL, NULL, NULL, 'e4exwaTQcPiMO1UtgdfubJPntFex4NXPcDuaDnKaxv9LCy4oAxjg9ErXW4mJ', '2017-10-13 09:50:46', '2017-10-26 08:42:58'),
(6, 1, 'Senior', 'senior@mail.ru', '$2y$10$XcEs796R8u0oFVX6J1a03uesfrolR5tUUKQvyZ6dN6wupxWSdsDBy', 'O9Y1LhtKWPdloNPGYljF30Xb0eNLaLnnlr0CCL7c.jpeg', '0', NULL, NULL, NULL, 'OypHwiXOqtV44IcO3NmixKE1GZK77iq9gXJ5AoWiIXl9ZR6miKz18TAi3aDU', '2017-10-13 09:51:25', '2017-10-19 10:38:33'),
(7, 1, 'Hasmik', 'has@g.com', '$2y$10$zP7B0gh8M9Upl6NxD5LJje/v32Tdfhx985jYbbcn5V60w9qrNxOAW', 'gFqMlPJImtvMvV9tzQoJHZbiItZLnC0KODOHivlF.jpeg', '1', NULL, NULL, NULL, '7AUnQqTLLXqYKRIIH02blh0lx0Vn0YetQGwftI8bvJbZAyueXt58SYURMDD6', '2017-10-19 04:49:38', '2017-10-19 10:31:55'),
(8, 1, 'Serob', 'serobyan@gmail.com', '$2y$10$XridEkT8cPNJ68a1AC.j1uwkxR7jKafWSDkoBbVMnqOl5371D1NuO', NULL, '0', NULL, NULL, NULL, 'eqBglKZaUvL6Ojcn1o5Tt7IfYUjDYPEhI3Syy5o7GYy94vYwviPgG9jGqwmt', '2017-10-19 04:50:35', '2017-10-19 04:50:35'),
(9, 1, 'Hovak', 'hovak@mail.ru', '$2y$10$cdxW3A3ZofXa3o38PFdOn.Pi7qrouvFkGthKx1TLKWVUmPyxSezYK', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 1, 'Melik', 'melik@mail.ru', '$2y$10$qvvQFNrw2uMBOkCkuJ5f.O1bWNSJo.se9C.RxflwnJO5s7p7/E7k2', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 1, 'Mek', 'melisak@mail.ru', '$2y$10$PKQ11mwYMz1pcsHvDM8Zmu/0bESo9Bhp8iTqWUnnnnd2EXMEw//Vq', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 1, 'Meik', 'meliak@mail.ru', '$2y$10$B.cq.eaFA3ImN.hCOAAq7OGCkCh7H4LsQVUHMA4qifsbOfgxHPCuW', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `notifications_notifiable_id_notifiable_type_index` (`notifiable_id`,`notifiable_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
